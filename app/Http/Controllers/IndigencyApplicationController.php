<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndigencyApplication;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class IndigencyApplicationController extends Controller
{
    public function index()
    {
        if (session('submitted_application')) {
            return redirect()->route('success', [
                'service' => 'Certificate of Indigency',
                'reference' => session('reference_number')
            ]);
        }

        return view('barangay_system.indigency_form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(5)->toDateString(),
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'monthly_income' => 'required',
            'household_members' => 'required|integer|min:1|max:20',
            'purpose' => 'required',
            'purpose_other' => 'nullable',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Primary proof of residency
        if ($request->hasFile('primary_proof')) {
            $proof = $request->file('primary_proof');
            $proofExt = $proof->getClientOriginalExtension();
            $proofName = time() . '_proof.' . $proofExt;
            $proof->move(public_path('uploads/proof_of_residency/indigency'), $proofName);
            $data['primary_proof'] = 'uploads/proof_of_residency/indigency/' . $proofName;
        }

        //Valid ID
        if ($request->hasFile('valid_id_path')){
            $vip = $request->file('valid_id_path');
            $vip_ext = $vip->getClientOriginalExtension();
            $vip_name = time() . '.' . $vip_ext;
            $vip->move(public_path('uploads/valid_id/indigency'), $vip_name);
            $data['valid_id_path'] = 'uploads/valid_id/indigency/' . $vip_name;
        }

        // Generate reference number
        $data['reference_number'] = 'IND-' . date('Y') . '-' . strtoupper(Str::random(6));

        $data['status'] = 'processing';
        $data['user_id'] = $request->user()?->id;

        IndigencyApplication::create($data);

        $applicant_name = $request->first_name . ' ' . $request->last_name;
        $date_submitted = now()->format('F d, Y');

        session([
            'route' => 'indigency',
            'applicant_name' => $applicant_name,
            'date_submitted' => $date_submitted,
            'status' => 'Submitted for Processing',
            'amount' => 0,
            'fee_label' => 'Free',
            'reference_number' => $data['reference_number'],
            'submitted_application' => true
        ]);

        return redirect()->route('success', [
            'service' => 'Certificate of Indigency',
            'reference' => $data['reference_number']
        ]);
    }
}
