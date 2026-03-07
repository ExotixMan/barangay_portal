<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayClearance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClearanceController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangayClearance::query();

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

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }
        // Filter by Purpose
        if ($request->purpose && in_array($request->purpose, ['employment', 'business', 'scholarship', 'travel', 'bank', 'government', 'school', 'other'])) {
            $query->where('purpose', $request->purpose);
        }

        $total_count = BarangayClearance::count(); // FIXED: removed all() since count() works directly
        $processing_count = BarangayClearance::where('status', 'processing')->count();
        $approved_count   = BarangayClearance::where('status', 'approved')->count();
        $rejected_count   = BarangayClearance::where('status', 'rejected')->count();

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'created_at'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction);

        $clearances = $query->latest()->paginate(20);
        
        return view('admin.admin_clearance', compact('clearances', 'total_count', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => ['required','regex:/^09\d{9}$/'],
            'email' => 'required|email',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'purpose' => 'required',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
        ]);

        try {
            //Valid ID
            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '.' . $vip_ext;
                $vip->move(public_path('uploads/valid_id/clearance'), $vip_name);
                $data['valid_id_path'] = 'uploads/valid_id/clearance/' . $vip_name;
            }

            // Generate Reference Number
            $data['reference_number'] = 'BC-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            // Default status
            $data['status'] = 'processing';

            BarangayClearance::create($data);

            return redirect()->route('admin.clearance.index') // FIXED: added redirect with proper route
                ->with('success', 'Clearance added successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
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
        $request->validate([    
            'ids' => 'required|array',
            'ids.*' => 'exists:barangay_clearances,id'
        ]);

        $applications = BarangayClearance::whereIn('id', $request->ids)->get();

        foreach ($applications as $application) {
            if ($application->valid_id_path && file_exists(public_path($application->valid_id_path))) {
                unlink(public_path($application->valid_id_path));
            }
        }
        
        BarangayClearance::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected application(s) deleted successfully.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:barangay_clearances,id'
        ]);

        $query = BarangayClearance::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $applications = $query->get();

        $filename = 'barangay_clearances_' . now()->format('Y-m-d_His') . '.csv'; // FIXED: added timestamp

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'ID',
                'Reference Number',
                'Full Name',
                'First Name',
                'Middle Name',
                'Last Name',
                'Suffix',
                'Birthdate',
                'Gender',
                'Address',
                'Contact Number',
                'Email',
                'Purpose',
                'Purpose Other',
                'Status',
                'Created At',
                'Updated At'
            ]);

            foreach ($applications as $app) {

                $full_name = trim(
                    $app->first_name . ' ' .
                    $app->middle_name . ' ' .
                    $app->last_name . ' ' .
                    $app->suffix
                );

                fputcsv($file, [
                    $app->id,
                    $app->reference_number,
                    $full_name,
                    $app->first_name,
                    $app->middle_name,
                    $app->last_name,
                    $app->suffix,
                    $app->birthdate,
                    $app->gender,
                    $app->address,
                    $app->contact_number,
                    $app->email,
                    $app->purpose,
                    $app->purpose_other,
                    $app->status,
                    $app->created_at,
                    $app->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $application = BarangayClearance::findOrFail($id);

        if ($application->valid_id_path && file_exists(public_path($application->valid_id_path))) {
            unlink(public_path($application->valid_id_path));
        }

        $reference = $application->reference_number;
        $application->delete();
        
        return back()->with('success', 'Application #' . $reference . ' deleted successfully.');
    }

    public function generate(Request $request)
    {
        $id = $request->query('id');
        $action = $request->query('action', 'download');

        $record = BarangayClearance::findOrFail($id);

        $templatePath = public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $full_name = trim($record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name . ' ' . $record->suffix);

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Barangay Clearance');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_at)->format('F d, Y')); // FIXED: changed created_ad to created_at

        $fileName = 'barangay_clearance_' . $record->reference_number . '.docx'; // FIXED: added underscore
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

            return $pdf->stream('barangay_clearance.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}