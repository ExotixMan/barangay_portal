<?php

namespace Tests\Feature;

use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_displays_the_authenticated_resident(): void
    {
        $resident = Residents::factory()->create();
        $this->actingAs($resident)->get('/profile')->assertOk()->assertSee($resident->firstname);
    }

    public function test_resident_can_update_contact_without_overwriting_locked_identity(): void
    {
        $resident = Residents::factory()->create();
        $this->mock(\App\Services\OtpSmsService::class, fn ($m) => $m->shouldReceive('sendOtp')->once()->andReturn(true));
        $this->actingAs($resident)->post(route('profile.info'), ['contact' => '9123456789', 'firstname' => 'Changed'])
            ->assertSessionHasNoErrors()->assertRedirect(route('profile'));
        $this->assertSame($resident->contact, $resident->fresh()->contact);
        $this->assertSame('09123456789', session('contact_change.contact'));
        $this->assertSame($resident->firstname, $resident->fresh()->firstname);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $this->get('/profile')->assertRedirect(route('login'));
    }
}
