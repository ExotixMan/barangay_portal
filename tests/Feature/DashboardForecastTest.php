<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\ResidencyApplication;
use App\Models\Residents;
use App\Services\ForecastService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardForecastTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $permissions = []): AdminUser
    {
        $role = AdminRole::create(['name' => 'dashboard_reviewer', 'display_name' => 'Dashboard reviewer']);
        foreach ($permissions as $name) {
            $permission = AdminPermission::firstOrCreate(['name' => $name], ['display_name' => $name, 'module' => 'dashboard']);
            $role->permissions()->attach($permission);
        }

        return AdminUser::create(['first_name' => 'Test', 'last_name' => 'Admin', 'email' => 'dashboard@example.test',
            'username' => 'dashboard_test', 'password' => bcrypt('Password123!'), 'status' => 'active', 'role_id' => $role->id]);
    }

    public function test_dashboard_load_does_not_run_python_and_excludes_archived_residencies(): void
    {
        $resident = Residents::factory()->create();
        $application = ResidencyApplication::create([
            'user_id' => $resident->id, 'reference_number' => 'RES-2026-TEST', 'first_name' => 'Test', 'last_name' => 'Resident',
            'birthdate' => '1990-01-01', 'gender' => 'Male', 'civil_status' => 'Single', 'birth_place' => 'Malabon',
            'address' => 'Test address', 'years_residing' => '5-10', 'residency_type' => 'owner',
            'contact_number' => '09123456789', 'email' => $resident->email, 'household_members' => 2,
            'purpose' => 'employment', 'government_id' => 'private/id.pdf', 'status' => 'processing',
        ]);
        $application->delete();
        $this->mock(ForecastService::class, function ($m) {
            $m->shouldNotReceive('data');
            $m->shouldNotReceive('updatedAt');
        });
        $this->actingAs($this->admin(['view_dashboard']), 'admin')->get(route('admin.dashboard.index'))
            ->assertOk()->assertViewHas('stats', fn ($stats) => $stats['pendingRequests'] === 0);
    }

    public function test_forecast_endpoint_filters_sections_by_permissions_and_labels_stale_data(): void
    {
        $this->mock(ForecastService::class, function ($m) {
            $m->shouldReceive('data')->with(true)->andReturn(['forecast_metadata' => [], 'request_forecast' => [],
                'applications' => [], 'residents' => ['total_residents' => 100], 'blotter_reports' => ['private' => true]]);
            $m->shouldReceive('isStale')->andReturn(true);
        });
        $this->actingAs($this->admin(['view_dashboard']), 'admin')->getJson(route('admin.dashboard.forecast'))
            ->assertOk()->assertJsonPath('forecast_metadata.stale', true)
            ->assertJsonMissingPath('residents')->assertJsonMissingPath('blotter_reports')->assertJsonMissingPath('applications');
    }

    public function test_refresh_failure_has_clear_message_without_internal_error_details(): void
    {
        $this->mock(ForecastService::class, fn ($m) => $m->shouldReceive('refresh')->andThrow(new \RuntimeException('internal-python-traceback')));
        $this->actingAs($this->admin(['view_dashboard', 'view_forecast']), 'admin')->postJson(route('admin.dashboard.refresh_forecast'))
            ->assertStatus(503)->assertDontSee('internal-python-traceback')->assertJsonPath('success', false);
    }
}
