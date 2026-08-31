<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Services\BackupArchiveService;
use App\Services\PersistentBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BackupSettingsController extends Controller
{
    public function index(PersistentBackupService $backups)
    {
        return view('admin.admin_backup_settings', [
            'backups' => collect($backups->listBackups()),
            'displayTimezone' => 'Asia/Manila',
        ]);
    }

    public function store(Request $request, PersistentBackupService $backups)
    {
        try {
            $backup = $backups->createBackup();

            $this->logAdminActivity($request, 'CREATE', 'Backup', [
                'file' => $backup['name'] ?? null,
                'action' => 'Created persistent backup archive',
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup created and saved successfully.');
        } catch (\Throwable $e) {
            Log::error('Backup creation failed.', ['message' => $e->getMessage()]);
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup failed. Please check the server logs.');
        }
    }

    public function download(Request $request, string $file, PersistentBackupService $backups)
    {
        try {
            $safeFile = basename($file);
            if ($safeFile !== $file) {
                abort(404);
            }

            $path = $backups->materialize($safeFile);

            $this->logAdminActivity($request, 'EXPORT', 'Backup', [
                'file' => $safeFile,
                'action' => 'Downloaded backup archive',
            ]);

            $response = response()->download($path, $safeFile, [
                'Content-Type' => 'application/zip',
            ]);

            if (str_contains(str_replace('\\', '/', $path), '/storage/app/runtime/backups/')) {
                $response->deleteFileAfterSend(true);
            }

            return $response;
        } catch (\Throwable $e) {
            Log::warning('Backup download failed.', ['file' => $file, 'message' => $e->getMessage()]);
            abort(404);
        }
    }

    public function destroy(Request $request, string $file, PersistentBackupService $backups)
    {
        try {
            $backups->deleteBackup($file);

            $this->logAdminActivity($request, 'DELETE', 'Backup', [
                'file' => $file,
                'action' => 'Deleted backup archive',
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup file could not be deleted.');
        }
    }

    public function restore(Request $request, PersistentBackupService $backups, BackupArchiveService $archiveService)
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

        $uploadedPath = null;
        $materializedPath = null;

        try {
            if ($request->hasFile('backup_archive')) {
                $uploadedPath = $request->file('backup_archive')->store('backups/uploaded_restore', 'local');
                $archivePath = Storage::disk('local')->path($uploadedPath);
                $restoreSource = 'uploaded archive';
            } else {
                $safeFile = basename((string) $request->input('file'));
                if ($safeFile !== (string) $request->input('file')) {
                    throw new \RuntimeException('Invalid backup file.');
                }
                $materializedPath = $backups->materialize($safeFile);
                $archivePath = $materializedPath;
                $restoreSource = $safeFile;
            }

            $archiveService->restoreBackup($archivePath);

            $this->logAdminActivity($request, 'RESTORE', 'Backup', [
                'source' => $restoreSource,
                'action' => 'Restored system from backup archive',
            ]);

            $message = 'Restore completed successfully from ' . $restoreSource . '.';
            return redirect()->route('admin.backup.index')
                ->with('success', $message)
                ->with('restore_status', 'success')
                ->with('restore_message', $message)
                ->with('restore_time', now('Asia/Manila')->format('M d, Y h:i:s A'));
        } catch (\Throwable $e) {
            Log::error('Backup restore failed', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'admin_id' => auth('admin')->id(),
            ]);

            return redirect()->route('admin.backup.index')
                ->with('error', 'Restore failed. Please check the server logs.')
                ->with('restore_status', 'error')
                ->with('restore_message', 'Restore failed. Please check the server logs.')
                ->with('restore_time', now('Asia/Manila')->format('M d, Y h:i:s A'));
        } finally {
            if ($uploadedPath !== null) {
                Storage::disk('local')->delete($uploadedPath);
            }
            if ($materializedPath && str_contains(str_replace('\\', '/', $materializedPath), '/storage/app/runtime/backups/')) {
                @unlink($materializedPath);
            }
        }
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
        } catch (\Throwable) {
        }
    }
}
