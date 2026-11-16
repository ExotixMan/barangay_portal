<?php

namespace Tests\Feature;

use App\Models\Residents;
use App\Services\SupabaseStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRecoveryTest extends TestCase
{
    use RefreshDatabase;

    private string $source;

    private array $manifests;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Storage::fake('local');
        config(['supabase_storage.url' => '', 'supabase_storage.secret_key' => '', 'supabase_storage.required' => false]);
        $this->source = storage_path('app/runtime/restore-test-'.bin2hex(random_bytes(8)));
        File::makeDirectory($this->source.'/uploads/valid_id', 0700, true);
        $this->manifests = File::glob(storage_path('app/private/legacy-restores/*.json'));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->source);
        File::delete(array_diff(File::glob(storage_path('app/private/legacy-restores/*.json')), $this->manifests));
        parent::tearDown();
    }

    public function test_restore_dry_run_then_copy_verifies_bytes_without_rewriting_records(): void
    {
        $path = 'uploads/valid_id/recovered.pdf';
        $resident = Residents::factory()->create(['valid_id' => $path]);
        file_put_contents($this->source.'/'.$path, '%PDF-1.4 synthetic restored document');
        $this->artisan('documents:restore-legacy', ['source' => $this->source])->assertSuccessful();
        Storage::disk('local')->assertMissing('private/valid_id/recovered.pdf');
        $this->artisan('documents:restore-legacy', ['source' => $this->source, '--apply' => true])->assertSuccessful();
        $this->assertSame(hash_file('sha256', $this->source.'/'.$path), hash_file('sha256', app(SupabaseStorageService::class)->localPrivatePath('private/valid_id/recovered.pdf')));
        $this->assertSame($path, $resident->fresh()->valid_id);
        $this->actingAs($resident)->get(route('secure.resident-id', ['side' => 'front']))->assertOk();
        $this->artisan('documents:restore-legacy', ['source' => $this->source, '--apply' => true])->assertSuccessful();
    }

    public function test_restore_never_overwrites_a_different_existing_object(): void
    {
        Residents::factory()->create(['valid_id' => 'uploads/valid_id/recovered.pdf']);
        file_put_contents($this->source.'/uploads/valid_id/recovered.pdf', 'restored original');
        Storage::disk('local')->put('private/valid_id/recovered.pdf', 'existing object');
        $this->artisan('documents:restore-legacy', ['source' => $this->source, '--apply' => true])->assertFailed();
        $this->assertSame('existing object', Storage::disk('local')->get('private/valid_id/recovered.pdf'));
    }

    public function test_missing_files_are_reported_instead_of_fabricated(): void
    {
        Residents::factory()->create(['valid_id' => 'uploads/valid_id/missing.pdf']);
        $this->artisan('documents:restore-legacy', ['source' => $this->source, '--apply' => true])->assertFailed();
        Storage::disk('local')->assertMissing('private/valid_id/missing.pdf');
    }

    public function test_private_upload_refuses_a_public_bucket(): void
    {
        config(['supabase_storage.url' => 'https://storage.example.test', 'supabase_storage.secret_key' => 'synthetic-test-key', 'supabase_storage.required' => true]);
        Http::fake(['*/storage/v1/bucket/*' => Http::response(['public' => true])]);
        try {
            (new SupabaseStorageService)->storePrivateBytes('valid_id/test.pdf', 'synthetic bytes');
            $this->fail('A public bucket must never receive private documents.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('private Supabase bucket', $e->getMessage());
        }
        Http::assertSentCount(1);
    }

    public function test_production_rejects_unknown_hosts_and_preserves_health_checks(): void
    {
        config(['app.url' => 'https://portal.example.test', 'app.allowed_hosts' => ['www.portal.example.test']]);
        app()->detectEnvironment(fn () => 'production');
        $this->get('https://untrusted.example/login')->assertStatus(400);
        $this->get('https://portal.example.test/login')->assertOk();
        $this->get('https://www.portal.example.test/login')->assertOk();
        $this->get('https://internal-health/up')->assertOk();
    }
}
