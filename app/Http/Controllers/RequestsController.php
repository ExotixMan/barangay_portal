<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\Residents;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{
    public function service(){
        $user = Auth::user();

        $requests = Requests::where('resident_id', $user->id)->get();

        return view('barangay_system.services', compact('requests'));
    }

    public function requestStore(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            "request_type" => "required|string|in:clearance,first_time_job_seeker,indigency",
            "full_name" => "required|string|max:150",
            "complete_address" => "required|string|max:255",
            "age" => "nullable|integer",              
            "date_of_birth" => "nullable|date",         
            "contact_number" => "required|string|max:20",
            "email_address" => "nullable|email|max:150",
            "purpose_of_request" => "nullable|string|max:150",
            "specify_others" => "nullable|string|max:255", 
            "valid_id_path" => "nullable|file",          
            "proof_of_residency_path" => "nullable|file",
            "remarks" => "nullable|string"
        ]);

        $data['resident_id'] = $user->id;

        //Valid ID
        if ($request->hasFile('valid_id_path')){
            $vip = $request->file('valid_id_path');
            $vip_ext = $vip->getClientOriginalExtension();
            $vip_name = time() . '.' . $vip_ext;
            $vip->move(public_path('uploads/valid_id'), $vip_name);
            $data['valid_id_path'] = 'uploads/valid_id/' . $vip_name;
        }
        
        //Residency
        if ($request->hasFile('proof_of_residency_path')){
            $porp = $request->file('proof_of_residency_path');
            $porp_ext = $porp->getClientOriginalExtension();
            $porp_name = time() . '.' . $porp_ext;
            $porp->move(public_path('uploads/proof_of_residency'), $porp_name);
            $data['proof_of_residency_path'] = 'uploads/proof_of_residency/' . $porp_name;
        }

        Requests::create($data);

        return redirect('services')->with('success', 'Requested successfully!');
    }

}
