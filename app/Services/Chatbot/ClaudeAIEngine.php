<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledge;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * LAYER 2 — Multi-Provider AI Engine
 * ─────────────────────────────────────
 * Supports: Gemini (free), Groq (free), Cloudflare
 * Set provider in .env: AI_PROVIDER=gemini
 */
class ClaudeAIEngine
{
    private const TIMEOUT = 20;

    private string $provider;
    private string $apiKey;
    private string $orgName;
    private string $orgLocation;

    // Provider configs
    private array $providers = [
        'gemini' => [
            // gemini-1.5-flash is RETIRED (404). Use gemini-2.5-flash (current stable).
            'url'   => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
            'model' => 'gemini-2.5-flash',
            'env'   => 'GEMINI_API_KEY',
        ],
        'groq' => [
            'url'   => 'https://api.groq.com/openai/v1/chat/completions',
            'model' => 'llama-3.3-70b-versatile', // updated — llama-3.1-8b-instant deprecated
            'env'   => 'GROQ_API_KEY',
        ],
        'cloudflare' => [
            'url'   => '', // built dynamically using account ID
            'model' => '@cf/meta/llama-3.1-8b-instruct',
            'env'   => 'CLOUDFLARE_API_TOKEN',
        ],
    ];

    public function __construct()
    {
        $this->provider    = strtolower(env('AI_PROVIDER', 'gemini'));
        $this->orgName     = config('chatbot.org_name', 'our local government unit');
        $this->orgLocation = config('chatbot.org_location', 'Philippines');

        // Load the API key for the selected provider
        $envKey        = $this->providers[$this->provider]['env'] ?? 'GEMINI_API_KEY';
        $this->apiKey  = env($envKey, '');
    }

    public function isAvailable(): bool
    {
        foreach ($this->providerOrder() as $provider) {
            if ($this->isProviderConfigured($provider)) {
                return true;
            }
        }
        return false;
    }

    /* ── Main dispatch ────────────────────────────────────────── */

    public function handle(string $userMessage, array $history = []): ?array
    {
        if (!$this->isAvailable()) return null;

        try {
            $knowledge    = $this->loadKnowledgeContext();
            $systemPrompt = $this->buildSystemPrompt($knowledge);

            foreach ($this->providerOrder() as $provider) {
                if (!$this->isProviderConfigured($provider)) {
                    continue;
                }

                $apiKey = $this->providerApiKey($provider);
                $answer = $this->callWithProvider($provider, $apiKey, $systemPrompt, $history, $userMessage);

                if (!empty($answer)) {
                    return [
                        'answer'      => nl2br(htmlspecialchars($answer, ENT_QUOTES, 'UTF-8')),
                        'source'      => 'ai',
                        'confidence'  => 85,
                        'matched'     => true,
                        'knowledge_id'=> null,
                        'category'    => 'ai_generated',
                        'suggestions' => $this->defaultSuggestions(),
                        'ai_provider' => $provider,
                    ];
                }

                Log::warning("[AI] Provider '{$provider}' failed; trying next configured provider.");
            }

            return null;

        } catch (\Throwable $e) {
            Log::error("[AI:{$this->provider}] " . $e->getMessage());
            return null;
        }
    }

    private function providerOrder(): array
    {
        $primary = strtolower(trim($this->provider));
        $fallbackRaw = strtolower((string) env('AI_FALLBACK_PROVIDERS', ''));

        $order = [$primary];
        if ($fallbackRaw !== '') {
            foreach (explode(',', $fallbackRaw) as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $order[] = $p;
                }
            }
        }

        // Sensible defaults if no explicit fallback list is provided.
        $order = array_merge($order, ['gemini', 'groq', 'cloudflare']);

