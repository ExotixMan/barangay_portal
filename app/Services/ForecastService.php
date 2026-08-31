<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ForecastService
{
    public function __construct(private SupabaseStorageService $storage)
    {
    }

    public function data(bool $refreshIfStale = false): ?array
    {
        $data = $this->readPersisted();

        if ($refreshIfStale && $this->isStale($data)) {
            try {
                $this->refresh();
                $data = $this->readPersisted() ?? $data;
            } catch (\Throwable $e) {
                Log::error('Automatic forecast refresh failed.', [
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $data;
    }

    public function refresh(): array
    {
        $lock = Cache::lock('analytics:forecast-refresh', 420);

        if (! $lock->get()) {
            $existing = $this->readPersisted();
            if ($existing !== null) {
                return $existing;
            }
            throw new \RuntimeException('A forecast refresh is already running.');
        }

        try {
            $python = trim((string) config('maintenance.python_binary', 'python')) ?: 'python';
            $script = base_path('analytics/analytics.py');
            $runtimeDirectory = storage_path('app/runtime/analytics');
            $outputPath = $runtimeDirectory . DIRECTORY_SEPARATOR . 'forecast.json';

            if (! is_file($script)) {
                throw new \RuntimeException('Analytics script was not found.');
            }

            File::ensureDirectoryExists($runtimeDirectory);
            if (is_file($outputPath)) {
                @unlink($outputPath);
            }

            $connectionName = config('database.default');
            $db = config('database.connections.' . $connectionName, []);

            $process = new Process([$python, $script], base_path(), [
                'DB_HOST' => (string) ($db['host'] ?? ''),
                'DB_PORT' => (string) ($db['port'] ?? ''),
                'DB_DATABASE' => (string) ($db['database'] ?? ''),
                'DB_USERNAME' => (string) ($db['username'] ?? ''),
                'DB_PASSWORD' => (string) ($db['password'] ?? ''),
                'DB_SSLMODE' => (string) ($db['sslmode'] ?? env('DB_SSLMODE', 'require')),
                'FORECAST_OUTPUT_PATH' => $outputPath,
                'PYTHONIOENCODING' => 'utf-8',
                'PATH' => (string) getenv('PATH'),
            ]);
            $process->setTimeout(360);
            $process->run();

            if (! $process->isSuccessful()) {
                throw new \RuntimeException(trim($process->getErrorOutput()) ?: trim($process->getOutput()) ?: 'Python forecasting failed.');
            }

            if (! is_file($outputPath)) {
                throw new \RuntimeException('Forecasting completed but no forecast JSON was generated.');
            }

            $contents = File::get($outputPath);
            $decoded = json_decode($contents, true);
            if (! is_array($decoded)) {
                throw new \RuntimeException('The generated forecast JSON is invalid.');
            }

            $this->storage->storePrivateBytes(
                (string) config('maintenance.forecast_object', 'analytics/forecast.json'),
                $contents,
                'application/json',
                true
            );

            Log::info('Forecast refreshed and persisted.', [
                'python' => $python,
                'object' => config('maintenance.forecast_object'),
                'output' => trim($process->getOutput()),
            ]);

            return $decoded;
        } finally {
            optional($lock)->release();
        }
    }

    public function isStale(?array $data = null): bool
    {
        $data ??= $this->readPersisted();
        if ($data === null) {
            return true;
        }

        $updatedAt = $this->updatedAt($data);
        if ($updatedAt === null) {
            return true;
        }

        $maxAgeHours = max(1, (int) config('maintenance.forecast_max_age_hours', 30));
        return $updatedAt->lt(now('Asia/Manila')->subHours($maxAgeHours));
    }

    public function updatedAt(?array $data = null): ?Carbon
    {
        $data ??= $this->readPersisted();
        $generatedAt = data_get($data, 'forecast_metadata.generated_at');

        if ($generatedAt) {
            try {
                return Carbon::parse($generatedAt)->timezone('Asia/Manila');
            } catch (\Throwable) {
            }
        }

        $legacyPath = public_path('analytics/forecast.json');
        if (is_file($legacyPath)) {
            return Carbon::createFromTimestamp(filemtime($legacyPath))->timezone('Asia/Manila');
        }

        return null;
    }

    private function readPersisted(): ?array
    {
        $object = (string) config('maintenance.forecast_object', 'analytics/forecast.json');
        $contents = $this->storage->privateObjectBytes($object);

        if ($contents === null) {
            $legacyPath = public_path('analytics/forecast.json');
            if (is_file($legacyPath)) {
                $contents = file_get_contents($legacyPath) ?: null;
            }
        }

        if ($contents === null) {
            return null;
        }

        $decoded = json_decode($contents, true);
        return is_array($decoded) ? $decoded : null;
    }
}
