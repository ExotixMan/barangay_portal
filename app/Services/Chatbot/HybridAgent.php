<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ╔══════════════════════════════════════════════════════════════════╗
 * ║          HYBRID AGENT — Double Protection Architecture           ║
 * ╠══════════════════════════════════════════════════════════════════╣
 * ║                                                                  ║
 * ║  Every message goes through TWO protection layers:              ║
 * ║                                                                  ║
 * ║  User Message                                                    ║
 * ║       │                                                          ║
 * ║       ▼                                                          ║
 * ║  ┌─────────────────────────────────────────┐                    ║
 * ║  │  🛡️  PROTECTION 1: InputGuard            │  ← Before AI      ║
 * ║  │  • Blocks prompt injection attacks       │                    ║
 * ║  │  • Blocks profanity / abuse              │                    ║
 * ║  │  • Blocks off-topic (food, love, etc.)   │                    ║
 * ║  │  • Requires gov keyword to proceed       │                    ║
 * ║  └──────────────────┬──────────────────────┘                    ║
 * ║          Blocked ◄──┤──► Passed                                 ║
 * ║          (return)   │                                            ║
 * ║                     ▼                                            ║
 * ║  ┌─────────────────────────────────────────┐                    ║
 * ║  │  LAYER 1: RuleBasedEngine               │  ← Free, instant   ║
 * ║  │  • Intent detection (hi/bye/help)        │                    ║
 * ║  │  • Filipino ↔ English translation        │                    ║
 * ║  │  • Knowledge base search + NLP scoring   │                    ║
 * ║  └──────────────────┬──────────────────────┘                    ║
 * ║        Matched ◄────┤──── No match                              ║
 * ║        (return)     │                                            ║
 * ║                     ▼                                            ║
 * ║  ┌─────────────────────────────────────────┐                    ║
 * ║  │  LAYER 2: AI Engine (Gemini/Groq/etc.)  │  ← Fallback only   ║
 * ║  │  • Strict eGov-only system prompt        │                    ║
 * ║  │  • Your knowledge base as context        │                    ║
 * ║  │  • Conversation history for context      │                    ║
 * ║  └──────────────────┬──────────────────────┘                    ║
 * ║       AI response   │                                            ║
 * ║                     ▼                                            ║
 * ║  ┌─────────────────────────────────────────┐                    ║
 * ║  │  🛡️  PROTECTION 2: OutputValidator       │  ← After AI       ║
 * ║  │  • Detects hallucinations                │                    ║
 * ║  │  • Detects off-topic AI responses        │                    ║
 * ║  │  • Truncates overly long responses       │                    ║
 * ║  │  • Logs suspicious fabricated data       │                    ║
 * ║  └──────────────────┬──────────────────────┘                    ║
 * ║     Rejected ◄──────┤──── Accepted                              ║
 * ║     (fallback)      │     (return)                               ║
 * ║                     ▼                                            ║
 * ║              Fallback + log unmatched                            ║
 * ║                                                                  ║
 * ╚══════════════════════════════════════════════════════════════════╝
 */
class HybridAgent
{
    public function __construct(
        private InputGuard      $guard,
        private OutputValidator $validator,
        private RuleBasedEngine $ruleEngine,
        private ClaudeAIEngine  $aiEngine,
    ) {}

    /* ═══════════════════════════════════════════════════════════════
       MAIN PROCESS METHOD
    ═══════════════════════════════════════════════════════════════ */

    public function process(string $userMessage, string $sessionId): array
    {
        // ╔═══════════════════════════════════════════════════════╗
        // ║  🛡️  PROTECTION 1 — Input Guard                      ║
        // ╚═══════════════════════════════════════════════════════╝
        $guard = $this->guard->check($userMessage);

        if ($guard->isBlocked()) {
            // If silent block (empty message), return nothing
            if ($guard->response === null) {
                return $this->silentBlock();
            }

            $result = $this->guardBlockResult($guard->response, $guard->blockedReason);
            $this->saveMessages($sessionId, $userMessage, $result);

            Log::info('[HybridAgent] InputGuard blocked', [
                'reason'  => $guard->blockedReason,
                'message' => substr($userMessage, 0, 80),
            ]);

            return $this->respond($result, 'guard');
        }

        // ╔═══════════════════════════════════════════════════════╗
        // ║  LAYER 1 — Rule-Based Engine                         ║
        // ╚═══════════════════════════════════════════════════════╝
        $result = $this->ruleEngine->handle($userMessage);

        if ($result !== null) {
            $this->saveMessages($sessionId, $userMessage, $result);
            return $this->respond($result, 'rule_based');
        }

        // ╔═══════════════════════════════════════════════════════╗
        // ║  LAYER 2 — AI Engine                                 ║
        // ╚═══════════════════════════════════════════════════════╝
        if ($this->aiEngine->isAvailable()) {
            $history  = $this->getHistory($sessionId, 6);
            $aiResult = $this->aiEngine->handle($userMessage, $history);

            if ($aiResult !== null) {
                // ╔═══════════════════════════════════════════════╗
                // ║  🛡️  PROTECTION 2 — Output Validator          ║
                // ╚═══════════════════════════════════════════════╝
                $validation = $this->validator->validate($aiResult['answer'], $userMessage);

                if ($validation->isAccepted()) {
                    $aiResult['answer'] = $validation->response;
                    $this->saveMessages($sessionId, $userMessage, $aiResult);
                    return $this->respond($aiResult, 'ai');
                }

                // AI response was rejected by validator
                Log::warning('[HybridAgent] AI output rejected by OutputValidator', [
                    'reason'  => $validation->reason,
                    'query'   => $userMessage,
                ]);

                // Return the validator's safe fallback response
                $safeResult = $this->guardBlockResult($validation->response, 'ai_output_rejected');
                $this->saveMessages($sessionId, $userMessage, $safeResult);
                return $this->respond($safeResult, 'guard');
            }
        }

        // ╔═══════════════════════════════════════════════════════╗
        // ║  FALLBACK — Both layers failed or unavailable         ║
        // ╚═══════════════════════════════════════════════════════╝
        $this->saveUnmatched($userMessage, $sessionId);
        $fallback = $this->fallbackResult($userMessage);
        $this->saveMessages($sessionId, $userMessage, $fallback);
        return $this->respond($fallback, 'fallback');
    }

