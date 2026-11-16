<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\Announcement;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\ChatbotUnmatched;
use App\Models\Event;
use App\Models\IndigencyApplication;
use App\Models\Project;
use App\Models\ResidencyApplication;
use App\Models\Residents;
use App\Services\EmailDeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminWorkflowQualityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Storage::fake('local');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $this->mock(EmailDeliveryService::class, fn ($m) => $m->shouldReceive('sendText', 'sendView')->zeroOrMoreTimes());
        $role = AdminRole::create(['name' => 'super_admin']);
        $this->actingAs(AdminUser::create(['first_name' => 'Test', 'last_name' => 'Admin', 'username' => 'workflow_admin', 'email' => 'workflow-admin@example.test', 'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active']), 'admin');
    }

    public static function certificateModules(): array
    {
        return [['residency', ResidencyApplication::class], ['indigency', IndigencyApplication::class], ['clearance', BarangayClearance::class]];
    }

    #[DataProvider('certificateModules')]
    public function test_certificate_create_edit_status_archive_and_restore(string $module, string $model): void
    {
        $resident = Residents::factory()->create();
        $payload = ['resident_id' => $resident->id, 'first_name' => $resident->firstname, 'last_name' => $resident->lastname,
            'birthdate' => '1990-01-01', 'birth_place' => 'Malabon', 'gender' => 'male', 'civil_status' => 'single',
            'address' => '25 Camia Street', 'contact_number' => $resident->contact, 'email' => $resident->email,
            'years_residing' => '5-10', 'residency_type' => 'owner', 'household_members' => 3,
            'monthly_income' => 'below 5k', 'purpose' => 'employment',
            'primary_proof' => UploadedFile::fake()->createWithContent('proof.pdf', '%PDF-1.4 synthetic proof')];
        $this->post(route('admin.'.$module.'.store'), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $record = $model::sole();
        $this->assertSame($resident->id, $record->user_id);
        Storage::disk('local')->assertExists($record->primary_proof);
        $this->assertStringStartsWith('private/', $record->primary_proof);
        unset($payload['primary_proof'], $payload['resident_id']);
        $payload['address'] = 'Updated sample address';
        $this->put(route('admin.'.$module.'.update', $record->id), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame($payload['address'], $record->fresh()->address);
        $this->postJson(route('admin.'.$module.'.status', $record->id), ['status' => 'rejected'])->assertUnprocessable()->assertJsonValidationErrors('remarks');
        $this->post(route('admin.'.$module.'.status', $record->id), ['status' => 'approved', 'notify_email' => false, 'notify_sms' => false])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame('approved', $record->fresh()->status);
        $this->delete(route('admin.'.$module.'.destroy', $record->id))->assertSessionHas('success');
        $this->assertSoftDeleted($record);
        $this->post(route('admin.'.$module.'.restore', $record->id))->assertSessionHas('success');
        $this->assertFalse($record->fresh()->trashed());
    }

    public static function contentModules(): array
    {
        return [
            ['announcements', Announcement::class, ['title' => 'Sample announcement', 'content' => 'Sample announcement content.', 'category' => 'services', 'status' => 'published', 'is_featured' => true]],
            ['events', Event::class, ['title' => 'Sample event', 'description' => 'Sample community event.', 'event_date' => '2025-01-01', 'start_time' => '09:00', 'end_time' => '10:00', 'location' => 'Barangay Hall', 'attendees' => 10]],
            ['projects', Project::class, ['title' => 'Sample project', 'description' => 'Sample community project.', 'start_date' => '2026-01-01', 'expected_completion' => '2027-01-01', 'status' => 'ongoing', 'progress' => 25]],
        ];
    }

    #[DataProvider('contentModules')]
    public function test_content_create_edit_archive_and_restore(string $module, string $model, array $payload): void
    {
        $this->post(route('admin.'.$module.'.store'), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $record = $model::sole();
        if ($module === 'announcements') {
            $this->assertTrue($record->is_featured);
        }
        if ($module === 'events') {
            $this->assertSame('past', $record->type);
        }
        $payload['title'] = 'Updated sample title';
        $this->put(route('admin.'.$module.'.update', $record->id), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame($payload['title'], $record->fresh()->title);
        $this->delete(route('admin.'.$module.'.destroy', $record->id))->assertSessionHas('success');
        $this->assertSoftDeleted($record);
        $this->post(route('admin.'.$module.'.restore', $record->id))->assertSessionHas('success');
        $this->assertFalse($record->fresh()->trashed());
    }

    public function test_duplicate_event_at_the_title_limit_stays_usable(): void
    {
        $event = Event::create(['title' => str_repeat('e', 255), 'description' => 'Sample event.', 'event_date' => today(), 'location' => 'Barangay Hall', 'type' => 'upcoming']);
        $this->post(route('admin.events.duplicate', $event->id))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseCount('events', 2);
        $this->assertLessThanOrEqual(255, mb_strlen(Event::latest('id')->first()->title));
    }

    public function test_project_cannot_be_completed_below_one_hundred_percent(): void
    {
        $project = Project::create(['title' => 'Sample', 'description' => 'Sample project.', 'status' => 'ongoing', 'progress' => 25]);
        $this->patchJson(route('admin.projects.progress', $project->id), ['status' => 'completed', 'progress' => 25])->assertUnprocessable()->assertJsonValidationErrors('status');
        $this->assertSame('ongoing', $project->fresh()->status);
        $this->patch(route('admin.projects.progress', $project->id), ['status' => 'completed', 'progress' => 100])->assertSessionHas('success');
        $this->assertSame('completed', $project->fresh()->status);
    }

    public function test_incident_save_edit_and_witness_validation_are_atomic(): void
    {
        $payload = ['reportType' => 'dispute', 'incidentDate' => today()->toDateString(), 'incidentTime' => '09:00',
            'incidentLocation' => 'Sample street', 'incidentDescription' => 'Synthetic report description.',
            'complainantName' => 'Maria Santos', 'complainantContact' => '09123456789', 'complainantAddress' => 'Sample address', 'complainantEmail' => 'maria@example.test', 'confidentiality' => 'low',
            'witnesses' => [['name' => 'Juan Santos', 'contact' => '09987654321', 'statement' => 'Sample witness statement.']]];
        $this->post(route('admin.blotter.store'), $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $report = BlotterReport::sole();
        $this->assertSame('Juan Santos', $report->witnesses()->sole()->name);
        $invalid = $payload;
        $invalid['incidentDescription'] = 'This edit must not be saved.';
        $invalid['witnesses'][0]['contact'] = ['invalid'];
        $this->putJson(route('admin.blotter.update', $report->id), $invalid)->assertUnprocessable()->assertJsonValidationErrors('witnesses.0.contact');
        $this->assertSame($payload['incidentDescription'], $report->fresh()->description);
        $payload['incidentDescription'] = 'Updated synthetic description.';
        $this->put(route('admin.blotter.update', $report->id), $payload)->assertSessionHas('success');
        $this->assertSame($payload['incidentDescription'], $report->fresh()->description);
        $this->delete(route('admin.blotter.destroy', $report->id))->assertSessionHas('success');
        $this->assertSoftDeleted($report);
        $this->post(route('admin.blotter.restore', $report->id))->assertSessionHas('success');
        $this->assertFalse($report->fresh()->trashed());
    }

    public function test_unmatched_chat_questions_are_visible_until_resolved(): void
    {
        $question = ChatbotUnmatched::create(['query' => 'Sample question', 'session_id' => (string) Str::uuid(), 'resolved' => false, 'created_at' => now()]);
        $this->getJson(route('admin.chatbot.stats'))->assertOk()->assertJsonPath('unmatched_count', 1);
        $this->patchJson(route('admin.chatbot.unmatched.resolve', $question->id))->assertOk()->assertJsonPath('success', true);
        $this->assertTrue($question->fresh()->resolved);
        $this->getJson(route('admin.chatbot.stats'))->assertOk()->assertJsonPath('unmatched_count', 0);
    }

    public static function searchablePages(): array
    {
        return array_map(fn ($page) => [$page], ['residents', 'residency', 'indigency', 'barangay-clearance', 'blotter', 'announcements', 'events', 'projects', 'users']);
    }

    #[DataProvider('searchablePages')]
    public function test_search_filters_work_on_the_production_database(string $page): void
    {
        $this->get('/bisig-admin-panel/'.$page.'?'.http_build_query(['search' => 'sample', 'direction' => 'asc']))->assertOk();
    }
}
