                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-shield-edit me-2"></i>
                                        Edit Role: {{ $role->display_name ?? $role->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" id="editRoleForm{{ $role->id }}">
                                    @csrf
                                    @method('PUT')
                                        <div class="row g-3">
                                            @if($role->is_system_role)
                                            <div class="col-12">
                                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                                    <i class="fas fa-info-circle me-2 fs-5"></i>
                                                    <div>This is a system role with limited edit capabilities.</div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name', $role->name) }}"
                                                    {{ $role->is_system_role ? 'readonly' : '' }} required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">System name (lowercase, no spaces)</small>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Display Name</label>
                                                <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                                    name="display_name" value="{{ old('display_name', $role->display_name) }}">
                                                @error('display_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror"
                                                        name="description" rows="2">{{ old('description', $role->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @if(!$role->is_system_role && auth('admin')->user()->hasPermission('manage_role_permissions'))
                                            <div class="col-12 mt-3">
                                                <h6 class="fw-semibold text-primary">Role Permissions</h6>
                                                <p class="text-muted small">Configure which permissions this role should have</p>
                                            </div>

                                            <div class="col-12">
                                                @php
                                                            $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                                                @endphp

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
                                                                        <input type="checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
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
                                        <button type="submit" form="editRoleForm{{ $role->id }}" class="btn btn-primary" {{ $role->is_system_role ? 'disabled' : '' }}>
                                            <i class="fas fa-save me-2"></i>Update Role
                                        </button>
                                </div>
                            </div>
                        </div>
