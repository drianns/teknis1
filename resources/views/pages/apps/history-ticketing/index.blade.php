<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        History Ticketing
    </x-slot>

    <style>
        /* Date Range Bar (V2) */
        .date-range-bar {
            display: flex;
            align-items: center;
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 14px;
            padding: 4px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            gap: 2px;
        }

        .date-range-bar:hover {
            background: rgba(31, 41, 55, 0.8);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .date-range-bar:active {
            transform: translateY(0);
        }

        .date-segment {
            display: flex;
            flex-direction: column;
            padding: 8px 16px;
            min-width: 120px;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .date-segment:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .segment-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #60a5fa;
            margin-bottom: 2px;
        }

        .segment-value {
            font-size: 13px;
            font-weight: 600;
            color: #f3f4f6;
            white-space: nowrap;
        }

        .date-divider {
            width: 1px;
            height: 24px;
            background: rgba(75, 85, 99, 0.4);
            margin: 0 4px;
        }

        .bar-icon {
            width: 40px;
            height: 40px;
            display: flex;
            items-center: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 20px;
        }

        .bar-chevron {
            padding-right: 12px;
            color: #4b5563;
            font-size: 18px;
        }

        /* Modern Popup (V2 Adjustments) */
        .modern-popup {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            width: 320px;
        }

        .popup-label {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .popup-input {
            width: 100%;
            background: #111827;
            border: 1px solid #374151;
            color: white;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            transition: all 200ms ease;
            outline: none;
        }

        .popup-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* Quick Presets Grid */
        .preset-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .preset-btn {
            padding: 8px;
            background: #111827;
            border: 1px solid #374151;
            border-radius: 8px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 200ms ease;
            text-align: center;
        }

        .preset-btn:hover {
            border-color: #3b82f6;
            color: white;
            background: #2563eb10;
        }

        .preset-btn.active {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }
    </style>

    <div class="min-h-screen bg-gray-900 w-full overflow-x-hidden"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <!-- Main Content -->
        <main class="flex-1 p-2 md:p-4">
            <!-- Header & Action Section -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-6 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bx bx-history text-white text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-white">History Ticketing</h1>
                    </div>
                    <nav class="flex text-sm text-gray-400 ml-0">
                        <a href="#" class="hover:text-blue-400">Home</a>
                        <span class="mx-2">/</span>
                        <a href="#" class="hover:text-blue-400">Apps</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-300">History Ticketing</span>
                    </nav>
                </div>

                <!-- Date Range Bar Relocated Here -->
                <div class="relative">
                    <div onclick="toggleDateFilter()" id="dateFilterBtn" class="date-range-bar">
                        <div class="bar-icon">
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <div class="date-segment">
                            <span class="segment-label">Start Date</span>
                            <span id="barStartDate" class="segment-value">Select Date</span>
                        </div>
                        <div class="date-divider"></div>
                        <div class="date-segment">
                            <span class="segment-label">End Date</span>
                            <span id="barEndDate" class="segment-value">Select Date</span>
                        </div>
                        <div class="bar-chevron">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Modern Date Picker Popup -->
                    <div id="dateFilterPopup" class="hidden absolute right-0 mt-3 modern-popup z-50 p-5">
                        <div class="space-y-4">
                            <!-- Presets -->
                            <div>
                                <span class="popup-label">Quick Selection</span>
                                <div class="preset-grid">
                                    <button onclick="setPreset('today')" class="preset-btn">Today</button>
                                    <button onclick="setPreset('yesterday')" class="preset-btn">Yesterday</button>
                                    <button onclick="setPreset('last7days')" class="preset-btn">Last 7 Days</button>
                                    <button onclick="setPreset('thismonth')" class="preset-btn">This Month</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="popup-label">Start Date</label>
                                    <input type="date" id="startDate" class="popup-input">
                                </div>
                                <div>
                                    <label class="popup-label">End Date</label>
                                    <input type="date" id="endDate" class="popup-input">
                                </div>
                            </div>

                            <div>
                                <label class="popup-label">Email Address</label>
                                <div class="relative">
                                    <i
                                        class="bx bx-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <input type="email" id="emailAddress" placeholder="Enter email..."
                                        class="popup-input pl-10">
                                </div>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button onclick="applyDateFilter()"
                                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                                    Apply Filter
                                </button>
                                <button onclick="closeDateFilter()"
                                    class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-4 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
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

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <!-- Search -->
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bx-search text-gray-500"></i>
                            </div>
                            <input type="text" id="searchInput"
                                class="bg-gray-900 border border-gray-700 text-gray-300 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full pl-12 p-2.5 transition-all outline-none"
                                placeholder="Search histories...">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
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
                                        Category <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Agent <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Posisi <i
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
                            <!-- Data render via Javascript -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="dataTableInfo">Showing 0 to 0 of 0 entries</span>
                    <div class="mt-2 md:mt-0" id="paginationContainer">
                        <!-- Pagination buttons populated by JS -->
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

    <!-- Loading Overlay -->
    <div id="loading-overlay"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm hidden">
        <div class="flex flex-col items-center">
            <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
                <div class="absolute inset-0 animate-ping rounded-full h-16 w-16 border-2 border-blue-500/20"></div>
            </div>
            <p class="mt-6 text-white text-xs font-black uppercase tracking-[0.3em] animate-pulse">Processing Request
            </p>
        </div>
    </div>

    <style>
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
        function toggleDateFilter() {
            const popup = document.getElementById('dateFilterPopup');
            popup.classList.toggle('hidden');
        }

        function closeDateFilter() {
            const popup = document.getElementById('dateFilterPopup');
            popup.classList.add('hidden');
        }

        // Helper to format date for display (e.g., "17 Feb 2026")
        function formatDisplayDate(dateStr) {
            if (!dateStr) return 'Select Date';
            const date = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Update the labels on the Bar
        function updateBarLabels() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const barStart = document.getElementById('barStartDate');
            const barEnd = document.getElementById('barEndDate');

            if (barStart) barStart.innerText = formatDisplayDate(startDate);
            if (barEnd) barEnd.innerText = formatDisplayDate(endDate);
        }

        function setPreset(type) {
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            const today = new Date();
            let start, end;

            // Helper to format date as YYYY-MM-DD
            const formatInputDate = (date) => date.toISOString().split('T')[0];

            switch (type) {
                case 'today':
                    start = end = today;
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    start = end = yesterday;
                    break;
                case 'last7days':
                    const last7 = new Date(today);
                    last7.setDate(today.getDate() - 7);
                    start = last7;
                    end = today;
                    break;
                case 'thismonth':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = today;
                    break;
            }

            if (start && end) {
                startInput.value = formatInputDate(start);
                endInput.value = formatInputDate(end);

                updateBarLabels(); // Sync bar immediately

                // Update active state of preset buttons
                document.querySelectorAll('.preset-btn').forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.innerText.toLowerCase().replace(/\s/g, '') === type) {
                        btn.classList.add('active');
                    }
                });
            }
        }

        function applyDateFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const email = document.getElementById('emailAddress').value;

            updateBarLabels(); // Sync bar labels
            console.log('Filter applied:', { startDate, endDate, email });

            closeDateFilter();
        }

        // Initialize dates on load
        window.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');

            if (startInput && !startInput.value) startInput.value = today;
            if (endInput && !endInput.value) endInput.value = today;

            updateBarLabels();
        });


        // Close popup when clicking outside
        document.addEventListener('click', function (event) {
            const popup = document.getElementById('dateFilterPopup');
            const btn = document.getElementById('dateFilterBtn');

            if (popup && btn && !popup.contains(event.target) && !btn.contains(event.target)) {
                popup.classList.add('hidden');
            }
        });

        // AJAX Table functionality
        // Wait for Alpine to be ready to access data methods
        document.addEventListener('alpine:init', () => {
             // We use Alpine.store or general JS event
        });
        
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

            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading data...</p></td></tr>`;

            // Note: Update Date Filter params when backend ready
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const url = `{{ route('apps.history-ticketing.getData') }}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}&start_date=${startDate}&end_date=${endDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                });
        }

        function renderTable(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No history available</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-900/40';
                
                // Safety map item fields if relations are nested differently
                const name = item.chat_ticket_user ? item.chat_ticket_user.name : (item.name || '-');
                const tno = item.ticket_number || '-';
                const cat = item.category || '-';
                const ag = item.agent || '-';
                const pos = item.posisi || '-';
                const st = item.status || '-';
                const sl = item.sla || '-';
                const dt = item.date || item.created_at ? new Date(item.created_at).toLocaleString() : '-';

                // We inject a specialized JS-driven dropdown using straightforward display toggling instead of x-data
                tr.innerHTML = `
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
                        <div class="truncate" title="${ag}">${ag}</div>
                    </td>
                    <td class="px-3 py-3 max-w-[100px]">
                        <div class="truncate" title="${pos}">${pos}</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">${sl}</td>
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
                                <a href="/journey?ticket_number=${tno}&name=${encodeURIComponent(name)}&category=${encodeURIComponent(cat)}&agent=${encodeURIComponent(ag)}&posisi=${encodeURIComponent(pos)}&status=${encodeURIComponent(st)}&date=${encodeURIComponent(dt)}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
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
            
            // Page numbers (simplified)
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

        // Catch the custom event dispatched from dynamically generated rows
        document.addEventListener('open-order-modal', function(e) {
            const ticketNumber = e.detail.ticket;
            // Since Alpine scope isn't automatically shared globally, we can mutate the component state
            // OR find the nearest alpine component and mutate it.
            // Using a simple Javascript way (since Alpine can be tricky to mutate externally without Alpine.store)
            document.querySelector('[x-data]').__x.$data.currentTicket = ticketNumber;
            document.querySelector('[x-data]').__x.$data.orderModalOpen = true;
        });
    </script>
</x-dashonic-horizontal-layout>