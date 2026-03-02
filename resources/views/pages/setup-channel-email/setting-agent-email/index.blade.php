@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none setting-agent-email-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Setting Agent Email</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Setting Agent Email</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full h-full">
            <div class="flex flex-col bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl h-full min-h-0 relative" x-data="{ userSearchQuery: '' }">
                
                <!-- Top Toolbar inside Panel -->
                <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50">
                    <button onclick="openAgentEmailModal()"
                        class="px-5 py-2 flex items-center gap-2 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-full text-white text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <i class='bx bx-plus text-blue-400 font-bold'></i> Add Agent Channel Email
                    </button>

                    <div class="relative w-72">
                        <input type="text" x-model="userSearchQuery"
                            class="bg-gray-800 border-gray-700 rounded-full pl-4 pr-10 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full placeholder-gray-500 shadow-inner"
                            placeholder="Email or User Search" />
                        <i class='bx bx-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-500'></i>
                    </div>
                </div>

                <!-- User Cards Grid -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                        @forelse($users as $user)
                            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 pt-8 relative group cursor-default"
                                x-show="userSearchQuery === '' || '{{ strtolower($user->name) }}'.includes(userSearchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(userSearchQuery.toLowerCase())">
                                
                                <!-- Hover Actions Overlay -->
                                <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-row items-center justify-center gap-3 backdrop-blur-[2px] z-10 rounded-2xl pointer-events-none group-hover:pointer-events-auto">
                                    <!-- Edit Action -->
                                    <button onclick="editAgentEmailModal({{ json_encode($user) }})" title="Edit" 
                                        class="w-10 h-10 rounded-[10px] bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-600 hover:-translate-y-1">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <button onclick="deleteAgentEmail({{ $user->id }})" title="Delete"
                                        class="w-10 h-10 rounded-[10px] bg-red-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-75 hover:bg-red-600 hover:-translate-y-1">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>

                                <!-- Not Login Badge -->
                                <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm z-0">
                                    Not Login
                                </div>

                                <!-- Avatar -->
                                <div class="w-24 h-24 flex-shrink-0 rounded-full bg-teal-500/20 mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors z-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2dd4bf&color=fff&size=128" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                                
                                <!-- User Info -->
                                <h3 class="text-white font-semibold text-center text-[15px] mb-1 px-2 truncate w-full group-hover:text-blue-400 transition-colors z-0">{{ $user->name }}</h3>
                                
                                <p class="text-gray-400 text-[11px] text-center truncate w-full px-2 mb-4 z-0">
                                    {{ $user->email }}
                                </p>

                                <div class="bg-blue-500 text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-sm select-none z-0 hover:-translate-y-0.5 transition-transform">
                                    Max Handle 20 - Now Handle {{ rand(0, 5) }}
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center p-12 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed">
                                <i class='bx bx-user-x text-5xl mb-3 opacity-50'></i>
                                <p>No users found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal for Setting Agent Email -->
    <div id="agent-email-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="agentEmailModalData()" x-show="open" @agent-email-modal.window="open = true; initData($event.detail)"
        style="display: none;">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-5xl relative z-10 flex flex-col overflow-hidden max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                <h3 class="text-lg font-bold text-gray-200">Form Setting Agent Email</h3>
                <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body Area -->
            <div class="p-4 sm:p-6 bg-gray-800/30 flex flex-col overflow-hidden flex-1">
                
                <!-- Top Dropdowns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4">
                    <!-- Dropdown Account Email -->
                    <div class="space-y-1.5" x-data="{ openEmailDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">Select Account Email</label>
                        <div class="relative">
                            <button type="button" @click="openEmailDropdown = !openEmailDropdown"
                                @click.outside="openEmailDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="formData.account_email || 'Select'" class="truncate" :class="!formData.account_email ? 'text-gray-400' : ''"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openEmailDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openEmailDropdown" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200]">
                                <div @click="formData.account_email = ''; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Select</div>
                                <div @click="formData.account_email = 'club.indonesia@nespresso.co.id'; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">club.indonesia@nespresso.co.id</div>
                                <div @click="formData.account_email = 'support@kanmogroup.com'; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">support@kanmogroup.com</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Max Distribution Data -->
                    <div class="space-y-1.5" x-data="{ openDistDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">Select Maximal Distribution Data</label>
                        <div class="relative">
                            <button type="button" @click="openDistDropdown = !openDistDropdown"
                                @click.outside="openDistDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="formData.max_distribution || 'Select'" class="truncate" :class="!formData.max_distribution ? 'text-gray-400' : ''"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openDistDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDistDropdown" style="display: none; max-height: 12rem;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200] max-h-48 overflow-y-auto dropdown-scroll">
                                <div @click="formData.max_distribution = ''; openDistDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Select</div>
                                <template x-for="num in [10, 20, 30, 40, 50, 60, 120, 0, 1, 55, 70]" :key="num">
                                    <div @click="formData.max_distribution = num; openDistDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors" x-text="num"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Line -->
                <hr class="border-gray-700/50 mb-4">

                <!-- Table Container (Taskboard Style) -->
                <div class="flex-1 overflow-hidden flex flex-col border border-gray-700/50 rounded-xl bg-gray-900 shadow-sm relative min-h-[250px]">
                    
                    <!-- Table Controls -->
                    <div class="px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex justify-between items-center gap-4">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <select x-model="tableEntries" class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6 pl-1 outline-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">entries</span>
                        </div>

                        <div class="relative w-full sm:w-64 group">
                            <span class="absolute left-min text-[10px] font-bold text-gray-500 uppercase -translate-x-12 top-1/2 -translate-y-1/2">Search:</span>
                            <input type="text" x-model="modalSearchQuery"
                                class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600 shadow-inner"
                                placeholder="">
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-800 sticky top-0 z-10 border-b border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 w-12 text-center border-r border-gray-700/30">
                                        <div class="flex justify-center flex-col items-center">
                                            <i class="bx bx-sort text-[10px] text-gray-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">ID <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">Agent <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">Name <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap">
                                        <div class="flex items-center justify-between gap-1">Email <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($modalUsers as $mUser)
                                    <tr class="hover:bg-blue-500/[0.05] transition-colors cursor-pointer" 
                                        @click="toggleSelection({{ $mUser->id }})"
                                        x-show="modalSearchQuery === '' || '{{ strtolower($mUser->username ?? $mUser->name) }}'.includes(modalSearchQuery.toLowerCase()) || '{{ strtolower($mUser->name) }}'.includes(modalSearchQuery.toLowerCase()) || '{{ strtolower($mUser->email) }}'.includes(modalSearchQuery.toLowerCase())">
                                        
                                        <td class="px-4 py-3 text-center border-r border-gray-700/30">
                                            <div class="flex justify-center items-center h-full">
                                                <div class="w-5 h-5 rounded border border-gray-600 flex items-center justify-center transition-all bg-gray-800"
                                                    :class="selectedUsers.includes({{ $mUser->id }}) ? 'bg-blue-500 border-blue-500' : ''">
                                                    <i class="bx bx-check text-white text-sm" x-show="selectedUsers.includes({{ $mUser->id }})"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm font-medium text-gray-300">{{ $mUser->id }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm font-medium text-white">{{ $mUser->username ?? str_replace(' ', '_', $mUser->name) }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm text-gray-300">{{ $mUser->name }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-400">{{ $mUser->email }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl gap-4">
                <button @click="open = false"
                    class="px-8 py-2.5 rounded-full bg-[#f43f5e] hover:bg-rose-600 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none">
                    Cancel
                </button>
                <button @click="saveAgentEmail()"
                    class="px-8 py-2.5 rounded-full bg-[#60a5fa] hover:bg-blue-500 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none min-w-[120px] flex justify-center">
                    <span>Submit</span>
                </button>
            </div>
        </div>
    </div>


    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-corner { background: transparent; }

        .custom-scrollbar-light {
            scrollbar-width: thin;
            scrollbar-color: #6b7280 #1f2937;
        }
        .custom-scrollbar-light::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar-light::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .custom-scrollbar-light::-webkit-scrollbar-thumb { background-color: #6b7280; border-radius: 10px; border: 2px solid transparent; background-clip: content-box; }
        .custom-scrollbar-light::-webkit-scrollbar-corner { background: transparent; }

        .dropdown-scroll {
            scrollbar-width: thin;
            scrollbar-color: #9ca3af #374151;
        }
        .dropdown-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .dropdown-scroll::-webkit-scrollbar-track {
            background: #374151;
            border-radius: 4px;
        }
        .dropdown-scroll::-webkit-scrollbar-thumb {
            background-color: #9ca3af;
            border-radius: 4px;
        }
        .dropdown-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #d1d5db;
        }
        
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openAgentEmailModal() {
            window.dispatchEvent(new CustomEvent('agent-email-modal'));
        }

        function editAgentEmailModal(userObj) {
            window.dispatchEvent(new CustomEvent('agent-email-modal', {
                detail: { userObj, isEdit: true }
            }));
        }

        function deleteAgentEmail(id) {
            Swal.fire({
                title: 'Hapus Agent Email?',
                text: "Data setelan agent ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#f43f5e',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showToast('Data berhasil dihapus (Simulasi).', 'success');
                }
            })
        }

        function agentEmailModalData() {
            return {
                open: false,
                isEditMode: false,
                modalSearchQuery: '',
                tableEntries: '10',
                selectedUsers: [],
                formData: {
                    account_email: '',
                    max_distribution: ''
                },

                initData(detail) {
                    this.modalSearchQuery = '';
                    this.selectedUsers = [];
                    this.formData = {
                        account_email: '',
                        max_distribution: ''
                    };
                    
                    if (detail && detail.isEdit && detail.userObj) {
                        this.isEditMode = true;
                        this.selectedUsers = [detail.userObj.id];
                        this.formData.account_email = 'club.indonesia@nespresso.co.id';
                        this.formData.max_distribution = 20;
                    } else {
                        this.isEditMode = false;
                    }
                },

                toggleSelection(id) {
                    const idx = this.selectedUsers.indexOf(id);
                    if (idx > -1) {
                        this.selectedUsers.splice(idx, 1);
                    } else {
                        this.selectedUsers.push(id);
                    }
                },

                saveAgentEmail() {
                    if (this.selectedUsers.length === 0) {
                        showToast('Harap pilih minimal 1 Agent', 'error');
                        return;
                    }
                    if (!this.formData.account_email) {
                        showToast('Harap pilih Account Email', 'error');
                        return;
                    }
                    if (this.formData.max_distribution === '') {
                        showToast('Harap pilih Maximal Distribution Data', 'error');
                        return;
                    }

                    // Simulated Saving Process
                    this.open = false;
                    showToast('Setting Agent Email berhasil disimpan!', 'success');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;

            let iconClass = 'bx-check-circle';
            let iconColor = 'text-emerald-500';
            let bgLine = 'bg-emerald-500';

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
