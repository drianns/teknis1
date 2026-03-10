@php
    $isActive = fn($route) => request()->routeIs($route);

    $menuGroups = [
        ['type' => 'link', 'route' => 'home', 'label' => 'Dashboard', 'icon' => 'bx-tachometer'],
        [
            'type' => 'group', 'key' => 'apps', 'label' => 'Apps', 'icon' => 'bx-grid-alt',
            'active' => request()->is('apps/*'),
            'items' => [
                ['route' => 'apps.ticketing-department', 'label' => 'Ticketing Department'],
                ['route' => 'apps.taskboard', 'label' => 'Taskboard'],
                ['route' => 'apps.thread-system', 'label' => 'Thread System'],
                ['route' => 'apps.ticketing', 'label' => 'Ticketing'],
                ['route' => 'apps.history-ticketing', 'label' => 'History Ticketing'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'masterData', 'label' => 'Master Data', 'icon' => 'bx-list-ul',
            'active' => request()->is('data-*') || request()->is('channel-ticket*') || request()->is('department-escalation*'),
            'items' => [
                ['route' => 'data-group-name.index', 'label' => 'Group Name'],
                ['route' => 'data-fulfillment-location.index', 'label' => 'Fulfillment Location'],
                ['route' => 'data-type.index', 'label' => 'Data Type'],
                ['route' => 'data-category.index', 'label' => 'Data Category'],
                ['route' => 'data-meta.index', 'label' => 'Data Meta'],
                ['route' => 'data-sub-category.index', 'label' => 'Sub Category'],
                ['route' => 'channel-ticket.index', 'label' => 'Channel Ticket'],
                ['route' => 'department-escalation-unit.index', 'label' => 'Department Unit'],
                ['route' => 'data-source.index', 'label' => 'Data Source'],
                ['route' => 'data-activity.index', 'label' => 'Data Activity'],
                ['route' => 'data-aux-reason.index', 'label' => 'Aux Reason'],
                ['route' => 'data-status-ticket.index', 'label' => 'Status Ticket'],
                ['route' => 'data-group-agent.index', 'label' => 'Group Agent'],
                ['route' => 'data-brand-category.index', 'label' => 'Brand Category'],
                ['route' => 'data-fulfillment.index', 'label' => 'Data Fulfillment'],
                ['route' => 'data-holiday.index', 'label' => 'Data Holiday'],
                ['route' => 'data-brand-name.index', 'label' => 'Brand Name'],
                ['route' => 'data-max-handle.index', 'label' => 'Max Handle'],
                ['route' => 'data-site.index', 'label' => 'Data Site'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'masterCustomer', 'label' => 'Master Customer', 'icon' => 'bx-group',
            'active' => request()->is('master-customer/*'),
            'items' => [
                ['route' => 'master-customer.data-table', 'label' => 'Data Table Customer'],
                ['route' => 'master-customer.data-customer', 'label' => 'Data Customer'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'channel', 'label' => 'Channel', 'icon' => 'bx-plug',
            'active' => request()->is('channel/*'),
            'items' => [
                ['route' => 'channel.email.inbox', 'label' => 'Inbox Email'],
                ['route' => 'channel.email.history', 'label' => 'History Email'],
            ]
        ],
        [
            'type' => 'link', 'route' => 'bantu-dagang', 'label' => 'Bantu Dagang', 'icon' => 'bx-list-ul'
        ],
        [
            'type' => 'link', 'route' => 'file-manager', 'label' => 'File Manager', 'icon' => 'bx-duplicate'
        ],
        [
            'type' => 'group', 'key' => 'recording', 'label' => 'Recording', 'icon' => 'bx-list-ul',
            'active' => request()->is('recording*'),
            'items' => [['route' => 'recording.index', 'label' => 'Voice Recording']]
        ],
        [
            'type' => 'group', 'key' => 'report', 'label' => 'Report', 'icon' => 'bx-duplicate',
            'active' => request()->is('report/*'),
            'items' => [
                ['route' => 'report.statistic-call', 'label' => 'Statistic Call'],
                ['route' => 'report.assign-email', 'label' => 'Assign Email'],
                ['route' => 'report.sl-nespresso', 'label' => 'SL Nespresso'],
                ['route' => 'report.sl-kanmo', 'label' => 'SL Kanmo'],
                ['route' => 'report.base-on-sla', 'label' => 'Base on SLA'],
                ['route' => 'report.base-on-transaction', 'label' => 'Base on Transaction'],
                ['route' => 'report.base-on-staff', 'label' => 'Base on Staff'],
                ['route' => 'report.thread-transaction', 'label' => 'Thread Transaction'],
                ['route' => 'report.interaction-ticket', 'label' => 'Interaction Ticket'],
                ['route' => 'report.agent-aux', 'label' => 'Report AUX'],
                ['route' => 'report.channel-email', 'label' => 'Channel Email'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'mgmtUser', 'label' => 'Management User', 'icon' => 'bx-id-card',
            'active' => request()->routeIs('management-user.*'),
            'items' => [
                ['route' => 'management-user.data-access-application', 'label' => 'Data Access'],
                ['route' => 'management-user.data-user-application', 'label' => 'Data User'],
                ['route' => 'management-user.level-user-application', 'label' => 'Level User'],
                ['route' => 'management-user.export.user.application', 'label' => 'Export User'],
            ]
        ],
        [
            'type' => 'link', 'route' => 'wallboard', 'label' => 'Wallboard', 'icon' => 'bx-tachometer'
        ],
        [
            'type' => 'group', 'key' => 'dataLogin', 'label' => 'Data Login', 'icon' => 'bx-git-merge',
            'active' => request()->routeIs('monitoring.login.*', 'report.login-activity'),
            'items' => [
                ['route' => 'monitoring.login.index', 'label' => 'Monitoring Login'],
                ['route' => 'report.login-activity', 'label' => 'Login Activity'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'settingEmailSys', 'label' => 'Setting Email System', 'icon' => 'bx-duplicate',
            'active' => request()->is('setting-email-system/*'),
            'items' => [
                ['route' => 'setting-email-system.accounts', 'label' => 'Email Account'],
                ['route' => 'setting-email-system.signature', 'label' => 'Email Signature'],
                ['route' => 'setting-email-system.service', 'label' => 'Email Service'],
                ['route' => 'setting-email-system.service-method', 'label' => 'Service Method'],
                ['route' => 'setting-email-system.server-profile', 'label' => 'Server Profile'],
                ['route' => 'setting-email-system.server-protocol', 'label' => 'Service Protocol'],
                ['route' => 'setting-email-system.server-protocol-out', 'label' => 'Protocol Out'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'channelCall', 'label' => 'Setup Channel Call', 'icon' => 'bx-duplicate',
            'active' => request()->routeIs('setting.agent.call'),
            'items' => [['route' => 'setting.agent.call', 'label' => 'Setting Agent Call']]
        ],
        [
            'type' => 'group', 'key' => 'epicSystem', 'label' => 'Setting EPIC System', 'icon' => 'bx-duplicate',
            'active' => request()->is('setting-epic-system/*'),
            'items' => [['route' => 'setting-epic-system.configuration', 'label' => 'Configurasi EPIC']]
        ],
        [
            'type' => 'group', 'key' => 'setupEmail', 'label' => 'Setup Channel Email', 'icon' => 'bx-highlight',
            'active' => request()->routeIs('dashboard.email', 'monitoring.email.response', 'setting.agent.email') || request()->is('setup-channel-email/*'),
            'items' => [
                ['route' => 'setup-channel-email.setting-auto-reply', 'label' => 'Setting Auto Reply Email'],
                ['route' => 'setup-channel-email.template-auto-reply', 'label' => 'Template Auto Reply Email'],
                ['route' => 'setup-channel-email.template-response', 'label' => 'Template Response Email'],
                ['route' => 'setup-channel-email.filter-jumlah-hari', 'label' => 'Data Filter Jumlah Hari'],
                ['route' => 'setup-channel-email.jam-operasional', 'label' => 'Data Jam Operasional Email'],
                ['route' => 'setup-channel-email.incoming-email', 'label' => 'Data Incoming Email'],
                ['route' => 'dashboard.email', 'label' => 'Dashboard Email'],
                ['route' => 'monitoring.email.response', 'label' => 'Monitoring Email Response'],
                ['route' => 'setting.agent.email', 'label' => 'Setting Agent Email'],
                ['route' => 'setup-channel-email.data-signature', 'label' => 'Data Signature'],
                ['route' => 'setup-channel-email.account-corporate', 'label' => 'Account Email Corporate'],
            ]
        ],
        [
            'type' => 'group', 'key' => 'settingApp', 'label' => 'Setting Application', 'icon' => 'bx-list-check',
            'active' => request()->routeIs('menu.application', 'sub.menu.application', 'detail.menu.application', 'ticket.notification.system', 'setting.channel.agent.index'),
            'items' => [
                ['route' => 'menu.application', 'label' => 'Menu Application'],
                ['route' => 'sub.menu.application', 'label' => 'Sub Menu'],
                ['route' => 'detail.menu.application', 'label' => 'Detail Menu'],
                ['route' => 'ticket.notification.system', 'label' => 'Ticket Notification'],
                ['route' => 'setting.channel.agent.index', 'label' => 'Channel Agent'],
            ]
        ],
    ];
@endphp

<div class="fixed inset-y-0 left-0 z-40 w-20 bg-gray-900/60 backdrop-blur-xl border-r border-gray-800 transition-all duration-300 flex flex-col sidebar-mini" id="sidebar">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-800 justify-end">
        <div class="flex items-center">
            <span class="logo-long h-12 w-40 flex-shrink-0 mt-1 ml-2">
                <!-- Fallback to plain text style if sidebar_logo helper is missing -->
                <div class="flex items-center h-full w-full">
                    <span class="text-white font-bold text-xl tracking-wide">KANMO</span><span class="text-blue-500 font-bold text-xl">CRM</span>
                </div>
            </span>
            <span class="logo-short h-8 w-8 flex-shrink-0">
                <div class="h-full w-full bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="bx bx-repost text-white text-xl"></i>
                </div>
            </span>
        </div>
        <button type="button" class="text-gray-400 hover:text-blue-400 transition-colors toggle-button hidden">
            <i class="fa fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto min-h-0 py-4 custom-scrollbar">
        <nav class="px-2">
            <ul class="space-y-1">
                @foreach($menuGroups as $group)
                    @if($group['type'] === 'link')
                        <li class="menu-section">
                            <a href="{{ route($group['route']) }}"
                                onclick="window.location.href='{{ route($group['route']) }}'; return false;"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-800 rounded-lg {{ $isActive($group['route']) ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-600/20' : '' }}">
                                <div class="flex items-center">
                                    <i class='bx {{ $group['icon'] }} mr-3 text-xl'></i>
                                    <span>{{ $group['label'] }}</span>
                                </div>
                            </a>
                        </li>
                    @else
                        <!-- Customer Interaction Menu (Hover to open) -->
                        <li class="menu-section relative">
                            <button type="button" onclick="toggleSubmenu(this)"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-800/80 rounded-lg transition-all {{ ($group['active'] ?? false) ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-600/20 active-btn' : '' }}">
                                <div class="flex items-center pointer-events-none">
                                    <i class='bx {{ $group['icon'] }} mr-3 text-xl'></i>
                                    <span>{{ $group['label'] }}</span>
                                </div>
                                <i class="fas fa-chevron-down text-sm transition-transform pointer-events-none"></i>
                            </button>
                            <ul class="pl-12 mt-1 space-y-1 submenu-container {{ ($group['active'] ?? false) ? 'active-submenu' : '' }}">
                                @foreach($group['items'] as $item)
                                    <li>
                                        <a href="{{ route($item['route']) }}"
                                            onclick="window.location.href='{{ route($item['route']) }}'; return false;"
                                            class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800/80 rounded-lg transition-colors {{ $isActive($item['route']) ? 'text-blue-400 font-medium' : '' }}">
                                            {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>

    <!-- Bottom Menu Section -->
    <div class="border-t border-gray-800 mt-auto">
        <div class="p-4 relative">
            <div class="flex items-center space-x-3">
                <div class="relative flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-700"
                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=6366f1&color=fff&size=40" alt="User Avatar">
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full {{ auth()->check() ? 'bg-green-500' : 'bg-gray-500' }} ring-2 ring-gray-900"></span>
                </div>
                <div class="flex-1 min-w-0 profile-info">
                    <button type="button" class="flex items-center w-full text-left" onclick="toggleProfileMenu()">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white truncate">
                                {{ auth()->user()->name ?? 'User' }}
                            </p>
                            <p class="text-[11px] text-blue-400 truncate font-medium">
                                Role: {{ auth()->user()->role->name ?? 'Agent' }}
                            </p>
                        </div>
                        <i class="fas fa-chevron-up text-gray-400 ml-2 transform transition-transform duration-200" id="profileArrow"></i>
                    </button>
                </div>
            </div>

            <!-- Profile Dropdown Menu -->
            <div id="profileMenu" class="absolute bottom-full left-0 right-0 mb-2 mr-6 ml-6 p-2 bg-gray-800 rounded-lg shadow-lg transform scale-95 opacity-0 pointer-events-none transition-all duration-200">
                <div class="profile-info-block px-2 pt-2">
                    <h6 class="text-sm font-medium text-white mb-0">{{ auth()->user()->name ?? 'User' }}</h6>
                </div>

                <!-- Menu Items -->
                <div class="space-y-1 mt-2">
                    <button type="button" onclick="openAuxModal()" class="w-full flex items-center px-3 py-2.5 text-gray-300 hover:bg-gray-700 hover:text-green-400 rounded-md transition-all duration-200 group">
                        <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-500/20 transition-colors">
                            <i class="fas fa-cog text-green-400 text-sm"></i>
                        </div>
                        <div class="flex-1 text-left">
                            <span class="text-sm font-medium">System AUX</span>
                        </div>
                    </button>

                    <form method="POST" action="#" class="m-0" id="logoutForm">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2.5 text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-md transition-all duration-200 group">
                            <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-500/20 transition-colors">
                                <i class="fas fa-sign-out-alt text-red-400 text-sm"></i>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="text-sm font-medium">Logout</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AUX Modal -->
<div id="auxModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="auxModalContent">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">System AUX</h3>
                </div>
            </div>
            <button type="button" onclick="closeAuxModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="mb-6">
                <label for="auxSelect" class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-list-ul mr-2 text-blue-500"></i>
                    Select AUX Status
                </label>
                <div class="relative">
                    <select id="auxSelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white text-gray-700 font-medium">
                        <option value="" class="text-gray-500">Choose your status...</option>
                        <option value="login" class="py-2">🔓 Login</option>
                        <option value="logout" class="py-2">🔒 Logout</option>
                        <option value="system_aux" class="py-2">System Aux</option>
                        <option value="istirahat" class="py-2">🍴 Istirahat</option>
                        <option value="ready" class="py-2">✅ Ready</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAuxModal()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-all duration-200">
                    Cancel
                </button>
                <button type="button" onclick="submitAuxStatus()" class="flex-1 px-4 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scrollbar styling */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(55, 65, 81, 0.8); border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(59, 130, 246, 0.5); }

    /* Mini sidebar default state */
    .sidebar-mini { width: 80px !important; }
    .sidebar-mini .logo-long { display: none !important; }
    .sidebar-mini .logo-short { display: block !important; }

    .sidebar-mini .logo-text,
    .sidebar-mini .menu-section span:not(.status-indicator),
    .sidebar-mini .submenu-container,
    .sidebar-mini .fa-chevron-down,
    .sidebar-mini .profile-info,
    .sidebar-mini .profile-actions {
        display: none !important;
    }

    .sidebar-mini .menu-section a,
    .sidebar-mini .menu-section button {
        padding: 0.75rem !important;
        justify-content: center !important;
    }

    .sidebar-mini .menu-section i:not(.fa-chevron-down) {
        margin: 0 !important;
        font-size: 1.5rem !important;
    }

    .sidebar-mini .profile-section { padding: 0.75rem !important; }
    .sidebar-mini .profile-section img {
        width: 2.5rem !important;
        height: 2.5rem !important;
        margin: 0 auto !important;
    }

    /* Hover expand effect */
    .sidebar-mini.expanded { width: 288px !important; }
    .sidebar-mini.expanded .logo-long { display: block !important; }
    .sidebar-mini.expanded .logo-short { display: none !important; }

    .sidebar-mini.expanded .logo-text,
    .sidebar-mini.expanded .menu-section span:not(.status-indicator),
    .sidebar-mini.expanded .profile-info,
    .sidebar-mini.expanded .profile-actions {
        display: block !important;
        animation: fadeIn 0.3s ease-in-out forwards;
    }

    .sidebar-mini.expanded .menu-section a,
    .sidebar-mini.expanded .menu-section button {
        padding: 0.75rem 1rem !important;
        justify-content: flex-start !important;
    }

    .sidebar-mini.expanded .menu-section i:not(.fa-chevron-down) {
        margin-right: 0.75rem !important;
        font-size: 1.25rem !important;
    }

    .sidebar-mini.expanded .fa-chevron-down {
        display: block !important;
    }

    /* Submenu magic hover trigger */
    .submenu-container {
        display: none;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease;
    }

    /* Active Submenu state triggered by JS click */
    .sidebar-mini.expanded .submenu-container.active-submenu {
        display: block !important;
        max-height: 2000px;
        opacity: 1;
        margin-bottom: 6px;
        animation: slideDown 0.3s ease-out forwards;
    }
    
    .sidebar-mini.expanded .menu-section > button.active-btn {
        background-color: rgba(59, 130, 246, 0.05); /* very light blue hint */
    }

    .sidebar-mini.expanded .menu-section > button.active-btn .fa-chevron-down {
        transform: rotate(180deg);
        color: #60a5fa;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Hover effect for mini menu items */
    .sidebar-mini .menu-section button:hover,
    .sidebar-mini .menu-section > a:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
        transform: translateX(4px);
    }

    /* Transitions */
    .menu-section a, .menu-section button {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<script>
    function toggleSubmenu(button, forceOpen = false) {
        const submenu = button.nextElementSibling;
        const isActive = submenu.classList.contains('active-submenu');
        
        if (forceOpen && isActive) return; // already open, do nothing on hover
        
        // Close all other submenus (accordion style)
        document.querySelectorAll('.submenu-container.active-submenu').forEach(el => {
            el.classList.remove('active-submenu');
            el.previousElementSibling.classList.remove('active-btn');
        });

        // Toggle the clicked one, or open if hovered
        if (!isActive || forceOpen) {
            submenu.classList.add('active-submenu');
            button.classList.add('active-btn');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            // Use JS mouse events instead of CSS :hover to prevent touch device double-tap bugs
            sidebar.addEventListener('mouseenter', function() {
                sidebar.classList.add('expanded');
            });

            sidebar.addEventListener('mouseleave', function() {
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
        }
    });

    function toggleProfileMenu() {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar.classList.contains('expanded')) return;

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
    }

    function openAuxModal() {
        const modal = document.getElementById('auxModal');
        const modalContent = document.getElementById('auxModalContent');
        
        if (!modal || !modalContent) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Close profile menu
        const profileMenu = document.getElementById('profileMenu');
        if (profileMenu) {
            profileMenu.classList.add('pointer-events-none', 'scale-95', 'opacity-0');
            profileMenu.classList.remove('scale-100', 'opacity-100');
        }
    }

    function closeAuxModal() {
        const modal = document.getElementById('auxModal');
        const modalContent = document.getElementById('auxModalContent');
        
        if (!modal || !modalContent) return;
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
    
    function submitAuxStatus() {
        closeAuxModal();
        // Here you can integrate your actual api payload as needed.
    }
</script>
