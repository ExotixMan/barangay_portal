<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class DatabaseLookupService
{
    private const LIMIT = 3;

    
    private array $trackingTables = [
        [
            'table' => 'barangay_clearances',
            'label' => 'Barangay Clearance',
            'category' => 'clearance',
        ],
        [
            'table' => 'indigency_applications',
            'label' => 'Indigency Certificate',
            'category' => 'indigency',
        ],
        [
            'table' => 'residency_applications',
            'label' => 'Residency Certificate',
            'category' => 'residency',
        ],
        [
            'table' => 'blotter_reports',
            'label' => 'Blotter / Incident Report',
            'category' => 'blotter',
        ],
    ];

    public function handle(string $message): ?array
    {
        $lower = $this->normalize($message);

        if ($this->isTrackingQuestion($lower)) {
            return $this->trackByReferenceNumber($message);
        }

        if ($this->containsAny($lower, ['announcement', 'announcements', 'patalastas', 'abiso', 'notice'])) {
            return $this->latestAnnouncements();
        }

        if ($this->containsAny($lower, ['event', 'events', 'program', 'activity', 'activities', 'aktibidad'])) {
            return $this->latestEvents();
        }

        if ($this->containsAny($lower, ['project', 'projects', 'proyekto', 'project update', 'progress'])) {
            return $this->latestProjects();
        }

        if ($this->containsAny($lower, [
            'official',
            'officials',
            'barangay official',
            'barangay officials',
            'kapitan',
            'captain',
            'chairman',
            'kagawad',
            'councilor',
            'secretary',
            'treasurer',
            'staff',
            'admin staff',
        ])) {
            return $this->barangayOfficials();
        }

        return null;
    }

    private function barangayOfficials(): array
    {
        try {
            $items = DB::table('admin_users as u')
                ->leftJoin('admin_roles as r', 'u.role_id', '=', 'r.id')
                ->where('u.status', 'active')
                ->whereNull('u.deleted_at')

                
                ->whereRaw("
                    LOWER(COALESCE(r.name, '')) NOT IN (?, ?, ?)
                    AND LOWER(COALESCE(r.display_name, '')) NOT IN (?, ?, ?)
                ", [
                    'testing',
                    'super admin',
                    'viewer',
                    'testing',
                    'super admin',
                    'viewer',
                ])

                ->select([
                    'u.first_name',
                    'u.middle_initial',
                    'u.last_name',
                    'r.name as role_name',
                    'r.display_name as role_display_name',
                ])

                ->orderByRaw("
                    CASE 
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%captain%' THEN 1
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%chairman%' THEN 2
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%secretary%' THEN 3
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%treasurer%' THEN 4
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%kagawad%' THEN 5
                        WHEN LOWER(COALESCE(r.display_name, r.name)) LIKE '%councilor%' THEN 6
                        ELSE 99
                    END
                ")
                ->orderBy('u.last_name')
                ->limit(12)
                ->get();
        } catch (\Throwable $e) {
            Log::error('[DatabaseLookupService] Officials lookup failed', [
                'error' => $e->getMessage(),
            ]);

            return $this->safeError(
                'I could not load the barangay officials right now. Please try again later.',
                'officials'
            );
        }

        if ($items->isEmpty()) {
            return $this->emptyDatabaseResponse(
                'There are no active barangay officials listed right now.',
                'officials'
            );
        }

        $answer = 'Barangay officials listed in the portal:<br><br>';

        foreach ($items as $item) {
            $middle = ! empty($item->middle_initial)
                ? ' ' . strtoupper((string) $item->middle_initial) . ''
                : '';

            $name = trim((string) $item->first_name . $middle . ' ' . (string) $item->last_name);

            $role = $item->role_display_name
                ?: $item->role_name
                ?: 'Barangay Official';

            $answer .= '• <strong>' . e($name) . '</strong> — ' . e(Str::headline((string) $role)) . '<br>';
        }

        return $this->databaseResult($answer, 'officials', [
            'Barangay services',
            'Latest announcements',
            'Upcoming events',
            'Contact support',
        ]);
    }

    private function isTrackingQuestion(string $lower): bool
    {
        if ($this->extractReferenceNumber($lower) !== null) {
            return true;
        }

        return $this->containsAny($lower, [
            'track', 'tracking', 'status', 'reference', 'reference id', 'ref',
            'pending', 'approved', 'rejected', 'released', 'processing', 'completed',
            'nasaan', 'ano na', 'update ng request', 'request update',
        ]);
    }

    private function trackByReferenceNumber(string $message): ?array
    {
        $reference = $this->extractReferenceNumber($message);

        if ($reference === null) {
            return [
                'answer' => 'Please provide your Reference Number so I can check the status of your request.',
                'source' => 'database',
                'confidence' => 90,
                'matched' => true,
                'knowledge_id' => null,
                'category' => 'tracking',
                'suggestions' => [
                    'Where can I find my Reference ID?',
                    'Track my request',
                    'Contact support',
                ],
            ];
        }

        try {
            foreach ($this->trackingTables as $source) {
                $record = DB::table($source['table'])
                    ->whereRaw('LOWER(reference_number) = ?', [Str::lower($reference)])
                    ->whereNull('deleted_at')
                    ->first();

                if ($record !== null) {
                    return $this->trackingResponse($source, $record);
                }
            }
        } catch (\Throwable $e) {
            Log::error('[DatabaseLookupService] Tracking lookup failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return $this->safeError('I could not check the request status right now. Please try again later or contact Barangay Hulong Duhat staff.', 'tracking');
        }

        return [
            'answer' => 'I could not find a request with Reference Number <strong>' . e($reference) . '</strong>. Please check the number and try again.',
            'source' => 'database',
            'confidence' => 90,
            'matched' => true,
            'knowledge_id' => null,
            'category' => 'tracking',
            'suggestions' => [
                'Track another request',
                'Contact support',
                'Office hours',
            ],
        ];
    }

    private function trackingResponse(array $source, object $record): array
    {
        $reference = e((string) ($record->reference_number ?? ''));
        $status = $this->formatStatus((string) ($record->status ?? 'processing'));

        $answer = 'Your <strong>' . e($source['label']) . '</strong> request with Reference Number '
            . '<strong>' . $reference . '</strong> is currently <strong>' . e($status) . '</strong>.';

        $updatedAt = $record->status_updated_at ?? $record->updated_at ?? $record->created_at ?? null;
        if (! empty($updatedAt)) {
            $answer .= '<br><br>Last update: <strong>' . e($this->formatDateTime((string) $updatedAt)) . '</strong>.';
        }

        $latestRemark = $this->latestStatusRemark((string) ($record->reference_number ?? ''));
        if ($latestRemark !== null && ! empty($latestRemark->remarks)) {
            $answer .= '<br><br>Latest remark: <em>' . e((string) $latestRemark->remarks) . '</em>';
        }

        return [
            'answer' => $answer,
            'source' => 'database',
            'confidence' => 100,
            'matched' => true,
            'knowledge_id' => null,
            'category' => $source['category'] ?? 'tracking',
            'suggestions' => [
                'What does this status mean?',
                'Contact support',
                'Office hours',
            ],
        ];
    }

    private function latestStatusRemark(string $reference): ?object
    {
        if (trim($reference) === '') {
            return null;
        }

        try {
            return DB::table('status_remark_histories')
                ->whereRaw('LOWER(reference_number) = ?', [Str::lower($reference)])
                ->orderByDesc('created_at')
                ->first();
        } catch (\Throwable $e) {
            Log::warning('[DatabaseLookupService] Remark lookup failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function latestAnnouncements(): array
    {
        try {
            $items = DB::table('announcements')
                ->where('status', 'published')
                ->whereNull('deleted_at')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->limit(self::LIMIT)
                ->get();
        } catch (\Throwable $e) {
            Log::error('[DatabaseLookupService] Announcement lookup failed', ['error' => $e->getMessage()]);
            return $this->safeError('I could not load announcements right now. Please try again later.', 'announcements');
        }

        if ($items->isEmpty()) {
            return $this->emptyDatabaseResponse('There are no published announcements right now.', 'announcements');
        }

        $answer = 'Latest announcements:<br><br>';

        foreach ($items as $item) {
            $date = ! empty($item->published_at)
                ? ' — ' . $this->formatDate((string) $item->published_at)
                : '';

            $answer .= '• <strong>' . e((string) $item->title) . '</strong>' . e($date) . '<br>';
        }

        return $this->databaseResult($answer, 'announcements', ['Events', 'Projects', 'Barangay services']);
    }

    private function latestEvents(): array
    {
        try {
            $items = DB::table('events')
                ->whereNull('deleted_at')
                ->whereDate('event_date', '>=', now()->toDateString())
                ->orderBy('event_date')
                ->orderBy('start_time')
                ->limit(self::LIMIT)
                ->get();
        } catch (\Throwable $e) {
            Log::error('[DatabaseLookupService] Event lookup failed', ['error' => $e->getMessage()]);
            return $this->safeError('I could not load events right now. Please try again later.', 'events');
        }

        if ($items->isEmpty()) {
            return $this->emptyDatabaseResponse('There are no upcoming events right now.', 'events');
        }

        $answer = 'Upcoming events:<br><br>';

        foreach ($items as $item) {
            $date = $this->formatDate((string) $item->event_date);
            $time = ! empty($item->start_time) ? ' at ' . $this->formatTime((string) $item->start_time) : '';
            $location = ! empty($item->location) ? ' — ' . e((string) $item->location) : '';

            $answer .= '• <strong>' . e((string) $item->title) . '</strong> — ' . e($date . $time) . $location . '<br>';
        }

        return $this->databaseResult($answer, 'events', ['Announcements', 'Projects', 'Barangay services']);
    }

    private function latestProjects(): array
    {
        try {
            $items = DB::table('projects')
                ->whereNull('deleted_at')
                ->orderByDesc('created_at')
                ->limit(self::LIMIT)
                ->get();
        } catch (\Throwable $e) {
            Log::error('[DatabaseLookupService] Project lookup failed', ['error' => $e->getMessage()]);
            return $this->safeError('I could not load projects right now. Please try again later.', 'projects');
        }

        if ($items->isEmpty()) {
            return $this->emptyDatabaseResponse('There are no projects posted right now.', 'projects');
        }

        $answer = 'Latest barangay projects:<br><br>';

        foreach ($items as $item) {
            $status = $this->formatStatus((string) ($item->status ?? 'posted'));
            $progress = isset($item->progress) ? ' — ' . (int) $item->progress . '% progress' : '';
            $answer .= '• <strong>' . e((string) $item->title) . '</strong> — ' . e($status . $progress) . '<br>';
        }

        return $this->databaseResult($answer, 'projects', ['Announcements', 'Events', 'Barangay services']);
    }

    private function extractReferenceNumber(string $message): ?string
    {
        $upper = strtoupper(trim($message));

        
        if (preg_match('/\b(?:RES|IND|BL|BC)[-_][0-9]{4,20}[-_][A-Z0-9]{3,50}\b/', $upper, $matches)) {
            return str_replace('_', '-', $matches[0]);
        }

        
        if (preg_match('/\b[A-Z]{2,10}[-_][0-9]{4,20}[-_][A-Z0-9]{3,50}\b/', $upper, $matches)) {
            return str_replace('_', '-', $matches[0]);
        }

        return null;
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, Str::lower($needle))) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $message): string
    {
        $message = Str::lower(trim($message));
        return preg_replace('/\s+/u', ' ', $message) ?? $message;
    }

    private function formatStatus(string $status): string
    {
        $status = Str::lower(trim($status));

        return match ($status) {
            'processing' => 'Processing',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'released' => 'Released',
            'completed' => 'Completed',
            'cancelled', 'canceled' => 'Cancelled',
            default => Str::headline($status ?: 'Processing'),
        };
    }

    private function formatDate(string $date): string
    {
        try {
            return date('F d, Y', strtotime($date));
        } catch (\Throwable) {
            return $date;
        }
    }

    private function formatTime(string $time): string
    {
        try {
            return date('h:i A', strtotime($time));
        } catch (\Throwable) {
            return $time;
        }
    }

    private function formatDateTime(string $dateTime): string
    {
        try {
            return date('F d, Y h:i A', strtotime($dateTime));
        } catch (\Throwable) {
            return $dateTime;
        }
    }

    private function databaseResult(string $answer, string $category, array $suggestions): array
    {
        return [
            'answer' => $answer,
            'source' => 'database',
            'confidence' => 100,
            'matched' => true,
            'knowledge_id' => null,
            'category' => $category,
            'suggestions' => $suggestions,
        ];
    }

    private function emptyDatabaseResponse(string $message, string $category): array
    {
        return $this->databaseResult($message, $category, ['Announcements', 'Events', 'Projects']);
    }

    private function safeError(string $message, string $category): array
    {
        return [
            'answer' => $message,
            'source' => 'database',
            'confidence' => 0,
            'matched' => false,
            'knowledge_id' => null,
            'category' => $category,
            'suggestions' => ['Try again', 'Contact support', 'Office hours'],
        ];
    }
}
