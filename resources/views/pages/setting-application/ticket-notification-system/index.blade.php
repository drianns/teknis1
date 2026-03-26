@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none ticket-notification-system-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div id="ticket-notification-config" class="hidden" 
                data-ajax-url="{{ route('ticket.notification.system.getData') }}"
                data-master-users='@json($masterUsers)'></div>
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Ticket Notification System</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Application</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Ticket Notification System</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full h-full">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-full min-h-0">
                
                <!-- Left Column (Settings) -->
                <div class="col-span-1 flex flex-col gap-6 overflow-y-auto custom-scrollbar h-full pr-2">
                    <!-- System Notification Setting -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl shadow-black/20">
                        <div class="p-4 border-b border-gray-700 bg-gray-800/80 flex items-center gap-2 text-blue-400 font-semibold">
                            <i class='bx bx-bell'></i> System Notification Setting
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach(['Ticket Create', 'Ticket Over SLA', 'Ticket Close', 'Ticket Escalation'] as $settingName)
                                @php
                                    $setting = $settings->where('name', $settingName)->where('type', 'system')->first();
                                    $isActive = $setting ? $setting->is_active : false;
                                @endphp
                                <div class="flex items-center justify-between group">
                                    <span class="text-gray-300 text-sm font-medium group-hover:text-white transition-colors">{{ $settingName }}</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ $isActive ? 'checked' : '' }} onchange="toggleSetting('{{ $settingName }}', this.checked)">
                                        <div class="w-11 h-6 bg-gray-700 rounded-full peer-checked:bg-emerald-500 transition-colors shadow-inner"></div>
                                        <div class="absolute left-[2px] top-[2px] w-5 h-5 bg-gray-400 rounded-full transition-transform peer-checked:translate-x-5 peer-checked:bg-white shadow"></div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Customer Notification Setting -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl shadow-black/20">
                        <div class="p-4 border-b border-gray-700 bg-gray-800/80 flex items-center gap-2 text-blue-400 font-semibold">
                            <i class='bx bx-bell'></i> Customer Notification Setting
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach(['Ticket Create', 'Ticket Close'] as $settingName)
                                @php
                                    $setting = $settings->where('name', $settingName)->where('type', 'customer')->first();
                                    $isActive = $setting ? $setting->is_active : false;
                                @endphp
                                <div class="flex items-center justify-between group">
                                    <span class="text-gray-300 text-sm font-medium group-hover:text-white transition-colors">{{ $settingName }}</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ $isActive ? 'checked' : '' }} onchange="toggleSetting('{{ $settingName }}', this.checked)">
                                        <div class="w-11 h-6 bg-gray-700 rounded-full peer-checked:bg-emerald-500 transition-colors shadow-inner"></div>
                                        <div class="absolute left-[2px] top-[2px] w-5 h-5 bg-gray-400 rounded-full transition-transform peer-checked:translate-x-5 peer-checked:bg-white shadow"></div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notification Template -->
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl shadow-black/20 flex-1">
                        <div class="p-4 border-b border-gray-700 bg-gray-800/80 flex items-center gap-2 text-blue-400 font-semibold">
                            <i class='bx bx-notepad'></i> Notification Template
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach([
                                ['Email Notification Ticket Create', 'Template SYSTEM'],
                                ['Email Notification Ticket Create', 'Template CUSTOMER'],
                                ['Email Notification Ticket Closed', 'Template CUSTOMER'],
                                ['Email Notification Ticket Escalation Department', 'Template SYSTEM'],
                                ['Email Notification Compose Email', 'Template SYSTEM'],
                            ] as $tpl)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-teal-500/30">
                                        <i class='bx bx-envelope text-lg'></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-300 text-sm font-medium">{{ $tpl[0] }}</p>
                                        <p class="text-gray-500 text-xs mt-0.5">{{ $tpl[1] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column (Users Grid) -->
                <div class="col-span-1 lg:col-span-3 flex flex-col bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl h-full min-h-0 relative" x-data="{ userSearchQuery: '' }">
                    
                    <!-- Top Toolbar inside Right Panel -->
                    <div class="p-4 border-b border-gray-800 flex flex-wrap justify-between items-center bg-gray-800/50 gap-4">
                        <button onclick="openNotificationModal()"
                            class="px-4 py-2 flex items-center gap-2 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-full text-white text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <i class='bx bx-plus text-blue-400'></i> Add Email Address Notification
                        </button>

                        <div class="flex items-center gap-4">
                            <select id="entries-per-page" onchange="loadUserGrid(1)"
                                class="bg-gray-800 border border-gray-700 rounded-full px-3 py-1.5 text-sm text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                <option value="12">12 per page</option>
                                <option value="24">24 per page</option>
                                <option value="48">48 per page</option>
                            </select>
                            <div class="relative w-64">
                                <input type="text" id="user-grid-search"
                                    class="bg-gray-800 border-gray-700 rounded-full pl-4 pr-10 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full placeholder-gray-500 shadow-inner"
                                    placeholder="Email or User" oninput="debounceSearch()" />
                                <i class='bx bx-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500'></i>
                            </div>
                        </div>
                    </div>

                    <!-- User Cards Grid -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                        <div id="user-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                            <!-- Populated via AJAX -->
                            <div class="col-span-full flex flex-col items-center justify-center p-20 text-gray-500">
                                <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                                <p>Loading notification users...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Pagination -->
                    <div class="px-6 py-4 border-t border-gray-800 bg-gray-800/30 flex justify-between items-center text-xs text-gray-400">
                        <div id="grid-pagination-info"></div>
                        <div id="grid-pagination-links" class="flex gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal for User Notification -->
    <div id="notification-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="notificationModalData()" x-show="open" @notification-modal.window="open = true; initData($event.detail)"
        style="display: none;">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-5xl relative z-10 flex flex-col"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700 bg-gray-800/50 rounded-t-xl">
                <h3 class="text-lg font-bold text-white">Form Email Notification Address</h3>
                <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body Area -->
            <div class="p-6">
                <!-- User Fields row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- User Name Select -->
                    <div class="space-y-1.5" x-data="{ openDropdown: false }">
                        <label class="font-semibold text-gray-300 text-sm">User Name</label>
                        <div class="relative">
                            <button type="button" @click="openDropdown = !openDropdown"
                                @click.outside="openDropdown = false"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all text-left shadow-sm">
                                <span x-text="selectedUserObj ? selectedUserObj.name : 'Select'" class="truncate"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDropdown" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-[200] max-h-60 overflow-y-auto custom-scrollbar">
                                <div class="p-2 sticky top-0 bg-gray-800 border-b border-gray-700">
                                    <input type="text" x-model="userSearch" placeholder="Search user..." class="w-full bg-gray-700 border border-gray-600 text-white rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <div @click="selectUser(null); openDropdown = false" class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 text-gray-400 transition-colors">
                                    Select
                                </div>
                                <template x-for="usr in filteredUsers" :key="usr.id">
                                    <div @click="selectUser(usr); openDropdown = false"
                                        class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                        :class="(selectedUserObj && selectedUserObj.id === usr.id) ? 'bg-gray-700 text-blue-400 font-medium' : 'text-gray-300'">
                                        <div class="flex flex-col">
                                            <span x-text="usr.name"></span>
                                            <span class="text-xs text-gray-500" x-text="usr.email"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Email Address (Auto-fill) -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-gray-300 text-sm">Email Address</label>
                        <input type="text" x-model="formData.email" readonly
                            class="w-full bg-gray-800/50 border border-gray-700 text-gray-400 rounded-lg px-4 py-2 focus:outline-none shadow-sm cursor-not-allowed"
                            placeholder="Email Address">
                    </div>

                    <!-- Status Select -->
                    <div class="space-y-1.5" x-data="{ openStatus: false }">
                        <label class="font-semibold text-gray-300 text-sm">Status</label>
                        <div class="relative">
                            <button type="button" @click="openStatus = !openStatus"
                                @click.outside="openStatus = false"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all text-left shadow-sm">
                                <span x-text="formData.status || 'Select'" class="truncate"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openStatus ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openStatus" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-[200]">
                                <div @click="formData.status = ''; openStatus = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-700 text-gray-400 transition-colors">Select</div>
                                <div @click="formData.status = 'Yes'; openStatus = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-700 transition-colors" :class="formData.status === 'Yes' ? 'text-blue-400 font-medium' : 'text-gray-300'">Yes</div>
                                <div @click="formData.status = 'No'; openStatus = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-700 transition-colors" :class="formData.status === 'No' ? 'text-blue-400 font-medium' : 'text-gray-300'">No</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Fields row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Level User (Auto-fill) -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-gray-300 text-sm">Level User</label>
                        <input type="text" x-model="formData.level" readonly
                            class="w-full bg-gray-800/50 border border-gray-700 text-gray-400 rounded-lg px-4 py-2 focus:outline-none shadow-sm cursor-not-allowed"
                            placeholder="Level User">
                    </div>

                    <!-- Department (Auto-fill) -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-gray-300 text-sm">Department</label>
                        <input type="text" x-model="formData.department" readonly
                            class="w-full bg-gray-800/50 border border-gray-700 text-gray-400 rounded-lg px-4 py-2 focus:outline-none shadow-sm cursor-not-allowed"
                            placeholder="Department">
                    </div>

                    <!-- Group Agent (Auto-fill) -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-gray-300 text-sm">Group Agent</label>
                        <input type="text" x-model="formData.group_agent" readonly
                            class="w-full bg-gray-800/50 border border-gray-700 text-gray-400 rounded-lg px-4 py-2 focus:outline-none shadow-sm cursor-not-allowed"
                            placeholder="Group Agent">
                    </div>
                </div>

                <!-- Notification Events -->
                <div class="border-t border-gray-700 pt-6">
                    <h4 class="flex items-center gap-2 text-blue-500 font-semibold mb-6">
                        <i class='bx bx-bell'></i> Setting Notification Event
                    </h4>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Checkbox Logic designed based on snapshot -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative w-5 h-5 flex items-center justify-center">
                                <input type="checkbox" x-model="formData.is_ticket_create" class="peer appearance-none w-5 h-5 border border-gray-600 rounded bg-gray-700 checked:bg-emerald-500 checked:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-colors cursor-pointer">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none text-lg top-[1px]'></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-300 group-hover:text-white transition-colors">Ticket Create</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative w-5 h-5 flex items-center justify-center">
                                <input type="checkbox" x-model="formData.is_ticket_over_sla" class="peer appearance-none w-5 h-5 border border-gray-600 rounded bg-gray-700 checked:bg-emerald-500 checked:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-colors cursor-pointer">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none text-lg top-[1px]'></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-300 group-hover:text-white transition-colors">Ticket Over SLA</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative w-5 h-5 flex items-center justify-center">
                                <input type="checkbox" x-model="formData.is_ticket_closed" class="peer appearance-none w-5 h-5 border border-gray-600 rounded bg-gray-700 checked:bg-emerald-500 checked:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-colors cursor-pointer">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none text-lg top-[1px]'></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-300 group-hover:text-white transition-colors">Ticket Closed</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative w-5 h-5 flex items-center justify-center">
                                <input type="checkbox" x-model="formData.is_ticket_escalation" class="peer appearance-none w-5 h-5 border border-gray-600 rounded bg-gray-700 checked:bg-emerald-500 checked:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500/50 transition-colors cursor-pointer">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none text-lg top-[1px]'></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-300 group-hover:text-white transition-colors">Ticket Escalation</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between bg-gray-800/50 rounded-b-xl gap-4">
                <button @click="open = false"
                    class="px-8 py-2.5 rounded-full bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none">
                    Close
                </button>
                <button @click="saveUserConf()"
                    class="px-8 py-2.5 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none min-w-[120px] flex justify-center">
                    <span>Save</span>
                </button>
            </div>
        </div>
    </div>


    

    

    
    @push('scripts')
        @vite('resources/js/pages/setting-application/ticket-notification-system.js')
    @endpush
@endsection
```


@push('styles')
    @vite('resources/css/pages/setting-application/ticket-notification-system.css')
@endpush