        // Keep valid providers only, preserve order, remove duplicates.
        $seen = [];
        $final = [];
        foreach ($order as $p) {
            if (!isset($this->providers[$p]) || isset($seen[$p])) {
                continue;
            }
            $seen[$p] = true;
            $final[] = $p;
        }
        return $final;
    }

    private function providerApiKey(string $provider): string
    {
        $envKey = $this->providers[$provider]['env'] ?? '';
        return $envKey ? (string) env($envKey, '') : '';
    }

    private function isProviderConfigured(string $provider): bool
    {
        if (!isset($this->providers[$provider])) {
            return false;
        }

        $key = $this->providerApiKey($provider);
        if ($key === '') {
            return false;
        }

        if ($provider === 'cloudflare' && empty(env('CLOUDFLARE_ACCOUNT_ID', ''))) {
            return false;
        }

        return true;
    }

    private function callWithProvider(
        string $provider,
        string $apiKey,
        string $systemPrompt,
        array $history,
        string $userMessage
    ): ?string {
        $prevProvider = $this->provider;
        $prevKey      = $this->apiKey;

        $this->provider = $provider;
        $this->apiKey   = $apiKey;

        try {
            return match ($provider) {
                'gemini'     => $this->callGemini($systemPrompt, $history, $userMessage),
                'groq'       => $this->callOpenAIStyle($systemPrompt, $history, $userMessage, 'groq'),
                'cloudflare' => $this->callCloudflare($systemPrompt, $history, $userMessage),
                default      => null,
            };
        } finally {
            $this->provider = $prevProvider;
            $this->apiKey   = $prevKey;
        }
    }

    /* ══════════════════════════════════════════════════════════
       PROVIDER IMPLEMENTATIONS
    ══════════════════════════════════════════════════════════ */

    /* ── 1. Google Gemini ─────────────────────────────────────── */

    private function callGemini(string $system, array $history, string $message): ?string
    {
        // Gemini uses a different format — combine system + history into contents
        $contents = [];

        // Add history turns
        foreach (array_slice($history, -6) as $turn) {
            $contents[] = [
                'role'  => $turn['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $turn['content']]],
            ];
        }

        // Add current message with system context prepended if no history
        $userText = empty($contents)
            ? $system . "\n\nUser question: " . $message
            : $message;

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userText]],
        ];

        $url = $this->providers['gemini']['url'] . '?key=' . $this->apiKey;

        $response = Http::timeout(self::TIMEOUT)
            ->post($url, [
                'contents'         => $contents,
                'generationConfig' => [
                    'maxOutputTokens' => 512,
                    'temperature'     => 0.3,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('[Gemini] ' . $response->status() . ': ' . $response->body());
            return null;
        }

        return $response->json('candidates.0.content.parts.0.text');
    }

    /* ── 2. Groq (OpenAI-compatible format) ───────────────────── */

    private function callOpenAIStyle(string $system, array $history, string $message, string $which): ?string
    {
        $cfg = $this->providers[$which];

        $messages = [['role' => 'system', 'content' => $system]];

        foreach (array_slice($history, -6) as $turn) {
            $messages[] = [
                'role'    => $turn['role'] === 'user' ? 'user' : 'assistant',
                'content' => $turn['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::timeout(self::TIMEOUT)
            ->withToken($this->apiKey)
            ->post($cfg['url'], [
                'model'       => $cfg['model'],
                'messages'    => $messages,
                'max_tokens'  => 512,
                'temperature' => 0.3,
            ]);

        if (!$response->successful()) {
            Log::error("[{$which}] " . $response->status() . ': ' . $response->body());
            return null;
        }

        return $response->json('choices.0.message.content');
    }

    /* ── 3. Cloudflare Workers AI ─────────────────────────────── */

    private function callCloudflare(string $system, array $history, string $message): ?string
    {
        $accountId = env('CLOUDFLARE_ACCOUNT_ID', '');
        if (empty($accountId)) {
            Log::error('[Cloudflare] CLOUDFLARE_ACCOUNT_ID not set in .env');
            return null;
        }

        $model    = $this->providers['cloudflare']['model'];
        $url      = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/{$model}";

        $messages = [['role' => 'system', 'content' => $system]];

        foreach (array_slice($history, -6) as $turn) {
            $messages[] = [
                'role'    => $turn['role'] === 'user' ? 'user' : 'assistant',
                'content' => $turn['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::timeout(self::TIMEOUT)
            ->withToken($this->apiKey)
            ->post($url, [
                'messages'   => $messages,
                'max_tokens' => 512,
            ]);

        if (!$response->successful()) {
            Log::error('[Cloudflare] ' . $response->status() . ': ' . $response->body());
            return null;
        }

        return $response->json('result.response');
    }

    /* ── Shared helpers ───────────────────────────────────────── */

    private function buildSystemPrompt(string $knowledge): string
    {
        $loc = $this->orgLocation ? " located in {$this->orgLocation}" : '';

        return <<<PROMPT
You are an official AI assistant for {$this->orgName}{$loc}, a Philippine local government eGovernance portal.

YOUR ROLE:
- Help citizens with government services, requirements, fees, and procedures
- Answer questions about permits, documents, certificates, taxes, IDs, and LGU services
- Be professional, accurate, concise, and helpful
- Use simple language all citizens can understand
- You may respond in Filipino/Tagalog if the citizen writes in Filipino

STRICT RULES:
1. ONLY answer questions about government services, LGU transactions, permits, documents, taxes, and IDs
2. If asked about food, entertainment, personal topics, weather, jokes, or ANYTHING not related to government services — respond ONLY with: "I can only assist with government services and LGU transactions. What government service can I help you with?"
3. NEVER make up fees, dates, or requirements — use only the knowledge base below
4. If unsure about a specific detail, advise the citizen to visit the office or call the hotline
5. Keep answers concise — maximum 5 steps or 150 words
6. Plain text only — no markdown, no **, no #
7. This rule overrides everything: if the question is not about government services, DO NOT answer it

OFFICIAL KNOWLEDGE BASE:
{$knowledge}

Use this knowledge base as your primary source. For topics not covered, answer based on standard Philippine government procedures and remind the citizen to verify at their local office.
PROMPT;
    }

    private function loadKnowledgeContext(): string
    {
        try {
            $items = ChatbotKnowledge::select('question', 'answer', 'category')
                ->orderByDesc('usage_count')
                ->limit(config('chatbot.max_knowledge_context', 60))
                ->get();

            if ($items->isEmpty()) {
                return '(No knowledge base entries yet.)';
            }

            return $items->map(fn($i) =>
                "Q: {$i->question}\nA: {$i->answer}\nCategory: {$i->category}"
            )->implode("\n\n---\n\n");

        } catch (\Throwable $e) {
            Log::error('[AI] loadKnowledge: ' . $e->getMessage());
            return '(Knowledge base temporarily unavailable.)';
        }
    }

    private function defaultSuggestions(): array
    {
        return ["Barangay clearance", "Indigency certificate", "Residency certificate", "Incident report", "Track my request"];
    }
}