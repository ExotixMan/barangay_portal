<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use App\Services\AdminSettingsService;
use App\Services\OtpSmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk();
    }

    public static function captchaModes(): array
    {
        return ['disabled' => [false], 'enabled' => [true]];
    }

    private function captchaSettings(bool $enabled): void
    {
        $this->mock(AdminSettingsService::class, function ($mock) use ($enabled) {
            $mock->shouldReceive('get')->andReturnUsing(fn ($key, $default = null) => match ($key) {
                'security.captcha_enabled' => $enabled,
                'security.recaptcha_secret_key' => 'test-only-captcha-secret',
                default => $default,
            });
        });
        Http::preventStrayRequests();
    }

    private function registrationData(): array
    {
        return [
            'firstname' => 'Test', 'lastname' => 'Resident', 'email' => 'new@example.test',
            'username' => 'new_resident', 'address' => '25 Camia Street, Hulong Duhat',
            'birthdate' => '1990-01-01', 'gender' => 'Male', 'civil_status' => 'Single',
            'place_of_birth' => 'Malabon', 'contact' => '09123456789',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!', 'terms' => 'on',
            'valid_id' => UploadedFile::fake()->createWithContent('front.pdf', '%PDF-1.4 front document'),
            'valid_id_back' => UploadedFile::fake()->createWithContent('back.pdf', '%PDF-1.4 back document'),
        ];
    }

    #[DataProvider('captchaModes')]
    public function test_registration_saves_a_pending_resident_and_requires_otp(bool $captchaEnabled): void
    {
        Storage::fake('local');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $this->mock(OtpSmsService::class, fn ($m) => $m->shouldReceive('sendOtp')->once()->andReturn(true));
        $this->captchaSettings($captchaEnabled);
        Http::fake(['www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true])]);
        $data = $this->registrationData();
        if ($captchaEnabled) {
            $data['g-recaptcha-response'] = 'test-only-captcha-token';
        }
        $this->post('/register', $data)->assertSessionHasNoErrors()->assertRedirect(route('otp.form'))->assertSessionHas('resident_otp_hash');
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

    public static function invalidCaptchas(): array
    {
        return ['missing token' => [null, 200], 'rejected token' => ['invalid', 200], 'provider unavailable' => ['invalid', 503]];
    }

    #[DataProvider('invalidCaptchas')]
    public function test_enabled_captcha_blocks_unverified_registration(?string $token, int $status): void
    {
        $this->captchaSettings(true);
        Http::fake(['www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false], $status)]);
        $this->mock(OtpSmsService::class, fn ($mock) => $mock->shouldNotReceive('sendOtp'));
        $data = $this->registrationData();
        if ($token !== null) {
            $data['g-recaptcha-response'] = $token;
        }
        $this->post('/register', $data)->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseCount('residents', 0);
        $this->assertGuest();
    }
}
