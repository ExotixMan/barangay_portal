<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlotterReport;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = BlotterReport::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('complainant_name', 'like', "%{$request->search}%")
                  ->orWhere('respondent_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $incidents = BlotterReport::with('witnesses')->latest()->paginate(10);


        return view('admin.admin_incident_report', compact('incidents'));
    }

    public function approve($id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $blotter->update(['status' => 'approved']);

        return back()->with('success', 'Blotter approved.');
    }

    public function reject($id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $blotter->update(['status' => 'rejected']);

        return back()->with('success', 'Blotter rejected.');
    }

    public function destroy($id)
    {
        BlotterReport::destroy($id);
        return back()->with('success', 'Blotter deleted.');
    }

    public function bulkDelete(Request $request)
    {
        BlotterReport::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records deleted.');
    }
}
