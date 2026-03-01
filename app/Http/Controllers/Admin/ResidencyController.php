<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Residency;

class ResidencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Residency::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $processing_count = Residency::where('status', 'processing')->count();
        $approved_count   = Residency::where('status', 'approved')->count();
        $rejected_count   = Residency::where('status', 'rejected')->count();

        $applications = $query->latest()->paginate(20);

        return view('admin.admin_residency', compact('applications', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function approve($id)
    {
        $application = Residency::findOrFail($id);
        $application->status = 'approved';
        $application->save();

        return back()->with('success', 'Application approved.');
    }

    public function reject($id)
    {
        $application = Residency::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return back()->with('success', 'Application rejected.');
    }

    public function bulkDelete(Request $request)
    {
        Residency::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected applications deleted.');
    }

    public function destroy($id)
    {
        Residency::findOrFail($id)->delete();

        return back()->with('success', 'Application deleted.');
    }
}
