<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Services\BackupArchiveService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:refresh-forecast', function () {
    $pythonExe = 'python';
    $scriptPath = base_path('analytics/analytics.py');

    if (!file_exists($scriptPath)) {
        $this->error('Analytics script not found: ' . $scriptPath);
        Log::error('Analytics refresh failed: script not found', ['path' => $scriptPath]);
        return;
    }

    // Run Python invisibly on Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows: Use PowerShell to run without visible window
        $command = sprintf(
            'powershell -NoWindow -Command "python \"%s\" 2>&1"',
            str_replace('"', '\"', $scriptPath)
        );
    } else {
        // Linux/Mac: Standard execution
        $command = "\"$pythonExe\" \"$scriptPath\" 2>&1";
    }

    $output = shell_exec($command);

    if ($output === null) {
        $this->error('Analytics refresh failed: no output from Python process.');
        Log::error('Analytics refresh failed: no output from python process');
        return;
    }

    $this->info('Analytics refresh completed.');
    $this->line(trim($output));
    Log::info('Analytics refresh completed', ['output' => trim($output)]);
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
