<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndigencyApplication;
use Illuminate\Support\Str;

class IndigencyController extends Controller
{
    public function index()
    {
        return view('barangay_system.indigency_form');
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
            'monthly_income' => 'required',
            'household_members' => 'required|integer',
            'purpose' => 'required',
            'purpose_other' => 'nullable',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        //Valid ID
        if ($request->hasFile('valid_id_path')){
            $vip = $request->file('valid_id_path');
            $vip_ext = $vip->getClientOriginalExtension();
            $vip_name = time() . '.' . $vip_ext;
            $vip->move(public_path('uploads/valid_id/indigency'), $vip_name);
            $data['valid_id_path'] = 'uploads/valid_id/indigency' . $vip_name;
        }

        // Generate reference number
        $data['reference_number'] = 'IND-' . date('Y') . '-' . strtoupper(Str::random(6));

        $data['status'] = 'processing';

        IndigencyApplication::create($data);

        $applicant_name = $request->first_name . ' ' . $request->last_name;
        $date_submitted = now()->format('F d, Y');

        session([
            'route' => 'indigency',
            'applicant_name' => $applicant_name,
            'date_submitted' => $date_submitted,
            'status' => 'Submitted for Processing',
            'amount' => 0
        ]);

        return redirect()->route('success', [
            'service' => 'Certificate of Indigency',
            'reference' => $data['reference_number']
        ]);
    }
}
