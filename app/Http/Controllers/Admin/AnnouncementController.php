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
use Throwable;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $baseResidentQuery = Residents::query();

        if (Schema::hasColumn('residents', 'deleted_at')) {
            $baseResidentQuery->whereNull('deleted_at');
        }

        $residentNotificationStats = [
            'all' => (clone $baseResidentQuery)->count(),

            'email' => (clone $baseResidentQuery)
                ->whereNotNull('email')
                ->where('email', '<>', '')
                ->count(),

            'sms' => (clone $baseResidentQuery)
                ->whereNotNull('contact')
                ->where('contact', '<>', '')
                ->count(),

            'verified' => Schema::hasColumn('residents', 'admin_verified')
                ? (clone $baseResidentQuery)->where('admin_verified', true)->count()
                : (
                    Schema::hasColumn('residents', 'residency_verified')
                        ? (clone $baseResidentQuery)->where('residency_verified', true)->count()
                        : 0
                ),
        ];

        $query = Announcement::query();

        // Search
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

        // Status filter
        $status = strtolower(trim((string) $request->get('status', '')));
        if ($status !== '' && in_array($status, ['published', 'draft', 'archived', 'deleted'], true)) {
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $status);
            }
        }

        // Category filter
        $category = strtolower(trim((string) $request->get('category', '')));
        if ($category !== '') {
            $query->whereRaw('LOWER(category) = ?', [$category]);
        }

        // Featured filter
        if ($request->has('featured') && $request->featured !== '') {
            $isFeaturedFilter = filter_var($request->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($isFeaturedFilter)) {
                $query->whereRaw($isFeaturedFilter ? 'is_featured IS TRUE' : 'is_featured IS FALSE');
            }
        }

        // Stats
        $total_count = Announcement::withTrashed()->count();
        $published_count = Announcement::where('status', 'published')->count();
        $draft_count = Announcement::where('status', 'draft')->count();
        $featured_count = Announcement::whereRaw('is_featured IS TRUE')->count();

        // Sorting
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
            // Keep nulls consistently last regardless of direction.
            $query->orderByRaw('published_at IS NULL ASC')
                ->orderBy('published_at', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // Secondary sort follows selected direction so status-only buckets
        if ($sort !== 'created_at') {
            $query->orderBy('created_at', $direction);
        }

        $query->orderBy('id', $direction);

        $announcements = $query->paginate(10)->withQueryString();

        return view('admin.admin_announcement', compact(
            'announcements', 
            'total_count', 
            'published_count', 
            'draft_count', 
            'featured_count'
        ));
    }

    public function store(Request $request)
    {
        // Store form type in session
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
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            // Generate slug
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);

            // Set published_at if status is published
            if ($request->status == 'published' && !$request->published_at) {
                $data['published_at'] = now();
            }

            // PostgreSQL-safe boolean literal to avoid boolean/integer binding mismatch.
            unset($data['is_featured']);

            $announcement = Announcement::create($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') // FIXED: added 'admin.' prefix
                ->with('success', 'Announcement created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create announcement. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        // Store form type in session
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
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($announcement->image && file_exists(public_path($announcement->image))) {
                    unlink(public_path($announcement->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            // Update slug only if title changed
            if ($announcement->title != $request->title) {
                $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);
            }

            // Set published_at if status changed to published
            if ($request->status == 'published' && !$request->published_at && $announcement->status != 'published') {
                $data['published_at'] = now();
            }

            // PostgreSQL-safe boolean literal to avoid boolean/integer binding mismatch.
            unset($data['is_featured']);

            $announcement->update($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') // FIXED: added 'admin.' prefix
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
        
        // Increment views
        $announcement->increment('views');

        return view('announcements.show', compact('announcement'));
    }

    public function bulkNotifyResidents(Request $request)
    {
        if (! auth('admin')->check()) {
            abort(403);
        }

        $validated = $request->validate([
            'channel' => ['required', 'in:sms,email,both'],
            'audience' => ['required', 'in:all,verified,email_ready,sms_ready'],
            'subject' => ['nullable', 'required_if:channel,email,both', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $channel = $validated['channel'];
        $message = trim($validated['message']);
        $subject = trim($validated['subject'] ?? 'Barangay Hulong Duhat Notification');

        $query = Residents::query();

        if (Schema::hasColumn('residents', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        match ($validated['audience']) {
            'verified' => $this->applyVerifiedResidentFilter($query),
            'email_ready' => $query->whereNotNull('email')->where('email', '<>', ''),
            'sms_ready' => $query->whereNotNull('contact')->where('contact', '<>', ''),
            default => null,
        };

        if ($channel === 'email') {
            $query->whereNotNull('email')->where('email', '<>', '');
        }

        if ($channel === 'sms') {
            $query->whereNotNull('contact')->where('contact', '<>', '');
        }

        if ($channel === 'both') {
            $query->where(function ($q) {
                $q->whereNotNull('email')->where('email', '<>', '')
                ->orWhere(function ($smsQuery) {
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

        $emailSent = 0;
        $emailFailed = 0;
        $smsSent = 0;
        $smsFailed = 0;

        if (in_array($channel, ['email', 'both'], true)) {
            foreach ($residents as $resident) {
                if (blank($resident->email)) {
                    continue;
                }

                $personalizedMessage = $this->personalizeResidentMessage($message, $resident);

                try {
                    Mail::raw($personalizedMessage, function ($mail) use ($resident, $subject) {
                        $mail->to($resident->email)
                            ->subject($subject);
                    });

                    $emailSent++;

                    NotificationLog::create([
                        'channel' => 'email',
                        'recipient' => $resident->email,
                        'status' => 'sent',
                        'message' => $personalizedMessage,
                        'reference_type' => 'resident_bulk_notification',
                        'reference_id' => $resident->id,
                        'sent_by' => auth('admin')->id(),
                        'sent_at' => now(),
                    ]);
                } catch (Throwable $e) {
                    $emailFailed++;

                    NotificationLog::create([
                        'channel' => 'email',
                        'recipient' => $resident->email,
                        'status' => 'failed',
                        'message' => $personalizedMessage,
                        'error_message' => $e->getMessage(),
                        'reference_type' => 'resident_bulk_notification',
                        'reference_id' => $resident->id,
                        'sent_by' => auth('admin')->id(),
                    ]);
                }
            }
        }

        if (in_array($channel, ['sms', 'both'], true)) {
            $smsResidents = $residents
                ->filter(fn ($resident) => filled($resident->contact))
                ->values();

            foreach ($smsResidents->chunk(1000) as $chunk) {
                $numbers = $chunk
                    ->pluck('contact')
                    ->map(fn ($number) => trim((string) $number))
                    ->filter(fn ($number) => preg_match('/^(09|\+639|639)\d{9}$/', $number))
                    ->unique()
                    ->values();

                if ($numbers->isEmpty()) {
                    continue;
                }

                try {
                    $this->sendSemaphoreBulkSms($numbers->all(), $message);

                    foreach ($chunk as $resident) {
                        if (! $numbers->contains(trim((string) $resident->contact))) {
                            continue;
                        }

                        $personalizedMessage = $this->personalizeResidentMessage($message, $resident);

                        $smsSent++;

                        NotificationLog::create([
                            'channel' => 'sms',
                            'recipient' => $resident->contact,
                            'status' => 'sent',
                            'message' => $personalizedMessage,
                            'reference_type' => 'resident_bulk_notification',
                            'reference_id' => $resident->id,
                            'sent_by' => auth('admin')->id(),
                            'sent_at' => now(),
                        ]);
                    }
                } catch (Throwable $e) {
                    foreach ($chunk as $resident) {
                        $personalizedMessage = $this->personalizeResidentMessage($message, $resident);

                        $smsFailed++;

                        NotificationLog::create([
                            'channel' => 'sms',
                            'recipient' => $resident->contact,
                            'status' => 'failed',
                            'message' => $personalizedMessage,
                            'error_message' => $e->getMessage(),
                            'reference_type' => 'resident_bulk_notification',
                            'reference_id' => $resident->id,
                            'sent_by' => auth('admin')->id(),
                        ]);
                    }
                }
            }
        }

        return back()->with(
            'success',
            "Bulk notification completed. Email sent: {$emailSent}, email failed: {$emailFailed}, SMS sent: {$smsSent}, SMS failed: {$smsFailed}."
        );
    }

    private function applyVerifiedResidentFilter(Builder$query): void
    {
        if (Schema::hasColumn('residents', 'admin_verified')) {
            $query->where('admin_verified', true);
            return;
        }

        if (Schema::hasColumn('residents', 'residency_verified')) {
            $query->where('residency_verified', true);
        }
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
            ['{name}', '{firstname}', '{barangay}'],
            [$fullName ?: 'Resident', $resident->firstname ?? 'Resident', 'Hulong Duhat'],
            $message
        );
    }

    private function sendSemaphoreBulkSms(array $numbers, string $message): void
    {
        $apiKey = trim((string) env('SEMAPHORE_API_KEY'));
        $senderName = trim((string) env('SEMAPHORE_SENDER_NAME', 'HULONGDUHAT'));

        if ($apiKey === '') {
            throw new \RuntimeException('Semaphore API key is missing in .env.');
        }

        $response = Http::asForm()
            ->timeout(30)
            ->post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => $apiKey,
                'number' => implode(',', $numbers),
                'message' => $message,
                'sendername' => $senderName,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Semaphore SMS failed. Status: ' . $response->status() . '. Response: ' . $response->body());
        }
    }
}