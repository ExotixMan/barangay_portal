<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\ResidentProfileCorrectionConcern;
use App\Models\Residents;
use App\Services\EmailDeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProfileCorrectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->mock(EmailDeliveryService::class, fn ($m) => $m->shouldReceive('sendText')->zeroOrMoreTimes());
    }

    private function admin(): AdminUser
    {
        $role = AdminRole::create(['name' => 'super_admin']);

        return AdminUser::create(['first_name' => 'Review', 'last_name' => 'Admin', 'username' => 'review_admin', 'email' => 'review-admin@example.test', 'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active']);
    }

    private function concern(Residents $resident, string $field = 'Full name', string $requested = 'María Ana Peña De la Cruz Jr.'): ResidentProfileCorrectionConcern
    {
        return ResidentProfileCorrectionConcern::create(['resident_id' => $resident->id, 'service' => 'Profile', 'field_name' => $field, 'current_value' => $resident->full_name, 'correct_value' => $requested, 'status' => 'pending']);
    }

    private function decision(ResidentProfileCorrectionConcern $concern): array
    {
        return ['correction_id' => $concern->id, 'resolution_status' => 'resolved', 'remarks' => 'Verified against supporting records.', 'notify_email' => false, 'name_firstname' => 'María Ana', 'name_middlename' => 'Peña', 'name_lastname' => 'De la Cruz', 'name_suffix' => 'Jr.'];
    }

    public static function correctionSources(): array
    {
        return [
            'profile canonical' => ['profile', 'full_name'],
            'profile existing label' => ['profile', 'Full name'],
            'indigency' => ['indigency', 'Full name'],
            'residency' => ['residency', 'Full name'],
            'clearance' => ['clearance', 'Full name'],
        ];
    }

    #[DataProvider('correctionSources')]
    public function test_full_name_request_can_be_submitted_reviewed_and_unlock_service_forms(string $source, string $field): void
    {
        $resident = Residents::factory()->create(['firstname' => 'Ana', 'middlename' => 'Old', 'lastname' => 'Santos', 'suffix' => null]);
        $originalName = $resident->full_name;
        $this->actingAs($resident, 'web')->post(route($source.'.reportIncorrectProfile'), ['field_name' => $field, 'correct_value' => 'María Ana Peña De la Cruz Jr.', 'current_value' => 'Forged current name'])->assertSessionHasNoErrors();
        $concern = ResidentProfileCorrectionConcern::sole();
        $this->assertSame($originalName, $concern->current_value);
        $this->assertSame('pending', $concern->status);
        $this->assertSame($originalName, $resident->fresh()->full_name);
        $this->get(route('residency.form'))->assertRedirect(route('profile'));

        $admin = $this->admin();
        $this->actingAs($admin, 'admin')->get(route('admin.residents.index'))->assertOk()->assertSee('name_firstname')->assertSee('Confirm the corrected name');
        $this->put(route('admin.residents.update', $resident->id), $this->decision($concern))->assertSessionHasNoErrors()->assertSessionHas('success');
        $resident->refresh();
        $concern->refresh();
        $this->assertSame(['María Ana', 'Peña', 'De la Cruz', 'Jr.'], [$resident->firstname, $resident->middlename, $resident->lastname, $resident->suffix]);
        $this->assertSame('resolved', $concern->status);
        $this->assertSame($admin->id, $concern->reviewed_by);
        Auth::guard('admin')->logout();
        $this->actingAs($resident, 'web')->get(route('residency.form'))->assertOk()->assertSee('María Ana');
    }

    public function test_full_name_approval_requires_parts_without_guessing(): void
    {
        $resident = Residents::factory()->create();
        $original = $resident->full_name;
        $concern = $this->concern($resident);
        $payload = ['correction_id' => $concern->id, 'resolution_status' => 'resolved', 'remarks' => 'Review name parts first.'];
        $this->actingAs($this->admin(), 'admin')->from(route('admin.residents.index'))->put(route('admin.residents.update', $resident->id), $payload)->assertSessionHasErrors(['name_firstname', 'name_lastname']);
        $this->assertSame($original, $resident->fresh()->full_name);
        $this->assertSame('pending', $concern->fresh()->status);
        $this->get(route('admin.residents.index'))->assertOk()->assertSee('Please check this correction decision.')->assertSee('errorCorrectionId');
    }

    public function test_mismatched_name_parts_cannot_resolve_a_request(): void
    {
        $resident = Residents::factory()->create();
        $original = $resident->full_name;
        $concern = $this->concern($resident);
        $payload = $this->decision($concern);
        $payload['name_lastname'] = 'Different Family';
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), $payload)->assertSessionHasErrors('name_firstname');
        $this->assertSame($original, $resident->fresh()->full_name);
        $this->assertSame('pending', $concern->fresh()->status);
    }

    public function test_invalid_name_characters_leave_the_request_and_resident_unchanged(): void
    {
        $resident = Residents::factory()->create();
        $original = $resident->full_name;
        $concern = $this->concern($resident);
        $payload = $this->decision($concern);
        $payload['name_firstname'] = 'Name<script>';
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), $payload)->assertSessionHasErrors('name_firstname');
        $this->assertSame($original, $resident->fresh()->full_name);
        $this->assertSame('pending', $concern->fresh()->status);
    }

    public function test_middle_name_and_suffix_can_be_cleared_without_splitting_compound_surnames(): void
    {
        $resident = Residents::factory()->create(['middlename' => 'Old', 'suffix' => 'Sr.']);
        $concern = $this->concern($resident, 'Full name', 'María Ana De la Cruz');
        $payload = $this->decision($concern);
        $payload['name_middlename'] = '';
        $payload['name_suffix'] = '';
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertNull($resident->fresh()->middlename);
        $this->assertNull($resident->fresh()->suffix);
        $this->assertSame('De la Cruz', $resident->fresh()->lastname);
    }

    public function test_rejection_does_not_require_or_change_name_parts(): void
    {
        $resident = Residents::factory()->create();
        $original = $resident->full_name;
        $concern = $this->concern($resident);
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), ['correction_id' => $concern->id, 'resolution_status' => 'rejected', 'remarks' => 'Supporting documents do not match.'])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame($original, $resident->fresh()->full_name);
        $this->assertSame('rejected', $concern->fresh()->status);
    }

    public function test_reviewed_full_name_request_can_be_resolved_only_once(): void
    {
        $resident = Residents::factory()->create();
        $concern = $this->concern($resident);
        $concern->update(['status' => 'reviewed']);
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), $this->decision($concern))->assertSessionHas('success');
        $this->put(route('admin.residents.update', $resident->id), $this->decision($concern))->assertSessionHas('error');
        $this->assertSame('María Ana Peña De la Cruz Jr.', $resident->fresh()->full_name);
    }

    public function test_individual_name_corrections_accept_unicode_and_apostrophes(): void
    {
        $resident = Residents::factory()->create();
        $concern = $this->concern($resident, 'lastname', 'Peña O’Neil');
        $this->actingAs($this->admin(), 'admin')->put(route('admin.residents.update', $resident->id), ['correction_id' => $concern->id, 'resolution_status' => 'resolved', 'remarks' => 'Verified spelling.'])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame('Peña O’Neil', $resident->fresh()->lastname);
    }

    public function test_name_and_decision_are_rolled_back_together_if_saving_the_decision_fails(): void
    {
        $resident = Residents::factory()->create();
        $original = $resident->full_name;
        $concern = $this->concern($resident);
        $this->actingAs($this->admin(), 'admin');
        ResidentProfileCorrectionConcern::updating(function () {
            throw new \RuntimeException('Synthetic decision persistence failure.');
        });
        try {
            $this->putJson(route('admin.residents.update', $resident->id), $this->decision($concern))->assertStatus(500);
            $this->assertSame($original, $resident->fresh()->full_name);
            $this->assertSame('pending', $concern->fresh()->status);
        } finally {
            ResidentProfileCorrectionConcern::flushEventListeners();
        }
    }
}
