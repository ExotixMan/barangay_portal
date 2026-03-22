<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * LAYER 1 — Rule-Based Engine (Filipino + English)
 * ──────────────────────────────────────────────────
 * Understands both Filipino and English queries.
 * Translates Filipino keywords → English before searching.
 */
class RuleBasedEngine
{
    private const CONFIDENCE_THRESHOLD = 0.20; // slightly lower to prefer verified KB over AI for near-exact admin-curated entries

    /* ── Filipino → English keyword map ──────────────────────────
     * When a user says "pano maka kuha ng clearance",
     * we extract: pano→how, kuha→get, clearance stays
     * Then search: "how get clearance" → matches "barangay clearance"
     */
    private array $filEng = [
        // Action words
        'pano'        => 'how',
        'paano'       => 'how',
        'makakuha'    => 'get',
        'makuha'      => 'get',
        'kuha'        => 'get',
        'kunin'       => 'get',
        'kumuha'      => 'get',
        'mag-apply'   => 'apply',
        'mag apply'   => 'apply',
        'mag-avail'   => 'apply',
        'apply'       => 'apply',
        'i-renew'     => 'renew',
        'i renew'     => 'renew',
        'renew'       => 'renew',
        'bayad'       => 'pay',
        'magbayad'    => 'pay',
        'bayaran'     => 'pay',
        'humingi'     => 'request',
        'hingi'       => 'request',
        'request'     => 'request',
        'gawin'       => 'do',
        'gawa'        => 'do',
        'proseso'     => 'process',
        'hakbang'     => 'steps',
        'requirements'=> 'requirements',
        'kailangan'   => 'requirements',
        'need'        => 'requirements',
        'libre'       => 'free',
        'bayad'       => 'fee',
        'magkano'     => 'fee',
        'presyo'      => 'fee',
        'halaga'      => 'fee',
        'oras'        => 'hours',
        'saan'        => 'where',
        'kailan'      => 'when',
        'sino'        => 'who',

        // Document / service words
        'clearance'       => 'clearance',
        'barangay'        => 'barangay',
        'permit'          => 'permit',
        'permiso'         => 'permit',
        'negosyo'         => 'business',
        'business'        => 'business',
        'tindahan'        => 'business',
        'kapanganakan'    => 'birth',
        'ipinanganak'     => 'birth',
        'birth'           => 'birth',
        'kasal'           => 'marriage',
        'kamatayan'       => 'death',
        'sertipiko'       => 'certificate',
        'certificate'     => 'certificate',
        'certipiko'       => 'certificate',
        'buwis'           => 'tax',
        'amilyar'         => 'property tax',
        'tax'             => 'tax',
        'lupa'            => 'property',
        'ari-arian'       => 'property',
        'senior'          => 'senior',
        'matatanda'       => 'senior',
        'lolo'            => 'senior',
        'lola'            => 'senior',
        'may kapansanan'  => 'disability',
        'pwd'             => 'PWD',
        'id'              => 'ID',
        'pagkakakilanlan' => 'ID',
        'philhealth'      => 'philhealth',
        'kalusugan'       => 'health',
        'ospital'         => 'health',
        'nbi'             => 'NBI',
        'pagkamamamayan'  => 'citizenship',
        'indigent'        => 'indigent',
        'indigency'       => 'indigency',
        'mahirap'         => 'indigent',
        'hanapbuhay'      => 'livelihood',
        'trabaho'         => 'employment',
        'cedula'          => 'cedula',
        'community tax'   => 'cedula',
        'kasunduan'       => 'contract',
    ];

    /* ── Stop words — Filipino + English ─────────────────────────
     * Only strip truly meaningless words — keep action/content words
     */
    private array $stopWords = [
        // English
        'a','an','the','is','it','in','on','at','to','for','of','and','or',
        'but','with','this','that','can','do','does','i','my','me','we',
        'you','your','about','any','some','please',
        // Filipino — particles/articles only
        'po','po','ang','ng','na','sa','mga','ko','mo','ba','ay',
        'nila','namin','ninyo','kayo','sila','ito','iyan','iyon','may',
        'yung','yong','ung','si','ni','kay','para','din','rin','lang',
        'po','naman','kasi','eh','kaya','dito','doon','dyan',
    ];

