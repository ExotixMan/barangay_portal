<?php

use App\Services\ForecastService;
use App\Services\MaintenanceService;
use App\Services\PersistentBackupService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:refresh-forecast', function (ForecastService $forecast) {
    try {
        $data = $forecast->refresh();
        $updatedAt = $forecast->updatedAt($data);
        $this->info('Analytics refresh completed.');
        if ($updatedAt) {
            $this->info('Forecast updated: ' . $updatedAt->format('M d, Y h:i A') . ' PHT');
        }
        return 0;
    } catch (\Throwable $e) {
        $this->error('Analytics refresh failed: ' . $e->getMessage());
        Log::error('Analytics refresh failed.', ['message' => $e->getMessage()]);
        return 1;
    }
})->purpose('Regenerate and persist the dashboard forecast');

Artisan::command('backup:weekly', function (PersistentBackupService $backups) {
    try {
        $backup = $backups->createBackup();
        $this->info('Weekly backup created: ' . ($backup['name'] ?? 'backup'));
        return 0;
    } catch (\Throwable $e) {
        $this->error('Weekly backup failed: ' . $e->getMessage());
        Log::error('Weekly backup failed.', ['message' => $e->getMessage()]);
        return 1;
    }
})->purpose('Create and persist the weekly system ZIP backup');

Artisan::command('maintenance:run {--forecast : Force forecast refresh} {--backup : Force backup creation}', function (MaintenanceService $maintenance) {
    $result = $maintenance->runDue(
        (bool) $this->option('forecast'),
        (bool) $this->option('backup')
    );

    $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return in_array('failed', [$result['forecast'] ?? null, $result['backup'] ?? null], true) ? 1 : 0;
})->purpose('Run due forecast and backup maintenance tasks');

Schedule::command('analytics:refresh-forecast')
    ->dailyAt('00:00')
    ->timezone('Asia/Manila')
    ->withoutOverlapping();

Schedule::command('backup:weekly')
    ->weeklyOn(0, '00:00')
    ->timezone('Asia/Manila')
    ->withoutOverlapping();
