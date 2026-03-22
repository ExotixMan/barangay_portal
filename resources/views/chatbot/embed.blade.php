{{--
╔══════════════════════════════════════════════════════════════╗
║  InfoHulo Assistant — Modal Embed for BISIG                ║
║                                                              ║
║  USAGE — paste ONE line before </body> in your page:        ║
║                                                              ║
║      @include('chatbot.embed')                              ║
║                                                              ║
║  The existing #chatBtn in your FAB will open the assistant. ║
║  Or call:  window.InfoHuloAssistant.open() / toggle()        ║
╚══════════════════════════════════════════════════════════════╝
--}}

@once
{{-- CSRF token — required for all POST requests. Safe to include even if already in <head>. --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ══════════════════════════════════════════════════════
    INFOHULO ASSISTANT MODAL — matches your existing style
══════════════════════════════════════════════════════ */

/* Backdrop */
#hulobot-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(0,0,0,.55);
    animation: hbFadeIn .2s ease;
}
#hulobot-backdrop.open { display: block; }
@keyframes hbFadeIn { from{opacity:0} to{opacity:1} }

/* Modal box — centered, same proportions as your screenshot */
#hulobot-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(.96);
    z-index: 9999;
    width: 800px;
    max-width: calc(100vw - 32px);
    height: 580px;
    max-height: calc(100vh - 80px);
    border-radius: 12px;
    overflow: hidden;
    display: none;
    flex-direction: column;
    background: #fff;
    box-shadow: 0 24px 64px rgba(0,0,0,.22);
    font-family: 'Inter', sans-serif;
}
#hulobot-modal.open {
    display: flex;
    animation: hbSlideIn .25s ease forwards;
}
@keyframes hbSlideIn {
    from { opacity:0; transform: translate(-50%,-50%) scale(.96); }
    to   { opacity:1; transform: translate(-50%,-50%) scale(1);   }
}

