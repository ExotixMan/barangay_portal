<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Services\BackupArchiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                $displayTimezone = 'Asia/Manila';
                $modifiedAt = \Carbon\Carbon::createFromTimestampUTC($modifiedTimestamp)->setTimezone($displayTimezone);

                return [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'modified_at' => $modifiedAt->format('M d, Y h:i A'),
                    'modified_relative' => $modifiedAt->diffForHumans(),
                    'modified_timestamp' => $modifiedTimestamp,
                ];
            })
            ->sortByDesc('modified_timestamp')
            ->values();

        return view('admin.admin_backup_settings', [
            'backups' => $backups,
            'displayTimezone' => 'Asia/Manila',
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

    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['nullable', 'string', 'required_without:backup_archive'],
            'backup_archive' => ['nullable', 'file', 'mimes:zip', 'max:512000', 'required_without:file'],
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first() ?: 'Restore validation failed.';

            return redirect()->route('admin.backup.index')
                ->withErrors($validator)
                ->withInput()
                ->with('restore_status', 'error')
                ->with('restore_message', $firstError)
                ->with('restore_time', now('Asia/Manila')->format('M d, Y h:i:s A'));
        }

        $backupService = app(BackupArchiveService::class);
        $uploadedPath = null;

        try {
            $archivePath = null;
            $restoreSource = null;

            if ($request->hasFile('backup_archive')) {
                $uploadedPath = $request->file('backup_archive')->store('backups/uploaded_restore', 'local');
                $archivePath = Storage::disk('local')->path($uploadedPath);
                $restoreSource = 'uploaded_archive';
            } elseif ($request->filled('file')) {
                $safeFile = basename((string) $request->input('file'));
                if ($safeFile !== (string) $request->input('file')) {
                    return redirect()->route('admin.backup.index')
                        ->with('error', 'Invalid backup file.');
                }

                $archivePath = $this->backupDirectory() . DIRECTORY_SEPARATOR . $safeFile;
                if (!file_exists($archivePath)) {
                    return redirect()->route('admin.backup.index')
                        ->with('error', 'Backup file not found.');
                }

                $restoreSource = $safeFile;
            }

            if ($archivePath === null) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Select a backup file to restore.');
            }

            $backupService->restoreBackup($archivePath);

            $this->logAdminActivity($request, 'RESTORE', 'Backup', [
                'source' => $restoreSource,
                'action' => 'Restored system from backup archive',
            ]);

            $sourceLabel = $request->hasFile('backup_archive')
                ? 'uploaded archive'
                : (string) $restoreSource;

            return redirect()->route('admin.backup.index')
                ->with('success', 'Restore completed successfully from ' . $sourceLabel . '.')
                ->with('restore_status', 'success')
                ->with('restore_message', 'Restore completed successfully from ' . $sourceLabel . '.')
                ->with('restore_time', now('Asia/Manila')->format('M d, Y h:i:s A'));
        } catch (\Throwable $e) {
            Log::error('Backup restore failed', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth('admin')->id(),
            ]);

            $errorMessage = trim((string) $e->getMessage()) !== ''
                ? $e->getMessage()
                : 'An unexpected error occurred while restoring the backup.';

            return redirect()->route('admin.backup.index')
                ->with('error', 'Restore failed: ' . $errorMessage)
                ->with('restore_status', 'error')
                ->with('restore_message', 'Restore failed: ' . $errorMessage)
                ->with('restore_time', now('Asia/Manila')->format('M d, Y h:i:s A'));
        } finally {
            if ($uploadedPath !== null) {
                Storage::disk('local')->delete($uploadedPath);
            }
        }
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
