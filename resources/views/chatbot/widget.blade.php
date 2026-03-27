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

    <style>
        /* ── Page shell ─────────────────────────────────────── */
        :root {
            --primary:      #d32f2f;
            --primary-lt:   #ffebee;
            --primary-dk:   #b71c1c;
            --grad:         linear-gradient(135deg,#d32f2f 0%,#b71c1c 100%);
            --border:       #e9ecef;
            --bg-page:      #f5f6fa;
            --radius:       16px;
            --shadow:       0 8px 32px rgba(0,0,0,.09);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg-page);
            color: #1e293b;
        }

        /* ── Outer page layout ───────────────────────────────── */
        .hb-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* ── Page header (above the widget) ─────────────────── */
        .hb-page-header {
            width: 100%;
            max-width: 500px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .hb-page-logo {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--grad);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 20px; flex-shrink: 0;
        }
        .hb-page-label      { font-size: .78rem; color: #6b7280; font-weight: 500; }
        .hb-page-title      { font-size: 1.05rem; font-weight: 700; color: #1e293b; line-height: 1.2; }
        .hb-page-badge {
            margin-left: auto;
            background: var(--primary-lt);
            color: var(--primary);
            font-size: .68rem; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
        }

        /* ════════════════════════════════════════════════════
           CHAT WIDGET CONTAINER
        ════════════════════════════════════════════════════ */
        .hb-widget {
            width: 100%;
            max-width: 500px;
            height: 620px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #fff;
        }

        /* ── Header ──────────────────────────────────────── */
        .hb-header {
            background: var(--grad);
            color: #fff;
            padding: 13px 16px;
            display: flex;
            align-items: center;
            gap: 11px;
            flex-shrink: 0;
        }
        .hb-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid rgba(255,255,255,.5);
            background: #fff;
        }
        .hb-avatar img,
        .hb-avatar-sm img,
        .hb-page-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hb-name    { font-weight: 700; font-size: .92rem; line-height: 1.2; }
        .hb-status  { font-size: .68rem; opacity: .85; display: flex; align-items: center; gap: 4px; margin-top: 1px; }
        .hb-dot     { width: 6px; height: 6px; border-radius: 50%; background: #a5d6a7; animation: hbPulse 1.8s infinite; }
        @keyframes hbPulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        .hb-head-actions { margin-left: auto; display: flex; gap: 6px; }
        .hb-icon-btn {
            background: rgba(255,255,255,.15); border: none; color: #fff;
            width: 30px; height: 30px; border-radius: 8px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; transition: background .2s;
        }
        .hb-icon-btn:hover { background: rgba(255,255,255,.28); }

        /* ── Messages ────────────────────────────────────── */
        .hb-messages {
            flex: 1; overflow-y: auto;
            padding: 14px 14px 10px;
            background: #f8f9fa;
            display: flex; flex-direction: column; gap: 10px;
        }
        .hb-messages::-webkit-scrollbar { width: 3px; }
        .hb-messages::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 3px; }

        /* Date divider */
        .hb-divider {
            text-align: center; font-size: .63rem; color: #9ca3af;
            position: relative; flex-shrink: 0;
        }
        .hb-divider::before, .hb-divider::after {
            content: ''; position: absolute; top: 50%;
            width: 38%; height: 1px; background: #e5e7eb;
        }
        .hb-divider::before { left: 0; }
        .hb-divider::after  { right: 0; }

        /* Rows */
        .hb-row { display: flex; align-items: flex-end; gap: 7px; }
        .hb-row.bot  { justify-content: flex-start; }
        .hb-row.user { justify-content: flex-end; }

        .hb-avatar-sm {
            width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
            overflow: hidden;
            border: 1px solid #ffd6d6;
            background: #fff;
        }

        .hb-bubble {
            max-width: 78%; padding: 8px 12px; border-radius: 13px;
            font-size: .835rem; line-height: 1.55; word-break: break-word;
        }
        .hb-row.bot  .hb-bubble { background: #fff; color: #1e293b; border: 1px solid var(--border); border-bottom-left-radius: 3px; }
        .hb-row.user .hb-bubble { background: var(--primary); color: #fff; border-bottom-right-radius: 3px; }

        .hb-meta { font-size: .62rem; color: #9ca3af; margin-top: 2px; }
        .hb-row.bot  .hb-meta { text-align: left;  padding-left: 33px; }
        .hb-row.user .hb-meta { text-align: right; }

        /* Source badges */
        .hb-badge { display: inline-block; font-size: .57rem; padding: 1px 6px; border-radius: 20px; margin-left: 4px; font-weight: 600; }
        .hb-kb    { background: #e8f5e9; color: #2e7d32; }
        .hb-ai    { background: var(--primary-lt); color: var(--primary-dk); }
        .hb-conf  { display: inline-block; font-size: .57rem; padding: 1px 5px; border-radius: 20px; background: #f3f4f6; color: #6b7280; margin-left: 3px; }
        .hb-note  { margin-top: 6px; font-size: .72rem; color: #7f1d1d; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 6px 8px; max-width: 78%; }

        /* ── Typing ───────────────────────────────────────── */
        .hb-typing { display: flex; gap: 4px; padding: 7px 11px; }
        .hb-dot-t  { width: 6px; height: 6px; border-radius: 50%; background: #cbd5e1; animation: hbBounce .9s infinite ease-in-out; }
        .hb-dot-t:nth-child(2) { animation-delay: .15s; }
        .hb-dot-t:nth-child(3) { animation-delay: .30s; }
        @keyframes hbBounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-7px)} }

        /* ── Suggestion chips — SMALL ────────────────────── */
        .hb-suggestions {
            padding: 5px 12px 6px;
            display: flex; gap: 4px; flex-wrap: wrap;
            background: #fff;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }
        .hb-chip {
            font-size: .68rem;       /* ← small */
            padding: 3px 9px;
            border-radius: 20px;
            border: 1px solid var(--primary);
            color: var(--primary);
            background: #fff;
            cursor: pointer;
            transition: background .15s, color .15s;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
            line-height: 1.45;
        }
        .hb-chip:hover { background: var(--primary); color: #fff; }

        /* ── Input bar ────────────────────────────────────── */
        .hb-input-bar {
            padding: 9px 12px;
            border-top: 1px solid var(--border);
            background: #fff;
            display: flex; gap: 8px; align-items: flex-end;
            flex-shrink: 0;
        }
        #hb-input {
            flex: 1; border: 1px solid var(--border); border-radius: 20px;
            padding: 7px 13px; font-size: .835rem;
            resize: none; outline: none;
            max-height: 80px; overflow-y: auto;
            font-family: 'Inter', sans-serif; line-height: 1.4;
            transition: border-color .2s; background: #fff; color: #1e293b;
        }
        #hb-input:focus { border-color: var(--primary); }
        #hb-input::placeholder { color: #adb5bd; }

        #hb-send {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            background: var(--grad); border: none; color: #fff;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 14px; transition: opacity .2s, transform .15s;
        }
        #hb-send:hover:not(:disabled) { transform: scale(1.08); }
        #hb-send:disabled { opacity: .4; cursor: not-allowed; }

        /* ── Powered-by footer ────────────────────────────── */
        .hb-footer {
            text-align: center; font-size: .60rem; color: #adb5bd;
            padding: 4px 12px 5px; background: #fff;
            border-top: 1px solid var(--border); flex-shrink: 0;
        }

        /* ── Below-widget info row ────────────────────────── */
        .hb-info-row {
            width: 100%; max-width: 500px;
            margin-top: 12px;
            display: flex; align-items: center; justify-content: space-between;
            font-size: .72rem; color: #9ca3af;
        }
        .hb-info-row a { color: var(--primary); text-decoration: none; }
        .hb-info-row a:hover { text-decoration: underline; }

        /* ── Responsive ───────────────────────────────────── */
        @media (max-width: 540px) {
            .hb-page   { padding: 12px 10px; justify-content: flex-start; }
            .hb-widget { height: calc(100svh - 120px); border-radius: 12px; }
        }
    </style>
</head>
<body>

<div class="hb-page">

    {{-- Page header above widget --}}
    <div class="hb-page-header">
        <div class="hb-page-logo"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
        <div>
            <div class="hb-page-label">Barangay Hulong Duhat, Malabon City</div>
            <div class="hb-page-title">BISIG Portal Assistant</div>
        </div>
        <span class="hb-page-badge">Online</span>
    </div>

    {{-- ═══════ CHAT WIDGET ═══════ --}}
    <div class="hb-widget" id="hulobot">

        {{-- Header --}}
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

        {{-- Messages --}}
        <div class="hb-messages" id="hb-messages">
            <div class="hb-divider">Today</div>
        </div>

        {{-- Suggestion chips --}}
        <div class="hb-suggestions" id="hb-suggestions"></div>

        {{-- Input --}}
        <div class="hb-input-bar">
            <textarea id="hb-input" rows="1"
                      placeholder="Type your question..."
                      maxlength="500"></textarea>
            <button id="hb-send" title="Send">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>

        {{-- Footer --}}
        <div class="hb-footer">
            Powered by BISIG &mdash; Barangay Hulo e-Governance Portal
        </div>

    </div>{{-- /.hb-widget --}}

    {{-- Info row below widget --}}
    <div class="hb-info-row">
        <span>Need more help? <a href="mailto:support@barangayhulo.portal">support@barangayhulo.portal</a></span>
        <span>Mon&ndash;Fri &nbsp;8:00 AM &ndash; 5:00 PM</span>
    </div>

</div>{{-- /.hb-page --}}

<script>
(function () {
    'use strict';

    let sessionId = null;
    let isBusy    = false;

    const msgs    = document.getElementById('hb-messages');
    const suggs   = document.getElementById('hb-suggestions');
    const input   = document.getElementById('hb-input');
    const sendBtn = document.getElementById('hb-send');
    const clearBtn= document.getElementById('hb-clear');

    /* ── Init session ─────────────────────────────────── */
    (async function init() {
        try {
            const r = await api('/chatbot/start', 'POST', {});
            sessionId = r.session_id;
        } catch { sessionId = uuid(); }
        welcome();
    })();

    function welcome() {
        bot(
            'Hi there! I\'m <strong>InfoHulo Assistant</strong>, your Barangay Hulo Portal assistant.<br><br>' +
            'I can help you with:<br>' +
            '&bull; Requesting Barangay Indigency<br>' +
            '&bull; Requesting Barangay Clearance<br>' +
            '&bull; Requesting Barangay Residency Certificate<br>' +
            '&bull; Submitting an Incident Report<br><br>' +
            'Click an option below or type your question!',
            null, [
                'I want to request a Barangay Indigency certificate.',
                'How do I get a Barangay Clearance?',
                'I want to submit an incident report.',
                'How can I track the status of my request?',
                'I need help or support with the portal.',
            ], 'rule_based', null
        );
    }

    /* ── Clear ────────────────────────────────────────── */
    clearBtn.addEventListener('click', () => {
        msgs.innerHTML  = '<div class="hb-divider">Today</div>';
        suggs.innerHTML = '';
        sessionId       = uuid();
        welcome();
    });

    /* ── Send ─────────────────────────────────────────── */
    async function send() {
        const text = input.value.trim();
        if (text.length === 1) {
            bot('Please type at least 2 characters so I can understand your request.', null, [], 'guard', null);
            return;
        }
        if (!text || isBusy) return;

        user(text);
        input.value = ''; resize();
        clearSuggs(); busy(true); typing(true);

        try {
            const r = await api('/chatbot/message', 'POST', {
                message: text, session_id: sessionId || uuid()
            });
            typing(false);
            if (r.success) {
                const d = r.data;
                bot(d.answer, d.confidence, d.suggestions || [], d.source, d);
            } else {
                bot(r.message || 'Something went wrong. Please try again.', null, [], 'fallback', null);
            }
        } catch (err) {
            typing(false);
            let msg = 'Something went wrong. Please try again.';
            const e = err.message || '';
            if (e.includes('419'))          msg = 'Session expired — please refresh the page.';
            else if (e.includes('404'))     msg = 'Route not found (404). Check routes.';
            else if (e.includes('500'))     msg = 'Server error. Check storage/logs/laravel.log.';
            else if (e.includes('NETWORK')) msg = 'Cannot reach the server.';
            bot(msg, null, [], 'fallback', null);
        }
        busy(false);
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); } });
    input.addEventListener('input', resize);

    /* ── Render ───────────────────────────────────────── */
    function user(text) {
        msgs.insertAdjacentHTML('beforeend', `
            <div class="hb-row user">
                <div>
                    <div class="hb-bubble">${esc(text)}</div>
                    <div class="hb-meta">${now()}</div>
                </div>
            </div>`);
        scroll();
    }

    function bot(text, conf, sug, source, d) {
        let src = '';
        let note = '';
        if (source === 'ai') {
            const p = (d && d.ai_provider) ? d.ai_provider.toUpperCase() : 'AI';
            src = `<span class="hb-badge hb-ai">AI Reference (${p})</span>`;
            note = '<div class="hb-note">AI response is for reference only. Please verify final details with Barangay staff.</div>';
        } else if (source === 'rule_based') {
            src = `<span class="hb-badge hb-kb">KB Verified</span>`;
        }
        const conf_ = (conf && conf > 0 && source !== 'fallback')
            ? `<span class="hb-conf">Confidence ${conf}%</span>` : '';

        msgs.insertAdjacentHTML('beforeend', `
            <div class="hb-row bot">
                <div class="hb-avatar-sm"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div>
                    <div class="hb-bubble">${text}</div>
                    ${note}
                    <div class="hb-meta">${now()}${src}${conf_}</div>
                </div>
            </div>`);
        if (sug && sug.length) chips(sug);
        scroll();
    }

    let typEl = null;
    function typing(show) {
        if (show) {
            typEl = document.createElement('div');
            typEl.className = 'hb-row bot';
            typEl.innerHTML = `
                <div class="hb-avatar-sm"><img src="{{ asset('Images/aichatbotimg.jpg') }}" alt="InfoHulo Assistant"></div>
                <div class="hb-bubble" style="padding:0;background:#fff;border:1px solid var(--border)">
                    <div class="hb-typing">
                        <div class="hb-dot-t"></div><div class="hb-dot-t"></div><div class="hb-dot-t"></div>
                    </div>
                </div>`;
            msgs.appendChild(typEl); scroll();
        } else {
            if (typEl) { typEl.remove(); typEl = null; }
        }
    }

    function chips(items) {
        suggs.innerHTML = '';
        items.slice(0, 5).forEach(s => {
            const b = document.createElement('button');
            b.className   = 'hb-chip';
            b.textContent = s;
            b.addEventListener('click', () => { input.value = s; send(); });
            suggs.appendChild(b);
        });
    }
    function clearSuggs() { suggs.innerHTML = ''; }

    /* ── Helpers ──────────────────────────────────────── */
    function busy(v)  { isBusy = v; sendBtn.disabled = v; input.disabled = v; }
    function scroll() { requestAnimationFrame(() => { msgs.scrollTop = msgs.scrollHeight; }); }
    function resize() { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 80) + 'px'; }
    function now()    { return new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }); }
    function esc(s)   { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    function uuid()   {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random()*16|0; return (c==='x'?r:(r&0x3|0x8)).toString(16);
        });
    }

    async function api(url, method, body) {
        const csrf = document.querySelector('meta[name="csrf-token"]');
        const res  = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.content : '',
                'Accept':       'application/json',
            },
            body: method !== 'GET' ? JSON.stringify(body) : undefined,
        });
        if (!res.ok) {
            let m = `HTTP ${res.status}`;
            try { const j = await res.clone().json(); m = j.message || m; } catch(_) {}
            throw new Error(m);
        }
        return res.json();
    }

})();
</script>

</body>
</html>