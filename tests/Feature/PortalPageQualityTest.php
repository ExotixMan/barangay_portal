<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\Residents;
use App\Services\EmailDeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PortalPageQualityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->mock(EmailDeliveryService::class, fn ($m) => $m->shouldReceive('sendText', 'sendView')->zeroOrMoreTimes());
    }

    private function signIn(string $role): void
    {
        if ($role === 'resident') {
            $this->actingAs(Residents::factory()->create(), 'web');
        } elseif ($role === 'admin') {
            $roleRecord = AdminRole::create(['name' => 'super_admin']);
            $this->actingAs(AdminUser::create(['first_name' => 'Review', 'last_name' => 'Admin', 'username' => 'page_admin', 'email' => 'page-admin@example.test', 'password' => bcrypt('Password123!'), 'role_id' => $roleRecord->id, 'status' => 'active']), 'admin');
        }
    }

    public static function pages(): array
    {
        $cases = [];
        foreach (['/', '/history', '/mission&vision', '/barangay-map', '/barangay-officials', '/announcements', '/events-project', '/contacts', '/track-request', '/login', '/register', '/forgot-password', '/reset-password/sample-token', '/chatbot'] as $path) {
            $cases['guest '.$path] = ['guest', $path];
        }
        foreach (['/profile', '/services', '/indigency', '/indigency/form', '/residency', '/residency/form', '/clearance', '/clearance/form', '/incident', '/incident/form', '/track-request'] as $path) {
            $cases['resident '.$path] = ['resident', $path];
        }
        foreach (['dashboard', 'requests', 'residents', 'residency', 'indigency', 'barangay-clearance', 'blotter', 'announcements', 'events', 'projects', 'users', 'permissions', 'chatbot', 'settings', 'settings/backup'] as $path) {
            $cases['admin '.$path] = ['admin', '/bisig-admin-panel/'.$path];
        }

        return $cases;
    }

    #[DataProvider('pages')]
    public function test_each_page_renders_without_a_server_error(string $role, string $path): void
    {
        $this->signIn($role);
        if ($path === '/bisig-admin-panel/permissions') {
            $this->get($path)->assertRedirect();

            return;
        }
        $this->get($path)->assertOk()->assertDontSee('Whoops, something went wrong')->assertDontSee('SQLSTATE[');
    }

    public static function invalidForms(): array
    {
        $cases = [];
        foreach (['/login', '/register', '/forgot-password', '/reset-password'] as $path) {
            $cases[$path] = ['guest', $path];
        }
        foreach (['/profile/info', '/profile/account', '/profile/password', '/profile/report-incorrect-profile', '/indigency/store', '/residency/store', '/clearance/store', '/incident/store', '/indigency/report-incorrect-profile', '/residency/report-incorrect-profile', '/clearance/report-incorrect-profile'] as $path) {
            $cases[$path] = ['resident', $path];
        }
        foreach (['residents/store', 'residency/store', 'indigency/store', 'barangay-clearance/store', 'blotter/store', 'announcements/store', 'events/store', 'projects/store', 'users'] as $path) {
            $cases[$path] = ['admin', '/bisig-admin-panel/'.$path];
        }

        return $cases;
    }

    #[DataProvider('invalidForms')]
    public function test_empty_forms_return_validation_messages(string $role, string $path): void
    {
        $this->signIn($role);
        $response = $this->postJson($path, []);
        if ($response->status() === 302) {
            $response->assertSessionHasErrors();
        } else {
            $response->assertUnprocessable()->assertJsonStructure(['message', 'errors']);
        }
    }

    public static function malformedFilters(): array
    {
        $cases = [];
        foreach (['residents', 'residency', 'indigency', 'barangay-clearance', 'blotter', 'announcements', 'events', 'projects', 'users'] as $page) {
            foreach (['search' => ['unexpected'], 'direction' => ['asc'], 'direction_invalid' => 'unexpected'] as $key => $value) {
                $cases[$page.' '.$key] = ['/bisig-admin-panel/'.$page, [$key === 'direction_invalid' ? 'direction' : $key => $value]];
            }
        }
        $cases['invalid project date'] = ['/bisig-admin-panel/projects', ['from_date' => 'not-a-date']];
        $cases['invalid event year'] = ['/bisig-admin-panel/events', ['year' => 'not-a-year']];

        return $cases;
    }

    #[DataProvider('malformedFilters')]
    public function test_bad_filters_never_become_page_errors(string $path, array $query): void
    {
        $this->signIn('admin');
        $response = $this->getJson($path.'?'.http_build_query($query));
        $this->assertContains($response->status(), [200, 422], $response->getContent());
    }

    public function test_invalid_tracking_input_is_validated(): void
    {
        $this->getJson('/track-request?view[]=bad')->assertUnprocessable();
    }

    public function test_non_numeric_record_id_does_not_cause_a_server_error(): void
    {
        $this->signIn('admin');
        $this->deleteJson('/bisig-admin-panel/residents/not-an-id')->assertNotFound();
    }

    public function test_contact_array_does_not_fail_before_admin_form_validation(): void
    {
        $this->signIn('admin');
        $this->postJson('/bisig-admin-panel/residents/store', ['contact' => ['invalid']])->assertUnprocessable();
    }
}
