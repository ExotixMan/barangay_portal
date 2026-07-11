<?php

namespace App\Http\Controllers;

use App\Models\BarangayClearance;
use App\Models\ResidentProfileCorrectionConcern;
use App\Models\Residents;
use App\Support\NotifiesAdmins;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class BarangayClearanceController extends Controller
{
    use NotifiesAdmins;

    public function index()
    {
        if (session('submitted_application')) {
            return redirect()->route('success', [
                'service' => 'Clearance',
                'reference' => session('reference_number'),
            ]);
        }

        $resident = Auth::user();

        if (! $resident) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'Please log in before applying for a barangay clearance.']);
        }

        return view('barangay_system.clearance_form', compact('resident'));
    }

    public function store(Request $request)
    {
        $resident = Auth::user();

        if (! $resident) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'Please log in before submitting a clearance request.']);
        }

        $missingProfileFields = $this->getMissingProfileFields($resident);

        if (! empty($missingProfileFields)) {
            return back()
                ->withErrors([
                    'profile' => 'Your resident profile is incomplete: ' . implode(', ', $missingProfileFields) . '. Please contact the barangay office to correct your information.',
                ])
                ->withInput();
        }

        $hasVerifiedValidId = $this->isTruthy($resident->valid_id_verified ?? false);
        $hasVerifiedResidency = $this->isTruthy($resident->residency_verified ?? false);
        $residentValidIdPath = $this->getResidentValidIdPath($resident);

        $rules = [
            'profile_confirmed' => ['accepted'],

            // Clearance-specific fields only
            'purpose' => ['required', 'string', 'max:255'],
            'purpose_other' => ['nullable', 'required_if:purpose,other', 'string', 'max:500'],

            'terms' => ['accepted'],
            'privacy' => ['accepted'],
            'pickup' => ['accepted'],
        ];

        if (! $hasVerifiedResidency) {
            $rules['primary_proof'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        } else {
            $rules['primary_proof'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        }

        if (! $hasVerifiedValidId || blank($residentValidIdPath)) {
            $rules['valid_id_path'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        } else {
            $rules['valid_id_path'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        }

        $validated = $request->validate($rules);

        try {
            $referenceNumber = $this->generateReferenceNumber();

            $primaryProofPath = null;
            $validIdPath = $residentValidIdPath;

            if ($request->hasFile('primary_proof')) {
                $primaryProofPath = $this->uploadFile(
                    $request->file('primary_proof'),
                    'uploads/proof_of_residency/clearance',
                    'proof'
                );
            }

            if ($request->hasFile('valid_id_path')) {
                $validIdPath = $this->uploadFile(
                    $request->file('valid_id_path'),
                    'uploads/valid_id/clearance',
                    'valid_id'
                );
            }

            $fee = $this->getClearanceFeeByPurpose($validated['purpose']);
            $fullName = $this->formatFullName($resident);

            $clearanceData = [
                'user_id' => $resident->id,

                // Snapshot from resident profile
                'first_name' => $resident->firstname,
                'middle_name' => $resident->middlename,
                'last_name' => $resident->lastname,
                'suffix' => $resident->suffix,
                'birthdate' => $resident->birthdate,
                'gender' => $resident->gender ?? null,
                'address' => $resident->address,
                'contact_number' => $resident->contact,
                'email' => $resident->email,

                // Clearance-specific data
                'primary_proof' => $primaryProofPath,
                'valid_id_path' => $validIdPath,
                'purpose' => $validated['purpose'],
                'purpose_other' => $validated['purpose_other'] ?? null,
                'fee' => $fee,

                'reference_number' => $referenceNumber,
                'status' => 'processing',
            ];

            $application = BarangayClearance::create($clearanceData);

            $this->notifyAdminsOfSubmission(
                category: 'request',
                title: 'New barangay clearance request',
                message: $fullName . ' submitted ' . $referenceNumber . '.',
                url: route('admin.clearance.index', ['highlight' => $application->id]),
                icon: 'fas fa-file-shield',
            );

            session([
                'route' => 'clearance',
                'applicant_name' => $fullName,
                'date_submitted' => now()->format('F d, Y'),
                'status' => 'Submitted for Processing',
                'amount' => $fee,
                'fee_label' => $fee > 0
                    ? '₱' . number_format($fee, 2)
                    : 'To be assessed at Barangay Hall',
                'reference_number' => $referenceNumber,
                'submitted_application' => true,
            ]);

            return redirect()->route('success', [
                'service' => 'Clearance',
                'reference' => $referenceNumber,
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors([
                    'submit' => 'Unable to submit your clearance request right now. Please try again or contact the barangay office.',
                ])
                ->withInput();
        }
    }

    public function reportIncorrectProfile(Request $request)
    {
        $resident = Auth::user();

        if (! $resident) {
            return redirect()->route('login.res')
                ->withErrors(['auth' => 'Please log in before reporting incorrect profile information.']);
        }

        $validated = $request->validate([
            'field_name' => [
                'required',
                'string',
                Rule::in([
                    'Full name',
                    'Birthdate',
                    'Gender',
                    'Civil Status',
                    'Place of Birth',
                    'Complete address',
                    'Contact number',
                    'Email address',
                ]),
            ],
            'correct_value' => ['required', 'string', 'min:2', 'max:1000'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $concern = DB::transaction(function () use ($resident, $validated) {
            $resident = Residents::whereKey($resident->id)
                ->lockForUpdate()
                ->firstOrFail();

            $existingPending = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->whereIn('status', ['pending', 'reviewed'])
                ->latest()
                ->first();

            if ($existingPending) {
                return redirect()
                    ->back()
                    ->with('warning', 'You already have a pending profile correction concern. Please wait for barangay review before submitting another correction.')
                    ->with('pending_correction_id', $existingPending->id)
                    ->with('open_tab', 'info');
            }

            $latestConcern = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->latest()
                ->first();

            if ($latestConcern && $latestConcern->created_at->gt(now()->subMinutes(10))) {
                return redirect()
                    ->back()
                    ->with('warning', 'Please wait at least 10 minutes before submitting another profile correction concern.')
                    ->with('open_tab', 'info');
            }

            $todayCount = ResidentProfileCorrectionConcern::query()
                ->where('resident_id', $resident->id)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= 3) {
                return redirect()
                    ->back()
                    ->with('warning', 'You have reached the daily limit for profile correction concerns. Please try again tomorrow or contact the barangay office.')
                    ->with('open_tab', 'info');
            }

            $currentValue = match ($validated['field_name']) {
                'Full name' => $this->formatFullName($resident),
                'Birthdate' => (string) $resident->birthdate,
                'Gender' => (string) ($resident->gender ?? ''),
                'Civil Status' => (string) ($resident->civil_status ?? ''),
                'Place of Birth' => (string) ($resident->birth_place ?? $resident->place_of_birth ?? ''),
                'Complete address' => (string) $resident->address,
                'Contact number' => (string) $resident->contact,
                'Email address' => (string) $resident->email,
                default => '',
            };

            if (
                mb_strtolower(trim($currentValue)) ===
                mb_strtolower(trim($validated['correct_value']))
            ) {
                return redirect()
                    ->back()
                    ->with('warning', 'The correct value you entered is the same as your current profile value.')
                    ->with('open_tab', 'info');
            }

            return ResidentProfileCorrectionConcern::create([
                'resident_id' => $resident->id,
                'service' => 'Profile Correction',
                'field_name' => $validated['field_name'],
                'current_value' => $currentValue,
                'correct_value' => trim($validated['correct_value']),
                'message' => $validated['message'] ?? null,
                'status' => 'pending',
            ]);
        });

        if ($concern instanceof \Illuminate\Http\RedirectResponse) {
            return $concern;
        }

        $this->notifyAdminsOfSubmission(
            category: 'profile_correction',
            title: 'New profile correction concern',
            message: $this->formatFullName($resident) . ' requested a correction for ' .
                $concern->field_name . '.',
            url: route('admin.residents.index', ['profile_correction' => $concern->id]),
            icon: 'fas fa-user-pen',
        );

        $barangayEmail = config(
            'services.barangay.support_email',
            env('BISIG_CONTACT_EMAIL', 'barangayhulongduhat@gmail.com')
        );

        $subject = 'Resident Profile Correction Request - ' . $this->formatFullName($resident);

        $body = "A resident reported incorrect profile information.\n\n"
            . "Service: Profile Correction\n"
            . "Concern ID: PCC-" . now()->format('Y') . '-' . str_pad((string) $concern->id, 6, '0', STR_PAD_LEFT) . "\n"
            . "Resident ID: {$resident->id}\n"
            . "Current Name: {$this->formatFullName($resident)}\n"
            . "Current Email: {$resident->email}\n"
            . "Current Contact: {$resident->contact}\n\n"
            . "Field to Correct: {$concern->field_name}\n"
            . "Current Value: {$concern->current_value}\n"
            . "Requested Correct Value: {$concern->correct_value}\n\n"
            . "Message:\n"
            . ($concern->message ?: 'No additional message provided.')
            . "\n\n"
            . "Please verify this request before changing any locked identity or residency information.";

        try {
            Mail::raw($body, function ($message) use ($barangayEmail, $subject, $resident) {
                $message->to($barangayEmail)
                    ->subject($subject);

                if (! blank($resident->email)) {
                    $message->replyTo($resident->email);
                }
            });
        } catch (Throwable $e) {
            report($e);

            return back()
                ->with('success', 'Your correction concern was saved for barangay review.')
                ->with('warning', 'However, the email notification could not be sent. Barangay staff can still see the concern in the system.')
                ->with('open_tab', 'info');
        }

        return back()
            ->with('success', 'Your correction concern was submitted successfully. Please wait for barangay review.')
            ->with('open_tab', 'info');
    }

    private function getMissingProfileFields(object $resident): array
    {
        $missing = [];

        if (blank($resident->firstname)) {
            $missing[] = 'First name';
        }

        if (blank($resident->lastname)) {
            $missing[] = 'Last name';
        }

        if (blank($resident->birthdate)) {
            $missing[] = 'Birthdate';
        }

        if (blank($resident->gender ?? null)) {
            $missing[] = 'Gender';
        }

        if (blank($resident->address)) {
            $missing[] = 'Complete address';
        }

        if (blank($resident->contact)) {
            $missing[] = 'Contact number';
        }

        if (blank($resident->email)) {
            $missing[] = 'Email address';
        }

        return $missing;
    }

    private function getResidentValidIdPath(object $resident): ?string
    {
        return $resident->valid_id_path
            ?? $resident->valid_id
            ?? $resident->valid_id_front
            ?? null;
    }

    private function uploadFile(UploadedFile $file, string $folder, string $prefix): string
    {
        File::ensureDirectoryExists(public_path($folder));

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(10) . '.' . $extension;

        $file->move(public_path($folder), $filename);

        return $folder . '/' . $filename;
    }

    private function generateReferenceNumber(): string
    {
        do {
            $referenceNumber = 'BC-' . now()->format('Y') . '-' . strtoupper(Str::random(6));
        } while (BarangayClearance::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }

    private function formatFullName(object $resident): string
    {
        return collect([
            $resident->firstname,
            $resident->middlename,
            $resident->lastname,
            $resident->suffix,
        ])->filter()->implode(' ');
    }

    private function getCurrentProfileValue(object $resident, string $fieldName): string
    {
        return match ($fieldName) {
            'Full name' => $this->formatFullName($resident),
            'Birthdate' => (string) $resident->birthdate,
            'Gender' => (string) ($resident->gender ?? ''),
            'Complete address' => (string) $resident->address,
            'Contact number' => (string) $resident->contact,
            'Email address' => (string) $resident->email,
            default => '',
        };
    }

    private function getClearanceFeeByPurpose(string $purpose): float
    {
        return match ($purpose) {
            default => '',
        };
    }

    private function isTruthy(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 'true', 'yes', 'verified'], true);
    }
}
