<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Residents;
use App\Models\ResidentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Password as PasswordRules;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Residents::withTrashed();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); // FIXED: added null coalescing

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

        //TOTAL (including deleted)
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
            $growthPercentage = (($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100;
        }

        //ACTIVE (not soft deleted)
        $activeResidents = Residents::whereNull('deleted_at')->count();

        //ACTIVE PERCENTAGE
        $activePercentage = $totalResidents > 0 ? ($activeResidents / $totalResidents) * 100 : 0;

        //NEW THIS MONTH
        $newResidents = Residents::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        //YESTERDAY COUNT
        $yesterdayCount = Residents::whereDate('created_at', Carbon::yesterday())->count();

        //SENIOR (60+ years old)
        $seniorResidents = Residents::whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) >= 60")->count();

        //SENIOR PERCENTAGE
        $seniorPercentage = $totalResidents > 0 ? ($seniorResidents / $totalResidents) * 100 : 0;

        return view('admin.admin_resident', compact('residents', 'totalResidents', 'growthPercentage', 'activeResidents', 'activePercentage', 'newResidents', 'yesterdayCount', 'seniorResidents', 'seniorPercentage'));
    }
    
    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

        // Normalize contact input before validation.
        $request->merge([
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
        ]);

        $request->validate([
            'firstname' => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'lastname'  => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'username'  => ['required','string','min:3','max:255','regex:/^[a-zA-Z0-9_]+$/','unique:residents,username'],
            'email'     => 'required|email|max:255|unique:residents,email',
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'password'  => ['required','confirmed', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'contact'   => ['required','string','regex:/^09[0-9]{9}$/'],
            'address'   => 'required|string|max:500',
        ], [
            'firstname.regex'   => 'First name can only contain letters, spaces, and hyphens.',
            'lastname.regex'    => 'Last name can only contain letters, spaces, and hyphens.',
            'username.regex'    => 'Username can only contain letters, numbers, and underscores.',
            'username.min'      => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex'     => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'password.*'        => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
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

        return redirect()->route('admin.residents.index') // FIXED: added redirect with proper route
            ->with('success', 'Resident added successfully.');
    }

    public function update(Request $request, $id) // FIXED: changed parameter to $id
    {
        $resident = Residents::findOrFail($id);
        
        // Store form type in session with resident ID
        session()->flash('form_type', 'edit_' . $resident->id);

        // Normalize contact input before validation.
        $request->merge([
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
        ]);

        $request->validate([
            'firstname' => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'lastname'  => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'username'  => ['required','string','min:3','max:255','regex:/^[a-zA-Z0-9_]+$/','unique:residents,username,'.$resident->id],
            'email'     => 'required|email|max:255|unique:residents,email,'.$resident->id,
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'contact'   => ['required','string','regex:/^09[0-9]{9}$/'],
            'address'   => 'required|string|max:500',
        ], [
            'firstname.regex' => 'First name can only contain letters, spaces, and hyphens.',
            'lastname.regex'  => 'Last name can only contain letters, spaces, and hyphens.',
            'username.regex'  => 'Username can only contain letters, numbers, and underscores.',
            'username.min'    => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex'   => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
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

    public function destroy($id) // FIXED: changed parameter to $id
    {
        $resident = Residents::findOrFail($id);
        $resident->delete(); // Soft delete
        $this->log($resident, 'Soft Deleted');
        $message = 'Resident moved to trash.';
    
        return redirect()->back()->with('success', $message);
    }

    public function restore($id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);
        $resident->restore();
        $this->log($resident, 'Restored');
        
        return redirect()->back()->with('success', 'Resident restored successfully.');
    }

    public function verifyValidId($id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        if (!$resident->valid_id) {
            return redirect()->back()->with('error', 'This resident has no valid ID uploaded.');
        }

        $resident->update(['valid_id_verified' => 'true']);
        $this->log($resident, 'Valid ID Verified');

        return redirect()->back()->with('success', 'Valid ID for ' . $resident->full_name . ' has been verified.');
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
        } else {
            $query->whereIn('id', Residents::pluck('id')); // FIXED: simplified
        }

        $residents = $query->get();

        $filename = 'residents_' . now()->format('Y-m-d_His') . '.csv'; // FIXED: added timestamp

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($residents) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Name', 'Email', 'Contact', 'Address', 'Username', 'Birthdate', 'Created At']);

            foreach ($residents as $r) {
                $full_name = $r->firstname . ' ' . $r->lastname;
                fputcsv($file, [
                    $r->id, $full_name, $r->email, $r->contact, $r->address, 
                    $r->username, $r->birthdate, $r->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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