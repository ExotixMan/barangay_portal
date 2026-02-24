<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentReport;
use Illuminate\Support\Facades\Http;

class AIncidentReportsController extends Controller
{
    //
    public function index(){
        $incidents = IncidentReport::orderBy('date_of_incident', 'desc')->get();
        return view('admin.aincidents',['incidents' => $incidents]);
    }
    public function show($incident_id)
    {
        $incident = IncidentReport::findOrFail($incident_id);
        return view('admin.aincidentsview', compact('incident'));
    }
    public function edit($incident_id)
    {
        $incident = IncidentReport::findOrFail($incident_id);
        return view('admin.aincidentsedit', compact('incident'));
    }

    public function update(Request $request, $incident_id)
    {
        $record = IncidentReport::findOrFail($incident_id);

        $record->update($request->all());

        return redirect()
            ->route('incident.view', $incident_id)
            ->with('success', 'Incident Report updated successfully');
    }

    public function destroy($incident_id)
    {
        $record = IncidentReport::findOrFail($incident_id);
        $record->delete();

        return redirect()
            ->route('incident.index')
            ->with('success', 'Incident Report deleted successfully');
    }

    
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'api-key' => config('services.brevo.key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'email' => config('services.brevo.sender_email'),
                'name' => config('services.brevo.sender_name'),
            ],
            'to' => [
                [
                    'email' => $request->email,
                    'name' => $request->name,
                ]
            ],
            'subject' => 'Barangay Request Update',
            'htmlContent' => $request->message,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'error' => $response->body()
            ]);
        }

         if ($response->failed()) {
            return back()->with('error', 'Email sending failed.');
        }

        return back()->with('success', 'Email sent successfully!');
    }

    public function sendSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $response = Http::withHeaders([
            'api-key' => config('services.brevo.key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/transactionalSMS/sms', [
            'sender' => config('services.brevo.sms_sender'),
            'recipient' => $request->phone,
            'content' => $request->message,
            'type' => 'transactional',
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'error' => $response->body()
            ]);
        }

         if ($response->failed()) {
            return back()->with('error', 'SMS sending failed.');
        }

        return back()->with('success', 'SMS sent successfully!');
    }
}
