<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Traits\NotificationThrottler;
use App\Models\StatusRemarkHistory;
use App\Models\AdminActivityLog;
use App\Models\Residency;
use App\Models\IndigencyApplication;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;

class NotificationController extends Controller
{
    use NotificationThrottler;

    private const REMARKED_STATUSES = ['rejected', 'dropped'];
    private const ALLOWED_REQUEST_TYPES = ['residency', 'indigency', 'clearance', 'incident'];

    public function remarksHistory(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|string|in:' . implode(',', self::ALLOWED_REQUEST_TYPES),
            'request_id' => 'required|integer|min:1',
        ]);

        $history = StatusRemarkHistory::query()
            ->with('adminUser:id,first_name,middle_initial,last_name')
            ->where('request_type', $validated['request_type'])
            ->where('request_id', (int) $validated['request_id'])
            ->latest('id')
            ->get()
            ->map(function (StatusRemarkHistory $item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'remarks' => $item->remarks,
                    'channel' => $item->channel,
                    'recipient' => $item->recipient,
                    'reference_number' => $item->reference_number,
                    'admin_name' => $item->adminUser?->full_name ?? 'Unknown Admin',
                    'created_at' => optional($item->created_at)->format('M d, Y h:i A'),
                ];
            })
            ->values();

        $requestType = $validated['request_type'];
        $requestId = (int) $validated['request_id'];
        $context = $this->resolveRequestContext($requestType, $requestId);

        $timeline = collect();

        if ($context['record']) {
            $timeline->push([
                'title' => 'Application submitted',
                'actor' => 'By applicant',
                'details' => null,
                'created_at' => optional($context['record']->created_at)->format('M d, Y h:i A'),
                'timestamp' => optional($context['record']->created_at)?->timestamp ?? 0,
            ]);
        }

        if ($context['module'] && $context['reference_number']) {
            $allowedActions = ['APPROVAL_STATUS_CHANGE', 'APPLICATION_UPDATE', 'APPLICATION_ARCHIVED', 'APPLICATION_RESTORED'];
            if ($requestType === 'incident') {
                $allowedActions = array_merge($allowedActions, ['APPROVAL_INCIDENT_REPORT', 'REJECT_INCIDENT_REPORT', 'PROCESS_INCIDENT_REPORT']);
            }

            $moduleFilter = [$context['module']];
            if ($requestType === 'incident') {
                $moduleFilter[] = 'Incident Report';
            }

            $activityLogs = AdminActivityLog::query()
                ->with('user:id,first_name,middle_initial,last_name')
                ->whereIn('action', $allowedActions)
                ->whereIn('module', $moduleFilter)
                ->where('details', 'like', '%"reference_number":"' . $context['reference_number'] . '"%')
                ->latest('id')
                ->get();

            foreach ($activityLogs as $log) {
                $details = is_array($log->details) ? $log->details : [];
                $isUpdate = $log->action === 'APPLICATION_UPDATE';
                $isArchived = $log->action === 'APPLICATION_ARCHIVED';
                $isRestored = $log->action === 'APPLICATION_RESTORED';
                $isIncidentResolved = $log->action === 'APPROVAL_INCIDENT_REPORT';
                $isIncidentDropped = $log->action === 'REJECT_INCIDENT_REPORT';
                $isIncidentProcessing = $log->action === 'PROCESS_INCIDENT_REPORT';
                $newStatus = isset($details['new_status']) ? str_replace('_', ' ', (string) $details['new_status']) : 'updated';
                $oldStatus = isset($details['old_status']) ? str_replace('_', ' ', (string) $details['old_status']) : null;
                $changedFields = $isUpdate && isset($details['changed_fields']) && is_array($details['changed_fields'])
                    ? array_map(fn ($field) => str_replace('_', ' ', ucfirst((string) $field)), $details['changed_fields'])
                    : [];

                $title = $isUpdate
                    ? 'Information updated'
                    : ($isArchived
                        ? 'Application archived'
                        : ($isRestored
                            ? 'Application restored'
                            : ($isIncidentResolved
                                ? 'Status changed to Resolved'
                                : ($isIncidentDropped
                                    ? 'Status changed to Dropped'
                                    : ($isIncidentProcessing
                                        ? 'Status changed to Processing'
                                        : 'Status changed to ' . ucfirst($newStatus))))));

                $logDetails = $isUpdate
                    ? (!empty($changedFields) ? 'Updated fields: ' . implode(', ', $changedFields) : 'Application information was edited.')
                    : ($isArchived
                        ? 'The record was moved to archive.'
                        : ($isRestored
                            ? 'The record was restored from archive.'
                            : (($isIncidentResolved || $isIncidentDropped || $isIncidentProcessing)
                                ? ($oldStatus ? 'Previous status: ' . ucfirst($oldStatus) : null)
                                : ($oldStatus ? 'Previous status: ' . ucfirst($oldStatus) : null))));

                $timeline->push([
                    'title' => $title,
                    'actor' => 'Admin: ' . ($log->user?->full_name ?? 'Unknown Admin'),
                    'details' => $logDetails,
                    'created_at' => optional($log->created_at)->format('M d, Y h:i A'),
                    'timestamp' => optional($log->created_at)?->timestamp ?? 0,
                ]);
            }
        }

        foreach ($history as $item) {
            $title = match ($item['channel']) {
                'email' => 'Email sent',
                'sms' => 'SMS sent',
                'system' => 'Status updated',
                default => 'History update',
            };

            $timeline->push([
                'title' => $title,
                'actor' => 'Admin: ' . ($item['admin_name'] ?? 'Unknown Admin'),
                'details' => $item['remarks'] ?: null,
                'created_at' => $item['created_at'] ?? null,
                'timestamp' => $this->parseTimestamp($item['created_at']),
            ]);
        }

        $timeline = $timeline
            ->sortByDesc('timestamp')
            ->values()
            ->map(function (array $row) {
                unset($row['timestamp']);
                return $row;
            });

        return response()->json([
            'history' => $history,
            'timeline' => $timeline,
        ]);
    }

    private function resolveRequestContext(string $requestType, int $requestId): array
    {
        $record = null;
        $module = null;

        if ($requestType === 'residency') {
            $record = Residency::find($requestId);
            $module = 'Residency';
        } elseif ($requestType === 'indigency') {
            $record = IndigencyApplication::find($requestId);
            $module = 'Indigency';
        } elseif ($requestType === 'clearance') {
            $record = BarangayClearance::find($requestId);
            $module = 'Clearance';
        } elseif ($requestType === 'incident') {
            $record = BlotterReport::find($requestId);
            $module = 'Blotter';
        }

        return [
            'record' => $record,
            'module' => $module,
            'reference_number' => $record?->reference_number,
        ];
    }

    private function parseTimestamp(?string $createdAt): int
    {
        if (!$createdAt) {
            return 0;
        }

        $parsed = \DateTime::createFromFormat('M d, Y h:i A', $createdAt);
        return $parsed ? (int) $parsed->getTimestamp() : 0;
    }

    public function sendEmail(Request $request)
    {
        $request->merge([
            'email' => trim((string) $request->input('email', '')),
            'status' => strtolower(trim((string) $request->input('status', ''))),
            'remarks' => trim((string) $request->input('remarks', '')),
        ]);

        if ($request->input('email') === '') {
            return back()->with('error', 'Cannot send email: complainant email is missing for this report.');
        }

        $validated = $request->validate([
            'email'   => 'required|email',
            'name'    => 'required|string',
            'message' => 'required|string',
            'status'  => 'nullable|string',
            'remarks' => 'nullable|string|max:40',
            'request_type' => 'nullable|string|in:' . implode(',', self::ALLOWED_REQUEST_TYPES),
            'request_id' => 'nullable|integer|min:1',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $requiresRemarks = in_array($validated['status'] ?? '', self::REMARKED_STATUSES, true);
        if ($requiresRemarks && ($validated['remarks'] ?? '') === '') {
            return back()->with('error', 'Remarks are required before sending notifications for rejected or dropped cases.');
        }

        if ($requiresRemarks) {
            $validated['message'] .= "\n\nRemarks: " . $validated['remarks'];
        }

        // Check throttle limits before sending email
        if (!$this->canSendNotification('email', $validated['email'], 'email_channel')) {
            return back()->with('error', 'Too many emails sent. Please try again in a few moments.');
        }

        Mail::to($validated['email'])
            ->send(new Notification(
                $validated['name'],
                $validated['message']
            ));

        // Record the notification for throttling tracking
        $this->recordNotification('email', $validated['email'], 'email_channel');
        $this->storeStatusRemarkHistory($validated, 'email', $validated['email']);

        // Log notification
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'SEND_EMAIL_NOTIFICATION',
                'module' => 'Notifications',
                'details' => [
                    'recipient_email' => $validated['email'],
                    'recipient_name' => $validated['name'],
                    'sent_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    'message_length' => strlen($validated['message']),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return back()->with('success', 'Email sent successfully!');
    }

    public function sendSMS(Request $request)
    {
        $request->merge([
            'status' => strtolower(trim((string) $request->input('status', ''))),
            'remarks' => trim((string) $request->input('remarks', '')),
        ]);

        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
            'status' => 'nullable|string',
            'remarks' => 'nullable|string|max:40',
            'request_type' => 'nullable|string|in:' . implode(',', self::ALLOWED_REQUEST_TYPES),
            'request_id' => 'nullable|integer|min:1',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $requiresRemarks = in_array($validated['status'] ?? '', self::REMARKED_STATUSES, true);
        if ($requiresRemarks && ($validated['remarks'] ?? '') === '') {
            return back()->with('error', 'Remarks are required before sending notifications for rejected or dropped cases.');
        }

        if ($requiresRemarks) {
            $validated['message'] .= "\nRemarks: " . $validated['remarks'];
        }

        // Normalize to Philippine format expected by Semaphore (63XXXXXXXXXX)
        $recipient = preg_replace('/\D/', '', $validated['phone']);

        if (substr($recipient, 0, 1) === '0') {
            $recipient = '63' . substr($recipient, 1);
        }

        if (substr($recipient, 0, 1) === '9') {
            $recipient = '63' . $recipient;
        }

        // Check throttle limits before sending SMS
        if (!$this->canSendNotification('sms', $recipient, 'sms_channel')) {
            return back()->with('error', 'Too many SMS messages sent. Please try again in a few moments.');
        }

        try {
            $apiKey = env('SMS_API_KEY');

            if (empty($apiKey)) {
                return back()->with('error', 'SMS API key is missing.');
            }

            $senderName = env('SMS_SENDER_NAME');

            // Match Semaphore payload format used in debug route
            $payload = [
                'apikey' => $apiKey,
                'number' => $recipient,
                'message' => $validated['message'],
                'sendername' => $senderName,
            ];

            $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', $payload);

            if ($response->successful()) {
                // Record the notification for throttling tracking
                $this->recordNotification('sms', $recipient, 'sms_channel');
                $this->storeStatusRemarkHistory($validated, 'sms', $recipient);

                // Log notification
                if (auth('admin')->check()) {
                    \App\Models\AdminActivityLog::create([
                        'user_id' => auth('admin')->id(),
                        'action' => 'SEND_SMS_NOTIFICATION',
                        'module' => 'Notifications',
                        'details' => [
                            'recipient_phone' => $recipient,
                            'sent_by' => auth('admin')->user()?->full_name ?? 'Admin',
                            'message_length' => strlen($request->message),
                            'sms_id' => $response->json('message_id') ?? null,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                }

                return back()->with('success', 'SMS sent successfully.');
            }

            return back()->with('error', 'Failed to send SMS (HTTP ' . $response->status() . '): ' . $response->body());

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function storeStatusRemarkHistory(array $validated, string $channel, ?string $recipient = null): void
    {
        $status = strtolower(trim((string) ($validated['status'] ?? '')));
        $remarks = trim((string) ($validated['remarks'] ?? ''));
        $requestType = $validated['request_type'] ?? null;
        $requestId = isset($validated['request_id']) ? (int) $validated['request_id'] : null;

        if (!in_array($status, self::REMARKED_STATUSES, true) || $remarks === '' || !$requestType || !$requestId) {
            return;
        }

        try {
            StatusRemarkHistory::create([
                'request_type' => $requestType,
                'request_id' => $requestId,
                'reference_number' => $validated['reference_number'] ?? null,
                'status' => $status,
                'remarks' => $remarks,
                'channel' => $channel,
                'recipient' => $recipient,
                'admin_user_id' => auth('admin')->id(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to persist status remark history.', [
                'error' => $e->getMessage(),
                'request_type' => $requestType,
                'request_id' => $requestId,
                'status' => $status,
                'channel' => $channel,
            ]);
        }
    }
}