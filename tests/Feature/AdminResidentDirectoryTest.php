<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResidentDirectoryTest extends TestCase
{
    use RefreshDatabase;

    private function signIn(): void
    {
        $role = AdminRole::create(['name' => 'super_admin']);
        $this->actingAs(AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Owner', 'username' => 'owner',
            'email' => 'owner@example.test', 'password' => bcrypt('Password123!'),
            'role_id' => $role->id, 'status' => 'active',
        ]), 'admin');
    }

    public function test_age_groups_include_exact_birthday_boundaries_on_both_databases(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 15)->startOfDay());
        $this->signIn();
        $minor = Residents::factory()->create(['birthdate' => '2008-11-16']);
        $adult = Residents::factory()->create(['birthdate' => '2008-11-15']);
        $olderAdult = Residents::factory()->create(['birthdate' => '1966-11-16']);
        $senior = Residents::factory()->create(['birthdate' => '1966-11-15']);
        Residents::factory()->create(['birthdate' => '1940-01-01'])->delete();

        foreach (['minor' => [$minor->id], 'adult' => [$adult->id, $olderAdult->id], 'senior' => [$senior->id]] as $group => $ids) {
            $this->get(route('admin.residents.index', ['age_range' => $group, 'status' => 'active']))
                ->assertOk()->assertViewHas('residents', fn ($rows) => $rows->pluck('id')->sort()->values()->all() === $ids)
                ->assertViewHas('seniorResidents', 1);
        }
        $this->get(route('admin.residents.index', ['sort' => 'age', 'direction' => 'asc', 'status' => 'active']))
            ->assertOk()->assertViewHas('residents', fn ($rows) => $rows->pluck('id')->all() === [$minor->id, $adult->id, $olderAdult->id, $senior->id]);
    }

    public function test_private_id_links_use_the_authenticated_file_route(): void
    {
        $this->signIn();
        $resident = Residents::factory()->create();
        $this->get(route('admin.residents.index'))->assertOk()
            ->assertSee(route('admin.secure.file', ['path' => $resident->valid_id]), false)
            ->assertDontSee('href="'.asset($resident->valid_id).'"', false);

        $this->mock(\App\Services\SupabaseStorageService::class, function ($mock) {
            $mock->shouldReceive('fetchPrivate')->once()->with('private/valid_id/front.pdf')
                ->andReturn(new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/pdf'], '%PDF-1.4 test')));
        });
        $this->get(route('admin.secure.file', ['path' => $resident->valid_id]))
            ->assertOk()->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
