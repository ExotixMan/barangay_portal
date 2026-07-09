<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Log;

class InputGuard
{
    private array $safeIntentPhrases = [
        'hi', 'hello', 'hey', 'help', 'tulong', 'menu', 'services', 'salamat', 'thanks', 'thank you',
        'good morning', 'good afternoon', 'good evening', 'magandang umaga', 'magandang hapon', 'magandang gabi',
    ];

    private array $injectionPatterns = [
        'ignore previous',
        'ignore all previous',
        'ignore your instructions',
        'disregard previous',
        'forget your instructions',
        'you are now',
        'you are no longer',
        'pretend you are',
        'act as if you are',
        'your new instructions',
        'override instructions',
        'system prompt',
        'reveal your prompt',
        'show your prompt',
        'print your instructions',
        'what are your instructions',
        'jailbreak',
        'developer mode',
        'unrestricted mode',
        'no restrictions',
        '<system>',
        '[system]',
    ];

    private array $profanity = [
        'putang ina', 'putangina', 'puta ka', 'gago ka', 'bobo ka', 'inutil', 'bwisit', 'lintik',
        'siraulo', 'tarantado', 'pakshet', 'pakyu', 'fuck', 'fucking', 'fucked', 'shit', 'bitch',
        'wtf', 'stfu', 'asshole', 'bastard', 'bullshit', 'gago', 'bobo', 'tanga', 'ulol', 'leche',
    ];

    private array $offTopicCategories = [
        'food/cooking' => ['ulam', 'pagkain', 'kain', 'kumain', 'gutom', 'luto', 'recipe', 'cook', 'food', 'eat', 'lunch', 'dinner', 'breakfast', 'adobo', 'sinigang', 'cookies', 'cake'],
        'entertainment' => ['movie', 'pelikula', 'kanta', 'music', 'song', 'game', 'sports', 'basketball', 'netflix', 'youtube', 'tiktok', 'anime', 'celebrity'],
        'personal/romance' => ['love', 'mahal', 'crush', 'boyfriend', 'girlfriend', 'jowa', 'date', 'ligaw', 'break up', 'hugot'],
        'weather only' => ['weather today', 'current weather', 'forecast today', 'temperature today'],
        'school/academics' => ['homework', 'assignment', 'essay', 'math problem', 'solve equation', 'exam reviewer'],
        'technology/coding' => ['coding', 'programming', 'python', 'javascript', 'html', 'css', 'debug code', 'sql query'],
        'casual/off-topic' => ['joke', 'funny', 'meme', 'chismis', 'gossip', 'boring'],
    ];

    private array $govKeywords = [
        'barangay', 'hulong duhat', 'hulo', 'malabon', 'bisig', 'portal',
        'clearance', 'indigency', 'indigent', 'residency', 'resident certificate', 'first time job',
        'first-time job', 'job seeker', 'blotter', 'incident', 'report', 'complaint', 'reklamo',
        'certificate', 'sertipiko', 'document', 'dokumento', 'request', 'reference id', 'tracking', 'track',
        'login', 'register', 'account', 'notification', 'approved', 'pending', 'rejected', 'remarks',
        'upload', 'valid id', 'proof of residency', 'requirements', 'kailangan', 'proseso', 'hakbang',
        'mag-apply', 'apply', 'submit', 'kuha', 'kunin', 'makuha', 'mag-file', 'mag-request',
        'office hours', 'tanggapan', 'barangay hall', 'staff', 'support', 'contact', 'hotline',
        'announcement', 'announcements', 'patalastas', 'abiso', 'notice',
        'event', 'events', 'program', 'activity', 'activities', 'aktibidad',
        'project', 'projects', 'proyekto', 'project update', 'progress',
        'status', 'reference', 'ref', 'released', 'completed',
        'baha', 'flood', 'sunog', 'fire', 'emergency', 'rescue', 'evacuation', 'relief',
    ];

