<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlotterReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Witness;
use App\Models\ReportFile;
use Carbon\Carbon;

class IncidentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = BlotterReport::withTrashed();

        $search = preg_replace('/[^a-zA-Z0-9\s\-@.]/', '', $request->search ?? ''); // FIXED: added null coalescing

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('complainant_name', 'like', "%{$search}%")
                  ->orWhere('respondent_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }
        // Filter by Type
        if ($request->report_type && in_array($request->report_type, ['dispute', 'security', 'public', 'other'])) {
            $query->where('report_type', $request->report_type);
        }

        $total_count = BlotterReport::withTrashed()->count();
        $processing_count = BlotterReport::where('status', 'processing')->count();
        $resolved_count = BlotterReport::where('status', 'resolved')->count();
        $dropped_count = BlotterReport::where('status', 'dropped')->count();

        $incidents = $query->with(['witnesses', 'files'])->latest()->paginate(20); // FIXED: moved with() to query

        return view('admin.admin_incident_report', compact('incidents', 'total_count', 'processing_count', 'resolved_count', 'dropped_count'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reportType' => 'required|string',
            'incidentDate' => 'required|date',
            'incidentTime' => 'required',
            'incidentLocation' => 'required|string|max:255',
            'incidentDescription' => 'required|string',
            'complainantName' => 'required|string|max:255',
            'complainantContact' => 'required|string|max:11',
            'complainantAddress' => 'required|string|max:255',
            'complainantEmail' => 'nullable|email',

            // RESPONDENT OPTIONAL
            'respondentName' => 'nullable|string|max:255',
            'respondentContact' => 'nullable|string|max:11',
            'respondentAddress' => 'nullable|string|max:255',
            'respondentDescription' => 'nullable|string',

            'confidentiality' => 'required|string',

            // EVIDENCE OPTIONAL
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'videos.*' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // generate reference number
            $reference = 'BL-' . date('Y') . '-' . strtoupper(uniqid());

            $report = BlotterReport::create([
                'reference_number' => $reference,
                'report_type' => $request->reportType,
                'incident_date' => $request->incidentDate,
                'incident_time' => $request->incidentTime,
                'location' => $request->incidentLocation,
                'description' => $request->incidentDescription,
                'immediate_action' => $request->immediateAction,
                'complainant_name' => $request->complainantName,
                'complainant_contact' => $request->complainantContact,
                'complainant_address' => $request->complainantAddress,
                'complainant_email' => $request->complainantEmail,
                'respondent_name' => $request->respondentName,
                'respondent_contact' => $request->respondentContact,
                'respondent_address' => $request->respondentAddress,
                'respondent_description' => $request->respondentDescription,
                'confidentiality' => $request->confidentiality,
                'additional_info' => $request->additionalInfo,
                'status' => 'processing',
            ]);

            // save witnesses
            if ($request->witnesses) {
                foreach ($request->witnesses as $w) {
                    if (!empty($w['name']) || !empty($w['statement'])) {
                        Witness::create([
                            'blotter_report_id' => $report->id,
                            'name' => $w['name'] ?? null,
                            'contact' => $w['contact'] ?? null,
                            'statement' => $w['statement'] ?? null,
                        ]);
                    }
                }
            }

            // handle uploads
            $this->uploadFiles($request, $report);

            DB::commit();

            return redirect()->route('admin.blotter.index') // FIXED: added redirect with proper route
                ->with('success', 'Incident Report added successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to submit application. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reportType' => 'required|string',
            'incidentDate' => 'required|date',
            'incidentTime' => 'required',
            'incidentLocation' => 'required|string|max:255',
            'incidentDescription' => 'required|string',
            'complainantName' => 'required|string|max:255',
            'complainantContact' => 'required|string|max:11',
            'complainantAddress' => 'required|string|max:255',
            'complainantEmail' => 'nullable|email',

            // RESPONDENT OPTIONAL
            'respondentName' => 'nullable|string|max:255',
            'respondentContact' => 'nullable|string|max:11',
            'respondentAddress' => 'nullable|string|max:255',
            'respondentDescription' => 'nullable|string',

            'confidentiality' => 'required|string',

            // EVIDENCE OPTIONAL
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'videos.*' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $report = BlotterReport::findOrFail($id);

            // Update main report
            $report->update([
                'report_type' => $request->reportType,
                'incident_date' => $request->incidentDate,
                'incident_time' => $request->incidentTime,
                'location' => $request->incidentLocation,
                'description' => $request->incidentDescription,
                'immediate_action' => $request->immediateAction,
                'complainant_name' => $request->complainantName,
                'complainant_contact' => $request->complainantContact,
                'complainant_address' => $request->complainantAddress,
                'complainant_email' => $request->complainantEmail,
                'respondent_name' => $request->respondentName,
                'respondent_contact' => $request->respondentContact,
                'respondent_address' => $request->respondentAddress,
                'respondent_description' => $request->respondentDescription,
                'confidentiality' => $request->confidentiality,
                'additional_info' => $request->additionalInfo,
            ]);

            // Update witnesses - delete existing and add new ones
            if ($request->witnesses) {
                // Delete existing witnesses
                Witness::where('blotter_report_id', $report->id)->delete();
                
                // Add new witnesses
                foreach ($request->witnesses as $w) {
                    if (!empty($w['name']) || !empty($w['statement'])) {
                        Witness::create([
                            'blotter_report_id' => $report->id,
                            'name' => $w['name'] ?? null,
                            'contact' => $w['contact'] ?? null,
                            'statement' => $w['statement'] ?? null,
                        ]);
                    }
                }
            }

            // handle new file uploads
            $this->uploadFiles($request, $report);

            DB::commit();

            return back()->with('success', 'Incident Report updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to update report. Please try again. ' . $e->getMessage());
        }
    }

    private function uploadFiles($request, $report)
    {
        // PHOTOS
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {

                $ext = $photo->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_photo.' . $ext;

                $photo->move(public_path('uploads/evidences/photos'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'photo',
                    'file_path' => 'uploads/evidences/photos/' . $name,
                ]);
            }
        }

        // VIDEOS
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {

                $ext = $video->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_video.' . $ext;

                $video->move(public_path('uploads/evidences/videos'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'video',
                    'file_path' => 'uploads/evidences/videos/' . $name,
                ]);
            }
        }

        // DOCUMENTS
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {

                $ext = $doc->getClientOriginalExtension();
                $name = time() . '_' . uniqid() . '_document.' . $ext;

                $doc->move(public_path('uploads/evidences/documents'), $name);

                ReportFile::create([
                    'blotter_report_id' => $report->id,
                    'file_type' => 'document',
                    'file_path' => 'uploads/evidences/documents/' . $name,
                ]);
            }
        }
    }

    public function approve($id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $blotter->update(['status' => 'resolved']);

        return back()->with('success', 'Blotter resolved.');
    }

    public function reject($id)
    {
        $blotter = BlotterReport::findOrFail($id);
        $blotter->update(['status' => 'dropped']);

        return back()->with('success', 'Blotter dropped.');
    }

    public function destroy($id)
    {
        $report = BlotterReport::findOrFail($id);
        $report->delete();
        return back()->with('success', 'Blotter deleted.');
    }

    public function restore($id)
    {
        $report = BlotterReport::withTrashed()->findOrFail($id);
        $report->restore();
        
        return back()->with('success', 'Incident report restored successfully.');
    }

    public function forceDelete($id)
    {
        $report = BlotterReport::withTrashed()->findOrFail($id);
        
        // Delete associated files from storage
        foreach ($report->files as $file) {
            $filePath = public_path($file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $file->forceDelete(); // Permanently delete file records
        }
        
        // Permanently delete witnesses
        $report->witnesses()->forceDelete();
        
        // Permanently delete the report
        $report->forceDelete();
        
        return back()->with('success', 'Incident report permanently deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        BlotterReport::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records moved to trash.');
    }

    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->restore();

        return back()->with('success', 'Selected records restored successfully.');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        $reports = BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->get();
        
        foreach ($reports as $report) {
            // Delete associated files from storage
            foreach ($report->files as $file) {
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $file->forceDelete();
            }
            
            // Permanently delete witnesses
            $report->witnesses()->forceDelete();
        }
        
        // Permanently delete reports
        BlotterReport::withTrashed()
            ->whereIn('id', $request->ids)
            ->forceDelete();

        return back()->with('success', 'Selected records permanently deleted.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'exists:blotter_reports,id'
        ]);

        $query = BlotterReport::with(['witnesses', 'files']);

        if ($request->ids) {
            $query->whereIn('id', $request->ids);
        }

        $incidents = $query->get();

        $filename = 'incident_reports_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($incidents) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'ID',
                'Reference Number',
                'Report Type',
                'Incident Date',
                'Incident Time',
                'Location',
                'Description',
                'Immediate Action',
                'Complainant Name',
                'Complainant Contact',
                'Complainant Address',
                'Complainant Email',
                'Respondent Name',
                'Respondent Contact',
                'Respondent Address',
                'Respondent Description',
                'Confidentiality',
                'Additional Info',
                'Status',
                'Deleted At',
                'Created At',
                'Updated At',
                'Witnesses Count',
                'Files Count'
            ]);

            foreach ($incidents as $incident) {
                // Format values for better readability
                $confidentialityDisplay = match ($incident->confidentiality) {
                    'low' => 'Public Report',
                    'medium' => 'Confidential Report',
                    'high' => 'Anonymous Report',
                    default => ucfirst($incident->confidentiality),
                };

                $statusDisplay = match ($incident->status) {
                    'processing' => 'Processing',
                    'resolved' => 'Resolved',
                    'dropped' => 'Dropped',
                    default => ucfirst($incident->status),
                };

                $reportTypeDisplay = match ($incident->report_type) {
                    'dispute' => 'Community Dispute',
                    'security' => 'Security Concern',
                    'public' => 'Public Safety',
                    'other' => 'Other Concern',
                    default => ucfirst($incident->report_type),
                };

                fputcsv($file, [
                    $incident->id,
                    $incident->reference_number,
                    $reportTypeDisplay,
                    $incident->incident_date ? Carbon::parse($incident->incident_date)->format('Y-m-d') : '',
                    $incident->incident_time,
                    $incident->location,
                    $incident->description,
                    $incident->immediate_action,
                    $incident->complainant_name,
                    $incident->complainant_contact,
                    $incident->complainant_address,
                    $incident->complainant_email,
                    $incident->respondent_name,
                    $incident->respondent_contact,
                    $incident->respondent_address,
                    $incident->respondent_description,
                    $confidentialityDisplay,
                    $incident->additional_info,
                    $statusDisplay,
                    $incident->deleted_at ? Carbon::parse($incident->deleted_at)->format('Y-m-d H:i:s') : '',
                    $incident->created_at ? Carbon::parse($incident->created_at)->format('Y-m-d H:i:s') : '',
                    $incident->updated_at ? Carbon::parse($incident->updated_at)->format('Y-m-d H:i:s') : '',
                    $incident->witnesses->count(),
                    $incident->files->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}