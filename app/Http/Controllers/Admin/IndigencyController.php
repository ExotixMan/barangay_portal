<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndigencyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class IndigencyController extends Controller
{
    public function index(Request $request)
    {
        $query = IndigencyApplication::query();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search);

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
        // Filter by Income
        if ($request->monthly_income && in_array($request->monthly_income, ['below 5k', '5k-8k', '8k-10k', '10k-15k', 'no income'])) {
            $query->where('monthly_income', $request->monthly_income);
        }

        $total_count = IndigencyApplication::all()->count();
        $processing_count = IndigencyApplication::where('status', 'processing')->count();
        $approved_count   = IndigencyApplication::where('status', 'approved')->count();
        $rejected_count   = IndigencyApplication::where('status', 'rejected')->count();

        // SORTING
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['first_name', 'monthly_income', 'household_members','created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'monthly_income') {
            $query->orderByRaw("
                CASE monthly_income
                    WHEN '10k-15k' THEN 1
                    WHEN '8k-10k' THEN 2
                    WHEN '5k-8k' THEN 3
                    WHEN 'below 5k' THEN 4
                    WHEN 'no income' THEN 5
                END $direction
            ");
        } else {
            $query->orderBy($sort, $direction);
        }

        $query->orderBy($sort, $direction);

        $indigency = $query->latest()->paginate(20);

        return view('admin.admin_indigency', compact('indigency', 'total_count', 'processing_count', 'approved_count', 'rejected_count'));
    }

    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

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
            'monthly_income' => 'required',
            'household_members' => 'required|integer|min:1|max:20',
            'purpose' => 'required',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'valid_id_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            //Valid ID
            if ($request->hasFile('valid_id_path')){
                $vip = $request->file('valid_id_path');
                $vip_ext = $vip->getClientOriginalExtension();
                $vip_name = time() . '.' . $vip_ext;
                $vip->move(public_path('uploads/valid_id/indigency'), $vip_name);
                $data['valid_id_path'] = 'uploads/valid_id/indigency/' . $vip_name;
            }

            // Generate reference number
            $data['reference_number'] = 'IND-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $data['status'] = 'processing';

            IndigencyApplication::create($data);

            return back()->with('success', 'Indigency added successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:indigency_applications,id'
        ]);

        $applications = IndigencyApplication::whereIn('id', $request->ids)->get();

        foreach ($applications as $application) {
            if ($application->valid_id_path && file_exists(public_path($application->valid_id_path))) {
                unlink(public_path($application->valid_id_path));
            }
        }

        IndigencyApplication::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected application(s) deleted successfully.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:indigency_applications,id'
        ]);

        $query = IndigencyApplication::query();

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $applications = $query->get();

        $filename = 'indigency_applications.csv';

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
                'Monthly Income',
                'Household Members',
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

                $incomeDisplay = match ($app->monthly_income) {
                    'below 5k' => 'Below ₱5,000',
                    '5k-8k' => '₱5,000 – ₱8,000',
                    '8k-10k' => '₱8,001 – ₱10,000',
                    '10k-15k' => '₱10,001 – ₱15,000',
                    'no income' => 'No fixed income',
                    default => $app->monthly_income,
                };

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
                    $incomeDisplay,
                    $app->household_members,
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
        $application = IndigencyApplication::findOrFail($id);

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

        $record = IndigencyApplication::findOrFail($id);

        $templatePath = public_path('document_template/Doc2.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found');
        }

        $full_name = trim($record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name . ' ' . $record->suffix);

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('SERVICE_TYPE', 'Certification of Indigency');
        $templateProcessor->setValue('FULL_NAME', $full_name);
        $templateProcessor->setValue('DATE_ISSUED', Carbon::parse($record->created_ad)->format('F d, Y'));

        $fileName = 'certification_of_indigency_' . $record->reference_number . '.docx';
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

            return $pdf->stream('certification_of_indigency.pdf');
        }

        return response()->download($docxPath)->deleteFileAfterSend(true);
    }
}