    /* ── Intent map ───────────────────────────────────────────── */
    private array $intentMap = [
        'greeting' => [
            'hello','hi','hey','good morning','good afternoon','good evening',
            'mabuhay','kumusta','kamusta','magandang umaga','magandang hapon',
            'magandang gabi','magandang tanghali',
        ],
        'farewell' => [
            'bye','goodbye','see you','take care','salamat na','paalam',
            'thank you bye','hanggang','ingat',
        ],
        'thanks'   => [
            'thank you','thanks','salamat','maraming salamat','thank',
            'nagpapasalamat','pasalamat',
        ],
        'help'     => [
            'help','what can you do','what do you know','list','menu',
            'services','available','ano ang','ano kaya','tulungan',
            'tulong','makatulong',
        ],
    ];

    /* ── Off-topic words — these are NEVER government topics ────
     * If the query only contains these and no gov keywords, reject early.
     */
    private array $offTopicWords = [
        // Food / cooking
        'ulam','pagkain','kain','kumain','gutom','busog','luto','niluto','magluto',
        'recipe','cook','food','eat','lunch','dinner','breakfast','merienda','snack',
        'kanin','bigas','gulay','isda','karne','manok','baboy','beef','chicken','pork',
        'bake','cookies','cake','tinapay','bread','adobo','sinigang','nilaga',
        // Entertainment / sports
        'pelikula','movie','film','kanta','musika','music','song','singer','artist',
        'laro','game','sports','basketball','football','volleyball','boxing',
        'netflix','youtube','tiktok','facebook post','social media',
        // Romance / personal
        'mahal','love','puso','heart','crush','boyfriend','girlfriend','jowa',
        'pakikipaghiwalay','break up','date','ligaw','courtship',
        // Weather / nature (non-gov)
        'weather','panahon','ulan','bagyo','typhoon','araw','mainit','malamig','lindol',
        // Misc non-gov
        'joke','funny','tawa','iyak','malungkot','masaya','boring',
        'wifi','load','data','pulsa','cellphone signal',
        'stock market','crypto','bitcoin','negosyo tips','investment',
        'school assignment','homework','math','science','history lesson',
        'diet','exercise','workout','gym','slimming',
    ];

    /* ── Government topic keywords — at least one must be present ─
     * for a non-intent query to proceed to knowledge search.
     */
    private array $govKeywords = [
        // BISIG Hulo specific
        'barangay','hulong duhat','malabon','bisig','hulo','portal',
        // Core services of BISIG Hulo
        'clearance','indigency','indigent','blotter','certificate','sertipiko',
        'incident','incident report','residency','resident certificate',
        'dokumento','document','request',
        // System / portal words
        'reference id','tracking','track','login','register','account',
        'notification','approved','pending','rejected','download','upload',
        'mag-apply','i-submit','i-download','i-track','i-cancel','i-appeal',
        'request status','support',
        // Filipino action words
        'kuha','kunin','makuha','mag-file','mag-request','pano','paano',
        'kailangan','requirements','steps','hakbang',
        'saan','anong oras','office hours','tanggapan',
        'residente','resident','staff','barangay hall',
    ];

    /* ─────────────────────────────────────────────────────────── */

    public function handle(string $message): ?array
    {
        $cleaned = $this->clean($message);

        // 1. Intent check (greeting, farewell, thanks, help)
        $intent = $this->detectIntent($cleaned);
        if ($intent) {
            return $this->intentResponse($intent);
        }

        // 2. Off-topic guard — reject non-government questions immediately
        if ($this->isOffTopic($cleaned)) {
            return $this->offTopicResponse();
        }

        // 3. Translate Filipino → English keywords
        $translated = $this->translate($cleaned);

        // 4. Extract meaningful search terms (skip stop words)
        $searchTerms = $this->extractSearchTerms($translated);

        // 5. Search knowledge base using both original and translated terms
        $candidates = $this->searchKnowledge($cleaned, $translated, $searchTerms);

        if ($candidates->isEmpty()) {
            return null; // → AI layer
        }

        // 6. Score and pick best match
        $best = $this->rankAndScore($searchTerms, $translated, $candidates);

        if (!$best || $best['score'] < self::CONFIDENCE_THRESHOLD) {
            return null; // → AI layer
        }

        // 6. Increment usage count
        try {
            DB::table('chatbot_knowledge')->where('id', $best['id'])->increment('usage_count');
        } catch (\Throwable $e) {}

        return [
            'answer'      => $this->formatAnswer($best['answer']),
            'source'      => 'rule_based',
            'confidence'  => (int) round($best['score'] * 100),
            'matched'     => true,
            'knowledge_id'=> (int) $best['id'],
            'category'    => $best['category'] ?? 'general',
            'suggestions' => $this->suggestions($best['category'] ?? 'general'),
        ];
    }

