<?php

namespace App\Http\Controllers;

use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\IndigencyApplication;
use App\Models\Residency;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrackRequestController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $userRequests = $user ? $this->getUserRequests($user) : [];
        $trackedRequest = null;

        $reference = $this->normalizeReference(
            $request->input('reference_number', $request->query('view', ''))
        );

        if ($reference !== '') {
            $trackedRequest = $this->protectTrackedRequest($this->searchRequest($reference));

            if (! $trackedRequest) {
                $message = $request->isMethod('post')
                    ? 'Reference number not found. Please check and try again.'
                    : 'Request not found.';

                return redirect()
                    ->route('track_request')
                    ->with('error', $message)
                    ->withInput();
            }
        }

        return view('barangay_system.track_request', compact('userRequests', 'trackedRequest'));
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reference_number' => ['required', 'string', 'max:60'],
        ], [
            'reference_number.required' => 'Please enter your reference number.',
        ]);

        $trackedRequest = $this->protectTrackedRequest($this->searchRequest(
            $this->normalizeReference($validated['reference_number'])
        ));

        if (! $trackedRequest) {
            return response()->json([
                'error' => 'Reference number not found',
                'message' => 'Reference number not found. Please check and try again.',
            ], 404);
        }

        
        return response()->json($trackedRequest);
    }

    public function print(string $reference): View|RedirectResponse
    {
        $trackedRequest = $this->protectTrackedRequest($this->searchRequest($this->normalizeReference($reference)));

        if (! $trackedRequest) {
            return redirect()
                ->route('track_request')
                ->with('error', 'Request not found.');
        }

        return view('barangay_system.print_track_request', compact('trackedRequest'));
    }

    private function searchRequest(string $reference): ?array
    {
        $reference = $this->normalizeReference($reference);

        if ($reference === '') {
            return null;
        }

        $clearance = BarangayClearance::whereRaw('UPPER(reference_number) = ?', [$reference])->first();

        if ($clearance) {
            return $this->formatDocumentRequest(
                $clearance,
                'Barangay Clearance',
                'clearance',
                is_null($clearance->fee) ? 0 : (float) $clearance->fee,
                is_null($clearance->fee)
                    ? 'Depending on purpose'
                    : 'PHP ' . number_format((float) $clearance->fee, 2)
            );
        }

        $residency = Residency::whereRaw('UPPER(reference_number) = ?', [$reference])->first();

        if ($residency) {
            return $this->formatDocumentRequest(
                $residency,
                'Certificate of Residency',
                'residency',
                0,
                'Free'
            );
        }

        $indigency = IndigencyApplication::whereRaw('UPPER(reference_number) = ?', [$reference])->first();

        if ($indigency) {
            return $this->formatDocumentRequest(
                $indigency,
                'Certificate of Indigency',
                'indigency',
                0,
                'Free'
            );
        }

        if (class_exists(BlotterReport::class)) {
            $blotter = BlotterReport::whereRaw('UPPER(reference_number) = ?', [$reference])->first();

            if ($blotter) {
                return $this->formatIncidentRequest($blotter);
            }
        }

        return null;
    }

    private function formatDocumentRequest(object $item, string $type, string $typeCode, float $amount, string $feeLabel): array
    {
        $status = $this->normalizeStatus($item->status ?? 'processing');
        $createdAt = $this->safeDate($item->created_at ?? now());
        $updatedAt = $this->safeDate($item->updated_at ?? $createdAt);

        $purpose = ($item->purpose ?? '') === 'other'
            ? ($item->purpose_other ?? 'Other')
            : ($item->purpose ?? 'Not specified');

        return [
            'user_id' => $item->user_id ?? null,
            'reference' => $item->reference_number,
            'type' => $type,
            'type_code' => $typeCode,
            'name' => $this->fullName($item),
            'date' => $createdAt->format('F d, Y'),
            'last_updated' => $updatedAt->format('F d, Y'),
            'status' => strtoupper($status),
            'status_label' => $this->statusLabel($status),
            'status_lower' => $status,
            'status_tone' => $this->statusTone($status),
            'purpose' => $purpose,
            'expected_completion' => $this->expectedDate($createdAt, 3),
            'remarks' => $item->remarks ?: $this->defaultRemark($status, 'document'),
            'next_action' => $this->nextAction($status, 'document'),
            'progress_percent' => $this->progressPercent($status, 'document'),
            'amount' => $amount,
            'fee_label' => $feeLabel,
            'email' => $this->maskEmail($item->email ?? ''),
            'contact' => $this->maskPhone($item->contact_number ?? ''),
            'request_method' => 'pickup',
            'steps' => $this->getDocumentSteps($status),
        ];
    }

    private function formatIncidentRequest(object $item): array
    {
        $status = $this->normalizeStatus($item->status ?? 'processing');
        $createdAt = $this->safeDate($item->created_at ?? now());
        $updatedAt = $this->safeDate($item->updated_at ?? $createdAt);

        return [
            'user_id' => $item->user_id ?? null,
            'reference' => $item->reference_number,
            'type' => 'Incident Report',
            'type_code' => 'incident',
            'name' => $item->complainant_name ?? '',
            'date' => $createdAt->format('F d, Y'),
            'last_updated' => $updatedAt->format('F d, Y'),
            'status' => strtoupper($status),
            'status_label' => $this->statusLabel($status),
            'status_lower' => $status,
            'status_tone' => $this->statusTone($status),
            'purpose' => $item->incident_type ?? 'Incident Report',
            'remarks' => $item->remarks ?: ($item->additional_info ?: $this->defaultRemark($status, 'incident')),
            'expected_completion' => $this->expectedDate($createdAt, 5),
            'next_action' => $this->nextAction($status, 'incident'),
            'progress_percent' => $this->progressPercent($status, 'incident'),
            'email' => $this->maskEmail($item->complainant_email ?? ''),
            'contact' => $this->maskPhone($item->complainant_contact ?? ''),
            'amount' => 0,
            'fee_label' => 'Free',
            'steps' => $this->getIncidentSteps($status),
        ];
    }

    private function getUserRequests(object $user): array
    {
        $userId = $user->id;

        $clearances = BarangayClearance::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(fn ($item) => $this->formatRequestListItem($item, 'Barangay Clearance', 'clearance', $item->email ?? ''))
            ->toArray();

        $residencies = Residency::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(fn ($item) => $this->formatRequestListItem($item, 'Certificate of Residency', 'residency', $item->email ?? ''))
            ->toArray();

        $indigencies = IndigencyApplication::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(fn ($item) => $this->formatRequestListItem($item, 'Certificate of Indigency', 'indigency', $item->email ?? ''))
            ->toArray();

        $blotters = [];

        if (class_exists(BlotterReport::class)) {
            $blotters = BlotterReport::where('user_id', $userId)
                ->latest()
                ->get()
                ->map(fn ($item) => $this->formatRequestListItem($item, 'Incident Report', 'incident', $item->complainant_email ?? ''))
                ->toArray();
        }

        $requests = array_merge($clearances, $residencies, $indigencies, $blotters);

        usort($requests, fn ($a, $b) => $b['sort_time'] <=> $a['sort_time']);

        return array_map(function ($request) {
            unset($request['sort_time']);
            return $request;
        }, $requests);
    }

    private function formatRequestListItem(object $item, string $type, string $typeCode, string $email): array
    {
        $status = $this->normalizeStatus($item->status ?? 'processing');
        $createdAt = $this->safeDate($item->created_at ?? now());
        $updatedAt = $this->safeDate($item->updated_at ?? $createdAt);
        $isIncident = $typeCode === 'incident';

        return [
            'reference' => $item->reference_number,
            'type' => $type,
            'type_code' => $typeCode,
            'date' => $createdAt->format('M d, Y'),
            'status' => strtoupper($status),
            'status_label' => $this->statusLabel($status),
            'status_lower' => $status,
            'status_tone' => $this->statusTone($status),
            'last_updated' => $updatedAt->format('M d, Y'),
            'email' => $this->maskEmail($email),
            'progress_percent' => $this->progressPercent($status, $isIncident ? 'incident' : 'document'),
            'next_action' => $this->nextAction($status, $isIncident ? 'incident' : 'document'),
            'sort_time' => $createdAt->timestamp,
        ];
    }

    private function getDocumentSteps(string $status): array
    {
        $status = $this->normalizeStatus($status);

        $steps = [
            ['label' => 'Request Submitted', 'description' => 'Your application has been received', 'key' => 'submitted'],
            ['label' => 'Under Review', 'description' => 'Barangay staff is verifying your details', 'key' => 'review'],
            ['label' => 'Processing', 'description' => 'Document is being prepared', 'key' => 'processing'],
            ['label' => 'Ready for Pickup', 'description' => 'Document ready at barangay hall', 'key' => 'ready'],
            ['label' => 'Claimed', 'description' => 'Document has been claimed', 'key' => 'claimed'],
        ];

        $activeIndex = match ($status) {
            'pending', 'submitted' => 0,
            'review', 'under review' => 1,
            'processing' => 2,
            'approved', 'ready', 'ready for pickup' => 3,
            'released', 'claimed', 'completed' => 4,
            default => 2,
        };

        return $this->applyStepStatus($steps, $activeIndex, $status);
    }

    private function getIncidentSteps(string $status): array
    {
        $status = $this->normalizeStatus($status);

        $steps = [
            ['label' => 'Report Submitted', 'description' => 'Your incident report has been received', 'key' => 'submitted'],
            ['label' => 'Under Review', 'description' => 'Barangay staff is reviewing your report', 'key' => 'review'],
            ['label' => 'Under Investigation', 'description' => 'Case is being investigated', 'key' => 'investigation'],
            ['label' => 'Resolved', 'description' => 'Incident has been resolved', 'key' => 'resolved'],
            ['label' => 'Closed', 'description' => 'Case has been closed', 'key' => 'closed'],
        ];

        $activeIndex = match ($status) {
            'pending', 'submitted' => 0,
            'processing', 'review', 'under review' => 1,
            'investigating', 'under investigation' => 2,
            'resolved' => 3,
            'closed', 'completed' => 4,
            default => 1,
        };

        return $this->applyStepStatus($steps, $activeIndex, $status);
    }

    private function applyStepStatus(array $steps, int $activeIndex, string $status): array
    {
        if (in_array($status, ['rejected', 'denied', 'cancelled'], true)) {
            return array_map(function ($step, $index) {
                $step['status'] = $index === 0 ? 'completed' : 'pending';
                return $step;
            }, $steps, array_keys($steps));
        }

        return array_map(function ($step, $index) use ($activeIndex) {
            $step['status'] = $index < $activeIndex ? 'completed' : ($index === $activeIndex ? 'active' : 'pending');
            return $step;
        }, $steps, array_keys($steps));
    }

    private function normalizeReference(?string $reference): string
    {
        return strtoupper(trim((string) $reference));
    }

    private function normalizeStatus(?string $status): string
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            '', 'new' => 'processing',
            'for pickup' => 'ready for pickup',
            'release' => 'released',
            default => $status,
        };
    }

    private function statusLabel(string $status): string
    {
        return match ($this->normalizeStatus($status)) {
            'pending', 'submitted' => 'Submitted',
            'review', 'under review' => 'Under Review',
            'processing' => 'Processing',
            'approved', 'ready', 'ready for pickup' => 'Ready for Pickup',
            'released', 'claimed', 'completed' => 'Completed',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            'rejected', 'denied' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }

    private function statusTone(string $status): string
    {
        return match ($this->normalizeStatus($status)) {
            'approved', 'ready', 'ready for pickup', 'released', 'claimed', 'completed', 'resolved', 'closed' => 'success',
            'rejected', 'denied', 'cancelled' => 'danger',
            'processing', 'review', 'under review', 'investigating', 'under investigation' => 'warning',
            default => 'neutral',
        };
    }

    private function progressPercent(string $status, string $type): int
    {
        $status = $this->normalizeStatus($status);

        if (in_array($status, ['rejected', 'denied', 'cancelled'], true)) {
            return 100;
        }

        if ($type === 'incident') {
            return match ($status) {
                'pending', 'submitted' => 20,
                'processing', 'review', 'under review' => 40,
                'investigating', 'under investigation' => 65,
                'resolved' => 85,
                'closed', 'completed' => 100,
                default => 40,
            };
        }

        return match ($status) {
            'pending', 'submitted' => 20,
            'review', 'under review' => 40,
            'processing' => 60,
            'approved', 'ready', 'ready for pickup' => 85,
            'released', 'claimed', 'completed' => 100,
            default => 60,
        };
    }

    private function nextAction(string $status, string $type): string
    {
        $status = $this->normalizeStatus($status);

        if (in_array($status, ['rejected', 'denied'], true)) {
            return 'Please review the remarks or contact the barangay for clarification.';
        }

        if ($type === 'incident') {
            return match ($status) {
                'pending', 'submitted', 'processing', 'review', 'under review' => 'Wait for barangay staff to review your report. You may be contacted for more details.',
                'investigating', 'under investigation' => 'Keep your contact line open in case the barangay needs additional information.',
                'resolved' => 'Review the resolution or visit the barangay hall if follow-up is needed.',
                'closed', 'completed' => 'No further action is needed unless you need a printed copy.',
                default => 'Check this page for updates.',
            };
        }

        return match ($status) {
            'pending', 'submitted', 'review', 'under review', 'processing' => 'Wait for confirmation. You will be notified once the document is ready.',
            'approved', 'ready', 'ready for pickup' => 'Bring a valid ID and claim your document at the barangay hall.',
            'released', 'claimed', 'completed' => 'No further action is needed.',
            default => 'Check this page for updates.',
        };
    }

    private function defaultRemark(string $status, string $type): string
    {
        $status = $this->normalizeStatus($status);

        if (in_array($status, ['rejected', 'denied'], true)) {
            return 'Please contact the barangay office for the reason and next steps.';
        }

        return $type === 'incident'
            ? 'Your report is being reviewed by the barangay.'
            : 'Your application is being processed.';
    }

    private function expectedDate(Carbon $createdAt, int $businessDays): string
    {
        return $createdAt->copy()->addWeekdays($businessDays)->format('F d, Y');
    }

    private function safeDate(mixed $date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        return Carbon::parse($date ?? now());
    }

    private function fullName(object $item): string
    {
        return trim(collect([
            $item->first_name ?? '',
            $item->middle_name ?? '',
            $item->last_name ?? '',
            $item->suffix ?? '',
        ])->filter()->implode(' '));
    }

    private function maskEmail(?string $email): string
    {
        $email = trim((string) $email);

        if ($email === '' || ! str_contains($email, '@')) {
            return $email;
        }

        [$name, $domain] = explode('@', $email, 2);
        $visibleLength = min(2, mb_strlen($name));
        $visible = mb_substr($name, 0, $visibleLength);
        $hiddenLength = max(2, mb_strlen($name) - $visibleLength);

        return $visible . str_repeat('*', $hiddenLength) . '@' . $domain;
    }

    private function maskPhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (strlen($digits) < 4) {
            return $phone ?: '';
        }

        return str_repeat('*', max(0, strlen($digits) - 4)) . substr($digits, -4);
    }
    private function protectTrackedRequest(?array $trackedRequest): ?array
    {
        if (!$trackedRequest) {
            return null;
        }

        $user = Auth::user();
        $ownerId = $trackedRequest['user_id'] ?? null;

        if ($user && $ownerId && (int) $ownerId === (int) $user->id) {
            return $trackedRequest;
        }

        // Public tracking intentionally exposes status only. Personal details, remarks,
        // contact information and incident narratives require the owning account.
        return [
            'reference' => $trackedRequest['reference'],
            'type' => $trackedRequest['type'],
            'type_code' => $trackedRequest['type_code'],
            'date' => $trackedRequest['date'],
            'last_updated' => $trackedRequest['last_updated'],
            'status' => $trackedRequest['status'],
            'status_label' => $trackedRequest['status_label'],
            'status_lower' => $trackedRequest['status_lower'],
            'status_tone' => $trackedRequest['status_tone'],
            'expected_completion' => $trackedRequest['expected_completion'] ?? null,
            'progress_percent' => $trackedRequest['progress_percent'],
            'next_action' => $trackedRequest['next_action'],
            'fee_label' => $trackedRequest['fee_label'] ?? null,
            'amount' => $trackedRequest['amount'] ?? 0,
            'steps' => $trackedRequest['steps'],
            'public_tracking' => true,
        ];
    }

}