/* ── Modal header — red bar like your screenshot ── */
.hbm-header {
    background: linear-gradient(135deg, #C62828, #d32f2f);
    color: #fff;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.hbm-header-title {
    font-size: 1rem;
    font-weight: 600;
    font-family: 'Poppins', 'Inter', sans-serif;
}
.hbm-close {
    margin-left: auto;
    background: none;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    width: 30px; height: 30px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
    opacity: .85;
}
.hbm-close:hover { background: rgba(255,255,255,.2); opacity: 1; }

/* ── Chat body — two-column layout like screenshot ── */
.hbm-body {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* Left sidebar — agent info strip */
.hbm-sidebar {
    width: 220px;
    flex-shrink: 0;
    border-right: 1px solid #e9ecef;
    background: #fafafa;
    display: flex;
    flex-direction: column;
    padding: 16px;
    gap: 12px;
}
.hbm-agent-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 12px;
}
.hbm-agent-avt {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg,#C62828,#d32f2f);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.hbm-agent-name  { font-weight: 600; font-size: .88rem; color: #1e293b; }
.hbm-agent-role  { font-size: .70rem; color: #9ca3af; }
.hbm-online-dot  { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; margin-left: auto; box-shadow: 0 0 0 2px #d1fae5; }

.hbm-sidebar-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: #9ca3af; padding: 0 2px; }

.hbm-quick-btn {
    display: block; width: 100%;
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 8px; padding: 8px 10px;
    font-size: .76rem; color: #374151;
    cursor: pointer; text-align: left;
    transition: border-color .15s, background .15s;
    font-family: 'Inter', sans-serif;
    line-height: 1.4;
}
.hbm-quick-btn:hover { border-color: #C62828; background: #fff5f5; color: #C62828; }

/* ── Right: chat panel ── */
.hbm-chat {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Messages */
.hbm-msgs {
    flex: 1; overflow-y: auto;
    padding: 14px 14px 8px;
    background: #f8f9fa;
    display: flex; flex-direction: column; gap: 10px;
}
.hbm-msgs::-webkit-scrollbar { width: 3px; }
.hbm-msgs::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 3px; }

.hbm-div {
    text-align: center; font-size: .62rem; color: #9ca3af;
    position: relative; flex-shrink: 0;
}
.hbm-div::before,.hbm-div::after {
    content:''; position:absolute; top:50%; width:38%; height:1px; background:#e5e7eb;
}
.hbm-div::before{left:0} .hbm-div::after{right:0}

.hbm-row { display:flex; align-items:flex-end; gap:7px; }
.hbm-row.bot  { justify-content:flex-start; }
.hbm-row.user { justify-content:flex-end; }

.hbm-avt {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg,#C62828,#d32f2f); color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.hbm-bbl {
    max-width: 80%; padding: 8px 12px; border-radius: 13px;
    font-size: .835rem; line-height: 1.55; word-break: break-word;
}
.hbm-row.bot  .hbm-bbl { background:#fff; color:#1e293b; border:1px solid #e9ecef; border-bottom-left-radius:3px; }
.hbm-row.user .hbm-bbl { background:#C62828; color:#fff; border-bottom-right-radius:3px; }

.hbm-meta { font-size:.60rem; color:#9ca3af; margin-top:2px; }
.hbm-row.bot  .hbm-meta { padding-left:35px; }
.hbm-row.user .hbm-meta { text-align:right; }
.hbm-src { display:inline-block; font-size:.56rem; padding:1px 6px; border-radius:20px; margin-left:3px; font-weight:600; }
.hbm-kb  { background:#e8f5e9; color:#2e7d32; }
.hbm-ai  { background:#ffebee; color:#b71c1c; }
.hbm-cf  { display:inline-block; font-size:.56rem; padding:1px 5px; border-radius:20px; background:#f3f4f6; color:#6b7280; margin-left:2px; }
.hbm-note { margin-top:6px; font-size:.66rem; color:#7f1d1d; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:5px 7px; }

/* Typing */
.hbm-typing { display:flex; gap:3px; padding:6px 10px; }
.hbm-td { width:5px; height:5px; border-radius:50%; background:#cbd5e1; animation:hbcBounce .9s infinite ease-in-out; }
.hbm-td:nth-child(2){animation-delay:.15s} .hbm-td:nth-child(3){animation-delay:.30s}
@keyframes hbcBounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-6px)}}

/* Chips — small */
.hbm-chips {
    padding: 4px 12px 5px;
    display: flex; gap: 4px; flex-wrap: wrap;
    background: #fff; border-top: 1px solid #e9ecef; flex-shrink: 0;
}
.hbm-chip {
    font-size: .65rem; padding: 3px 9px; border-radius: 20px;
    border: 1px solid #C62828; color: #C62828; background: #fff;
    cursor: pointer; transition: background .15s, color .15s;
    white-space: nowrap; font-family: 'Inter', sans-serif; line-height: 1.45;
}
.hbm-chip:hover { background: #C62828; color: #fff; }

/* Input */
.hbm-bar {
    padding: 10px 12px; border-top: 1px solid #e9ecef;
    background: #fff; display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0;
}
.hbm-txt {
    flex: 1; border: 1px solid #e9ecef; border-radius: 20px;
    padding: 7px 13px; font-size: .835rem; resize: none; outline: none;
    max-height: 72px; overflow-y: auto; font-family: 'Inter', sans-serif;
    line-height: 1.4; transition: border-color .2s;
}
.hbm-txt:focus { border-color: #C62828; }
.hbm-txt::placeholder { color: #adb5bd; }
.hbm-send {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg,#C62828,#d32f2f); border: none; color: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 14px; transition: opacity .2s, transform .15s;
}
.hbm-send:hover:not(:disabled) { transform: scale(1.08); }
.hbm-send:disabled { opacity: .4; cursor: not-allowed; }

/* Footer */
.hbm-foot {
    text-align: center; font-size: .58rem; color: #adb5bd;
    padding: 3px 12px 5px; background: #fff;
    border-top: 1px solid #e9ecef; flex-shrink: 0;
}

/* Mobile — full screen */
@media (max-width: 640px) {
    #hulobot-modal {
        width: 100%; max-width: 100%;
        height: 100%; max-height: 100%;
        top: 0; left: 0; transform: none;
        border-radius: 0;
    }
    #hulobot-modal.open { animation: hbSlideUp .25s ease; }
    @keyframes hbSlideUp { from{transform:translateY(30px);opacity:0} to{transform:translateY(0);opacity:1} }
    .hbm-sidebar { display: none; }
}
</style>
@endonce

{{-- ════ BACKDROP ════ --}}
<div id="hulobot-backdrop"></div>

{{-- ════ MODAL ════ --}}
<div id="hulobot-modal" role="dialog" aria-modal="true" aria-label="InfoHulo Assistant">

    {{-- Header --}}
    <div class="hbm-header">
        <i class="bi bi-person-badge-fill" style="font-size:1.1rem"></i>
        <span class="hbm-header-title">InfoHulo Assistant</span>
        <button class="hbm-close" id="hbm-close" title="Close">&times;</button>
    </div>

    {{-- Body --}}
    <div class="hbm-body">

        {{-- Left sidebar --}}
        <div class="hbm-sidebar">
            <div class="hbm-agent-card">
                <div class="hbm-agent-avt"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="hbm-agent-name">InfoHulo Assistant</div>
                    <div class="hbm-agent-role">Portal Assistant</div>
                </div>
                <span class="hbm-online-dot" title="Online"></span>
            </div>

            <div class="hbm-sidebar-label">Quick Links</div>

            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('How do I get a Barangay Clearance?')">
                Barangay Clearance
            </button>
            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('I want to request a Barangay Indigency certificate.')">
                Indigency Certificate
            </button>
            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('How do I request a Barangay Residency certificate?')">
                Residency Certificate
            </button>
            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('I want to submit an incident report.')">
                Incident Report
            </button>
            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('How can I track the status of my request?')">
                Track My Request
            </button>
            <button class="hbm-quick-btn" onclick="InfoHuloAssistant.ask('I need help or support with the portal.')">
                Portal Support
            </button>
        </div>

        {{-- Chat panel --}}
        <div class="hbm-chat">
            <div class="hbm-msgs" id="hbm-msgs">
                <div class="hbm-div">Today</div>
            </div>

            <div class="hbm-chips" id="hbm-chips"></div>

            <div class="hbm-bar">
                <textarea class="hbm-txt" id="hbm-input"
                          rows="1"
                          placeholder="Type your question..."
                          maxlength="500"></textarea>
                <button class="hbm-send" id="hbm-send" title="Send">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>

            <div class="hbm-foot">
                Powered by BISIG &mdash; Barangay Hulo e-Governance Portal
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

    /* ── Open / Close ──────────────────────────────────── */
    function open() {
        isOpen = true;
        backdrop.classList.add('open');
        modal.classList.add('open');
        input.focus();
        if (!ready) init();
    }
    function close() {
        isOpen = false;
        backdrop.classList.remove('open');
        modal.classList.remove('open');
    }
    function toggle() { isOpen ? close() : open(); }

    closeBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) close(); });

    /* ── Hook into your existing #chatBtn ──────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        const chatBtn = document.getElementById('chatBtn');
        if (chatBtn) {
            // Override whatever floating-actions.js does with chatBtn
            chatBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggle();
            });
        }
    });

    /* ── Public API — call from anywhere ───────────────── */
    const apiObject = { open, close, toggle,
        ask: function(text) {
            if (!isOpen) open();
            setTimeout(() => { input.value = text; send(); }, 100);
        }
    };
    // Keep legacy API name while exposing branded API.
    window.HuloBot = apiObject;
    window.InfoHuloAssistant = apiObject;

    /* ── Init session ──────────────────────────────────── */
    async function init() {
        ready = true;
        try {
            const r = await api('/chatbot/start', 'POST', {});
            sessionId = r.session_id;
        } catch { sessionId = uuid(); }
        welcome();
    }

    function welcome() {
        bot(
            'Hi there! I\'m <strong>InfoHulo Assistant</strong>, your Barangay Hulo Portal assistant.<br><br>' +
            'I can help you with:<br>' +
            '&bull; Requesting Barangay Indigency<br>' +
            '&bull; Requesting Barangay Clearance<br>' +
            '&bull; Requesting Barangay Residency Certificate<br>' +
            '&bull; Submitting an Incident Report<br><br>' +
            'Click an option on the left or type your question!',
            null, [
                'I want to request a Barangay Indigency certificate.',
                'How do I get a Barangay Clearance?',
                'I want to submit an incident report.',
                'How can I track the status of my request?',
            ], 'rule_based', null
        );
    }

    /* ── Send ──────────────────────────────────────────── */
    async function send() {
        const text = input.value.trim();
        if (!text || isBusy) return;

        addUser(text);
        input.value = ''; resize();
        clearChips(); busy(true); typing(true);

        try {
            const r = await api('/chatbot/message', 'POST', {
                message: text, session_id: sessionId || uuid()
            });
            typing(false);
            if (r.success) {
                const d = r.data;
                bot(d.answer, d.confidence, d.suggestions || [], d.source, d);
            } else {
                console.error('[InfoHuloAssistant] API returned error:', r);
                bot('Error: ' + (r.message || 'Server returned an error. Check storage/logs/laravel.log'), null, [], 'fallback', null);
            }
        } catch (err) {
            typing(false);
            console.error('[InfoHuloAssistant]', err);
            const e = err.message || '';
            let msg = 'Error: ' + (e || 'Unknown error');
            if (e.includes('419'))          msg = 'Session expired — please refresh the page.';
            else if (e.includes('404'))     msg = 'Route not found. Make sure chatbot routes are registered in web.php and run: php artisan route:clear';
            else if (e.includes('500'))     msg = 'Server error (500). Check storage/logs/laravel.log for details.';
            else if (e.includes('Failed to fetch') || e.includes('NetworkError') || e.includes('NETWORK')) msg = 'Cannot reach the server. Check your internet connection.';
            bot(msg, null, [], 'fallback', null);
        }
        busy(false);
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
    });
    input.addEventListener('input', resize);

    /* ── Render ────────────────────────────────────────── */
    function addUser(text) {
        msgs.insertAdjacentHTML('beforeend', `
            <div class="hbm-row user">
                <div>
                    <div class="hbm-bbl">${esc(text)}</div>
                    <div class="hbm-meta">${now()}</div>
                </div>
            </div>`);
        scroll();
    }

    function bot(text, conf, sug, source, d) {
        let src = '';
        let note = '';
        if (source === 'ai') {
            const p = (d && d.ai_provider) ? d.ai_provider.toUpperCase() : 'AI';
            src = `<span class="hbm-src hbm-ai">AI Reference (${p})</span>`;
            note = '<div class="hbm-note">AI response is for reference only. Please verify final details with Barangay staff.</div>';
        } else if (source === 'rule_based') {
            src = `<span class="hbm-src hbm-kb">KB Verified</span>`;
        }
        const cf = (conf && conf > 0 && source !== 'fallback')
            ? `<span class="hbm-cf">Confidence ${conf}%</span>` : '';

        msgs.insertAdjacentHTML('beforeend', `
            <div class="hbm-row bot">
                <div class="hbm-avt"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <div class="hbm-bbl">${text}</div>
                    ${note}
                    <div class="hbm-meta">${now()}${src}${cf}</div>
                </div>
            </div>`);
        if (sug && sug.length) renderChips(sug);
        scroll();
    }

    let typEl = null;
    function typing(show) {
        if (show) {
            typEl = document.createElement('div');
            typEl.className = 'hbm-row bot';
            typEl.innerHTML = `
                <div class="hbm-avt"><i class="bi bi-person-badge-fill"></i></div>
                <div class="hbm-bbl" style="padding:0;background:#fff;border:1px solid #e9ecef">
                    <div class="hbm-typing">
                        <div class="hbm-td"></div><div class="hbm-td"></div><div class="hbm-td"></div>
                    </div>
                </div>`;
            msgs.appendChild(typEl); scroll();
        } else { if (typEl) { typEl.remove(); typEl = null; } }
    }

    function renderChips(items) {
        chips.innerHTML = '';
        items.slice(0, 5).forEach(s => {
            const b = document.createElement('button');
            b.className = 'hbm-chip';
            b.textContent = s;
            b.addEventListener('click', () => { input.value = s; send(); });
            chips.appendChild(b);
        });
    }
    function clearChips() { chips.innerHTML = ''; }

    /* ── Utils ─────────────────────────────────────────── */
    function busy(v)  { isBusy = v; sendBtn.disabled = v; input.disabled = v; }
    function scroll() { requestAnimationFrame(() => { msgs.scrollTop = msgs.scrollHeight; }); }
    function resize() { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 72) + 'px'; }
    function now()    { return new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' }); }
    function esc(s)   { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    function uuid()   {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random()*16|0; return (c==='x'?r:(r&0x3|0x8)).toString(16);
        });
    }
    async function api(url, method, body) {
        const csrf = document.querySelector('meta[name="csrf-token"]');
        if (!csrf) console.warn('[InfoHuloAssistant] No CSRF meta tag found — add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout <head>');

        let res;
        try {
            res = await fetch(url, {
                method,
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  csrf ? csrf.content : '',
                    'Accept':        'application/json',
                },
                body: method !== 'GET' ? JSON.stringify(body) : undefined,
            });
        } catch (netErr) {
            throw new Error('NETWORK: ' + netErr.message);
        }

        if (!res.ok) {
            let m = 'HTTP ' + res.status;
            try {
                const j = await res.clone().json();
                m = j.message || m;
            } catch (_) {
                try {
                    const t = await res.text();
                    // grab first 200 chars of response body for debugging
                    m = 'HTTP ' + res.status + ': ' + t.replace(/<[^>]+>/g, ' ').trim().substring(0, 200);
                } catch (_2) {}
            }
            throw new Error(m);
        }
        return res.json();
    }
})();
</script>