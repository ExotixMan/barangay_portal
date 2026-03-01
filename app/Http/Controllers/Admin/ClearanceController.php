<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayClearance;
use Illuminate\Http\Request;

class ClearanceController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangayClearance::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_number', 'like', "%{$request->search}%")
                  ->orWhere('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $clearances = $query->latest()->paginate(10);

        return view('admin.admin_clearance', compact('clearances'));
    }

    public function approve($id)
    {
        $app = BarangayClearance::findOrFail($id);
        $app->status = 'approved';
        $app->save();

        return back()->with('success', 'Clearance approved.');
    }

    public function reject($id)
    {
        $app = BarangayClearance::findOrFail($id);
        $app->status = 'rejected';
        $app->save();

        return back()->with('success', 'Clearance rejected.');
    }

    public function bulkDelete(Request $request)
    {
        BarangayClearance::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records deleted.');
    }

    public function destroy($id)
    {
        BarangayClearance::findOrFail($id)->delete();

        return back()->with('success', 'Record deleted.');
    }
}
