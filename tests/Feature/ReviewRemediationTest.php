<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\Residents;
use App\Services\EmailDeliveryService;
use App\Services\OtpSmsService;
use App\Services\SupabaseStorageService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReviewRemediationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Mail::fake();
        Notification::fake();
    }

    private function admin(array $permissions = []): AdminUser
    {
        $role = AdminRole::create(['name' => 'review_staff', 'display_name' => 'Review Staff']);
        foreach ($permissions as $permission) {
            $p = AdminPermission::create(['name' => $permission, 'display_name' => $permission, 'module' => 'review']);
            $role->permissions()->attach($p);
        }

        return AdminUser::create(['first_name' => 'Review', 'last_name' => 'Staff', 'username' => 'review_staff', 'email' => 'staff@example.test', 'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active']);
    }

    private function indigency(): int
    {
        return DB::table('indigency_applications')->insertGetId(['reference_number' => 'IND-REVIEW-001', 'first_name' => 'Review', 'last_name' => 'Resident', 'birthdate' => '1990-01-01', 'gender' => 'Male', 'address' => 'Example address', 'contact_number' => '09123456789', 'email' => 'resident@example.test', 'monthly_income' => 'below 5k', 'household_members' => 2, 'purpose' => 'medical', 'valid_id_path' => 'private/valid_id/review.png', 'status' => 'processing', 'created_at' => now(), 'updated_at' => now()]);
    }

    private function incident(): array
    {
        return ['reportType' => 'dispute', 'incidentDate' => now()->subDay()->toDateString(), 'incidentTime' => '10:00', 'incidentLocation' => 'Example Street', 'incidentDescription' => 'A synthetic report for validation review.', 'complainantName' => 'Review Resident', 'complainantContact' => '09123456789', 'complainantAddress' => 'Example address', 'complainantEmail' => 'review@example.test', 'confidentiality' => 'high'];
    }

    public function test_private_files_require_the_owning_modules_permission_before_storage_access(): void
    {
        $this->indigency();
        $this->actingAs($this->admin(), 'admin');
        $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldNotReceive('fetchPrivate'));
        $this->get(route('admin.indigency.index'))->assertForbidden();
        $this->get(route('admin.secure.file', ['path' => 'private/valid_id/review.png']))->assertForbidden();
    }

    public function test_authorized_private_document_is_readable_and_uncacheable(): void
    {
        $this->indigency();
        $this->actingAs($this->admin(['view_indigency']), 'admin');
        $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldReceive('fetchPrivate')->once()->with('private/valid_id/review.png')->andReturn(new \Illuminate\Http\Client\Response(new Response(200, ['Content-Type' => 'image/png'], 'SYNTHETIC_DOCUMENT'))));
        $response = $this->get(route('admin.secure.file', ['path' => 'private/valid_id/review.png']))->assertOk()->assertSee('SYNTHETIC_DOCUMENT');
        $this->assertTrue($response->headers->hasCacheControlDirective('no-store'));
        $this->get(route('admin.secure.file', ['path' => 'private/valid_id/unlinked.png']))->assertForbidden();
        $this->get(route('admin.secure.file', ['path' => 'private/valid_id/../../.env']))->assertNotFound();
    }

    public function test_guest_cannot_access_private_id(): void
    {
        $this->getJson(route('admin.secure.file', ['path' => 'private/valid_id/review.png']))->assertUnauthorized();
    }

    public function test_sort_direction_cannot_execute_sql_syntax(): void
    {
        $this->indigency();
        $this->actingAs($this->admin(['view_indigency']), 'admin');
        DB::enableQueryLog();
        $this->getJson(route('admin.indigency.index', ['sort' => 'monthly_income', 'direction' => 'ASC, (SELECT 1) /* review_marker */']))->assertUnprocessable();
        $sql = implode("\n", array_column(DB::getQueryLog(), 'query'));
        DB::disableQueryLog();
        $this->assertStringNotContainsString('review_marker', $sql);
    }

    public function test_case_insensitive_account_updates_and_legacy_logins(): void
    {
        $r = Residents::factory()->create(['password' => bcrypt('Password123!')]);
        $this->actingAs($r)->post('/profile/account', ['username' => 'ReviewMixedCase', 'email' => strtoupper($r->email)])->assertSessionHasNoErrors();
        $this->assertSame('reviewmixedcase', $r->fresh()->username);
        // Old mixed-case database records must remain usable without a data rewrite.
        DB::table('residents')->where('id', $r->id)->update(['username' => 'LegacyMixedCase']);
        Auth::guard('web')->logout();
        $this->post('/login', ['login' => 'LEGACYMIXEDCASE', 'password' => 'Password123!'])->assertSessionHasNoErrors();
    }

    public function test_case_folded_duplicates_are_rejected(): void
    {
        $a = Residents::factory()->create();
        $b = Residents::factory()->create();
        $this->actingAs($b)->post('/profile/account', ['username' => strtoupper($a->username), 'email' => strtoupper($a->email)])->assertSessionHasErrors(['username', 'email']);
        $this->assertSame($b->username, $b->fresh()->username);
    }

    public function test_duplicate_phone_number_is_rejected_before_sending(): void
    {
        $a = Residents::factory()->create();
        $b = Residents::factory()->create();
        $this->mock(OtpSmsService::class, fn ($m) => $m->shouldNotReceive('sendOtp'));
        $this->actingAs($b)->post('/profile/info', ['contact' => substr($a->contact, 1)])->assertSessionHasErrors('contact');
        $this->assertSame($b->contact, $b->fresh()->contact);
    }

    public function test_contact_change_requires_a_bound_single_use_otp(): void
    {
        $r = Residents::factory()->create();
        $otp = null;
        $this->mock(OtpSmsService::class, function ($m) use (&$otp) {
            $m->shouldReceive('sendOtp')->once()->andReturnUsing(function ($recipient, $code, $context) use (&$otp) {
                $this->assertSame('09999999999', $recipient->contact);
                $this->assertSame('profile_contact', $context);
                $otp = (string) $code;

                return true;
            });
        });
        $this->actingAs($r)->post('/profile/info', ['contact' => '9999999999'])->assertSessionHasNoErrors();
        $this->assertSame($r->contact, $r->fresh()->contact);
        $this->assertNotSame($otp, session('contact_change.hash'));
        $this->get('/profile')->assertOk()->assertSee('contactOtp');
        $this->post('/profile/contact/verify', ['contact_otp' => $otp])->assertSessionHasNoErrors()->assertSessionMissing('contact_change');
        $this->assertSame('09999999999', $r->fresh()->contact);
        $this->assertTrue($r->fresh()->phone_verified);
        $this->post('/profile/contact/verify', ['contact_otp' => $otp])->assertSessionHasErrors('contact_otp');
    }

    public function test_failed_or_expired_contact_challenge_preserves_current_number(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r)->withSession(['contact_change' => ['resident_id' => $r->id, 'contact' => '09999999999', 'hash' => Hash::make('123456'), 'expires_at' => now()->subMinute()->timestamp, 'attempts' => 0]])->post('/profile/contact/verify', ['contact_otp' => '123456'])->assertSessionHasErrors('contact_otp')->assertSessionMissing('contact_change');
        $this->assertSame($r->contact, $r->fresh()->contact);
    }

    public function test_indigency_rejects_unsupported_purpose_and_income(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r)->post('/indigency/store', ['profile_confirmed' => '1', 'monthly_income' => 'not-a-valid-bracket', 'household_members' => 2, 'purpose' => 'not-a-valid-purpose', 'terms' => '1', 'privacy' => '1', 'pickup' => '1'])->assertSessionHasErrors(['monthly_income', 'purpose']);
        $this->assertDatabaseCount('indigency_applications', 0);
    }

    public function test_incident_consents_are_required_and_unicode_names_are_accepted(): void
    {
        $r = Residents::factory()->create();
        $payload = $this->incident();
        $payload['complainantName'] = 'Ana Peña';
        $payload['respondentName'] = 'José O’Neil';
        $this->actingAs($r)->post('/incident/store', $payload)->assertSessionHasErrors(['declareTruth', 'agreePrivacy', 'consentProcessing']);
        $this->assertDatabaseCount('blotter_reports', 0);
        session()->forget('errors');
        $this->post('/incident/store', $payload + ['declareTruth' => '1', 'agreePrivacy' => '1', 'consentProcessing' => '1'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('blotter_reports', ['complainant_name' => 'Ana Peña']);
    }

    public function test_indigency_rejections_are_visible(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r)->from('/indigency/form')->post('/indigency/store', [])->assertSessionHasErrors('monthly_income');
        $message = session('errors')->first('monthly_income');
        $this->get('/indigency/form')->assertOk()->assertSee($message)->assertSee('submissionErrors');
    }

    public function test_public_availability_does_not_disclose_email_or_phone_and_is_throttled(): void
    {
        $r = Residents::factory()->create();
        foreach (['email' => $r->email, 'contact' => $r->contact] as $field => $value) {
            $known = $this->getJson('/register/check-availability?'.http_build_query(compact('field', 'value')))->assertOk()->json();
            $unknownValue = $field === 'email' ? 'unknown@example.test' : '09999999999';
            $unknown = $this->getJson('/register/check-availability?'.http_build_query(['field' => $field, 'value' => $unknownValue]))->assertOk()->json();
            $this->assertSame($known, $unknown);
        }
        for ($i = 0; $i < 17; $i++) {
            $response = $this->getJson('/register/check-availability?field=username&value=example');
        }
        $response->assertTooManyRequests();
    }

    public function test_reset_and_verification_links_use_the_canonical_host(): void
    {
        config(['app.url' => 'https://canonical.example.test']);
        $r = Residents::factory()->create();
        $captured = [];
        $this->mock(EmailDeliveryService::class, function ($m) use (&$captured) {
            $m->shouldReceive('sendView')->twice()->andReturnUsing(function ($email, $subject, $view, $data) use (&$captured) {
                $captured[] = $data['url'];
            });
        });
        $this->withHeaders(['Host' => 'untrusted-review.example', 'X-Forwarded-Host' => 'untrusted-review.example', 'X-Forwarded-Proto' => 'https'])->post('/forgot-password', ['email' => $r->email])->assertSessionHasNoErrors();
        $r->sendEmailVerificationNotification();
        foreach ($captured as $url) {
            $this->assertSame('canonical.example.test', parse_url($url, PHP_URL_HOST));
        }
    }

    public function test_notification_history_and_actions_require_module_access(): void
    {
        $id = $this->indigency();
        $this->actingAs($this->admin(['send_sms', 'send_email']), 'admin');
        $this->getJson(route('admin.notifications.remarksHistory', ['request_type' => 'indigency', 'request_id' => $id]))->assertForbidden();
        $this->post(route('admin.notifications.sendSMS'), ['request_type' => 'indigency', 'request_id' => $id, 'phone' => '09123456789', 'message' => 'Example'])->assertForbidden();
        $this->post(route('admin.notifications.sendEmail'), ['request_type' => 'indigency', 'request_id' => $id, 'email' => 'resident@example.test', 'name' => 'Resident', 'message' => 'Example'])->assertForbidden();
    }

    public function test_authorized_history_is_available_and_nonexistent_record_is_rejected(): void
    {
        $id = $this->indigency();
        $this->actingAs($this->admin(['send_sms', 'view_indigency']), 'admin');
        $this->getJson(route('admin.notifications.remarksHistory', ['request_type' => 'indigency', 'request_id' => $id]))->assertOk();
        $this->getJson(route('admin.notifications.remarksHistory', ['request_type' => 'indigency', 'request_id' => 999]))->assertNotFound();
    }

    public function test_delivery_failure_has_recovery_feedback_after_email_is_saved(): void
    {
        $r = Residents::factory()->create();
        $this->mock(EmailDeliveryService::class, fn ($m) => $m->shouldReceive('sendView')->once()->andThrow(new \RuntimeException('Synthetic provider failure')));
        $this->actingAs($r)->post('/profile/account', ['username' => 'review_profile', 'email' => 'changed-review@example.test'])->assertRedirect(route('verification.notice'))->assertSessionHas('error');
        $this->assertDatabaseHas('residents', ['id' => $r->id, 'email' => 'changed-review@example.test', 'email_verified_at' => null]);
        $this->get(route('verification.notice'))->assertOk()->assertSee('we could not send the verification link');
    }

    public function test_generated_pdf_requires_matching_module_and_disables_caching(): void
    {
        $this->indigency();
        $this->actingAs($this->admin(['generate_indigency_document']), 'admin');
        $dir = storage_path('app/generated');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $path = $dir.'/indigency_IND-REVIEW-001.pdf';
        file_put_contents($path, '%PDF-1.4 synthetic fixture');
        try {
            $response = $this->get(route('admin.documents.preview_file', ['file' => basename($path)]))->assertOk();
            $this->assertFalse($response->headers->hasCacheControlDirective('public'));
            $this->assertTrue($response->headers->hasCacheControlDirective('no-store'));
            $this->get(route('admin.documents.preview_file', ['file' => 'clearance_IND-REVIEW-001.pdf']))->assertNotFound();
        } finally {
            unlink($path);
        }
    }

    public function test_failed_validation_never_flashes_passwords(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r)->from('/profile')->post('/profile/password', ['current_password' => 'Password123!', 'new_password' => 'Synthetic-Password1!', 'new_password_confirmation' => 'Different-Password1!'])->assertSessionHasErrors('new_password');
        foreach (['current_password', 'new_password', 'new_password_confirmation'] as $field) {
            $this->assertNull(session()->getOldInput($field));
        }
    }

    public function test_other_certificates_reject_unsupported_purposes(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r);
        foreach (['residency', 'clearance'] as $type) {
            $this->post('/'.$type.'/store', ['profile_confirmed' => '1', 'years_residing' => '3-5', 'residency_type' => 'owner', 'household_members' => 2, 'purpose' => 'not-a-valid-purpose', 'terms' => '1', 'privacy' => '1', 'pickup' => '1'])->assertSessionHasErrors('purpose');
        }
        $this->assertDatabaseCount('residency_applications', 0);
        $this->assertDatabaseCount('barangay_clearances', 0);
    }

    public function test_contact_challenges_are_bound_to_the_resident_and_limited_to_five_guesses(): void
    {
        $r = Residents::factory()->create();
        $challenge = ['resident_id' => $r->id, 'contact' => '09999999999', 'hash' => Hash::make('123456'), 'expires_at' => now()->addMinutes(5)->timestamp, 'attempts' => 0];
        $this->actingAs($r)->withSession(['contact_change' => $challenge]);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/profile/contact/verify', ['contact_otp' => '654321'])->assertSessionHasErrors('contact_otp');
        }
        $this->post('/profile/contact/verify', ['contact_otp' => '123456'])->assertSessionHasErrors('contact_otp')->assertSessionMissing('contact_change');
        $this->assertSame($r->contact, $r->fresh()->contact);
        $challenge['resident_id'] = $r->id + 1;
        $this->withSession(['contact_change' => $challenge])->post('/profile/contact/verify', ['contact_otp' => '123456'])->assertSessionHasErrors('contact_otp')->assertSessionMissing('contact_change');
    }

    public function test_phone_delivery_failure_does_not_save_a_new_number_or_challenge(): void
    {
        $r = Residents::factory()->create();
        $this->mock(OtpSmsService::class, fn ($m) => $m->shouldReceive('sendOtp')->once()->andReturn(false));
        $this->actingAs($r)->post('/profile/info', ['contact' => '9999999999'])->assertSessionHasErrors('contact')->assertSessionMissing('contact_change');
        $this->assertSame($r->contact, $r->fresh()->contact);
    }

    public function test_pdf_permission_for_another_module_does_not_grant_access(): void
    {
        $this->indigency();
        $this->actingAs($this->admin(['generate_clearance_document']), 'admin')->get(route('admin.documents.preview_file', ['file' => 'indigency_IND-REVIEW-001.pdf']))->assertForbidden();
    }

    public function test_government_purpose_from_the_residency_dropdown_is_accepted(): void
    {
        $r = Residents::factory()->create();
        $this->actingAs($r)->post('/residency/store', ['profile_confirmed' => '1', 'years_residing' => '3-5', 'residency_type' => 'owner', 'household_members' => 2, 'purpose' => 'government', 'terms' => '1', 'privacy' => '1', 'pickup' => '1'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('residency_applications', ['user_id' => $r->id, 'purpose' => 'government']);
    }

    public function test_malformed_availability_input_is_a_validation_error(): void
    {
        $this->getJson('/register/check-availability?field[]=email&value[]=test')->assertUnprocessable();
        $this->getJson('/register/check-username?username[]=test')->assertUnprocessable();
    }
}
