<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_verified_residents_can_login_with_username_and_password(): void
    {
        $resident = Residents::factory()->create();
        $this->post('/login', ['login' => $resident->username, 'password' => 'Password123!'])
            ->assertSessionHasNoErrors()->assertRedirect(route('barangay_system.index'));
        $this->assertAuthenticatedAs($resident);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $resident = Residents::factory()->create();
        $this->post('/login', ['login' => $resident->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_residents_waiting_for_id_approval_cannot_login(): void
    {
        $resident = Residents::factory()->create(['valid_id_verified' => false, 'valid_id_status' => 'for_verification']);
        $this->post('/login', ['login' => $resident->email, 'password' => 'Password123!'])->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_residents_can_logout(): void
    {
        $resident = Residents::factory()->create();
        $this->actingAs($resident)->post(route('logout.res'))->assertRedirect('/login');
        $this->assertGuest();
    }
}
