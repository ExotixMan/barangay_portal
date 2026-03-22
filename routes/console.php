<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

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

    $output = shell_exec("\"$pythonExe\" \"$scriptPath\" 2>&1");

    if ($output === null) {
        $this->error('Analytics refresh failed: no output from Python process.');
        Log::error('Analytics refresh failed: no output from python process');
        return;
    }

    $this->info('Analytics refresh completed.');
    $this->line(trim($output));
    Log::info('Analytics refresh completed', ['output' => trim($output)]);
})->purpose('Regenerate dashboard forecast JSON file');

Schedule::command('analytics:refresh-forecast')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();
