<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class ForecastService
{
    public function __construct(private SupabaseStorageService $storage) {}

    public function data(bool $refreshIfStale = false): ?array
    {
        $data = $this->readPersisted();

        if ($refreshIfStale && $this->isStale($data) && Cache::add('analytics:refresh-attempt', true, 300)) {
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
            throw new \RuntimeException('A forecast refresh is already running. Please wait before retrying.', 409);
        }

        $outputPath = null;
        try {
            $python = trim((string) config('maintenance.python_binary', 'python')) ?: 'python';
            $script = base_path('analytics/analytics.py');
            $runtimeDirectory = storage_path('app/runtime/analytics');
            $outputPath = $runtimeDirectory.DIRECTORY_SEPARATOR.'forecast-'.Str::uuid().'.json';

            if (! is_file($script)) {
                throw new \RuntimeException('Analytics script was not found.');
            }

            File::ensureDirectoryExists($runtimeDirectory);

            $connectionName = config('database.default');
            $db = config('database.connections.'.$connectionName, []);

            $environment = [
                'DB_CONNECTION' => (string) ($db['driver'] ?? $connectionName),
                'DB_HOST' => (string) ($db['host'] ?? ''),
                'DB_PORT' => (string) ($db['port'] ?? ''),
                'DB_DATABASE' => (string) ($db['database'] ?? ''),
                'DB_USERNAME' => (string) ($db['username'] ?? ''),
                'DB_PASSWORD' => (string) ($db['password'] ?? ''),
                'DB_SSLMODE' => (string) ($db['sslmode'] ?? env('DB_SSLMODE', 'require')),
                'FORECAST_OUTPUT_PATH' => $outputPath,
                'PYTHONIOENCODING' => 'utf-8',
            ];
            $process = Process::path(base_path())->env($environment)->timeout(360)->run([$python, $script]);

            if (! $process->successful()) {
                throw new \RuntimeException(trim($process->errorOutput()) ?: trim($process->output()) ?: 'Python forecasting failed.');
            }

            if (! is_file($outputPath)) {
                throw new \RuntimeException('Forecasting completed but no forecast JSON was generated.');
            }

            $contents = File::get($outputPath);
            $decoded = json_decode($contents, true);
            if (! is_array($decoded) || data_get($decoded, 'forecast_metadata.schema_version') !== 2 || ! isset($decoded['applications'], $decoded['request_forecast'])) {
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
                'output' => trim($process->output()),
            ]);

            return $decoded;
        } finally {
            if ($outputPath && is_file($outputPath)) {
                File::delete($outputPath);
            }
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

        if (data_get($data, 'forecast_metadata.schema_version') !== 2
            || data_get($data, 'forecast_metadata.forecast_start_date') !== now('Asia/Manila')->toDateString()
            || $updatedAt->gt(now('Asia/Manila')->addMinutes(5))) {
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

        return null;
    }

    private function readPersisted(): ?array
    {
        $object = (string) config('maintenance.forecast_object', 'analytics/forecast.json');
        try {
            $contents = $this->storage->privateObjectBytes($object);
        } catch (\Throwable $e) {
            Log::warning('Stored forecast could not be loaded.', ['message' => $e->getMessage()]);
            $contents = null;
        }

        if ($contents === null) {
            return null;
        }

        $decoded = json_decode($contents, true);

        return is_array($decoded) && isset($decoded['forecast_metadata'], $decoded['applications'], $decoded['request_forecast'])
            ? $decoded : null;
    }
}
