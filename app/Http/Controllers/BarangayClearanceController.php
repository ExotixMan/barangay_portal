<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangayClearance;
use Illuminate\Support\Str;

class BarangayClearanceController extends Controller
{
    public function index()
    {
        return view('barangay_system.clearance_form');
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
            'address' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'purpose' => 'required',
            'purpose_other' => 'nullable',
        ]);
        
        //Valid ID
        if ($request->hasFile('valid_id_path')){
            $vip = $request->file('valid_id_path');
            $vip_ext = $vip->getClientOriginalExtension();
            $vip_name = time() . '.' . $vip_ext;
            $vip->move(public_path('uploads/valid_id/clearance'), $vip_name);
            $data['valid_id_path'] = 'uploads/valid_id/clearance' . $vip_name;
        }

        // Generate Reference Number
        $data['reference_number'] = 'BC-' . date('Y') . '-' . strtoupper(Str::random(6));

        // Default status
        $data['status'] = 'processing';

        BarangayClearance::create($data);

        $applicant_name = $request->first_name . ' ' . $request->last_name;
        $date_submitted = now()->format('F d, Y');

        session([
            'applicant_name' => $applicant_name,
            'date_submitted' => $date_submitted,
            'status' => 'Submitted for Processing',
            'amount' => 100
        ]);

        return redirect()->route('success', [
            'service' => 'Clearance',
            'reference' => $data['reference_number']
        ]);
    }
}
