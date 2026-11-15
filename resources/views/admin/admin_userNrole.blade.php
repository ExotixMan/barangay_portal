@extends('admin.layouts.admin')

@section('title', 'Users & Roles Management')
@section('page_title', 'Users & Roles Management')
@section('page_icon', 'fas fa-users-cog')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/users-roles.css') }}">
@endpush

@section('content')
            <div class="users-page">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show users-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show users-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <section class="users-hero">
                <div class="users-hero-content">
                    <div class="users-hero-icon"><i class="fas fa-users-cog"></i></div>
                    <div class="users-kicker">Access Control Center</div>
                    <h2>Manage users, roles, and permissions clearly.</h2>
                    <p>Review administrator accounts, assign roles, configure permissions, and audit activity using the same clean card layout used in Backup Settings.</p>
                    <div class="users-hero-actions">
                        @admin_can('create_users')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus"></i>Add User
                        </button>
                        @endadmin_can
                        @admin_can('create_roles')
                        <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="fas fa-shield-alt"></i>Create Role
                        </button>
                        @endadmin_can
                    </div>
                </div>
            </section>

            <div class="row g-3 g-lg-4 mb-4 users-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total Users</div>
                            <div class="stat-number">{{ $total_users ?? 28 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-arrow-up me-1"></i>All time
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Active Users</div>
                            <div class="stat-number">{{ $active_users ?? 24 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-circle me-1"></i>Currently online
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Super Admins</div>
                            <div class="stat-number">{{ $super_admins ?? 1 }}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-crown me-1"></i>Full access
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total Roles</div>
                            <div class="stat-number">{{ $total_roles ?? 5 }}</div>
                            <small class="text-primary mt-2 d-none d-sm-block">
                                <i class="fas fa-shield-alt me-1"></i>Customizable
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-navigation">
                <button class="tab-link active" data-click-call="switchTab" data-click-pass="args,event" data-click-args='["users"]'>
                    <i class="fas fa-users me-2"></i>Users
                </button>
                <button class="tab-link" data-click-call="switchTab" data-click-pass="args,event" data-click-args='["roles"]'>
                    <i class="fas fa-shield-alt me-2"></i>Roles
                </button>
                @admin_can('view_permissions')
                <button class="tab-link" data-click-call="switchTab" data-click-pass="args,event" data-click-args='["permissions"]'>
                    <i class="fas fa-lock me-2"></i>Permissions
                </button>
                @endadmin_can
                <button class="tab-link" data-click-call="switchTab" data-click-pass="args,event" data-click-args='["audit"]'>
                    <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                </button>
            </div>

            <div id="usersTab" class="tab-content active">

                <div class="users-panel mb-4">
                    <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm">
                        <input type="hidden" name="tab" value="users">

                        <div class="users-panel-header">
                            <div class="users-panel-title">
                                <div class="users-panel-icon"><i class="fas fa-filter"></i></div>
                                <div class="min-w-0">
                                    <div class="section-kicker"><i class="fas fa-users"></i> User Directory</div>
                                    <h3>Filter Users</h3>
                                    <p class="panel-copy">Search by name or email, then narrow the list by role and account status.</p>
                                </div>
                            </div>

                            <div class="users-panel-actions">
                                @admin_can('create_users')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus"></i>Add User
                                </button>
                                @endadmin_can
                                <a href="{{ route('admin.users.index', ['tab' => 'users']) }}" class="btn btn-light-soft">
                                    <i class="fas fa-rotate"></i>Reset
                                </a>
                            </div>
                        </div>

                        <div class="users-filter-body">
                            <div class="users-filter-toolbar">
                                <div class="users-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search name, email, or username..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search users">
                                </div>

                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>

                                <select name="role" class="form-select" aria-label="Filter by role">
                                    <option value="">All roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->display_name ?? $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="status" class="form-select" aria-label="Filter by status">
                                    <option value="">All status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>

                                <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                    <i class="fas fa-filter"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card border-0 users-table-panel">
                    <div class="card-body p-0">
                        <div class="users-table-header">
                            <div class="users-table-title">
                                <span class="users-table-icon"><i class="fas fa-users"></i></span>
                                <div class="min-w-0">
                                    <h3>User Accounts</h3>
                                    <p>Review account details, roles, status, and quick actions in one clean table.</p>
                                </div>
                            </div>
                            <span class="users-count-pill">
                                <i class="fas fa-list"></i>
                                @if(isset($users))
                                    {{ $users->total() }} users
                                @else
                                    0 users
                                @endif
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50" class="ps-4">
                                            <input type="checkbox" id="selectAll" data-click-call="toggleSelectAll">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Joined Date</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users ?? [] as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <input type="checkbox" value="{{ $user->id }}" class="user-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ trim($user->first_name . ' ' . ($user->middle_initial ? $user->middle_initial . ' ' : '') . $user->last_name) }}</div>
                                                    <small class="text-muted">ID: {{ $user->user_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                                        </td>
                                        <td>{{ $user->department ?? 'N/A' }}</td>
                                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Jan 1, 2020' }}</td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @elseif($user->status == 'inactive')
                                                <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-1 gap-sm-2 justify-content-end">

                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                @admin_can('update_users')
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endadmin_can

                                                @admin_can('manage_user_permissions')
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#userPermissionsModal{{ $user->id }}" title="Manage Permissions">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                                @endadmin_can

                                                @admin_can('reset_user_password')
                                                <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" style="display: inline;" id="resetPasswordForm_{{ $user->id }}" data-submit-call="confirmResetPassword" data-submit-pass="event,self,args" data-submit-args="{{ json_encode([trim($user->first_name . ' ' . $user->last_name)]) }}">
                                                    @csrf
                                                    <input type="hidden" name="new_password" id="new_password_{{ $user->id }}" value="">
                                                    <button type="button" class="btn btn-sm btn-outline-warning" title="Reset Password"
                                                            data-click-call="promptNewPassword" data-click-args="{{ json_encode([$user->id, trim($user->first_name . ' ' . $user->last_name)]) }}">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                                @endadmin_can

                                                @if($user->role?->name != 'super_admin' && auth('admin')->user()->hasPermission('delete_users'))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Delete this user permanently?']) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <div>
                                                    <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                                                    <h4>No users found</h4>
                                                    <p>No user accounts match the current search and filters.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($users) && $users->total() > 0)
                        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <small class="text-muted order-2 order-md-1">
                                <i class="fas fa-list me-1"></i>
                                {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                            </small>
                            <nav class="order-1 order-md-2">
                                {{ $users->appends(['tab' => 'users'])->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div id="rolesTab" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>Role Management
                    </h5>
                    @admin_can('create_roles')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>Create New Role
                    </button>
                    @endadmin_can
                </div>

                <div class="roles-grid">
                    @forelse($roles as $role)
                    <div class="role-card">
                        @php
                            $roleClass = match($role->name) {
                                'super_admin' => 'super_admin',
                                'barangay_captain' => 'barangay_captain',
                                'staff' => 'staff',
                                'barangay_secretary' => 'barangay_secretary',
                                default => 'custom'
                            };
                        @endphp
                        <div class="role-header {{ $roleClass }}">
                            <h3>
                                @if($role->name == 'super_admin')
                                    <i class="fas fa-crown"></i>
                                @elseif($role->name == 'barangay_captain')
                                    <i class="fas fa-user-tie"></i>
                                @elseif($role->name == 'staff')
                                    <i class="fas fa-user"></i>
                                @elseif($role->name == 'barangay_secretary')
                                    <i class="fas fa-file-alt"></i>
                                @else
                                    <i class="fas fa-shield-alt"></i>
                                @endif
                                {{ $role->display_name }}
                            </h3>
                            <span class="role-count">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</span>
                        </div>
                        <div class="role-body">
                            <div class="permission-list">
                                @forelse($role->permissions->take(5) as $permission)
                                <div class="permission-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>{{ $permission->display_name ?? $permission->name }}
                                </div>
                                @empty
                                    <div class="permission-item text-muted"><i class="fas fa-info-circle me-2"></i>No permissions configured</div>
                                @endforelse

                                @if($role->permissions->count() > 5)
                                <div class="permission-item text-muted">
                                    <i class="fas fa-ellipsis-h me-2"></i>and {{ $role->permissions->count() - 5 }} more...
                                </div>
                                @endif
                            </div>
                            <div class="role-actions">
                                @admin_can('update_roles')
                                <button class="btn-small" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                @endadmin_can
                                @if(auth('admin')->user()->hasPermission('manage_role_permissions') || auth('admin')->user()->hasPermission('view_users'))
                                <button class="btn-small" data-bs-toggle="modal" data-bs-target="#viewRoleMembersModal{{ $role->id }}">
                                    <i class="fas fa-users me-1"></i>Members
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <div>
                                                    <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                                                    <h4>No users found</h4>
                                                    <p>No user accounts match the current search and filters.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                </div>
            </div>

            @admin_can('view_permissions')
            <div id="permissionsTab" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-semibold mb-1">
                            <i class="fas fa-lock me-2 text-primary"></i>Permission Management
                        </h5>
                        <p class="text-muted small">Configure granular permissions for each role</p>
                    </div>
                    <div class="d-flex gap-2">
                        @admin_can('reset_permission_defaults')
                        <form method="POST" action="{{ route('admin.permissions.reset-defaults') }}" id="resetPermissionsForm" style="display: inline;">
                            @csrf
                            @if($selectedRole)
                                <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">
                            @endif
                            <button type="submit" class="btn btn-outline-secondary" data-click-call="confirmReset" data-click-pass="event,self">
                                <i class="fas fa-undo me-2"></i>Reset Defaults
                            </button>
                        </form>
                        @endadmin_can
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" id="selectRoleForm">
                    <input type="hidden" name="tab" value="permissions">
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Select Role to Configure</label>
                                    <select name="role_id" class="form-select" data-submit-parent-form>
                                        <option value="">Choose a role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $selectedRole && $selectedRole->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name }} ({{ $role->users_count ?? 0 }} users)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Select a role to view and edit its permissions
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if($selectedRole)

                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2 fs-5"></i>
                        <div>Currently configuring permissions for: <strong>{{ $selectedRole->display_name }}</strong></div>
                    </div>

                    @if($selectedRole->name === 'super_admin')
                        <div class="alert alert-warning d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                            <div><strong>Super Admin</strong> permissions cannot be modified as they have full system access.</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.roles.permissions.update', $selectedRole->id) }}" id="permissionsForm">
                        @csrf
                        <div class="permissions-grid">
                            @foreach($allPermissions as $module => $permissions)
                            <div class="permission-module" data-module="{{ $module }}">
                                <div class="module-header">
                                    <h4>
                                        @switch($module)
                                            @case('dashboard')
                                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                                @break
                                            @case('residents')
                                                <i class="fas fa-users me-2 text-primary"></i>
                                                @break
                                            @case('residency')
                                                <i class="fas fa-file-signature me-2 text-primary"></i>
                                                @break
                                            @case('indigency')
                                                <i class="fas fa-file-invoice me-2 text-primary"></i>
                                                @break
                                            @case('clearance')
                                                <i class="fas fa-file-contract me-2 text-primary"></i>
                                                @break
                                            @case('blotter')
                                                <i class="fas fa-book me-2 text-primary"></i>
                                                @break
                                            @case('witness')
                                                <i class="fas fa-gavel me-2 text-primary"></i>
                                                @break
                                            @case('announcements')
                                                <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                @break
                                            @case('events')
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                @break
                                            @case('projects')
                                                <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                @break
                                            @case('requests')
                                                <i class="fas fa-inbox me-2 text-primary"></i>
                                                @break
                                            @case('notifications')
                                                <i class="fas fa-bell me-2 text-primary"></i>
                                                @break
                                            @case('users')
                                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                                @break
                                            @case('roles')
                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                @break
                                            @case('permissions')
                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                @break
                                            @default
                                                <i class="fas fa-cog me-2 text-primary"></i>
                                        @endswitch
                                        {{ ucfirst($module) }}
                                    </h4>
                                </div>
                                <div class="module-permissions">
                                    @foreach($permissions as $permission)
                                    <div class="perm-item">
                                        <input type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            id="perm_{{ $permission->id }}"
                                            {{ in_array($permission->id, $selectedRolePermissionIds) ? 'checked' : '' }}
                                            {{ ($selectedRole->name === 'super_admin' || !auth('admin')->user()->hasPermission('manage_role_permissions')) ? 'disabled' : '' }}>
                                        <label for="perm_{{ $permission->id }}">{{ $permission->display_name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($selectedRole->name !== 'super_admin' && auth('admin')->user()->hasPermission('manage_role_permissions'))
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save Permissions for {{ $selectedRole->display_name }}
                            </button>
                        </div>
                        @endif
                    </form>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x mb-3 text-muted"></i>
                        <h5>Select a role to configure permissions</h5>
                        <p class="text-muted">Please choose a role from the dropdown above to view and edit its permissions.</p>
                    </div>
                @endif
            </div>
            @endadmin_can

            <div id="auditTab" class="tab-content">
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('admin.users.audit-logs.export-csv', [
                                'tab' => 'audit',
                                'log_action' => request('log_action'),
                                'log_module' => request('log_module'),
                                'log_user_id' => request('log_user_id'),
                                'log_date' => request('log_date'),
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-csv me-2"></i>Export CSV
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <input type="hidden" name="tab" value="audit">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-semibold">Action</label>
                                    <select name="log_action" class="form-select">
                                        <option value="">All Actions</option>
                                        @foreach($auditActions as $action)
                                            <option value="{{ $action }}" {{ request('log_action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-semibold">Module</label>
                                    <select name="log_module" class="form-select">
                                        <option value="">All Modules</option>
                                        @foreach($auditModules as $module)
                                            <option value="{{ $module }}" {{ request('log_module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label fw-semibold">User</label>
                                    <select name="log_user_id" class="form-select">
                                        <option value="">All Users</option>
                                        @foreach($allUsers as $adminUser)
                                            <option value="{{ $adminUser->id }}" {{ (string) request('log_user_id') === (string) $adminUser->id ? 'selected' : '' }}>
                                                {{ $adminUser->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label fw-semibold">Date</label>
                                    <input type="date" name="log_date" class="form-control" value="{{ request('log_date') }}">
                                </div>
                                <div class="col-12 col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Apply
                                    </button>
                                    <a href="{{ route('admin.users.index', ['tab' => 'audit']) }}" class="btn btn-outline-secondary w-100">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">When</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Module</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($auditLogs as $log)
                                        <tr>
                                            <td class="ps-4">{{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-' }}</td>
                                            <td>{{ $log->user?->full_name ?? 'Unknown User' }}</td>
                                            <td>
                                                @php
                                                    $action = strtoupper((string) $log->action);
                                                    $actionBadgeClass = match(true) {
                                                        str_contains($action, 'CREATE') => 'bg-success-subtle text-success',
                                                        str_contains($action, 'UPDATE') => 'bg-warning-subtle text-warning',
                                                        str_contains($action, 'DELETE') => 'bg-danger-subtle text-danger',
                                                        str_contains($action, 'VIEW') => 'bg-info-subtle text-info',
                                                        default => 'bg-secondary-subtle text-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $actionBadgeClass }}">{{ $log->action }}</span>
                                            </td>
                                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $log->module }}</span></td>
                                            <td>
                                                @php
                                                    $details = is_array($log->details) ? $log->details : (json_decode((string) $log->details, true) ?: []);
                                                @endphp
                                                @if(!empty($details))
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary view-log-details-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#auditLogDetailsModal"
                                                            data-log-id="{{ $log->id }}"
                                                            data-log-action="{{ $log->action }}"
                                                            data-log-module="{{ $log->module }}"
                                                            data-log-user="{{ $log->user?->full_name ?? 'Unknown User' }}"
                                                            data-log-time="{{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-' }}"
                                                            data-log-details='@json($details)'>
                                                        <i class="fas fa-eye me-1"></i>View Details
                                                    </button>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>{{ $log->ip_address ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                                <div>No audit logs found for the selected filters.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($auditLogs) && $auditLogs->total() > 0)
                        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <small class="text-muted order-2 order-md-1">
                                <i class="fas fa-list me-1"></i>
                                {{ $auditLogs->firstItem() ?? 0 }}-{{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }}
                            </small>
                            <nav class="order-1 order-md-2">
                                {{ $auditLogs->appends(array_merge(['tab' => 'audit'], request()->only(['log_action', 'log_module', 'log_user_id', 'log_date'])))->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="auditLogDetailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i>Audit Log Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"><strong>User:</strong> <span id="auditDetailUser">-</span></div>
                                <div class="col-md-6"><strong>Time:</strong> <span id="auditDetailTime">-</span></div>
                                <div class="col-md-6"><strong>Action:</strong> <span id="auditDetailAction">-</span></div>
                                <div class="col-md-6"><strong>Module:</strong> <span id="auditDetailModule">-</span></div>
                            </div>
                            <label class="form-label fw-semibold">Details JSON</label>
                            <pre id="auditDetailJson" class="p-3 bg-light rounded border" style="max-height: 380px; overflow: auto; white-space: pre-wrap;">{}</pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @admin_can('create_users')
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-plus me-2"></i>
                                Add New User
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.users.store') }}" id="addUserForm">
                            @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Personal Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Middle Initial</label>
                                        <input type="text" class="form-control" name="middle_initial" maxlength="3" placeholder="A">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" placeholder="09XXXXXXXXX" maxlength="11">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Account Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" required>
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-select" name="role_id" required>
                                            <option value="">Select role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Department</label>
                                        <select class="form-select" name="department">
                                            <option value="">Select department</option>
                                            <option value="Administration">Administration</option>
                                            <option value="Records Management">Records Management</option>
                                            <option value="Health Services">Health Services</option>
                                            <option value="Events & Activities">Events & Activities</option>
                                            <option value="Public Relations">Public Relations</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addUserForm" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create User
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @admin_can('reset_user_password')
                <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-key me-2"></i>
                                    Reset User Password
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="" id="resetPasswordForm">
                                @csrf
                                    <div class="text-center mb-4">
                                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;" id="resetUserAvatar">JD</div>
                                        <h5 id="resetUserName">Juan Dela Cruz</h5>
                                        <span class="badge bg-info-subtle text-info" id="resetUserEmail">juan@example.com</span>
                                    </div>

                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                                        <div>This will reset the user's password. They will receive an email with instructions to set a new password.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="resetPassword" required minlength="8"
                                                   data-no-spaces>
                                            <button class="btn btn-outline-secondary" type="button" data-click-call="togglePasswordVisibility" data-click-args='["resetPassword"]'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Minimum 8 characters. <strong>No spaces allowed.</strong></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation" id="resetPasswordConfirm" required
                                                   data-no-spaces>
                                            <button class="btn btn-outline-secondary" type="button" data-click-call="togglePasswordVisibility" data-click-args='["resetPasswordConfirm"]'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="send_email" id="sendResetEmail" checked>
                                        <label class="form-check-label" for="sendResetEmail">
                                            Send password reset email to user
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="force_logout" id="forceLogout" checked>
                                        <label class="form-check-label" for="forceLogout">
                                            Force logout from all devices
                                        </label>
                                    </div>
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="resetPasswordForm" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Reset Password
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            @endadmin_can

            @admin_can('create_roles')
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-shield-alt me-2"></i>
                                Create New Role
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.roles.store') }}" id="addRoleForm">
                            @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add_role') @error('name') is-invalid @enderror @endif"
                                            name="name"
                                            value="{{ session('form_type') == 'add_role' ? old('name') : '' }}"
                                            placeholder="e.g., staff"
                                            pattern="[a-z_]+"
                                            title="Use lowercase letters and underscores only."
                                            required>
                                        @if(session('form_type') == 'add_role')
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                        <small class="text-muted">System name (lowercase, no spaces)</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Display Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add_role') @error('display_name') is-invalid @enderror @endif"
                                            name="display_name"
                                            value="{{ session('form_type') == 'add_role' ? old('display_name') : '' }}"
                                            placeholder="e.g., Staff Member">
                                        @if(session('form_type') == 'add_role')
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control @if(session('form_type') == 'add_role') @error('description') is-invalid @enderror @endif"
                                            name="description"
                                            rows="2"
                                            placeholder="Role description">{{ session('form_type') == 'add_role' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add_role')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Assign Permissions</h6>
                                        <p class="text-muted small">Select the permissions this role should have</p>
                                    </div>

                                    <div class="col-12">

                                        @foreach($allPermissions as $module => $permissions)
                                            <div class="permission-module mb-3">
                                                <div class="module-header">
                                                    <h4 class="mb-0">
                                                        @switch($module)
                                                            @case('dashboard')
                                                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                                                @break
                                                            @case('residents')
                                                                <i class="fas fa-users me-2 text-primary"></i>
                                                                @break
                                                            @case('residency')
                                                            @case('indigency')
                                                            @case('clearance')
                                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                                @break
                                                            @case('blotter')
                                                                <i class="fas fa-book me-2 text-primary"></i>
                                                                @break
                                                            @case('announcements')
                                                                <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                                @break
                                                            @case('events')
                                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                                @break
                                                            @case('projects')
                                                                <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                                @break
                                                            @case('requests')
                                                                <i class="fas fa-inbox me-2 text-primary"></i>
                                                                @break
                                                            @case('notifications')
                                                                <i class="fas fa-bell me-2 text-primary"></i>
                                                                @break
                                                            @case('users')
                                                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                                                @break
                                                            @case('roles')
                                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                                @break
                                                            @case('permissions')
                                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                                @break
                                                            @default
                                                                <i class="fas fa-cog me-2 text-primary"></i>
                                                        @endswitch
                                                        {{ $module }}
                                                    </h4>
                                                </div>
                                                <div class="module-permissions">
                                                    <div class="permission-checklist">
                                                        @foreach($permissions as $permission)
                                                        <div class="perm-check">
                                                            <label>
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                                    {{ session('form_type') == 'add_role' && is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                                                {{ $permission->display_name }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addRoleForm" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create Role
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @forelse($users ?? [] as $user)
            <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user me-2"></i>
                                User Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ substr($user->first_name ?? 'J', 0, 1) }}{{ substr($user->last_name ?? 'D', 0, 1) }}
                                </div>
                                <h5>{{ trim(($user->first_name ?? 'Juan') . ' ' . (($user->middle_initial ?? null) ? ($user->middle_initial . ' ') : '') . ($user->last_name ?? 'Dela Cruz')) }}</h5>
                                <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">User ID</small>
                                        <strong>{{ $user->user_id ?? 'USR-001' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Status</small>
                                        @if(($user->status ?? 'active') == 'active')
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @elseif(($user->status ?? '') == 'inactive')
                                            <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $user->email ?? 'juan.delacruz@barangay.gov.ph' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Middle Initial</small>
                                        <strong>{{ $user->middle_initial ?: 'N/A' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Department</small>
                                        <strong>{{ $user->department ?? 'Administration' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Joined Date</small>
                                        <strong>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Jan 1, 2020' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Last Login</small>
                                        <strong>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i:s') : 'Never logged in' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse

            @if(auth('admin')->user()->hasPermission('update_users'))
                @forelse($users ?? [] as $user)
                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit User
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name ?? 'Juan' }}" required>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">Middle Initial</label>
                                            <input type="text" class="form-control" name="middle_initial" value="{{ $user->middle_initial }}" maxlength="3" placeholder="A">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name ?? 'Dela Cruz' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ $user->email ?? 'juan.delacruz@barangay.gov.ph' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" name="contact_number" value="{{ $user->contact_number ?? '09123456789' }}" maxlength="11">
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Account Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="username" value="{{ $user->username ?? 'juan.delacruz' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="role_id" required>
                                                <option value="">Select role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ ($user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->display_name ?? $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Department</label>
                                            <select class="form-select" name="department">
                                                <option value="">Select department</option>
                                                <option value="Administration" {{ ($user->department ?? '') == 'Administration' ? 'selected' : '' }}>Administration</option>
                                                <option value="Records Management" {{ ($user->department ?? '') == 'Records Management' ? 'selected' : '' }}>Records Management</option>
                                                <option value="Health Services" {{ ($user->department ?? '') == 'Health Services' ? 'selected' : '' }}>Health Services</option>
                                                <option value="Events & Activities" {{ ($user->department ?? '') == 'Events & Activities' ? 'selected' : '' }}>Events & Activities</option>
                                                <option value="Public Relations" {{ ($user->department ?? '') == 'Public Relations' ? 'selected' : '' }}>Public Relations</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="active" {{ ($user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ ($user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="suspended" {{ ($user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editUserForm{{ $user->id }}" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            @endif

            @if(auth('admin')->user()->hasPermission('manage_user_permissions'))
                @forelse($users ?? [] as $user)
                <div class="modal fade" id="userPermissionsModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" @if(!(session('form_type') === 'user_permissions_' . $user->id)) data-lazy-modal-url="{{ route('admin.users.dialog', ['type' => 'user-permissions', 'id' => $user->id]) }}" @endif>
                    @if(session('form_type') === 'user_permissions_' . $user->id)
                        @include('admin.partials.user-permissions-dialog')
                    @else
                        @include('admin.partials.loading-dialog')
                    @endif
                </div>
                @empty
                @endforelse
            @endif

            @if(auth('admin')->user()->hasPermission('update_roles'))
                @if(isset($roles) && $roles->count() > 0)
                    @foreach($roles as $role)
                    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" @if(!(session('form_type') === 'edit_role_' . $role->id)) data-lazy-modal-url="{{ route('admin.users.dialog', ['type' => 'role-edit', 'id' => $role->id]) }}" @endif>
                    @if(session('form_type') === 'edit_role_' . $role->id)
                        @include('admin.partials.role-edit-dialog')
                    @else
                        @include('admin.partials.loading-dialog')
                    @endif
                </div>
                    @endforeach
                @endif
            @endif

            @if(auth('admin')->user()->hasAnyPermission(['manage_role_permissions', 'view_users']))
                @if(isset($roles) && $roles->count() > 0)
                    @foreach($roles as $role)
                    <div class="modal fade" id="viewRoleMembersModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-users me-2"></i>
                                        {{ $role->display_name ?? $role->name }} Members
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $role->display_name ?? $role->name }}</h6>
                                            <span class="badge bg-primary">{{ $role->users_count ?? $role->users->count() }} members</span>
                                        </div>
                                        @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMemberToRoleModal{{ $role->id }}">
                                            <i class="fas fa-user-plus me-1"></i>Add Member
                                        </button>
                                        @endif
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                                    <th class="text-end">Actions</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($role->users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar me-2">
                                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ trim($user->first_name . ' ' . ($user->middle_initial ? $user->middle_initial . ' ' : '') . $user->last_name) }}</div>
                                                                <small class="text-muted">ID: {{ $user->user_id }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->department ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($user->status == 'active')
                                                            <span class="badge bg-success-subtle text-success">Active</span>
                                                        @elseif($user->status == 'inactive')
                                                            <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                                        @endif
                                                    </td>
                                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                                    <td class="text-end">
                                                        <form method="POST" action="{{ route('admin.roles.members.remove', [$role->id, $user->id]) }}"
                                                              style="display: inline;"
                                                              data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Remove this user from the role?']) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from role">
                                                                <i class="fas fa-user-minus"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="{{ auth('admin')->user()->hasPermission('manage_role_permissions') ? '5' : '4' }}" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-users-slash fa-2x mb-2"></i>
                                                            <p>No members found in this role</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                    <div class="modal fade" id="addMemberToRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Add Member to {{ $role->display_name ?? $role->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.roles.members.add', $role->id) }}" id="addMemberForm{{ $role->id }}">
                                    @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Select User</label>
                                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                                <option value="">Choose a user...</option>
                                                @foreach($allUsers ?? [] as $user)
                                                    @if(!$role->users->contains($user->id))
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                        ({{ $user->email }}) - {{ $user->department ?? 'No Department' }}
                                                    </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                        <button type="submit" form="addMemberForm{{ $role->id }}" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Add Member
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                @endif
            @endif

            </div>
@endsection

@push('scripts')
@if ($errors->any() && session('form_type') == 'add_role')
    <script src="{{ asset('js/pages/admin/admin_userNrole-part1.js') }}"></script>
    @endif

    @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_role_'))
        @php
            $editRoleId = str_replace('edit_role_', '', session('form_type'));
        @endphp
        @include('shared.js-config', [
    'id' => 'js-config-admin-admin-usernrole-1',
    'data' => [
        'v1' => e($editRoleId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_userNrole-dynamic1.js') }}"></script>
    @endif

    @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'user_permissions_'))
        @php
            $permissionsUserId = str_replace('user_permissions_', '', session('form_type'));
        @endphp
        @include('shared.js-config', [
    'id' => 'js-config-admin-admin-usernrole-2',
    'data' => [
        'v1' => e($permissionsUserId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_userNrole-dynamic2.js') }}"></script>
    @endif

    <script src="{{ asset('js/pages/admin/admin_userNrole-part2.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-admin-users-roles',
    'data' => [
        'passwordResetSuccess' => session('success') && str_contains(session('success'), 'Password reset') ? session('success') : null,
        'passwordError' => session('error') && str_contains(session('error'), 'password') ? session('error') : null,
    ],
])
<script src="{{ asset('js/pages/admin/admin_userNrole-main.js') }}"></script>

    <script src="{{ asset('js/pages/admin/admin_userNrole-part3.js') }}"></script>
@endpush
