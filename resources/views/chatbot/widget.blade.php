<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InfoHulo Assistant — Barangay Hulo Portal</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/chatbot/widget.css') }}">
</head>
<body>

<div class="hb-page">

    <div class="hb-page-header">
        <div class="hb-page-logo"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
        <div>
            <div class="hb-page-label">Barangay Hulong Duhat, Malabon City</div>
            <div class="hb-page-title">BISIG Portal Assistant</div>
        </div>
        <span class="hb-page-badge">Online</span>
    </div>

    <div class="hb-widget" id="hulobot">

        <div class="hb-header">
            <div class="hb-avatar"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
            <div>
                <div class="hb-name">InfoHulo Assistant</div>
                <div class="hb-status"><span class="hb-dot"></span>Barangay Hulo Portal Assistant</div>
            </div>
            <div class="hb-head-actions">
                <button class="hb-icon-btn" id="hb-clear" title="Clear conversation">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        <div class="hb-messages" id="hb-messages">
            <div class="hb-divider">Today</div>
        </div>

        <div class="hb-suggestions" id="hb-suggestions"></div>

        <div class="hb-input-bar">
            <textarea id="hb-input" rows="1"
                      placeholder="Type your question..."
                      maxlength="500"></textarea>
            <button id="hb-send" title="Send">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>

        <div class="hb-footer">
            Powered by BISIG &mdash; Barangay Hulo e-Governance Portal
        </div>

    </div>

    <div class="hb-info-row">
        <span>Need more help? <a href="mailto:support@barangayhulo.portal">support@barangayhulo.portal</a></span>
        <span>Mon&ndash;Fri &nbsp;8:00 AM &ndash; 5:00 PM</span>
    </div>

</div>

@include('shared.js-config', [
    'id' => 'js-config-chatbot-widget-1',
    'data' => [
        'v1' => e(asset('Images/aichatbotimg.jpg')),
    ],
])
<script src="{{ asset('js/pages/chatbot/widget-dynamic1.js') }}"></script>

</body>
</html>
