<?php

namespace App\Console\Commands;

use App\Services\PrivateRecordAccess;
use App\Services\SupabaseStorageService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class RestoreLegacyDocuments extends Command
{
    protected $signature = 'documents:restore-legacy {source : Restored directory containing uploads/} {--apply : Copy and verify files; otherwise dry run}';

    protected $description = 'Restore referenced legacy documents into private storage without changing database paths';

    public function handle(PrivateRecordAccess $access, SupabaseStorageService $storage): int
    {
        $root = realpath($this->argument('source'));
        if (! $root || ! is_dir($root)) {
            $this->error('The source directory does not exist.');

            return self::FAILURE;
        }
        $rows = [];
        $seen = [];
        $failed = false;
        foreach ($access->legacyPaths() as $path) {
            if (isset($seen[$path])) {
                continue;
            }
            $seen[$path] = true;
            $source = realpath($root.'/'.$path);
            $row = ['path' => $path, 'status' => 'missing'];
            if (! $source || ! is_file($source) || ! str_starts_with($source, rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR)) {
                $failed = true;
                $rows[] = $row;

                continue;
            }
            $row['sha256'] = hash_file('sha256', $source);
            $row['status'] = 'ready';
            if ($this->option('apply')) {
                $destination = 'private/'.substr($path, strlen('uploads/'));
                try {
                    $existing = $this->checksum($storage, $destination);
                    if ($existing === null) {
                        $storage->storePrivateFileWithName(new UploadedFile($source, basename($source), null, null, true), dirname($destination), basename($destination));
                    }
                    if ($this->checksum($storage, $destination) !== $row['sha256']) {
                        throw new \RuntimeException('Restored file checksum does not match. Existing objects are never overwritten.');
                    }
                    $row['status'] = $existing === null ? 'restored' : 'already_restored';
                } catch (\Throwable $e) {
                    report($e);
                    $row['status'] = 'failed';
                    $failed = true;
                }
            }
            $rows[] = $row;
        }
        $directory = storage_path('app/private/legacy-restores');
        if (! is_dir($directory)) {
            mkdir($directory, 0700, true);
        }
        $manifest = $directory.'/'.now()->format('Ymd_His').'-'.Str::random(8).'.json';
        file_put_contents($manifest, json_encode(['apply' => (bool) $this->option('apply'), 'files' => $rows], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        chmod($manifest, 0600);
        $this->info(($this->option('apply') ? 'Restore' : 'Dry run').': '.count($rows).' referenced files. Manifest: '.$manifest);
        foreach (array_count_values(array_column($rows, 'status')) as $status => $count) {
            $this->line($status.': '.$count);
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function checksum(SupabaseStorageService $storage, string $path): ?string
    {
        $remote = $storage->fetchPrivate($path);
        if ($remote) {
            return hash('sha256', $remote->body());
        }
        $local = $storage->localPrivatePath($path);

        return $local ? hash_file('sha256', $local) : null;
    }
}
