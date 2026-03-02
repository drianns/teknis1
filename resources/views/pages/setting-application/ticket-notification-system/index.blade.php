@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none ticket-notification-system-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
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
                    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50">
                        <button onclick="openNotificationModal()"
                            class="px-4 py-2 flex items-center gap-2 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-full text-white text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <i class='bx bx-plus text-blue-400'></i> Add Email Address Notification
                        </button>

                        <div class="relative w-64">
                            <input type="text" x-model="userSearchQuery"
                                class="bg-gray-800 border-gray-700 rounded-full pl-4 pr-10 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full placeholder-gray-500 shadow-inner"
                                placeholder="Email or User" />
                            <i class='bx bx-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500'></i>
                        </div>
                    </div>

                    <!-- User Cards Grid -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                            @forelse($notificationUsers as $nu)
                                @php
                                    $user = $nu->user;
                                    $level = $user->userAgent ? $user->userAgent->user_type : 'Undefined';
                                    $dept = $user->company ? $user->company->name : 'N/A';
                                @endphp
                                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 relative group cursor-pointer" 
                                    onclick="editNotificationModal({{ json_encode($nu) }}, {{ json_encode($user) }})"
                                    x-show="userSearchQuery === '' || '{{ strtolower($user->name) }}'.includes(userSearchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(userSearchQuery.toLowerCase())">
                                    <!-- Edit Overlay -->
                                    <div class="absolute inset-0 bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px] z-10">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                            <i class='bx bx-edit text-xl'></i>
                                        </div>
                                    </div>

                                    <!-- Avatar -->
                                    <div class="w-24 h-24 flex-shrink-0 rounded-full bg-[#A7A7DD] mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=A7A7DD&color=fff&size=128" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- User Info -->
                                    <h3 class="text-white font-semibold text-center text-sm mb-2 px-2 truncate w-full group-hover:text-blue-400 transition-colors">{{ $user->name }}</h3>
                                    
                                    <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3 shadow-sm select-none">
                                        {{ $dept }}
                                    </div>
                                    
                                    <p class="text-gray-400 text-xs text-center truncate w-full px-2" title="{{ $user->email }}">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            @empty
                                <div class="col-span-full flex flex-col items-center justify-center p-12 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed">
                                    <i class='bx bx-user-x text-5xl mb-3 opacity-50'></i>
                                    <p>No email notification users found.</p>
                                </div>
                            @endforelse
                        </div>
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


    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-corner { background: transparent; }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideInRight 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        .toast-exit { animation: slideOutRight 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards !important; }
        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spinner-ring { animation: spin-slow 1s linear infinite; }
    </style>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const masterUsers = @json($masterUsers);

        function toggleSetting(name, isActive) {
            fetch('/ticket-notification-system/setting', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    is_active: isActive
                })
            }).then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast('Setting saved', 'success');
                } else {
                    showToast('Error saving setting', 'error');
                }
            }).catch(e => {
                showToast('Server error', 'error');
            });
        }

        function openNotificationModal() {
            window.dispatchEvent(new CustomEvent('notification-modal'));
        }

        function editNotificationModal(nuObj, userObj) {
            window.dispatchEvent(new CustomEvent('notification-modal', {
                detail: { nuObj, userObj }
            }));
        }

        function notificationModalData() {
            return {
                open: false,
                allUsers: masterUsers,
                userSearch: '',
                selectedUserObj: null,
                formData: {
                    user_id: '',
                    email: '',
                    status: 'Yes',
                    level: '',
                    department: '',
                    group_agent: '',
                    is_ticket_create: false,
                    is_ticket_over_sla: false,
                    is_ticket_closed: false,
                    is_ticket_escalation: false
                },

                get filteredUsers() {
                    if (this.userSearch === '') {
                        return this.allUsers.slice(0, 50); // limit to avoid lag
                    }
                    return this.allUsers.filter(usr => 
                        usr.name.toLowerCase().includes(this.userSearch.toLowerCase()) || 
                        usr.email.toLowerCase().includes(this.userSearch.toLowerCase())
                    ).slice(0, 50);
                },

                selectUser(usr) {
                    this.selectedUserObj = usr;
                    if(usr) {
                        this.formData.user_id = usr.id;
                        this.formData.email = usr.email;
                        this.formData.level = usr.level;
                        this.formData.department = usr.department;
                        this.formData.group_agent = usr.group_agent;
                    } else {
                        this.formData.user_id = '';
                        this.formData.email = '';
                        this.formData.level = '';
                        this.formData.department = '';
                        this.formData.group_agent = '';
                    }
                },

                initData(detail) {
                    this.userSearch = '';
                    if (detail && detail.nuObj) {
                        const nu = detail.nuObj;
                        const usrId = nu.user_id;
                        const loadedUser = this.allUsers.find(u => u.id == usrId);
                        
                        this.selectUser(loadedUser);
                        
                        this.formData.status = nu.status;
                        this.formData.is_ticket_create = nu.is_ticket_create == 1;
                        this.formData.is_ticket_over_sla = nu.is_ticket_over_sla == 1;
                        this.formData.is_ticket_closed = nu.is_ticket_closed == 1;
                        this.formData.is_ticket_escalation = nu.is_ticket_escalation == 1;

                    } else {
                        this.selectedUserObj = null;
                        this.selectUser(null);
                        this.formData.status = 'Yes';
                        this.formData.is_ticket_create = false;
                        this.formData.is_ticket_over_sla = false;
                        this.formData.is_ticket_closed = false;
                        this.formData.is_ticket_escalation = false;
                    }
                },

                saveUserConf() {
                    if (!this.formData.user_id) {
                        showToast('Please select a User Name', 'error');
                        return;
                    }

                    showLoading('Saving notification config...');

                    fetch('/ticket-notification-system/user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: this.formData.user_id,
                            status: this.formData.status,
                            is_ticket_create: this.formData.is_ticket_create,
                            is_ticket_over_sla: this.formData.is_ticket_over_sla,
                            is_ticket_closed: this.formData.is_ticket_closed,
                            is_ticket_escalation: this.formData.is_ticket_escalation
                        })
                    }).then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showToast(data.message, 'success');
                            this.open = false;
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message || 'Validation error', 'error');
                        }
                    }).catch(e => {
                        hideLoading();
                        showToast('Server error', 'error');
                    });
                }
            }
        }

        // --- Core UI Reusables --- //
        function showLoading(message = 'Processing...') {
            let overlay = document.getElementById('bantu-dagang-loader');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'bantu-dagang-loader';
                overlay.className = 'fixed inset-0 bg-[#000000] bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-[500] transition-opacity duration-300';
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
                setTimeout(() => overlay.remove(), 300);
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

            toast.className = `fixed top-6 right-6 bg-gray-900 border border-gray-800 shadow-xl rounded-xl flex items-center overflow-hidden z-[600] min-w-[300px] toast-enter`;

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
