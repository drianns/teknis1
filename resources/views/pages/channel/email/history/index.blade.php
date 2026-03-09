<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        History Email
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
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 20px;
        }

        .bar-chevron {
            padding-right: 12px;
            color: #4b5563;
            font-size: 18px;
        }

        /* Modern Popup */
        .modern-popup {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            width: 340px;
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

    <div class="min-h-screen bg-gray-900">
        <!-- Main Content -->
        <main class="flex-1 p-2 md:p-4">
            <!-- Header & Action Section -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-6 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bx bx-envelope text-white text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-white">Data History Email</h1>
                    </div>
                    <nav class="flex text-sm text-gray-400 ml-0">
                        <a href="#" class="hover:text-blue-400">Home</a>
                        <span class="mx-2">/</span>
                        <a href="#" class="hover:text-blue-400">Apps</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-300">History Email</span>
                    </nav>
                </div>

                <!-- Date Range Bar (Relocated) -->
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

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="popup-label">Feature</label>
                                    <select id="selectFeature" class="popup-input">
                                        <option value="">Select</option>
                                        <option value="inbox">Inbox</option>
                                        <option value="outbox">Outbox</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="popup-label">Email Address</label>
                                    <input type="email" id="emailAddress" placeholder="Search email..."
                                        class="popup-input">
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
                                        Email Service <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Email Address <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Subject <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Agent Name <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Date <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Type <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold text-center whitespace-nowrap">
                                    File
                                </th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody" class="divide-y divide-gray-800 bg-transparent">
                            <!-- Populated dynamically via JS AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="dataTableInfo">Showing 0 to 0 of 0 entries</span>
                    <div class="flex gap-1 mt-2 md:mt-0" id="paginationContainer">
                        <!-- JS paginated -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Attachment Modal -->
    <div id="attachmentModal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeAttachmentModal()">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-700">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-500/10 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bx bx-paperclip text-blue-400 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-white mb-1">Email Attachments</h3>
                            <p class="text-xs text-gray-400" id="modalSubject">-</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-2" id="attachmentList">
                        <!-- Attachments will be loaded here -->
                    </div>
                </div>
                <div class="bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-700">
                    <button type="button" onclick="closeAttachmentModal()"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search function - AJAX Handled
        // Removed old DOM search onkeyup based filtering


        // Toggle dropdown
        function toggleDropdown(emailId) {
            const dropdown = document.getElementById(`dropdown-${emailId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== `dropdown-${emailId}`) {
                    d.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        });

        // Date Filter Functions
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
            
            if(barStart) barStart.innerText = formatDisplayDate(startDate);
            if(barEnd) barEnd.innerText = formatDisplayDate(endDate);
        }

        function setPreset(type) {
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            const today = new Date();
            let start, end;

            // Helper to format date as YYYY-MM-DD for input fields
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
            updateBarLabels(); // Sync bar labels
            closeDateFilter();
            
            // Trigger AJAX Real Load
            currentPage = 1;
            loadTableData();
        }

        // Initialize dates on load
        window.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            
            if(startInput && !startInput.value) startInput.value = today;
            if(endInput && !endInput.value) endInput.value = today;
            
            updateBarLabels();
            loadTableData(); // Initial JS Load
            
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

        let currentPage = 1;
        let debounceTimer;

        // Note: For demonstration since real attachments require DB logic we use a mock payload
        // But table rendering is real via AJAX
        let currentDataBucket = {}; 

        function loadTableData(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput').value;
            const perPage = document.getElementById('perPageSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const emailAddress = document.getElementById('emailAddress').value;
            const feature = document.getElementById('selectFeature').value; // if we can pass it, we can filter in backend too, ignored for now
            
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading emails...</p></td></tr>`;

            const url = `{{ route('channel.email.history.getData') }}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}&start_date=${startDate}&end_date=${endDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                });
        }

        function renderTable(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';
            currentDataBucket = {}; // clear bucket

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No email history available</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-900/40 relative z-0';

                const svc = item.email_service || '-';
                const addr = item.contact || item.email_address || '-';
                const subj = item.subject || '-';
                const agent = item.agent || item.agent_name || '-';
                const type = item.type || '-';
                const dt = item.created_at ? new Date(item.created_at).toLocaleString() : '-';
                const contentText = item.content || 'Content not available'; // Or handle real body
                const attachmentsMock = "[]"; // Replace with real if present
                
                // Store mapped data for modal viewing
                currentDataBucket[item.id] = {
                    subject: subj,
                    content: btoa(unescape(encodeURIComponent(contentText))),
                    attachments: attachmentsMock
                };

                let typeColorHtml = `<span class="text-gray-600 text-xs">-</span>`;
                if(type && type !== '-') {
                    const tc = type.toUpperCase() === 'OUT' ? 'bg-amber-500 text-white' : 'bg-blue-500 text-white';
                    typeColorHtml = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold ${tc}">${type}</span>`;
                }

                tr.innerHTML = `
                    <td class="px-3 py-3 text-gray-400 max-w-[160px]">
                        <div class="truncate" title="${svc}">${svc}</div>
                    </td>
                    <td class="px-3 py-3 text-cyan-400 max-w-[200px]">
                        <div class="truncate" title="${addr}">${addr}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-400 max-w-[180px]">
                        <div class="truncate" title="${subj}">${subj}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-400 max-w-[120px]">
                        <div class="truncate" title="${agent}">${agent}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-500 text-xs whitespace-nowrap">${dt}</td>
                    <td class="px-3 py-3 whitespace-nowrap">${typeColorHtml}</td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        <div class="relative inline-block z-10">
                            <button onclick="toggleDropdown(${item.id})" class="text-gray-400 hover:text-white transition-colors">
                                <i class="bx bx-dots-vertical-rounded text-xl"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="dropdown-${item.id}" class="hidden absolute right-0 top-6 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-50">
                                <ul class="py-1 text-sm">
                                    <li>
                                        <a href="javascript:void(0)" onclick="viewEmailContent(${item.id})" class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors">
                                            <i class="bx bx-envelope text-base"></i>
                                            <span>File Email</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="viewAttachments(${item.id})" class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors">
                                            <i class="bx bx-paperclip text-base"></i>
                                            <span>Attachment</span>
                                        </a>
                                    </li>
                                </ul>
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

            let html = '';
            
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
            
            container.innerHTML = html;
        }

        // Close date filter popup when clicking outside
        document.addEventListener('click', function (event) {
            const popup = document.getElementById('dateFilterPopup');
            const button = document.getElementById('dateFilterBtn');

            if (popup && button && !popup.contains(event.target) && !button.contains(event.target)) {
                popup.classList.add('hidden');
            }
        });
        // View Email Content in new tab
        function viewEmailContent(id) {
            const stored = currentDataBucket[id];
            if (!stored) return;

            const subject = stored.subject;
            const content = atob(stored.content); // Decode base64

            const newTab = window.open('', '_blank');
            newTab.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${subject || 'Email Content'}</title>
                    <style>
                        body { 
                            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
                            line-height: 1.6; 
                            color: #333; 
                            max-width: 800px; 
                            margin: 40px auto; 
                            padding: 20px;
                            background: #f4f7f6;
                        }
                        .container {
                            background: white;
                            padding: 40px;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                        }
                        .header {
                            border-bottom: 1px solid #eee;
                            margin-bottom: 20px;
                            padding-bottom: 20px;
                        }
                        .subject { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .content { min-height: 200px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <div class="subject">${subject || '(No Subject)'}</div>
                        </div>
                        <div class="content">
                            ${content}
                        </div>
                    </div>
                </body>
                </html>
            `);
            newTab.document.close();
        }

        // Attachment Modal Functions
        function viewAttachments(id) {
            const stored = currentDataBucket[id];
            if (!stored) return;

            const subject = stored.subject;
            const attachments = JSON.parse(stored.attachments);

            const modal = document.getElementById('attachmentModal');
            const list = document.getElementById('attachmentList');
            const subjectLabel = document.getElementById('modalSubject');

            subjectLabel.textContent = subject || '(No Subject)';
            list.innerHTML = '';

            if (attachments.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <i class="bx bx-info-circle text-2xl mb-1"></i>
                        <p class="text-sm">No attachments found in this email.</p>
                    </div>
                `;
            } else {
                attachments.forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between p-3 bg-gray-900/50 rounded-lg border border-gray-700 hover:border-blue-500/50 transition-colors group';
                    item.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500/10 rounded flex items-center justify-center text-blue-400 group-hover:bg-blue-500/20 transition-colors">
                                <i class="bx ${getFileIcon(file.name)} text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-200">${file.name}</p>
                                <p class="text-xs text-gray-500">${file.size}</p>
                            </div>
                        </div>
                        <button class="p-2 text-gray-400 hover:text-blue-400 transition-colors" title="Download">
                            <i class="bx bx-download text-lg"></i>
                        </button>
                    `;
                    list.appendChild(item);
                });
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAttachmentModal() {
            document.getElementById('attachmentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            switch (ext) {
                case 'pdf': return 'bxs-file-pdf';
                case 'doc':
                case 'docx': return 'bxs-file-doc';
                case 'jpg':
                case 'jpeg':
                case 'png': return 'bxs-file-image';
                default: return 'bxs-file';
            }
        }
    </script>
</x-dashonic-horizontal-layout>