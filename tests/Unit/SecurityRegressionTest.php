<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SecurityRegressionTest extends TestCase
{
    private function source(string $path): string
    {
        return file_get_contents(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $path) ?: '';
    }

    public function test_admin_password_reset_has_no_default_password(): void
    {
        $source = $this->source('app/Http/Controllers/Admin/AdminUserController.php');
        $this->assertStringNotContainsString("password123", $source);
        $this->assertStringContainsString("'new_password' => ['required'", $source);
    }

    public function test_resident_model_persists_selfie_path(): void
    {
        $source = $this->source('app/Models/Residents.php');
        $this->assertStringContainsString("'valid_id_selfie'", $source);
    }

    public function test_sensitive_uploads_use_private_storage(): void
    {
        $source = $this->source('app/Support/HandlesPrivateUploads.php');
        $this->assertStringContainsString("'private/'", $source);
    }
}
