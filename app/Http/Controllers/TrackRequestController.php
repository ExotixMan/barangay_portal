<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangayClearance;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BlotterReport;
use Illuminate\Support\Facades\Auth;

class TrackRequestController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $userRequests = [];
        $trackedRequest = null;
        
        if ($user) {
            // Get all requests for the logged-in user
            $userRequests = $this->getUserRequests($user);
        }
        
        // Check if this is a POST request with reference number (form submission)
        if ($request->isMethod('post') && $request->has('reference_number')) {
            $trackedRequest = $this->searchRequest($request->reference_number);
            
            if (!$trackedRequest) {
                return redirect()->route('track.request')
                    ->with('error', 'Reference number not found. Please check and try again.')
                    ->withInput();
            }
        }
        
        // Check if there's a reference in the query string (from view button)
        if ($request->has('view')) {
            $trackedRequest = $this->searchRequest($request->view);
            
            if (!$trackedRequest) {
                return redirect()->route('track.request')
                    ->with('error', 'Request not found');
            }
        }
        
        return view('barangay_system.track_request', compact('userRequests', 'trackedRequest'));
    }

    private function searchRequest($reference)
    {
        // Search in Barangay Clearance
        $clearance = BarangayClearance::where('reference_number', $reference)->first();
        if ($clearance) {
            $status = ucfirst($clearance->status ?? 'processing');
            return [
                'reference' => $clearance->reference_number,
                'type' => 'Barangay Clearance',
                'type_code' => 'clearance',
                'name' => trim($clearance->first_name . ' ' . ($clearance->middle_name ?? '') . ' ' . $clearance->last_name . ($clearance->suffix ? ' ' . $clearance->suffix : '')),
                'date' => $clearance->created_at->format('F d, Y'),
                'status' => $status,
                'status_lower' => strtolower($status),
                'purpose' => $clearance->purpose == 'other' ? ($clearance->purpose_other ?? 'Other') : $clearance->purpose,
                'expected_completion' => $clearance->created_at->addDays(3)->format('F d, Y'),
                'remarks' => $clearance->remarks ?? 'Your application is being processed',
                'amount' => is_null($clearance->fee) ? 0 : (float) $clearance->fee,
                'fee_label' => is_null($clearance->fee)
                    ? 'Depending on purpose'
                    : 'PHP ' . number_format((float) $clearance->fee, 2),
                'email' => $clearance->email,
                'contact' => $clearance->contact_number,
                'request_method' => 'pickup',
                'steps' => $this->getDocumentSteps($status)
            ];
        }

        // Search in Residency
        $residency = ResidencyApplication::where('reference_number', $reference)->first();
        if ($residency) {
            $status = ucfirst($residency->status ?? 'processing');
            return [
                'reference' => $residency->reference_number,
                'type' => 'Certificate of Residency',
                'type_code' => 'residency',
                'name' => trim($residency->first_name . ' ' . ($residency->middle_name ?? '') . ' ' . $residency->last_name . ($residency->suffix ? ' ' . $residency->suffix : '')),
                'date' => $residency->created_at->format('F d, Y'),
                'status' => $status,
                'status_lower' => strtolower($status),
                'purpose' => $residency->purpose == 'other' ? ($residency->purpose_other ?? 'Other') : $residency->purpose,
                'expected_completion' => $residency->created_at->addDays(3)->format('F d, Y'),
                'remarks' => $residency->remarks ?? 'Your application is being processed',
                'amount' => 0,
                'fee_label' => 'Free',
                'email' => $residency->email,
                'contact' => $residency->contact_number,
                'request_method' => 'pickup',
                'steps' => $this->getDocumentSteps($status)
            ];
        }

        // Search in Indigency
        $indigency = IndigencyApplication::where('reference_number', $reference)->first();
        if ($indigency) {
            $status = ucfirst($indigency->status ?? 'processing');
            return [
                'reference' => $indigency->reference_number,
                'type' => 'Certificate of Indigency',
                'type_code' => 'indigency',
                'name' => trim($indigency->first_name . ' ' . ($indigency->middle_name ?? '') . ' ' . $indigency->last_name . ($indigency->suffix ? ' ' . $indigency->suffix : '')),
                'date' => $indigency->created_at->format('F d, Y'),
                'status' => $status,
                'status_lower' => strtolower($status),
                'purpose' => $indigency->purpose == 'other' ? ($indigency->purpose_other ?? 'Other') : $indigency->purpose,
                'expected_completion' => $indigency->created_at->addDays(3)->format('F d, Y'),
                'remarks' => $indigency->remarks ?? 'Your application is being processed',
                'amount' => 0,
                'fee_label' => 'Free',
                'email' => $indigency->email,
                'contact' => $indigency->contact_number,
                'request_method' => 'pickup',
                'steps' => $this->getDocumentSteps($status)
            ];
        }

        // Search in incident reports
        if (class_exists('App\Models\BlotterReport')) {
            $blotter = BlotterReport::where('reference_number', $reference)
                ->first();
            if ($blotter) {
                $status = $blotter->status ?? 'processing';
                return [
                    'reference' => $blotter->reference_number,
                    'type' => 'Incident Report',
                    'type_code' => 'incident',
                    'name' => $blotter->complainant_name ?? '',
                    'date' => $blotter->created_at->format('F d, Y'),
                    'status' => $status,
                    'status_lower' => strtolower($status),
                    'purpose' => $blotter->incident_type ?? 'Incident Report',
                    'remarks' => $blotter->remarks ?? $blotter->additional_info ?? 'Under investigation',
                    'expected_completion' => $blotter->created_at->addDays(5)->format('F d, Y'),
                    'email' => $blotter->complainant_email ?? '',
                    'contact' => $blotter->complainant_contact ?? '',
                    'amount' => 0,
                    'steps' => $this->getIncidentSteps($status)
                ];
            }
        }

        return null;
    }

    private function getDocumentSteps($status)
    {
        $statusLower = strtolower($status);
        
        // Define all possible steps
        $allSteps = [
            ['label' => 'Request Submitted', 'description' => 'Your application has been received', 'key' => 'submitted'],
            ['label' => 'Under Review', 'description' => 'Barangay staff is verifying your details', 'key' => 'review'],
            ['label' => 'Processing', 'description' => 'Document is being prepared', 'key' => 'processing'],
            ['label' => 'Ready for Pickup', 'description' => 'Document ready at barangay hall', 'key' => 'ready'],
            ['label' => 'Claimed', 'description' => 'Document has been claimed', 'key' => 'claimed'],
        ];
        
        $steps = [];
        
        // Determine which steps are completed based on actual status
        foreach ($allSteps as $index => $step) {
            $stepStatus = 'pending';
            
            // All steps up to current status should be completed
            if ($statusLower == 'processing') {
                // Processing means: Submitted and Under Review are completed
                if ($index <= 1) {
                    $stepStatus = 'completed';
                } elseif ($index == 2) {
                    $stepStatus = 'active';
                }
            } 
            elseif ($statusLower == 'approved' || $statusLower == 'ready for pickup') {
                // Approved/Ready for Pickup means: Submitted, Under Review, Processing are completed
                if ($index <= 2) {
                    $stepStatus = 'completed';
                } elseif ($index == 3) {
                    $stepStatus = 'active';
                }
            }
            elseif ($statusLower == 'claimed' || $statusLower == 'released' || $statusLower == 'completed') {
                // Claimed means all steps are completed
                $stepStatus = 'completed';
            }
            elseif ($statusLower == 'rejected' || $statusLower == 'denied') {
                // Rejected - only first step completed, rest pending with rejection note
                if ($index == 0) {
                    $stepStatus = 'completed';
                } else {
                    $stepStatus = 'pending';
                }
            }
            
            $steps[] = array_merge($step, ['status' => $stepStatus]);
        }
        
        return $steps;
    }

    private function getIncidentSteps($status)
    {
        $statusLower = strtolower($status);
        
        $steps = [
            ['label' => 'Report Submitted', 'description' => 'Your incident report has been received', 'key' => 'submitted'],
            ['label' => 'Under Investigation', 'description' => 'Case is being investigated', 'key' => 'investigation'],
            ['label' => 'Resolved', 'description' => 'Incident has been resolved', 'key' => 'resolved'],
            ['label' => 'Closed', 'description' => 'Case has been closed', 'key' => 'closed'],
        ];
        
        foreach ($steps as $index => $step) {
            $stepStatus = 'pending';
            
            if ($statusLower == 'processing') {
                if ($index == 0) {
                    $stepStatus = 'completed';
                } elseif ($index == 1) {
                    $stepStatus = 'active';
                }
            }
            elseif ($statusLower == 'resolved') {
                if ($index <= 1) {
                    $stepStatus = 'completed';
                } elseif ($index == 2) {
                    $stepStatus = 'active';
                }
            }
            elseif ($statusLower == 'closed') {
                if ($index <= 2) {
                    $stepStatus = 'completed';
                } elseif ($index == 3) {
                    $stepStatus = 'active';
                }
            }
            
            $steps[$index]['status'] = $stepStatus;
        }
        
        return $steps;
    }

    public function search(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string'
        ]);

        $trackedRequest = $this->searchRequest($request->reference_number);
        
        if (!$trackedRequest) {
            return response()->json(['error' => 'Reference number not found'], 404);
        }

        return response()->json($trackedRequest);
    }

    /**
     * Print request details
     */
    public function print($reference)
    {
        $trackedRequest = $this->searchRequest($reference);

        if (!$trackedRequest) {
            return redirect()->route('track.request')->with('error', 'Request not found');
        }

        return view('barangay_system.print_track_request', compact('trackedRequest'));
    }

    private function getUserRequests($user)
    {
        $requests = [];
        $email = $user->email;
        $contact = $user->contact_number ?? '';

        // Get clearance applications
        $clearances = BarangayClearance::where('email', $email)
            ->orWhere('contact_number', $contact)
            ->get()
            ->map(function($item) {
                return [
                    'reference' => $item->reference_number,
                    'type' => 'Barangay Clearance',
                    'type_code' => 'clearance',
                    'date' => $item->created_at->format('M d, Y'),
                    'status' => ucfirst($item->status ?? 'processing'),
                    'status_lower' => strtolower($item->status ?? 'processing'),
                    'last_updated' => $item->updated_at->format('M d, Y'),
                    'email' => $item->email,
                ];
            })->toArray();
        
        // Get residency applications
        $residencies = ResidencyApplication::where('email', $email)
            ->orWhere('contact_number', $contact)
            ->get()
            ->map(function($item) {
                return [
                    'reference' => $item->reference_number,
                    'type' => 'Certificate of Residency',
                    'type_code' => 'residency',
                    'date' => $item->created_at->format('M d, Y'),
                    'status' => ucfirst($item->status ?? 'processing'),
                    'status_lower' => strtolower($item->status ?? 'processing'),
                    'last_updated' => $item->updated_at->format('M d, Y'),
                    'email' => $item->email,
                ];
            })->toArray();
        
        // Get indigency applications
        $indigencies = IndigencyApplication::where('email', $email)
            ->orWhere('contact_number', $contact)
            ->get()
            ->map(function($item) {
                return [
                    'reference' => $item->reference_number,
                    'type' => 'Certificate of Indigency',
                    'type_code' => 'indigency',
                    'date' => $item->created_at->format('M d, Y'),
                    'status' => ucfirst($item->status ?? 'processing'),
                    'status_lower' => strtolower($item->status ?? 'processing'),
                    'last_updated' => $item->updated_at->format('M d, Y'),
                    'email' => $item->email,
                ];
            })->toArray();
        
        // Get incident reports if model exists
        $blotters = [];
        if (class_exists('App\Models\BlotterReport')) {
            $blotters = BlotterReport::where('complainant_email', $email)
                ->orWhere('complainant_contact', $contact)
                ->get()
                ->map(function($item) {
                    return [
                        'reference' => $item->reference_number,
                        'type' => 'Incident Report',
                        'type_code' => 'incident',
                        'date' => $item->created_at->format('M d, Y'),
                        'status' => $item->status ?? 'processing',
                        'status_lower' => strtolower($item->status ?? 'processing'),
                        'last_updated' => $item->updated_at->format('M d, Y'),
                        'email' => $item->complainant_email
                    ];
                })->toArray();
        }
        
        // Merge all requests
        $requests = array_merge($clearances, $residencies, $indigencies, $blotters);
        
        // Sort by date (most recent first)
        usort($requests, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $requests;
    }
}