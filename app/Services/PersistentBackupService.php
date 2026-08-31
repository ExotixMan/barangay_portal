<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PersistentBackupService
{
    public function __construct(
        private BackupArchiveService $archiveService,
        private SupabaseStorageService $storage,
    ) {
    }

    public function createBackup(): array
    {
        $lock = Cache::lock('backup:create-persistent', 1800);
        if (! $lock->get()) {
            throw new \RuntimeException('Another backup is already being created.');
        }

        try {
            $fileName = $this->archiveService->createBackup();
            $localPath = $this->archiveService->backupDirectory() . DIRECTORY_SEPARATOR . $fileName;
            $size = filesize($localPath) ?: 0;
            $sha256 = hash_file('sha256', $localPath) ?: '';

            if ($this->storage->configured()) {
                $this->persistRemote($fileName, $localPath, $size, $sha256);
                @unlink($localPath);
            }

            $this->pruneOldBackups();

            return [
                'name' => $fileName,
                'size' => $size,
                'sha256' => $sha256,
                'created_at' => now('Asia/Manila')->toIso8601String(),
            ];
        } finally {
            optional($lock)->release();
        }
    }

    public function listBackups(): array
    {
        if (! $this->storage->configured()) {
            return $this->listLocalBackups();
        }

        $prefix = trim((string) config('maintenance.backup_remote_prefix', 'system-backups'), '/');
        $objects = $this->storage->listPrivateObjects($prefix);
        $results = [];

        foreach ($objects as $object) {
            $path = (string) ($object['name'] ?? '');
            $base = basename($path);

            if (preg_match('/^barangay_backup_\d{8}_\d{6}\.zip$/', $base)) {
                $size = (int) data_get($object, 'metadata.size', data_get($object, 'metadata.contentLength', 0));
                $created = $object['created_at'] ?? $object['updated_at'] ?? now()->toIso8601String();
                $results[$base] = $this->backupEntry($base, $size, $created, 'single');
                continue;
            }

            if (preg_match('/^(barangay_backup_\d{8}_\d{6}\.zip)\.manifest\.json$/', $base, $match)) {
                $contents = $this->storage->privateObjectBytes($path);
                $manifest = $contents ? json_decode($contents, true) : null;
                if (! is_array($manifest)) {
                    continue;
                }

                $name = $match[1];
                $results[$name] = $this->backupEntry(
                    $name,
                    (int) ($manifest['size'] ?? 0),
                    (string) ($manifest['created_at'] ?? $object['created_at'] ?? now()->toIso8601String()),
                    'chunked'
                );
            }
        }

        return collect(array_values($results))
            ->sortByDesc('modified_timestamp')
            ->values()
            ->all();
    }

    public function materialize(string $fileName): string
    {
        $fileName = $this->safeBackupName($fileName);

        if (! $this->storage->configured()) {
            $path = $this->archiveService->backupDirectory() . DIRECTORY_SEPARATOR . $fileName;
            if (! is_file($path)) {
                throw new \RuntimeException('Backup file not found.');
            }
            return $path;
        }

        $runtime = storage_path('app/runtime/backups');
        File::ensureDirectoryExists($runtime);
        $target = $runtime . DIRECTORY_SEPARATOR . uniqid('backup_', true) . '_' . $fileName;
        $prefix = trim((string) config('maintenance.backup_remote_prefix', 'system-backups'), '/');
        $directObject = $prefix . '/' . $fileName;
        $direct = $this->storage->privateObjectBytes($directObject);

        if ($direct !== null) {
            File::put($target, $direct);
            return $target;
        }

        $manifestObject = $prefix . '/' . $fileName . '.manifest.json';
        $manifestBytes = $this->storage->privateObjectBytes($manifestObject);
        $manifest = $manifestBytes ? json_decode($manifestBytes, true) : null;
        if (! is_array($manifest) || empty($manifest['parts'])) {
            throw new \RuntimeException('Backup file not found.');
        }

        $handle = fopen($target, 'wb');
        if ($handle === false) {
            throw new \RuntimeException('Unable to prepare the backup for download.');
        }

        try {
            foreach ($manifest['parts'] as $part) {
                $partName = basename((string) $part);
                $bytes = $this->storage->privateObjectBytes($prefix . '/' . $partName);
                if ($bytes === null) {
                    throw new \RuntimeException('A backup part is missing: ' . $partName);
                }
                fwrite($handle, $bytes);
            }
        } finally {
            fclose($handle);
        }

        $expected = (string) ($manifest['sha256'] ?? '');
        if ($expected !== '' && ! hash_equals($expected, hash_file('sha256', $target) ?: '')) {
            @unlink($target);
            throw new \RuntimeException('Backup verification failed after reassembly.');
        }

        return $target;
    }

    public function deleteBackup(string $fileName): void
    {
        $fileName = $this->safeBackupName($fileName);

        if (! $this->storage->configured()) {
            $path = $this->archiveService->backupDirectory() . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($path)) {
                @unlink($path);
            }
            return;
        }

        $prefix = trim((string) config('maintenance.backup_remote_prefix', 'system-backups'), '/');
        $this->storage->deletePrivateObject($prefix . '/' . $fileName);

        $manifestPath = $prefix . '/' . $fileName . '.manifest.json';
        $manifestBytes = $this->storage->privateObjectBytes($manifestPath);
        $manifest = $manifestBytes ? json_decode($manifestBytes, true) : null;

        if (is_array($manifest)) {
            foreach ($manifest['parts'] ?? [] as $part) {
                $this->storage->deletePrivateObject($prefix . '/' . basename((string) $part));
            }
        }
        $this->storage->deletePrivateObject($manifestPath);
    }

    public function hasBackupSince(Carbon $since): bool
    {
        foreach ($this->listBackups() as $backup) {
            $timestamp = (int) ($backup['modified_timestamp'] ?? 0);
            if ($timestamp >= $since->timestamp) {
                return true;
            }
        }
        return false;
    }

    private function persistRemote(string $fileName, string $localPath, int $size, string $sha256): void
    {
        $prefix = trim((string) config('maintenance.backup_remote_prefix', 'system-backups'), '/');
        $chunkBytes = max(5, min(45, (int) config('maintenance.backup_chunk_size_mb', 45))) * 1024 * 1024;

        if ($size <= $chunkBytes) {
            $contents = file_get_contents($localPath);
            if ($contents === false) {
                throw new \RuntimeException('Unable to read the generated backup.');
            }
            $this->storage->storePrivateBytes($prefix . '/' . $fileName, $contents, 'application/zip', true);
            return;
        }

        $handle = fopen($localPath, 'rb');
        if ($handle === false) {
            throw new \RuntimeException('Unable to read the generated backup.');
        }

        $parts = [];
        $index = 1;
        try {
            while (! feof($handle)) {
                $bytes = fread($handle, $chunkBytes);
                if ($bytes === false || $bytes === '') {
                    break;
                }
                $partName = $fileName . '.part' . str_pad((string) $index, 4, '0', STR_PAD_LEFT);
                $this->storage->storePrivateBytes($prefix . '/' . $partName, $bytes, 'application/octet-stream', true);
                $parts[] = $partName;
                $index++;
            }
        } catch (\Throwable $e) {
            foreach ($parts as $partName) {
                $this->storage->deletePrivateObject($prefix . '/' . $partName);
            }
            throw $e;
        } finally {
            fclose($handle);
        }

        $manifest = json_encode([
            'version' => 1,
            'name' => $fileName,
            'size' => $size,
            'sha256' => $sha256,
            'created_at' => now('Asia/Manila')->toIso8601String(),
            'parts' => $parts,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        try {
            $this->storage->storePrivateBytes(
                $prefix . '/' . $fileName . '.manifest.json',
                $manifest ?: '{}',
                'application/json',
                true
            );
        } catch (\Throwable $e) {
            foreach ($parts as $partName) {
                try {
                    $this->storage->deletePrivateObject($prefix . '/' . $partName);
                } catch (\Throwable) {
                }
            }
            throw $e;
        }
    }

    private function pruneOldBackups(): void
    {
        $keep = max(1, (int) config('maintenance.backup_retention_count', 4));
        $backups = $this->listBackups();

        foreach (array_slice($backups, $keep) as $backup) {
            try {
                $this->deleteBackup((string) $backup['name']);
            } catch (\Throwable) {
            }
        }
    }

    private function listLocalBackups(): array
    {
        $directory = $this->archiveService->backupDirectory();
        File::ensureDirectoryExists($directory);

        return collect(File::files($directory))
            ->filter(fn ($file) => strtolower($file->getExtension()) === 'zip')
            ->map(fn ($file) => $this->backupEntry(
                $file->getFilename(),
                $file->getSize(),
                Carbon::createFromTimestamp($file->getMTime())->toIso8601String(),
                'local'
            ))
            ->sortByDesc('modified_timestamp')
            ->values()
            ->all();
    }

    private function backupEntry(string $name, int $size, string $createdAt, string $storage): array
    {
        $time = Carbon::parse($createdAt)->timezone('Asia/Manila');
        return [
            'name' => $name,
            'size' => $size,
            'modified_at' => $time->format('M d, Y h:i A'),
            'modified_relative' => $time->diffForHumans(),
            'modified_timestamp' => $time->timestamp,
            'storage' => $storage,
        ];
    }

    private function safeBackupName(string $fileName): string
    {
        $safe = basename($fileName);
        if ($safe !== $fileName || ! preg_match('/^barangay_backup_\d{8}_\d{6}\.zip$/', $safe)) {
            throw new \RuntimeException('Invalid backup file name.');
        }
        return $safe;
    }
}
