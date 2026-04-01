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

        return response()->json([
            'history' => $history,
        ]);
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
            'remarks' => 'nullable|string|max:500',
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
            'remarks' => 'nullable|string|max:500',
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