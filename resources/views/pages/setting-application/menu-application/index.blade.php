@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none menu-application-page">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Menu Application</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Application</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Menu Application</span>
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
                                placeholder="Search menus..." value="{{ request('search') }}"
                                oninput="debounceSearch(this.value)" />
                            <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                        </div>

                        <button
                            class="btn-add px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all"
                            onclick="openAddMenuModal()">
                            <i class='bx bx-plus text-lg'></i> Add Menu
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="data-table w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu Name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Url</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Number</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Icon</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Detail Page</th>
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

    

    @push('scripts')
<script>
        const AJAX_URL = '{{ route("menu.application.getData") }}';
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
                document.getElementById('table-body').innerHTML = '<tr><td colspan="7" class="p-12 text-center text-red-500">Failed to load data.</td></tr>';
            });
        }

        function renderTable(menus) {
            const tbody = document.getElementById('table-body');
            if (!menus || menus.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-12 text-center text-gray-500">No menus found.</td></tr>';
                return;
            }

            tbody.innerHTML = menus.map(menu => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                    <td class="px-3 py-3 whitespace-nowrap font-mono text-blue-400 font-medium">#${String(menu.id).padStart(3, '0')}</td>
                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">${esc(menu.menu_name)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">${esc(menu.url)}</td>
                    <td class="px-3 py-3 whitespace-nowrap">${menu.number}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 flex items-center gap-2">
                        <i class='bx ${menu.icon} text-lg'></i> ${esc(menu.icon)}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="${menu.type === 'Yes' ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-500 border-red-500/30'} px-2.5 py-1 rounded-full text-xs font-medium border">
                            ${menu.type}
                        </span>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                        <div class="action-dropdown relative flex justify-center" x-data="{ dropdownOpen: false }">
                            <button @click.stop="dropdownOpen = !dropdownOpen" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-all text-gray-400 hover:text-white">
                                <i class='bx bx-dots-vertical-rounded'></i>
                            </button>
                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-transition class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden text-left" style="display: none;">
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3 transition-colors border-b border-gray-700/50" onclick="editMenu(${menu.id})">
                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> Edit
                                </button>
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors" onclick="deleteMenu(${menu.id})">
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

        function openAddMenuModal() {
            window.dispatchEvent(new CustomEvent('menu-modal'));
        }

        function editMenu(menuId) {
            window.dispatchEvent(new CustomEvent('menu-modal', { detail: { menuId: menuId } }));
        }

        function deleteMenu(menuId) {
            if (!confirm('Are you sure you want to delete this menu?')) return;
            showLoading('Deleting...');
            fetch(`/menu-application/${menuId}`, {
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

        // Alpine JS for Modal
        function menuModalData() {
            return {
                open: false, isEdit: false,
                formData: { id: null, menuName: '', number: '', url: '', icon: '', type: '' },
                resetForm() {
                    this.isEdit = false;
                    this.formData = { id: null, menuName: '', number: '', url: '', icon: '', type: '' };
                },
                loadMenuData(detail) {
                    if (detail && detail.menuId) {
                        this.isEdit = true;
                        showLoading('Loading details...');
                        fetch(`/menu-application/${detail.menuId}`, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(data => {
                            hideLoading();
                            this.formData = { id: data.id, menuName: data.menu_name, number: data.number, url: data.url || '', icon: data.icon, type: data.type };
                        })
                        .catch(() => { hideLoading(); showToast('Failed to load', 'error'); this.open = false; });
                    } else { this.resetForm(); }
                },
                saveMenu() {
                    if (!this.formData.menuName || !this.formData.number || !this.formData.icon || !this.formData.type) {
                        showToast('Fill required fields', 'error'); return;
                    }
                    const url = this.isEdit ? `/menu-application/${this.formData.id}` : '/menu-application/store';
                    const method = this.isEdit ? 'PUT' : 'POST';
                    showLoading('Saving...');
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ menu_name: this.formData.menuName, number: this.formData.number, url: this.formData.url, icon: this.formData.icon, type: this.formData.type })
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
@endpush
@endsection