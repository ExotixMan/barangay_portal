<?php

namespace App\Http\Controllers;

use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotMessage;
use App\Models\ChatbotUnmatched;
use App\Services\AdminSettingsService;
use App\Services\Chatbot\ClaudeAIEngine;
use App\Services\Chatbot\HybridAgent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(protected HybridAgent $agent) {}

    private const KNOWLEDGE_CATEGORIES = [
        'general',
        'clearance',
        'residency',
        'indigency',
        'first_time_job_seeker',
        'blotter',
        'tracking',
        'announcements',
        'events',
        'projects',
        'portal',
        'support',
        'valid_id',
        'requirements',
    ];

    public function index()
    {
        return view('chatbot.widget');
    }

    public function startConversation(): JsonResponse
    {
        $sessionId = Str::uuid()->toString();

        try {
            ChatbotConversation::firstOrCreate(
                ['session_id' => $sessionId],
                ['started_at' => now()]
            );
        } catch (\Throwable $e) {
            Log::error('[Chatbot] startConversation failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
        }

        return response()->json([
            'success'    => true,
            'session_id' => $sessionId,
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'    => ['required', 'string', 'max:500'],
            'session_id' => ['nullable', 'string', 'max:120'],
        ]);

        $message = trim((string) $data['message']);
        $sessionId = $this->normalizeSessionId((string) ($data['session_id'] ?? ''));

        try {
            ChatbotConversation::firstOrCreate(
                ['session_id' => $sessionId],
                ['started_at' => now()]
            );

            $result = $this->agent->process($message, $sessionId);

            if (($result['source'] ?? null) === 'ai') {
                app(AdminSettingsService::class)->incrementUsage('ai');
            }

            return response()->json([
                'success'    => true,
                'session_id' => $sessionId,
                'data'       => $result,
            ]);
        } catch (\Throwable $e) {
            Log::error('[Chatbot] sendMessage failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine(),
            ], 500);
        }
    }

    public function aiStatus(): JsonResponse
    {
        $ai       = app(ClaudeAIEngine::class);
        $settings = app(AdminSettingsService::class);
        $provider = strtolower((string) $settings->get('ai.provider', env('AI_PROVIDER', 'gemini')));
        $keyValue = (string) $settings->get('ai.keys.' . $provider, env(strtoupper($provider) . '_API_KEY', ''));

        return response()->json([
            'available'   => $ai->isAvailable(),
            'provider'    => $provider,
            'key_set'     => $keyValue !== '',
            'key_preview' => $keyValue !== '' ? substr($keyValue, 0, 8) . '...' : null,
        ]);
    }

    public function testAI(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $ai = app(ClaudeAIEngine::class);

        if (! $ai->isAvailable()) {
            return response()->json([
                'success' => false,
                'error'   => 'AI is not available. Check AI_PROVIDER and the selected provider API key.',
                'hint'    => 'Run: php artisan chatbot:test --ai',
            ], 503);
        }

        $start  = microtime(true);
        $result = $ai->handle(trim((string) $data['message']), []);
        $ms     = (int) round((microtime(true) - $start) * 1000);

        if (! $result) {
            return response()->json([
                'success' => false,
                'error'   => 'AI returned no answer. Check storage/logs/laravel.log for details.',
            ], 500);
        }

        return response()->json([
            'success'     => true,
            'answer'      => $result['answer'] ?? '',
            'provider'    => $result['ai_provider'] ?? env('AI_PROVIDER'),
            'response_ms' => $ms,
        ]);
    }

    public function adminIndex()
    {
        $knowledge = ChatbotKnowledge::orderByDesc('usage_count')->get();
        $unmatched = ChatbotUnmatched::where('resolved', 'false')
            ->latest('created_at')
            ->limit(100)
            ->get();
        $stats = $this->statsData();

        return view('admin.admin_chatbot', compact('knowledge', 'stats', 'unmatched'));
    }

    public function storeKnowledge(Request $request): JsonResponse
    {
        $data = $request->validate($this->knowledgeRules());
        $data['keywords'] = $this->normalizeKeywords($data['keywords'] ?? '');
        $data['category'] = $data['category'] ?? 'general';

        return response()->json([
            'success' => true,
            'data'    => ChatbotKnowledge::create($data),
        ]);
    }

    public function updateKnowledge(Request $request, int $id): JsonResponse
    {
        $item = ChatbotKnowledge::findOrFail($id);
        $data = $request->validate($this->knowledgeRules());
        $data['keywords'] = $this->normalizeKeywords($data['keywords'] ?? '');
        $data['category'] = $data['category'] ?? 'general';

        $item->update($data);

        return response()->json([
            'success' => true,
            'data'    => $item->fresh(),
        ]);
    }

    public function deleteKnowledge(int $id): JsonResponse
    {
        ChatbotKnowledge::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function getStats(): JsonResponse
    {
        return response()->json($this->statsData());
    }

    public function resolveUnmatched(int $id): JsonResponse
    {
        ChatbotUnmatched::findOrFail($id)->update(['resolved' => true]);

        return response()->json(['success' => true]);
    }

    private function statsData(): array
    {
        return [
            'knowledge_count'    => ChatbotKnowledge::count(),
            'conversation_count' => ChatbotConversation::count(),
            'message_count'      => ChatbotMessage::count(),
            'unmatched_count'    => ChatbotUnmatched::where('resolved', 'false')->count(),
            'ai_enabled'         => app(ClaudeAIEngine::class)->isAvailable(),
        ];
    }

    /**
     * PostgreSQL UUID columns reject fake test IDs like ai-test-123.
     * This converts any missing/invalid value into a valid UUID.
     */
    private function normalizeSessionId(string $sessionId): string
    {
        $sessionId = trim($sessionId);
        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

        if ($sessionId !== '' && preg_match($uuidPattern, $sessionId)) {
            return $sessionId;
        }

        return Str::uuid()->toString();
    }

    private function knowledgeRules(): array
    {
        return [
            'question' => ['required', 'string', 'max:500'],
            'answer'   => ['required', 'string', 'max:5000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'in:' . implode(',', self::KNOWLEDGE_CATEGORIES)],
        ];
    }

    private function normalizeKeywords(string $keywords): string
    {
        return collect(explode(',', $keywords))
            ->map(fn ($keyword) => trim(strtolower($keyword)))
            ->filter()
            ->unique()
            ->implode(', ');
    }
}
