<?php

namespace App\Services;

use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorageService
{
    private string $url;
    private string $key;
    private string $privateBucket;
    private string $publicBucket;
    private int $timeout;
    private bool $required;

    public function __construct()
    {
        $this->url = rtrim((string) config('supabase_storage.url', ''), '/');
        $this->key = trim((string) config('supabase_storage.secret_key', ''));
        $this->privateBucket = trim((string) config('supabase_storage.private_bucket', 'barangay-private'));
        $this->publicBucket = trim((string) config('supabase_storage.public_bucket', 'barangay-public'));
        $this->timeout = max(10, (int) config('supabase_storage.timeout', 120));
        $this->required = (bool) config('supabase_storage.required', false);
    }

    public function configured(): bool
    {
        return $this->url !== '' && $this->key !== '' && $this->privateBucket !== '' && $this->publicBucket !== '';
    }

    private function requireConfiguredStorage(): void
    {
        if ($this->required && ! $this->configured()) {
            throw new RuntimeException('Supabase Storage is required but is not fully configured. Set SUPABASE_URL, SUPABASE_SECRET_KEY, and the storage bucket names.');
        }
    }

    public function storePrivateUpload(UploadedFile $file, string $folder, string $prefix): string
    {
        $this->requireConfiguredStorage();

        $folder = $this->normalizeObjectPath($folder);
        $folder = preg_replace('#^(?:private|uploads)/#', '', $folder) ?: $folder;

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $filename = $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(12) . '.' . $extension;
        $object = trim($folder . '/' . $filename, '/');

        if ($this->configured()) {
            $this->ensureBucket($this->privateBucket, false);
            $this->upload($this->privateBucket, $object, $file, false);
            return 'private/' . $object;
        }

        $localDirectory = dirname($object) === '.' ? 'private' : 'private/' . dirname($object);
        Storage::disk('local')->putFileAs($localDirectory, $file, basename($object));
        return 'private/' . $object;
    }

    public function storePrivateFileWithName(UploadedFile $file, string $folder, string $filename, bool $upsert = false): string
    {
        $this->requireConfiguredStorage();

        $folder = $this->normalizeObjectPath($folder);
        $folder = preg_replace('#^(?:private|uploads)/#', '', $folder) ?: $folder;
        $object = trim($folder . '/' . basename($filename), '/');

        if ($this->configured()) {
            $this->ensureBucket($this->privateBucket, false);
            $this->upload($this->privateBucket, $object, $file, false, $upsert);
            return 'private/' . $object;
        }

        $localDirectory = dirname($object) === '.' ? 'private' : 'private/' . dirname($object);
        Storage::disk('local')->putFileAs($localDirectory, $file, basename($object));
        return 'private/' . $object;
    }

    public function storePrivateBytes(string $objectPath, string $contents, string $contentType = 'application/octet-stream', bool $upsert = true): string
    {
        $this->requireConfiguredStorage();

        $object = $this->normalizeObjectPath($objectPath);
        $object = preg_replace('#^(?:private|uploads)/#', '', $object) ?: $object;

        if ($this->configured()) {
            $this->ensureBucket($this->privateBucket, false);
            $this->uploadBytes($this->privateBucket, $object, $contents, $contentType, false, $upsert);
            return 'private/' . $object;
        }

        $fullPath = storage_path('app/private/' . $object);
        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, $contents);
        return 'private/' . $object;
    }

    public function materializePrivate(string $storedPath, ?string $fallbackPath = null): ?string
    {
        $path = $this->normalizeStoredPath($storedPath);

        if ($this->configured() && str_starts_with($path, 'private/')) {
            $response = $this->fetchPrivate($path);
            if ($response) {
                $object = Str::after($path, 'private/');
                $target = storage_path('app/runtime/supabase/' . $object);
                if (! is_dir(dirname($target))) {
                    mkdir(dirname($target), 0755, true);
                }
                file_put_contents($target, $response->body());
                return $target;
            }
        }

        $local = $this->localPrivatePath($path);
        if ($local) {
            return $local;
        }

        return $fallbackPath && is_file($fallbackPath) ? $fallbackPath : null;
    }

    public function storePublicUpload(UploadedFile $file, string $folder, string $prefix, string $legacyPublicFolder): string
    {
        $this->requireConfiguredStorage();

        $folder = $this->normalizeObjectPath($folder);
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $filename = $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(12) . '.' . $extension;
        $object = trim($folder . '/' . $filename, '/');

        if ($this->configured()) {
            $this->ensureBucket($this->publicBucket, true);
            $this->upload($this->publicBucket, $object, $file, true);
            return 'supabase-public/' . $object;
        }

        $legacyPublicFolder = trim(str_replace('\\', '/', $legacyPublicFolder), '/');
        $directory = public_path($legacyPublicFolder);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $file->move($directory, $filename);

        return $legacyPublicFolder . '/' . $filename;
    }

    public function delete(?string $storedPath): void
    {
        if (! $storedPath || str_contains($storedPath, '..')) {
            return;
        }

        $path = $this->normalizeStoredPath($storedPath);

        if ($this->configured() && str_starts_with($path, 'private/')) {
            $this->deleteRemote($this->privateBucket, Str::after($path, 'private/'));
        } elseif ($this->configured() && str_starts_with($path, 'supabase-public/')) {
            $this->deleteRemote($this->publicBucket, Str::after($path, 'supabase-public/'));
        }

        if (str_starts_with($path, 'private/')) {
            $localObject = Str::after($path, 'private/');
            Storage::disk('local')->delete('private/' . $localObject);
            return;
        }

        if (! str_starts_with($path, 'supabase-public/')) {
            $fullPath = public_path(ltrim($path, '/'));
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    public function fetchPrivate(string $storedPath): ?HttpResponse
    {
        if (! $this->configured()) {
            return null;
        }

        $path = $this->normalizeStoredPath($storedPath);
        if (! str_starts_with($path, 'private/')) {
            return null;
        }

        $object = Str::after($path, 'private/');
        $url = $this->storageUrl('/object/authenticated/' . rawurlencode($this->privateBucket) . '/' . $this->encodeObjectPath($object));

        $response = Http::withHeaders($this->authHeaders())
            ->timeout($this->timeout)
            ->retry(2, 250, throw: false)
            ->get($url);

        return $response->successful() ? $response : null;
    }

    public function publicUrl(?string $storedPath): ?string
    {
        if (! $storedPath) {
            return null;
        }

        $path = $this->normalizeStoredPath($storedPath);

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if ($this->configured() && str_starts_with($path, 'supabase-public/')) {
            $object = Str::after($path, 'supabase-public/');
            return $this->storageUrl('/object/public/' . rawurlencode($this->publicBucket) . '/' . $this->encodeObjectPath($object));
        }

        return asset(ltrim($path, '/'));
    }

    public function privateObjectBytes(string $objectPath): ?string
    {
        $object = $this->normalizeObjectPath($objectPath);
        if ($object === '') {
            return null;
        }

        if ($this->configured()) {
            $response = $this->fetchPrivate('private/' . $object);
            return $response?->body();
        }

        $localPath = storage_path('app/private/' . $object);
        if (! is_file($localPath)) {
            return null;
        }

        $contents = file_get_contents($localPath);
        return $contents === false ? null : $contents;
    }

    public function privateObjectExists(string $objectPath): bool
    {
        return $this->privateObjectBytes($objectPath) !== null;
    }

    public function deletePrivateObject(string $objectPath): void
    {
        $object = $this->normalizeObjectPath($objectPath);
        if ($object === '') {
            return;
        }

        if ($this->configured()) {
            $this->deleteRemote($this->privateBucket, $object);
        }

        $localPath = storage_path('app/private/' . $object);
        if (is_file($localPath)) {
            @unlink($localPath);
        }
    }

    /**
     * List objects directly beneath a prefix in the private bucket.
     * Returned names are normalized to full object paths.
     */
    public function listPrivateObjects(string $prefix = '', int $limit = 1000): array
    {
        $prefix = trim($this->normalizeObjectPath($prefix), '/');
        $limit = max(1, min($limit, 1000));

        if (! $this->configured()) {
            $root = storage_path('app/private/' . $prefix);
            if (! is_dir($root)) {
                return [];
            }

            $items = [];
            foreach (scandir($root) ?: [] as $name) {
                if ($name === '.' || $name === '..') {
                    continue;
                }
                $path = $root . DIRECTORY_SEPARATOR . $name;
                if (! is_file($path)) {
                    continue;
                }
                $items[] = [
                    'name' => trim($prefix . '/' . $name, '/'),
                    'size' => filesize($path) ?: 0,
                    'created_at' => date(DATE_ATOM, filemtime($path) ?: time()),
                    'updated_at' => date(DATE_ATOM, filemtime($path) ?: time()),
                    'metadata' => ['size' => filesize($path) ?: 0],
                ];
            }
            return $items;
        }

        $items = [];
        $offset = 0;

        do {
            $response = Http::withHeaders(array_merge($this->authHeaders(), [
                    'Content-Type' => 'application/json',
                ]))
                ->timeout($this->timeout)
                ->retry(2, 250, throw: false)
                ->post($this->storageUrl('/object/list/' . rawurlencode($this->privateBucket)), [
                    'prefix' => $prefix,
                    'limit' => $limit,
                    'offset' => $offset,
                    'sortBy' => ['column' => 'created_at', 'order' => 'desc'],
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('Supabase Storage list failed with HTTP ' . $response->status() . '.');
            }

            $batch = $response->json();
            if (! is_array($batch)) {
                break;
            }

            foreach ($batch as $item) {
                if (! is_array($item) || empty($item['name'])) {
                    continue;
                }
                $name = (string) $item['name'];
                if ($prefix !== '' && ! str_starts_with($name, $prefix . '/')) {
                    $name = $prefix . '/' . ltrim($name, '/');
                }
                $item['name'] = $name;
                $items[] = $item;
            }

            $count = count($batch);
            $offset += $count;
        } while ($count === $limit && $offset < 10000);

        return $items;
    }

    public function localPrivatePath(string $storedPath): ?string
    {
        $path = $this->normalizeStoredPath($storedPath);
        if (! str_starts_with($path, 'private/')) {
            return null;
        }

        $object = Str::after($path, 'private/');
        $fullPath = storage_path('app/private/' . $object);

        return is_file($fullPath) ? $fullPath : null;
    }

    private function upload(string $bucket, string $object, UploadedFile $file, bool $public, bool $upsert = false): void
    {
        $realPath = $file->getRealPath();
        if (! $realPath || ! is_file($realPath)) {
            throw new RuntimeException('The uploaded file could not be read.');
        }

        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $body = file_get_contents($realPath);
        if ($body === false) {
            throw new RuntimeException('The uploaded file could not be read.');
        }

        $this->uploadBytes($bucket, $object, $body, $mime, $public, $upsert);
    }

    private function uploadBytes(string $bucket, string $object, string $body, string $mime, bool $public, bool $upsert): void
    {
        $url = $this->storageUrl('/object/' . rawurlencode($bucket) . '/' . $this->encodeObjectPath($object));

        $response = Http::withHeaders(array_merge($this->authHeaders(), [
                'Content-Type' => $mime,
                'cache-control' => $public ? '3600' : 'no-store',
                'x-upsert' => $upsert ? 'true' : 'false',
            ]))
            ->withBody($body, $mime)
            ->timeout($this->timeout)
            ->retry(2, 300, throw: false)
            ->post($url);

        if (! $response->successful()) {
            throw new RuntimeException('Supabase Storage upload failed with HTTP ' . $response->status() . '.');
        }
    }

    private function deleteRemote(string $bucket, string $object): void
    {
        if ($object === '') {
            return;
        }

        $url = $this->storageUrl('/object/' . rawurlencode($bucket) . '/' . $this->encodeObjectPath($object));

        $response = Http::withHeaders($this->authHeaders())
            ->timeout($this->timeout)
            ->retry(1, 200, throw: false)
            ->delete($url);

        if (! $response->successful() && $response->status() !== 404) {
            throw new RuntimeException('Supabase Storage delete failed with HTTP ' . $response->status() . '.');
        }
    }

    private function ensureBucket(string $bucket, bool $public): void
    {
        static $checked = [];
        $cacheKey = $bucket . ':' . ($public ? '1' : '0');
        if (isset($checked[$cacheKey])) {
            return;
        }

        $get = Http::withHeaders($this->authHeaders())
            ->timeout($this->timeout)
            ->get($this->storageUrl('/bucket/' . rawurlencode($bucket)));

        if ($get->status() === 404) {
            $create = Http::withHeaders($this->authHeaders())
                ->timeout($this->timeout)
                ->post($this->storageUrl('/bucket/'), [
                    'id' => $bucket,
                    'name' => $bucket,
                    'public' => $public,
                ]);

            if (! $create->successful()) {
                throw new RuntimeException('Supabase Storage bucket creation failed with HTTP ' . $create->status() . '.');
            }
        } elseif (! $get->successful()) {
            throw new RuntimeException('Supabase Storage bucket check failed with HTTP ' . $get->status() . '.');
        }

        $checked[$cacheKey] = true;
    }

    private function authHeaders(): array
    {
        return [
            'apikey' => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Accept' => '*/*',
        ];
    }

    private function storageUrl(string $path): string
    {
        return $this->url . '/storage/v1' . $path;
    }

    private function encodeObjectPath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $this->normalizeObjectPath($path))));
    }

    private function normalizeObjectPath(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            throw new RuntimeException('Invalid storage path.');
        }

        return preg_replace('#/+#', '/', $path) ?: $path;
    }

    private function normalizeStoredPath(string $path): string
    {
        return trim(preg_replace('#/+#', '/', str_replace('\\', '/', $path)) ?: $path, '/');
    }
}
