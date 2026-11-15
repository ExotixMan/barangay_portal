(function () {
            'use strict';

            const modalCopy = [
                ['addUserModal', 'Create an administrator account, assign a role, and set the first login details.'],
                ['resetPasswordModal', 'Set a secure temporary password and choose whether to send reset instructions.'],
                ['addRoleModal', 'Create a role with only the permissions needed for that responsibility.'],
                ['auditLogDetailsModal', 'Review the recorded action, actor, timestamp, module, and stored event details.']
            ];

            function resolveModalSubtitle(modal) {
                const id = modal.getAttribute('id') || '';

                for (const [key, copy] of modalCopy) {
                    if (id === key) return copy;
                }

                if (id.startsWith('viewUserModal')) {
                    return 'View account profile, role, contact, and activity information without editing anything.';
                }

                if (id.startsWith('editUserModal')) {
                    return 'Update only the account fields that need to change, then save the user record.';
                }

                if (id.startsWith('userPermissionsModal')) {
                    return 'Grant or remove direct user permissions. Use search to quickly find a permission.';
                }

                if (id.startsWith('editRoleModal')) {
                    return 'Update the role name, description, and permission set for this role.';
                }

                if (id.startsWith('viewRoleMembersModal')) {
                    return 'Review the administrator accounts currently assigned to this role.';
                }

                return 'Review the details below before saving changes.';
            }

            function enhanceModalHeaders() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    const header = modal.querySelector('.modal-header');
                    const title = modal.querySelector('.modal-title');

                    if (!header || !title || header.querySelector('.ux-modal-subtitle')) {
                        return;
                    }

                    const subtitle = document.createElement('p');
                    subtitle.className = 'ux-modal-subtitle';
                    subtitle.textContent = resolveModalSubtitle(modal);

                    const closeBtn = header.querySelector('.btn-close');
                    if (closeBtn) {
                        header.insertBefore(subtitle, closeBtn);
                    } else {
                        header.appendChild(subtitle);
                    }
                });
            }

            function addPermissionSearch() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    const id = modal.getAttribute('id') || '';
                    const hasPermissions = modal.querySelector('.permission-checklist, .module-permissions');

                    if (!hasPermissions || modal.querySelector('.ux-permission-search')) {
                        return;
                    }

                    if (!id.includes('Permission') && !id.includes('Role')) {
                        return;
                    }

                    const body = modal.querySelector('.modal-body');
                    if (!body) {
                        return;
                    }

                    const modules = Array.from(modal.querySelectorAll('.permission-module'));
                    const checks = Array.from(modal.querySelectorAll('.perm-check, .perm-item'));
                    const search = document.createElement('div');
                    search.className = 'ux-permission-search';
                    search.innerHTML = `
                        <div class="users-search-field">
                            <i class="fas fa-search"></i>
                            <input type="search" class="form-control ux-permission-input" placeholder="Search permission..." aria-label="Search permissions">
                        </div>
                        <span class="users-count-pill"><i class="fas fa-shield-alt"></i>${checks.length} permissions</span>
                    `;

                    body.insertBefore(search, body.firstElementChild);

                    const input = search.querySelector('.ux-permission-input');

                    input.addEventListener('input', () => {
                        const query = input.value.trim().toLowerCase();

                        checks.forEach((item) => {
                            const match = item.textContent.toLowerCase().includes(query);
                            item.classList.toggle('is-hidden', query !== '' && !match);
                        });

                        modules.forEach((module) => {
                            const visible = Array.from(module.querySelectorAll('.perm-check, .perm-item'))
                                .some((item) => !item.classList.contains('is-hidden'));
                            module.classList.toggle('is-empty', !visible);
                        });
                    });
                });
            }

            function keepModalFocusClean() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    modal.addEventListener('shown.bs.modal', () => {
                        const first = modal.querySelector('input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
                        if (first && !modal.querySelector('.ux-permission-input')) {
                            setTimeout(() => first.focus(), 80);
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                enhanceModalHeaders();
                addPermissionSearch();
                keepModalFocusClean();
            });
            document.addEventListener('admin:dialog-loaded', function () {
                enhanceModalHeaders();
                addPermissionSearch();
            });
        })();
