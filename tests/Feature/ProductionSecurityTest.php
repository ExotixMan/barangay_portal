<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\Residents;
use App\Services\Chatbot\DatabaseLookupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionSecurityTest extends \Tests\TestCase
{
    use RefreshDatabase;

    private function resident(): Residents
    {
        return Residents::create([
            'firstname' => 'Review', 'lastname' => 'Fixture', 'email' => 'review@example.test',
            'address' => 'Example address', 'birthdate' => '1990-01-01', 'contact' => '09123456789',
            'username' => 'review_fixture', 'password' => Hash::make('Local-Review-Password1!'),
            'valid_id' => 'private/test-front.png', 'valid_id_back' => 'private/test-back.png',
            'valid_id_verified' => true, 'valid_id_status' => 'verified', 'email_verified_at' => now(),
            'phone_otp' => Hash::make('123456'), 'phone_otp_expires_at' => now()->addMinutes(5),
        ]);
    }

    public function test_otp_cannot_authenticate_without_password_validated_session(): void
    {
        $r = $this->resident();
        $this->assertGuest();
        $this->post('/verify-otp', ['resident_id' => $r->id, 'otp' => '123456'])->assertRedirect();
        $this->assertGuest();
    }

    public function test_guest_chatbot_redacts_private_remarks(): void
    {
        $r = $this->resident();
        $id = DB::table('barangay_clearances')->insertGetId([
            'user_id' => $r->id, 'reference_number' => 'BC-2026-ABC123', 'first_name' => 'Review', 'last_name' => 'Fixture',
            'birthdate' => '1990-01-01', 'gender' => 'Male', 'address' => 'Example address', 'contact_number' => '09123456789',
            'email' => 'review@example.test', 'valid_id_path' => 'private/test-front.png', 'purpose' => 'employment',
            'status' => 'processing', 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('status_remark_histories')->insert([
            'request_type' => 'clearance', 'request_id' => $id, 'reference_number' => 'BC-2026-ABC123',
            'status' => 'processing', 'remarks' => 'PRIVATE_REVIEW_MARKER', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->assertGuest();
        $this->postJson('/track-request/search', ['reference_number' => 'BC-2026-ABC123'])
            ->assertOk()->assertJsonMissing(['remarks' => 'PRIVATE_REVIEW_MARKER']);
        $result = app(DatabaseLookupService::class)->handle('Track BC-2026-ABC123');
        $this->assertStringNotContainsString('PRIVATE_REVIEW_MARKER', $result['answer']);
        $this->postJson('/chatbot/message', ['message' => 'Track BC-2026-ABC123'])
            ->assertOk()->assertDontSee('PRIVATE_REVIEW_MARKER');
        $this->actingAs($r)->postJson('/chatbot/message', ['message' => 'Track BC-2026-ABC123'])->assertOk()->assertSee('PRIVATE_REVIEW_MARKER');
    }

    public function test_inactive_admin_session_is_revoked(): void
    {
        $role = AdminRole::create(['name' => 'super_admin', 'display_name' => 'Review Super Admin']);
        $admin = AdminUser::create([
            'first_name' => 'Review', 'last_name' => 'Admin', 'email' => 'admin-review@example.test',
            'username' => 'admin_review', 'password' => Hash::make('Local-Review-Password1!'),
            'status' => 'inactive', 'role_id' => $role->id,
        ]);
        $this->actingAs($admin, 'admin')->getJson('/bisig-admin-panel/notifications/feed')->assertForbidden();
        $this->assertGuest('admin');
    }

    public function test_ai_diagnostics_are_not_public(): void
    {
        $this->postJson('/chatbot/test-ai', ['message' => 'Review example'])->assertNotFound();
        $this->getJson('/chatbot/ai-status')->assertNotFound();
        $this->postJson(route('admin.chatbot.test.ai'), ['message' => 'Review example'])->assertUnauthorized();
        $this->getJson(route('admin.chatbot.ai.status'))->assertUnauthorized();
    }

    public function test_valid_login_challenge_can_only_be_consumed_once(): void
    {
        $r = $this->resident();
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'login', 'resident_otp_hash' => $r->phone_otp])
            ->post('/verify-otp', ['otp' => '123456'])->assertRedirect(route('barangay_system.index'));
        $this->assertAuthenticatedAs($r);
        $this->assertNull($r->fresh()->phone_otp);
        $this->assertFalse(session()->has('resident_otp_hash'));
    }

    public function test_another_residents_id_cannot_replace_the_pending_challenge(): void
    {
        $r = $this->resident();
        $other = Residents::factory()->create();
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'login', 'resident_otp_hash' => 'old-challenge'])
            ->post('/verify-otp', ['resident_id' => $other->id, 'otp' => '123456'])->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_repeated_wrong_codes_invalidate_the_challenge(): void
    {
        $r = $this->resident();
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'login', 'resident_otp_hash' => $r->phone_otp]);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-otp', ['otp' => '000000']);
        }
        $this->assertNull($r->fresh()->phone_otp);
        $this->assertGuest();
    }

    public function test_active_admin_diagnostic_is_rate_limited_and_never_returns_key_preview(): void
    {
        $role = AdminRole::create(['name' => 'super_admin', 'display_name' => 'Super Admin']);
        $admin = AdminUser::create(['first_name' => 'Review', 'last_name' => 'Admin', 'email' => 'active@example.test',
            'username' => 'active_admin', 'password' => Hash::make('Password123!'), 'status' => 'active', 'role_id' => $role->id]);
        $this->mock(\App\Services\Chatbot\ClaudeAIEngine::class, function ($m) {
            $m->shouldReceive('isAvailable')->andReturn(true);
            $m->shouldReceive('handle')->times(5)->andReturn(['answer' => 'Mock answer']);
        });
        $this->actingAs($admin, 'admin')->getJson(route('admin.chatbot.ai.status'))->assertOk()->assertJsonMissingPath('key_preview');
        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('admin.chatbot.test.ai'), ['message' => 'Review example'])->assertOk();
        }
        $this->postJson(route('admin.chatbot.test.ai'), ['message' => 'Review example'])->assertTooManyRequests();
    }

    public function test_admin_without_settings_permission_cannot_use_ai_diagnostics(): void
    {
        $role = AdminRole::create(['name' => 'staff', 'display_name' => 'Staff']);
        $admin = AdminUser::create(['first_name' => 'Staff', 'last_name' => 'Review', 'email' => 'staff@example.test',
            'username' => 'staff_review', 'password' => Hash::make('Password123!'), 'status' => 'active', 'role_id' => $role->id]);
        $this->actingAs($admin, 'admin')->postJson(route('admin.chatbot.test.ai'), ['message' => 'Review'])->assertForbidden();
    }

    public function test_registration_otp_verifies_phone_without_bypassing_id_approval(): void
    {
        $r = $this->resident();
        $r->update(['valid_id_verified' => false, 'valid_id_status' => 'for_verification', 'phone_verified' => false]);
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'register', 'resident_otp_hash' => $r->phone_otp])
            ->post('/verify-otp', ['otp' => '123456'])->assertRedirect(route('login'));
        $this->assertTrue($r->fresh()->phone_verified);
        $this->assertFalse($r->fresh()->valid_id_verified);
        $this->assertGuest();
    }

    public function test_expired_login_otp_is_rejected(): void
    {
        $r = $this->resident();
        $r->update(['phone_otp_expires_at' => now()->subMinute()]);
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'login', 'resident_otp_hash' => $r->phone_otp])
            ->post('/verify-otp', ['otp' => '123456'])->assertSessionHas('fail');
        $this->assertGuest();
        $this->assertNull($r->fresh()->phone_otp);
    }

    public function test_expired_registration_can_resend_without_losing_the_account(): void
    {
        $r = $this->resident();
        $r->update(['phone_verified' => false, 'phone_otp_expires_at' => now()->subMinute()]);
        $this->withSession(['resident_id' => $r->id, 'otp_purpose' => 'register', 'resident_otp_hash' => $r->phone_otp])
            ->post('/verify-otp', ['otp' => '123456'])->assertSessionHas('fail');
        $this->assertNotNull(Residents::find($r->id));
        $this->mock(\App\Services\OtpSmsService::class, fn ($m) => $m->shouldReceive('sendOtp')->once()->andReturn(true));
        $this->postJson('/resend-otp')->assertOk();
        $this->assertNotNull($r->fresh()->phone_otp);
        $this->assertGuest();
    }
}
