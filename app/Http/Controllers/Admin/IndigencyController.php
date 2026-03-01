<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndigencyApplication;
use Illuminate\Http\Request;

class IndigencyController extends Controller
{
    public function index(Request $request)
    {
        $query = IndigencyApplication::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $indigency = $query->latest()->paginate(10);

        return view('admin.admin_indigency', compact('indigency'));
    }

    public function approve($id)
    {
        $application = IndigencyApplication::findOrFail($id);
        $application->status = 'approved';
        $application->save();

        return back()->with('success', 'Application approved.');
    }

    public function reject($id)
    {
        $application = IndigencyApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return back()->with('success', 'Application rejected.');
    }

    public function bulkDelete(Request $request)
    {
        IndigencyApplication::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected applications deleted.');
    }

    public function destroy($id)
    {
        IndigencyApplication::findOrFail($id)->delete();

        return back()->with('success', 'Application deleted.');
    }
}
