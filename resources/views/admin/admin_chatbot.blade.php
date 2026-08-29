@extends('admin.layouts.admin')

@section('title', 'Chatbot Management')
@section('page_title', 'Chatbot Management')
@section('page_icon', 'fas fa-robot')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/chatbot.css') }}">

@endpush

@section('content')
            @admin_can('view_content')
            @php
                $pendingUnmatched = collect($unmatched ?? [])
                    ->filter(fn ($item) => empty($item['resolved']))
                    ->values();
                $pendingUnmatchedCount = $pendingUnmatched->count();
            @endphp

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
                            <div class="hero-kicker">Knowledge &amp; Training</div>
                            <h2>Manage chatbot answers and training from one workspace.</h2>
                            <p>Maintain resident-facing answers, organize keywords, and review questions the chatbot could not match.</p>

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
                            <strong id="stat-unmatched">{{ $pendingUnmatchedCount }}</strong>
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
                    <button class="tab-link active" id="knowledge-tab" data-tab="knowledge" type="button" role="tab" aria-selected="true" aria-controls="tab-knowledge">
                        <i class="fas fa-database"></i>Knowledge Base
                    </button>
                    <button class="tab-link" id="unmatched-tab" data-tab="unmatched" type="button" role="tab" aria-selected="false" aria-controls="tab-unmatched">
                        <i class="fas fa-circle-question"></i>Unmatched Queries
                    </button>
                </div>

                <div class="chatbot-workspace">
                    <div id="tab-knowledge" class="tab-content active" role="tabpanel" aria-labelledby="knowledge-tab">
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

                                <button class="btn btn-light-soft btn-sm filter-reset-btn" id="kb-filter-reset" type="button" title="Reset filters" aria-label="Reset filters">
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
                                            <td class="kb-question" data-label="Question">{{ Str::limit($k['question'], 90) }}</td>
                                            <td class="kb-answer" data-label="Answer">{{ Str::limit($k['answer'], 130) }}</td>
                                            @php
                                                $keywordList = collect(explode(',', (string) ($k['keywords'] ?? '')))
                                                    ->map(fn ($keyword) => trim($keyword))
                                                    ->filter()
                                                    ->values();
                                            @endphp
                                            <td data-label="Keywords" title="{{ $keywordList->implode(', ') }}">
                                                <div class="keyword-list">
                                                    @forelse($keywordList->take(3) as $keyword)
                                                        <span class="keyword-chip">{{ Str::limit($keyword, 22) }}</span>
                                                    @empty
                                                        <span class="keyword-empty">No keywords</span>
                                                    @endforelse
                                                    @if($keywordList->count() > 3)
                                                        <span class="keyword-more">+{{ $keywordList->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td data-label="Category"><span class="badge-category">{{ $k['category'] ?? 'general' }}</span></td>
                                            <td data-label="Uses"><span class="usage-pill">{{ $k['usage_count'] ?? 0 }}</span></td>
                                            <td data-label="Actions">
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-outline-primary btn-edit" data-id="{{ $k['id'] }}" data-question="{{ $k['question'] }}" data-answer="{{ $k['answer'] }}" data-keywords="{{ $k['keywords'] ?? '' }}" data-category="{{ $k['category'] ?? 'general' }}" title="Edit knowledge" aria-label="Edit knowledge" type="button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('delete_content')
                                                    <button class="btn btn-outline-danger btn-delete" data-id="{{ $k['id'] }}" title="Delete knowledge" aria-label="Delete knowledge" type="button">
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

                    <div id="tab-unmatched" class="tab-content" role="tabpanel" aria-labelledby="unmatched-tab">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-question-circle-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-magic"></i> Training Queue</div>
                                        <h3>Unmatched Queries</h3>
                                        <p class="panel-copy">Train useful questions into knowledge, or dismiss entries that are unrelated to barangay services.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count" id="unmatched-count">{{ $pendingUnmatchedCount }} pending</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle unmatched-table" id="unmatched-table">
                                    <thead>
                                        <tr>
                                            <th style="width:42%">Query</th>
                                            <th style="width:18%">Session</th>
                                            <th style="width:16%">Time</th>
                                            <th style="width:24%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unmatched-tbody">
                                        @forelse($pendingUnmatched as $u)
                                        <tr class="unmatched-row" data-unmatched-id="{{ $u['id'] }}">
                                            <td class="unmatched-query" data-label="Query" title="{{ $u['query'] }}">{{ $u['query'] }}</td>
                                            <td data-label="Session">
                                                <div class="session-control">
                                                    <span class="session-pill" title="{{ $u['session_id'] ?? 'No session' }}">
                                                        {{ Str::limit($u['session_id'] ?? 'No session', 16) }}
                                                    </span>
                                                    @if(!empty($u['session_id']))
                                                    <button class="session-copy" type="button"
                                                            data-session="{{ $u['session_id'] }}"
                                                            title="Copy full session ID"
                                                            aria-label="Copy full session ID">
                                                        <i class="bi bi-copy"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td data-label="Time"><small class="query-time">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</small></td>
                                            <td data-label="Actions">
                                                <div class="btn-group btn-group-sm unmatched-actions">
                                                    @admin_can('create_content')
                                                    <button class="btn btn-train"
                                                            data-id="{{ $u['id'] }}"
                                                            data-query="{{ $u['query'] }}"
                                                            title="Create a knowledge item from this query"
                                                            type="button">
                                                        <i class="bi bi-plus-lg"></i>Train Bot
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-dismiss"
                                                            data-id="{{ $u['id'] }}"
                                                            data-query="{{ $u['query'] }}"
                                                            title="Dismiss this unrelated or unhelpful query"
                                                            type="button">
                                                        <i class="bi bi-x-lg"></i>Dismiss
                                                    </button>
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
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
                            <p class="modal-subtitle" id="addKnowledgeSubtitle">Create one clear resident-facing answer, then organize it so the chatbot can match related questions.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form" class="knowledge-form">
                        <input type="hidden" name="source_unmatched_id" id="source-unmatched-id" value="">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Answer Content</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" required autocomplete="off" placeholder="e.g. How do I track my Barangay Clearance request?">
                                    <span class="form-hint">Use the same wording residents commonly ask.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" rows="7" required placeholder="Write a short, complete answer with clear steps..."></textarea>
                                    <span class="form-hint">Keep the answer direct, accurate, and easy to scan.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization &amp; Matching</div>
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
                    <span class="modal-safe-note" id="addKnowledgeSafeNote"><i class="bi bi-shield-check"></i> Saved items become available to chatbot training.</span>
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
                            <p class="modal-subtitle">Review the answer first, then update its category and search keywords when needed.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" class="knowledge-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Answer Content</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" id="edit-question" required autocomplete="off">
                                    <span class="form-hint">Edit the resident-facing question or topic title.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" id="edit-answer" rows="7" required></textarea>
                                    <span class="form-hint">Make sure the answer is still correct before updating.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization &amp; Matching</div>
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
@include('shared.js-config', [
    'id' => 'js-config-admin-admin-chatbot-1',
    'data' => [
        'v1' => route('admin.chatbot.knowledge.store'),
        'v2' => route('admin.chatbot.knowledge.update', ['id' => '__ID__']),
        'v3' => route('admin.chatbot.knowledge.delete', ['id' => '__ID__']),
        'v4' => route('admin.chatbot.unmatched.resolve', ['id' => '__ID__']),
    ],
])
<script src="{{ asset('js/pages/admin/admin_chatbot-dynamic1.js') }}"></script>
@endpush
