@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-900 w-full overflow-x-hidden"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <div class="p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                        <div class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bxs-dashboard text-blue-500"></i>
                        </div>
                        Taskboard
                    </h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium text-gray-500">
                            <li class="inline-flex items-center">
                                <a href="#" class="hover:text-blue-400 transition-colors">Home</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <a href="#" class="hover:text-blue-400 transition-colors">Apps</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center text-blue-400/80">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <span>Taskboard</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>

            <!-- Statistics Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Open Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'open']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'open' ? 'ring-1 ring-blue-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <!-- Icon -->
                        <div
                            class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-blue-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-coupon text-2xl text-blue-500"></i>
                        </div>

                        <!-- Text -->
                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['open'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Open</span>
                        </div>
                    </div>

                    <!-- Bottom Limit Line -->
                    <div
                        class="h-[3px] w-full bg-blue-500 rounded-full group-hover:shadow-[0_0_8px_rgba(59,130,246,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Pending Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'pending']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'pending' ? 'ring-1 ring-yellow-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-yellow-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-error-circle text-2xl text-yellow-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['pending'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Pending</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-yellow-500 rounded-full group-hover:shadow-[0_0_8px_rgba(234,179,8,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- In Progress -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'in_progress']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'in_progress' ? 'ring-1 ring-teal-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-teal-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-teal-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-like text-2xl text-teal-400"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['in_progress'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">In progress</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-teal-500 rounded-full group-hover:shadow-[0_0_8px_rgba(20,184,166,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Closed Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'closed']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'closed' ? 'ring-1 ring-red-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-red-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-red-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-check-circle text-2xl text-red-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['closed'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Closed</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-red-500 rounded-full group-hover:shadow-[0_0_8px_rgba(239,68,68,0.5)] transition-shadow duration-500">
                    </div>
                </div>

            </div>

            <!-- Table Section -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5">

                <!-- Table Controls -->
                <div
                    class="px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bx-list-ul text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Ticket Management</h2>
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">List of all reported
                                tickets</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <select id="perPageSelect" class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6">
                                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <!-- Search Box -->
                        <div class="relative w-full sm:w-64 group">
                            <i
                                class="bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                            <input type="text" id="searchInput" value="{{ request('search') }}"
                                class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600"
                                placeholder="Search tickets...">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Ticket Number <i
                                            class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Name <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Kategori <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">SLA <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Note SLA <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Position
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Agent
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Department
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Status
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Date Create
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="taskboardTableBody" class="divide-y divide-gray-700/50">
                            <!-- Data Table will be populated here via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                <div class="px-6 py-4 border-t border-gray-700/50 flex flex-col sm:flex-row justify-between items-center bg-gray-800/30 gap-4" id="paginationContainer">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest" id="tableInfo">
                        Showing <span class="text-white">0</span> - <span class="text-white">0</span> of <span class="text-blue-400">0</span> tickets
                    </div>
                     <div id="paginationLinks">
                        <!-- Pagination buttons will be injected here via javascript -->
                     </div>
                </div>

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
                            <button @click="orderModalOpen = false"
                                class="text-gray-500 hover:text-white transition-colors">
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
        </div>
    </div>



    <!-- Loading Overlay -->
    <div id="loading-overlay"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm hidden">
        <div class="flex flex-col items-center">
            <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
                <div class="absolute inset-0 animate-ping rounded-full h-16 w-16 border-2 border-blue-500/20"></div>
            </div>
            <p class="mt-6 text-white text-xs font-black uppercase tracking-[0.3em] animate-pulse">Processing Request</p>
        </div>
    </div>

    <style>
        .card-glow {
            @apply absolute -right-8 -bottom-8 w-32 h-32 rounded-full blur-3xl opacity-0 transition-opacity duration-500;
        }

        .group:hover .card-glow {
            @apply opacity-100;
        }

        /* Custom Dropdown Animation */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading on browser back button (bfcache restore)
        window.addEventListener('pageshow', function (event) {
            document.getElementById('loading-overlay').classList.add('hidden');
        });
        
        // AJAX Data Fetching logic
        document.addEventListener('DOMContentLoaded', () => {
            let searchTimeout = null;
            const state = {
                page: 1,
                perPage: 10,
                search: '',
                status: '{{ request("status", "") }}'
            };

            const els = {
                tbody: document.getElementById('taskboardTableBody'),
                tableInfo: document.getElementById('tableInfo'),
                pagination: document.getElementById('paginationLinks'),
                searchInput: document.getElementById('searchInput'),
                perPageSelect: document.getElementById('perPageSelect')
            };

            const endpointUrl = `{{ route('apps.taskboard.getData') }}`;

            function loadTableData() {
                els.tbody.innerHTML = `<tr><td colspan="11" class="py-12 text-center text-gray-400 group/row"><div class="flex flex-col items-center justify-center gap-2 animate-pulse"><i class="bx bx-loader-alt bx-spin text-4xl mb-1 text-blue-500"></i><p>Loading records...</p></div></td></tr>`;

                const queryParams = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    status: state.status
                });

                fetch(`${endpointUrl}?${queryParams.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        renderTable(data.data);
                        renderPagination(data);
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        els.tbody.innerHTML = `<tr><td colspan="11" class="py-12 text-center text-red-500"><div class="flex flex-col items-center justify-center gap-2"><i class="bx bx-error text-4xl mb-1"></i><p>Error loading data</p></div></td></tr>`;
                    });
            }

            function renderTable(items) {
                if (!items || items.length === 0) {
                    els.tbody.innerHTML = `<tr>
                                    <td colspan="11" class="py-20 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-4 animate-fade-in">
                                            <div class="w-20 h-20 bg-gray-700/30 rounded-full flex items-center justify-center border border-gray-600/50 shadow-inner">
                                                <i class="bx bx-folder-open text-5xl text-gray-500"></i>
                                            </div>
                                            <div class="space-y-1">
                                                <h3 class="text-xl font-bold text-white">Data tidak ada</h3>
                                                <p class="text-sm text-gray-500">Belum ada tiket yang sesuai dengan filter ini.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`;
                    return;
                }

                els.tbody.innerHTML = items.map((ticket, index) => {
                    const ticketNumber = ticket.ticket_number || '-';
                    
                    let name = '-';
                    if (ticket.chat_ticket_user && ticket.chat_ticket_user.name) {
                        name = ticket.chat_ticket_user.name;
                    } else if (ticket.extra_data && ticket.extra_data.name) {
                        name = ticket.extra_data.name;
                    }

                    let category = '-';
                    if (typeof ticket.category === 'string') {
                        category = ticket.category;
                    } else if (ticket.category && ticket.category.nama_kategori) {
                        category = ticket.category.nama_kategori;
                    }
                    
                    const sla = ticket.sla || '-';
                    const noteSlaRaw = ticket.note_sla || '-';
                    const noteSla = noteSlaRaw.replace(' Days ', '<br>Days ');
                    const position = ticket.ticket_position ? 'Layer ' + ticket.ticket_position : 'Layer 1';
                    
                    let agent = '-';
                    if (ticket.user_agent) {
                        agent = ticket.user_agent.full_name || (ticket.user_agent.user ? ticket.user_agent.user.name : ticket.user_agent.username || '-');
                    }
                    
                    const department = ticket.department || '-';
                    
                    const pillStyles = {
                        'open': 'bg-blue-500 text-white',
                        'pending': 'bg-yellow-500 text-white',
                        'in_progress': 'bg-teal-500 text-white',
                        'process': 'bg-teal-500 text-white',
                        'resolved': 'bg-green-500 text-white',
                        'closed': 'bg-gray-500 text-white',
                    };
                    const statusVal = ticket.status || 'closed';
                    const statusKey = statusVal.toLowerCase();
                    const style = pillStyles[statusKey] || 'bg-gray-500 text-white';
                    
                    const ticketDateObj = ticket.created_at ? new Date(ticket.created_at) : new Date();
                    const dt1 = ticketDateObj.toLocaleDateString('en-US'); 
                    const dt2 = ticketDateObj.toLocaleTimeString('en-US');
                    
                    // Route construction for journey href
                    const journeyUrl = `{{ url('journey') }}?ticket_number=${encodeURIComponent(ticketNumber)}&name=${encodeURIComponent(name)}&category=${encodeURIComponent(category)}&agent=${encodeURIComponent(agent)}&posisi=${encodeURIComponent(position)}&status=${encodeURIComponent(statusVal)}&date=${encodeURIComponent(ticket.created_at || '')}`;

                    return `<tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-300">${ticketNumber}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-white max-w-[150px] truncate block" title="${name}">${name}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-xs text-gray-400">${category}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-xs text-gray-400">${sla}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-gray-400 block leading-tight">${noteSla}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-xs text-gray-400">${position}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-xs text-gray-300">${agent}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="text-xs text-gray-400">${department}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold ${style} tracking-wide">
                                ${statusVal.charAt(0).toUpperCase() + statusVal.slice(1)}
                            </span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-300">${dt1}</span>
                                <span class="text-[10px] font-medium text-gray-500">${dt2}</span>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2" x-data="{ open: false }">
                                <div class="relative">
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                                        <i class="bx bx-dots-vertical-rounded text-base"></i>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden"
                                        style="display: none;">
                                        <button
                                            @click="orderModalOpen = true; currentTicket = '${ticketNumber}'; open = false"
                                            class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors">
                                            <i class="bx bx-hash text-sm"></i> Order ID
                                        </button>
                                        <div class="h-px bg-gray-700 my-1"></div>
                                        <a href="${journeyUrl}"
                                            class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                                            <i class="bx bx-right-arrow-circle text-sm"></i> Follow Up
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            }
            
            function renderPagination(data) {
                const total = data.total || 0;
                const from = data.from || 0;
                const to = data.to || 0;
                els.tableInfo.innerHTML = `Showing <span class="text-white">${from}</span> - <span class="text-white">${to}</span> of <span class="text-blue-400">${total}</span> tickets`;

                if (!data.links || data.links.length === 0) {
                    els.pagination.innerHTML = '';
                    return;
                }
                
                let paginationHtml = `<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center lg:justify-end">
                        <div>
                            <span class="relative z-0 inline-flex shadow-sm rounded-md">`;
                
                data.links.forEach((link, index) => {
                    let label = link.label;
                    if (label.includes('Previous')) label = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>`;
                    if (label.includes('Next')) label = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>`;

                    let className = "relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-gray-800 border border-gray-600 cursor-pointer leading-5 hover:text-white hover:bg-gray-700 transition-colors";
                    if (index === 0) className += ' rounded-l-md px-2';
                    if (index === data.links.length - 1) className += ' rounded-r-md px-2';
                    if (link.active) {
                        className = "relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-gray-600 cursor-default leading-5";
                    }

                    if (link.url) {
                        const parsedUrl = new URL(link.url);
                        const pageNum = parsedUrl.searchParams.get('page');
                        paginationHtml += `
                            <span aria-current="${link.active ? 'page' : 'false'}">
                                <button type="button" class="${className}" data-page="${pageNum}">
                                    ${label}
                                </button>
                            </span>`;
                    } else {
                        paginationHtml += `
                            <span aria-disabled="true">
                                <span class="${className} opacity-50 cursor-not-allowed">
                                    ${label}
                                </span>
                            </span>`;
                    }
                });
                
                paginationHtml += `</span></div></div></nav>`;

                els.pagination.innerHTML = paginationHtml;

                // Add event listeners to pagination buttons
                const buttons = els.pagination.querySelectorAll('button[data-page]');
                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        state.page = parseInt(btn.getAttribute('data-page'));
                        loadTableData();
                    });
                });
            }

            // Event Listeners for Search and Per-Page elements
            els.searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    state.search = e.target.value;
                    state.page = 1;
                    loadTableData();
                }, 300);
            });

            els.perPageSelect.addEventListener('change', (e) => {
                state.perPage = e.target.value;
                state.page = 1;
                loadTableData();
            });

            // Make it work with existing stat cards smoothly via Alpine / event listeners if needed
            // Currently they reload the page via window.location.href. To make it SPA-like, we could update the url logic
            // But for now, we leave the status cards as they were, they'll reload and we fetch correct data.

            // Initial load
            loadTableData();
        });
    </script>
@endsection