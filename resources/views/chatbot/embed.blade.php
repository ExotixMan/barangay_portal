{{--
  InfoHulo Assistant — Modern Minimal Modal Embed            
                                                              
  Usage: paste before </body>                                
      @include('chatbot.embed')                              
                                                              
  Existing #chatBtn will open this assistant.                 
  Public API: window.InfoHuloAssistant.open()/toggle()/ask()  

--}}

@once
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --hulo-primary: #c62828;
    --hulo-primary-dark: #991b1b;
    --hulo-primary-soft: #fff1f2;
    --hulo-bg: #f8fafc;
    --hulo-card: #ffffff;
    --hulo-text: #0f172a;
    --hulo-muted: #64748b;
    --hulo-line: #e2e8f0;
    --hulo-success: #16a34a;
    --hulo-warning: #d97706;
    --hulo-shadow: 0 24px 80px rgba(15, 23, 42, .24);
}

/* Backdrop */
#hulobot-backdrop {
    position: fixed;
    inset: 0;
    display: none;
    z-index: 2147483000;
    background:
        radial-gradient(circle at 82% 18%, rgba(198, 40, 40, .20), transparent 32%),
        rgba(15, 23, 42, .54);
    backdrop-filter: blur(4px);
    animation: hbmBackdropIn .18s ease;
}
#hulobot-backdrop.open { display: block; }

@keyframes hbmBackdropIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Prevent background scrolling/actions while open */
body.hulobot-open { overflow: hidden; }
body.hulobot-open #fabMain,
body.hulobot-open #speedDial,
body.hulobot-open #translateBtn,
body.hulobot-open #darkModeBtn,
body.hulobot-open #backToTop {
    pointer-events: none !important;
}

/* Main modal */
#hulobot-modal {
    position: fixed;
    right: 66px;
    bottom: 10px;
    z-index: 2147483001;
    display: none;
    width: 420px;
    max-width: calc(100vw - 28px);
    height: 680px;
    max-height: calc(100vh - 48px);
    overflow: hidden;
    border: 1px solid rgba(226, 232, 240, .9);
    border-radius: 28px;
    background: var(--hulo-card);
    box-shadow: var(--hulo-shadow);
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    transform: translateY(18px) scale(.98);
    opacity: 0;
}
#hulobot-modal.open {
    display: flex;
    flex-direction: column;
    animation: hbmPanelIn .24s cubic-bezier(.2,.8,.2,1) forwards;
}
@keyframes hbmPanelIn {
    to { transform: translateY(0) scale(1); opacity: 1; }
}

/* Header */
.hbm-header {
    position: relative;
    flex-shrink: 0;
    padding: 18px 18px 16px;
    color: #fff;
    background:
        radial-gradient(circle at 18% 16%, rgba(255,255,255,.24), transparent 28%),
        linear-gradient(135deg, #dc2626 0%, #b91c1c 48%, #7f1d1d 100%);
}
.hbm-header-top {
    display: flex;
    align-items: center;
    gap: 12px;
}
.hbm-logo-ring {
    width: 46px;
    height: 46px;
    display: grid;
    place-items: center;
    border-radius: 16px;
    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .28);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.22);
}
.hbm-logo-icon {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    object-fit: cover;
    background: #fff;
}
.hbm-title-wrap { min-width: 0; }
.hbm-header-title {
    margin: 0;
    font-size: 1.03rem;
    font-weight: 800;
    letter-spacing: -.015em;
    line-height: 1.15;
}
.hbm-header-subtitle {
    margin-top: 3px;
    font-size: .75rem;
    color: rgba(255,255,255,.82);
    line-height: 1.35;
}
.hbm-close {
    margin-left: auto;
    width: 38px;
    height: 38px;
    display: grid;
    place-items: center;
    border: 0;
    border-radius: 14px;
    color: #fff;
    background: rgba(255,255,255,.13);
    cursor: pointer;
    transition: transform .16s ease, background .16s ease;
}
.hbm-close:hover {
    background: rgba(255,255,255,.22);
    transform: translateY(-1px);
}
.hbm-status-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
}
.hbm-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: .68rem;
    font-weight: 700;
    color: rgba(255,255,255,.94);
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.18);
}
.hbm-pill i { font-size: .72rem; }

/* Body */
.hbm-chat {
    min-height: 0;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--hulo-bg);
}
.hbm-msgs {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    scroll-behavior: smooth;
}
.hbm-msgs::-webkit-scrollbar { width: 5px; }
.hbm-msgs::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 999px;
}
.hbm-date {
    align-self: center;
    padding: 4px 10px;
    border-radius: 999px;
    color: #94a3b8;
    background: rgba(255,255,255,.7);
    border: 1px solid #e2e8f0;
    font-size: .66rem;
    font-weight: 700;
}

