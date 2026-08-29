const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const collapseBtn = document.getElementById('collapseBtn');
const overlay = document.getElementById('sidebarOverlay');
const isMobile = () => window.innerWidth <= 992;

document.addEventListener('DOMContentLoaded', function() {
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed && !isMobile()) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
        collapseBtn.classList.remove('fa-chevron-left');
        collapseBtn.classList.add('fa-chevron-right');
        collapseBtn.title = "Expand sidebar";
    }
});

function toggleSidebarCollapse() {

    if (isMobile()) return;

    const wasCollapsed = sidebar.classList.contains('collapsed');

    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');

    if (!wasCollapsed) {

        document.querySelectorAll('.submenu.show').forEach(menu => {
            menu.classList.add('temp-hidden');
            menu.classList.remove('show');
        });
    } else {

        document.querySelectorAll('.dropdown-btn.active').forEach(btn => {
            const submenu = btn.nextElementSibling;
            if (submenu) {
                submenu.classList.add('show');
            }
        });
    }

    if (sidebar.classList.contains('collapsed')) {
        collapseBtn.classList.replace('fa-chevron-left','fa-chevron-right');
        collapseBtn.title = "Expand sidebar";
    } else {
        collapseBtn.classList.replace('fa-chevron-right','fa-chevron-left');
        collapseBtn.title = "Minimize sidebar";
    }

    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

function handleToggleButtonClick(event) {
    event.preventDefault();
    event.stopPropagation();

    if (isMobile()) {

        closeMobileSidebar();
    } else {

        toggleSidebarCollapse();
    }
}

function handleSidebarClick(event) {
    if (!isMobile() && sidebar.classList.contains('collapsed')) {
        if (!event.target.closest('.toggle-btn')) {
            toggleSidebarCollapse();
        }
    }
}

function handleLinkClick(event, element) {
    event.preventDefault();

    const href = element.getAttribute('href');

    if (href && href !== '#') {

        window.location.href = href;
    }

    document.querySelectorAll('.sidebar a').forEach(link => {
        link.classList.remove('active');
    });
    element.classList.add('active');

    if (window.innerWidth <= 768) {
        closeMobileSidebar();
    }
}

function handleDropdownClick(event, element) {
    event.preventDefault();
    event.stopPropagation();

    const submenu = element.nextElementSibling;

    if (!isMobile() && sidebar.classList.contains('collapsed')) {

        toggleSidebarCollapse();

        setTimeout(() => {
            openSingleDropdown(element, submenu);
        }, 300);

        return;
    }

    openSingleDropdown(element, submenu);
}

function openSingleDropdown(element, submenu) {

    element.classList.toggle('active');

    if (submenu && submenu.classList.contains('submenu')) {
        submenu.classList.toggle('show');
    }
}

function toggleMobileSidebar() {
    if (!isMobile()) return;

    sidebar.classList.toggle('show');
    if (sidebar.classList.contains('show')) {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';

        collapseBtn.classList.remove('fa-chevron-left');
        collapseBtn.classList.add('fa-chevron-left');
        collapseBtn.title = "Close sidebar";
    } else {
        overlay.classList.remove('show');
        document.body.style.overflow = '';

        collapseBtn.classList.remove('fa-chevron-left');
        collapseBtn.classList.add('fa-chevron-left');
        collapseBtn.title = "Close sidebar";
    }
}

function closeMobileSidebar() {
    if (!isMobile()) return;

    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    document.body.style.overflow = '';

    collapseBtn.classList.remove('fa-times');
    collapseBtn.classList.add('fa-chevron-left');
    collapseBtn.title = "Close sidebar";
}

window.addEventListener('resize', function() {
    if (isMobile()) {

        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
        collapseBtn.classList.remove('fa-chevron-right');
        collapseBtn.classList.add('fa-chevron-left');
        collapseBtn.title = "Close sidebar";

        closeMobileSidebar();
    } else {

        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            collapseBtn.classList.remove('fa-chevron-left');
            collapseBtn.classList.add('fa-chevron-right');
            collapseBtn.title = "Expand sidebar";
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            collapseBtn.classList.remove('fa-chevron-right');
            collapseBtn.classList.add('fa-chevron-left');
            collapseBtn.title = "Minimize sidebar";
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const AUTO_SYNC_INTERVAL_MS = 10000;
    const AUTO_SYNC_REQUEST_TIMEOUT_MS = 6000;

    function hasListTable(doc) {
        return !!doc.querySelector('table tbody');
    }

    function isUserBusy() {
        if (document.hidden) return true;
        if (document.querySelector('.modal.show')) return true;
        if (document.querySelector('.dropdown-menu.show')) return true;

        const active = document.activeElement;
        if (active && ['INPUT', 'TEXTAREA', 'SELECT'].includes(active.tagName)) {
            return true;
        }

        if (document.querySelector('tbody input[type="checkbox"]:checked')) {
            return true;
        }

        return false;
    }

    function buildSignature(doc) {
        const tbody = doc.querySelector('table tbody');
        if (!tbody) return '';

        const rows = Array.from(tbody.querySelectorAll('tr')).slice(0, 30).map(function(row) {
            return (row.textContent || '').replace(/\s+/g, ' ').trim();
        });

        const pagination = (doc.querySelector('.pagination')?.textContent || '')
            .replace(/\s+/g, ' ')
            .trim();

        return rows.join('|') + '||' + pagination;
    }

    if (!hasListTable(document)) {
        return;
    }

    let currentSignature = buildSignature(document);
    let isSyncRunning = false;

    setInterval(async function() {
        if (isSyncRunning || isUserBusy()) {
            return;
        }

        isSyncRunning = true;

        const controller = new AbortController();
        const timeoutId = setTimeout(function() {
            controller.abort();
        }, AUTO_SYNC_REQUEST_TIMEOUT_MS);

        try {
            const response = await fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Auto-Sync': '1',
                    'Cache-Control': 'no-cache'
                },
                signal: controller.signal
            });

            if (!response.ok) {
                return;
            }

            const html = await response.text();
            const parser = new DOMParser();
            const nextDoc = parser.parseFromString(html, 'text/html');

            if (!hasListTable(nextDoc)) {
                return;
            }

            const nextSignature = buildSignature(nextDoc);
            if (nextSignature && nextSignature !== currentSignature) {
                window.location.reload();
                return;
            }

            currentSignature = nextSignature;
        } catch (error) {

        } finally {
            clearTimeout(timeoutId);
            isSyncRunning = false;
        }
    }, AUTO_SYNC_INTERVAL_MS);
});

document.addEventListener('DOMContentLoaded', function () {
    overlay?.addEventListener('click', closeMobileSidebar);
    sidebar?.addEventListener('click', handleSidebarClick);
    collapseBtn?.addEventListener('click', handleToggleButtonClick);

    document.querySelectorAll('.mobile-menu-btn').forEach(function (button) {
        button.addEventListener('click', toggleMobileSidebar);
    });

    document.querySelectorAll('.sidebar .dropdown-btn').forEach(function (button) {
        button.addEventListener('click', function (event) {
            handleDropdownClick(event, button);
        });
    });

    document.querySelectorAll('.sidebar a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (isMobile()) {
                closeMobileSidebar();
            }
        });
    });
});
