<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residents;

class AResidentsController extends Controller
{
    //
    public function index(){
        $residents = Residents::all();
        return view('barangay_system.aresidents',['residents' => $residents]);
    }
    public function show($resident_id)
    {
        $resident = Residents::findOrFail($resident_id);
        return view('barangay_system.aresidentsview', compact('resident'));
    }
    public function edit($resident_id)
    {
        $resident = Residents::findOrFail($resident_id);
        return view('barangay_system.aresidentsedit', compact('resident'));
    }

    public function update(Request $request, $resident_id)
    {
        $record = Residents::findOrFail($resident_id);

        $record->update($request->all());

        return redirect()
            ->route('resident.view', $resident_id)
            ->with('success', 'Resident updated successfully');
    }

    public function destroy($resident_id)
    {
        $record = Residents::findOrFail($resident_id);
        $record->delete();

        return redirect()
            ->route('resident.index')
            ->with('success', 'Resident deleted successfully');
    }
}
