<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Log;

/**
 * ╔══════════════════════════════════════════════════════════════════╗
 * ║                   OUTPUT VALIDATOR                               ║
 * ║             PROTECTION LAYER 2 — After AI                       ║
 * ╠══════════════════════════════════════════════════════════════════╣
 * ║                                                                  ║
 * ║  Runs AFTER the AI generates a response.                        ║
 * ║  Catches cases where AI "slipped" and answered off-topic        ║
 * ║  despite the strict system prompt.                               ║
 * ║                                                                  ║
 * ║  CHECKS:                                                         ║
 * ║  1. AI refused correctly (detect refusal phrases)               ║
 * ║  2. Response contains off-topic content (hallucination check)   ║
 * ║  3. Response is suspiciously long (possible hallucination)      ║
 * ║  4. Response contains dangerous content                         ║
 * ║  5. Response contains fabricated gov data                       ║
 * ║                                                                  ║
 * ╚══════════════════════════════════════════════════════════════════╝
 */
class OutputValidator
{
    /* ── Phrases that mean the AI already refused ─────────────────
     * If AI said these, it handled it correctly. Accept as-is.
     */
    private array $refusalPhrases = [
        'pasensya na po',
        'hindi ko masasagot',
        'hindi ko po masasagot',
        'i can only assist with',
        'i am only able to help',
        'nakatuon lamang',
        'para lamang sa',
        'bisig assistant',
        'barangay hulong duhat lamang',
        'serbisyo ng barangay lamang',
        'outside my area',
        'outside of my',
    ];

    /* ── Hallucination red flags ──────────────────────────────────
     * Phrases that suggest AI went off-topic or made things up.
     */
    private array $hallucinationFlags = [
        // Food/cooking leaked through
        'here is a recipe','here\'s a recipe','to make cookies','to bake',
        'ingredients','tablespoon','teaspoon','preheat oven','mix together',
        'narito ang recipe','paano magluto',
        // Weather leaked through
        'the weather today','current weather','temperature is',
        'will rain','chance of rain',
        // Romance/personal leaked through
        'love is','when you love','relationship advice','to win someone',
        // Coding leaked through
        'public function','<?php','console.log','import react',
        // Dangerous content
        'how to make bomb','illegal','drug','shabu',
    ];

    /* ── Suspicious fake gov data patterns ───────────────────────
     * AI sometimes invents specific fees, phone numbers, or dates.
     * Flag these so we can log them.
     */
    private array $fabricationPatterns = [
        '/\+63\d{10}/',            // fake phone numbers
        '/09\d{9}/',               // fake mobile numbers
        '/\bphp\s*\d{4,}\b/i',    // suspiciously large fee amounts
        '/\bhttps?:\/\/(?!(?:bisig|barangay|malabon|hulong))/i', // external URLs
    ];

    /* ═══════════════════════════════════════════════════════════════
       MAIN VALIDATE METHOD
    ═══════════════════════════════════════════════════════════════ */

    public function validate(string $aiResponse, string $originalQuery): ValidationResult
    {
        $lower = strtolower($aiResponse);

        // 1. Check if AI already refused correctly — accept it
        foreach ($this->refusalPhrases as $phrase) {
            if (str_contains($lower, $phrase)) {
                return ValidationResult::accept($aiResponse, 'ai_refused_correctly');
            }
        }

        // 2. Check for hallucination / off-topic content leaked through
        foreach ($this->hallucinationFlags as $flag) {
            if (str_contains($lower, $flag)) {
                Log::warning('[OutputValidator] Hallucination detected', [
                    'flag'  => $flag,
                    'query' => $originalQuery,
                    'response_snippet' => substr($aiResponse, 0, 150),
                ]);
                return ValidationResult::reject('hallucination',
                    "Pasensya na po, hindi ko masasagot ang tanong na iyon. "
                    . "Nakatuon lamang ako sa mga serbisyo ng <strong>Barangay Hulong Duhat</strong>.<br><br>"
                    . "Ano pong serbisyo ng barangay ang maipaglilingkod ko sa inyo?"
                );
            }
        }

        // 3. Suspiciously long response (> 800 chars) — possible hallucination
        if (strlen($aiResponse) > 800) {
            Log::warning('[OutputValidator] Overly long AI response truncation', [
                'length' => strlen($aiResponse),
                'query'  => $originalQuery,
            ]);
            // Truncate rather than reject — may be valid but verbose
            $truncated = $this->truncate($aiResponse, 800);
            return ValidationResult::accept($truncated, 'truncated');
        }

        // 4. Check for fabricated data patterns (log only, don't reject)
        foreach ($this->fabricationPatterns as $pattern) {
            if (preg_match($pattern, $aiResponse)) {
                Log::warning('[OutputValidator] Possible fabricated data in AI response', [
                    'pattern' => $pattern,
                    'query'   => $originalQuery,
                ]);
                // Don't reject — just log for review. Barangay staff can correct in admin.
                break;
            }
        }

        // 5. Empty response
        if (empty(trim(strip_tags($aiResponse)))) {
            return ValidationResult::reject('empty_response',
                "Pasensya na po, hindi ko nasagot ang inyong tanong. "
                . "Pakibisita ang Barangay Hall sa loob ng office hours para sa tulong."
            );
        }

        // ✅ Passed all checks
        return ValidationResult::accept($aiResponse, 'clean');
    }

    /* ── Smart truncate at sentence boundary ─────────────────────  */

    private function truncate(string $text, int $maxLen): string
    {
        if (strlen($text) <= $maxLen) return $text;

        $truncated = substr($text, 0, $maxLen);
        $lastPeriod = max(
            strrpos($truncated, '.'),
            strrpos($truncated, '!'),
            strrpos($truncated, '?'),
            strrpos($truncated, "\n"),
        );

        if ($lastPeriod > $maxLen * 0.6) {
            $truncated = substr($truncated, 0, $lastPeriod + 1);
        }

        return $truncated . '<br><br><small><em>Para sa kumpletong impormasyon, bisitahin ang Barangay Hall.</em></small>';
    }
}


/**
 * Value object returned by OutputValidator::validate()
 */
class ValidationResult
{
    private function __construct(
        public readonly bool    $accepted,
        public readonly string  $response,
        public readonly string  $reason,
    ) {}

    public static function accept(string $response, string $reason): self
    {
        return new self(true, $response, $reason);
    }

    public static function reject(string $reason, string $fallbackResponse): self
    {
        return new self(false, $fallbackResponse, $reason);
    }

    public function isAccepted(): bool { return $this->accepted; }
}
