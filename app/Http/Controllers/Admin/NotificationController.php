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

            // Clean phone number
            $recipient = preg_replace('/\D/', '', $request->phone);

            if (substr($recipient, 0, 1) === '0') {
                $recipient = '63' . substr($recipient, 1);
            }

            // Build payload - sender name is OPTIONAL
            $payload = [
                'apikey' => $apiKey,
                'number' => $recipient,
                'message' => $request->message,
            ];

            // Only add sendername if it exists and is not empty
            $senderName = env('SMS_SENDER_NAME');
            if (!empty($senderName)) {
                $payload['sendername'] = $senderName;
            }
            // If no sender name, Semaphore will use "SEMAPHORE" by default

            $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', $payload);

            if ($response->successful()) {
                $senderUsed = $senderName ?? 'SEMAPHORE (default)';
                return back()->with('success', "SMS sent successfully using sender: {$senderUsed}");
            }

            return back()->with('error', 'Failed to send SMS: ' . $response->body());

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}