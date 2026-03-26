@extends('layouts.app')

@section('content')
    <div class="px-6 pt-4 pb-6 min-h-screen"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <!-- Main Content -->
        <main class="flex-1 space-y-6">
            <!-- Header & Breadcrumb -->
            <div class="flex flex-col mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="bx bx-table text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-white">Data Taskboar Ticket Department</h1>
                </div>
                <nav class="flex text-sm text-gray-400 ml-0">
                    <a href="#" class="hover:text-blue-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="#" class="hover:text-blue-400">Apps</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-300">Ticketing Department</span>
                </nav>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-3 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="flex items-center text-gray-400 text-sm">
                        <span>Show</span>
                        <select id="perPageSelect"
                            class="mx-2 bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-500"></i>
                        </div>
                        <input type="text" id="searchInput"
                            class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-2.5"
                            placeholder="Search...">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col"
                                    class="px-3 py-3 font-bold cursor-pointer hover:text-white group whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        ID <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Ticket Number <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Name <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Kategori <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Department <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3 font-bold cursor-pointer hover:text-white group whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        SLA <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Agent <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Status <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Date <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold text-center whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody" class="divide-y divide-gray-800 bg-transparent">
                            <!-- Populated dynamically via JS AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="dataTableInfo">Showing 0 to 0 of 0 entries</span>
                    <div class="mt-2 md:mt-0" id="paginationContainer">
                        <!-- Javascript will populate pagination buttons here -->
                    </div>
                </div>
            </div>
        </main>

        <!-- Order ID Modal -->
        <div x-show="orderModalOpen" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm">
            <div x-show="orderModalOpen" @click.outside="orderModalOpen = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl w-full max-w-md p-6 relative">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Input Order ID</h3>
                    <button @click="orderModalOpen = false" class="text-gray-500 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <p class="text-gray-400 text-sm mb-4">Please enter the Order ID for Ticket <span
                        class="text-blue-400 font-bold" x-text="currentTicket"></span></p>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Order
                        ID</label>
                    <div class="relative">
                        <i class="bx bx-hash absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="text"
                            class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all placeholder-gray-600"
                            placeholder="e.g. ORD-2026-001">
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button @click="orderModalOpen = false"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all">
                        Close
                    </button>
                    <button
                        @click="orderModalOpen = false; showLoading(); setTimeout(() => { document.getElementById('loading-overlay').classList.add('hidden') }, 1000)"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('components.loading-overlay')

    @push('scripts')
    <script>
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading on browser back button (bfcache restore)
        window.addEventListener('pageshow', function (event) {
            document.getElementById('loading-overlay').classList.add('hidden');
        });

        // AJAX Functionality
        let currentPage = 1;
        let debounceTimer;

        document.addEventListener('DOMContentLoaded', function() {
            loadTableData();

            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    loadTableData();
                }, 300);
            });

            document.getElementById('perPageSelect').addEventListener('change', function() {
                currentPage = 1;
                loadTableData();
            });
        });

        function getStatusColor(status) {
            if (!status) return 'bg-gray-500 text-white';
            const s = status.toLowerCase();
            if (s === 'open') return 'bg-blue-500 text-white';
            if (s === 'pending') return 'bg-yellow-500 text-white';
            if (s === 'in progress') return 'bg-teal-500 text-white';
            if (s === 'resolved') return 'bg-green-500 text-white';
            if (s === 'closed') return 'bg-gray-500 text-white';
            return 'bg-gray-500 text-white';
        }

        function loadTableData(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput').value;
            const perPage = document.getElementById('perPageSelect').value;
            const tbody = document.getElementById('dataTableBody');

            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading data...</p></td></tr>`;

            const url = `{{ route('apps.ticketing-department.getData') }}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    tbody.innerHTML = `<tr><td colspan="10" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                });
        }

        function renderTable(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No tickets available</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-900/40';

                const id = item.id || '-';
                const name = item.name || '-';
                const tno = item.ticket_number || '-';
                const cat = item.kategori || item.category || '-';
                const dep = item.department || '-';
                const ag = item.agent || '-';
                const st = item.status || '-';
                const sl = item.sla || '-';
                const dt = item.created_at ? new Date(item.created_at).toLocaleString() : '-';

                tr.innerHTML = `
                    <td class="px-3 py-3 font-medium text-blue-400 whitespace-nowrap">${id}</td>
                    <td class="px-3 py-3 text-cyan-400 max-w-[140px]">
                        <div class="truncate" title="${tno}">${tno}</div>
                    </td>
                    <td class="px-3 py-3 text-white font-medium max-w-[120px]">
                        <div class="truncate" title="${name}">${name}</div>
                    </td>
                    <td class="px-3 py-3 max-w-[100px]">
                        <div class="truncate" title="${cat}">${cat}</div>
                    </td>
                    <td class="px-3 py-3 max-w-[100px]">
                        <div class="truncate" title="${dep}">${dep}</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">${sl}</td>
                    <td class="px-3 py-3 max-w-[100px]">
                        <div class="truncate" title="${ag}">${ag}</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${getStatusColor(st)}">
                            ${st}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-gray-500 text-xs whitespace-nowrap">${dt}</td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2 relative z-10" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                                <i class="bx bx-dots-vertical-rounded text-base"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 top-10 w-40 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden">
                                <button @click="open = false; $dispatch('open-order-modal', { ticket: '${tno}' })" class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors">
                                    <i class="bx bx-hash text-sm"></i> Order ID
                                </button>
                                <div class="h-px bg-gray-700 my-1"></div>
                                <a href="/journey?ticket_number=${tno}&name=${encodeURIComponent(name)}&category=${encodeURIComponent(cat)}&agent=${encodeURIComponent(ag)}&posisi=${encodeURIComponent(dep)}&status=${encodeURIComponent(st)}&date=${encodeURIComponent(dt)}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                                    <i class="bx bx-right-arrow-circle text-sm"></i> Follow Up
                                </a>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderPagination(data) {
            const info = document.getElementById('dataTableInfo');
            const container = document.getElementById('paginationContainer');

            let from = data.from || 0;
            let to = data.to || 0;
            info.innerHTML = `Showing ${from} to ${to} of ${data.total} entries`;

            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<div class="flex gap-1">';
            
            // Prev
            html += `<button onclick="loadTableData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Previous</button>`;
            
            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    if (i === data.current_page) {
                        html += `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg">${i}</button>`;
                    } else {
                        html += `<button onclick="loadTableData(${i})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">${i}</button>`;
                    }
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    html += `<span class="px-2 py-1 text-gray-500">...</span>`;
                }
            }

            // Next
            html += `<button onclick="loadTableData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Next</button>`;
            
            html += '</div>';
            container.innerHTML = html;
        }

        // Global Event for Order Modal
        document.addEventListener('open-order-modal', function(e) {
            const ticketNumber = e.detail.ticket;
            const xDataNode = document.querySelector('[x-data]');
            if(xDataNode && xDataNode.__x) {
                xDataNode.__x.$data.currentTicket = ticketNumber;
                xDataNode.__x.$data.orderModalOpen = true;
            }
        });
    </script>
    @endpush
@endsection