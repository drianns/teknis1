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
                        <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <tr><td colspan="7" class="p-12 text-center text-gray-500">Loading data...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div id="pagination-info" class="pagination-info text-sm text-gray-500"></div>
                    <div id="pagination-links"></div>
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

    

    

    
    @push('scripts')
        @vite('resources/js/pages/setting-application/detail-menu-application.js')
    @endpush
@endsection

@push('styles')
    @vite('resources/css/pages/setting-application/detail-menu-application.css')
@endpush
