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
use App\Services\AdminSettingsService;

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

        // Get 3-month average for smoother growth calculation
        $thisMonth = Carbon::now();
        $threeMonthsAgo = $thisMonth->copy()->subMonths(3);
        
        $totalLastThreeMonths = Residents::withTrashed()
            ->whereBetween('created_at', [$threeMonthsAgo->startOfMonth(), $thisMonth->copy()->subMonth()->endOfMonth()])
            ->count();
        
        $threeMonthAverage = $totalLastThreeMonths / 3;
        
        $totalThisMonth = Residents::withTrashed()
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->count();

        $growthPercentage = 0;

        if ($threeMonthAverage > 0) {
            $growthPercentage = (($totalThisMonth - $threeMonthAverage) / $threeMonthAverage) * 100;
            // Cap growth at ±100% for display purposes to avoid extreme percentages from small numbers
            $growthPercentage = max(-100, min(100, $growthPercentage));
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
            'middlename' => ['nullable','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'lastname'  => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'suffix' => ['nullable','string','max:50','regex:/^[a-zA-Z\s\.,]+$/'],
            'username'  => ['required','string','min:3','max:255','regex:/^[a-zA-Z0-9_]+$/','unique:residents,username'],
            'email'     => 'required|email|max:255|unique:residents,email',
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'password'  => ['required','confirmed', 'regex:/^\S+$/', PasswordRules::min(8)->mixedCase()->numbers()->symbols()],
            'contact'   => ['required','string','regex:/^09[0-9]{9}$/'],
            'address'   => 'required|string|max:500',
        ], [
            'firstname.regex'   => 'First name can only contain letters, spaces, and hyphens.',
            'middlename.regex'  => 'Middle name can only contain letters, spaces, and hyphens.',
            'lastname.regex'    => 'Last name can only contain letters, spaces, and hyphens.',
            'suffix.regex'      => 'Suffix can only contain letters, spaces, and periods.',
            'username.regex'    => 'Username can only contain letters, numbers, and underscores.',
            'username.min'      => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex'     => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'password.regex'    => 'Password cannot contain spaces.',
            'password.*'        => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.',
        ]);

        $resident = Residents::create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'contact' => $request->contact,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $this->log($resident, 'Created');

        return redirect()->route('admin.residents.index')
            ->with('success', 'Resident added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        session()->flash('form_type', 'edit_' . $resident->id);

        $request->merge([
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
        ]);

        $validated = $request->validate([
            'firstname' => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'middlename' => ['nullable','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'lastname'  => ['required','string','min:2','max:255','regex:/^[a-zA-Z\s\-\.]+$/'],
            'suffix' => ['nullable','string','max:50','regex:/^[a-zA-Z\s\.,]+$/'],
            'username'  => ['required','string','min:3','max:255','regex:/^[a-zA-Z0-9_]+$/','unique:residents,username,'.$resident->id],
            'email'     => 'required|email|max:255|unique:residents,email,'.$resident->id,
            'birthdate' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'contact'   => ['required','string','regex:/^09[0-9]{9}$/'],
            'address'   => 'required|string|max:500',

            // Valid ID update/review fields.
            'valid_id' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'valid_id_back' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'valid_id_selfie' => ['nullable','file','mimes:jpg,jpeg,png','max:5120'],
            'valid_id_status' => ['nullable','in:for_verification,verified,rejected'],
            'valid_id_rejection_reason' => ['nullable','string','max:500','required_if:valid_id_status,rejected'],
        ], [
            'firstname.regex' => 'First name can only contain letters, spaces, and hyphens.',
            'middlename.regex' => 'Middle name can only contain letters, spaces, and hyphens.',
            'lastname.regex'  => 'Last name can only contain letters, spaces, and hyphens.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and periods.',
            'username.regex'  => 'Username can only contain letters, numbers, and underscores.',
            'username.min'    => 'Username must be at least 3 characters.',
            'birthdate.before_or_equal' => 'Resident must be 18 years old or above.',
            'contact.regex'   => 'Contact number must be in the format 09XXXXXXXXX (11 digits).',
            'valid_id.mimes' => 'The front valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_back.mimes' => 'The back valid ID must be JPG, JPEG, PNG, or PDF.',
            'valid_id_selfie.mimes' => 'The selfie holding ID must be JPG, JPEG, or PNG.',
            'valid_id_status.in' => 'Please select a valid ID status.',
            'valid_id_rejection_reason.required_if' => 'Remarks are required when rejecting a valid ID.',
        ]);

        $updateData = [
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname'  => $validated['lastname'],
            'suffix' => $validated['suffix'] ?? null,
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'contact'   => $validated['contact'],
            'birthdate' => $validated['birthdate'],
            'address'   => $validated['address'],
        ];

        foreach (['valid_id', 'valid_id_back', 'valid_id_selfie'] as $field) {
            if ($request->hasFile($field)) {
                $this->deleteResidentValidIdFile((string) $resident->{$field});
                $updateData[$field] = $this->storeResidentValidIdFile($request, $field);

                // New ID image means it needs admin review again unless the admin selects Verified in the same update.
                $updateData['valid_id_verified'] = false;
                $updateData['valid_id_status'] = 'for_verification';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] = null;
            }
        }

        if ($request->filled('valid_id_status')) {
            $status = (string) $validated['valid_id_status'];

            if ($status === 'verified') {
                $hasFront = $request->hasFile('valid_id') || !empty($resident->valid_id);
                $hasBack = $request->hasFile('valid_id_back') || !empty($resident->valid_id_back);

                if (!$hasFront || !$hasBack) {
                    return back()
                        ->withErrors(['valid_id_status' => 'Front and back valid ID images are required before approval.'])
                        ->withInput();
                }

                $updateData['valid_id_verified'] = true;
                $updateData['valid_id_status'] = 'verified';
                $updateData['valid_id_verified_at'] = now('Asia/Manila');
                $updateData['valid_id_verified_by'] = auth('admin')->id();
                $updateData['valid_id_rejection_reason'] = null;
            }

            if ($status === 'rejected') {
                $updateData['valid_id_verified'] = false;
                $updateData['valid_id_status'] = 'rejected';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] = $validated['valid_id_rejection_reason'] ?? null;
            }

            if ($status === 'for_verification') {
                $updateData['valid_id_verified'] = false;
                $updateData['valid_id_status'] = 'for_verification';
                $updateData['valid_id_verified_at'] = null;
                $updateData['valid_id_verified_by'] = null;
                $updateData['valid_id_rejection_reason'] = null;
            }
        }

        $resident->update($updateData);

        $this->log($resident, 'Updated');

        return back()->with('success', 'Resident updated successfully.');
    }

    public function destroy(int $id)
    {
        $resident = Residents::findOrFail($id);
        $resident->delete();
        $this->log($resident, 'Deactivated');
        $message = 'Resident moved to archive.';
    
        return redirect()->back()->with('success', $message);
    }

    public function restore(int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);
        $resident->restore();
        $this->log($resident, 'Restored');
        
        return redirect()->back()->with('success', 'Resident restored successfully.');
    }

    public function verifyValidId(int $id)
    {
        $resident = Residents::withTrashed()->findOrFail($id);

        if (empty($resident->valid_id) || empty($resident->valid_id_back)) {
            return redirect()->back()->with('error', 'Front and back valid ID images are required before approval.');
        }

        $resident->update([
            'valid_id_verified' => true,
            'valid_id_status' => 'verified',
            'valid_id_verified_at' => now('Asia/Manila'),
            'valid_id_verified_by' => auth('admin')->id(),
            'valid_id_rejection_reason' => null,
        ]);

        $this->log($resident, 'Valid ID Approved');

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPROVE',
                'module' => 'Residents',
                'details' => [
                    'resident_id' => $resident->id,
                    'resident_name' => $resident->full_name,
                    'action_type' => 'valid_id_approved',
                    'approved_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return redirect()->back()->with('success', 'Valid ID for ' . $resident->full_name . ' has been approved.');
    }

    public function rejectValidId(Request $request, int $id)
    {
        $validated = $request->validate([
            'valid_id_rejection_reason' => ['required','string','max:500'],
        ], [
            'valid_id_rejection_reason.required' => 'Remarks are required when rejecting a valid ID.',
            'valid_id_rejection_reason.max' => 'Remarks must not exceed 500 characters.',
        ]);

        $resident = Residents::withTrashed()->findOrFail($id);

        if (empty($resident->valid_id) && empty($resident->valid_id_back)) {
            return redirect()->back()->with('error', 'This resident has no valid ID images to reject.');
        }

        $resident->update([
            'valid_id_verified' => false,
            'valid_id_status' => 'rejected',
            'valid_id_verified_at' => null,
            'valid_id_verified_by' => null,
            'valid_id_rejection_reason' => $validated['valid_id_rejection_reason'],
        ]);

        $this->log($resident, 'Valid ID Rejected');

        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'REJECT',
                'module' => 'Residents',
                'details' => [
                    'resident_id' => $resident->id,
                    'resident_name' => $resident->full_name,
                    'action_type' => 'valid_id_rejected',
                    'remarks' => $validated['valid_id_rejection_reason'],
                    'rejected_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->back()->with('success', 'Valid ID for ' . $resident->full_name . ' has been rejected.');
    }

    private function storeResidentValidIdFile(Request $request, string $field): string
    {
        $file = $request->file($field);

        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('The uploaded file for ' . $field . ' is invalid.');
        }

        $uploadDirectory = public_path('uploads/valid_id/residents');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $field . '_' . now('Asia/Manila')->format('Ymd_His') . '_' . uniqid('', true) . '.' . $extension;

        $file->move($uploadDirectory, $filename);

        return 'uploads/valid_id/residents/' . $filename;
    }

    private function deleteResidentValidIdFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
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
            $this->log($resident, 'Bulk Archived');
        }

        // Log audit trail
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'BULK_ARCHIVE',
                'module' => 'Residents',
                'details' => [
                    'ids' => $request->ids,
                    'count' => count($request->ids),
                    'archived_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return back()->with('success', 'Selected residents archived.');
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
            $query->whereIn('id', Residents::pluck('id'));
        }

        $residents = $query->get();

        $filename = 'residents_' . now()->format('Y-m-d_His') . '.csv'; 

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

        // Log audit trail
        if (auth('admin')->check()) {
            $exportCount = Residents::query()->when($request->ids, function($q) use ($request) {
                return $q->whereIn('id', $request->ids);
            })->count();
            
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'EXPORT',
                'module' => 'Residents',
                'details' => [
                    'ids' => $request->ids ?: 'all',
                    'count' => $exportCount,
                    'exported_by' => auth('admin')->user()?->full_name ?? 'Admin',
                    'file_format' => 'CSV'
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return response()->stream($callback, 200, $headers);
    }

    private function log(Residents $resident, string $action) : void
    {
        ResidentActivity::create([
            'residents_id' => $resident->id,
            'action' => $action,
            'description' => $action . " by " . (auth('admin')->user()?->full_name),
        ]);
    }
}