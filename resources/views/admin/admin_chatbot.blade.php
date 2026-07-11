@extends('admin.layouts.admin')

@section('title', 'Chatbot Management')
@section('page_title', 'Chatbot Management')
@section('page_icon', 'fas fa-robot')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/chatbot.css') }}">
@endpush

@section('content')
            @admin_can('view_content')
            <div class="chatbot-page">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show chatbot-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show chatbot-alert" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <section class="chatbot-hero">
                    <div class="hero-panel">
                        <div class="hero-content">
                            <div class="hero-icon"><i class="fas fa-robot"></i></div>
                            <div class="hero-kicker">Chatbot Control Center</div>
                            <h2>Keep chatbot answers clean, useful, and easy to train.</h2>
                            <p>Manage the bot knowledge base, review unanswered questions, and improve future responses with the same minimal card layout used in Backup Settings.</p>

                            <div class="hero-actions">
                                @admin_can('create_content')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
                                    <i class="bi bi-plus-lg"></i>Add Knowledge
                                </button>
                                @endadmin_can
                                <button class="btn btn-light-soft" type="button" data-tab-jump="unmatched">
                                    <i class="bi bi-magic"></i>Review Training Queue
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="chatbot-stats-grid">
                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-database-fill"></i></div>
                        <div class="min-w-0">
                            <span>Knowledge Items</span>
                            <strong id="stat-knowledge">{{ $stats['knowledge_count'] ?? 0 }}</strong>
                            <small>Total saved answers available to the bot.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                        <div class="min-w-0">
                            <span>Conversations</span>
                            <strong id="stat-conv">{{ $stats['conversation_count'] ?? 0 }}</strong>
                            <small>Recorded user chatbot sessions.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-send-fill"></i></div>
                        <div class="min-w-0">
                            <span>Total Messages</span>
                            <strong id="stat-msg">{{ $stats['message_count'] ?? 0 }}</strong>
                            <small>Messages handled across conversations.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-question-circle-fill"></i></div>
                        <div class="min-w-0">
                            <span>Unmatched Queries</span>
                            <strong id="stat-unmatched">{{ $stats['unmatched_count'] ?? 0 }}</strong>
                            <small>Questions that need training review.</small>
                        </div>
                    </div>
                </div>

                <div class="training-note">
                    <div class="panel-icon"><i class="bi bi-lightbulb-fill"></i></div>
                    <div class="min-w-0">
                        <h3>Training reminder</h3>
                        <p>Prioritize unmatched questions with repeated resident intent, then convert them into short, clear knowledge items with searchable keywords.</p>
                    </div>
                </div>

                <div class="tab-navigation" role="tablist" aria-label="Chatbot management tabs">
                    <button class="tab-link active" data-tab="knowledge" type="button">
                        <i class="fas fa-database"></i>Knowledge Base
                    </button>
                    <button class="tab-link" data-tab="unmatched" type="button">
                        <i class="fas fa-circle-question"></i>Unmatched Queries
                    </button>
                </div>

                <div class="chatbot-workspace">
                    <div id="tab-knowledge" class="tab-content active">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-database-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-stars"></i> Bot Knowledge</div>
                                        <h3>Knowledge Base</h3>
                                        <p class="panel-copy">Search, edit, and organize chatbot answers by category and keywords.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count" id="kb-count">{{ count($knowledge) }} items</span>
                                </div>
                            </div>

                            <div class="knowledge-toolbar" aria-label="Knowledge base search and filters">
                                <div class="search-control">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="form-control form-control-sm" id="kb-search" placeholder="Search question, answer, keyword...">
                                </div>

                                <button class="btn btn-primary btn-sm search-submit-btn" id="kb-search-btn" type="button">
                                    <i class="bi bi-search"></i>Search
                                </button>

                                <div class="filter-control">
                                    <i class="bi bi-funnel"></i>
                                    <select class="form-select form-select-sm" id="kb-category-filter" aria-label="Filter by category">
                                        <option value="">All categories</option>
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                </div>

                                <div class="filter-control">
                                    <i class="bi bi-activity"></i>
                                    <select class="form-select form-select-sm" id="kb-usage-filter" aria-label="Filter by usage">
                                        <option value="">All usage</option>
                                        <option value="used">Used</option>
                                        <option value="unused">Unused</option>
                                    </select>
                                </div>

                                <button class="btn btn-light-soft btn-sm filter-reset-btn" id="kb-filter-reset" type="button" title="Reset filters">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>

                                @admin_can('create_content')
                                <button class="btn btn-primary btn-sm knowledge-add-btn" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
                                    <i class="bi bi-plus-lg"></i>Add
                                </button>
                                @endadmin_can
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="knowledge-table">
                                    <thead>
                                        <tr>
                                            <th style="width:26%">Question</th>
                                            <th style="width:34%">Answer</th>
                                            <th style="width:16%">Keywords</th>
                                            <th style="width:10%">Category</th>
                                            <th style="width:6%">Uses</th>
                                            <th style="width:8%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="knowledge-tbody">
                                        @forelse($knowledge as $k)
                                        <tr data-id="{{ $k['id'] }}" data-category="{{ strtolower($k['category'] ?? 'general') }}" data-usage="{{ $k['usage_count'] ?? 0 }}">
                                            <td class="kb-question">{{ Str::limit($k['question'], 90) }}</td>
                                            <td class="kb-answer">{{ Str::limit($k['answer'], 130) }}</td>
                                            <td><span class="keyword-text">{{ Str::limit($k['keywords'] ?? 'No keywords', 70) }}</span></td>
                                            <td><span class="badge-category">{{ $k['category'] ?? 'general' }}</span></td>
                                            <td><span class="usage-pill">{{ $k['usage_count'] ?? 0 }}</span></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-outline-secondary btn-edit" data-id="{{ $k['id'] }}" data-question="{{ addslashes($k['question']) }}" data-answer="{{ addslashes($k['answer']) }}" data-keywords="{{ addslashes($k['keywords'] ?? '') }}" data-category="{{ $k['category'] ?? 'general' }}" title="Edit" type="button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('delete_content')
                                                    <button class="btn btn-outline-danger btn-delete" data-id="{{ $k['id'] }}" title="Delete" type="button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <div>
                                                        <div class="empty-icon"><i class="bi bi-database-add"></i></div>
                                                        <h4>No knowledge items yet</h4>
                                                        <p>Add the first answer so the chatbot can respond to common resident questions.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="filter-empty-state d-none" id="kb-filter-empty">
                                <div class="empty-state">
                                    <div>
                                        <div class="empty-icon"><i class="bi bi-search"></i></div>
                                        <h4>No matching knowledge items</h4>
                                        <p>Try a different search term, category, or usage filter.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-unmatched" class="tab-content">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-question-circle-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-magic"></i> Training Queue</div>
                                        <h3>Unmatched Queries</h3>
                                        <p class="panel-copy">Review questions the bot could not answer and turn useful ones into knowledge items.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count">{{ $stats['unmatched_count'] ?? count($unmatched) }} pending</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:36%">Query</th>
                                            <th style="width:16%">Session</th>
                                            <th style="width:16%">Time</th>
                                            <th style="width:12%">Status</th>
                                            <th style="width:20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($unmatched as $u)
                                        <tr class="{{ $u['resolved'] ? 'resolved-row' : 'unmatched-row' }}" data-unmatched-id="{{ $u['id'] }}">
                                            <td class="unmatched-query" title="{{ $u['query'] }}">{{ $u['query'] }}</td>
                                            <td><span class="session-pill">{{ Str::limit($u['session_id'] ?? 'No session', 16) }}</span></td>
                                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</small></td>
                                            <td>
                                                @if($u['resolved'])
                                                    <span class="status-badge is-resolved">Resolved</span>
                                                @else
                                                    <span class="status-badge is-pending">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('create_content')
                                                    <button class="btn btn-outline-primary btn-train" data-query="{{ addslashes($u['query']) }}" type="button">
                                                        <i class="bi bi-plus"></i>Train Bot
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('edit_content')
                                                    @if(!$u['resolved'])
                                                        <button class="btn btn-outline-success btn-resolve" data-id="{{ $u['id'] }}" type="button">
                                                            <i class="bi bi-check2"></i>Resolve
                                                        </button>
                                                    @endif
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="empty-state">
                                                    <div>
                                                        <div class="empty-icon"><i class="bi bi-check2-circle"></i></div>
                                                        <h4>No unmatched queries</h4>
                                                        <p>Your chatbot is currently answering all recorded questions successfully.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="permission-panel">
                <i class="fas fa-lock"></i>
                <div>
                    <strong>You do not have permission to view chatbot management.</strong>
                    <p class="mb-0">Ask a system administrator to enable the required chatbot content permission for your account.</p>
                </div>
            </div>
            @endadmin_can

