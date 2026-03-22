<?php

namespace App\Http\Controllers;

use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotMessage;
use App\Models\ChatbotUnmatched;
use App\Services\Chatbot\ClaudeAIEngine;
use App\Services\Chatbot\HybridAgent;
use App\Services\Chatbot\InputGuard;
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
        'blotter',
        'tracking',
        'announcements',
        'events',
        'projects',
        'portal',
        'support',
    ];

    public function index()
    {
        return view('chatbot.widget');
    }

    public function startConversation(): JsonResponse
    {
        $sessionId = Str::uuid()->toString();
        try {
            ChatbotConversation::create(['session_id' => $sessionId, 'started_at' => now()]);
        } catch (\Throwable $e) {
            Log::error('[Chatbot] startConversation: ' . $e->getMessage());
        }
        return response()->json(['session_id' => $sessionId]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message'    => 'required|string|max:500',
            'session_id' => 'required|string',
        ]);

        try {
            $result = $this->agent->process(trim($request->message), $request->session_id);
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Throwable $e) {
            Log::error('[Chatbot] sendMessage: ' . $e->getMessage(), [
                'file' => $e->getFile(), 'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine(),
            ], 500);
        }
    }

    /* ─── AI Status + Test endpoint ──────────────────────────── */

    public function aiStatus(): JsonResponse
    {
        $ai       = app(ClaudeAIEngine::class);
        $provider = strtolower(env('AI_PROVIDER', 'gemini'));

        $keyMap = [
            'gemini'     => 'GEMINI_API_KEY',
            'groq'       => 'GROQ_API_KEY',
            'cloudflare' => 'CLOUDFLARE_API_TOKEN',
        ];

        $envKey   = $keyMap[$provider] ?? 'GEMINI_API_KEY';
        $keyValue = env($envKey, '');

        return response()->json([
            'available'     => $ai->isAvailable(),
            'provider'      => $provider,
            'env_key'       => $envKey,
            'key_set'       => !empty($keyValue),
            'key_preview'   => !empty($keyValue) ? substr($keyValue, 0, 8) . '...' : null,
        ]);
    }

    public function testAI(Request $request): JsonResponse
    {
        $request->validate(['message' => 'required|string|max:500']);

        $ai = app(ClaudeAIEngine::class);

        if (!$ai->isAvailable()) {
            return response()->json([
                'success' => false,
                'error'   => 'AI is not available. Check your .env AI_PROVIDER and API key.',
                'hint'    => 'Run: php artisan chatbot:test --ai',
            ], 503);
        }

        $start  = microtime(true);
        $result = $ai->handle($request->message, []);
        $ms     = round((microtime(true) - $start) * 1000);

        if (!$result) {
            return response()->json([
                'success' => false,
                'error'   => 'AI returned null. Check storage/logs/laravel.log for details.',
            ], 500);
        }

        return response()->json([
            'success'     => true,
            'answer'      => $result['answer'],
            'provider'    => $result['ai_provider'] ?? env('AI_PROVIDER'),
            'response_ms' => $ms,
        ]);
    }

    /* ─── Admin routes ────────────────────────────────────────── */

    public function adminIndex()
    {
        $knowledge = ChatbotKnowledge::orderByDesc('usage_count')->get();
        $unmatched = ChatbotUnmatched::orderBy('resolved')->latest('created_at')->limit(100)->get();
        $stats     = $this->statsData();
        return view('admin.admin_chatbot', compact('knowledge', 'stats', 'unmatched'));
    }

    public function storeKnowledge(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string|max:5000',
            'keywords' => 'nullable|string|max:500',
            'category' => 'nullable|string|in:' . implode(',', self::KNOWLEDGE_CATEGORIES),
        ]);
        $data['keywords'] = $data['keywords'] ?? '';
        $data['category'] = $data['category'] ?? 'general';
        return response()->json(['success' => true, 'data' => ChatbotKnowledge::create($data)]);
    }

    public function updateKnowledge(Request $request, int $id): JsonResponse
    {
        $item = ChatbotKnowledge::findOrFail($id);
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string|max:5000',
            'keywords' => 'nullable|string|max:500',
            'category' => 'nullable|string|in:' . implode(',', self::KNOWLEDGE_CATEGORIES),
        ]);
        $item->update($data);
        return response()->json(['success' => true, 'data' => $item->fresh()]);
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
        ChatbotUnmatched::findOrFail($id)->update(['resolved' => 'true']);
        return response()->json(['success' => true]);
    }

    private function statsData(): array
    {
        return [
            'knowledge_count'    => ChatbotKnowledge::count(),
            'conversation_count' => ChatbotConversation::count(),
            'message_count'      => ChatbotMessage::count(),
            'unmatched_count'    => ChatbotUnmatched::where('resolved', 'false')->count(),
            'ai_enabled'         => !empty(env('ANTHROPIC_API_KEY'))
                                 || !empty(env('GEMINI_API_KEY'))
                                 || !empty(env('GROQ_API_KEY')),
        ];
    }
}