import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        // Disabling CSS minification to bypass a cryptic parse error (column 60)
        // encountered during large multi-entry production builds.
        // Tailwind JIT already produces optimized output.
        cssMinify: false,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                }
            }
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',

                // Channel Email
                'resources/js/pages/channel/email/history.js',
                'resources/js/pages/channel/email/inbox.js',

                // Apps
                'resources/js/pages/apps/history-ticketing.js',
                'resources/js/pages/apps/journey.js',
                'resources/js/pages/apps/taskboard.js',
                'resources/js/pages/apps/thread-transaction.js',
                'resources/js/pages/apps/ticketing-department.js',
                'resources/js/pages/apps/ticketing-system.js',

                // Master Customer
                'resources/js/pages/master-customer/data-table-customer.js',

                // Master Data
                'resources/js/pages/master-data/data-activity.js',
                'resources/js/pages/master-data/data-aux-reason.js',
                'resources/js/pages/master-data/data-brand-category.js',
                'resources/js/pages/master-data/data-brand-name.js',
                'resources/js/pages/master-data/data-category.js',
                'resources/js/pages/master-data/data-fulfillment-location.js',
                'resources/js/pages/master-data/data-fulfillment.js',
                'resources/js/pages/master-data/data-group-agent.js',
                'resources/js/pages/master-data/data-group-name.js',
                'resources/js/pages/master-data/data-holiday.js',
                'resources/js/pages/master-data/data-max-handle.js',
                'resources/js/pages/master-data/data-meta.js',
                'resources/js/pages/master-data/data-site.js',
                'resources/js/pages/master-data/data-source.js',
                'resources/js/pages/master-data/data-status-ticket.js',
                'resources/js/pages/master-data/data-sub-category.js',
                'resources/js/pages/master-data/data-type.js',

                // Reports
                'resources/js/pages/report/audit-email.js',
                'resources/js/pages/report/audit-recording.js',
                'resources/js/pages/report/audit-ticket.js',
                'resources/js/pages/report/export-excel-audit.js',
                'resources/js/pages/report/statistic-call.js',
                'resources/js/pages/report/statistic-ticket.js',

                // Setting Application
                'resources/js/pages/setting-application/admin-menu-application.js',
                'resources/js/pages/setting-application/detail-menu-application.js',
                'resources/js/pages/setting-application/setting-channel-agent.js',
                'resources/js/pages/setting-application/sub-menu-application.js',
                'resources/js/pages/setting-application/ticket-notification-system.js',

                // Setup Channel Email
                'resources/js/pages/setup-channel-email/setting-agent-email.js',
                'resources/js/pages/setup-channel-email/setting-auto-reply.js',
                'resources/js/pages/setup-channel-email/setting-email-signature.js',
                'resources/js/pages/setup-channel-email/setting-forwarding.js',
                'resources/js/pages/setup-channel-email/setting-holiday.js',
                'resources/js/pages/setup-channel-email/setting-signature.js',
                'resources/js/pages/setup-channel-email/setting-smtp.js',
                'resources/js/pages/setup-channel-email/setting-template.js',
                'resources/js/pages/setup-channel-email/setting-treatment.js',
                'resources/js/pages/setup-channel-email/setting-working-hours.js',
            ],
            refresh: true,
        }),
    ],
});
