const __cfg_chatbot_widget_1 = JSON.parse(document.getElementById('js-config-chatbot-widget-1')?.dataset.jsConfig || '{}');

(function () {
    'use strict';

    let sessionId = null;
    let isBusy    = false;

    const msgs    = document.getElementById('hb-messages');
    const suggs   = document.getElementById('hb-suggestions');
    const input   = document.getElementById('hb-input');
    const sendBtn = document.getElementById('hb-send');
    const clearBtn= document.getElementById('hb-clear');

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

    clearBtn.addEventListener('click', () => {
        msgs.innerHTML  = '<div class="hb-divider">Today</div>';
        suggs.innerHTML = '';
        sessionId       = uuid();
        welcome();
    });

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
                <div class="hb-avatar-sm"><img src="${__cfg_chatbot_widget_1.v1}" alt="InfoHulo Assistant"></div>
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
                <div class="hb-avatar-sm"><img src="${__cfg_chatbot_widget_1.v1}" alt="InfoHulo Assistant"></div>
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