/* Welcome card */
.hbm-welcome {
    background: #fff;
    border: 1px solid var(--hulo-line);
    border-radius: 22px;
    padding: 14px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
}
.hbm-welcome-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 6px;
    color: var(--hulo-text);
    font-size: .93rem;
    font-weight: 800;
}
.hbm-welcome-title i { color: var(--hulo-primary); }
.hbm-welcome-text {
    margin: 0;
    color: var(--hulo-muted);
    font-size: .78rem;
    line-height: 1.55;
}
.hbm-quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 12px;
}
.hbm-quick {
    min-height: 58px;
    text-align: left;
    border: 1px solid #fee2e2;
    border-radius: 16px;
    background: linear-gradient(180deg, #fff, #fff7f7);
    color: #7f1d1d;
    padding: 10px;
    cursor: pointer;
    transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
}
.hbm-quick:hover {
    transform: translateY(-1px);
    border-color: #fecaca;
    box-shadow: 0 10px 22px rgba(198,40,40,.10);
}
.hbm-quick i {
    display: block;
    margin-bottom: 5px;
    color: var(--hulo-primary);
}
.hbm-quick span {
    display: block;
    font-size: .72rem;
    font-weight: 800;
    line-height: 1.25;
}

/* Message rows */
.hbm-row {
    display: flex;
    gap: 9px;
    align-items: flex-end;
}
.hbm-row.bot { justify-content: flex-start; }
.hbm-row.user { justify-content: flex-end; }
.hbm-avt {
    width: 31px;
    height: 31px;
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #fecaca;
    box-shadow: 0 4px 14px rgba(198,40,40,.12);
}
.hbm-avt img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hbm-message-wrap { max-width: min(82%, 310px); }
.hbm-bbl {
    padding: 10px 13px;
    border-radius: 18px;
    font-size: .84rem;
    line-height: 1.55;
    word-break: break-word;
}
.hbm-row.bot .hbm-bbl {
    color: #172033;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 6px;
    box-shadow: 0 8px 22px rgba(15,23,42,.06);
}
.hbm-row.user .hbm-bbl {
    color: #fff;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border-bottom-right-radius: 6px;
    box-shadow: 0 10px 24px rgba(198,40,40,.22);
}
.hbm-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #94a3b8;
    font-size: .61rem;
    font-weight: 600;
}
.hbm-row.user .hbm-meta { justify-content: flex-end; }

