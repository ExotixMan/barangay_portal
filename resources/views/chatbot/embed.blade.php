@once
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/pages/chatbot/embed.css') }}">
@endonce

<div id="hulobot-backdrop"></div>

<div id="hulobot-modal" role="dialog" aria-modal="true" aria-label="InfoHulo Assistant">
    <div class="hbm-header">
        <div class="hbm-header-top">
            <div class="hbm-logo-ring">
                <img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant" class="hbm-logo-icon">
            </div>

            <div class="hbm-title-wrap">
                <h2 class="hbm-header-title">InfoHulo Assistant</h2>
                <div class="hbm-header-subtitle">Barangay Hulong Duhat e-Governance Helpdesk</div>
            </div>

            <button class="hbm-close" id="hbm-close" type="button" title="Close" aria-label="Close chatbot">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="hbm-status-row">
            <span class="hbm-pill"><i class="bi bi-shield-check"></i> Verified KB</span>
            <span class="hbm-pill"><i class="bi bi-database-check"></i> Live DB</span>
            <span class="hbm-pill"><i class="bi bi-clock"></i> 24/7 Guide</span>
        </div>
    </div>

    <div class="hbm-chat">
        <div class="hbm-msgs" id="hbm-msgs">
            <div class="hbm-date">Today</div>
            <div class="hbm-row bot" id="hbm-loading-row">
                <div class="hbm-avt"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div class="hbm-message-wrap">
                    <div class="hbm-bbl">Loading assistant...</div>
                </div>
            </div>
        </div>

        <div class="hbm-chips" id="hbm-chips"></div>

        <div class="hbm-composer">
            <div class="hbm-input-wrap">
                <textarea class="hbm-txt" id="hbm-input" rows="1" placeholder="Ask about requests, announcements, events..." maxlength="500"></textarea>
                <button class="hbm-send" id="hbm-send" type="button" title="Send" aria-label="Send message">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <div class="hbm-foot">
                <i class="bi bi-info-circle"></i>
                <span>Powered by BISIG — Barangay Hulong Duhat Portal</span>
            </div>
        </div>
    </div>
</div>

@include('shared.js-config', [
    'id' => 'js-config-chatbot-embed-1',
    'data' => [
        'v1' => e(asset('Images/aichatbotimg.jpg')),
    ],
])
<script src="{{ asset('js/pages/chatbot/embed-dynamic1.js') }}"></script>
