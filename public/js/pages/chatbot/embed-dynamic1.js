const __cfg_chatbot_embed_1 = JSON.parse(document.getElementById('js-config-chatbot-embed-1')?.dataset.jsConfig || '{}');

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
                <div class="hbm-avt"><img src="${__cfg_chatbot_embed_1.v1}" alt="InfoHulo Assistant"></div>
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
                <div class="hbm-avt"><img src="${__cfg_chatbot_embed_1.v1}" alt="InfoHulo Assistant"></div>
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
            button.innerHTML = `<i class="bi bi-plus-circle chatbot-quick-chat-icon"></i>${esc(suggestion)}`;
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
                <div class="hbm-avt"><img src="${__cfg_chatbot_embed_1.v1}" alt="InfoHulo Assistant"></div>
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
