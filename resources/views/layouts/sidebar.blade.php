@php
    $isActive = fn($route) => request()->routeIs($route);
    $activePath = request()->path();
@endphp

<aside x-data="sidebar()" @mouseenter="expanded = true" @mouseleave="expanded = false" @click.stop=""
    class="fixed left-0 top-0 h-screen flex transition-all duration-300 z-50 bg-gray-900 border-r border-gray-800 rounded-r-[20px] overflow-visible group"
    :class="expanded ? 'w-[280px] sm:w-[300px] shadow-2xl' : 'w-[60px] shadow-lg'" id="main-sidebar">
    <!-- Left Icon Bar (Always Visible Strip) -->
    <div
        class="w-[60px] bg-gray-800/50 flex flex-col items-center py-6 gap-6 border-r border-gray-800 h-full flex-shrink-0 relative z-40">
        <div class="text-blue-500 transition-transform hover:scale-110 mb-2 cursor-pointer" title="Kanmo Center">
            <i class="bx bx-sparkles text-2xl"></i>
        </div>
        <a href="{{ route('home') }}" class="icon-nav-item {{ $isActive('home') ? 'text-blue-500' : 'text-gray-400' }}"
            data-tooltip="Dashboard">
            <i class="bx bx-home text-2xl"></i>
        </a>
        <a href="{{ route('channel.email.inbox') }}"
            class="icon-nav-item {{ $isActive('channel.email.inbox') ? 'text-blue-500' : 'text-gray-400' }}"
            data-tooltip="Messages">
            <i class="bx bx-message-square-detail text-2xl"></i>
        </a>
        <a href="{{ route('apps.taskboard') }}"
            class="icon-nav-item {{ request()->is('apps/*') ? 'text-blue-500' : 'text-gray-400' }}" data-tooltip="Apps">
            <i class="bx bx-grid-alt text-2xl"></i>
        </a>
        <a href="{{ route('apps.thread-system') }}"
            class="icon-nav-item {{ $isActive('apps.thread-system') ? 'text-blue-500' : 'text-gray-400' }}"
            data-tooltip="Threads">
            <i class="bx bx-clipboard text-2xl"></i>
        </a>
        <a href="{{ route('master-customer.data-customer') }}"
            class="icon-nav-item {{ request()->is('master-customer/*') ? 'text-blue-500' : 'text-gray-400' }}"
            data-tooltip="Master Customer">
            <i class="bx bx-user text-2xl"></i>
        </a>
        <a href="{{ route('dashboard.email') }}"
            class="icon-nav-item {{ $isActive('dashboard.email') ? 'text-blue-500' : 'text-gray-400' }}"
            data-tooltip="Setup Channel Email">
            <i class="bx bx-cog text-2xl"></i>
        </a>
    </div>

    <!-- Main Menu Content (Slide/Fade in on hover) -->
    <div class="flex-1 flex flex-col transition-all duration-300 h-full overflow-hidden z-30"
        :class="expanded ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-10 pointer-events-none'">
        <!-- Header -->
        <div class="flex items-center gap-2 p-5 text-white font-semibold text-lg mb-2 whitespace-nowrap">
            <i class="bx bx-sparkles text-blue-500"></i>
            <span>Menu</span>
        </div>

        <!-- Navigation List -->
        <nav class="flex-1 px-4 overflow-y-auto space-y-1 pb-6 sidebar-scroll custom-scrollbar">

            <!-- Home -->
            <a href="{{ route('home') }}"
                class="menu-item group {{ $isActive('home') ? 'menu-item-active' : 'menu-item-default' }}">
                <i class="bx bx-home text-lg"></i>
                <span class="transition-opacity duration-200"
                    :class="expanded ? 'opacity-100' : 'opacity-0'">Home</span>
            </a>

            <!-- Messages -->
            <a href="{{ route('channel.email.inbox') }}"
                class="menu-item group {{ $isActive('channel.email.inbox') ? 'menu-item-active' : 'menu-item-default' }}">
                <i class="bx bx-message-square-detail text-lg"></i>
                <span class="transition-opacity duration-200"
                    :class="expanded ? 'opacity-100' : 'opacity-0'">Messages</span>
                <span x-show="expanded"
                    class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-red-500 text-white rounded-full text-[11px] font-semibold">2</span>
            </a>

            <!-- Apps Dropdown -->
            <div class="space-y-1">
                <button @click.stop="toggle('apps')"
                    class="menu-item w-full group {{ request()->is('apps/*') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-grid-alt text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 'opacity-0'">Apps</span>
                    <i x-show="expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200"
                        :class="openMenus.apps ? 'rotate-180' : ''"></i>
                </button>
                <ul x-show="openMenus.apps && expanded" x-collapse class="pl-8 space-y-1">
                    @foreach([
                            ['route' => 'apps.ticketing-department', 'label' => 'Ticketing Department'],
                            ['route' => 'apps.taskboard', 'label' => 'Taskboard'],
                            ['route' => 'apps.thread-system', 'label' => 'Thread System'],
                            ['route' => 'apps.ticketing', 'label' => 'Ticketing'],
                            ['route' => 'apps.history-ticketing', 'label' => 'History Ticketing']
                        ] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="submenu-item {{ $isActive($item['route']) ? 'submenu-active' : 'submenu-default' }}">
                                <i class="bx {{ $isActive($item['route']) ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
            </ul>
            </div>

            <!-- Master Customer Dropdown -->

            <div class="space-y-1">
                <button @click.stop="toggle('masterCustomer')" 
                        class="menu-item w-full group {{ request()->is('master-customer/*') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-user text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 
              '             opacity-0'">Master Customer</span>
                    <i x-show=
         "                      expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200" :class="openMenus.masterCustomer ? 'rotate-180' : ''"></i>
                </button>
                <ul x-show="openMenus.masterCustomer && expanded" x-collapse class="pl-8 space-y-1">
                    @foreach([
                            ['route' => 'master-customer.data-table', 'label' => 'Data Table Customer'],
                            ['route' => 'master-customer.data-customer', 'label' => 'Data Customer']
                        ] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" @click.stop="" class="submenu-item {{ $isActive($item['route']) ? 'submenu-active' : 'submenu-default' }}">
                                <i class="bx {{ $isActive($item['route']) ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                                   </div>

            <!-- Channel Dropdown -->
            <div class="space-y-1">
                <button @click.stop="toggle('channel')"
                    class="menu-item w-full group {{ request()->is('channel/*') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-broadcast text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 'opacity-0'">Channel</span>
                    <i x-show="expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200"
                        :class="openMenus.channel ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openMenus.channel && expanded" x-collapse class="pl-8 space-y-1">
                    <div class="space-y-1">
         
                        <button @click.stop="toggle('channelEmail')"
                            class="submenu-item w-full {{ request()->is('channel/email/*') ? 'text-blue-400 font-medium' : 'submenu-default' }}">
                            <i class="bx bx-right-arrow-alt text-lg"></i>
                            <span>Email</span>
                            <i class="bx bx-chevron-down ml-auto transition-transform duration-200"
                                :class="openMenus.channelEmail ? 'rotate-180' : ''"></i>
                        </button>
                        <ul x-show="openMenus.channelEmail" x-collapse class="pl-6 space-y-1 mt-1">
                            @foreach([
                                    ['route' => 'channel.email.inbox', 'label' => 'Inbox Email'],
                                    ['route' => 'channel.email.history', 'label' => 'History Email']
                                ] as $item)
                                <li>
                                    <a href="{{ route($item['route']) }}" @click.stop="" class="submenu-item text-[12px] {{ $isActive($item['route']) ? 'submenu-active pl-3 border-l-2 border-blue-400' : 'submenu-default' }}">
                                        <i class="bx {{ $isActive($item['route']) ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-sm"></i>
                                        <span>{{ $item['label'] }}</span>
                                    </a>

                                </li>
                            @endforeach

                                            </ul>
                    </div>
                </div>

                                   </div>
            <!-- Setup Channel Email Dropdown -->
            <div class="space-y-1">
                <button @click.stop="toggle('setupChannelEmail')"
                    class="menu-item w-full group {{ request()->routeIs('dashboard.email') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-cog text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 'opacity-0'">Setup Channel Email</span>
                    <i x-show="expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200"
                        :class="openMenus.setupChannelEmail ? 'rotate-180' : ''"></i>
                </button>
                <ul x-show="openMenus.setupChannelEmail && expanded" x-collapse class="pl-8 space-y-1">
                    <li>
                        <a href="{{ route('dashboard.email') }}" class="submenu-item {{ $isActive('dashboard.email') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('dashboard.email') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Dashboard Email</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('monitoring.email.response') }}" class="submenu-item {{ $isActive('monitoring.email.response') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('monitoring.email.response') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Monitoring Email Response</span>
                        </a>
                    </li>
                </ul>
            </div>
                        <div class="space-y-1">
                <button @click.stop="toggle('setupManagementUser')"
                    class="menu-item w-full group {{ request()->routeIs('management-user.*') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-user-pin text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 'opacity-0'">Management User</span>
                    <i x-show="expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200"
                        :class="openMenus.setupManagementUser ? 'rotate-180' : ''"></i>
                </button>
                <ul x-show="openMenus.setupManagementUser && expanded" x-collapse class="pl-8 space-y-1">
                    <li>
                        <a href="{{ route('management-user.data-access-application') }}" class="submenu-item {{ $isActive('management-user.data-access-application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('management-user.data-access-application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Data Access Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('management-user.data-user-application') }}" class="submenu-item {{ $isActive('management-user.data-user-application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('management-user.data-user-application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Data User Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('management-user.level-user-application') }}" class="submenu-item {{ $isActive('management-user.level-user-application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('management-user.level-user-application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Level User Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('management-user.export.user.application') }}" class="submenu-item {{ $isActive('management-user.export.user.application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('management-user.export.user.application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Export User Application</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Setting Application Dropdown -->
            <div class="space-y-1">
                <button @click.stop="toggle('settingApplication')"
                    class="menu-item w-full group {{ request()->routeIs('menu.application', 'sub.menu.application', 'detail.menu.application', 'ticket.notification.system', 'setting.channel.agent.index') ? 'text-blue-400 font-medium' : 'menu-item-default' }}">
                    <i class="bx bx-wrench text-lg"></i>
                    <span :class="expanded ? 'opacity-100' : 'opacity-0'">Setting Application</span>
                    <i x-show="expanded" class="bx bx-chevron-down ml-auto transition-transform duration-200"
                        :class="openMenus.settingApplication ? 'rotate-180' : ''"></i>
                </button>
                <ul x-show="openMenus.settingApplication && expanded" x-collapse class="pl-8 space-y-1">

                    <li>
                        <a href="{{ route('menu.application') }}" class="submenu-item {{ $isActive('menu.application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('menu.application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Menu Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sub.menu.application') }}" class="submenu-item {{ $isActive('sub.menu.application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('sub.menu.application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Sub Menu Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('detail.menu.application') }}" class="submenu-item {{ $isActive('detail.menu.application') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('detail.menu.application') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Detail Menu Application</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ticket.notification.system') }}" class="submenu-item {{ $isActive('ticket.notification.system') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('ticket.notification.system') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Ticket Notification System</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('setting.channel.agent.index') }}" class="submenu-item {{ $isActive('setting.channel.agent.index') ? 'submenu-active' : 'submenu-default' }}">
                            <i class="bx {{ $isActive('setting.channel.agent.index') ? 'bx-right-arrow-alt text-blue-400' : 'bx-dots-horizontal-rounded text-gray-600' }} text-lg"></i>
                            <span>Setting Channel Agent</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-800 bg-gray-900/50 mt-auto whitespace-nowrap">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=6366f1&color=fff" class="w-10 h-10 rounded-full shadow-lg border border-gray-700 flex-shrink-0">
                <div class="flex-1 overflow-hidden" x-show="expanded" x-transition:enter="delay-100 duration-200">
                    <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Guest User' }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()->role->name ?? 'Agent' }}</div>
                </div>
                <button x-show="expanded" class="text-gray-400 hover:text-white"><i class="bx bx-cog text-xl"></i></button>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Fixed width classes to prevent jumpy layout */
    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 250ms cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .menu-item-default {
        color: #9ca3af;
        background: transparent;
    }

    .menu-item-default:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
        transform: translateX(4px);
    }

    .menu-item-active {
        background: #2563eb;
        color: white;
        font-weight: 500;
        border-radius: 24px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .submenu-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-size: 13px;
        border-radius: 6px;
        transition: all 200ms ease;
        color: #6b7280;
    }

    .submenu-default:hover {
        background: rgba(255, 255, 255, 0.03);
        color: white;
        padding-left: 20px;
    }

    .submenu-active {
        color: #60a5fa;
        font-weight: 500;
        background: rgba(96, 165, 250, 0.05);
    }

    /* Tooltip styling for collapsed state */
    .icon-nav-item {
        position: relative;
        transition: all 200ms ease;
    }

    .icon-nav-item:hover {
        transform: scale(1.15);
        color: white;
    }

    #main-sidebar:not(.expanded) .icon-nav-item[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 15px;
        padding: 6px 12px;
        background: #1f2937;
        color: white;
        border-radius: 6px;
        white-space: nowrap;
        font-size: 12px;
        z-index: 100;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        border: 1px border #374151;
        pointer-events: none;
    }

    #main-sidebar:not(.expanded) .icon-nav-item[data-tooltip]:hover::before {
        content: '';


   
               position: absolute;


   
               left: 100%;

   
       

               top: 50%;

       
           transform: translateY(-50%);
        margin-left: 5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent #1f2937 transparent transparent;
        z-index: 100;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #4b5563; }
</style>
