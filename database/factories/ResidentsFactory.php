<?php

namespace Database\Factories;

use App\Models\Residents;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ResidentsFactory extends Factory
{
    protected $model = Residents::class;

    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(), 'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(), 'username' => fake()->unique()->userName(),
            'address' => '25 Camia Street, Hulong Duhat, Malabon City',
            'birthdate' => '1990-01-01', 'gender' => 'Male', 'civil_status' => 'Single',
            'place_of_birth' => 'Malabon', 'contact' => fake()->unique()->numerify('09#########'),
            'password' => Hash::make('Password123!'), 'email_verified_at' => now(),
            'valid_id' => 'private/valid_id/front.pdf', 'valid_id_back' => 'private/valid_id/back.pdf',
            'valid_id_verified' => true, 'valid_id_status' => 'verified', 'phone_verified' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