    /* ── Off-topic detection ─────────────────────────────────────
     * Returns true if message has NO government keywords at all.
     * "ano ulam" → no gov keywords → off-topic → polite redirect.
     * "pano kuha ng clearance" → has "clearance" (gov) → NOT off-topic.
     */
    private function isOffTopic(string $cleaned): bool
    {
        // Check if any government keyword exists in the message
        foreach ($this->govKeywords as $kw) {
            if (str_contains(' ' . $cleaned . ' ', ' ' . $kw . ' ') || str_contains($cleaned, $kw)) {
                return false; // has a gov keyword → proceed normally
            }
        }

        // No gov keyword found → off-topic
        // But only flag if query is short (1-3 words) and off-topic words present
        $words = explode(' ', trim($cleaned));
        if (count($words) <= 5) {
            return true; // short message with no gov keyword → off-topic
        }

        // Longer messages may still be gov-related even without exact keywords
        // Let the AI handle it
        return false;
    }

    private function offTopicResponse(string $categoryLabel = ''): array
    {
        $label = $categoryLabel ? " ({$categoryLabel})" : '';
        $responses = [
            "I can only assist with Barangay Hulo Portal services. That topic{$label} is outside my area.<br><br>"
            . "I can help you with Clearance, Indigency, Residency, Incident Reports, or request tracking. What do you need?",

            "Sorry, I'm not able to help with that{$label}. I'm InfoHulo Assistant — your Barangay Hulo Portal assistant.<br><br>"
            . "Is there a portal service I can help you with?",
        ];

        return [
            'answer'      => $responses[array_rand($responses)],
            'source'      => 'rule_based',
            'confidence'  => 100,
            'matched'     => true,
            'knowledge_id'=> null,
            'category'    => 'off_topic',
            'suggestions' => $this->defaultSuggestions(),
        ];
    }

    /* ── Translation ──────────────────────────────────────────── */

