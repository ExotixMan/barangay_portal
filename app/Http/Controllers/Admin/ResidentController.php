<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Residents;
use App\Models\ResidentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ResidentController extends Controller
{
    public function index(Request $request)
    {

        $query = Residents::withTrashed();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname','like',"%$search%")
                ->orWhere('lastname','like',"%$search%")
                ->orWhere('email','like',"%$search%")
                ->orWhere('username','like',"%$search%");
            });
        }

        // FILTER BY STATUS
        if ($request->status === 'active') {
            $query->whereNull('deleted_at');
        }

        if ($request->status === 'deleted') {
            $query->onlyTrashed();
        }

        // FILTER BY AGE RANGE
        if ($request->age_range) {
            switch ($request->age_range) {
                case 'minor':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) < 18");
                    break;
                case 'adult':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) BETWEEN 18 AND 59");
                    break;
                case 'senior':
                    $query->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) >= 60");
                    break;
            }
        }

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['firstname', 'username', 'age', 'created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'age') {
            $query->orderByRaw(
                "EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) $direction"
            );
        } else {
            $query->orderBy($sort, $direction);
        }

        $residents = $query->paginate(20);

        /* ==============================
        DASHBOARD STATISTICS
        ============================== */

        //TOTAL (excluding deleted)
        $totalResidents = Residents::withTrashed()->count();

        // Get last month date
        $lastMonth = Carbon::now()->subMonth();

        //TOTAL CREATED LAST MONTH
        $totalLastMonth = Residents::withTrashed()
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $thisMonth = Carbon::now();

        $totalThisMonth = Residents::withTrashed()
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->count();

        $growthPercentage = 0;

        if ($totalLastMonth > 0) {
            $growthPercentage =
                (($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100;
        }

        //ACTIVE (not soft deleted)
        $activeResidents = Residents::whereNull('deleted_at')->count();

        //ACTIVE PERCENTAGE
        $activePercentage = $totalResidents > 0 ? ($activeResidents / $totalResidents) * 100: 0;

        //NEW THIS MONTH
        $newResidents = Residents::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();

        //YESTERDAY COUNT
        $yesterdayCount = Residents::whereDate('created_at', Carbon::yesterday())->count();

        //SENIOR (60+ years old)
        $seniorResidents = Residents::whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) >= 60")->count();

        //SENIOR PERCENTAGE
        $seniorPercentage = $totalResidents > 0 ? ($seniorResidents / $totalResidents) * 100: 0;

        return view('admin.admin_resident', compact('residents', 'totalResidents', 'growthPercentage', 'activeResidents', 'activePercentage', 'newResidents', 'yesterdayCount', 'seniorResidents', 'seniorPercentage'));
    }
    
    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:residents,username',
            'email'     => 'required|email|max:255|unique:residents,email',
            'birthdate' => 'required|date|before:today',
            'password'  => 'required|min:8|confirmed',
            'contact'   => 'required|string|max:11',
            'address'   => 'required|string|max:500',
        ]);

        $resident = Residents::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'contact' => $request->contact,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $this->log($resident, 'Created');

        return back()->with('success', 'Resident added successfully.');
    }

    public function update(Request $request, Residents $resident)
    {
        // Store form type in session with resident ID
        session()->flash('form_type', 'edit_' . $resident->id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:residents,username,' . $resident->id,
            'email'     => 'required|email|max:255|unique:residents,email,' . $resident->id,
            'birthdate'  => 'required|date|before:today',
            'contact'    => 'required|string|max:11',
            'address'    => 'required|string|max:500',
        ]);

        $resident->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'contact'   => $request->contact,
            'birthdate' => $request->birthdate,
            'address'   => $request->address,
        ]);

        return back()->with('success', 'Resident updated successfully');
    }

    public function destroy(Residents $resident)
    {
        $resident->delete();
        $this->log($resident, 'Soft Deleted');

        return redirect()->route('residents.index')
        ->with('success', 'Resident deleted successfully.');
    }

    public function restore($id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        $resident->restore();

        $this->log($resident, 'Restored');

        return back()->with('success', 'Resident restored.');
    }

    public function bulkDelete(Request $request)
    {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:residents,id'
        ]);

        $residents = Residents::whereIn('id', $request->ids)->get();

        foreach ($residents as $resident) {
            $resident->delete();
            $this->log($resident, 'Bulk Deleted');
        }

        return back()->with('success', 'Selected residents deleted.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:residents,id'
        ]);

        $query = Residents::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $residents = $query->get();

        $residents = Residents::whereIn('id', $request->ids ?? Residents::pluck('id'))->get();

        $csv = "ID,Name,Email,Contact,Address\n";

        foreach ($residents as $r) {
            $csv .= "{$r->id},{$r->full_name},{$r->email},{$r->contact},{$r->address}\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=residents.csv',
        ]);
    }

    private function log($resident, $action)
    {
        ResidentActivity::create([
            'residents_id' => $resident->id,
            'action' => $action,
            'description' => $action . " by admin"
        ]);
    }
}