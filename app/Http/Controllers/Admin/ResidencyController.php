<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Residency;
use App\Models\ResidencyApplication;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ResidencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Residency::query();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); // FIXED: added null coalescing

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status - UPDATED to include all statuses
        $allowedStatuses = ['pending', 'under_review', 'processing', 'approved', 'ready_pickup', 'claimed', 'rejected'];
        if ($request->status && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        // Filter by residency type
        if ($request->residency_type && in_array($request->residency_type, ['owner', 'renter', 'family', 'relative', 'boarder'])) {
            $query->where('residency_type', $request->residency_type);
        }

        // UPDATED counts for new statuses
        $total_count = Residency::count();
        $pending_count = Residency::where('status', 'pending')->count();
        $under_review_count = Residency::where('status', 'under_review')->count();
        $processing_count = Residency::where('status', 'processing')->count();
        $approved_count = Residency::where('status', 'approved')->count();
        $ready_pickup_count = Residency::where('status', 'ready_pickup')->count();
        $claimed_count = Residency::where('status', 'claimed')->count();
        $rejected_count = Residency::where('status', 'rejected')->count();

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'years_residing', 'created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'years_residing') {
            $query->orderByRaw("
                CASE years_residing
                    WHEN '20+' THEN 1
                    WHEN '10-20' THEN 2
                    WHEN '5-10' THEN 3
                    WHEN '3-5' THEN 4
                    WHEN '1-3' THEN 5
                    WHEN 'less1' THEN 6
                END $direction
            ");
        } else {
            $query->orderBy($sort, $direction);
        }

        $applications = $query->latest()->paginate(20);

        return view('admin.admin_residency', compact(
            'applications', 
            'total_count',
            'pending_count',
            'under_review_count',
            'processing_count', 
            'approved_count',
            'ready_pickup_count',
            'claimed_count',
            'rejected_count'
        ));
    }

    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

        // Normalize phone input (remove spaces/dashes/etc.) before validation.
        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        // Validate based on residency applications table schema
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'civil_status' => 'required',
            'birth_place' => 'required',
            'address' => 'required',
            'years_residing' => 'required|in:less1,1-3,3-5,5-10,10-20,20+',
            'residency_type' => 'required',
            'contact_number' => ['required','regex:/^09\d{9}$/'],
            'email' => 'required|email',
            'household_members' => 'required|integer|min:1|max:20',
            'purpose' => 'required',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'primary_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'government_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            // Upload primary proof
            if ($request->hasFile('primary_proof')) {
                $prip = $request->file('primary_proof');
                $prip_ext = $prip->getClientOriginalExtension();
                $prip_name = time() . '_proof_' . Str::random(5) . '.' . $prip_ext;
                $prip->move(public_path('uploads/proof_of_residency'), $prip_name);
                $data['primary_proof'] = 'uploads/proof_of_residency/' . $prip_name;
            }

            // Upload government ID
            if ($request->hasFile('government_id')) {
                $gip = $request->file('government_id');
                $gip_ext = $gip->getClientOriginalExtension();
                $gip_name = time() . '_id_' . Str::random(5) . '.' . $gip_ext;
                $gip->move(public_path('uploads/valid_id/residency'), $gip_name);
                $data['government_id'] = 'uploads/valid_id/residency/' . $gip_name;
            }

            // Generate reference number
            $data['reference_number'] = 'RES-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
            
            // Set default status to 'pending' instead of 'processing'
            $data['status'] = 'pending';

            Residency::create($data);

            return redirect()->route('admin.residency.index')
                ->with('success', 'Residency application submitted successfully and is now pending.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * NEW METHOD: Update status for any application
     */
    public function updateStatus(Request $request, $id)
    {
        $application = Residency::findOrFail($id);
        
        // Validate status
        $allowedStatuses = ['pending', 'under_review', 'processing', 'approved', 'ready_pickup', 'claimed', 'rejected'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses)
        ]);

        $newStatus = $request->status;
        $oldStatus = $application->status;

        // Optional: Add business logic rules for status transitions
        // For example, prevent certain transitions if needed
        
        // Update status and timestamp
        $application->status = $newStatus;
        $application->status_updated_at = now();
        $application->save();

        // Log approval/status update
        if (auth('admin')->check()) {
            \App\Models\AdminActivityLog::create([
                'user_id' => auth('admin')->id(),
                'action' => 'APPROVAL_STATUS_CHANGE',
                'module' => 'Residency',
                'details' => [
                    'application_id' => $application->id,
                    'reference_number' => $application->reference_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'approved_by' => auth('admin')->user()?->full_name ?? 'Admin',
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        // Format status name for display
        $statusDisplay = ucfirst(str_replace('_', ' ', $newStatus));

        return back()->with('success', "Application #{$application->reference_number} status updated from " . 
            ucfirst(str_replace('_', ' ', $oldStatus)) . " to {$statusDisplay}.");
    }

    public function update(Request $request, $id)
    {
        $application = Residency::findOrFail($id);
        
        // Store form type in session with application ID
        session()->flash('form_type', 'edit_' . $application->id);

        // Normalize phone input (remove spaces/dashes/etc.) before validation.
        $request->merge([
            'contact_number' => preg_replace('/\D+/', '', (string) $request->input('contact_number')),
        ]);

        // Validate based on residency applications table schema
        $data = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date|before:today',
            'birth_place' => 'required|string|max:500',
            'gender' => 'required|in:male,female,other',
            'civil_status' => 'required|in:single,married,widowed,separated',

            // Contact Information
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:11|regex:/^09[0-9]{9}$/',
            'address' => 'required|string',

            // Residency Details
            'years_residing' => 'required|in:less1,1-3,3-5,5-10,10-20,20+',
            'residency_type' => 'required|in:owner,renter,family,relative,boarder',
            'household_members' => 'required|integer|min:1|max:20',

            // Purpose
            'purpose' => 'required|in:government,school,employment,legal,bank,scholarship,pwd,senior,other',
            'purpose_other' => 'nullable|required_if:purpose,other|string|max:500',

            // Document Uploads (optional for update)
            'primary_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'government_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            // Update fields
            $application->first_name = $request->first_name;
            $application->middle_name = $request->middle_name;
            $application->last_name = $request->last_name;
            $application->suffix = $request->suffix;
            $application->birthdate = $request->birthdate;
            $application->birth_place = $request->birth_place;
            $application->gender = $request->gender;
            $application->civil_status = $request->civil_status;
            $application->email = $request->email;
            $application->contact_number = $data['contact_number'];
            $application->address = $request->address;
            $application->years_residing = $request->years_residing;
            $application->residency_type = $request->residency_type;
            $application->household_members = $request->household_members;
            $application->purpose = $request->purpose;
            $application->purpose_other = $request->purpose_other;

            // Upload new primary proof if provided
            if ($request->hasFile('primary_proof')) {
                // Delete old file
                if ($application->primary_proof && File::exists(public_path($application->primary_proof))) {
                    File::delete(public_path($application->primary_proof));
                }

                $prip = $request->file('primary_proof');
                $prip_ext = $prip->getClientOriginalExtension();
                $prip_name = time() . '_proof_' . Str::random(5) . '.' . $prip_ext;
                $prip->move(public_path('uploads/proof_of_residency'), $prip_name);
                $application->primary_proof = 'uploads/proof_of_residency/' . $prip_name;
            }

            // Upload new government ID if provided
            if ($request->hasFile('government_id')) {
                // Delete old file
                if ($application->government_id && File::exists(public_path($application->government_id))) {
                    File::delete(public_path($application->government_id));
                }

                $gip = $request->file('government_id');
                $gip_ext = $gip->getClientOriginalExtension();
                $gip_name = time() . '_id_' . Str::random(5) . '.' . $gip_ext;
                $gip->move(public_path('uploads/valid_id/residency'), $gip_name);
                $application->government_id = 'uploads/valid_id/residency/' . $gip_name;
            }

            $application->save();

            return redirect()->route('admin.residency.index')
                ->with('success', 'Residency application #' . $application->reference_number . ' updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update application. Please try again. ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:residency_applications,id'
        ]);

        // Get applications to delete their files
        $applications = Residency::whereIn('id', $request->ids)->get();

        foreach ($applications as $application) {
            // Delete associated files
            if ($application->primary_proof && file_exists(public_path($application->primary_proof))) {
                unlink(public_path($application->primary_proof));
            }
            if ($application->government_id && file_exists(public_path($application->government_id))) {
                unlink(public_path($application->government_id));
            }
        }

        // Delete the records
        Residency::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected application(s) deleted successfully.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:residency_applications,id'
        ]);

        $query = Residency::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        } else {
            $query->whereIn('id', Residency::pluck('id'));
        }

        $applications = $query->get();

        $filename = 'residency_applications_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID','Reference Number','Full Name','First Name','Middle Name','Last Name','Suffix',
                'Birthdate','Gender','Civil Status','Birth Place','Address','Years Residing',
                'Residency Type','Contact Number','Email','Household Members','Purpose','Purpose Other',
                'Status','Created At','Updated At'
            ]);

            foreach ($applications as $app) {
                $full_name = trim($app->first_name . ' ' . $app->middle_name . ' ' . $app->last_name . ' ' . $app->suffix);

                $yearsDisplay = '';
                switch ($app->years_residing) {
                    case 'less1': $yearsDisplay = 'Less than 1 year'; break;
                    case '1-3': $yearsDisplay = '1-3 years'; break;
                    case '3-5': $yearsDisplay = '3-5 years'; break;
                    case '5-10': $yearsDisplay = '5-10 years'; break;
                    case '10-20': $yearsDisplay = '10-20 years'; break;
                    case '20+': $yearsDisplay = '20+ years'; break;
                    default: $yearsDisplay = $app->years_residing;
                }

                // Format status display
                $statusDisplay = ucfirst(str_replace('_', ' ', $app->status));
                
                fputcsv($file, [
                    $app->id, $app->reference_number, $full_name, $app->first_name, $app->middle_name,
                    $app->last_name, $app->suffix, $app->birthdate, $app->gender, $app->civil_status,
                    $app->birth_place, $app->address, $yearsDisplay, $app->residency_type,
                    $app->contact_number, $app->email, $app->household_members, $app->purpose,
                    $app->purpose_other, $statusDisplay, $app->created_at, $app->updated_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $application = Residency::findOrFail($id);

        // Delete associated files
        if ($application->primary_proof && file_exists(public_path($application->primary_proof))) {
            unlink(public_path($application->primary_proof));
        }
        if ($application->government_id && file_exists(public_path($application->government_id))) {
            unlink(public_path($application->government_id));
        }

        $reference = $application->reference_number;
        $application->delete();

        return back()->with('success', 'Application #' . $reference . ' deleted successfully.');
    }

    public function generate(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = Residency::findOrFail($id);

        $templatePath = public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $full_name = trim($record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name . ' ' . $record->suffix);

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Certificate of Residency');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_at)->format('F d, Y'));

        $fileName = 'certificate_' . $record->reference_number . '.docx';
        $outputDir = storage_path('app/generated');
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        $docxPath = $outputDir . '/' . $fileName;
        $templateProcessor->saveAs($docxPath);

        if ($action === 'print') {
            $phpWord = IOFactory::load($docxPath);
            $objWriter = IOFactory::createWriter($phpWord, 'HTML');

            $tempHtml = $outputDir . '/temp.html';
            $objWriter->save($tempHtml);

            $htmlContent = file_get_contents($tempHtml);
            $pdf = Pdf::loadHTML($htmlContent);

            return $pdf->stream('certificate.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}