@admin_can('create_content')
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addKnowledgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-knowledge modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header knowledge-modal-header">
                    <div class="modal-heading">
                        <span class="knowledge-modal-icon"><i class="bi bi-plus-circle"></i></span>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="addKnowledgeTitle">Add Knowledge</h5>
                            <p class="modal-subtitle">Create a clear chatbot answer residents can find through related keywords and category filtering.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form" class="knowledge-form">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Main answer</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" required placeholder="e.g. How do I track my Barangay Clearance request?">
                                    <span class="form-hint">Use the same wording residents commonly ask.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" rows="7" required placeholder="Write a short, complete answer with clear steps..."></textarea>
                                    <span class="form-hint">Keep the answer direct, accurate, and easy to scan.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization</div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <span class="form-hint">This powers the table filter.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="keywords" placeholder="clearance, reference id, status">
                                    <span class="form-hint">Separate keywords with commas.</span>
                                </div>
                                <div class="knowledge-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div>Add important aliases residents may use, like “certificate,” “request ID,” or “track status.”</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer knowledge-modal-footer">
                    <span class="modal-safe-note"><i class="bi bi-shield-check"></i> Saved items become available to chatbot training.</span>
                    <div class="modal-footer-actions">
                        <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save-add"><i class="bi bi-save"></i>Save Knowledge</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can

    @admin_can('edit_content')
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editKnowledgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-knowledge modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header knowledge-modal-header">
                    <div class="modal-heading">
                        <span class="knowledge-modal-icon"><i class="bi bi-pencil-square"></i></span>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="editKnowledgeTitle">Edit Knowledge</h5>
                            <p class="modal-subtitle">Update the chatbot answer, keywords, and category without changing the existing management flow.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" class="knowledge-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Main answer</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" id="edit-question" required>
                                    <span class="form-hint">Edit the resident-facing question or topic title.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" id="edit-answer" rows="7" required></textarea>
                                    <span class="form-hint">Make sure the answer is still correct before updating.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization</div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category" id="edit-category">
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <span class="form-hint">Changing this updates where the item appears in the filter.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="keywords" id="edit-keywords" placeholder="clearance, reference id, status">
                                    <span class="form-hint">Use comma-separated search terms.</span>
                                </div>
                                <div class="knowledge-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div>Use simple words residents type naturally, not only official document names.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer knowledge-modal-footer">
                    <span class="modal-safe-note"><i class="bi bi-clock-history"></i> Updates apply after saving.</span>
                    <div class="modal-footer-actions">
                        <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save-edit"><i class="bi bi-save"></i>Update Knowledge</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can
