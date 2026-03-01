<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Witness;
use App\Models\BlotterReport;
use Illuminate\Http\Request;

class WitnessController extends Controller
{
    public function store(Request $request, $blotterId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'statement' => 'nullable|string',
        ]);

        Witness::create([
            'blotter_report_id' => $blotterId,
            'name' => $request->name,
            'contact' => $request->contact,
            'statement' => $request->statement,
        ]);

        return back()->with('success', 'Witness added successfully.');
    }

    public function destroy($id)
    {
        $witness = Witness::findOrFail($id);
        $witness->delete();

        return back()->with('success', 'Witness deleted successfully.');
    }
}