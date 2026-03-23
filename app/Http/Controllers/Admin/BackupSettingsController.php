<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Services\BackupArchiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupSettingsController extends Controller
{
    public function index()
    {
        $backupDirectory = $this->backupDirectory();

        if (!is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0755, true);
        }

        $backups = collect(File::files($backupDirectory))
            ->filter(function ($file) {
                return strtolower($file->getExtension()) === 'zip';
            })
            ->map(function ($file) {
                $modifiedTimestamp = $file->getMTime();
                return [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'modified_at' => \Carbon\Carbon::createFromTimestamp($modifiedTimestamp)->format('M d, Y h:i A'),
                    'modified_timestamp' => $modifiedTimestamp,
                ];
            })
            ->sortByDesc('modified_timestamp')
            ->values();

        return view('admin.admin_backup_settings', [
            'backups' => $backups,
        ]);
    }

    public function store(Request $request)
    {
        $backupService = app(BackupArchiveService::class);

        try {
            $fileName = $backupService->createBackup();

            $this->logAdminActivity($request, 'CREATE', 'Backup', [
                'file' => $fileName,
                'action' => 'Created backup archive',
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup created successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(Request $request, string $file)
    {
        $safeFile = basename($file);
        if ($safeFile !== $file) {
            abort(404);
        }

        $filePath = $this->backupDirectory() . DIRECTORY_SEPARATOR . $safeFile;
        if (!file_exists($filePath)) {
            abort(404);
        }

        $this->logAdminActivity($request, 'EXPORT', 'Backup', [
            'file' => $safeFile,
            'action' => 'Downloaded backup archive',
        ]);

        return response()->download($filePath, $safeFile, [
            'Content-Type' => 'application/zip',
        ]);
    }

    public function destroy(Request $request, string $file)
    {
        $safeFile = basename($file);
        if ($safeFile !== $file) {
            abort(404);
        }

        $filePath = $this->backupDirectory() . DIRECTORY_SEPARATOR . $safeFile;
        if (!file_exists($filePath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup file not found.');
        }

        @unlink($filePath);

        $this->logAdminActivity($request, 'DELETE', 'Backup', [
            'file' => $safeFile,
            'action' => 'Deleted backup archive',
        ]);

        return redirect()->route('admin.backup.index')
            ->with('success', 'Backup deleted successfully.');
    }

    private function backupDirectory(): string
    {
        return storage_path('app/backups');
    }

    private function logAdminActivity(Request $request, string $action, string $module, array $details): void
    {
        try {
            AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => $action,
                'module' => $module,
                'details' => $details,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Intentionally swallow logging failures to avoid blocking backup operations.
        }
    }
}
