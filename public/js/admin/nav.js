// ========== SIDEBAR COLLAPSE FUNCTIONALITY ==========
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const collapseBtn = document.getElementById('collapseBtn');
const overlay = document.getElementById('sidebarOverlay');
const isMobile = () => window.innerWidth <= 992;

// Check localStorage for sidebar state on page load
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

// Function to toggle sidebar collapse (desktop only)
function toggleSidebarCollapse() {

    if (isMobile()) return;

    const wasCollapsed = sidebar.classList.contains('collapsed');

    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');

    if (!wasCollapsed) {
        // When collapsing → only hide submenus visually
        document.querySelectorAll('.submenu.show').forEach(menu => {
            menu.classList.add('temp-hidden');
            menu.classList.remove('show');
        });
    } else {
        // When expanding again → restore previous open dropdowns
        document.querySelectorAll('.dropdown-btn.active').forEach(btn => {
            const submenu = btn.nextElementSibling;
            if (submenu) {
                submenu.classList.add('show');
            }
        });
    }

    // Toggle icon
    if (sidebar.classList.contains('collapsed')) {
        collapseBtn.classList.replace('fa-chevron-left','fa-chevron-right');
        collapseBtn.title = "Expand sidebar";
    } else {
        collapseBtn.classList.replace('fa-chevron-right','fa-chevron-left');
        collapseBtn.title = "Minimize sidebar";
    }

    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Handle toggle button click (works on both mobile and desktop)
function handleToggleButtonClick(event) {
    event.preventDefault();
    event.stopPropagation();
    
    if (isMobile()) {
        // On mobile, close the sidebar
        closeMobileSidebar();
    } else {
        // On desktop, toggle collapse
        toggleSidebarCollapse();
    }
}

// Handle click on sidebar to expand when collapsed (desktop only)
function handleSidebarClick(event) {
    if (!isMobile() && sidebar.classList.contains('collapsed')) {
        if (!event.target.closest('.toggle-btn')) {
            toggleSidebarCollapse();
        }
    }
}

// Handle link clicks
function handleLinkClick(event, element) {
    event.preventDefault();
    event.stopPropagation();
    
    if (!isMobile() && sidebar.classList.contains('collapsed')) {
        toggleSidebarCollapse();
        return;
    }
    
    // On mobile, close sidebar after clicking
    if (isMobile()) {
        closeMobileSidebar();
    }
    
    // Add your navigation logic here
    console.log('Navigate to:', element.querySelector('span').textContent);
}

// Handle dropdown clicks
function handleDropdownClick(event, element) {
    event.preventDefault();
    event.stopPropagation();

    const submenu = element.nextElementSibling;

    // If sidebar is collapsed (desktop)
    if (!isMobile() && sidebar.classList.contains('collapsed')) {

        toggleSidebarCollapse();

        setTimeout(() => {
            openSingleDropdown(element, submenu);
        }, 300);

        return;
    }

    // Normal behavior (ONLY ONE OPEN)
    openSingleDropdown(element, submenu);
}

function openSingleDropdown(element, submenu) {

    // // Close all other dropdowns
    // document.querySelectorAll('.dropdown-btn').forEach(btn => {
    //     if (btn !== element) btn.classList.remove('active');
    // });

    // document.querySelectorAll('.submenu').forEach(menu => {
    //     if (menu !== submenu) menu.classList.remove('show');
    // });

    // Toggle current dropdown
    element.classList.toggle('active');

    if (submenu && submenu.classList.contains('submenu')) {
        submenu.classList.toggle('show');
    }
}

// Handle submenu link clicks
// function handleSubmenuClick(event) {
//     event.preventDefault();
//     event.stopPropagation();
    
//     // On mobile, close sidebar after clicking
//     if (isMobile()) {
//         closeMobileSidebar();
//     }
    
//     // Add your navigation logic here
//     console.log('Navigate to:', event.currentTarget.querySelector('span').textContent);
// }

// ========== MOBILE SIDEBAR TOGGLE ==========
function toggleMobileSidebar() {
    if (!isMobile()) return;
    
    sidebar.classList.toggle('show');
    if (sidebar.classList.contains('show')) {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        // Change chevron icon to indicate close
        collapseBtn.classList.remove('fa-chevron-left');
        collapseBtn.classList.add('fa-chevron-left');
        collapseBtn.title = "Close sidebar";
    } else {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
        // Restore chevron icon
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
    // Restore chevron icon
    collapseBtn.classList.remove('fa-times');
    collapseBtn.classList.add('fa-chevron-left');
    collapseBtn.title = "Close sidebar";
}

// Handle window resize
window.addEventListener('resize', function() {
    if (isMobile()) {
        // On mobile, ensure sidebar is not in collapsed state
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
        collapseBtn.classList.remove('fa-chevron-right');
        collapseBtn.classList.add('fa-chevron-left');
        collapseBtn.title = "Close sidebar";
        
        // Close sidebar if open
        closeMobileSidebar();
    } else {
        // On desktop, restore collapsed state from localStorage
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
