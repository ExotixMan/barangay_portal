<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResidencyApplication;
use Illuminate\Support\Str;

class ResidencyApplicationController extends Controller
{

    public function index()
    {
        return view('barangay_system.residency_form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'civil_status' => 'required',
            'birth_place' => 'required',
            'address' => 'required',
            'years_residing' => 'required',
            'residency_type' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'household_members' => 'required|integer|min:1|max:20',
            'purpose' => 'required',
            'purpose_other' => 'nullable',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'government_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Upload files
        if ($request->hasFile('primary_proof')){
            $prip = $request->file('primary_proof');
            $prip_ext = $prip->getClientOriginalExtension();
            $prip_name = time() . '.' . $prip_ext;
            $prip->move(public_path('uploads/proof_of_residency'), $prip_name);
            $data['primary_proof'] = 'uploads/proof_of_residency' . $prip_name;
        }

        //Government ID
        if ($request->hasFile('government_id')){
            $gip = $request->file('government_id');
            $gip_ext = $gip->getClientOriginalExtension();
            $gip_name = time() . '.' . $gip_ext;
            $gip->move(public_path('uploads/valid_id/residency'), $gip_name);
            $data['government_id'] = 'uploads/valid_id/residency' . $gip_name;
        }

        // Generate reference number
        $data['reference_number'] = 'RES-' . date('Y') . '-' . strtoupper(Str::random(6));

        ResidencyApplication::create($data);

        $applicant_name = $request->first_name . ' ' . $request->last_name;
        $date_submitted = now()->format('F d, Y');

        session([
            'route' => 'residency',
            'applicant_name' => $applicant_name,
            'date_submitted' => $date_submitted,
            'status' => 'Submitted for Processing',
            'amount' => 50
        ]);

        return redirect()->route('success', [
            'service' => 'Certificate of Residency',
            'reference' => $data['reference_number']
        ]);
    }
}
