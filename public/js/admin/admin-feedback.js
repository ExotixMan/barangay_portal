(function () {
    'use strict';
    const pending = new WeakSet();
    function notify(message, field = null) {
        const modal = document.querySelector('.modal.show .modal-body');
        const host = modal || document.querySelector('.admin-page') || document.body;
        let notice = host.querySelector(':scope > .admin-feedback');
        if (!notice) {
            notice = document.createElement('div');
            notice.className = 'admin-feedback';
            notice.setAttribute('role', 'alert');
            notice.tabIndex = -1;
            host.prepend(notice);
        }
        notice.textContent = String(message || 'Please review the form and try again.');
        notice.scrollIntoView({block: 'nearest', behavior: 'smooth'});
        if (field) { field.classList.add('is-invalid'); field.setAttribute('aria-invalid', 'true'); field.focus(); }
        else notice.focus({preventScroll: true});
    }
    async function confirm(message) {
        if (typeof Swal === 'undefined') { unavailableConfirm(); return false; }
        const result = await Swal.fire({title: 'Confirm action', text: String(message || 'Continue with this action?'),
            icon: 'question', showCancelButton: true, confirmButtonText: 'Continue', cancelButtonText: 'Cancel',
            confirmButtonColor: '#b4232d', cancelButtonColor: '#667085', focusCancel: true,
            target: document.querySelector('.modal.show') || document.body});
        return result.isConfirmed;
    }
    function unavailableConfirm() {
        notify('The confirmation dialog could not be opened. Reload this page and try again. No changes were submitted.');
        return false;
    }
    function unavailablePrompt() { unavailableConfirm(); return null; }
    function confirmSubmission(event, form, message, options = {}) {
        event?.preventDefault();
        if (!form || pending.has(form)) return false;
        pending.add(form);
        const submitter = event?.submitter || event?.target?.closest?.('button[type="submit"]');
        (async () => {
            try {
                const status = form.querySelector('[name="status"]')?.value;
                const remarks = form.querySelector('[name="remarks"]');
                if (options.requestRemarks && ['rejected', 'dropped'].includes(status)) {
                    if (typeof Swal === 'undefined' || !remarks) { unavailableConfirm(); return; }
                    const result = await Swal.fire({title: 'Add notification remarks', input: 'textarea',
                        inputValue: remarks.value || '', inputLabel: 'Explain the reason (up to 40 characters).',
                        showCancelButton: true, confirmButtonText: 'Continue', target: form.closest('.modal.show') || document.body,
                        inputValidator: value => !value.trim() ? 'Enter a reason before continuing.' : value.trim().length > 40 ? 'Keep remarks to 40 characters or fewer.' : undefined});
                    if (!result.isConfirmed) return;
                    remarks.value = result.value.trim();
                }
                if (!form.checkValidity()) return;
                if (!await confirm(message)) return;
                if (submitter?.name) {
                    const input = document.createElement('input'); input.type = 'hidden'; input.name = submitter.name; input.value = submitter.value; form.appendChild(input);
                }
                HTMLFormElement.prototype.submit.call(form);
            } finally { pending.delete(form); }
        })().catch(() => notify('We could not submit this action. Reload the page and try again.'));
        return false;
    }
    function escape(value) {
        return String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
    }
    window.AdminUI = {notify, confirm, confirmSubmission, unavailableConfirm, unavailablePrompt, escape};
})();
