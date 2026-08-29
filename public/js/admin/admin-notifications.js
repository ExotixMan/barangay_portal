(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const config = window.adminNotificationConfig;
        const root = document.getElementById('adminNotification');
        const toggle = document.getElementById('adminNotificationToggle');
        const dropdown = document.getElementById('adminNotificationDropdown');
        const badge = document.getElementById('adminNotificationBadge');
        const summary = document.getElementById('adminNotificationSummary');
        const readAllButton = document.getElementById('adminNotificationReadAll');
        const refreshButton = document.getElementById('adminNotificationRefresh');
        const list = document.getElementById('adminNotificationList');
        const toastContainer = document.getElementById('adminNotificationToastContainer');

        if (!config || !root || !toggle || !dropdown || !badge || !list) {
            return;
        }

        function getUnreadCount() {
            return Math.max(0, Number(badge.dataset.count || 0));
        }

        function setUnreadCount(count) {
            const safeCount = Math.max(0, Number(count) || 0);

            badge.dataset.count = String(safeCount);
            badge.textContent = safeCount > 99 ? '99+' : String(safeCount);
            badge.hidden = safeCount === 0;

            if (summary) {
                summary.textContent = safeCount + ' unread';
            }

            if (readAllButton) {
                readAllButton.disabled = safeCount === 0;
            }
        }

        function setDropdownOpen(open) {
            dropdown.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        function templateUrl(template, notificationId) {
            return template.replace(
                '__NOTIFICATION_ID__',
                encodeURIComponent(notificationId)
            );
        }

        async function fetchJson(url, options) {
            const response = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options?.headers || {})
                },
                ...options
            });

            if (!response.ok) {
                throw new Error('Notification request failed with status ' + response.status + '.');
            }

            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                throw new Error('The notification endpoint returned HTML instead of JSON. Check the admin route prefix.');
            }

            return response.json();
        }

        async function postJson(url) {
            return fetchJson(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            });
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (character) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[character];
            });
        }

        function iconFor(notification) {
            return notification.icon || 'fas fa-bell';
        }

        function notificationItemHtml(notification) {
            const unreadClass = notification.is_unread ? ' is-unread' : '';
            const unreadDot = notification.is_unread
                ? '<span class="admin-notification__unread-dot" aria-label="Unread"></span>'
                : '';
            const openUrl = notification.open_url || templateUrl(
                config.openUrlTemplate,
                notification.id
            );

            return `
                <a href="${escapeHtml(openUrl)}"
                   class="admin-notification__item${unreadClass}"
                   data-notification-id="${escapeHtml(notification.id)}">
                    <span class="admin-notification__icon" aria-hidden="true">
                        <i class="${escapeHtml(iconFor(notification))}"></i>
                    </span>
                    <span class="admin-notification__content">
                        <strong>${escapeHtml(notification.title || 'New notification')}</strong>
                        <span>${escapeHtml(notification.message || '')}</span>
                        <small>${escapeHtml(notification.created_at || 'Recently')}</small>
                    </span>
                    ${unreadDot}
                </a>
            `;
        }

        function renderNotifications(notifications) {
            if (!Array.isArray(notifications) || notifications.length === 0) {
                list.innerHTML = `
                    <div class="admin-notification__empty" id="adminNotificationEmpty">
                        <i class="far fa-bell-slash" aria-hidden="true"></i>
                        <strong>No notifications yet</strong>
                        <span>New resident requests and incident reports will appear here.</span>
                    </div>
                `;
                return;
            }

            list.innerHTML = notifications.map(notificationItemHtml).join('');
        }

        async function refreshNotifications(showLoading) {
            if (showLoading && refreshButton) {
                refreshButton.classList.add('is-loading');
                refreshButton.disabled = true;
            }

            try {
                const result = await fetchJson(config.feedUrl, {
                    method: 'GET'
                });

                renderNotifications(result.notifications);
                setUnreadCount(result.unread_count);
            } catch (error) {
                console.error(error);
                showErrorToast(error.message);
            } finally {
                if (refreshButton) {
                    refreshButton.classList.remove('is-loading');
                    refreshButton.disabled = false;
                }
            }
        }

        function showToast(notification) {
            if (!toastContainer) {
                return;
            }

            const toast = document.createElement('div');
            toast.className = 'admin-notification-toast';
            toast.innerHTML = `
                <span class="admin-notification-toast__icon" aria-hidden="true">
                    <i class="${escapeHtml(iconFor(notification))}"></i>
                </span>
                <span class="admin-notification-toast__content">
                    <strong>${escapeHtml(notification.title || 'New notification')}</strong>
                    <span>${escapeHtml(notification.message || '')}</span>
                </span>
            `;

            toastContainer.prepend(toast);
            window.setTimeout(function () {
                toast.remove();
            }, 6000);
        }

        function showErrorToast(message) {
            showToast({
                icon: 'fas fa-circle-exclamation',
                title: 'Notification action failed',
                message: message || 'Please refresh the page and try again.'
            });
        }

        toggle.addEventListener('click', function (event) {
            event.stopPropagation();
            const shouldOpen = dropdown.hidden;
            setDropdownOpen(shouldOpen);

            if (shouldOpen) {
                refreshNotifications(false);
            }
        });

        refreshButton?.addEventListener('click', function (event) {
            event.stopPropagation();
            refreshNotifications(true);
        });

        readAllButton?.addEventListener('click', async function (event) {
            event.stopPropagation();

            if (readAllButton.disabled) {
                return;
            }

            readAllButton.disabled = true;

            try {
                const result = await postJson(config.readAllUrl);
                setUnreadCount(result.unread_count);
                await refreshNotifications(false);
            } catch (error) {
                console.error(error);
                showErrorToast(error.message);
                readAllButton.disabled = getUnreadCount() === 0;
            }
        });

        document.addEventListener('click', function (event) {
            if (!root.contains(event.target)) {
                setDropdownOpen(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setDropdownOpen(false);
                toggle.focus();
            }
        });

        if (window.Echo) {
            window.Echo
                .private('admins.' + config.adminId)
                .notification(function (notification) {
                    showToast(notification);
                    refreshNotifications(false);
                });
        }

        window.setInterval(function () {
            refreshNotifications(false);
        }, 30000);

        highlightDestinationRecord();
    });

    function highlightDestinationRecord() {
        const recordId = new URLSearchParams(window.location.search).get('highlight');

        if (!recordId) {
            return;
        }

        const escapedId = window.CSS && CSS.escape
            ? CSS.escape(recordId)
            : recordId.replace(/[^a-zA-Z0-9_-]/g, '');

        const selectors = [
            `[data-record-id="${escapedId}"]`,
            `[data-application-id="${escapedId}"]`,
            `[data-report-id="${escapedId}"]`,
            `[data-id="${escapedId}"]`,
            `#application-row-${escapedId}`,
            `#report-row-${escapedId}`,
            `#record-row-${escapedId}`,
            `#row-${escapedId}`
        ];

        let attempts = 0;
        const timer = window.setInterval(function () {
            attempts += 1;
            const target = document.querySelector(selectors.join(','));

            if (target) {
                window.clearInterval(timer);
                target.classList.add('notification-target-highlight');
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                window.setTimeout(function () {
                    target.classList.remove('notification-target-highlight');
                }, 8000);
            } else if (attempts >= 20) {
                window.clearInterval(timer);
            }
        }, 250);
    }
})();
