<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'email'   => 'required|email',
            'name'    => 'required|string',
            'message' => 'required|string',
        ]);

        Mail::to($validated['email'])
            ->send(new Notification(
                $validated['name'],
                $validated['message']
            ));

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
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $apiKey = env('SMS_API_KEY');

            if (empty($apiKey)) {
                return back()->with('error', 'SMS API key is missing.');
            }

            // Normalize to Philippine format expected by Semaphore (63XXXXXXXXXX)
            $recipient = preg_replace('/\D/', '', $request->phone);

            if (substr($recipient, 0, 1) === '0') {
                $recipient = '63' . substr($recipient, 1);
            }

            if (substr($recipient, 0, 1) === '9') {
                $recipient = '63' . $recipient;
            }

            $senderName = env('SMS_SENDER_NAME');

            // Match Semaphore payload format used in debug route
            $payload = [
                'apikey' => $apiKey,
                'number' => $recipient,
                'message' => $request->message,
                'sendername' => $senderName,
            ];

            $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', $payload);

            if ($response->successful()) {
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
}