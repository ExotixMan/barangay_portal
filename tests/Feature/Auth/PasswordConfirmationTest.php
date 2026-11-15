<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_change_a_resident_password(): void
    {
        $this->post(route('profile.password'), ['current_password' => 'Password123!', 'new_password' => 'Changed123!'])->assertRedirect(route('login'));
    }

    public function test_guest_cannot_change_account_details(): void
    {
        $this->post(route('profile.account'), ['email' => 'attacker@example.test'])->assertRedirect(route('login'));
    }
}
