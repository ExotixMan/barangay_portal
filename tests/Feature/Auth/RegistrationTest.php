<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use App\Services\OtpSmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_registration_saves_a_pending_resident_and_requires_otp(): void
    {
        Storage::fake('local');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $this->mock(OtpSmsService::class, fn ($m) => $m->shouldReceive('sendOtp')->once()->andReturn(true));
        $this->post('/register', [
            'firstname' => 'Test', 'lastname' => 'Resident', 'email' => 'new@example.test',
            'username' => 'new_resident', 'address' => '25 Camia Street, Hulong Duhat',
            'birthdate' => '1990-01-01', 'gender' => 'Male', 'civil_status' => 'Single',
            'place_of_birth' => 'Malabon', 'contact' => '09123456789',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!', 'terms' => 'on',
            'valid_id' => UploadedFile::fake()->createWithContent('front.pdf', '%PDF-1.4 front document'),
            'valid_id_back' => UploadedFile::fake()->createWithContent('back.pdf', '%PDF-1.4 back document'),
        ])->assertSessionHasNoErrors()->assertRedirect(route('otp.form'))->assertSessionHas('resident_otp_hash');
        $resident = Residents::where('email', 'new@example.test')->firstOrFail();
        $this->assertFalse($resident->valid_id_verified);
        $this->assertFalse($resident->phone_verified);
        $this->assertFalse($resident->is_pwd);
        Storage::disk('local')->assertExists($resident->valid_id);
        $this->assertGuest();
    }

    public function test_incomplete_registration_does_not_create_an_account(): void
    {
        $this->post('/register', ['firstname' => 'Test', 'email' => 'new@example.test'])->assertSessionHasErrors(['valid_id', 'password']);
        $this->assertDatabaseCount('residents', 0);
    }
}
