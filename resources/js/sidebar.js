document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        expanded: false,
        openMenus: {
            apps: false,
            masterCustomer: false,
            channel: false,
            channelEmail: false,
            setupChannelEmail: false
        },

        init() {
            this.autoExpandActiveMenu();

            this.$watch('expanded', (value) => {
                if (value) this.autoExpandActiveMenu();
            });

            // Attempt 3: Manual Navigation Bypass (Capture Phase)
            // Using capture phase (true) ensures we catch the click before other scripts can preventDefault
            this.$el.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link && link.href && !link.href.startsWith('javascript:') && !link.getAttribute('target')) {
                    // Only handle internal sidebar links that aren't javascript triggers
                    const sidebar = document.getElementById('main-sidebar');
                    if (sidebar.contains(link)) {
                        // Force navigation immediately
                        window.location.href = link.href;
                    }
                }
            }, true);
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
            if (path.includes('/dashboard-email') || path.includes('/setup-channel-email/')) {
                this.openMenus.setupChannelEmail = true;
            }
        },

        toggle(menu) {
            this.openMenus[menu] = !this.openMenus[menu];
        }
    }));
});
