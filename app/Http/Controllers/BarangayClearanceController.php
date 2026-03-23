<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangayClearance;
use Illuminate\Support\Str;

class BarangayClearanceController extends Controller
{
    public function index()
    {
        if (session('submitted_application')) {
            return redirect()->route('success', [
                'service' => 'Clearance',
                'reference' => session('reference_number')
            ]);
        }

        return view('barangay_system.clearance_form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'middle_name' => 'nullable|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\pL\s\'\.,-]+$/u',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(5)->toDateString(),
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'purpose' => 'required',
            'purpose_other' => 'nullable',
        ]);
        try {

            // Primary proof of residency
            if ($request->hasFile('primary_proof')) {
                $proof = $request->file('primary_proof');
                $proofExt = $proof->getClientOriginalExtension();
                $proofName = time() . '_proof.' . $proofExt;
                $proof->move(public_path('uploads/proof_of_residency/clearance'), $proofName);
                $data['primary_proof'] = 'uploads/proof_of_residency/clearance/' . $proofName;
            }
        
            //Valid ID
            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '.' . $vip_ext;
                $vip->move(public_path('uploads/valid_id/clearance'), $vip_name);
                $data['valid_id_path'] = 'uploads/valid_id/clearance/' . $vip_name;
            }

            // Generate Reference Number
            $data['reference_number'] = 'BC-' . date('Y') . '-' . strtoupper(Str::random(6));

            // Default status
            $data['status'] = 'processing';

            BarangayClearance::create($data);

            $applicant_name = $request->first_name . ' ' . $request->last_name;
            $date_submitted = now()->format('F d, Y');

            session([
                'route' => 'clearance',
                'applicant_name' => $applicant_name,
                'date_submitted' => $date_submitted,
                'status' => 'Submitted for Processing',
                'amount' => null,
                'fee_label' => 'Depending on purpose',
                'reference_number' => $data['reference_number'],
                'submitted_application' => true
            ]);

            return redirect()->route('success', [
                'service' => 'Clearance',
                'reference' => $data['reference_number']
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
