const __cfg_admin_admin_chatbot_1 = JSON.parse(document.getElementById('js-config-admin-admin-chatbot-1')?.dataset.jsConfig || '{}');

        (function () {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const chatbotRoutes = {
                knowledgeStore: __cfg_admin_admin_chatbot_1.v1,
                knowledgeUpdateTemplate: __cfg_admin_admin_chatbot_1.v2,
                knowledgeDeleteTemplate: __cfg_admin_admin_chatbot_1.v3,
                unmatchedResolveTemplate: __cfg_admin_admin_chatbot_1.v4
            };

            function activateTab(tab) {
                document.querySelectorAll('.tab-link[data-tab]').forEach(link => {
                    const isActive = link.dataset.tab === tab;
                    link.classList.toggle('active', isActive);
                    link.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.toggle('active', content.id === 'tab-' + tab);
                });
            }

            document.querySelectorAll('.tab-link[data-tab]').forEach(link => {
                link.addEventListener('click', function () {
                    activateTab(this.dataset.tab);
                });
            });

            document.querySelectorAll('[data-tab-jump]').forEach(button => {
                button.addEventListener('click', function () {
                    activateTab(this.dataset.tabJump);
                    document.querySelector('.tab-navigation')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            const allowedCategories = ['general', 'clearance', 'residency', 'indigency', 'blotter', 'tracking', 'announcements', 'events', 'projects', 'portal', 'support'];

            function setFieldError(input, message) {
                if (!input) {
                    return;
                }
                input.classList.add('is-invalid');
                let feedback = input.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    input.insertAdjacentElement('afterend', feedback);
                }
                feedback.textContent = message;
            }

            function clearFieldError(input) {
                if (!input) {
                    return;
                }
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }

            function clearFormErrors(form) {
                if (!form) {
                    return;
                }
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }

            function validateKnowledgeForm(form) {
                if (!form) {
                    return false;
                }

                clearFormErrors(form);

                const question = form.querySelector('[name="question"]');
                const answer = form.querySelector('[name="answer"]');
                const keywords = form.querySelector('[name="keywords"]');
                const category = form.querySelector('[name="category"]');

                let valid = true;

                const qVal = (question?.value || '').trim();
                if (!qVal) {
                    setFieldError(question, 'Question is required.');
                    valid = false;
                } else if (qVal.length < 6) {
                    setFieldError(question, 'Question must be at least 6 characters.');
                    valid = false;
                } else if (qVal.length > 500) {
                    setFieldError(question, 'Question must not exceed 500 characters.');
                    valid = false;
                }

                const aVal = (answer?.value || '').trim();
                if (!aVal) {
                    setFieldError(answer, 'Answer is required.');
                    valid = false;
                } else if (aVal.length < 10) {
                    setFieldError(answer, 'Answer must be at least 10 characters.');
                    valid = false;
                } else if (aVal.length > 5000) {
                    setFieldError(answer, 'Answer must not exceed 5000 characters.');
                    valid = false;
                }

                const kVal = (keywords?.value || '').trim();
                if (kVal.length > 500) {
                    setFieldError(keywords, 'Keywords must not exceed 500 characters.');
                    valid = false;
                } else if (kVal && !/^[a-zA-Z0-9,\s\-]+$/.test(kVal)) {
                    setFieldError(keywords, 'Keywords can only contain letters, numbers, commas, spaces, and hyphens.');
                    valid = false;
                }

                const cVal = (category?.value || '').trim();
                if (!allowedCategories.includes(cVal)) {
                    setFieldError(category, 'Please select a valid category.');
                    valid = false;
                }

                return valid;
            }

            function applyServerErrors(form, errors) {
                if (!form || !errors) {
                    return;
                }
                Object.entries(errors).forEach(([field, messages]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input && Array.isArray(messages) && messages.length) {
                        setFieldError(input, messages[0]);
                    }
                });
            }

            async function api(method, url, body) {
                const opts = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                };
                if (body) {
                    opts.body = JSON.stringify(body);
                }
                const res = await fetch(url, opts);
                const payload = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const error = new Error(payload.message || `HTTP ${res.status}`);
                    error.status = res.status;
                    error.payload = payload;
                    throw error;
                }
                return payload;
            }

            function withId(template, id) {
                return template.replace('__ID__', String(id));
            }

            function formData(formEl) {
                const fd = new FormData(formEl);
                return Object.fromEntries(fd.entries());
            }

            function updateUnmatchedCount() {
                const pendingRows = document.querySelectorAll('#unmatched-tbody tr[data-unmatched-id]').length;
                const stat = document.getElementById('stat-unmatched');
                const count = document.getElementById('unmatched-count');

                if (stat) {
                    stat.textContent = String(pendingRows);
                }

                if (count) {
                    count.textContent = `${pendingRows} pending`;
                }

                if (pendingRows === 0) {
                    const tbody = document.getElementById('unmatched-tbody');
                    if (tbody) {
                        tbody.innerHTML = `
                            <tr class="empty-unmatched-row">
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div>
                                            <div class="empty-icon"><i class="bi bi-check2-circle"></i></div>
                                            <h4>Training queue is clear</h4>
                                            <p>There are no pending unmatched questions to review.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    }
                }
            }

            async function markUnmatchedProcessed(id) {
                return api('PATCH', withId(chatbotRoutes.unmatchedResolveTemplate, id));
            }

            function setAddModalContext(unmatchedId = '', query = '') {
                const addForm = document.getElementById('add-form');
                const sourceInput = document.getElementById('source-unmatched-id');
                const title = document.getElementById('addKnowledgeTitle');
                const subtitle = document.getElementById('addKnowledgeSubtitle');
                const safeNote = document.getElementById('addKnowledgeSafeNote');
                const saveButton = document.getElementById('save-add');

                if (addForm) {
                    addForm.reset();
                    clearFormErrors(addForm);
                }

                if (sourceInput) {
                    sourceInput.value = unmatchedId;
                }

                if (addForm && query) {
                    const questionInput = addForm.querySelector('[name="question"]');
                    if (questionInput) {
                        questionInput.value = query;
                    }
                }

                const isTraining = Boolean(unmatchedId);
                if (title) {
                    title.textContent = isTraining ? 'Train Chatbot' : 'Add Knowledge';
                }
                if (subtitle) {
                    subtitle.textContent = isTraining
                        ? 'Create the correct answer for this unmatched question. Saving it will remove the query from the training queue.'
                        : 'Create a clear chatbot answer residents can find through related keywords and category filtering.';
                }
                if (safeNote) {
                    safeNote.innerHTML = isTraining
                        ? '<i class="bi bi-check2-circle"></i> Saving also marks the unmatched query as processed.'
                        : '<i class="bi bi-shield-check"></i> Saved items become available to chatbot training.';
                }
                if (saveButton) {
                    saveButton.innerHTML = isTraining
                        ? '<i class="bi bi-save"></i>Save &amp; Mark Trained'
                        : '<i class="bi bi-save"></i>Save Knowledge';
                }
            }

            function copyText(value) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(value);
                }

                return new Promise((resolve, reject) => {
                    const textarea = document.createElement('textarea');
                    textarea.value = value;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    const copied = document.execCommand('copy');
                    textarea.remove();
                    copied ? resolve() : reject(new Error('Copy failed'));
                });
            }

            function toast(msg, type = 'success') {
                const el = document.createElement('div');
                const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'x-circle');
                el.className = `alert alert-${type} fade show chatbot-toast`;
                el.setAttribute('role', 'alert');
                el.innerHTML = `<i class="bi bi-${icon} me-2"></i>${msg}`;
                document.body.appendChild(el);

                setTimeout(() => {
                    el.classList.remove('show');
                    setTimeout(() => el.remove(), 250);
                }, 3500);
            }

            const addModalElement = document.getElementById('addModal');
            document.querySelectorAll('[data-bs-target="#addModal"]').forEach(button => {
                button.addEventListener('click', () => setAddModalContext());
            });
            addModalElement?.addEventListener('hidden.bs.modal', () => setAddModalContext());

            ['add-form', 'edit-form', 'quick-add-form'].forEach(formId => {
                const form = document.getElementById(formId);
                if (!form) {
                    return;
                }
                ['question', 'answer', 'keywords', 'category'].forEach(fieldName => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.addEventListener('input', () => clearFieldError(input));
                        input.addEventListener('change', () => clearFieldError(input));
                    }
                });
            });

            const quickAddForm = document.getElementById('quick-add-form');
            if (quickAddForm) {
            quickAddForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (!validateKnowledgeForm(this)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const data = formData(this);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge added!');
                        this.reset();
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(this, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            const saveAddBtn = document.getElementById('save-add');
            if (saveAddBtn) {
                saveAddBtn.addEventListener('click', async function () {
                    const addForm = document.getElementById('add-form');
                    if (!validateKnowledgeForm(addForm)) {
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }

                    const sourceUnmatchedId = (document.getElementById('source-unmatched-id')?.value || '').trim();
                    const data = formData(addForm);
                    delete data.source_unmatched_id;

                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>Saving...';

                    try {
                        const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                        if (!res.success) {
                            toast(res.message || 'Unable to save knowledge.', 'danger');
                            return;
                        }

                        let queryProcessed = !sourceUnmatchedId;
                        if (sourceUnmatchedId) {
                            try {
                                const processResult = await markUnmatchedProcessed(sourceUnmatchedId);
                                queryProcessed = Boolean(processResult.success);
                            } catch (error) {
                                queryProcessed = false;
                            }
                        }

                        bootstrap.Modal.getInstance(document.getElementById('addModal'))?.hide();

                        if (sourceUnmatchedId && !queryProcessed) {
                            toast('Knowledge was saved, but the query remains in the queue. You can dismiss it manually.', 'warning');
                        } else {
                            toast(sourceUnmatchedId ? 'Knowledge saved and query marked as trained.' : 'Knowledge saved!');
                        }

                        setTimeout(() => location.reload(), 900);
                    } catch (error) {
                        if (error.status === 422) {
                            applyServerErrors(addForm, error.payload?.errors);
                            toast('Please fix the validation errors first.', 'warning');
                            return;
                        }
                        toast('Network error', 'danger');
                    } finally {
                        this.disabled = false;
                        this.innerHTML = originalHtml;
                    }
                });
            }

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit-id').value = this.dataset.id;
                    document.getElementById('edit-question').value = this.dataset.question;
                    document.getElementById('edit-answer').value = this.dataset.answer;
                    document.getElementById('edit-keywords').value = this.dataset.keywords;
                    document.getElementById('edit-category').value = this.dataset.category;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
            });

            const saveEditBtn = document.getElementById('save-edit');
            if (saveEditBtn) {
            saveEditBtn.addEventListener('click', async function () {
                const editForm = document.getElementById('edit-form');
                if (!validateKnowledgeForm(editForm)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const id = document.getElementById('edit-id').value;
                const data = formData(editForm);
                try {
                    const res = await api('PUT', withId(chatbotRoutes.knowledgeUpdateTemplate, id), data);
                    if (res.success) {
                        toast('Updated!');
                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(editForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async function () {
                    if (!confirm('Delete this knowledge item? This cannot be undone.')) {
                        return;
                    }
                    const id = this.dataset.id;
                    try {
                        const res = await api('DELETE', withId(chatbotRoutes.knowledgeDeleteTemplate, id));
                        if (res.success) {
                            this.closest('tr').remove();
                            toast('Deleted.');
                        } else {
                            toast('Delete failed.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
                    }
                });
            });

            document.querySelectorAll('.btn-train').forEach(btn => {
                btn.addEventListener('click', function () {
                    setAddModalContext(this.dataset.id || '', this.dataset.query || '');
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                });
            });

            document.querySelectorAll('.btn-dismiss').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id = this.dataset.id;
                    const query = this.dataset.query || 'this query';

                    if (!confirm(`Dismiss “${query}”? It will be removed from the pending training queue without adding a chatbot answer.`)) {
                        return;
                    }

                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>Removing...';

                    try {
                        const res = await markUnmatchedProcessed(id);
                        if (!res.success) {
                            toast(res.message || 'Unable to dismiss the query.', 'danger');
                            return;
                        }

                        this.closest('tr')?.remove();
                        updateUnmatchedCount();
                        toast('Query dismissed from the training queue.');
                    } catch (error) {
                        toast('Network error', 'danger');
                    } finally {
                        if (document.body.contains(this)) {
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                        }
                    }
                });
            });

            document.querySelectorAll('.session-copy').forEach(button => {
                button.addEventListener('click', async function () {
                    const sessionId = this.dataset.session || '';
                    if (!sessionId) {
                        return;
                    }

                    try {
                        await copyText(sessionId);
                        toast('Session ID copied.');
                    } catch (error) {
                        toast('Unable to copy the session ID.', 'danger');
                    }
                });
            });

            const kbSearch = document.getElementById('kb-search');
            const kbSearchBtn = document.getElementById('kb-search-btn');
            const kbCategoryFilter = document.getElementById('kb-category-filter');
            const kbUsageFilter = document.getElementById('kb-usage-filter');
            const kbFilterReset = document.getElementById('kb-filter-reset');
            const kbFilterEmpty = document.getElementById('kb-filter-empty');
            const kbCount = document.getElementById('kb-count');

            function applyKnowledgeFilters() {
                const q = (kbSearch?.value || '').trim().toLowerCase();
                const category = (kbCategoryFilter?.value || '').trim().toLowerCase();
                const usage = (kbUsageFilter?.value || '').trim();
                const rows = Array.from(document.querySelectorAll('#knowledge-tbody tr[data-id]'));
                let visibleCount = 0;

                rows.forEach(tr => {
                    const text = tr.textContent.toLowerCase();
                    const rowCategory = (tr.dataset.category || 'general').toLowerCase();
                    const rowUsage = Number.parseInt(tr.dataset.usage || '0', 10) || 0;

                    const matchesSearch = !q || text.includes(q);
                    const matchesCategory = !category || rowCategory === category;
                    const matchesUsage = !usage || (usage === 'used' ? rowUsage > 0 : rowUsage === 0);
                    const shouldShow = matchesSearch && matchesCategory && matchesUsage;

                    tr.style.display = shouldShow ? '' : 'none';
                    if (shouldShow) {
                        visibleCount += 1;
                    }
                });

                const hasActiveFilter = Boolean(q || category || usage);
                if (kbCount && rows.length > 0) {
                    kbCount.textContent = hasActiveFilter ? `${visibleCount} shown` : `${rows.length} items`;
                }

                if (kbFilterEmpty) {
                    kbFilterEmpty.classList.toggle('d-none', rows.length === 0 || visibleCount > 0);
                }
            }

            if (kbSearchBtn) {
                kbSearchBtn.addEventListener('click', applyKnowledgeFilters);
            }

            if (kbSearch) {
                kbSearch.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        applyKnowledgeFilters();
                    }
                });
            }

            [kbCategoryFilter, kbUsageFilter].forEach(control => {
                if (control) {
                    control.addEventListener('change', applyKnowledgeFilters);
                }
            });

            if (kbFilterReset) {
                kbFilterReset.addEventListener('click', function () {
                    if (kbSearch) kbSearch.value = '';
                    if (kbCategoryFilter) kbCategoryFilter.value = '';
                    if (kbUsageFilter) kbUsageFilter.value = '';
                    applyKnowledgeFilters();
                });
            }
        })();
