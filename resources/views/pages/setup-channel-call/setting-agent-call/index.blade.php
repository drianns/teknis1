@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none setting-agent-call-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Setting Agent Call</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Call</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Setting Agent Call</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full h-full">
            <div class="flex flex-col bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl h-full min-h-0 relative" x-data="{ userSearchQuery: '' }">
                
                <!-- Top Toolbar inside Panel -->
                <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50">
                    <button onclick="openAgentCallModal()"
                        class="px-5 py-2 flex items-center gap-2 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-full text-white text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        <i class='bx bx-plus text-blue-400 font-bold'></i> Add Setting Agent Call
                    </button>

                    <div class="flex items-center gap-4">
                        <div class="relative w-72">
                            <input type="text" id="agent-call-search"
                                class="bg-gray-800 border-gray-700 rounded-full pl-4 pr-10 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full placeholder-gray-500 shadow-inner"
                                placeholder="Search Name or Email..." oninput="debounceSearch(this.value)" />
                            <i class='bx bx-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-500'></i>
                        </div>
                        <select id="entries-per-page" onchange="loadGrid(1)"
                            class="bg-gray-800 border-gray-700 rounded-lg px-3 py-1 text-xs text-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <!-- User Cards Grid -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                    <div id="agent-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                        <!-- Populated via AJAX -->
                        <div class="col-span-full py-20 flex flex-col items-center justify-center">
                            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                            <p class="text-gray-500">Loading agents...</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Pagination -->
                <div class="p-4 border-t border-gray-800 bg-gray-800/30 flex justify-between items-center px-6">
                    <div id="grid-pagination-info" class="text-xs text-gray-500"></div>
                    <div id="grid-pagination-links"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal for Setting Agent Call -->
    <div id="agent-call-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="agentCallModalData()" x-show="open" @agent-call-modal.window="open = true; initData($event.detail)"
        style="display: none;">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-2xl relative z-10 flex flex-col overflow-hidden"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                <h3 class="text-lg font-bold text-gray-200">Form Setting Agent Call</h3>
                <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body Area -->
            <div class="p-6 bg-gray-800/30 overflow-y-auto custom-scrollbar max-h-[70vh]">
                <div class="space-y-4">
                    
                    <!-- Call Type Select -->
                    <div class="space-y-1.5" x-data="{ openDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">Call Type</label>
                        <div class="relative">
                            <button type="button" @click="openDropdown = !openDropdown"
                                @click.outside="openDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="formData.call_type || 'Select'" class="truncate" :class="!formData.call_type ? 'text-gray-400' : ''"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDropdown" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200]">
                                <div @click="formData.call_type = ''; openDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Select</div>
                                <div @click="formData.call_type = 'Inbound Call'; openDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Inbound Call</div>
                                <div @click="formData.call_type = 'Outbound Call'; openDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Outbound Call</div>
                                <div @click="formData.call_type = 'Inbound & Outbound Call'; openDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Inbound & Outbound Call</div>
                            </div>
                        </div>
                    </div>

                    <!-- User Agent Select -->
                    <div class="space-y-1.5" x-data="{ openUserDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">User Agent</label>
                        <div class="relative">
                            <button type="button" @click="openUserDropdown = !openUserDropdown"
                                @click.outside="openUserDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="selectedUserObj ? selectedUserObj.name : ''" class="truncate"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openUserDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openUserDropdown" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200] max-h-60 overflow-y-auto custom-scrollbar">
                                <div class="p-2 sticky top-0 bg-gray-800 border-b border-gray-700">
                                    <input type="text" x-model="userSearch" placeholder="Search user..." class="w-full bg-gray-700 border border-gray-600 text-white rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <template x-for="usr in filteredUsers" :key="usr.id">
                                    <div @click="selectUser(usr); openUserDropdown = false"
                                        class="px-4 py-2.5 text-sm cursor-pointer hover:bg-blue-600 transition-colors"
                                        :class="(selectedUserObj && selectedUserObj.id === usr.id) ? 'bg-blue-600 text-white' : 'text-gray-200'">
                                        <div class="flex flex-col">
                                            <span x-text="usr.name"></span>
                                            <span class="text-xs text-gray-400" x-text="usr.email" :class="(selectedUserObj && selectedUserObj.id === usr.id) ? 'text-gray-200' : 'text-gray-400'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Password EPIC -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-blue-300 text-sm">Password EPIC</label>
                        <input type="password" x-model="formData.password_epic"
                            class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm placeholder-gray-500"
                            placeholder="EPIC Password">
                    </div>

                    <!-- User PABX -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-blue-300 text-sm">User PABX</label>
                        <input type="text" x-model="formData.user_pabx"
                            class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm placeholder-gray-500"
                            placeholder="PABX User">
                    </div>

                    <!-- Password PABX -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-blue-300 text-sm">Password PABX</label>
                        <input type="password" x-model="formData.password_pabx"
                            class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm placeholder-gray-500"
                            placeholder="PABX Password">
                    </div>

                    <!-- PIN PABX -->
                    <div class="space-y-1.5">
                        <label class="font-semibold text-blue-300 text-sm">PIN PABX</label>
                        <input type="text" x-model="formData.pin_pabx"
                            class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm placeholder-gray-500"
                            placeholder="PABX PIN">
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl gap-4">
                <button @click="open = false"
                    class="px-8 py-2.5 rounded-full bg-[#f43f5e] hover:bg-rose-600 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none">
                    Cancel
                </button>
                <button @click="saveAgentCall()"
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

    <script>
        const AJAX_URL = '{{ route("setting.agent.call.getData") }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentPage = 1, searchTimer = null;

        function loadGrid(page = 1) {
            currentPage = page;
            const search = document.getElementById('agent-call-search').value;
            const perPage = document.getElementById('entries-per-page').value;
            const container = document.getElementById('agent-grid-container');

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderCards(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Grid load error:', err);
                container.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Failed to load data.</div>';
            });
        }

        function renderCards(items) {
            const container = document.getElementById('agent-grid-container');
            if (!items || items.length === 0) {
                container.innerHTML = '<div class="col-span-full flex flex-col items-center justify-center p-12 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed"><i class="bx bx-user-x text-5xl mb-3 opacity-50"></i><p>No agents found.</p></div>';
                return;
            }

            container.innerHTML = items.map(user => `
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 pt-8 relative group cursor-default">
                    <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-row items-center justify-center gap-3 backdrop-blur-[2px] z-10 rounded-2xl pointer-events-none group-hover:pointer-events-auto">
                        <button onclick='editAgentCallModal(${JSON.stringify(user)})' title="Edit" class="w-10 h-10 rounded-[10px] bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-600 hover:-translate-y-1">
                            <i class='bx bx-edit text-xl'></i>
                        </button>
                        <button onclick="syncAgentCall(${user.id})" title="Sync / Reset Password" class="w-10 h-10 rounded-[10px] bg-emerald-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-75 hover:bg-emerald-600 hover:-translate-y-1">
                            <i class='bx bx-refresh text-2xl'></i>
                        </button>
                        <button onclick="deleteAgentCall(${user.id})" title="Delete" class="w-10 h-10 rounded-[10px] bg-red-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-150 hover:bg-red-600 hover:-translate-y-1">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                    <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm z-0">Not Login</div>
                    <div class="w-24 h-24 flex-shrink-0 rounded-full bg-teal-500/20 mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors z-0">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=2dd4bf&color=fff&size=128" alt="${esc(user.name)}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-white font-semibold text-center text-[15px] mb-1 px-2 truncate w-full group-hover:text-blue-400 transition-colors z-0">${esc(user.name)}</h3>
                    <p class="text-gray-400 text-[11px] text-center truncate w-full px-2 mb-4 z-0">User 101${user.id % 99} - Pin 101${user.id % 99} - Epic Not Login</p>
                    <div class="bg-blue-500 text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-sm select-none z-0 hover:-translate-y-0.5 transition-transform">Inbound & Outbound Call</div>
                </div>
            `).join('');
        }

        function renderPagination(data) {
            document.getElementById('grid-pagination-info').innerText = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} entries`;
            const container = document.getElementById('grid-pagination-links');
            container.innerHTML = '';
            if (data.last_page <= 1) return;
            const nav = document.createElement('nav'); nav.className = 'flex gap-2';
            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button'); btn.innerHTML = label; btn.disabled = disabled;
                btn.className = `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all ${active ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700/50'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadGrid(page);
                return btn;
            };
            nav.appendChild(createBtn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, data.current_page <= 1));
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    nav.appendChild(createBtn(i, i, false, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const dots = document.createElement('span'); dots.className = 'w-5 h-10 flex items-center justify-center text-gray-600';
                    dots.innerText = '...'; nav.appendChild(dots);
                }
            }
            nav.appendChild(createBtn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, data.current_page >= data.last_page));
            container.appendChild(nav);
        }

        function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
        function debounceSearch(q) { clearTimeout(searchTimer); searchTimer = setTimeout(() => loadGrid(1), 500); }

        function openAgentCallModal() { window.dispatchEvent(new CustomEvent('agent-call-modal')); }
        function editAgentCallModal(userObj) { window.dispatchEvent(new CustomEvent('agent-call-modal', { detail: { userObj } })); }

        function syncAgentCall(id) {
            Swal.fire({
                title: 'Sync Agent Call?', text: "Anda akan mensinkronisasi data/kredensial agen ini.", icon: 'question',
                showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#f43f5e', confirmButtonText: 'Ya, Sinkronisasi!'
            }).then((result) => { if (result.isConfirmed) showToast('Sinkronisasi berhasil (Simulasi).'); });
        }

        function deleteAgentCall(id) {
            Swal.fire({
                title: 'Hapus Agent Call?', text: "Agent Call ini akan dihapus secara permanen!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3b82f6', cancelButtonColor: '#f43f5e', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) showToast('Data berhasil dihapus (Simulasi).'); });
        }

        function agentCallModalData() {
            return {
                open: false, allUsers: [], userSearch: '', selectedUserObj: null,
                formData: { user_id: '', call_type: '', password_epic: '', user_pabx: '', password_pabx: '', pin_pabx: '' },
                async init() {
                    const res = await fetch(AJAX_URL + '?per_page=100');
                    const data = await res.json();
                    this.allUsers = data.data;
                },
                get filteredUsers() {
                    if (this.userSearch === '') return this.allUsers.slice(0, 50);
                    return this.allUsers.filter(usr => usr.name.toLowerCase().includes(this.userSearch.toLowerCase()) || usr.email.toLowerCase().includes(this.userSearch.toLowerCase())).slice(0, 50);
                },
                selectUser(usr) { this.selectedUserObj = usr; this.formData.user_id = usr ? usr.id : ''; },
                initData(detail) {
                    this.userSearch = ''; this.formData = { user_id: '', call_type: '', password_epic: '', user_pabx: '', password_pabx: '', pin_pabx: '' };
                    if (detail && detail.userObj) { this.selectUser(detail.userObj); this.formData.call_type = 'Inbound & Outbound Call'; }
                    else { this.selectedUserObj = null; this.selectUser(null); }
                },
                saveAgentCall() {
                    if (!this.formData.user_id) { showToast('Please select a User Agent', 'error'); return; }
                    this.open = false; showToast('Setting Agent Call saved successfully!');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 bg-gray-900 border ${type === 'success' ? 'border-green-500/50 text-green-400' : 'border-red-500/50 text-red-500'} shadow-2xl rounded-xl flex items-center p-4 z-[600] transition-all transform translate-x-full`;
            toast.innerHTML = `<i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-2xl mr-3'></i><span class="font-semibold text-sm">${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateX(0)', 10);
            setTimeout(() => { toast.style.transform = 'translateX(full)'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => loadGrid(1));
    </script>
@endsection
