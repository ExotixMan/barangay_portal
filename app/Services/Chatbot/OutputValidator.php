<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Log;

class OutputValidator
{
    private array $validRefusalPhrases = [
        'i can only assist',
        'outside my area',
        'barangay hulong duhat portal services',
        'hindi ko masasagot',
        'nakatuon lamang',
        'serbisyo ng barangay',
    ];

    private array $hallucinationFlags = [
        'here is a recipe', 'ingredients', 'tablespoon', 'teaspoon', 'preheat oven',
        'the weather today', 'temperature is', 'chance of rain',
        'relationship advice', 'to win someone',
        'public function', '<?php', 'console.log', 'import react',
        'bitcoin', 'crypto trading', 'stock market',
    ];

    private array $sensitiveUnverifiedPatterns = [
        '/\+63\d{10}/',
        '/(?<!\d)09\d{9}(?!\d)/',
        '/\b(?:php|₱)\s*\d+(?:,\d{3})*(?:\.\d{2})?\b/i',
        '/\bhttps?:\/\//i',
    ];

    public function validate(string $aiResponse, string $originalQuery): ValidationResult
    {
        $plain = trim(html_entity_decode(strip_tags($aiResponse), ENT_QUOTES, 'UTF-8'));
        $lower = mb_strtolower($plain);

        if ($plain === '') {
            return ValidationResult::reject(
                'empty_response',
                'Pasensya na po, hindi ko nasagot ang inyong tanong. Please contact or visit Barangay Hulong Duhat for immediate assistance.'
            );
        }

        foreach ($this->validRefusalPhrases as $phrase) {
            if (str_contains($lower, $phrase)) {
                return ValidationResult::accept($aiResponse, 'ai_refused_correctly');
            }
        }

        foreach ($this->hallucinationFlags as $flag) {
            if (str_contains($lower, $flag)) {
                Log::warning('[OutputValidator] off-topic AI content detected', [
                    'flag'  => $flag,
                    'query' => mb_substr($originalQuery, 0, 180),
                ]);

                return ValidationResult::reject(
                    'off_topic_or_hallucination',
                    'I can only assist with Barangay Hulong Duhat Portal services.<br><br>What barangay service can I help you with?'
                );
            }
        }

        foreach ($this->sensitiveUnverifiedPatterns as $pattern) {
            if (preg_match($pattern, $plain)) {
                Log::warning('[OutputValidator] possible fabricated contact/fee/link blocked', [
                    'pattern' => $pattern,
                    'query'   => mb_substr($originalQuery, 0, 180),
                ]);

                return ValidationResult::reject(
                    'possible_fabricated_specific_detail',
                    'I cannot confirm the exact fee, contact number, or link for that from the chatbot knowledge base.<br><br>Please verify it with Barangay Hulong Duhat staff before proceeding.'
                );
            }
        }

        if (mb_strlen($plain) > 1200) {
            Log::warning('[OutputValidator] long AI response truncated', [
                'length' => mb_strlen($plain),
                'query'  => mb_substr($originalQuery, 0, 180),
            ]);

            return ValidationResult::accept($this->truncate($plain, 1200), 'truncated');
        }

        return ValidationResult::accept($aiResponse, 'clean');
    }

    private function truncate(string $plainText, int $maxLen): string
    {
        $truncated = mb_substr($plainText, 0, $maxLen);
        $lastBreak = max(
            mb_strrpos($truncated, '.') ?: 0,
            mb_strrpos($truncated, '!') ?: 0,
            mb_strrpos($truncated, '?') ?: 0,
            mb_strrpos($truncated, "\n") ?: 0,
        );

        if ($lastBreak > ($maxLen * 0.6)) {
            $truncated = mb_substr($truncated, 0, $lastBreak + 1);
        }

        return nl2br(e($truncated)) . '<br><br><small><em>For complete details, please verify with Barangay Hulong Duhat staff.</em></small>';
    }
}

class ValidationResult
{
    private function __construct(
        public readonly bool $accepted,
        public readonly string $response,
        public readonly string $reason,
    ) {}

    public static function accept(string $response, string $reason): self
    {
        return new self(true, $response, $reason);
    }

    public static function reject(string $reason, string $fallbackResponse): self
    {
        return new self(false, $fallbackResponse, $reason);
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }
}
