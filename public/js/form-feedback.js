(function () {
    'use strict';
    if (window.PortalFeedback) return;
    function dialog(message, confirmation = false) {
        return new Promise(resolve => {
            const previous = document.activeElement;
            const box = document.createElement('dialog');
            box.className = 'portal-feedback-dialog';
            const title = document.createElement('h2');
            title.id = `portal-dialog-title-${++sequence}`;
            title.textContent = confirmation ? 'Please confirm' : 'Please check';
            box.setAttribute('aria-labelledby', title.id);
            const text = document.createElement('p');
            text.textContent = String(message);
            const actions = document.createElement('div');
            actions.className = 'portal-feedback-actions';
            function close(value) { box.close(); box.remove(); previous?.focus(); resolve(value); }
            if (confirmation) {
                const cancel = document.createElement('button');
                cancel.type = 'button'; cancel.textContent = 'Cancel';
                cancel.addEventListener('click', () => close(false)); actions.append(cancel);
            }
            const ok = document.createElement('button');
            ok.type = 'button'; ok.className = 'portal-feedback-primary';
            ok.textContent = confirmation ? 'Continue' : 'Got it';
            ok.addEventListener('click', () => close(true)); actions.append(ok);
            box.append(title, text, actions);
            box.addEventListener('cancel', event => { event.preventDefault(); close(false); });
            document.body.append(box); box.showModal();
        });
    }
    window.PortalFeedback = { error: message => dialog(message), confirm: message => dialog(message, true) };
    let sequence = 0;
    function clear(field) {
        const id = field.dataset.portalFeedbackId;
        if (!id) return;
        document.getElementById(id)?.remove();
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');
        const described = (field.getAttribute('aria-describedby') || '').split(' ').filter(value => value && value !== id);
        if (described.length) field.setAttribute('aria-describedby', described.join(' '));
        else field.removeAttribute('aria-describedby');
        delete field.dataset.portalFeedbackId;
    }
    function explain(field) {
        const label = (field.labels?.[0]?.textContent || field.name || 'this field').replace(/\*/g, '').replace(/\s+/g, ' ').trim();
        const name = label.length > 80 ? 'this field' : label;
        const value = field.validity;
        if (field.dataset.validationMessage) return field.dataset.validationMessage;
        if (value.valueMissing) {
            if (field.type === 'checkbox') return 'Please confirm this choice before continuing.';
            return field.tagName === 'SELECT' ? `Choose an option for ${name}.` : `Please enter ${name}.`;
        }
        if (value.typeMismatch && field.type === 'email') return 'Enter a complete email address, such as name@example.com.';
        if (value.rangeUnderflow) return `${name} must be at least ${field.min}.`;
        if (value.rangeOverflow) return `${name} must be no more than ${field.max}.`;
        if (value.tooShort) return `${name} needs at least ${field.minLength} characters.`;
        if (value.tooLong) return `${name} must be no more than ${field.maxLength} characters.`;
        if (value.badInput || value.stepMismatch) return `Enter a valid number for ${name}.`;
        return `Check ${name} and use the format shown beside the field.`;
    }
    // Replace browser validation bubbles with inline feedback. Native validity
    // rules and the form's submit behavior remain unchanged.
    document.addEventListener('invalid', function (event) {
        const field = event.target;
        if (!field.form || field.disabled) return;
        event.preventDefault();
        clear(field);
        const message = document.createElement('p');
        const id = `portal-field-feedback-${++sequence}`;
        message.id = id;
        message.className = 'portal-field-feedback';
        message.setAttribute('role', 'alert');
        message.textContent = explain(field);
        const group = field.closest('.form-group, .form-floating, .terms-section') || field.parentElement;
        group.appendChild(message);
        field.dataset.portalFeedbackId = id;
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
        field.setAttribute('aria-describedby', `${field.getAttribute('aria-describedby') || ''} ${id}`.trim());
        if (field === field.form.querySelector(':invalid') && field.getClientRects().length) field.focus();
    }, true);
    document.addEventListener('input', event => { if (event.target.dataset?.portalFeedbackId) clear(event.target); });
    document.addEventListener('change', event => { if (event.target.dataset?.portalFeedbackId) clear(event.target); });
})();