    private function translate(string $text): string
    {
        $result = $text;

        // Sort by length descending so longer phrases match first
        $map = $this->filEng;
        uksort($map, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($map as $fil => $eng) {
            $result = preg_replace('/\b' . preg_quote($fil, '/') . '\b/u', $eng, $result);
        }

        return $result;
    }

    /* ── Search ───────────────────────────────────────────────── */

    private function searchKnowledge(string $original, string $translated, array $terms): \Illuminate\Support\Collection
    {
        try {
            $results = collect();

            // Search using translated text
            if (!empty($translated)) {
                $r = ChatbotKnowledge::search($translated, 10);
                $results = $results->merge($r);
            }

            // Search using each individual term (catches partial matches)
            foreach (array_unique($terms) as $term) {
                if (strlen($term) >= 3) {
                    $r = ChatbotKnowledge::search($term, 5);
                    $results = $results->merge($r);
                }
            }

            // Also search original message (catches English words in mixed messages)
            if ($original !== $translated) {
                $r = ChatbotKnowledge::search($original, 5);
                $results = $results->merge($r);
            }

            // Deduplicate by id
            return $results->unique('id')->values();

        } catch (\Throwable $e) {
            Log::error('[RuleEngine] search failed: ' . $e->getMessage());
            return collect();
        }
    }

    /* ── Scoring ──────────────────────────────────────────────── */

    private function extractSearchTerms(string $text): array
    {
        $words = explode(' ', $text);
        return array_values(array_filter(
            $words,
            fn($w) => strlen($w) >= 2 && !in_array(strtolower($w), $this->stopWords)
        ));
    }

    private function rankAndScore(array $qTerms, string $translated, $candidates): ?array
    {
        $best = null;

        foreach ($candidates as $item) {
            $score = $this->computeScore($qTerms, $translated, $item);
            if (!$best || $score > ($best['score'] ?? 0)) {
                $best          = $item->toArray();
                $best['score'] = $score;
            }
        }

        return $best;
    }

    private function computeScore(array $qTerms, string $translated, $item): float
    {
        $s = 0.0;

        $qNorm = trim(strtolower($translated));
        $iQuestion = trim(strtolower($item->question ?? ''));
        $iAnswer   = trim(strtolower($item->answer ?? ''));

        // Hard match: when admin-added KB question is identical or almost identical.
        if ($qNorm !== '' && $iQuestion !== '') {
            if ($qNorm === $iQuestion) {
                return 1.0;
            }

            // Near-exact phrase containment gets a strong boost.
            if ((str_contains($qNorm, $iQuestion) || str_contains($iQuestion, $qNorm))
                && (strlen($qNorm) >= 12 || strlen($iQuestion) >= 12)) {
                $s += 0.45;
            }
        }

        $qTokens  = array_map('strtolower', $qTerms);
        $qUnique  = array_unique($qTokens);

        // ── SIGNAL 1: Token overlap with KB question (most important — 50%) ──
        // Filters out generic words: "barangay", "how", "get" that appear everywhere.
        // Only rewards tokens that BOTH the query and KB question share.
        $iTokens     = $this->extractSearchTerms(strtolower($item->question ?? ''));
        $meaningful  = array_diff($qUnique, ['barangay','hulong','duhat','malabon','ano','sino','paano','pano','how','what','who','the','is','are','does','do','can','i','my']);
        $overlap     = count(array_intersect($meaningful ?: $qUnique, $iTokens));
        $total       = max(count($meaningful ?: $qUnique), count($iTokens), 1);
        $s          += ($overlap / $total) * 0.50;

        // ── SIGNAL 2: Exact keyword match from KB keywords field (35%) ──
        // "clearance" exactly in keywords list = strong signal
        $keywords     = array_map('trim', explode(',', strtolower($item->keywords ?? '')));
        $keywordWords = []; // flatten all keyword phrases into individual words
        foreach ($keywords as $kw) {
            foreach (explode(' ', $kw) as $kword) {
                if (strlen($kword) >= 3) $keywordWords[] = $kword;
            }
        }
        $kwHits = 0;
        foreach ($qUnique as $t) {
            if (strlen($t) < 3) continue;
            if (in_array($t, ['barangay','hulong','duhat','malabon','bisig'])) continue;

            if (in_array($t, $keywords)) {
                $kwHits += 1.0; // exact full-phrase keyword match (strongest)
            } elseif (in_array($t, $keywordWords)) {
                $kwHits += 0.7; // exact single-word match inside keyword phrase
            } else {
                foreach ($keywords as $kw) {
                    if (strlen($kw) > 4 && str_contains($kw, $t) && $t !== 'barangay') {
                        $kwHits += 0.3;
                        break;
                    }
                }
            }
        }
        $s += min($kwHits * 0.175, 0.35);

        // ── SIGNAL 3: Meaningful terms in KB question text (20%) ──
        // Only count non-generic terms to avoid "barangay" inflating every score
        $termHits = 0;
        foreach ($qUnique as $t) {
            if (strlen($t) < 4) continue;
            if (in_array($t, ['barangay','hulong','duhat','malabon','bisig','hall'])) continue;
            if (str_contains(strtolower($item->question ?? ''), $t)) {
                $termHits++;
            }
        }
        $s += min($termHits * 0.10, 0.20);

        // ── SIGNAL 4: Meaningful terms in KB answer text (15%) ──
        // Helps if admin stores the fact in answer while question is phrased differently.
        $answerHits = 0;
        foreach ($qUnique as $t) {
            if (strlen($t) < 4) continue;
            if (in_array($t, ['barangay','hulong','duhat','malabon','bisig','hall'])) continue;
            if (str_contains($iAnswer, $t)) {
                $answerHits++;
            }
        }
        $s += min($answerHits * 0.075, 0.15);

        return min($s, 1.0);
    }

    /* ── Helpers ──────────────────────────────────────────────── */

    private function clean(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^\w\s\-]/u', ' ', $text); // keep hyphens for mag-apply etc.
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    private function formatAnswer(string $raw): string
    {
        return nl2br(htmlspecialchars($raw, ENT_QUOTES, 'UTF-8'));
    }

    private function detectIntent(string $cleaned): ?string
    {
        foreach ($this->intentMap as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                if (str_contains(' ' . $cleaned . ' ', ' ' . $phrase . ' ') || $cleaned === $phrase) {
                    return $intent;
                }
            }
        }
        return null;
    }

