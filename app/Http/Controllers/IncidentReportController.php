<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\IncidentReport;

class IncidentReportController extends Controller
{
    public function incident(){
        $user = Auth::user();

        $reports = IncidentReport::where('resident_id', $user->id)->get();

        return view('barangay_system.incident', compact('reports'));
    }

    public function incidentStore(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            "full_name" => "required|string|max:150",
            "address" => "required|string",
            "location" => "required|string",              
            "date_of_incident" => "required|date_format:Y-m-d\TH:i",         
            "contact_number" => "required|string|max:11",
            "type_of_incident" => "string|max:255", 
            "description" => "string|max:255",      
            "proof_of_incident" => "nullable|file"
        ]);

        $data['resident_id'] = $user->id;

        //Proof Evidence
        if ($request->hasFile('proof_of_incident')){
            $poi = $request->file('proof_of_incident');
            $poi_ext = $poi->getClientOriginalExtension();
            $poi_name = time() . '.' . $poi_ext;
            $poi->move(public_path('uploads/evidences'), $poi_name);
            $data['proof_of_incident'] = 'uploads/evidences/' . $poi_name;
        }

        IncidentReport::create($data);

        return redirect('/')->with('success', 'Reported successfully!');
    }

}
