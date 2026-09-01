<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledge;
use App\Services\AdminSettingsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeAIEngine
{
    private const TIMEOUT = 20;
    private const MAX_HISTORY = 8;
    private const MAX_OUTPUT_TOKENS = 420;

    private string $provider;
    private string $apiKey;
    private string $orgName;
    private string $orgLocation;

    private array $providers = [
        'gemini' => [
            'url'   => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
            'model' => 'gemini-2.5-flash',
            'env'   => 'GEMINI_API_KEY',
        ],
        'groq' => [
            'url'   => 'https://api.groq.com/openai/v1/chat/completions',
            'model' => 'llama-3.3-70b-versatile',
            'env'   => 'GROQ_API_KEY',
        ],
        'cloudflare' => [
            'url'   => '',
            'model' => '@cf/google/gemma-4-26b-a4b-it',
            'env'   => 'CLOUDFLARE_API_TOKEN',
        ],
    ];

    public function __construct(private ?AdminSettingsService $settings = null)
    {
        $this->settings = $this->settings ?? app(AdminSettingsService::class);
        $this->provider = strtolower((string) $this->settings->get('ai.provider', env('AI_PROVIDER', 'gemini')));
        $this->orgName = config('chatbot.org_name', 'Barangay Hulong Duhat');
        $this->orgLocation = config('chatbot.org_location', 'Malabon City, Philippines');

        $envKey = $this->providers[$this->provider]['env'] ?? 'GEMINI_API_KEY';
        $this->apiKey = (string) $this->settings->get('ai.keys.' . $this->provider, env($envKey, ''));
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

    public function handle(string $userMessage, array $history = []): ?array
    {
        if (! $this->isAvailable()) {
            return null;
        }

        $knowledge = $this->loadKnowledgeContext();
        $systemPrompt = $this->buildSystemPrompt($knowledge);

        foreach ($this->providerOrder() as $provider) {
            if (! $this->isProviderConfigured($provider)) {
                continue;
            }

            try {
                $apiKey = $this->providerApiKey($provider);
                $answer = $this->callWithProvider($provider, $apiKey, $systemPrompt, $history, $userMessage);

                if ($answer !== null && trim($answer) !== '') {
                    return [
                        'answer'       => nl2br(e(trim($answer))),
                        'source'       => 'ai',
                        'confidence'   => 80,
                        'matched'      => true,
                        'knowledge_id' => null,
                        'category'     => 'ai_generated',
                        'suggestions'  => $this->defaultSuggestions(),
                        'ai_provider'  => $provider,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error("[AI:{$provider}] provider failed", [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ]);
            }
        }

        return null;
    }

    private function providerOrder(): array
    {
        $primary = strtolower(trim($this->provider));
        $fallbackRaw = strtolower((string) $this->settings->get('ai.fallback_providers', env('AI_FALLBACK_PROVIDERS', '')));

        $order = [$primary];
        foreach (explode(',', $fallbackRaw) as $provider) {
            $provider = trim($provider);
            if ($provider !== '') {
                $order[] = $provider;
            }
        }

        $order = array_merge($order, ['gemini', 'groq', 'cloudflare']);

        $seen = [];
        $final = [];
        foreach ($order as $provider) {
            if (isset($this->providers[$provider]) && ! isset($seen[$provider])) {
                $seen[$provider] = true;
                $final[] = $provider;
            }
        }

        return $final;
    }

    private function providerApiKey(string $provider): string
    {
        $envKey = $this->providers[$provider]['env'] ?? '';
        return (string) $this->settings->get('ai.keys.' . $provider, $envKey ? env($envKey, '') : '');
    }

    private function providerModel(string $provider): string
    {
        $default = $this->providers[$provider]['model'] ?? '';
        return (string) $this->settings->get('ai.models.' . $provider, env('AI_MODEL_' . strtoupper($provider), $default));
    }

    private function isProviderConfigured(string $provider): bool
    {
        if (! isset($this->providers[$provider])) {
            return false;
        }

        if ($this->providerApiKey($provider) === '') {
            return false;
        }

        if ($provider === 'cloudflare') {
            return (string) $this->settings->get('ai.cloudflare_account_id', env('CLOUDFLARE_ACCOUNT_ID', '')) !== '';
        }

        return true;
    }

    private function callWithProvider(string $provider, string $apiKey, string $systemPrompt, array $history, string $userMessage): ?string
    {
        $previousProvider = $this->provider;
        $previousKey = $this->apiKey;

        $this->provider = $provider;
        $this->apiKey = $apiKey;

        try {
            return match ($provider) {
                'gemini' => $this->callGemini($systemPrompt, $history, $userMessage),
                'groq' => $this->callOpenAIStyle($systemPrompt, $history, $userMessage, 'groq'),
                'cloudflare' => $this->callCloudflare($systemPrompt, $history, $userMessage),
                default => null,
            };
        } finally {
            $this->provider = $previousProvider;
            $this->apiKey = $previousKey;
        }
    }

    private function callGemini(string $system, array $history, string $message): ?string
    {
        $contents = [];

        foreach (array_slice($history, -self::MAX_HISTORY) as $turn) {
            $role = ($turn['role'] ?? '') === 'user' ? 'user' : 'model';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => strip_tags((string) ($turn['content'] ?? ''))]],
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $message]],
        ];

        $model = $this->providerModel('gemini');
        $url = str_replace('{model}', $model, $this->providers['gemini']['url']) . '?key=' . $this->apiKey;

        $response = Http::timeout(self::TIMEOUT)->post($url, [
            'systemInstruction' => [
                'parts' => [['text' => $system]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => self::MAX_OUTPUT_TOKENS,
                'temperature'     => 0.2,
                'topP'            => 0.8,
            ],
        ]);

        if (! $response->successful()) {
            Log::error('[Gemini] request failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        return $response->json('candidates.0.content.parts.0.text');
    }

    private function callOpenAIStyle(string $system, array $history, string $message, string $which): ?string
    {
        $cfg = $this->providers[$which];
        $messages = [['role' => 'system', 'content' => $system]];

        foreach (array_slice($history, -self::MAX_HISTORY) as $turn) {
            $messages[] = [
                'role'    => ($turn['role'] ?? '') === 'user' ? 'user' : 'assistant',
                'content' => strip_tags((string) ($turn['content'] ?? '')),
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::timeout(self::TIMEOUT)
            ->withToken($this->apiKey)
            ->post($cfg['url'], [
                'model'       => $this->providerModel($which),
                'messages'    => $messages,
                'max_tokens'  => self::MAX_OUTPUT_TOKENS,
                'temperature' => 0.2,
            ]);

        if (! $response->successful()) {
            Log::error("[{$which}] request failed", ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        return $response->json('choices.0.message.content');
    }

    private function callCloudflare(string $system, array $history, string $message): ?string
    {
        $accountId = (string) $this->settings->get('ai.cloudflare_account_id', env('CLOUDFLARE_ACCOUNT_ID', ''));
        if ($accountId === '') {
            return null;
        }

        $model = $this->providerModel('cloudflare');
        $url = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/{$model}";
        $messages = [['role' => 'system', 'content' => $system]];

        foreach (array_slice($history, -self::MAX_HISTORY) as $turn) {
            $messages[] = [
                'role'    => ($turn['role'] ?? '') === 'user' ? 'user' : 'assistant',
                'content' => strip_tags((string) ($turn['content'] ?? '')),
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::timeout(self::TIMEOUT)
            ->withToken($this->apiKey)
            ->post($url, [
                'messages'   => $messages,
                'max_tokens' => self::MAX_OUTPUT_TOKENS,
            ]);

        if (! $response->successful()) {
            Log::error('[Cloudflare] request failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        return $response->json('result.response');
    }

    private function buildSystemPrompt(string $knowledge): string
    {
        return <<<PROMPT
You are InfoHulo Assistant, the official chatbot for {$this->orgName}, {$this->orgLocation}.

ROLE:
- Help residents with Barangay Hulong Duhat portal services.
- Answer about barangay documents, requirements, valid ID uploads, request tracking, rejection remarks, office support, announcements, events, projects, and incident reports.
- Reply in the same language as the resident when possible.

STRICT RULES:
1. Only answer questions related to Barangay Hulong Duhat, its portal, and barangay/LGU services.
2. Do not answer food, entertainment, romance, homework, coding, jokes, or unrelated questions.
3. Never invent fees, phone numbers, office addresses, schedules, links, requirements, or processing times.
4. Use the official knowledge base below first.
5. If the knowledge base does not contain an exact detail, say that the detail needs staff verification.
6. Keep answers concise: maximum 5 bullets or 120 words.
7. Plain text only. No markdown headings.
8. For emergencies, advise the resident to contact barangay staff or emergency services immediately.

OFFICIAL KNOWLEDGE BASE:
{$knowledge}
PROMPT;
    }

    private function loadKnowledgeContext(): string
    {
        try {
            $items = ChatbotKnowledge::query()
                ->select('question', 'answer', 'category', 'keywords')
                ->orderByDesc('usage_count')
                ->limit((int) config('chatbot.max_knowledge_context', 80))
                ->get();

            if ($items->isEmpty()) {
                return '(No knowledge base entries yet.)';
            }

            return $items->map(function ($item) {
                return "Category: {$item->category}\nKeywords: {$item->keywords}\nQ: {$item->question}\nA: {$item->answer}";
            })->implode("\n\n---\n\n");
        } catch (\Throwable $e) {
            Log::error('[AI] loadKnowledge failed', ['error' => $e->getMessage()]);
            return '(Knowledge base temporarily unavailable.)';
        }
    }

    private function defaultSuggestions(): array
    {
        return [
            'Barangay Clearance',
            'Indigency Certificate',
            'Residency Certificate',
            'First-Time Job Seeker',
            'Incident Report',
            'Track my request',
        ];
    }
}