@endsection

@push('scripts')
<script>
        (function () {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const chatbotRoutes = {
                knowledgeStore: @json(route('admin.chatbot.knowledge.store')),
                knowledgeUpdateTemplate: @json(route('admin.chatbot.knowledge.update', ['id' => '__ID__'])),
                knowledgeDeleteTemplate: @json(route('admin.chatbot.knowledge.delete', ['id' => '__ID__'])),
                unmatchedResolveTemplate: @json(route('admin.chatbot.unmatched.resolve', ['id' => '__ID__']))
            };

            function activateTab(tab) {
                document.querySelectorAll('.tab-link[data-tab]').forEach(l => l.classList.toggle('active', l.dataset.tab === tab));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                const panel = document.getElementById('tab-' + tab);
                if (panel) {
                    panel.classList.add('active');
                }
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
                const data = formData(addForm);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge saved!');
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(addForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
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
                    document.getElementById('add-form').querySelector('[name="question"]').value = this.dataset.query;
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                });
            });

            document.querySelectorAll('.btn-resolve').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id = this.dataset.id;
                    try {
                        const res = await api('PATCH', withId(chatbotRoutes.unmatchedResolveTemplate, id));
                        if (res.success) {
                            const row = this.closest('tr');
                            if (row) {
                                row.classList.remove('unmatched-row');
                                row.classList.add('resolved-row');
                                const statusCell = row.children[3];
                                if (statusCell) {
                                    statusCell.innerHTML = '<span class="badge bg-success-subtle text-success">Resolved</span>';
                                }
                            }
                            this.remove();
                            toast('Marked as resolved.');
                        } else {
                            toast('Unable to mark as resolved.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
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
    </script>
@endpush