    public function check(string $message): GuardResult
    {
        $raw = trim($message);
        $lower = $this->normalize($raw);

        if (mb_strlen($raw) < 2) {
            return GuardResult::block('too_short', 'Please type at least 2 characters so I can help you with Barangay Hulong Duhat Portal services.');
        }

        if (mb_strlen($raw) > 500) {
            return GuardResult::block('too_long', 'Your message is too long. Please shorten it and try again.');
        }

        if ($this->isSafeIntent($lower)) {
            return GuardResult::pass();
        }

        foreach ($this->injectionPatterns as $pattern) {
            if (str_contains($lower, $pattern)) {
                Log::channel('security')->warning('[InputGuard] Injection attempt blocked', [
                    'pattern' => $pattern,
                    'message' => mb_substr($raw, 0, 120),
                ]);

                return GuardResult::block(
                    'injection',
                    "I'm not able to do that. I'm InfoHulo Assistant, the Barangay Hulong Duhat Portal assistant. How can I help you with a barangay service?"
                );
            }
        }

        foreach ($this->profanity as $word) {
            if ($this->containsWordOrPhrase($lower, $word)) {
                return GuardResult::block('profanity', "Please be respectful. I'm here to assist you with Barangay Hulong Duhat Portal services.");
            }
        }

        if (! $this->hasGovKeyword($lower)) {
            $offTopic = $this->detectOffTopic($lower);

            if ($offTopic !== null) {
                return GuardResult::block('off_topic', $this->offTopicResponse($offTopic));
            }

            $wordCount = str_word_count($lower);
            if ($wordCount <= 6) {
                return GuardResult::block(
                    'no_gov_context',
                    'I can only assist with Barangay Hulong Duhat Portal services.<br><br>What service can I help you with?'
                );
            }
        }

        return GuardResult::pass();
    }

    private function normalize(string $message): string
    {
        $message = mb_strtolower(trim($message));
        $message = preg_replace('/\s+/u', ' ', $message) ?? $message;
        return $message;
    }

    private function isSafeIntent(string $lower): bool
    {
        foreach ($this->safeIntentPhrases as $phrase) {
            if ($lower === $phrase || $this->containsWordOrPhrase($lower, $phrase)) {
                return true;
            }
        }

        return false;
    }

    private function detectOffTopic(string $lower): ?string
    {
        foreach ($this->offTopicCategories as $label => $words) {
            foreach ($words as $word) {
                if ($this->containsWordOrPhrase($lower, $word)) {
                    return $label;
                }
            }
        }

        return null;
    }

    private function hasGovKeyword(string $lower): bool
    {
        foreach ($this->govKeywords as $kw) {
            if ($this->containsWordOrPhrase($lower, $kw) || str_contains($lower, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function containsWordOrPhrase(string $text, string $needle): bool
    {
        $needle = mb_strtolower(trim($needle));

        if ($needle === '') {
            return false;
        }

        if (str_contains($needle, ' ')) {
            return str_contains($text, $needle);
        }

        return (bool) preg_match('/(?<![\pL\pN])' . preg_quote($needle, '/') . '(?![\pL\pN])/u', $text);
    }

    private function offTopicResponse(string $categoryLabel): string
    {
        return "I can only assist with Barangay Hulong Duhat Portal services. That topic (<em>{$categoryLabel}</em>) is outside my area.<br><br>"
            . 'I can help you with Barangay Clearance, Indigency Certificate, Residency Certificate, First-Time Job Seeker Certificate, Incident Reports, or request tracking. What do you need?';
    }
}

class GuardResult
{
    private function __construct(
        public readonly bool $passed,
        public readonly ?string $blockedReason,
        public readonly ?string $response,
    ) {}

    public static function pass(): self
    {
        return new self(true, null, null);
    }

    public static function block(string $reason, ?string $response): self
    {
        return new self(false, $reason, $response);
    }

    public function isBlocked(): bool
    {
        return ! $this->passed;
    }
}
