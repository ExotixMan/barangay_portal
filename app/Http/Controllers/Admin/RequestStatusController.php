<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\RequestRecord;
use Illuminate\Support\Facades\Http;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;


class RequestStatusController extends Controller
{
    //
    public function index()
    {
        $requests = Requests::orderBy('date_submitted', 'desc')->get();
        return view('admin.requestdash', compact('requests'));
    }

    public function show($request_id)
    {
        $request = RequestRecord::findOrFail($request_id);
        return view('admin.requestview', compact('request'));
    }
    public function edit($request_id)
    {
        $request = RequestRecord::findOrFail($request_id);
        return view('admin.requestedit', compact('request'));
    }

    public function update(Request $request, $request_id)
    {
        $record = RequestRecord::findOrFail($request_id);

        $record->update($request->all());

        return redirect()
            ->route('request.view', $request_id)
            ->with('success', 'Request updated successfully');
    }

    public function destroy($request_id)
    {
        $record = RequestRecord::findOrFail($request_id);
        $record->delete();

        return redirect()
            ->route('request.index')
            ->with('success', 'Request deleted successfully');
    }

    // public function sendEmail(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'name' => 'required|string',
    //         'message' => 'required|string',
    //     ]);

    //     $response = Http::withHeaders([
    //         'api-key' => config('services.brevo.key'),
    //         'Content-Type' => 'application/json',
    //         'Accept' => 'application/json',
    //     ])->post('https://api.brevo.com/v3/smtp/email', [
    //         'sender' => [
    //             'email' => config('services.brevo.sender_email'),
    //             'name' => config('services.brevo.sender_name'),
    //         ],
    //         'to' => [
    //             [
    //                 'email' => $request->email,
    //                 'name' => $request->name,
    //             ]
    //         ],
    //         'subject' => 'Barangay Request Update',
    //         'htmlContent' => $request->message,
    //     ]);

    //     if ($response->failed()) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => $response->body()
    //         ]);
    //     }

    //      if ($response->failed()) {
    //         return back()->with('error', 'Email sending failed.');
    //     }

    //     return back()->with('success', 'Email sent successfully!');
    // }

    
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
        $recipient = $request->phone;;
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
            // Handle success
            return $data;
        } else {
            // Handle error
            return $response->body();
        }
    }

}
