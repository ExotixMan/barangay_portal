(function () {
    'use strict';
    const pending = new WeakSet();
    async function load(modal) {
        if (pending.has(modal) || modal.dataset.lazyModalLoaded === 'true') return;
        pending.add(modal);
        modal.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(modal.dataset.lazyModalUrl, {
                headers: {'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html'},
                credentials: 'same-origin'
            });
            if (!response.ok || response.redirected) throw new Error('Dialog unavailable');
            const template = document.createElement('template');
            template.innerHTML = await response.text();
            const content = template.content.querySelector('.modal-dialog');
            const dialog = modal.querySelector('.modal-dialog');
            if (!content || !dialog) throw new Error('Invalid dialog response');
            // Bootstrap retains a reference to this element for its focus and
            // transition lifecycle. Replace its contents, not the element.
            dialog.className = content.className;
            dialog.replaceChildren(...content.childNodes);
            modal.dataset.lazyModalLoaded = 'true';
            document.dispatchEvent(new CustomEvent('admin:dialog-loaded', {detail: {modal}}));
            bootstrap.Modal.getInstance(modal)?.handleUpdate();
            if (modal.classList.contains('show')) {
                modal.querySelector('input:not([type="hidden"]):not(:disabled), button')?.focus();
            }
        } catch (error) {
            const body = modal.querySelector('.modal-body');
            body.replaceChildren();
            const message = document.createElement('p');
            message.setAttribute('role', 'alert');
            message.textContent = 'These details could not be loaded. Try again, or reload the page if your session has ended.';
            const retry = document.createElement('button');
            retry.type = 'button'; retry.className = 'btn btn-outline-primary'; retry.textContent = 'Try again';
            retry.addEventListener('click', () => { retry.disabled = true; load(modal); });
            body.append(message, retry);
        } finally {
            pending.delete(modal);
            modal.removeAttribute('aria-busy');
        }
    }
    document.addEventListener('show.bs.modal', event => {
        if (event.target.dataset.lazyModalUrl) load(event.target);
    });
})();
