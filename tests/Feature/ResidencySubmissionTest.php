<?php

namespace Tests\Feature;

use App\Models\Residents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidencySubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_resident_can_submit_without_uploading_proof_again(): void
    {
        $resident = Residents::create([
            'firstname' => 'Test', 'lastname' => 'Resident', 'email' => 'residency@example.test',
            'address' => '25 Camia Street, Hulong Duhat', 'birthdate' => '1990-01-01',
            'gender' => 'Male', 'civil_status' => 'Single', 'place_of_birth' => 'Malabon',
            'contact' => '09123456789', 'username' => 'residency_test', 'password' => Hash::make('Password123!'),
            'valid_id' => 'private/front.png', 'valid_id_back' => 'private/back.png',
            'valid_id_verified' => true, 'valid_id_status' => 'verified', 'email_verified_at' => now(),
        ]);
        $this->actingAs($resident)->from('/residency/form')->post('/residency/store', [
            'profile_confirmed' => 'on', 'years_residing' => '5-10', 'residency_type' => 'owner',
            'household_members' => 3, 'purpose' => 'employment',
            'terms' => 'on', 'privacy' => 'on', 'pickup' => 'on',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('residency_applications', ['user_id' => $resident->id, 'purpose' => 'employment']);
    }

    private function payload(): array
    {
        return ['profile_confirmed' => 'on', 'years_residing' => '5-10', 'residency_type' => 'owner',
            'household_members' => 3, 'purpose' => 'employment', 'terms' => 'on', 'privacy' => 'on', 'pickup' => 'on'];
    }

    public function test_unverified_profile_must_provide_the_required_evidence(): void
    {
        $resident = Residents::factory()->create(['valid_id_verified' => false]);
        $this->actingAs($resident)->post('/residency/store', $this->payload())
            ->assertSessionHasErrors(['primary_proof', 'government_id', 'government_id_back']);
        $this->assertDatabaseCount('residency_applications', 0);
    }

    public function test_resident_with_uploads_is_saved_and_files_remain_private(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $resident = Residents::factory()->create(['valid_id_verified' => false]);
        $files = [];
        foreach (['primary_proof', 'government_id', 'government_id_back'] as $name) {
            $files[$name] = \Illuminate\Http\UploadedFile::fake()->create($name.'.pdf', 20, 'application/pdf');
        }
        $this->actingAs($resident)->post('/residency/store', array_merge($this->payload(), $files))
            ->assertSessionHasNoErrors()->assertRedirect();
        $application = \App\Models\ResidencyApplication::firstOrFail();
        foreach (array_keys($files) as $field) {
            $this->assertStringStartsWith('private/', $application->$field);
            \Illuminate\Support\Facades\Storage::disk('local')->assertExists($application->$field);
        }
        $this->assertSame($resident->id, $application->user_id);
        $this->assertSame($application->reference_number, session('reference_number'));
    }

    public function test_failed_upload_does_not_save_a_partial_request(): void
    {
        $resident = Residents::factory()->create(['valid_id_verified' => false]);
        $this->mock(\App\Services\SupabaseStorageService::class, function ($m) {
            $m->shouldReceive('storePrivateUpload')->once()->ordered()->andReturn('private/proof/test.pdf');
            $m->shouldReceive('storePrivateUpload')->once()->ordered()->andThrow(new \RuntimeException('Storage unavailable'));
            $m->shouldReceive('delete')->once()->with('private/proof/test.pdf');
        });
        $files = [];
        foreach (['primary_proof', 'government_id', 'government_id_back'] as $name) {
            $files[$name] = \Illuminate\Http\UploadedFile::fake()->create($name.'.pdf', 20, 'application/pdf');
        }
        $this->actingAs($resident)->post('/residency/store', array_merge($this->payload(), $files))->assertSessionHasErrors('submit');
        $this->assertDatabaseCount('residency_applications', 0);
    }

    public function test_guest_cannot_submit_a_request(): void
    {
        $this->post('/residency/store', $this->payload())->assertRedirect(route('login'));
        $this->assertDatabaseCount('residency_applications', 0);
    }
}
