<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

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

        $apiKey = env('SMS_API_KEY');
        $recipient = $request->phone;
        $message = $request->message;

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://sms-api-ph-gceo.onrender.com/send/sms', [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return back()->with('success', 'SMS sent successfully!'); // FIXED: return proper response
        } else {
            return back()->with('error', 'Failed to send SMS: ' . $response->body()); // FIXED: return proper response
        }
    }
}