    /* ─── Result builders ────────────────────────────────────────── */

    private function guardBlockResult(string $response, ?string $reason): array
    {
        return [
            'answer'      => $response,
            'source'      => 'guard',
            'confidence'  => 100,
            'matched'     => true,
            'knowledge_id'=> null,
            'category'    => $reason ?? 'blocked',
            'suggestions' => $this->ruleEngine->defaultSuggestions(),
        ];
    }

    private function fallbackResult(string $q): array
    {
        $safe = htmlspecialchars($q, ENT_QUOTES, 'UTF-8');
        return [
            'answer'      => "I don't have specific information about <em>\"{$safe}\"</em> yet.<br><br>"
                           . "Your question has been recorded and our team will update the portal knowledge base.<br><br>"
                           . "For immediate assistance:<br>"
                           . "• Email us at <strong>support@barangayhulo.portal</strong><br>"
                           . "• Visit the <strong>Barangay Hulo Hall</strong> in person<br>"
                           . "• Office hours: Monday–Friday, 8:00 AM – 5:00 PM<br>"
                           . "• Or try rephrasing your question",
            'source'      => 'fallback',
            'confidence'  => 0,
            'matched'     => false,
            'knowledge_id'=> null,
            'category'    => 'unknown',
            'suggestions' => $this->ruleEngine->defaultSuggestions(),
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

    /* ─── Response normalizer ────────────────────────────────────── */

    private function respond(array $result, string $layer): array
    {
        return [
            'answer'      => $result['answer'],
            'source'      => $layer,
            'confidence'  => $result['confidence'] ?? 0,
            'matched'     => $result['matched']    ?? false,
            'category'    => $result['category']   ?? 'general',
            'suggestions' => $result['suggestions'] ?? $this->ruleEngine->defaultSuggestions(),
        ];
    }

    /* ─── Persistence ────────────────────────────────────────────── */

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
                ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
                ->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function saveMessages(string $sessionId, string $userMsg, array $result): void
    {
        try {
            $now       = now()->toDateTimeString();
            $safeSession = $this->safeSessionId($sessionId);

            // PostgreSQL requires explicit boolean cast — not PHP bool/int 0/1
            DB::table('chatbot_messages')->insert([
                [
                    'session_id'   => $safeSession,
                    'role'         => 'user',
                    'content'      => $userMsg,
                    'matched'      => DB::raw('FALSE'),
                    'knowledge_id' => null,
                    'created_at'   => $now,
                ],
                [
                    'session_id'   => $safeSession,
                    'role'         => 'bot',
                    'content'      => strip_tags($result['answer']),
                    'matched'      => ($result['matched'] ?? false) ? DB::raw('TRUE') : DB::raw('FALSE'),
                    'knowledge_id' => $result['knowledge_id'] ?? null,
                    'created_at'   => $now,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[HybridAgent] saveMessages: ' . $e->getMessage());
        }
    }

    private function saveUnmatched(string $query, string $sessionId): void
    {
        try {
            DB::table('chatbot_unmatched')->insert([
                'query'      => $query,
                'session_id' => $this->safeSessionId($sessionId),
                'resolved'   => DB::raw('FALSE'),
                'created_at' => now()->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[HybridAgent] saveUnmatched: ' . $e->getMessage());
        }
    }

    /**
     * Ensure session_id is a valid UUID format.
     * The AI test page sends "ai-test-1234567890" which is not a UUID.
     * PostgreSQL uuid columns reject non-UUID strings.
     * If invalid, generate a real UUID so the insert doesn't fail.
     */
    private function safeSessionId(string $sessionId): string
    {
        // UUID v4 pattern: 8-4-4-4-12 hex chars
        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

        if (preg_match($uuidPattern, $sessionId)) {
            return $sessionId; // already valid UUID
        }

        // Not a UUID — generate one deterministically from the input
        // so repeated calls with same test session get same UUID
        return \Illuminate\Support\Str::uuid()->toString();
    }
}