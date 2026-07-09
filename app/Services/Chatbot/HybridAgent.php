<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotConversation;
use App\Models\ChatbotUnmatched;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HybridAgent
{
    public function __construct(
        private InputGuard $guard,
        private OutputValidator $validator,
        private RuleBasedEngine $ruleEngine,
        private ClaudeAIEngine $aiEngine,
        private DatabaseLookupService $databaseLookup,
    ) {}

    public function process(string $userMessage, string $sessionId): array
    {
        $sessionId = $this->safeSessionId($sessionId);
        $this->ensureConversation($sessionId);

        $guard = $this->guard->check($userMessage);

        if ($guard->isBlocked()) {
            if ($guard->response === null) {
                return $this->silentBlock();
            }

            $result = $this->guardBlockResult($guard->response, $guard->blockedReason);
            $this->saveMessages($sessionId, $userMessage, $result);

            Log::info('[HybridAgent] InputGuard blocked', [
                'reason'  => $guard->blockedReason,
                'message' => mb_substr($userMessage, 0, 120),
            ]);

            return $this->respond($result, 'guard');
        }

        try {
            $databaseResult = $this->databaseLookup->handle($userMessage);

            if ($databaseResult !== null) {
                $this->saveMessages($sessionId, $userMessage, $databaseResult);
                return $this->respond($databaseResult, 'database');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[HybridAgent] DatabaseLookup failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $userMessage,
            ]);
        }

        $result = $this->ruleEngine->handle($userMessage);

        if ($result !== null) {
            $this->saveMessages($sessionId, $userMessage, $result);
            return $this->respond($result, 'rule_based');
        }

        try {
            $databaseResult = $this->databaseLookup->handle($userMessage);

            if ($databaseResult !== null) {
                $this->saveMessages($sessionId, $userMessage, $databaseResult);
                return $this->respond($databaseResult, 'database');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[HybridAgent] DatabaseLookup failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $userMessage,
            ]);
        }

        $this->saveUnmatched($userMessage, $sessionId);

        if ($this->aiEngine->isAvailable()) {
            $history = $this->getHistory($sessionId, 8);
            $aiResult = $this->aiEngine->handle($userMessage, $history);

            if ($aiResult !== null) {
                $validation = $this->validator->validate((string) $aiResult['answer'], $userMessage);

                if ($validation->isAccepted()) {
                    $aiResult['answer'] = $validation->response;
                    $this->saveMessages($sessionId, $userMessage, $aiResult);
                    return $this->respond($aiResult, 'ai');
                }

                Log::warning('[HybridAgent] AI output rejected by OutputValidator', [
                    'reason' => $validation->reason,
                    'query'  => mb_substr($userMessage, 0, 180),
                ]);

                $safeResult = $this->guardBlockResult($validation->response, 'ai_output_rejected');
                $this->saveMessages($sessionId, $userMessage, $safeResult);
                return $this->respond($safeResult, 'guard');
            }
        }

        $fallback = $this->fallbackResult($userMessage);
        $this->saveMessages($sessionId, $userMessage, $fallback);
        return $this->respond($fallback, 'fallback');
    }

    private function guardBlockResult(string $response, ?string $reason): array
    {
        return [
            'answer'       => $response,
            'source'       => 'guard',
            'confidence'   => 100,
            'matched'      => true,
            'knowledge_id' => null,
            'category'     => $reason ?? 'blocked',
            'suggestions'  => $this->ruleEngine->defaultSuggestions(),
        ];
    }

    private function fallbackResult(string $query): array
    {
        $safe = e($query);

        return [
            'answer'       => "I don't have verified information about <em>\"{$safe}\"</em> yet.<br><br>"
                . 'Your question has been recorded so staff can add the correct answer to the chatbot knowledge base.<br><br>'
                . 'For immediate help, please contact or visit Barangay Hulong Duhat during office hours.',
            'source'       => 'fallback',
            'confidence'   => 0,
            'matched'      => false,
            'knowledge_id' => null,
            'category'     => 'unknown',
            'suggestions'  => $this->ruleEngine->defaultSuggestions(),
        ];
    }

    private function silentBlock(): array
    {
        return [
            'answer'      => '',
            'source'      => 'guard',
            'confidence'  => 0,
            'matched'     => false,
            'category'    => 'empty',
            'suggestions' => [],
        ];
    }

    private function respond(array $result, string $layer): array
    {
        return [
            'answer'       => $result['answer'] ?? '',
            'source'       => $layer,
            'confidence'   => $result['confidence'] ?? 0,
            'matched'      => $result['matched'] ?? false,
            'category'     => $result['category'] ?? 'general',
            'knowledge_id' => $result['knowledge_id'] ?? null,
            'suggestions'  => $result['suggestions'] ?? $this->ruleEngine->defaultSuggestions(),
        ];
    }

    private function getHistory(string $sessionId, int $limit): array
    {
        try {
            return DB::table('chatbot_messages')
                ->where('session_id', $sessionId)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get(['role', 'content'])
                ->reverse()
                ->values()
                ->map(function ($message) {
                    return [
                        'role'    => $message->role === 'user' ? 'user' : 'assistant',
                        'content' => (string) $message->content,
                    ];
                })
                ->toArray();
        } catch (\Throwable $e) {
            Log::warning('[HybridAgent] getHistory failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function saveMessages(string $sessionId, string $userMsg, array $result): void
    {
        try {
            $now = now()->toDateTimeString();

            DB::table('chatbot_messages')->insert([
                [
                    'session_id'   => $sessionId,
                    'role'         => 'user',
                    'content'      => mb_substr($userMsg, 0, 1000),
                    'matched'      => DB::raw('FALSE'),
                    'knowledge_id' => null,
                    'created_at'   => $now,
                ],
                [
                    'session_id'   => $sessionId,
                    'role'         => 'bot',
                    'content'      => mb_substr(strip_tags((string) ($result['answer'] ?? '')), 0, 5000),
                    'matched'      => ($result['matched'] ?? false) ? DB::raw('TRUE') : DB::raw('FALSE'),
                    'knowledge_id' => $result['knowledge_id'] ?? null,
                    'created_at'   => $now,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[HybridAgent] saveMessages failed', ['error' => $e->getMessage()]);
        }
    }

    private function saveUnmatched(string $query, string $sessionId): void
    {
        try {
            $normalized = mb_strtolower(trim(preg_replace('/\s+/', ' ', $query) ?? $query));

            $alreadyExists = ChatbotUnmatched::query()
                ->where('session_id', $sessionId)
                ->whereRaw('LOWER(query) = ?', [$normalized])
                ->where('resolved', false)
                ->exists();

            if (! $alreadyExists) {
                ChatbotUnmatched::create([
                    'query'      => mb_substr($query, 0, 500),
                    'session_id' => $sessionId,
                    'resolved'   => false,
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[HybridAgent] saveUnmatched failed', ['error' => $e->getMessage()]);
        }
    }

    private function ensureConversation(string $sessionId): void
    {
        try {
            ChatbotConversation::firstOrCreate(
                ['session_id' => $sessionId],
                ['started_at' => now()]
            );
        } catch (\Throwable $e) {
            Log::warning('[HybridAgent] ensureConversation failed', ['error' => $e->getMessage()]);
        }
    }

    private function safeSessionId(string $sessionId): string
    {
        $sessionId = trim($sessionId);
        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

        if ($sessionId !== '' && preg_match($uuidPattern, $sessionId)) {
            return $sessionId;
        }

        return Str::uuid()->toString();
    }
}
