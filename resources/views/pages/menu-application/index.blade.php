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
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    ID <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Menu Name <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Url <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Number <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Icon <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Detail Page <i class='bx bx-sort text-gray-600 ml-1'></i></th>
                                <th
                                    class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @forelse($menus as $menu)
                                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                    <td class="px-3 py-3 whitespace-nowrap font-mono text-blue-400 font-medium">
                                        <span class="hover:underline">#{{ str_pad($menu->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">{{ $menu->menu_name }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">{{ $menu->url }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">{{ $menu->number }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 flex items-center gap-2">
                                        <i class='bx {{ $menu->icon }} text-lg'></i>
                                        {{ $menu->icon }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        @if ($menu->type == 'Yes')
                                            <span
                                                class="bg-green-500/20 text-green-400 px-2.5 py-1 rounded-full text-xs font-medium border border-green-500/30">Yes</span>
                                        @else
                                            <span
                                                class="bg-red-500/20 text-red-500 px-2.5 py-1 rounded-full text-xs font-medium border border-red-500/30">No</span>
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
                                                    @click="editMenu({{ $menu->id }}); dropdownOpen = false">
                                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> <span
                                                        class="font-medium">Edit</span>
                                                </button>
                                                <button
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors"
                                                    @click="deleteMenu({{ $menu->id }}); dropdownOpen = false">
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
                                        No menus found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="pagination-info text-sm text-gray-500">
                        Showing <span class="text-white font-bold">{{ $menus->firstItem() ?? 0 }}</span> to <span
                            class="text-white font-bold">{{ $menus->lastItem() ?? 0 }}</span> of <span
                            class="text-white font-bold">{{ $menus->total() }}</span> entries
                    </div>
                    <div>
                        {{ $menus->appends(request()->query())->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Modal Component -->
    <div id="menu-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-data="menuModalData()"
        x-show="open" @menu-modal.window="open = true; loadMenuData($event.detail)" style="display: none;">

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
                <h3 class="text-xl font-bold text-white"
                    x-text="isEdit ? 'Edit Menu Application' : 'Form Add Menu Application'"></h3>
                <button @click="open = false"
                    class="text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 text-sm text-gray-300 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Menu Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="formData.menuName"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="e.g. Master Data">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Number <span class="text-red-500">*</span></label>
                        <input type="number" x-model="formData.number"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="e.g. 1">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="font-medium text-gray-400 ml-1">Url</label>
                    <div class="relative">
                        <i class='bx bx-link absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                        <input type="text" x-model="formData.url"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl pl-11 pr-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                            placeholder="e.g. apps.ticketing">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Icon <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class='bx absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg'
                                :class="formData.icon || 'bx-image'"></i>
                            <input type="text" x-model="formData.icon"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl pl-11 pr-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-500"
                                placeholder="e.g. bx-user">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-gray-400 ml-1">Type (Detail Page) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <select x-model="formData.type"
                                class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition-colors appearance-none cursor-pointer">
                                <option value="">Select Type</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <i
                                class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-800 flex justify-end gap-3 bg-gray-900 rounded-b-2xl">
                <button @click="open = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-700 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors font-semibold text-sm">
                    Cancel
                </button>
                <button @click="saveMenu()"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-500/20 min-w-[100px] flex justify-center">
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
        function openAddMenuModal() {
            window.dispatchEvent(new CustomEvent('menu-modal'));
        }

        function editMenu(menuId) {
            window.dispatchEvent(new CustomEvent('menu-modal', {
                detail: { menuId: menuId }
            }));
        }

        function deleteMenu(menuId) {
            if (!confirm('Are you sure you want to delete this menu? This action cannot be undone.')) {
                return;
            }

            showLoading('Deleting menu record...');

            fetch(`/menu-application/${menuId}`, {
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
                        showToast(data.message || 'Failed to delete menu', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Server error during deletion.', 'error');
                });
        }

        // Alpine JS Component Scope
        function menuModalData() {
            return {
                open: false,
                isEdit: false,
                formData: {
                    id: null,
                    menuName: '',
                    number: '',
                    url: '',
                    icon: '',
                    type: ''
                },

                resetForm() {
                    this.isEdit = false;
                    this.formData = {
                        id: null,
                        menuName: '',
                        number: '',
                        url: '',
                        icon: '',
                        type: ''
                    };
                },

                loadMenuData(detail) {
                    if (detail && detail.menuId) {
                        this.isEdit = true;
                        showLoading('Loading details...');

                        fetch(`/menu-application/${detail.menuId}`, {
                            headers: { 'Accept': 'application/json' }
                        })
                            .then(res => res.json())
                            .then(data => {
                                hideLoading();
                                this.formData = {
                                    id: data.id,
                                    menuName: data.menu_name,
                                    number: data.number,
                                    url: data.url || '',
                                    icon: data.icon,
                                    type: data.type
                                };
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error loading menu:', error);
                                showToast('Failed to load menu data', 'error');
                                this.open = false;
                            });
                    } else {
                        this.resetForm();
                    }
                },

                saveMenu() {
                    // Validation
                    if (!this.formData.menuName || !this.formData.number || !this.formData.icon || !this.formData.type) {
                        showToast('Please fill in all required fields marked with *', 'error');
                        return;
                    }

                    const url = this.isEdit
                        ? `/menu-application/${this.formData.id}`
                        : '/menu-application/store';

                    const method = this.isEdit ? 'PUT' : 'POST';

                    showLoading(this.isEdit ? 'Updating menu...' : 'Saving new menu...');

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            menu_name: this.formData.menuName,
                            number: this.formData.number,
                            url: this.formData.url,
                            icon: this.formData.icon,
                            type: this.formData.type
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showToast(this.isEdit ? 'Menu updated successfully' : 'Menu created successfully', 'success');
                                this.open = false;
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast(data.message || 'Failed to save menu', 'error');
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