<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class RuleBasedEngine
{
    private const CONFIDENCE_THRESHOLD = 0.28;

    private array $filEng = [
        'pano' => 'how', 'paano' => 'how', 'papano' => 'how',
        'makakuha' => 'get', 'makuha' => 'get', 'kuha' => 'get', 'kunin' => 'get', 'kumuha' => 'get',
        'mag-apply' => 'apply', 'mag apply' => 'apply', 'i-apply' => 'apply', 'apply' => 'apply',
        'mag-request' => 'request', 'mag request' => 'request', 'humingi' => 'request', 'hingi' => 'request',
        'kailangan' => 'requirements', 'requirements' => 'requirements', 'need' => 'requirements',
        'hakbang' => 'steps', 'proseso' => 'process', 'gawin' => 'do', 'gawa' => 'do',
        'magkano' => 'fee', 'bayad' => 'fee', 'presyo' => 'fee', 'halaga' => 'fee', 'libre' => 'free',
        'oras' => 'hours', 'saan' => 'where', 'kailan' => 'when', 'sino' => 'who',
        'sertipiko' => 'certificate', 'certipiko' => 'certificate', 'dokumento' => 'document',
        'mahirap' => 'indigent', 'indigent' => 'indigent', 'indigency' => 'indigency',
        'residente' => 'resident', 'tirahan' => 'residency', 'paninirahan' => 'residency',
        'reklamo' => 'complaint', 'sumbong' => 'complaint', 'insidente' => 'incident', 'blotter' => 'blotter',
        'trabaho' => 'job', 'first time job seeker' => 'first time job seeker', 'valid id' => 'valid id',
        'id' => 'id', 'pagkakakilanlan' => 'id', 'upload' => 'upload', 'i-upload' => 'upload',
        'subaybayan' => 'track', 'i-track' => 'track', 'status' => 'status', 'reference' => 'reference',
    ];

    private array $stopWords = [
        'a','an','the','is','it','in','on','at','to','for','of','and','or','but','with','this','that',
        'can','do','does','i','my','me','we','you','your','about','any','some','please','how','what','who',
        'po','ang','ng','na','sa','mga','ko','mo','ba','ay','nila','namin','ninyo','kayo','sila','ito','iyan','iyon',
        'may','yung','yong','ung','si','ni','kay','para','din','rin','lang','naman','kasi','eh','dito','doon','dyan',
    ];

    private array $intentMap = [
        'greeting' => ['hello','hi','hey','good morning','good afternoon','good evening','mabuhay','kumusta','kamusta','magandang umaga','magandang hapon','magandang gabi'],
        'farewell' => ['bye','goodbye','see you','take care','paalam','ingat'],
        'thanks' => ['thank you','thanks','salamat','maraming salamat','thank'],
        'help' => ['help','tulong','what can you do','services','available services','menu','ano pwede','ano ang services'],
    ];

    private array $govKeywords = [
        'barangay','hulong duhat','hulo','malabon','bisig','portal','clearance','indigency','indigent',
        'residency','resident','first time job','first-time job','job seeker','blotter','incident','report',
        'complaint','certificate','sertipiko','document','request','reference id','tracking','track','login',
        'register','account','notification','approved','pending','rejected','remarks','upload','valid id',
        'proof of residency','requirements','steps','hakbang','office hours','barangay hall','support',
    ];

    public function handle(string $message): ?array
    {
        $cleaned = $this->clean($message);

        $intent = $this->detectIntent($cleaned);
        if ($intent) {
            return $this->intentResponse($intent);
        }

        if ($this->isShortOffTopic($cleaned)) {
            return $this->offTopicResponse();
        }

        $translated = $this->translate($cleaned);
        $terms = $this->extractSearchTerms($translated . ' ' . $cleaned);

        $candidates = $this->searchKnowledge($cleaned, $translated, $terms);
        if ($candidates->isEmpty()) {
            return null;
        }

        $best = $this->rankAndScore($terms, $translated, $candidates);
        if (! $best || ($best['score'] ?? 0) < self::CONFIDENCE_THRESHOLD) {
            return null;
        }

        try {
            DB::table('chatbot_knowledge')->where('id', $best['id'])->increment('usage_count');
        } catch (\Throwable $e) {
            Log::warning('[RuleEngine] usage_count increment failed', ['error' => $e->getMessage()]);
        }

        return [
            'answer'       => $this->formatAnswer((string) $best['answer']),
            'source'       => 'rule_based',
            'confidence'   => (int) round($best['score'] * 100),
            'matched'      => true,
            'knowledge_id' => (int) $best['id'],
            'category'     => $best['category'] ?? 'general',
            'suggestions'  => $this->suggestions($best['category'] ?? 'general'),
        ];
    }

    private function isShortOffTopic(string $cleaned): bool
    {
        if ($this->hasGovKeyword($cleaned)) {
            return false;
        }

        return str_word_count($cleaned) <= 5;
    }

    private function translate(string $text): string
    {
        $result = $text;
        $map = $this->filEng;
        uksort($map, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($map as $fil => $eng) {
            $result = preg_replace('/\b' . preg_quote($fil, '/') . '\b/u', $eng, $result) ?? $result;
        }

        return trim(preg_replace('/\s+/', ' ', $result) ?? $result);
    }

    private function searchKnowledge(string $original, string $translated, array $terms): \Illuminate\Support\Collection
    {
        try {
            $results = collect();

            foreach (array_unique(array_filter([$translated, $original])) as $phrase) {
                $results = $results->merge(ChatbotKnowledge::search($phrase, 10));
            }

            foreach (array_unique($terms) as $term) {
                if (mb_strlen($term) >= 3) {
                    $results = $results->merge(ChatbotKnowledge::search($term, 5));
                }
            }

            return $results->unique('id')->values();
        } catch (\Throwable $e) {
            Log::error('[RuleEngine] search failed', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    private function rankAndScore(array $qTerms, string $translated, Collection $candidates): ?array
    {
        $best = null;

        foreach ($candidates as $item) {
            $score = $this->computeScore($qTerms, $translated, $item);
            if (! $best || $score > ($best['score'] ?? 0)) {
                $best = $item->toArray();
                $best['score'] = $score;
            }
        }

        return $best;
    }

    private function computeScore(array $qTerms, string $translated, ChatbotKnowledge $item): float
    {
        $score = 0.0;

        $qNorm = $this->clean($translated);
        $question = $this->clean((string) ($item->question ?? ''));
        $answer = $this->clean((string) ($item->answer ?? ''));
        $category = $this->clean((string) ($item->category ?? ''));
        $keywordsRaw = $this->clean((string) ($item->keywords ?? ''));

        if ($qNorm !== '' && $question !== '') {
            if ($qNorm === $question) {
                return 1.0;
            }

            if ((str_contains($qNorm, $question) || str_contains($question, $qNorm)) && mb_strlen($qNorm) >= 10) {
                $score += 0.40;
            }
        }

        $queryTerms = array_values(array_unique(array_filter($qTerms)));
        $questionTerms = $this->extractSearchTerms($question);
        $answerTerms = $this->extractSearchTerms($answer);
        $keywordTerms = $this->extractSearchTerms(str_replace(',', ' ', $keywordsRaw));

        $meaningful = array_values(array_diff($queryTerms, ['barangay','hulong','duhat','malabon','bisig','portal','how','what','get','request']));
        if (empty($meaningful)) {
            $meaningful = $queryTerms;
        }

        $questionOverlap = count(array_intersect($meaningful, $questionTerms));
        $score += min(($questionOverlap / max(count($meaningful), 1)) * 0.35, 0.35);

        $keywordOverlap = count(array_intersect($meaningful, $keywordTerms));
        $score += min(($keywordOverlap / max(count($meaningful), 1)) * 0.30, 0.30);

        $answerOverlap = count(array_intersect($meaningful, $answerTerms));
        $score += min(($answerOverlap / max(count($meaningful), 1)) * 0.15, 0.15);

        foreach ($meaningful as $term) {
            if (mb_strlen($term) >= 4 && str_contains($category, $term)) {
                $score += 0.08;
                break;
            }
        }

        return min($score, 1.0);
    }

    private function extractSearchTerms(string $text): array
    {
        $text = $this->clean($text);
        $words = preg_split('/\s+/', $text) ?: [];

        return array_values(array_filter($words, function ($word) {
            $word = trim($word);
            return mb_strlen($word) >= 2 && ! in_array($word, $this->stopWords, true);
        }));
    }

    private function clean(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\pL\pN\s\-]/u', ' ', $text) ?? $text;
        return trim(preg_replace('/\s+/', ' ', $text) ?? $text);
    }

    private function formatAnswer(string $raw): string
    {
        return nl2br(e($raw));
    }

    private function detectIntent(string $cleaned): ?string
    {
        foreach ($this->intentMap as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                if ($cleaned === $phrase || Str::contains(' ' . $cleaned . ' ', ' ' . $phrase . ' ')) {
                    return $intent;
                }
            }
        }

        return null;
    }

    private function hasGovKeyword(string $cleaned): bool
    {
        foreach ($this->govKeywords as $kw) {
            if (Str::contains(' ' . $cleaned . ' ', ' ' . $kw . ' ') || Str::contains($cleaned, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function offTopicResponse(): array
    {
        return [
            'answer'       => 'I can only assist with Barangay Hulong Duhat Portal services.<br><br>I can help you with Barangay Clearance, Indigency Certificate, Residency Certificate, First-Time Job Seeker Certificate, Incident Reports, and request tracking. What do you need?',
            'source'       => 'rule_based',
            'confidence'   => 100,
            'matched'      => true,
            'knowledge_id' => null,
            'category'     => 'off_topic',
            'suggestions'  => $this->defaultSuggestions(),
        ];
    }

    private function intentResponse(string $intent): array
    {
        return match ($intent) {
            'greeting' => [
                'answer' => "Hi! I'm <strong>InfoHulo Assistant</strong>, your Barangay Hulong Duhat Portal assistant.<br><br>I can help you with Clearance, Indigency, Residency, First-Time Job Seeker Certificate, Incident Reports, and request tracking.<br><br>What can I help you with today?",
                'source' => 'rule_based', 'confidence' => 100, 'matched' => true, 'knowledge_id' => null, 'category' => 'greeting', 'suggestions' => $this->defaultSuggestions(),
            ],
            'farewell' => [
                'answer' => 'Thank you for using Barangay Hulong Duhat Portal. Have a great day!',
                'source' => 'rule_based', 'confidence' => 100, 'matched' => true, 'knowledge_id' => null, 'category' => 'farewell', 'suggestions' => [],
            ],
            'thanks' => [
                'answer' => "You're welcome! Is there anything else I can help you with?",
                'source' => 'rule_based', 'confidence' => 100, 'matched' => true, 'knowledge_id' => null, 'category' => 'general', 'suggestions' => $this->defaultSuggestions(),
            ],
            'help' => [
                'answer' => "Hi! I'm InfoHulo Assistant.<br><br>I can help you with:<br><strong>Barangay Clearance</strong><br><strong>Indigency Certificate</strong><br><strong>Residency Certificate</strong><br><strong>First-Time Job Seeker Certificate</strong><br><strong>Incident Report / Blotter</strong><br><strong>Request Tracking</strong><br><br>Type your question in Filipino or English.",
                'source' => 'rule_based', 'confidence' => 100, 'matched' => true, 'knowledge_id' => null, 'category' => 'help', 'suggestions' => $this->defaultSuggestions(),
            ],
            default => $this->offTopicResponse(),
        };
    }

    public function suggestions(string $cat): array
    {
        return match ($cat) {
            'clearance' => ['Clearance requirements', 'Track my request', 'Clearance processing time'],
            'indigency' => ['Indigency requirements', 'Is indigency free?', 'Track my request'],
            'residency' => ['Residency requirements', 'Proof of residency', 'Track my request'],
            'first_time_job_seeker' => ['First-time job seeker requirements', 'Is it free?', 'Track my request'],
            'blotter' => ['How to file incident report', 'Upload evidence', 'Track my report'],
            'tracking' => ['What is a Reference ID?', 'My request is rejected', 'Contact support'],
            'valid_id' => ['Valid ID requirements', 'Upload valid ID', 'Rejected valid ID remarks'],
            'portal' => ['How to register', 'Forgot password', 'Contact support'],
            'support' => ['Office hours', 'Contact support', 'Track my request'],
            default => $this->defaultSuggestions(),
        };
    }

    public function defaultSuggestions(): array
    {
        return [
            'Barangay Clearance',
            'Indigency Certificate',
            'Residency Certificate',
            'First-Time Job Seeker',
            'Incident Report',
            'Track my request',
            'Latest announcements',
            'Upcoming events',
        ];
    }
}
