// =====================================================
// Sidebar Module — extracted from sidebar.blade.php
// =====================================================

// ----- DOM-based sidebar functions -----

/**
 * Toggle a submenu open/closed (accordion style).
 * Called via onclick="toggleSubmenu(this)" on <button> elements.
 */
window.toggleSubmenu = function(button, forceOpen = false) {
    const submenu = button.nextElementSibling;
    const isActive = submenu.classList.contains('active-submenu');

    if (forceOpen && isActive) return;

    // Close all other submenus (accordion style)
    document.querySelectorAll('.submenu-container.active-submenu').forEach(el => {
        el.classList.remove('active-submenu');
        el.previousElementSibling.classList.remove('active-btn');
    });

    if (!isActive || forceOpen) {
        submenu.classList.add('active-submenu');
        button.classList.add('active-btn');
    }
};

/**
 * Toggle the profile dropdown menu.
 */
window.toggleProfileMenu = function() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar || !sidebar.classList.contains('expanded')) return;

    const menu = document.getElementById('profileMenu');
    const arrow = document.getElementById('profileArrow');
    const isHidden = menu.classList.contains('pointer-events-none');

    if (isHidden) {
        menu.classList.remove('pointer-events-none', 'scale-95', 'opacity-0');
        menu.classList.add('scale-100', 'opacity-100');
        arrow.classList.add('rotate-180');
    } else {
        menu.classList.add('pointer-events-none', 'scale-95', 'opacity-0');
        menu.classList.remove('scale-100', 'opacity-100');
        arrow.classList.remove('rotate-180');
    }
};

/**
 * Open the AUX Status modal.
 */
window.openAuxModal = function() {
    const modal = document.getElementById('auxModal');
    const modalContent = document.getElementById('auxModalContent');

    if (!modal || !modalContent) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Close profile menu while opening AUX modal
    const profileMenu = document.getElementById('profileMenu');
    if (profileMenu) {
        profileMenu.classList.add('pointer-events-none', 'scale-95', 'opacity-0');
        profileMenu.classList.remove('scale-100', 'opacity-100');
    }
};

/**
 * Close the AUX Status modal.
 */
window.closeAuxModal = function() {
    const modal = document.getElementById('auxModal');
    const modalContent = document.getElementById('auxModalContent');

    if (!modal || !modalContent) return;

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

/**
 * Submit AUX status (hook in actual API call here).
 */
window.submitAuxStatus = function() {
    closeAuxModal();
};

// ----- Sidebar hover expand / collapse -----
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    sidebar.addEventListener('mouseenter', function () {
        sidebar.classList.add('expanded');
    });

    sidebar.addEventListener('mouseleave', function () {
        sidebar.classList.remove('expanded');

        // Close profile menu when mouse leaves sidebar
        const profileMenu = document.getElementById('profileMenu');
        const arrow = document.getElementById('profileArrow');
        if (profileMenu && arrow) {
            profileMenu.classList.add('pointer-events-none', 'scale-95', 'opacity-0');
            profileMenu.classList.remove('scale-100', 'opacity-100');
            arrow.classList.remove('rotate-180');
        }
    });
});

// ----- Alpine.js sidebar data component -----
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        expanded: false,
        collapseTimer: null,
        navigating: false,
        openMenus: {
            apps: false,
            masterCustomer: false,
            channel: false,
            channelEmail: false,
            setupChannelEmail: false,
            settingEmailSystem: false,
            settingEpicSystem: false,
            masterData: false,
            setupChannelCall: false,
            dataLogin: false,
            recording: false,
            report: false,
            setupManagementUser: false,
            settingApplication: false
        },

        init() {
            this.autoExpandActiveMenu();
            this.$watch('expanded', (value) => {
                if (value) this.autoExpandActiveMenu();
            });
            this.$el.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (link && link.href && !link.href.endsWith('#')) {
                    this.navigating = true;
                    clearTimeout(this.collapseTimer);
                }
            });
        },

        expand() {
            clearTimeout(this.collapseTimer);
            this.expanded = true;
        },

        collapse() {
            if (this.navigating) return;
            this.collapseTimer = setTimeout(() => {
                if (!this.navigating) this.expanded = false;
            }, 300);
        },

        autoExpandActiveMenu() {
            const path = window.location.pathname;
            if (path.includes('/apps/'))                    this.openMenus.apps = true;
            if (path.includes('/master-customer/'))         this.openMenus.masterCustomer = true;
            if (path.includes('/channel/'))                 this.openMenus.channel = true;
            if (path.includes('/channel/email/'))           this.openMenus.channelEmail = true;
            if (path.includes('/dashboard-email') || path.includes('/setup-channel-email/') || path.includes('/setting-agent-email'))
                                                            this.openMenus.setupChannelEmail = true;
            if (path.includes('/setting-email-system/'))    this.openMenus.settingEmailSystem = true;
            if (path.includes('/setting-epic-system/'))     this.openMenus.settingEpicSystem = true;
            if (path.includes('/data-') || path.includes('/channel-ticket') || path.includes('/department-escalation-unit'))
                                                            this.openMenus.masterData = true;
            if (path.includes('/setting-agent-call'))       this.openMenus.setupChannelCall = true;
            if (path.includes('/monitoring-login'))         this.openMenus.dataLogin = true;
            if (path.includes('/recording/'))               this.openMenus.recording = true;
            if (path.includes('/report/'))                  this.openMenus.report = true;
        },

        toggle(menu) {
            this.openMenus[menu] = !this.openMenus[menu];
        }
    }));
});

