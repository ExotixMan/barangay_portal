<?php

namespace Tests\Feature\Auth;

use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_resident_is_shown_verification_notice(): void
    {
        $this->actingAs(Residents::factory()->unverified()->create())->get(route('verification.notice'))->assertOk();
    }

    public function test_signed_link_verifies_the_authenticated_resident(): void
    {
        $resident = Residents::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $resident->id, 'hash' => sha1($resident->email)]);
        $this->actingAs($resident)->get($url)->assertRedirect('/login');
        $this->assertNotNull($resident->fresh()->email_verified_at);
    }

    public function test_wrong_email_hash_cannot_verify_resident(): void
    {
        $resident = Residents::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $resident->id, 'hash' => sha1('wrong@example.test')]);
        $this->actingAs($resident)->get($url)->assertForbidden();
        $this->assertNull($resident->fresh()->email_verified_at);
    }
}
