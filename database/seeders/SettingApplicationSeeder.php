<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketNotificationSetting;
use App\Models\MenuApplication;
use App\Models\SubMenuApplication;
use App\Models\DetailMenuApplication;
use Illuminate\Support\Facades\DB;

class SettingApplicationSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ticket Notification Settings ──────────────────────────────
        $settings = ['ticket_create', 'ticket_over_sla', 'ticket_closed', 'ticket_escalation'];
        foreach ($settings as $name) {
            TicketNotificationSetting::firstOrCreate(['name' => $name], ['is_active' => true]);
        }

        // ── Menu Application ──────────────────────────────────────────
        // Skip if already seeded
        if (MenuApplication::count() > 0) return;

        $menus = [
            ['menu_name' => 'Home',               'number' => 1,  'url' => 'dashboard',      'icon' => 'bx-home-circle',            'type' => 'No'],
            ['menu_name' => 'Messages',            'number' => 2,  'url' => '#',              'icon' => 'bx-message-square-detail',  'type' => 'Yes'],
            ['menu_name' => 'Apps',                'number' => 3,  'url' => '#',              'icon' => 'bx-grid-alt',               'type' => 'Yes'],
            ['menu_name' => 'Recording',           'number' => 4,  'url' => '#',              'icon' => 'bx-microphone',             'type' => 'Yes'],
            ['menu_name' => 'Report',              'number' => 5,  'url' => '#',              'icon' => 'bx-bar-chart-alt-2',        'type' => 'Yes'],
            ['menu_name' => 'Master Customer',     'number' => 6,  'url' => '#',              'icon' => 'bx-user-pin',               'type' => 'Yes'],
            ['menu_name' => 'Channel',             'number' => 7,  'url' => '#',              'icon' => 'bx-wifi',                   'type' => 'Yes'],
            ['menu_name' => 'Setup Channel Email', 'number' => 8,  'url' => '#',              'icon' => 'bx-mail-send',              'type' => 'Yes'],
            ['menu_name' => 'Setting Email System','number' => 9,  'url' => '#',              'icon' => 'bx-envelope-open',          'type' => 'Yes'],
            ['menu_name' => 'Setting EPIC System', 'number' => 10, 'url' => '#',              'icon' => 'bx-server',                 'type' => 'Yes'],
            ['menu_name' => 'Master Data',         'number' => 11, 'url' => '#',              'icon' => 'bx-database',               'type' => 'Yes'],
            ['menu_name' => 'Setup Channel Call',  'number' => 12, 'url' => '#',              'icon' => 'bx-phone',                  'type' => 'Yes'],
            ['menu_name' => 'Data Login',          'number' => 13, 'url' => '#',              'icon' => 'bx-log-in-circle',          'type' => 'Yes'],
            ['menu_name' => 'Management User',     'number' => 14, 'url' => '#',              'icon' => 'bx-user-circle',            'type' => 'Yes'],
            ['menu_name' => 'Setting Application', 'number' => 15, 'url' => '#',              'icon' => 'bx-cog',                    'type' => 'Yes'],
        ];

        foreach ($menus as $menu) {
            MenuApplication::create($menu);
        }

        // ── Sub Menu Application ───────────────────────────────────────
        $subMenus = [
            // Apps
            ['menu_name' => 'Apps', 'sub_menu_name' => 'Ticketing Department', 'url' => 'apps/ticketing-department', 'type' => 'No'],
            ['menu_name' => 'Apps', 'sub_menu_name' => 'Taskboard',            'url' => 'apps/taskboard',            'type' => 'No'],
            ['menu_name' => 'Apps', 'sub_menu_name' => 'Thread System',        'url' => 'apps/thread-system',        'type' => 'No'],
            ['menu_name' => 'Apps', 'sub_menu_name' => 'Ticketing',            'url' => 'apps/ticketing',            'type' => 'No'],
            ['menu_name' => 'Apps', 'sub_menu_name' => 'History Ticketing',    'url' => 'apps/history-ticketing',    'type' => 'No'],
            // Report
            ['menu_name' => 'Report', 'sub_menu_name' => 'Statistic Call',       'url' => 'reports/statistic-call',      'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Assign Email',          'url' => 'reports/assign-email',        'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Base On SLA',           'url' => 'reports/base-on-sla',         'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Base On Transaction',   'url' => 'reports/base-on-transaction', 'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Base On Staff',         'url' => 'reports/base-on-staff',       'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Thread Transaction',    'url' => 'reports/thread-transaction',  'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Interaction Ticket',    'url' => 'reports/interaction-ticket',  'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Agent Aux',             'url' => 'reports/agent-aux',           'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Channel Email',         'url' => 'reports/channel-email',       'type' => 'No'],
            ['menu_name' => 'Report', 'sub_menu_name' => 'Login Activity',        'url' => 'reports/login-activity',      'type' => 'No'],
            // Master Customer
            ['menu_name' => 'Master Customer', 'sub_menu_name' => 'Data Table Customer', 'url' => 'master-customer/data-table', 'type' => 'No'],
            ['menu_name' => 'Master Customer', 'sub_menu_name' => 'Data Customer',       'url' => 'master-customer/data-customer', 'type' => 'No'],
            // Management User
            ['menu_name' => 'Management User', 'sub_menu_name' => 'Data User Application',   'url' => 'management-user/data-user-application',  'type' => 'No'],
            ['menu_name' => 'Management User', 'sub_menu_name' => 'Data Access Application', 'url' => 'management-user/data-access-application', 'type' => 'No'],
            ['menu_name' => 'Management User', 'sub_menu_name' => 'Level User Application',  'url' => 'management-user/level-user-application',  'type' => 'No'],
            ['menu_name' => 'Management User', 'sub_menu_name' => 'Export User Application', 'url' => 'management-user/export-user-application', 'type' => 'No'],
            // Setting Application
            ['menu_name' => 'Setting Application', 'sub_menu_name' => 'Menu Application',            'url' => 'setting-application/menu-application',            'type' => 'No'],
            ['menu_name' => 'Setting Application', 'sub_menu_name' => 'Sub Menu Application',        'url' => 'setting-application/sub-menu-application',        'type' => 'No'],
            ['menu_name' => 'Setting Application', 'sub_menu_name' => 'Detail Menu Application',     'url' => 'setting-application/detail-menu-application',     'type' => 'No'],
            ['menu_name' => 'Setting Application', 'sub_menu_name' => 'Ticket Notification System',  'url' => 'setting-application/ticket-notification-system',  'type' => 'No'],
            ['menu_name' => 'Setting Application', 'sub_menu_name' => 'Setting Channel Agent',       'url' => 'setting-application/setting-channel-agent',       'type' => 'No'],
        ];

        foreach ($subMenus as $sm) {
            SubMenuApplication::create($sm);
        }
    }
}