.hbm-src,
.hbm-cf {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 7px;
    border-radius: 999px;
    font-size: .57rem;
    font-weight: 800;
}
.hbm-src.database { color: #166534; background: #dcfce7; }
.hbm-src.rule_based { color: #1d4ed8; background: #dbeafe; }
.hbm-src.ai { color: #9f1239; background: #ffe4e6; }
.hbm-src.guard { color: #92400e; background: #fef3c7; }
.hbm-src.fallback { color: #475569; background: #e2e8f0; }
.hbm-cf { color: #64748b; background: #f1f5f9; }

.hbm-note {
    margin-top: 7px;
    padding: 7px 9px;
    border-radius: 12px;
    color: #7f1d1d;
    background: #fff1f2;
    border: 1px solid #fecdd3;
    font-size: .68rem;
    line-height: 1.45;
}

/* Typing */
.hbm-typing-bbl {
    padding: 10px 12px !important;
}
.hbm-typing {
    display: flex;
    gap: 4px;
}
.hbm-dot {
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: #cbd5e1;
    animation: hbmBounce .9s infinite ease-in-out;
}
.hbm-dot:nth-child(2) { animation-delay: .14s; }
.hbm-dot:nth-child(3) { animation-delay: .28s; }
@keyframes hbmBounce {
    0%, 80%, 100% { transform: translateY(0); opacity: .65; }
    40% { transform: translateY(-5px); opacity: 1; }
}

/* Suggestions */
.hbm-chips {
    padding: 8px 12px 10px;
    display: flex;
    gap: 8px;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    background: #fff;
    border-top: 1px solid #e9ecef;
    flex-shrink: 0;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x proximity;
}

.hbm-chips::-webkit-scrollbar {
    height: 5px;
}

.hbm-chips::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 999px;
}

.hbm-chip {
    flex: 0 0 auto;
    font-size: .72rem;
    padding: 7px 12px;
    border-radius: 999px;
    border: 1px solid rgba(198, 40, 40, .22);
    color: #991b1b;
    background: #fff;
    cursor: pointer;
    transition: background .15s, color .15s, border-color .15s, transform .15s;
    white-space: nowrap;
    font-family: 'Inter', sans-serif;
    line-height: 1.3;
    scroll-snap-align: start;
}

.hbm-chip:hover {
    background: #C62828;
    color: #fff;
    border-color: #C62828;
    transform: translateY(-1px);
}

/* Composer */
.hbm-composer {
    flex-shrink: 0;
    padding: 12px;
    background: #fff;
    border-top: 1px solid var(--hulo-line);
}
.hbm-input-wrap {
    display: flex;
    align-items: flex-end;
    gap: 9px;
    padding: 8px 8px 8px 13px;
    border-radius: 22px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
}
.hbm-input-wrap:focus-within {
    border-color: #fca5a5;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, .12);
}
.hbm-txt {
    flex: 1;
    min-height: 24px;
    max-height: 96px;
    padding: 7px 0;
    border: 0;
    outline: 0;
    resize: none;
    background: transparent;
    color: var(--hulo-text);
    font: 500 .85rem/1.45 'Inter', sans-serif;
}
.hbm-txt::placeholder { color: #94a3b8; }
.hbm-send {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border: 0;
    border-radius: 15px;
    color: #fff;
    background: linear-gradient(135deg, #dc2626, #991b1b);
    cursor: pointer;
    transition: transform .16s ease, opacity .16s ease;
}
.hbm-send:hover:not(:disabled) { transform: translateY(-1px) scale(1.03); }
.hbm-send:disabled { opacity: .45; cursor: not-allowed; }
.hbm-foot {
    margin-top: 8px;
    display: flex;
    justify-content: center;
    gap: 5px;
    color: #94a3b8;
    font-size: .65rem;
    font-weight: 600;
}

/* Mobile */
@media (max-width: 640px) {
    #hulobot-modal {
        inset: 0;
        width: 100%;
        max-width: 100%;
        height: 100dvh;
        max-height: 100dvh;
        border-radius: 0;
        border: 0;
    }
    #hulobot-modal.open { animation: hbmMobileIn .22s ease forwards; }
    @keyframes hbmMobileIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .hbm-header {
        padding-top: calc(14px + env(safe-area-inset-top));
        border-radius: 0 0 22px 22px;
    }
    .hbm-msgs { padding: 14px 12px; }
    .hbm-quick-grid { grid-template-columns: 1fr; }
    .hbm-message-wrap { max-width: 86%; }
    .hbm-composer { padding-bottom: calc(10px + env(safe-area-inset-bottom)); }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
        scroll-behavior: auto !important;
    }
}
</style>
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

<script>
(function () {
    'use strict';

    const backdrop = document.getElementById('hulobot-backdrop');
    const modal    = document.getElementById('hulobot-modal');
    const closeBtn = document.getElementById('hbm-close');
    const msgs     = document.getElementById('hbm-msgs');
    const chips    = document.getElementById('hbm-chips');
    const input    = document.getElementById('hbm-input');
    const sendBtn  = document.getElementById('hbm-send');

    let sessionId = null;
    let isBusy    = false;
    let isOpen    = false;
    let ready     = false;
    let typEl     = null;

    const quickActions = [
        { icon: 'bi-file-earmark-check', label: 'Barangay Clearance', text: 'How do I get a Barangay Clearance?' },
        { icon: 'bi-heart-pulse', label: 'Indigency Certificate', text: 'How do I request an Indigency Certificate?' },
        { icon: 'bi-house-check', label: 'Residency Certificate', text: 'How do I request a Residency Certificate?' },
        { icon: 'bi-search', label: 'Track Request', text: 'How can I track the status of my request?' },
        { icon: 'bi-megaphone', label: 'Announcements', text: 'Latest announcements' },
        { icon: 'bi-calendar-event', label: 'Events', text: 'Upcoming events' }
    ];

    function open() {
        isOpen = true;
        document.body.classList.add('hulobot-open');
        backdrop.classList.add('open');
        modal.classList.add('open');

        setTimeout(() => input.focus(), 80);

        if (!ready) {
            showLoading();
            init();
        }
    }

    function close() {
        isOpen = false;
        document.body.classList.remove('hulobot-open');
        backdrop.classList.remove('open');
        modal.classList.remove('open');
    }

    function toggle() {
        isOpen ? close() : open();
    }

    closeBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen) close();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const chatBtn = document.getElementById('chatBtn');

        if (chatBtn) {
            chatBtn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const speedDial = document.getElementById('speedDial');
                const fabMain = document.getElementById('fabMain');

                if (speedDial) speedDial.classList.remove('active');
                if (fabMain) {
                    fabMain.classList.remove('active');
                    fabMain.innerHTML = '<i class="fas fa-gear"></i>';
                }

                toggle();
            });
        }
    });

    const apiObject = {
        open,
        close,
        toggle,
        ask: function (text) {
            if (!isOpen) open();
            setTimeout(function () {
                input.value = text;
                send();
            }, 120);
        }
    };

    window.HuloBot = apiObject;
    window.InfoHuloAssistant = apiObject;

    async function init() {
        ready = true;
        showLoading();

        try {
            const response = await api('/chatbot/start', 'POST', {});
            sessionId = response.session_id;
        } catch (error) {
            console.warn('[InfoHuloAssistant] Session fallback used:', error);
            sessionId = uuid();
        }

        welcome();
    }

    function welcome() {
        clearLoading();

        const card = document.createElement('div');
        card.className = 'hbm-welcome';
        card.innerHTML = `
            <h3 class="hbm-welcome-title"><i class="bi bi-stars"></i> Welcome, Hulowenyo!</h3>
            <p class="hbm-welcome-text">
                I can help with barangay services, live announcements, upcoming events,
                project updates, and request tracking using your Reference Number.
            </p>
            <div class="hbm-quick-grid">
                ${quickActions.map(action => `
                    <button type="button" class="hbm-quick" data-ask="${escAttr(action.text)}">
                        <i class="bi ${action.icon}"></i>
                        <span>${esc(action.label)}</span>
                    </button>
                `).join('')}
            </div>
        `;

        msgs.appendChild(card);

        card.querySelectorAll('.hbm-quick').forEach(function (button) {
            button.addEventListener('click', function () {
                input.value = button.getAttribute('data-ask') || '';
                send();
            });
        });

        renderChips([
            'Latest announcements',
            'Upcoming events',
            'Barangay projects',
            'Track my request'
        ]);

        scroll();
    }

    async function send() {
        const text = input.value.trim();

        if (text.length === 1) {
            bot('Please type at least 2 characters so I can understand your request.', null, [], 'guard', null);
            return;
        }

        if (!text || isBusy) return;

        addUser(text);
        input.value = '';
        resize();
        clearChips();
        busy(true);
        typing(true);

        try {
            const response = await api('/chatbot/message', 'POST', {
                message: text,
                session_id: sessionId || uuid()
            });

            typing(false);

            if (response.success) {
                const data = response.data || {};
                bot(data.answer || 'No answer returned.', data.confidence, data.suggestions || [], data.source, data);
            } else {
                console.error('[InfoHuloAssistant] API returned error:', response);
                bot('Error: ' + (response.message || 'Server returned an error. Check storage/logs/laravel.log'), null, [], 'fallback', null);
            }
        } catch (error) {
            typing(false);
            console.error('[InfoHuloAssistant]', error);

            const message = formatError(error.message || '');
            bot(message, null, [], 'fallback', null);
        }

        busy(false);
    }

    sendBtn.addEventListener('click', send);

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            send();
        }
    });

    input.addEventListener('input', resize);

    function addUser(text) {
        msgs.insertAdjacentHTML('beforeend', `
            <div class="hbm-row user">
                <div class="hbm-message-wrap">
                    <div class="hbm-bbl">${esc(text)}</div>
                    <div class="hbm-meta">${now()}</div>
                </div>
            </div>
        `);

        scroll();
    }

    function bot(text, confidence, suggestions, source, data) {
        const safeSource = source || 'fallback';
        const meta = sourceMeta(safeSource, data);
        const confidenceBadge = confidence && confidence > 0 && safeSource !== 'fallback'
            ? `<span class="hbm-cf">${Number(confidence)}%</span>`
            : '';

        const note = safeSource === 'ai'
            ? '<div class="hbm-note"><i class="bi bi-exclamation-triangle"></i> AI response is for reference only. Please verify final details with Barangay staff.</div>'
            : '';

        msgs.insertAdjacentHTML('beforeend', `
            <div class="hbm-row bot">
                <div class="hbm-avt"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div class="hbm-message-wrap">
                    <div class="hbm-bbl">${text}</div>
                    ${note}
                    <div class="hbm-meta">
                        <span>${now()}</span>
                        <span class="hbm-src ${escAttr(meta.key)}"><i class="bi ${meta.icon}"></i>${meta.label}</span>
                        ${confidenceBadge}
                    </div>
                </div>
            </div>
        `);

        if (suggestions && suggestions.length) renderChips(suggestions);
        scroll();
    }

    function sourceMeta(source, data) {
        if (source === 'database') {
            return { key: 'database', icon: 'bi-database-check', label: 'Live DB' };
        }

        if (source === 'rule_based') {
            return { key: 'rule_based', icon: 'bi-patch-check', label: 'KB Verified' };
        }

        if (source === 'ai') {
            const provider = data && data.ai_provider ? String(data.ai_provider).toUpperCase() : 'AI';
            return { key: 'ai', icon: 'bi-cpu', label: provider };
        }

        if (source === 'guard') {
            return { key: 'guard', icon: 'bi-shield-lock', label: 'Protected' };
        }

        return { key: 'fallback', icon: 'bi-info-circle', label: 'Guide' };
    }

    function typing(show) {
        if (show) {
            if (typEl) return;

            typEl = document.createElement('div');
            typEl.className = 'hbm-row bot';
            typEl.innerHTML = `
                <div class="hbm-avt"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div class="hbm-message-wrap">
                    <div class="hbm-bbl hbm-typing-bbl">
                        <div class="hbm-typing">
                            <span class="hbm-dot"></span>
                            <span class="hbm-dot"></span>
                            <span class="hbm-dot"></span>
                        </div>
                    </div>
                </div>
            `;

            msgs.appendChild(typEl);
            scroll();
            return;
        }

        if (typEl) {
            typEl.remove();
            typEl = null;
        }
    }

    function renderChips(items) {
        chips.innerHTML = '';

        items.slice(0, 6).forEach(function (suggestion) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'hbm-chip';
            button.innerHTML = `<i class="bi bi-plus-circle"></i>${esc(suggestion)}`;
            button.addEventListener('click', function () {
                input.value = suggestion;
                send();
            });
            chips.appendChild(button);
        });
    }

    function clearChips() {
        chips.innerHTML = '';
    }

    function showLoading() {
        if (document.getElementById('hbm-loading-row')) return;

        msgs.insertAdjacentHTML('beforeend', `
            <div class="hbm-row bot" id="hbm-loading-row">
                <div class="hbm-avt"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div class="hbm-message-wrap">
                    <div class="hbm-bbl">Loading assistant...</div>
                </div>
            </div>
        `);

        scroll();
    }

    function clearLoading() {
        const loading = document.getElementById('hbm-loading-row');
        if (loading) loading.remove();
    }

    function busy(value) {
        isBusy = value;
        sendBtn.disabled = value;
        input.disabled = value;
    }

    function scroll() {
        requestAnimationFrame(function () {
            msgs.scrollTop = msgs.scrollHeight;
        });
    }

    function resize() {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 96) + 'px';
    }

    function now() {
        return new Date().toLocaleTimeString('en-PH', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function esc(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escAttr(value) {
        return esc(value).replace(/`/g, '&#096;');
    }

    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (character) {
            const random = Math.random() * 16 | 0;
            const value = character === 'x' ? random : (random & 0x3 | 0x8);
            return value.toString(16);
        });
    }

    function formatError(error) {
        let message = 'Error: ' + (error || 'Unknown error');

        if (error.includes('419')) {
            message = 'Session expired. Please refresh the page and try again.';
        } else if (error.includes('404')) {
            message = 'Route not found. Make sure chatbot routes are registered in web.php and run: php artisan route:clear';
        } else if (error.includes('500')) {
            message = 'Server error. Check storage/logs/laravel.log for details.';
        } else if (error.includes('Failed to fetch') || error.includes('NetworkError') || error.includes('NETWORK')) {
            message = 'Cannot reach the server. Check your internet connection.';
        }

        return message;
    }

    async function api(url, method, body) {
        const csrf = document.querySelector('meta[name="csrf-token"]');

        if (!csrf) {
            console.warn('[InfoHuloAssistant] No CSRF meta tag found.');
        }

        let response;

        try {
            response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf ? csrf.content : '',
                    'Accept': 'application/json'
                },
                body: method !== 'GET' ? JSON.stringify(body) : undefined
            });
        } catch (networkError) {
            throw new Error('NETWORK: ' + networkError.message);
        }

        if (!response.ok) {
            let message = 'HTTP ' + response.status;

            try {
                const json = await response.clone().json();
                message = json.message || message;
            } catch (_) {
                try {
                    const text = await response.text();
                    message = 'HTTP ' + response.status + ': ' + text.replace(/<[^>]+>/g, ' ').trim().substring(0, 200);
                } catch (_ignored) {}
            }

            throw new Error(message);
        }

        return response.json();
    }
})();
</script>
