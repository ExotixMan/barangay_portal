<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentTemplateStorageService
{
    private const MIME_DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

    public function __construct(private SupabaseStorageService $storage)
    {
    }

    public function path(string $filename): ?string
    {
        $filename = basename($filename);

        return $this->storage->materializePrivate(
            'private/document_templates/' . $filename,
            public_path('document_template/' . $filename)
        );
    }

    public function store(UploadedFile $file, string $filename): string
    {
        $filename = basename($filename);
        $current = $this->path($filename);

        if ($current && is_file($current)) {
            $backupName = pathinfo($filename, PATHINFO_FILENAME)
                . '_backup_'
                . now()->format('Ymd_His')
                . '_' . Str::random(6)
                . '.docx';

            $contents = file_get_contents($current);
            if ($contents !== false) {
                $this->storage->storePrivateBytes(
                    'document_templates/backups/' . $backupName,
                    $contents,
                    self::MIME_DOCX,
                    false
                );
            }
        }

        return $this->storage->storePrivateFileWithName(
            $file,
            'document_templates',
            $filename,
            true
        );
    }
}
