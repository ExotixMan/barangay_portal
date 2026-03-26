<?php

namespace App\Traits;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AuditLogger
{
    /**
     * Log an admin activity with detailed information
     * 
     * @param string $action The action performed (CREATE, READ, UPDATE, DELETE, APPROVE, REJECT, EMAIL, SMS, etc.)
     * @param string $module The module/resource affected (Users, Roles, Permissions, Residents, etc.)
     * @param array $details Additional context (old values, new values, affected IDs, etc.)
     * @param string|int|null $entityId The primary entity ID affected by this action
     * @return AdminActivityLog|null The created log record
     */
    public function auditLog($action, $module, $details = [], $entityId = null)
    {
        try {
            $user = Auth::guard('admin')->user();
            if (!$user) {
                return null;
            }

            // Build comprehensive details array
            $auditDetails = array_merge($details, [
                'timestamp' => now()->toDateTimeString(),
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_role' => $user->role ? $user->role->display_name : 'No Role',
                'user_email' => $user->email,
            ]);

            // Add entity ID if provided
            if ($entityId) {
                $auditDetails['entity_id'] = $entityId;
            }

            return AdminActivityLog::create([
                'user_id' => $user->id,
                'action' => strtoupper($action),
                'module' => $module,
                'details' => $auditDetails,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log admin activity: ' . $e->getMessage(), [
                'action' => $action,
                'module' => $module,
                'exception' => $e
            ]);
            return null;
        }
    }

    /**
     * Log a CREATE action
     */
    public function auditLogCreate($module, $entityId, $data = [])
    {
        return $this->auditLog('CREATE', $module, [
            'created_data' => $data,
        ], $entityId);
    }

    /**
     * Log an UPDATE action with change tracking
     */
    public function auditLogUpdate($module, $entityId, $oldValues = [], $newValues = [])
    {
        $changes = [];
        foreach ($newValues as $key => $newValue) {
            if (isset($oldValues[$key]) && $oldValues[$key] !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValues[$key],
                    'new' => $newValue
                ];
            }
        }

        return $this->auditLog('UPDATE', $module, [
            'changes' => $changes,
            'all_changes' => count($changes) > 0,
        ], $entityId);
    }

    /**
     * Log a DELETE action
     */
    public function auditLogDelete($module, $entityId, $deletedData = [])
    {
        return $this->auditLog('DELETE', $module, [
            'deleted_data' => $deletedData,
        ], $entityId);
    }

    /**
     * Log a VIEW/READ action
     */
    public function auditLogView($module, $entityId = null, $context = [])
    {
        return $this->auditLog('VIEW', $module, [
            'context' => $context,
        ], $entityId);
    }

    /**
     * Log an APPROVE action
     */
    public function auditLogApprove($module, $entityId, $details = [])
    {
        return $this->auditLog('APPROVE', $module, array_merge([
            'status' => 'approved',
        ], $details), $entityId);
    }

    /**
     * Log a REJECT/DENY action
     */
    public function auditLogReject($module, $entityId, $reason = '', $details = [])
    {
        return $this->auditLog('REJECT', $module, array_merge([
            'status' => 'rejected',
            'reason' => $reason,
        ], $details), $entityId);
    }

    /**
     * Log an EMAIL action
     */
    public function auditLogEmail($module, $recipient, $subject = '', $details = [])
    {
        return $this->auditLog('EMAIL', $module, array_merge([
            'recipient' => $recipient,
            'subject' => $subject,
        ], $details));
    }

    /**
     * Log an SMS action
     */
    public function auditLogSMS($module, $recipient, $message = '', $details = [])
    {
        return $this->auditLog('SMS', $module, array_merge([
            'recipient' => $recipient,
            'message_length' => strlen($message),
        ], $details));
    }

    /**
     * Log a generic action with description
     */
    public function auditLogAction($action, $module, $description = '', $entityId = null)
    {
        return $this->auditLog($action, $module, [
            'description' => $description,
        ], $entityId);
    }

    /**
     * Get audit logs for a specific module
     */
    public function getModuleAuditLogs($module, $limit = 50)
    {
        return AdminActivityLog::where('module', $module)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific user
     */
    public function getUserAuditLogs($userId, $limit = 50)
    {
        return AdminActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific entity
     */
    public function getEntityAuditLogs($entityId, $limit = 50)
    {
        return AdminActivityLog::where('details->entity_id', $entityId)
            ->orWhere('details->id', $entityId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
