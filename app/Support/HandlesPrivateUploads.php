<?php

namespace App\Support;

use App\Services\SupabaseStorageService;
use Illuminate\Http\UploadedFile;

trait HandlesPrivateUploads
{
    protected function storePrivateUpload(UploadedFile $file, string $folder, string $prefix): string
    {
        return app(SupabaseStorageService::class)->storePrivateUpload($file, $folder, $prefix);
    }

    protected function deleteStoredUpload(?string $path): void
    {
        app(SupabaseStorageService::class)->delete($path);
    }
}
