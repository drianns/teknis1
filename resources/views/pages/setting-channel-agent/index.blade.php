@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none menu-application-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Setting Channel Layer 1</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Application</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Setting Channel Agent</span>
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
                            onclick="openAddModal()">
                            <i class='bx bx-plus text-lg'></i> Add Agent Channel
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="data-table w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    ID</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    UserName</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Sub Menu</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Detail Menu</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Url</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Status</th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @forelse($settingChannelAgents as $agent)
                                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                    <td class="px-3 py-3 whitespace-nowrap font-mono text-blue-400 font-medium">
                                        {{ $agent->id }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">
                                        {{ $agent->user ? $agent->user->name : '-' }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-300">{{ $agent->sub_menu }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-300">{{ $agent->detail_menu }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">{{ $agent->url }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        @if ($agent->status == 'Yes')
                                            <span
                                                class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/30">Aktif</span>
                                        @else
                                            <span
                                                class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-semibold border border-red-500/30">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-center">
                                        <div class="action-dropdown relative flex justify-center"
                                            x-data="{ dropdownOpen: false }">
                                            <button @click.stop="dropdownOpen = !dropdownOpen"
                                                class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-all text-gray-400 hover:text-white">
                                                <i class='bx bx-dots-vertical-rounded'></i>
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
                                                    @click="editAgent({{ $agent->id }}, '{{ $agent->user_id }}', '{{ $agent->menu }}', '{{ $agent->sub_menu }}', '{{ $agent->detail_menu }}', '{{ $agent->url }}', '{{ $agent->status }}'); dropdownOpen = false">
                                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> <span
                                                        class="font-medium">Edit</span>
                                                </button>
                                                <button
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors"
                                                    @click="deleteAgent({{ $agent->id }}); dropdownOpen = false">
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
                                        No agents found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="pagination-info text-sm text-gray-500">
                        Showing <span class="text-white font-bold">{{ $settingChannelAgents->firstItem() ?? 0 }}</span> to
                        <span class="text-white font-bold">{{ $settingChannelAgents->lastItem() ?? 0 }}</span> of <span
                            class="text-white font-bold">{{ $settingChannelAgents->total() }}</span> entries
                    </div>
                    <div>
                        {{ $settingChannelAgents->appends(request()->query())->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Modal Component -->
    <div id="agent-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="agentModalData()" x-show="open" @agent-modal.window="open = true; loadData($event.detail)"
        style="display: none;">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <!-- Modal Panel -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl relative z-10 flex flex-col max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
                <h3 class="text-xl font-bold text-white tracking-tight"
                    x-text="isEdit ? 'Edit Setting Channel Agent' : 'Form Data Setting Channel Agent'"></h3>
                <button @click="open = false"
                    class="text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body -->
            <form @submit.prevent="saveAgent" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 text-sm text-gray-300 space-y-5">

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">UserName</label>
                        <div class="relative">
                            <select x-model="formData.user_id"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Pilih</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Menu</label>
                        <div class="relative">
                            <select x-model="formData.menu"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Pilih</option>
                                <option value="Master Data">Master Data</option>
                                <option value="Apps">Apps</option>
                                <option value="Dashboard">Dashboard</option>
                                <option value="Setup Sosial Media">Setup Sosial Media</option>
                                <option value="Report">Report</option>
                                <option value="Management User">Management User</option>
                                <option value="Master Customer">Master Customer</option>
                                <option value="File Manager">File Manager</option>
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Sub Menu</label>
                        <div class="relative">
                            <select x-model="formData.sub_menu"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Pilih</option>
                                <option value="Email">Email</option>
                                <option value="Data Type">Data Type</option>
                                <option value="Data Category">Data Category</option>
                                <option value="Data Meta">Data Meta</option>
                                <option value="Data Sub Category">Data Sub Category</option>
                                <option value="Knowledge Base">Knowledge Base</option>
                                <option value="Setting Agent Email">Setting Agent Email</option>
                                <option value="Data Source">Data Source</option>
                                <option value="Channel Ticket">Channel Ticket</option>
                                <option value="Outbound Call">Outbound Call</option>
                                <option value="Data Activity">Data Activity</option>
                                <option value="Data User Application">Data User Application</option>
                                <option value="Department Escalation Unit">Department Escalation Unit</option>
                                <option value="Data Brand Name">Data Brand Name</option>
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Detail Menu</label>
                        <div class="relative">
                            <select x-model="formData.detail_menu"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Pilih</option>
                                <option value="Inbox Email">Inbox Email</option>
                                <option value="History Email">History Email</option>
                                <option value="Add Asset">Add Asset</option>
                                <option value="Allocation">Allocation</option>
                                <option value="Transfer Asset">Transfer Asset</option>
                                <option value="Accession Detail">Accession Detail</option>
                                <option value="View Asset">View Asset</option>
                                <option value="Scrap Request">Scrap Request</option>
                                <option value="Borrow Approve">Borrow Approve</option>
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Url</label>
                        <input type="text" x-model="formData.url"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="Url">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Status</label>
                        <div class="relative">
                            <select x-model="formData.status"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-800 flex justify-between gap-3 bg-gray-900 rounded-b-2xl">
                    <button type="button" @click="open = false"
                        class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 border border-red-500/50 text-white transition-colors font-semibold text-sm shadow-lg shadow-red-500/20">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-500/20 min-w-[100px] flex justify-center">
                        <span x-text="'Submit'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div id="toast-container" class="fixed top-6 right-6 z-[110] flex flex-col gap-3"></div>

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

        let searchTimeout;
        function debounceSearch(query) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location);
                url.searchParams.set('search', query);
                url.searchParams.delete('page');
                window.location = url;
            }, 500);
        }

        function changeEntriesPerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            window.location = url;
        }

        function openAddModal() {
            window.dispatchEvent(new CustomEvent('agent-modal'));
        }

        function editAgent(id, user_id, menu, sub_menu, detail_menu, url, status) {
            window.dispatchEvent(new CustomEvent('agent-modal', {
                detail: { id, user_id, menu, sub_menu, detail_menu, url, status }
            }));
        }

        function deleteAgent(id) {
            if (!confirm('Are you sure you want to delete this agent channel?')) return;

            fetch(`/setting-channel-agent/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json().catch(() => ({ success: res.ok })))
                .then(data => {
                    if (data.success !== false) {
                        showToast('Deleted successfully', 'success');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast('Failed to delete', 'error');
                    }
                });
        }

        function agentModalData() {
            return {
                open: false,
                isEdit: false,
                formData: {
                    id: null,
                    user_id: '',
                    menu: '',
                    sub_menu: '',
                    detail_menu: '',
                    url: '',
                    status: ''
                },

                loadData(detail) {
                    if (detail && detail.id) {
                        this.isEdit = true;
                        this.formData = { ...detail };
                    } else {
                        this.isEdit = false;
                        this.formData = {
                            id: null,
                            user_id: '',
                            menu: '',
                            sub_menu: '',
                            detail_menu: '',
                            url: '',
                            status: ''
                        };
                    }
                },

                saveAgent() {
                    if (!this.formData.user_id || !this.formData.url || !this.formData.status) {
                        showToast('Please fill all required fields (UserName, Url, Status)', 'error');
                        return;
                    }

                    const url = this.isEdit
                        ? `/setting-channel-agent/${this.formData.id}`
                        : `/setting-channel-agent/store`;

                    const method = this.isEdit ? 'PUT' : 'POST';

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    })
                        .then(res => {
                            // We redirect in controller so check for ok status
                            if (res.ok) {
                                showToast(this.isEdit ? 'Updated successfully' : 'Created successfully', 'success');
                                setTimeout(() => location.reload(), 800);
                            } else {
                                showToast('Server error', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Network error', 'error');
                        });
                }
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-emerald-500/10' : 'bg-red-500/10';
            const borderColor = type === 'success' ? 'border-emerald-500/20' : 'border-red-500/20';
            const iconColor = type === 'success' ? 'text-emerald-400' : 'text-red-400';
            const icon = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';

            toast.className = `toast-enter flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg backdrop-blur-md ${bgColor} ${borderColor}`;

            toast.innerHTML = `
                                <i class='bx ${icon} text-xl ${iconColor}'></i>
                                <p class="text-sm font-medium text-white shadow-sm">${message}</p>
                            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.replace('toast-enter', 'toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
@endsection