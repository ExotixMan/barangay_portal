<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangayClearance;
use App\Models\ResidencyApplication;
use App\Models\IndigencyApplication;
use App\Models\BlotterReport;

class TrackRequestController extends Controller
{
    public function track(Request $request)
    {
        $reference = $request->reference_number;

        // Search in all tables
        $clearance = BarangayClearance::where('reference_number', $reference)->first();
        if ($clearance) {
            return response()->json([
                'type' => 'Barangay Clearance',
                'name' => $clearance->first_name.' '.$clearance->last_name,
                'status' => $clearance->status,
                'date' => $clearance->created_at,
                'reference' => $clearance->reference_number,
                'amount' => '100',
                'timeline' => $this->buildTimeline('Barangay Clearance', $clearance->status, $clearance->created_at)
            ]);
        }

        $residency = ResidencyApplication::where('reference_number', $reference)->first();
        if ($residency) {
            return response()->json([
                'type' => 'Certificate of Residency',
                'name' => $residency->first_name.' '.$residency->last_name,
                'status' => $residency->status,
                'date' => $residency->created_at,
                'reference' => $residency->reference_number,
                'amount' => '50',
                'timeline' => $this->buildTimeline('Residency', $residency->status, $residency->created_at)
            ]);
        }

        $indigency = IndigencyApplication::where('reference_number', $reference)->first();
        if ($indigency) {
            return response()->json([
                'type' => 'Certificate of Indigency',
                'name' => $indigency->first_name.' '.$indigency->last_name,
                'status' => $indigency->status,
                'date' => $indigency->created_at,
                'reference' => $indigency->reference_number,
                'amount' => '0',
                'timeline' => $this->buildTimeline('Indigency', $indigency->status, $indigency->created_at)
            ]);
        }

        $blotter = BlotterReport::where('reference_number', $reference)->first();
        if ($blotter) {
            return response()->json([
                'type' => 'Blotter Report',
                'name' => $blotter->complainant_name,
                'status' => $blotter->status,
                'date' => $blotter->created_at,
                'reference' => $blotter->reference_number,
                'amount' => '0',
                'timeline' => $this->buildTimeline('Blotter Report', $blotter->status, $blotter->created_at)
            ]);
        }

        return response()->json([
            'error' => 'Reference number not found'
        ], 404);
    }

    private function buildTimeline($type, $status, $created_at) {
        $timeline = [
            'submitted' => ['status' => 'completed', 'date_time' => $created_at->format('M d, Y')],
            'verified' => ['status' => null, 'date_time' => null],
            'processing' => ['status' => null, 'date_time' => null],
            'ready' => ['status' => null, 'date_time' => null],
            'completed' => ['status' => null, 'date_time' => null],
        ];

        $status = strtolower($status);

        if (in_array($type, ['Barangay Clearance', 'Residency', 'Indigency'])) {
            switch ($status) {
                case 'processing':
                    $timeline['verified'] = ['status' => 'processing', 'date_time' => ''];
                    $timeline['processing'] = ['status' => 'processing', 'date_time' => ''];
                    break;
                case 'approved':
                    $timeline['verified'] = ['status' => 'completed', 'date_time' => 'Verified'];
                    $timeline['processing'] = ['status' => 'completed', 'date_time' => 'Processed'];
                    $timeline['ready'] = ['status' => 'completed', 'date_time' => 'Ready'];
                    $timeline['completed'] = ['status' => 'pending', 'date_time' => 'Completed'];
                    break;
                case 'rejected':
                    $timeline['verified'] = ['status' => 'completed', 'date_time' => 'Verified'];
                    $timeline['processing'] = ['status' => 'rejected', 'date_time' => 'Rejected'];
                    $timeline['completed'] = ['status' => 'rejected', 'date_time' => 'Rejected'];
                    break;
            }
        } elseif ($type === 'Blotter Report') {
            switch ($status) {
                case 'processing':
                    $timeline['verified'] = ['status' => 'processing', 'date_time' => ''];
                    $timeline['processing'] = ['status' => 'processing', 'date_time' => ''];
                    break;
                case 'resolved':
                    $timeline['verified'] = ['status' => 'completed', 'date_time' => 'Verified'];
                    $timeline['processing'] = ['status' => 'completed', 'date_time' => 'Processed'];
                    $timeline['completed'] = ['status' => 'resolved', 'date_time' => 'Resolved'];
                    break;
                case 'dropped':
                    $timeline['verified'] = ['status' => 'completed', 'date_time' => 'Verified'];
                    $timeline['processing'] = ['status' => 'dropped', 'date_time' => 'Dropped'];
                    $timeline['completed'] = ['status' => 'dropped', 'date_time' => 'Dropped'];
                    break;
            }
        }

        return $timeline;
    }
}
