<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\ResidentProfileCorrectionConcern;
use App\Models\Residents;
use App\Services\EmailDeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WorkflowQualityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->mock(EmailDeliveryService::class, fn ($m) => $m->shouldReceive('sendText', 'sendView')->zeroOrMoreTimes());
    }

    private function admin(string $role = 'super_admin'): AdminUser
    {
        return AdminUser::create(['first_name' => 'Test', 'last_name' => 'Reviewer', 'username' => 'quality_admin', 'email' => 'quality-admin@example.test', 'password' => bcrypt('Password123!'), 'role_id' => AdminRole::create(['name' => $role])->id, 'status' => 'active']);
    }

    private function concern(Residents $resident, string $field, string $value, string $status = 'pending'): ResidentProfileCorrectionConcern
    {
        return ResidentProfileCorrectionConcern::create(['resident_id' => $resident->id, 'service' => 'Profile', 'field_name' => $field, 'current_value' => 'Old value', 'correct_value' => $value, 'status' => $status]);
    }

    private function approve(Residents $resident, ResidentProfileCorrectionConcern $concern)
    {
        return $this->actingAs($this->admin(), 'admin')->from(route('admin.residents.index'))->put(route('admin.residents.update', $resident->id), ['correction_id' => $concern->id, 'resolution_status' => 'resolved', 'remarks' => 'Checked supporting ID.', 'notify_email' => false]);
    }

    public static function openStatuses(): array
    {
        return array_map(fn ($status) => [$status], ['pending', 'reviewed', 'under_review', 'in_review', 'reviewing', '']);
    }

    #[DataProvider('openStatuses')]
    public function test_open_corrections_block_services_and_duplicate_corrections(string $status): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);
        $resident = Residents::factory()->create();
        $this->concern($resident, 'firstname', 'Updated', $status);
        $this->actingAs($resident, 'web');
        foreach (['indigency', 'residency', 'clearance'] as $service) {
            $this->get(route($service.'.form'))->assertRedirect(route('profile'));
            $this->postJson(route($service.'.store'), [])->assertStatus(423);
            $this->post(route($service.'.reportIncorrectProfile'), ['field_name' => 'Full name', 'correct_value' => 'Sample Name'])->assertSessionHas('warning');
        }
        $this->post(route('profile.reportIncorrectProfile'), ['field_name' => 'firstname', 'correct_value' => 'Updated'])->assertSessionHas('warning');
        $this->assertDatabaseCount('resident_profile_correction_concerns', 1);
    }

    public static function invalidResidentCorrections(): array
    {
        $cases = [];
        foreach (['profile', 'indigency', 'residency', 'clearance'] as $source) {
            foreach (['Birthdate' => 'yesterday', 'Complete address' => str_repeat('a', 256), 'Full name' => 'Name<script>'] as $field => $value) {
                $mapped = $source === 'profile' ? ['Birthdate' => 'birthdate', 'Complete address' => 'address', 'Full name' => 'full_name'][$field] : $field;
                $cases[$source.' '.$field] = [$source, $mapped, $value];
            }
        }
        $cases['invalid email'] = ['residency', 'Email address', 'not-an-email'];
        $cases['invalid mobile'] = ['clearance', 'Contact number', '12345'];

        return $cases;
    }

    #[DataProvider('invalidResidentCorrections')]
    public function test_unusable_corrections_are_validated_before_locking_services(string $source, string $field, string $value): void
    {
        $resident = Residents::factory()->create();
        $this->actingAs($resident, 'web')->postJson(route($source.'.reportIncorrectProfile'), ['field_name' => $field, 'correct_value' => $value])
            ->assertUnprocessable()->assertJsonValidationErrors('correct_value');
        $this->assertDatabaseCount('resident_profile_correction_concerns', 0);
        $this->get(route('residency.form'))->assertOk();
    }

    public function test_email_address_label_is_approved_and_the_new_address_requires_verification(): void
    {
        $resident = Residents::factory()->create();
        $concern = $this->concern($resident, 'Email address', 'Updated@Example.Test');
        $this->approve($resident, $concern)->assertSessionHas('success');
        $this->assertSame('updated@example.test', $resident->fresh()->email);
        $this->assertNull($resident->fresh()->email_verified_at);
        $this->assertSame('resolved', $concern->fresh()->status);
    }

    public function test_complete_address_label_can_be_approved(): void
    {
        $resident = Residents::factory()->create();
        $concern = $this->concern($resident, 'Complete address', '25 Camia Street, Hulong Duhat');
        $this->approve($resident, $concern)->assertSessionHas('success');
        $this->assertSame($concern->correct_value, $resident->fresh()->address);
    }

    public static function invalidCorrections(): array
    {
        return [
            'case insensitive email collision' => ['Email address', 'TAKEN@EXAMPLE.TEST'],
            'case insensitive username collision' => ['username', 'TAKEN_USER'],
            'contact collision' => ['Contact number', '09123456789'],
            'overlong address' => ['Complete address', str_repeat('a', 256)],
            'overlong name' => ['firstname', str_repeat('a', 256)],
            'non date birthdate' => ['birthdate', 'yesterday'],
            'future birthdate' => ['birthdate', '2099-01-01'],
        ];
    }

    #[DataProvider('invalidCorrections')]
    public function test_unusable_corrections_do_not_resolve_or_change_the_profile(string $field, string $value): void
    {
        Residents::factory()->create(['email' => 'taken@example.test', 'username' => 'taken_user', 'contact' => '09123456789']);
        $resident = Residents::factory()->create();
        $original = $resident->fresh()->getRawOriginal();
        $concern = $this->concern($resident, $field, $value);
        $this->approve($resident, $concern)->assertSessionHas('error');
        $this->assertSame('pending', $concern->fresh()->status);
        $this->assertSame($original, $resident->fresh()->getRawOriginal());
    }

    public function test_a_corrected_phone_does_not_inherit_the_old_numbers_otp_verification(): void
    {
        $resident = Residents::factory()->create(['contact' => '09123456789', 'phone_verified' => true, 'phone_otp' => bcrypt('123456'), 'phone_otp_expires_at' => now()->addMinutes(5)]);
        $concern = $this->concern($resident, 'Contact number', '09987654321');
        $this->approve($resident, $concern)->assertSessionHas('success');
        $this->assertSame('09987654321', $resident->fresh()->contact);
        $this->assertFalse($resident->fresh()->phone_verified);
        $this->assertNull($resident->fresh()->phone_otp);
        $this->assertNull($resident->fresh()->phone_otp_expires_at);
    }

    public function test_direct_permissions_choose_an_accessible_admin_landing_page(): void
    {
        $admin = $this->admin('limited_staff');
        $permission = AdminPermission::create(['name' => 'view_residents', 'display_name' => 'View residents', 'module' => 'residents']);
        $admin->permissions()->attach($permission);
        $this->post('/login', ['login' => $admin->username, 'password' => 'Password123!'])->assertRedirect(route('admin.residents.index'));
        $this->get('/')->assertRedirect(route('admin.residents.index'));
        $this->get(route('admin.index'))->assertRedirect(route('admin.residents.index'));
        $this->get(route('admin.residents.index'))->assertOk();
        $this->get(route('admin.dashboard.index'))->assertForbidden();
    }

    public function test_malformed_audit_filters_return_validation_and_an_html_request_drops_invalid_filters(): void
    {
        $this->actingAs($this->admin(), 'admin');
        foreach (['log_date' => 'invalid', 'log_user_id' => 'invalid', 'selected_role' => 'invalid', 'logs_page' => -1] as $key => $value) {
            $this->getJson(route('admin.users.index', [$key => $value]))->assertUnprocessable()->assertJsonValidationErrors($key);
        }
        $this->get(route('admin.users.index', ['log_date' => 'invalid']))->assertRedirect(route('admin.users.index'))->assertSessionHasErrors('log_date');
        $this->get(route('admin.users.index'))->assertOk()->assertSee('log date');
    }

    public static function certificateModules(): array
    {
        return [['residency'], ['indigency'], ['clearance']];
    }

    #[DataProvider('certificateModules')]
    public function test_admin_certificates_reject_a_future_birthday(string $module): void
    {
        $this->actingAs($this->admin(), 'admin')->postJson(route('admin.'.$module.'.store'), ['birthdate' => '2099-01-01'])->assertUnprocessable()->assertJsonValidationErrors('birthdate');
    }

    public function test_out_of_range_legacy_certificate_numbers_are_a_missing_file_not_a_database_error(): void
    {
        $this->actingAs($this->admin(), 'admin')->get(route('admin.documents.preview_file', 'certificate_999999999999999999999999.pdf'))->assertNotFound();
    }

    public function test_malformed_and_missing_notification_ids_are_missing_records_not_database_errors(): void
    {
        $this->actingAs($this->admin(), 'admin');
        foreach (['invalid', '999999999999999999999999999', '00000000-0000-0000-0000-000000000000'] as $id) {
            $this->getJson(route('admin.notifications.open', $id))->assertNotFound();
            $this->postJson(route('admin.notifications.read', $id))->assertNotFound();
        }
    }

    public static function errorStatuses(): array
    {
        return [[403, 'Access denied'], [404, 'Page not found'], [419, 'Your session has expired'], [429, 'Please wait a moment'], [500, 'We could not complete that action'], [503, 'The portal is temporarily unavailable']];
    }

    #[DataProvider('errorStatuses')]
    public function test_error_pages_offer_recovery_without_showing_internal_exception_details(int $status, string $title): void
    {
        config(['app.debug' => false]);
        Route::get('/quality-error', function () use ($status) {
            if ($status === 500) {
                throw new \RuntimeException('Private database diagnostic marker');
            }
            abort($status, 'Private database diagnostic marker');
        });
        $this->get('/quality-error')->assertStatus($status)->assertSee($title)->assertSee('Go to home')->assertDontSee('Private database diagnostic marker')->assertDontSee('SQLSTATE[');
    }
}