    /* ── Intent responses ─────────────────────────────────────── */

    private function intentResponse(string $intent): array
    {
        return match ($intent) {
            'greeting' => [
                'answer'      => "Hi there! I'm <strong>InfoHulo Assistant</strong>, your Barangay Hulo Portal assistant.<br><br>"
                               . "I can help you with Barangay Clearance, Indigency Certificate, Residency Certificate, Incident Reports, and request tracking.<br><br>"
                               . "What can I help you with today?",
                'source'      => 'rule_based',
                'confidence'  => 100,
                'matched'     => true,
                'knowledge_id'=> null,
                'category'    => 'greeting',
                'suggestions' => $this->defaultSuggestions(),
            ],
            'farewell' => [
                'answer'      => "Thank you for using the Barangay Hulo Portal! Have a great day. If you need help again, I'm always here.",
                'source'      => 'rule_based',
                'confidence'  => 100,
                'matched'     => true,
                'knowledge_id'=> null,
                'category'    => 'farewell',
                'suggestions' => [],
            ],
            'thanks' => [
                'answer'      => "You're welcome! Is there anything else I can help you with?",
                'source'      => 'rule_based',
                'confidence'  => 100,
                'matched'     => true,
                'knowledge_id'=> null,
                'category'    => 'general',
                'suggestions' => $this->defaultSuggestions(),
            ],
            'help' => [
                'answer'      => "Hi! I'm InfoHulo Assistant, your Barangay Hulo Portal assistant.<br><br>"
                               . "I can help you with:<br>"
                               . "<strong>Barangay Clearance</strong> — request online through the portal<br>"
                               . "<strong>Indigency Certificate</strong> — request online, free of charge<br>"
                               . "<strong>Residency Certificate</strong> — request online through the portal<br>"
                               . "<strong>Incident Report</strong> — submit online, reviewed by staff<br>"
                               . "<strong>Request Tracking</strong> — check status using your Reference ID<br><br>"
                               . "Just type your question — Filipino or English — and I'll guide you step by step!",
                'source'      => 'rule_based',
                'confidence'  => 100,
                'matched'     => true,
                'knowledge_id'=> null,
                'category'    => 'help',
                'suggestions' => $this->defaultSuggestions(),
            ],
            default => null,
        };
    }

    /* ── Suggestions ──────────────────────────────────────────── */

    public function suggestions(string $cat): array
    {
        return match ($cat) {
            'clearance'   => ["Clearance requirements", "Track my request", "Download my clearance"],
            'indigency'   => ["Indigency requirements", "Track my request", "Is indigency free?"],
            'residency'   => ["Residency requirements", "Track my request", "Download my residency certificate"],
            'blotter'     => ["How to file incident report", "Track my report", "Upload evidence"],
            'tracking'    => ["What is a Reference ID?", "My request is rejected", "Contact support"],
            'portal'      => ["How to register", "Track my request", "Contact support"],
            'support'     => ["Email support", "Office hours", "Cancel a request"],
            default       => $this->defaultSuggestions(),
        };
    }

    public function defaultSuggestions(): array
    {
        return [
            "Barangay Clearance",
            "Indigency Certificate",
            "Residency Certificate",
            "Incident Report",
            "Track my request",
        ];
    }
}