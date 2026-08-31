<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MaintenanceService
{
    public function __construct(
        private ForecastService $forecast,
        private PersistentBackupService $backups,
    ) {
    }

    public function runDue(bool $forceForecast = false, bool $forceBackup = false): array
    {
        $now = now('Asia/Manila');
        $result = [
            'checked_at' => $now->toIso8601String(),
            'forecast' => 'not_due',
            'backup' => 'not_due',
        ];

        if ($forceForecast || $this->forecast->isStale()) {
            try {
                $this->forecast->refresh();
                $result['forecast'] = 'refreshed';
            } catch (\Throwable $e) {
                $result['forecast'] = 'failed';
                $result['forecast_error'] = $e->getMessage();
                Log::error('Maintenance forecast refresh failed.', ['message' => $e->getMessage()]);
            }
        }

        $sunday = $this->mostRecentSundayStart($now);
        $backupDue = $forceBackup || ! $this->backups->hasBackupSince($sunday);

        if ($backupDue) {
            try {
                $backup = $this->backups->createBackup();
                $result['backup'] = 'created';
                $result['backup_file'] = $backup['name'] ?? null;
            } catch (\Throwable $e) {
                $result['backup'] = 'failed';
                $result['backup_error'] = $e->getMessage();
                Log::error('Maintenance weekly backup failed.', ['message' => $e->getMessage()]);
            }
        }

        return $result;
    }

    private function mostRecentSundayStart(Carbon $now): Carbon
    {
        $copy = $now->copy()->startOfDay();
        while ($copy->dayOfWeek !== Carbon::SUNDAY) {
            $copy->subDay();
        }
        return $copy;
    }
}
