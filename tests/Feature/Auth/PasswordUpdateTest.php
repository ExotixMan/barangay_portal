<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated_with_the_current_password(): void
    {
        $resident = Residents::factory()->create();
        $this->actingAs($resident)->post(route('profile.password'), [
            'current_password' => 'Password123!', 'new_password' => 'Changed123!', 'new_password_confirmation' => 'Changed123!',
        ])->assertSessionHasNoErrors()->assertRedirect(route('profile'));
        $this->assertTrue(Hash::check('Changed123!', $resident->fresh()->password));
    }

    public function test_wrong_current_password_cannot_change_password(): void
    {
        $resident = Residents::factory()->create();
        $this->actingAs($resident)->post(route('profile.password'), [
            'current_password' => 'incorrect', 'new_password' => 'Changed123!', 'new_password_confirmation' => 'Changed123!',
        ])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('Password123!', $resident->fresh()->password));
    }
}
