@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none sub-menu-application-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Sub Menu Application</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Application</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Sub Menu Application</span>
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
                                placeholder="Search sub menus..." value="{{ request('search') }}"
                                oninput="debounceSearch(this.value)" />
                            <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                        </div>

                        <button
                            class="btn-add px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all"
                            onclick="openAddSubMenuModal()">
                            <i class='bx bx-plus text-lg'></i> Add Sub Menu Application
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="data-table w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-56 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Sub Menu Name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-56 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Url</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Detail Page</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <tr><td colspan="6" class="p-12 text-center text-gray-500">Loading data...</td></tr>
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
        $menuOptions = [
            'Master Data',
            'Apps',
            'Dashboard',
            'Setup Sosial Media',
            'Report',
            'Management User',
            'Master Customer',
            'File Manager',
            'Setting Email System',
            'Setup Channel Call',
            'Setting EPIC System',
            'Simulasi Data Masuk',
            'Setting Application',
            'Setup Channel Email',
            'Channel',
            'Data Login',
            'Log Out',
            'Welcome Call',
            'Setting Sosial Media',
            'Multichannel',
            'Ticketing System',
            'Setup Channel WA',
            'Taskboard',
            'Teleconfirm',
            'Modul Outbound Call',
            'Collection',
            'Outbound Call',
            'Apps UIDESK',
            'Recording',
            'Sosial Media',
            'Bantu Dagang',
            'Wallboard'
        ];
    @endphp

    <!-- Alpine Modal Component -->
    <div id="sub-menu-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="subMenuModalData()" x-show="open" @sub-menu-modal.window="open = true; loadSubMenuData($event.detail)"
        style="display: none;">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <!-- Modal Panel -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl relative z-10 flex flex-col max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
                <h3 class="text-xl font-bold text-white"
                    x-text="isEdit ? 'Form Edit Sub Menu Application' : 'Form Add Sub Menu Application'"></h3>
                <button @click="open = false"
                    class="text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-visible flex-1 text-sm text-gray-300 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
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
                                @foreach($menuOptions as $option)
                                    <div @click="formData.menuName = '{{ $option }}'; openDropdown = false"
                                        class="px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-700 transition-colors"
                                        :class="formData.menuName === '{{ $option }}' ? 'text-blue-400 bg-blue-500/10 font-medium' : 'text-gray-300'">
                                        {{ $option }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-blue-400 ml-1">Sub Menu Name</label>
                        <input type="text" x-model="formData.subMenuName"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="Sub Menu Name">
                    </div>

                    <div class="space-y-1.5" x-data="{ openTypeDropdown: false }">
                        <label class="font-medium text-blue-400 ml-1">Type</label>
                        <div class="relative">
                            <button type="button" @click="openTypeDropdown = !openTypeDropdown"
                                @click.outside="openTypeDropdown = false"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 flex justify-between items-center focus:outline-none focus:border-blue-500 transition-colors cursor-pointer text-left">
                                <span x-text="formData.type || 'Select'" class="truncate"></span>
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
                <button @click="saveSubMenu()"
                    class="px-6 py-2.5 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm transition-all shadow-lg min-w-[100px] flex justify-center">
                    <span x-text="isEdit ? 'Update' : 'Save'"></span>
                </button>
            </div>
        </div>
    </div>

    @include('pages.setup-channel-email.partials._scrollbar')

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
    </style>

    <script>
        const AJAX_URL = '{{ route("sub.menu.application.getData") }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentPage = 1, searchTimer = null;

        function loadTable(page = 1) {
            currentPage = page;
            const search = document.getElementById('table-search').value;
            const perPage = document.getElementById('entries-per-page').value;

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Load error:', err);
                document.getElementById('table-body').innerHTML = '<tr><td colspan="6" class="p-12 text-center text-red-500">Failed to load data.</td></tr>';
            });
        }

        function renderTable(items) {
            const tbody = document.getElementById('table-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-12 text-center text-gray-500">No sub menus found.</td></tr>';
                return;
            }

            tbody.innerHTML = items.map(item => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                    <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-300 font-medium">${item.id}</td>
                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">${esc(item.menu_name)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400">${esc(item.sub_menu_name)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">${esc(item.url)}</td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="${item.type === 'Yes' ? 'bg-teal-500' : 'bg-red-500'} text-white px-3 py-1 rounded-full text-xs shadow-sm">
                            ${item.type}
                        </span>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                        <div class="action-dropdown relative flex justify-center" x-data="{ dropdownOpen: false }">
                            <button @click.stop="dropdownOpen = !dropdownOpen" class="w-8 h-8 rounded-lg hover:bg-gray-800 flex items-center justify-center transition-all text-blue-400">
                                <i class='bx bx-dots-vertical-rounded text-xl'></i>
                            </button>
                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-transition class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden text-left" style="display: none;">
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3 transition-colors border-b border-gray-700/50" onclick="editSubMenu(${item.id})">
                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> Edit
                                </button>
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors" onclick="deleteSubMenu(${item.id})">
                                    <i class='bx bx-trash text-red-500 text-lg'></i> Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(data) {
            document.getElementById('pagination-info').innerHTML = `Showing <span class="text-white font-bold">${data.from || 0}</span> to <span class="text-white font-bold">${data.to || 0}</span> of <span class="text-white font-bold">${data.total}</span> entries`;
            const container = document.getElementById('pagination-links');
            container.innerHTML = '';
            
            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button');
                btn.innerHTML = label;
                btn.disabled = disabled;
                btn.className = `px-3 py-1 rounded-lg text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadTable(page);
                return btn;
            };

            container.appendChild(createBtn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, data.current_page <= 1, false));
            for (let i = Math.max(1, data.current_page - 1); i <= Math.min(data.last_page, data.current_page + 1); i++) {
                container.appendChild(createBtn(i, i, false, i === data.current_page));
            }
            container.appendChild(createBtn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, data.current_page >= data.last_page, false));
        }

        function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

        function debounceSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadTable(1), 500);
        }

        function changeEntriesPerPage() {
            loadTable(1);
        }

        // Global triggers
        function openAddSubMenuModal() {
            window.dispatchEvent(new CustomEvent('sub-menu-modal'));
        }

        function editSubMenu(id) {
            window.dispatchEvent(new CustomEvent('sub-menu-modal', { detail: { subMenuId: id } }));
        }

        function deleteSubMenu(id) {
            if (!confirm('Are you sure?')) return;
            showLoading('Deleting...');
            fetch(`/sub-menu-application/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast(data.message);
                    loadTable(currentPage);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => { hideLoading(); showToast('Error deleting', 'error'); });
        }

        // Alpine JS Component Scope
        function subMenuModalData() {
            return {
                open: false, isEdit: false,
                formData: { id: null, menuName: '', subMenuName: '', url: '', type: '' },
                resetForm() {
                    this.isEdit = false;
                    this.formData = { id: null, menuName: '', subMenuName: '', url: '', type: '' };
                },
                loadSubMenuData(detail) {
                    if (detail && detail.subMenuId) {
                        this.isEdit = true;
                        showLoading('Loading details...');
                        fetch(`/sub-menu-application/${detail.subMenuId}`, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(data => {
                            hideLoading();
                            this.formData = { id: data.id, menuName: data.menu_name, subMenuName: data.sub_menu_name, url: data.url || '', type: data.type };
                        })
                        .catch(() => { hideLoading(); showToast('Failed to load', 'error'); this.open = false; });
                    } else { this.resetForm(); }
                },
                saveSubMenu() {
                    if (!this.formData.menuName || !this.formData.subMenuName || !this.formData.type) {
                        showToast('Fill required fields', 'error'); return;
                    }
                    const url = this.isEdit ? `/sub-menu-application/${this.formData.id}` : '/sub-menu-application/store';
                    const method = this.isEdit ? 'PUT' : 'POST';
                    showLoading('Saving...');
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ menu_name: this.formData.menuName, sub_menu_name: this.formData.subMenuName, url: this.formData.url, type: this.formData.type })
                    })
                    .then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showToast(data.message);
                            this.open = false;
                            loadTable(this.isEdit ? currentPage : 1);
                        } else { showToast(data.message, 'error'); }
                    })
                    .catch(() => { hideLoading(); showToast('Error saving', 'error'); });
                }
            };
        }

        // UI Helpers
        function showLoading(msg = 'Processing...') {
            let overlay = document.getElementById('global-loader');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'global-loader';
                overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[200] opacity-0 transition-opacity duration-300';
                overlay.innerHTML = `<div class="bg-gray-900 p-8 rounded-2xl border border-gray-800 shadow-2xl flex flex-col items-center">
                    <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-white font-bold dynamic-msg">${msg}</p>
                </div>`;
                document.body.appendChild(overlay);
                setTimeout(() => overlay.style.opacity = '1', 10);
            } else { overlay.querySelector('.dynamic-msg').innerText = msg; }
        }
        function hideLoading() {
            const el = document.getElementById('global-loader');
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
        }
        function showToast(msg, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 px-6 py-3 rounded-xl border shadow-2xl z-[300] transition-all transform translate-x-full ${type === 'success' ? 'bg-gray-900 border-green-500/50 text-green-400' : 'bg-gray-900 border-red-500/50 text-red-500'}`;
            toast.innerHTML = `<div class="flex items-center gap-3"><i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl'></i><span class="font-semibold text-sm">${msg}</span></div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateX(0)', 10);
            setTimeout(() => { toast.style.transform = 'translateX(full)'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => loadTable(1));
    </script>
@endsection