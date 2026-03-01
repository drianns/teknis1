@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none detail-menu-application-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Detail Menu Application</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Application</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Detail Menu Application</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <!-- Table Section -->
            <div
                class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <!-- Table Controls -->
                <div
                    class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="show-entries flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select id="entries-per-page" onchange="changeEntriesPerPage(this.value)"
                            class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:outline-none text-gray-300">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="search-box relative">
                            <input type="text" id="table-search"
                                class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 focus:w-80 transition-all placeholder-gray-500"
                                placeholder="Search..." value="{{ request('search') }}"
                                oninput="debounceSearch(this.value)" />
                            <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                        </div>

                        <button
                            class="btn-add px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all"
                            onclick="openAddDetailMenuModal()">
                            <i class='bx bx-plus text-lg'></i> Add Detail Menu Application
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="data-table w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu Name <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Sub Menu Name <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Detail Menu Name <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-56 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Url <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">Type <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @forelse($detailMenus as $detailMenu)
                                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                    <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-300 font-medium">
                                        {{ $detailMenu->id }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">{{ $detailMenu->menu_name }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-400">{{ $detailMenu->sub_menu_name }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-300">{{ $detailMenu->detail_menu_name }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">{{ $detailMenu->url }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-center">
                                        @if ($detailMenu->type == 'Yes')
                                            <span class="bg-teal-500/20 text-teal-400 border border-teal-500/30 px-3 py-1 rounded-full text-xs shadow-sm">Yes</span>
                                        @else
                                            <span class="bg-red-500/20 text-red-500 border border-red-500/30 px-3 py-1 rounded-full text-xs shadow-sm">No</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-center">
                                        <div class="action-dropdown relative flex justify-center"
                                            x-data="{ dropdownOpen: false }">
                                            <button @click.stop="dropdownOpen = !dropdownOpen"
                                                class="w-8 h-8 rounded-lg hover:bg-gray-800 flex items-center justify-center transition-all text-blue-400">
                                                <i class='bx bx-dots-vertical-rounded text-xl'></i>
                                            </button>

                                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden text-left"
                                                style="display: none;">
                                                <button
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3 transition-colors border-b border-gray-700/50"
                                                    @click="editDetailMenu({{ $detailMenu->id }}); dropdownOpen = false">
                                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> <span
                                                        class="font-medium">Edit</span>
                                                </button>
                                                <button
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors"
                                                    @click="deleteDetailMenu({{ $detailMenu->id }}); dropdownOpen = false">
                                                    <i class='bx bx-trash text-red-500 text-lg'></i> <span
                                                        class="font-medium">Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-500 text-sm bg-gray-900 border-none">
                                        No detail menus found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="pagination-info text-sm text-gray-500">
                        Showing <span class="text-white font-bold">{{ $detailMenus->firstItem() ?? 0 }}</span> to <span
                            class="text-white font-bold">{{ $detailMenus->lastItem() ?? 0 }}</span> of <span
                            class="text-white font-bold">{{ $detailMenus->total() }}</span> entries
                    </div>
                    <div>
                        {{ $detailMenus->appends(request()->query())->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $menuHierarchy = [
            'Master Data' => ['Data Type', 'Data Category', 'Data Meta', 'Data Sub Category', 'Data Source', 'Channel Ticket', 'Data Activity', 'Department Escalation Unit', 'Data Brand Name', 'Data Aux Reason', 'Data Status Ticket', 'Data Group Agent', 'Data Brand Category', 'Data Fulfillment', 'Data Holidays', 'Data Site', 'Data Group Name', 'Data Fulfillment Location', 'Data Max Handle'],
            'Apps' => ['Taskboard', 'Thread System', 'Ticketing', 'Simulation Inbound', 'Ticketing Summary', 'Ticketing Department', 'History Ticketing'],
            'Dashboard' => [],
            'Setup Sosial Media' => ['Dashboard', 'History Data Instagram', 'Analytic Data', 'Analytic', 'Data Account Sosial Media', 'Setting Agent Sosial Media', 'History Data Facebook', 'History Data Mention', 'History Data Twitter'],
            'Report' => ['Report Interaction Ticket', 'Report base on SLA', 'Report Thread Transaction', 'Report base on Transaction', 'Report base on Staff', 'Report Acara OJK', 'Report Instagram', 'Report Mention', 'Report AUX', 'Report Transaction Outbound', 'Report Channel Email', 'Report Data Multichat', 'Report Twitter', 'Report Summary Call', 'Report Statistic Call', 'Report Assign Email', 'Report SL Nespresso', 'Report SL Kanmo', 'Report Multichat', 'Report Call History'],
            'Management User' => ['Data User Application', 'Data Access Application', 'Level User Application', 'Export User Application'],
            'Master Customer' => ['Data Customer', 'Data Table Customer'],
            'File Manager' => ['Knowledge Base', 'Document HTML', 'Logo Signature'],
            'Setting Email System' => ['Setting Email Accounts', 'Setting Email Signature', 'Setting Email Service', 'Setting Email Notification Method', 'Setting Server Profile', 'Setting Server Protocol', 'Setting Server Protocol Out'],
            'Setup Channel Call' => ['Setting Agent Call'],
            'Setting EPIC System' => ['Setting Configuration EPIC'],
            'Simulasi Data Masuk' => [],
            'Setting Application' => ['Data Journey', 'Menu Application', 'Sub Menu Application', 'Detail Menu Application', 'Ticket Notification System', 'Configuration LDAP', 'Channel License', 'Transaksi Teleconfirm', 'WS Acra Testing', 'Ticket Agent Syncronize', 'Gamification Direct Call', 'Xtra Form', 'Bantu Dagang', 'Setting Channel Agent', 'Transaksi Collection'],
            'Setup Channel Email' => [],
            'Channel' => ['Setting Agent Email', 'Data Signature', 'New Email Form', 'Setting Auto Reply Email', 'Template Auto Reply Email', 'Template Response Email', 'Monitoring Email Response', 'Account Email Corporate', 'Data Filter Jumlah Hari', 'Data Jam Operational Email', 'Data Incoming Email', 'Dashboard Email', 'Outbound Call', 'WA', 'Campaign', 'Social Media', 'Email', 'Whatsapp'],
            'Data Login' => ['Monitoring Login', 'Data Login Activity'],
            'Log Out' => [],
            'Welcome Call' => ['Jumlah Call Parameter', 'API Data Welcome Call', 'Distribution Data Bucket', 'Detail History Outbound', 'Data Upload Nasabah', 'Reason Call', 'Dashboard Welcome Call', 'Status Call', 'Setting Agent Campaign', 'History Welcome Call', 'Release Data Welcome Call'],
            'Setting Sosial Media' => ['Dashboard', 'Analytic Data'],
            'Multichannel Status' => [],
            'Taskboard' => [],
            'Ticketing System' => [],
            'Setup Channel WA' => ['Setting Account WA Agent', 'Data Nomor WhatsApp'],
            'Teleconfirm' => ['Data Upload Nasabah', 'Setting Agent Campaign', 'Data Taskboard Agent', 'Data Produk', 'History Teleconfirm', 'Data Detail Produk', 'Dashboard Data Teleconfirm', 'Report Data Teleconfirm', 'Report Data Summary Call', 'Sample AI'],
            'Modul Outbound Call' => ['Data Modul', 'Data Campaign Header', 'Campaign List Group'],
            'Collection' => ['Report Data Collection', 'History Data Collection', 'Dashboard Data Collection', 'Report Data Summary Call', 'Setting Agent Campaign', 'Data Taskboard Agent', 'Data Upload Nasabah'],
            'Outbound Call' => ['Data Upload Nasabah', 'Setting Agent Campaign', 'Data Taskboard Agent', 'Report Transaksi Outbound', 'History Data Upload', 'Dashboard Data Outbound', 'History Transaksi Call'],
            'Apps UIDESK' => ['Application', 'Company', 'Transaksi', 'Partner'],
            'Recording' => [],
            'Sosial Media' => ['Setting Agent Sosial Media', 'Dashboard'],
            'Bantu Dagang' => [],
            'Wallboard' => []
        ];
    @endphp

    <!-- Alpine Modal Component -->
    <div id="detail-menu-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="detailMenuModalData()" x-show="open" @detail-menu-modal.window="open = true; loadDetailMenuData($event.detail)"
        style="display: none;">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <!-- Modal Panel -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl w-full max-w-5xl relative z-10 flex flex-col max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
                <h3 class="text-xl font-bold text-white"
                    x-text="isEdit ? 'Form Edit Detail Menu Application' : 'Form Add Detail Menu Application'"></h3>
                <button @click="open = false"
                    class="text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-visible flex-1 text-sm text-gray-300 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    
                    <!-- Menu Name Dropdown -->
                    <div class="space-y-1.5" x-data="{ openDropdown: false }">
                        <label class="font-medium text-blue-400 ml-1">Menu Name</label>
                        <div class="relative">
                            <button type="button" @click="openDropdown = !openDropdown"
                                @click.outside="openDropdown = false"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 flex justify-between items-center focus:outline-none focus:border-blue-500 transition-colors cursor-pointer text-left">
                                <span x-text="formData.menuName || 'Select'" class="truncate"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform'
                                    :class="openDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDropdown" style="display: none;"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 top-full mt-2 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-[200] max-h-60 overflow-y-auto custom-scrollbar ring-1 ring-black/50">
                                <div @click="formData.menuName = ''; openDropdown = false"
                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                    :class="formData.menuName === '' ? 'text-blue-400 bg-blue-500/10' : 'text-gray-400'">
                                    Select
                                </div>
                                <template x-for="option in Object.keys(menuHierarchy)" :key="option">
                                    <div @click="formData.menuName = option; openDropdown = false"
                                        class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                        :class="formData.menuName === option ? 'text-blue-400 bg-blue-500/10 font-medium' : 'text-gray-300'"
                                        x-text="option">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Sub Menu Name Dropdown (Cascading) -->
                    <div class="space-y-1.5" x-data="{ openSubSelect: false }">
                        <label class="font-medium text-blue-400 ml-1">Sub Menu Name</label>
                        <div class="relative">
                            <button type="button" 
                                @click="availableSubMenus.length > 0 ? openSubSelect = !openSubSelect : null"
                                @click.outside="openSubSelect = false"
                                :class="availableSubMenus.length === 0 ? 'opacity-60 cursor-not-allowed bg-gray-800' : 'cursor-pointer hover:border-gray-600 bg-gray-800'"
                                class="w-full border border-gray-700 text-white rounded-xl px-4 py-2.5 flex justify-between items-center focus:outline-none transition-colors text-left">
                                <span x-text="availableSubMenus.length === 0 ? 'Select' : (formData.subMenuName || 'Select')" class="truncate text-gray-400"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform'
                                    :class="openSubSelect ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openSubSelect" style="display: none;"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 top-full mt-2 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-[200] max-h-60 overflow-y-auto custom-scrollbar ring-1 ring-black/50">
                                <div @click="formData.subMenuName = ''; openSubSelect = false"
                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                    :class="formData.subMenuName === '' ? 'text-blue-400 bg-blue-500/10' : 'text-gray-400'">
                                    Select
                                </div>
                                <template x-for="option in availableSubMenus" :key="option">
                                    <div @click="formData.subMenuName = option; openSubSelect = false"
                                        class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                        :class="formData.subMenuName === option ? 'text-blue-400 bg-blue-500/10 font-medium' : 'text-gray-300'"
                                        x-text="option">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-blue-400 ml-1">Detail Menu Name</label>
                        <input type="text" x-model="formData.detailMenuName"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="Detail Menu Name">
                    </div>

                    <div class="space-y-1.5" x-data="{ openTypeDropdown: false }">
                        <label class="font-medium text-blue-400 ml-1">Type</label>
                        <div class="relative">
                            <button type="button" @click="openTypeDropdown = !openTypeDropdown"
                                @click.outside="openTypeDropdown = false"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 flex justify-between items-center focus:outline-none focus:border-blue-500 transition-colors cursor-pointer text-left">
                                <span x-text="formData.type || 'Select'" class="truncate text-gray-400"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform'
                                    :class="openTypeDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openTypeDropdown" style="display: none;"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 top-full mt-2 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-[200] overflow-hidden ring-1 ring-black/50">
                                <div @click="formData.type = ''; openTypeDropdown = false"
                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                    :class="formData.type === '' ? 'text-blue-400 bg-blue-500/10' : 'text-gray-400'">
                                    Select
                                </div>
                                <div @click="formData.type = 'Yes'; openTypeDropdown = false"
                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                    :class="formData.type === 'Yes' ? 'text-blue-400 bg-blue-500/10 font-medium' : 'text-gray-300'">
                                    Yes
                                </div>
                                <div @click="formData.type = 'No'; openTypeDropdown = false"
                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                    :class="formData.type === 'No' ? 'text-blue-400 bg-blue-500/10 font-medium' : 'text-gray-300'">
                                    No
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5 mt-4">
                    <label class="font-medium text-blue-400 ml-1">Url</label>
                    <input type="text" x-model="formData.url"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                        placeholder="Url">
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-800 flex justify-between gap-3 bg-gray-900 rounded-b-2xl">
                <button @click="open = false"
                    class="px-6 py-2.5 rounded-full bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition-all shadow-lg">
                    Close
                </button>
                <button @click="saveDetailMenu()"
                    class="px-6 py-2.5 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm transition-all shadow-lg min-w-[100px] flex justify-center">
                    <span x-text="isEdit ? 'Update' : 'Save'"></span>
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar for overflow handling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #374151;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Spinner & Toast */
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner-ring {
            animation: spin-slow 1s linear infinite;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-enter {
            animation: slideInRight 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .toast-exit {
            animation: slideOutRight 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards !important;
        }
    </style>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Search timeout config
        let searchTimeout;

        function debounceSearch(query) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTable(query);
            }, 500);
        }

        function changeEntriesPerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            window.location = url;
        }

        function searchTable(query) {
            const url = new URL(window.location);
            url.searchParams.set('search', query);
            url.searchParams.delete('page'); // reset to page 1 on search
            window.location = url;
        }

        // Global triggers
        function openAddDetailMenuModal() {
            window.dispatchEvent(new CustomEvent('detail-menu-modal'));
        }

        function editDetailMenu(id) {
            window.dispatchEvent(new CustomEvent('detail-menu-modal', {
                detail: { detailMenuId: id }
            }));
        }

        function deleteDetailMenu(id) {
            if (!confirm('Are you sure you want to delete this detail menu? This action cannot be undone.')) {
                return;
            }

            showLoading('Deleting detail menu record...');

            fetch(`/detail-menu-application/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast(data.message || 'Failed to delete detail menu', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Server error during deletion.', 'error');
                });
        }

        // Alpine JS Component Scope
        function detailMenuModalData() {
            return {
                open: false,
                isEdit: false,
                menuHierarchy: @json($menuHierarchy),
                availableSubMenus: [],
                formData: {
                    id: null,
                    menuName: '',
                    subMenuName: '',
                    detailMenuName: '',
                    url: '',
                    type: ''
                },

                init() {
                    // Watch for Menu Name changes to update Sub Menus
                    this.$watch('formData.menuName', value => {
                        this.availableSubMenus = value && this.menuHierarchy[value] ? this.menuHierarchy[value] : [];
                        
                        if (this.availableSubMenus.length === 0) {
                            // If no submenus, clear sub menu selection
                            this.formData.subMenuName = '';
                        } else if (!this.availableSubMenus.includes(this.formData.subMenuName)) {
                            // If previous sub menu is invalid, clear it
                            this.formData.subMenuName = '';
                        }
                    });
                },

                resetForm() {
                    this.isEdit = false;
                    this.availableSubMenus = [];
                    this.formData = {
                        id: null,
                        menuName: '',
                        subMenuName: '',
                        detailMenuName: '',
                        url: '',
                        type: ''
                    };
                },

                loadDetailMenuData(detail) {
                    if (detail && detail.detailMenuId) {
                        this.isEdit = true;
                        showLoading('Loading details...');

                        fetch(`/detail-menu-application/${detail.detailMenuId}`, {
                            headers: { 'Accept': 'application/json' }
                        })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                this.formData = {
                                    id: data.id,
                                    menuName: data.menu_name || '',
                                    subMenuName: data.sub_menu_name || '',
                                    detailMenuName: data.detail_menu_name || '',
                                    url: data.url || '',
                                    type: data.type || ''
                                };
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error loading data:', error);
                                showToast('Failed to load detail menu data', 'error');
                                this.open = false;
                            });
                    } else {
                        this.resetForm();
                    }
                },

                saveDetailMenu() {
                    // Validation
                    if (!this.formData.menuName || !this.formData.detailMenuName || !this.formData.type) {
                        showToast('Please fill in all required fields.', 'error');
                        return;
                    }

                    // Strict dependent validation
                    if (this.availableSubMenus.length > 0 && !this.formData.subMenuName) {
                        showToast('Please select a Sub Menu Name.', 'error');
                        return;
                    }

                    const endpointUrl = this.isEdit
                        ? `/detail-menu-application/${this.formData.id}`
                        : '/detail-menu-application/store';

                    const method = this.isEdit ? 'PUT' : 'POST';

                    showLoading(this.isEdit ? 'Updating detail menu...' : 'Saving new detail menu...');

                    fetch(endpointUrl, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            menu_name: this.formData.menuName,
                            sub_menu_name: this.formData.subMenuName || null,
                            detail_menu_name: this.formData.detailMenuName,
                            url: this.formData.url,
                            type: this.formData.type
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(this.isEdit ? 'Detail menu updated successfully' : 'Detail menu created successfully', 'success');
                                this.open = false;
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast(data.message || 'Failed to save detail menu', 'error');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            showToast('Server error. Validation may have failed.', 'error');
                        });
                }
            };
        }

        /**
         * Reusable UI Helpers
         */
        function showLoading(message = 'Processing request...') {
            let overlay = document.getElementById('bantu-dagang-loader');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'bantu-dagang-loader';
                overlay.className = 'fixed inset-0 bg-[#000000] bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-[200] transition-opacity duration-300';

                overlay.innerHTML = `
                                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 flex flex-col items-center shadow-2xl min-w-[300px] scale-95 opacity-0 transition-all duration-300" id="loader-box">
                                    <div class="relative w-16 h-16 mb-4">
                                        <div class="absolute inset-0 rounded-full border-[3px] border-gray-700"></div>
                                        <div class="absolute inset-0 rounded-full border-[3px] border-blue-500 border-t-transparent spinner-ring"></div>
                                    </div>
                                    <h3 class="text-white font-bold text-lg mb-1">Please Wait</h3>
                                    <p class="text-gray-400 text-sm dynamic-msg">${message}</p>
                                </div>
                            `;
                document.body.appendChild(overlay);
            } else {
                overlay.querySelector('.dynamic-msg').innerText = message;
            }

            setTimeout(() => {
                const box = document.getElementById('loader-box');
                if (box) {
                    box.classList.remove('scale-95', 'opacity-0');
                    box.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        }

        function hideLoading() {
            const overlay = document.getElementById('bantu-dagang-loader');
            const box = document.getElementById('loader-box');

            if (overlay && box) {
                box.classList.remove('scale-100', 'opacity-100');
                box.classList.add('scale-95', 'opacity-0');
                overlay.classList.add('opacity-0');

                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        }

        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;

            let iconClass = 'bx-check-circle';
            let iconColor = 'text-green-500';
            let bgLine = 'bg-green-500';

            if (type === 'error') {
                iconClass = 'bx-error-circle';
                iconColor = 'text-red-500';
                bgLine = 'bg-red-500';
            }

            toast.className = `fixed top-6 right-6 bg-gray-900 border border-gray-800 shadow-xl rounded-xl flex items-center overflow-hidden z-[300] min-w-[300px] toast-enter`;

            toast.innerHTML = `
                            <div class="w-1.5 h-full self-stretch ${bgLine}"></div>
                            <div class="px-4 py-3 flex items-center w-full">
                                <i class='bx ${iconClass} ${iconColor} text-2xl mr-3'></i>
                                <div class="flex-1">
                                    <p class="text-white text-sm font-semibold">${type === 'error' ? 'Error' : 'Success'}</p>
                                    <p class="text-gray-400 text-[13px]">${message}</p>
                                </div>
                                <button onclick="document.getElementById('${toastId}').classList.add('toast-exit')" class="ml-4 text-gray-500 hover:text-white transition-colors">
                                    <i class='bx bx-x text-xl'></i>
                                </button>
                            </div>
                        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                const el = document.getElementById(toastId);
                if (el) {
                    el.classList.add('toast-exit');
                    setTimeout(() => el.remove(), 300);
                }
            }, 4000);
        }
    </script>
@endsection