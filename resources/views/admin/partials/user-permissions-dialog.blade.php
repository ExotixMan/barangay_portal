                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Manage User Permissions
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.users.permissions', $user->id) }}" id="userPermissionsForm{{ $user->id }}">
                                @csrf
                                    @php
                                        $isSuperAdminUser = $user->role && $user->role->name === 'super_admin';
                                    @endphp
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="user-avatar">{{ substr($user->first_name ?? 'J', 0, 1) }}{{ substr($user->last_name ?? 'D', 0, 1) }}</div>
                                            <div>
                                                <h6 class="mb-1">{{ $user->first_name ?? 'Juan' }} {{ $user->last_name ?? 'Dela Cruz' }}</h6>
                                                <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">Configure additional permissions for this user beyond their role-based permissions.</p>
                                    </div>

                                    @if($isSuperAdminUser)
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="fas fa-lock me-2"></i>
                                        <div>Super admin permissions are fixed and cannot be modified.</div>
                                    </div>
                                    @endif

                                    <div class="permissions-grid" style="grid-template-columns: 1fr;">

                                        @foreach($allPermissions as $module => $permissions)
                                        <div class="permission-module">
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
                                                @foreach($permissions as $permission)
                                                <div class="perm-item">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="user_perm_{{ $permission->id }}_{{ $user->id }}"
                                                        {{ $user->hasPermission($permission->name) ? 'checked' : '' }}
                                                        {{ $isSuperAdminUser ? 'disabled' : '' }}>
                                                    <label for="user_perm_{{ $permission->id }}_{{ $user->id }}">{{ $permission->display_name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    @if(session('form_type') == 'user_permissions_' . $user->id)
                                        @error('permissions')
                                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                        @enderror
                                        @error('permissions.*')
                                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="userPermissionsForm{{ $user->id }}" class="btn btn-success" {{ $isSuperAdminUser ? 'disabled' : '' }}>
                                        <i class="fas fa-save me-2"></i>Save Permissions
                                    </button>
                                </div>
                        </div>
                    </div>
