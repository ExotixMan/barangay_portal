<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Residents;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Throwable;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $residentNotificationStats = $this->buildResidentNotificationStats();

        $query = Announcement::query();

        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $search = preg_replace('/[^a-zA-Z0-9\s\-\.@,]/', '', $search);
            $searchLike = '%' . strtolower($search) . '%';

            $query->where(function ($q) use ($searchLike) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(content) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(category) LIKE ?', [$searchLike])
                  ->orWhereRaw('CAST(status AS TEXT) ILIKE ?', [$searchLike]);
            });
        }

        $status = strtolower(trim((string) $request->get('status', '')));
        if ($status !== '' && in_array($status, ['published', 'draft', 'archived', 'deleted'], true)) {
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $status);
            }
        }

        $category = strtolower(trim((string) $request->get('category', '')));
        if ($category !== '') {
            $query->whereRaw('LOWER(category) = ?', [$category]);
        }

        if ($request->has('featured') && $request->featured !== '') {
            $isFeaturedFilter = filter_var($request->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($isFeaturedFilter)) {
                $query->whereRaw($isFeaturedFilter ? 'is_featured IS TRUE' : 'is_featured IS FALSE');
            }
        }

        $total_count = Announcement::withTrashed()->count();
        $published_count = Announcement::where('status', 'published')->count();
        $draft_count = Announcement::where('status', 'draft')->count();
        $featured_count = Announcement::whereRaw('is_featured IS TRUE')->count();

        $sort = $request->get('sort', 'created_at');
        $direction = strtolower((string) $request->get('direction', 'desc'));
        $direction = $direction === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['title', 'category', 'is_featured', 'views', 'published_at', 'created_at', 'status'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'status') {
            $query->orderByRaw("CASE status WHEN 'published' THEN 1 WHEN 'draft' THEN 2 WHEN 'archived' THEN 3 ELSE 4 END {$direction}");
        } elseif ($sort === 'is_featured') {
            $query->orderBy('is_featured', $direction);
        } elseif ($sort === 'published_at') {
            
            $query->orderByRaw('published_at IS NULL ASC')
                ->orderBy('published_at', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        if ($sort !== 'created_at') {
            $query->orderBy('created_at', $direction);
        }

        $query->orderBy('id', $direction);

        $announcements = $query->paginate(10)->withQueryString();

        $semaphoreAccount = $this->getSemaphoreAccountSummary();
        $smsAudienceCounts = $this->buildSmsAudienceCounts();

        $residentsForNotification = $this->residentBaseQuery()
        ->select([
            'id',
            'firstname',
            'middlename',
            'lastname',
            'suffix',
            'email',
            'contact',
            'birthdate',
            'is_pwd',
            'is_student',
            'gender',
        ])
        ->orderBy('lastname')
        ->orderBy('firstname')
        ->get();

        return view('admin.admin_announcement', compact(
            'announcements',
            'total_count',
            'published_count',
            'draft_count',
            'featured_count',
            'residentNotificationStats',
            'residentsForNotification',
            'semaphoreAccount',
            'smsAudienceCounts'
        ));
    }

    public function store(Request $request)
    {
        
        session()->flash('form_type', 'add');

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:important,events,health,services,other',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);

            if ($request->status == 'published' && !$request->published_at) {
                $data['published_at'] = now();
            }

            unset($data['is_featured']);

            $announcement = Announcement::create($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') 
                ->with('success', 'Announcement created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create announcement. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        
        session()->flash('form_type', 'edit_' . $id);

        $announcement = Announcement::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:important,events,health,services,other',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            
            if ($request->hasFile('image')) {
                
                if ($announcement->image && file_exists(public_path($announcement->image))) {
                    unlink(public_path($announcement->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            if ($announcement->title != $request->title) {
                $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);
            }

            if ($request->status == 'published' && !$request->published_at && $announcement->status != 'published') {
                $data['published_at'] = now();
            }

            unset($data['is_featured']);

            $announcement->update($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') 
                ->with('success', 'Announcement updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update announcement. Please try again. ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $announcement = Announcement::findOrFail($id);

        $title = $announcement->title;
        $announcement->delete();

        return back()->with('success', 'Announcement "' . $title . '" archived successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        Announcement::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected announcement(s) archived successfully.');
    }

    public function restore(int $id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        $title = $announcement->title;
        $announcement->restore();

        return back()->with('success', 'Announcement "' . $title . '" restored successfully.');
    }

    public function toggleFeature(int$id)
    {
        $announcement = Announcement::findOrFail($id);

        Announcement::where('id', $announcement->id)
            ->update(['is_featured' => DB::raw('NOT is_featured')]);

        $announcement->refresh();

        $status = $announcement->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', 'Announcement ' . $status . ' successfully.');
    }

    public function trackView(string $slug)
    {
        $announcement = Announcement::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $announcement->increment('views');

        return response()->json([
            'success' => true,
            'views' => $announcement->views,
        ]);
    }

    public function show(string $slug)
    {
        $announcement = Announcement::where('slug', $slug)->firstOrFail();

        $announcement->increment('views');

        return view('announcements.show', compact('announcement'));
    }

    public function bulkNotifyResidents(Request $request)
    {
        if (! auth('admin')->check()) {
            abort(403);
        }

        session()->flash('form_type', 'bulk_notify');

        $validated = $request->validate([
            'channel' => ['required', 'in:sms,email,both'],
            'audience' => [
                'required',
                'in:all,selected,verified,unverified,senior,pwd,student,male,female,email_ready,sms_ready,with_valid_id'
            ],
            'resident_ids' => ['nullable', 'array', 'required_if:audience,selected'],
            'resident_ids.*' => ['integer', 'distinct', 'exists:residents,id'],
            'subject' => ['nullable', 'required_if:channel,email,both', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'resident_ids.required_if' => 'Please select at least one resident.',
            'subject.required_if' => 'Email subject is required when sending email.',
        ]);

        $channel = $validated['channel'];
        $audience = $validated['audience'];
        $message = trim($validated['message']);
        $subject = trim($validated['subject'] ?? 'Barangay Hulong Duhat Notification');

        $query = $this->residentBaseQuery();

        if ($audience === 'selected') {
            $query->whereIn('id', $validated['resident_ids'] ?? []);
        } else {
            $audienceError = $this->applyResidentAudienceFilter($query, $audience);

            if ($audienceError !== null) {
                return back()
                    ->withInput()
                    ->with('form_type', 'bulk_notify')
                    ->with('error', $audienceError);
            }
        }

        if ($channel === 'email') {
            $query->whereNotNull('email')->where('email', '<>', '');
        }

        if ($channel === 'sms') {
            $query->whereNotNull('contact')->where('contact', '<>', '');
        }

        if ($channel === 'both') {
            $query->where(function ($q) {
                $q->where(function ($emailQuery) {
                    $emailQuery->whereNotNull('email')->where('email', '<>', '');
                })->orWhere(function ($smsQuery) {
                    $smsQuery->whereNotNull('contact')->where('contact', '<>', '');
                });
            });
        }

        $residents = $query->get();

        if ($residents->isEmpty()) {
            return back()
                ->withInput()
                ->with('form_type', 'bulk_notify')
                ->with('error', 'No residents matched the selected audience and channel.');
        }

        $smsPayloads = collect();

        if (in_array($channel, ['sms', 'both'], true)) {
            foreach ($residents as $resident) {
                if (blank($resident->contact)) {
                    continue;
                }

                $mobileNumber = $this->normalizePhilippineMobileNumber((string) $resident->contact);

                if ($mobileNumber === null) {
                    continue;
                }

                $smsPayloads->push([
                    'resident' => $resident,
                    'resident_id' => $resident->id,
                    'number' => $mobileNumber,
                    'message' => $this->personalizeResidentMessage($message, $resident),
                ]);
            }

            $smsPayloads = $smsPayloads
                ->unique('number')
                ->values();

            if ($smsPayloads->count() > 1000) {
                return back()
                    ->withInput()
                    ->with('form_type', 'bulk_notify')
                    ->with('error', 'SMS bulk notification is limited to 1,000 valid mobile numbers only. Selected valid mobile numbers: ' . $smsPayloads->count() . '.');
            }

            $creditsNeeded = $this->calculateSmsCreditsNeeded($smsPayloads);
            $semaphoreAccount = $this->getSemaphoreAccountSummary(false);
            $availableCredits = (float) ($semaphoreAccount['credit_balance'] ?? 0);

            if (! ($semaphoreAccount['success'] ?? false)) {
                return back()
                    ->withInput()
                    ->with('form_type', 'bulk_notify')
                    ->with('error', $semaphoreAccount['message'] ?? 'Unable to verify Semaphore credits.');
            }

            if ($creditsNeeded > $availableCredits) {
                return back()
                    ->withInput()
                    ->with('form_type', 'bulk_notify')
                    ->with('error', "Not enough SMS credits. Needed: {$creditsNeeded}, Available: {$availableCredits}. Message was not sent.");
            }
        }

        $emailSent = 0;
        $emailFailed = 0;
        $smsSent = 0;
        $smsFailed = 0;
        $smsSkipped = 0;

        foreach ($residents as $resident) {
            $personalizedMessage = $this->personalizeResidentMessage($message, $resident);

            if (in_array($channel, ['email', 'both'], true) && filled($resident->email)) {
                try {
                    Mail::raw($personalizedMessage, function ($mail) use ($resident, $subject) {
                        $mail->to($resident->email)->subject($subject);
                    });

                    $emailSent++;

                    $this->logResidentNotification(
                        channel: 'email',
                        recipient: $resident->email,
                        status: 'sent',
                        message: $personalizedMessage,
                        residentId: $resident->id
                    );
                } catch (Throwable $e) {
                    $emailFailed++;

                    $this->logResidentNotification(
                        channel: 'email',
                        recipient: $resident->email,
                        status: 'failed',
                        message: $personalizedMessage,
                        residentId: $resident->id,
                        error: $e->getMessage()
                    );
                }
            }

            if (in_array($channel, ['sms', 'both'], true)) {
                foreach ($smsPayloads as $payload) {
                    try {
                        $this->sendSemaphoreSms($payload['number'], $payload['message']);

                        $smsSent++;

                        $this->logResidentNotification(
                            channel: 'sms',
                            recipient: $payload['number'],
                            status: 'sent',
                            message: $payload['message'],
                            residentId: $payload['resident_id']
                        );
                    } catch (Throwable $e) {
                        $smsFailed++;

                        $this->logResidentNotification(
                            channel: 'sms',
                            recipient: $payload['number'],
                            status: 'failed',
                            message: $payload['message'],
                            residentId: $payload['resident_id'],
                            error: $e->getMessage()
                        );
                    }
                }
            }
        }

        return back()
            ->with('success', "Bulk notification completed. Email sent: {$emailSent}, email failed: {$emailFailed}, SMS sent: {$smsSent}, SMS failed: {$smsFailed}, SMS skipped: {$smsSkipped}.");
    }

    private function residentBaseQuery(): Builder
    {
        $query = Residents::query();

        if (Schema::hasColumn('residents', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        return $query;
    }

    private function buildResidentNotificationStats(): array
    {
        $base = $this->residentBaseQuery();

        return [
            'all' => (clone $base)->count(),

            'email' => (clone $base)
                ->whereNotNull('email')
                ->where('email', '<>', '')
                ->count(),

            'sms' => (clone $base)
                ->whereNotNull('contact')
                ->where('contact', '<>', '')
                ->count(),

            'verified' => $this->countResidentAudience('verified'),
            'senior' => $this->countResidentAudience('senior'),
            'pwd' => $this->countResidentAudience('pwd'),
            'student' => $this->countResidentAudience('student'),
        ];
    }

    private function countResidentAudience(string $audience): int
    {
        $query = $this->residentBaseQuery();

        $error = $this->applyResidentAudienceFilter($query, $audience);

        if ($error !== null) {
            return 0;
        }

        return $query->count();
    }

    private function applyResidentAudienceFilter(Builder $query, string $audience): ?string
    {
        return match ($audience) {
            'all' => null,

            'verified' => $this->applyVerifiedResidentFilter($query, true),

            'unverified' => $this->applyVerifiedResidentFilter($query, false),

            'senior' => $this->applySeniorResidentFilter($query),

            'pwd' => $this->applyBooleanResidentColumnFilter(
                $query,
                ['is_pwd', 'pwd', 'is_person_with_disability', 'person_with_disability'],
                'PWD filtering needs an is_pwd column in the residents table.'
            ),

            'student' => $this->applyBooleanResidentColumnFilter(
                $query,
                ['is_student', 'student', 'student_status'],
                'Student filtering needs an is_student column in the residents table.'
            ),

            'male' => $this->applyGenderResidentFilter($query, 'male'),

            'female' => $this->applyGenderResidentFilter($query, 'female'),

            'email_ready' => tap(null, function () use ($query) {
                $query->whereNotNull('email')->where('email', '<>', '');
            }),

            'sms_ready' => tap(null, function () use ($query) {
                $query->whereNotNull('contact')->where('contact', '<>', '');
            }),

            'with_valid_id' => $this->applyValidIdResidentFilter($query),

            default => 'Invalid audience selected.',
        };
    }

    private function applyVerifiedResidentFilter(Builder $query, bool $verified = true): ?string
    {
        if (Schema::hasColumn('residents', 'admin_verified')) {
            $query->whereRaw('"admin_verified" IS ' . ($verified ? 'TRUE' : 'FALSE'));
            return null;
        }

        if (Schema::hasColumn('residents', 'residency_verified')) {
            $query->whereRaw('"residency_verified" IS ' . ($verified ? 'TRUE' : 'FALSE'));
            return null;
        }

        if (Schema::hasColumn('residents', 'valid_id_verified')) {
            $query->whereRaw('"valid_id_verified" IS ' . ($verified ? 'TRUE' : 'FALSE'));
            return null;
        }

        return 'Verified filtering needs admin_verified, residency_verified, or valid_id_verified column.';
    }

    private function applySeniorResidentFilter(Builder $query): ?string
    {
        if (! Schema::hasColumn('residents', 'birthdate')) {
            return 'Senior filtering needs the birthdate column in the residents table.';
        }

        $query->whereNotNull('birthdate')
            ->whereDate('birthdate', '<=', now()->subYears(60)->toDateString());

        return null;
    }

    private function applyBooleanResidentColumnFilter(Builder $query, array $columns, string $errorMessage): ?string
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn('residents', $column)) {
                continue;
            }

            if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
                return 'Invalid resident filter column.';
            }

            $query->whereRaw(
                'LOWER(CAST("' . $column . '" AS TEXT)) IN (?, ?, ?, ?, ?)',
                ['yes', 'true', '1', 'pwd', 'student']
            );

            return null;
        }

        return $errorMessage;
    }

    private function applyGenderResidentFilter(Builder $query, string $gender): ?string
    {
        $column = null;

        if (Schema::hasColumn('residents', 'gender')) {
            $column = 'gender';
        }

        if ($column === null && Schema::hasColumn('residents', 'sex')) {
            $column = 'sex';
        }

        if ($column === null) {
            return 'Gender filtering needs gender or sex column in the residents table.';
        }

        $query->whereRaw("LOWER(CAST({$column} AS TEXT)) = ?", [strtolower($gender)]);

        return null;
    }

    private function applyValidIdResidentFilter(Builder $query): ?string
    {
        $columns = collect(['valid_id', 'valid_id_front', 'valid_id_back'])
            ->filter(fn ($column) => Schema::hasColumn('residents', $column))
            ->values();

        if ($columns->isEmpty()) {
            return 'Valid ID filtering needs valid_id, valid_id_front, or valid_id_back column.';
        }

        $query->where(function ($q) use ($columns) {
            foreach ($columns as $column) {
                $q->orWhere(function ($idQuery) use ($column) {
                    $idQuery->whereNotNull($column)->where($column, '<>', '');
                });
            }
        });

        return null;
    }

    private function personalizeResidentMessage(string $message, Residents $resident): string
    {
        $fullName = trim(collect([
            $resident->firstname ?? '',
            $resident->middlename ?? '',
            $resident->lastname ?? '',
            $resident->suffix ?? '',
        ])->filter()->implode(' '));

        return str_replace(
            [
                '{name}',
                '{firstname}',
                '{lastname}',
                '{barangay}',
            ],
            [
                $fullName ?: 'Resident',
                $resident->firstname ?? 'Resident',
                $resident->lastname ?? '',
                'Hulong Duhat',
            ],
            $message
        );
    }

    private function normalizePhilippineMobileNumber(string $number): ?string
    {
        $number = preg_replace('/[\s\-\(\)]/', '', trim($number));

        if (str_starts_with($number, '+639')) {
            $number = '639' . substr($number, 4);
        } elseif (str_starts_with($number, '09')) {
            $number = '639' . substr($number, 1);
        } elseif (str_starts_with($number, '9')) {
            $number = '63' . $number;
        }

        return preg_match('/^639\d{9}$/', $number) ? $number : null;
    }

    private function logResidentNotification(
        string $channel,
        string $recipient,
        string $status,
        string $message,
        int $residentId,
        ?string $error = null
    ): void {
        NotificationLog::create([
            'channel' => $channel,
            'recipient' => $recipient,
            'status' => $status,
            'message' => $message,
            'error_message' => $error ? Str::limit($error, 1000) : null,
            'reference_type' => 'resident_bulk_notification',
            'reference_id' => $residentId,
            'sent_by' => auth('admin')->id(),
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }

    private function wherePostgresBoolean(Builder $query, string $column, bool $value = true): void
    {
        if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \InvalidArgumentException('Invalid boolean column name.');
        }

        $query->whereRaw('"' . $column . '" IS ' . ($value ? 'TRUE' : 'FALSE'));
    }

    private function getSemaphoreAccountSummary(bool $useCache = true): array
    {
        $apiKey = trim((string) env('SMS_API_KEY'));

        if ($apiKey === '') {
            return [
                'success' => false,
                'message' => 'Semaphore API key is missing.',
                'credit_balance' => 0,
                'account_name' => null,
                'status' => null,
            ];
        }

        $resolver = function () use ($apiKey) {
            try {
                $response = Http::timeout(15)
                    ->acceptJson()
                    ->get('https://api.semaphore.co/api/v4/account', [
                        'apikey' => $apiKey,
                    ]);

                if (! $response->successful()) {
                    return [
                        'success' => false,
                        'message' => 'Unable to retrieve Semaphore balance. Status: ' . $response->status(),
                        'credit_balance' => 0,
                        'account_name' => null,
                        'status' => null,
                    ];
                }

                $data = $response->json();

                return [
                    'success' => true,
                    'message' => 'Semaphore balance loaded.',
                    'credit_balance' => (float) ($data['credit_balance'] ?? 0),
                    'account_name' => $data['account_name'] ?? null,
                    'status' => $data['status'] ?? null,
                ];
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => 'Semaphore balance check failed: ' . $e->getMessage(),
                    'credit_balance' => 0,
                    'account_name' => null,
                    'status' => null,
                ];
            }
        };

        if (! $useCache) {
            return $resolver();
        }

        return Cache::remember(
            'semaphore_account_summary_' . md5($apiKey),
            now()->addSeconds(60),
            $resolver
        );
    }

    private function calculateSmsSegments(string $message): int
    {
        $length = mb_strlen($message, 'UTF-8');

        return max(1, (int) ceil($length / 160));
    }

    private function calculateSmsCreditsForMessage(string $message): int
    {
        return $this->calculateSmsSegments($message) * 2;
    }

    private function calculateSmsCreditsNeeded(Collection $smsPayloads): int
    {
        return (int) $smsPayloads->sum(function (array $payload) {
            return $this->calculateSmsCreditsForMessage($payload['message']);
        });
    }

    private function buildSmsAudienceCounts(): array
    {
        $audiences = [
            'all',
            'verified',
            'unverified',
            'senior',
            'pwd',
            'student',
            'male',
            'female',
            'with_valid_id',
            'sms_ready',
        ];

        $counts = [];

        foreach ($audiences as $audience) {
            $query = $this->residentBaseQuery()
                ->whereNotNull('contact')
                ->where('contact', '<>', '');

            if (! in_array($audience, ['all', 'sms_ready'], true)) {
                $error = $this->applyResidentAudienceFilter($query, $audience);

                if ($error !== null) {
                    $counts[$audience] = 0;
                    continue;
                }
            }

            $counts[$audience] = $query->count();
        }

        $counts['selected'] = 0;
        $counts['email_ready'] = 0;

        return $counts;
    }

    private function sendSemaphoreSms(string $number, string $message): void
    {
        $apiKey = trim((string) env('SMS_API_KEY'));
        $senderName = trim((string) env('SMS_SENDER_NAME', 'HULONGDUHAT'));

        if ($apiKey === '') {
            throw new \RuntimeException('Semaphore API key is missing in .env.');
        }

        $response = Http::asForm()
            ->timeout(30)
            ->post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => $apiKey,
                'number' => $number,
                'message' => $message,
                'sendername' => $senderName,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'Semaphore SMS failed. Status: ' . $response->status() . '. Response: ' . $response->body()
            );
        }
    }
}