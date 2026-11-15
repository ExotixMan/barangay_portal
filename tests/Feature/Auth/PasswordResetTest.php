<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use App\Services\EmailDeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_request_screen_can_be_rendered(): void
    {
        $this->get('/forgot-password')->assertOk();
    }

    public function test_reset_link_is_sent_through_the_actual_email_service(): void
    {
        $resident = Residents::factory()->create();
        $this->mock(EmailDeliveryService::class, function ($mock) use ($resident) {
            $mock->shouldReceive('sendView')->once()->with($resident->email, \Mockery::type('string'), 'email.reset-password', \Mockery::on(fn ($data) => str_contains($data['url'], '/reset-password/')));
        });
        $this->post('/forgot-password', ['email' => $resident->email])->assertSessionHasNoErrors()->assertSessionHas('status');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $resident = Residents::factory()->create();
        $token = Password::createToken($resident);
        $this->get(route('resident.password.reset', ['token' => $token, 'email' => $resident->email]))->assertOk();
        $this->post('/reset-password', ['email' => $resident->email, 'token' => $token, 'password' => 'Changed123!', 'password_confirmation' => 'Changed123!'])
            ->assertSessionHasNoErrors()->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('Changed123!', $resident->fresh()->password));
        $this->assertFalse(Password::tokenExists($resident, $token));
    }

    public function test_invalid_token_cannot_reset_password(): void
    {
        $resident = Residents::factory()->create();
        $this->post('/reset-password', ['email' => $resident->email, 'token' => 'invalid', 'password' => 'Changed123!', 'password_confirmation' => 'Changed123!'])->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('Password123!', $resident->fresh()->password));
    }
}
