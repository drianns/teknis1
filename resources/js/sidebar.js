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

            // Prevent collapse when navigating via link click
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
                if (!this.navigating) {
                    this.expanded = false;
                }
            }, 300);
        },

        autoExpandActiveMenu() {
            const path = window.location.pathname;

            if (path.includes('/apps/')) {
                this.openMenus.apps = true;
            }
            if (path.includes('/master-customer/')) {
                this.openMenus.masterCustomer = true;
            }
            if (path.includes('/channel/')) {
                this.openMenus.channel = true;
            }
            if (path.includes('/channel/email/')) {
                this.openMenus.channelEmail = true;
            }
            if (path.includes('/dashboard-email') || path.includes('/setup-channel-email/') || path.includes('/setting-agent-email')) {
                this.openMenus.setupChannelEmail = true;
            }
            if (path.includes('/setting-email-system/')) {
                this.openMenus.settingEmailSystem = true;
            }
            if (path.includes('/setting-epic-system/')) {
                this.openMenus.settingEpicSystem = true;
            }
            if (path.includes('/data-') || path.includes('/channel-ticket') || path.includes('/department-escalation-unit')) {
                this.openMenus.masterData = true;
            }
            if (path.includes('/setting-agent-call')) {
                this.openMenus.setupChannelCall = true;
            }
            if (path.includes('/monitoring-login')) {
                this.openMenus.dataLogin = true;
            }
            if (path.includes('/recording/')) {
                this.openMenus.recording = true;
            }
            if (path.includes('/report/')) {
                this.openMenus.report = true;
            }
        },

        toggle(menu) {
            this.openMenus[menu] = !this.openMenus[menu];
        }
    }));
});
