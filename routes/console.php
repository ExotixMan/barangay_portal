<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Services\BackupArchiveService;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:refresh-forecast', function () {
    $pythonExe = 'C:\\Users\\Admin\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
    $scriptPath = base_path('analytics/analytics.py');
    $forecastPath = public_path('analytics/forecast.json');

    if (! file_exists($pythonExe)) {
        $this->error('Python not found: ' . $pythonExe);
        Log::error('Analytics refresh failed: python not found', ['python' => $pythonExe]);
        return 1;
    }

    if (! file_exists($scriptPath)) {
        $this->error('Analytics script not found: ' . $scriptPath);
        Log::error('Analytics refresh failed: script not found', ['path' => $scriptPath]);
        return 1;
    }

    $process = new Process([$pythonExe, $scriptPath], base_path(), [
        'DB_PASSWORD' => env('DB_PASSWORD'),
        'PYTHONIOENCODING' => 'utf-8',
        'SYSTEMROOT' => getenv('SYSTEMROOT') ?: 'C:\\Windows',
        'WINDIR' => getenv('WINDIR') ?: 'C:\\Windows',
        'PATH' => getenv('PATH'),
    ]);

    $process->setTimeout(300);
    $process->run();

    $output = trim($process->getOutput());
    $error = trim($process->getErrorOutput());

    if (! $process->isSuccessful()) {
        $this->error('Analytics refresh failed.');
        $this->line($error ?: $output ?: 'No Python output.');

        Log::error('Analytics refresh failed', [
            'python' => $pythonExe,
            'script' => $scriptPath,
            'output' => $output,
            'error' => $error,
        ]);

        return 1;
    }

    clearstatcache(true, $forecastPath);

    if (! file_exists($forecastPath)) {
        $this->error('Analytics ran, but forecast.json was not created.');
        Log::error('Analytics refresh failed: forecast.json missing', [
            'forecast_path' => $forecastPath,
            'output' => $output,
        ]);
        return 1;
    }

    $this->info('Analytics refresh completed.');
    $this->line($output ?: 'Forecast file generated.');
    $this->info('Forecast updated: ' . \Carbon\Carbon::createFromTimestamp(filemtime($forecastPath))
        ->timezone('Asia/Manila')
        ->format('M d, Y h:i A') . ' PHT');

    Log::info('Analytics refresh completed', [
        'python' => $pythonExe,
        'forecast_path' => $forecastPath,
        'output' => $output,
    ]);

    return 0;
})->purpose('Regenerate dashboard forecast JSON file');

Artisan::command('backup:weekly', function () {
    try {
        $backupService = app(BackupArchiveService::class);
        $fileName = $backupService->createBackup();

        $this->info('Weekly backup created: ' . $fileName);
        Log::info('Weekly backup created successfully', ['file' => $fileName]);
        return 0;
    } catch (\Throwable $e) {
        $this->error('Weekly backup failed: ' . $e->getMessage());
        Log::error('Weekly backup failed', ['error' => $e->getMessage()]);
        return 1;
    }
})->purpose('Create automatic weekly system backup');

Schedule::command('analytics:refresh-forecast')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('backup:weekly')
    ->weeklyOn(0, '00:00')
    ->timezone('Asia/Manila')
    ->withoutOverlapping()
    ->runInBackground();
