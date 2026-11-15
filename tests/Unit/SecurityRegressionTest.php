<?php

namespace Tests\Unit;

use App\Models\Residents;
use App\Services\SupabaseStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_model_persists_selfie_path(): void
    {
        $resident = Residents::factory()->create(['valid_id_selfie' => 'private/selfie.png']);
        $this->assertSame('private/selfie.png', $resident->fresh()->valid_id_selfie);
    }

    public function test_sensitive_upload_is_stored_on_private_disk_and_can_be_deleted(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $storage = app(SupabaseStorageService::class);
        $path = $storage->storePrivateUpload(UploadedFile::fake()->create('id.pdf', 20, 'application/pdf'), 'uploads/valid_id', 'id');
        $this->assertStringStartsWith('private/valid_id/', $path);
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
        $resident = Residents::factory()->create(['valid_id' => $path]);
        $this->actingAs($resident)->get(route('secure.resident-id', ['side' => 'front']))->assertOk();
        $storage->delete($path);
        Storage::disk('local')->assertMissing($path);
    }
}
