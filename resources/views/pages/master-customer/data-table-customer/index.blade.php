<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Data Table Customer
    </x-slot>

    <div class="min-h-screen bg-gray-900">
        <!-- Main Content -->
        <main class="flex-1 p-2 md:p-4">
            <!-- Header & Breadcrumb -->
            <div class="flex flex-col mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="bx bx-table text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-white">Data Table Customer</h1>
                </div>
                <nav class="flex text-sm text-gray-400 ml-0">
                    <a href="#" class="hover:text-blue-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="#" class="hover:text-blue-400">Apps</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-300">Data Table Customer</span>
                </nav>
            </div>

            <!-- Drag & Drop Grouping Area -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-4 mb-4">
                <div id="dropZone"
                    class="border-2 border-dashed border-gray-600 rounded-lg p-4 min-h-[60px] flex items-center gap-2">
                    <i class="bx bx-move text-gray-500 text-xl"></i>
                    <span class="text-gray-400 text-sm" id="dropZoneText">Drag a column header here to group by that
                        column</span>
                    <div id="groupedColumns" class="flex gap-2 flex-wrap"></div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-3 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="flex items-center gap-3">
                        <!-- Show Entries -->
                        <div class="flex items-center text-gray-400 text-sm">
                            <span>Show</span>
                            <select
                                class="mx-2 bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span>entries</span>
                        </div>

                        <!-- Export Excel -->
                        <button onclick="exportToExcel()"
                            class="flex items-center gap-2 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="bx bx-download text-base"></i>
                            <span class="text-sm">Export</span>
                        </button>

                        <!-- Export Format Dropdown -->
                        <div class="relative">
                            <button onclick="toggleExportDropdown()" id="exportDropdownBtn"
                                class="flex items-center gap-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors border border-gray-600">
                                <span class="text-sm" id="selectedFormat">Excel</span>
                                <i class="bx bx-chevron-down text-base"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="exportDropdown"
                                class="hidden absolute left-0 mt-2 w-40 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50">
                                <div class="py-1">
                                    <button onclick="selectExportFormat('Excel')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        Excel
                                    </button>
                                    <button onclick="selectExportFormat('Excel 97-2003')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        Excel 97-2003
                                    </button>
                                    <button onclick="selectExportFormat('CSV')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-500"></i>
                        </div>
                        <input type="text" id="searchInput" onkeyup="searchTable()"
                            class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-2.5"
                            placeholder="Search...">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400" id="customerTable">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col" draggable="true" data-column="id" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>ID</div>
                                </th>
                                <th scope="col" draggable="true" data-column="name" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Name</div>
                                </th>
                                <th scope="col" draggable="true" data-column="email" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Email</div>
                                </th>
                                <th scope="col" draggable="true" data-column="phone" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Phone</div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold">Created At</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-gray-800 bg-transparent">
                            <tr><td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                    <p class="font-bold tracking-widest uppercase text-xs">Loading data...</p>
                                </div>
                            </td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="paginationInfo">Showing 0 to 0 of 0 entries</span>
                    <div id="paginationLinks" class="flex gap-1 mt-2 md:mt-0"></div>
                </div>
            </div>
        </main>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        let groupedBy = [];

        // Drag and Drop for Column Grouping
        const headers = document.querySelectorAll('th[draggable="true"]');
        const dropZone = document.getElementById('dropZone');
        const dropZoneText = document.getElementById('dropZoneText');
        const groupedColumns = document.getElementById('groupedColumns');

        headers.forEach(header => {
            header.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', header.dataset.column);
                e.dataTransfer.setData('text/html', header.textContent.trim());
            });
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-500/10');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-500/10');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-500/10');

            const column = e.dataTransfer.getData('text/plain');
            const columnText = e.dataTransfer.getData('text/html');

            if (!groupedBy.includes(column)) {
                groupedBy.push(column);
                addGroupPill(column, columnText);
                dropZoneText.classList.add('hidden');
                groupTable(column);
            }
        });

        function addGroupPill(column, text) {
            const pill = document.createElement('div');
            pill.className =
                'flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm';
            pill.innerHTML = `
                <i class="bx bx-grid-alt"></i>
                <span>${text.replace(/\s+/g, ' ').trim()}</span>
                <button onclick="removeGroup('${column}')" class="hover:text-red-300">
                    <i class="bx bx-x text-lg"></i>
                </button>
            `;
            groupedColumns.appendChild(pill);
        }

        function removeGroup(column) {
            groupedBy = groupedBy.filter(c => c !== column);
            groupedColumns.innerHTML = '';
            groupedBy.forEach((col, idx) => {
                const header = document.querySelector(`th[data-column="${col}"]`);
                addGroupPill(col, header.textContent.trim());
            });

            if (groupedBy.length === 0) {
                dropZoneText.classList.remove('hidden');
            }

            // Refresh table grouping
            ungroupTable();
            groupedBy.forEach(col => groupTable(col));
        }

        function groupTable(column) {
            // Simple visual feedback - actual grouping logic would be more complex
            console.log('Grouping by:', column);
        }

        function ungroupTable() {
            console.log('Ungrouping table');
        }

        // Export Format Dropdown
        let selectedExportFormat = 'Excel';

        function toggleExportDropdown() {
            const dropdown = document.getElementById('exportDropdown');
            dropdown.classList.toggle('hidden');
        }

        function selectExportFormat(format) {
            selectedExportFormat = format;
            document.getElementById('selectedFormat').textContent = format;
            toggleExportDropdown();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('exportDropdown');
            const btn = document.getElementById('exportDropdownBtn');
            
            if (dropdown && btn && !dropdown.contains(event.target) && !btn.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Export to Excel
        function exportToExcel() {
            const table = document.getElementById('customerTable');
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Customers"
            });

            let filename = 'data_table_customer';
            let bookType = 'xlsx';

            if (selectedExportFormat === 'Excel 97-2003') {
                filename += '.xls';
                bookType = 'xls';
            } else if (selectedExportFormat === 'CSV') {
                filename += '.csv';
                bookType = 'csv';
            } else {
                filename += '.xlsx';
                bookType = 'xlsx';
            }

            XLSX.writeFile(wb, filename, { bookType: bookType });
        }

        // AJAX Table Loading
        const AJAX_URL = '{{ route('master-customer.data-table.getData') }}';
        let searchTimeout;
        let currentPerPage = 10;

        function loadTable(page = 1) {
            const search = document.getElementById('searchInput')?.value ?? '';
            const tableBody = document.getElementById('tableBody');

            tableBody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xs uppercase tracking-widest font-bold">Loading...</p>
                </div></td></tr>`;

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${currentPerPage}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderRows(data.data);
                renderPagination(data);
            })
            .catch(() => {
                tableBody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-red-400 font-bold">Error loading data. Please try again.</td></tr>`;
            });
        }

        function renderRows(rows) {
            const tableBody = document.getElementById('tableBody');
            if (!rows || rows.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i><p>No customers found</p></div>
                </td></tr>`;
                return;
            }
            tableBody.innerHTML = rows.map(c => `
                <tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-900/40">
                    <td class="px-3 py-3 text-blue-400 font-mono">${c.id}</td>
                    <td class="px-3 py-3 text-white font-medium">${esc(c.name)}</td>
                    <td class="px-3 py-3 text-cyan-400">${esc(c.email ?? '-')}</td>
                    <td class="px-3 py-3 text-gray-300">${esc(c.phone ?? '-')}</td>
                    <td class="px-3 py-3 text-gray-500 text-xs">${c.created_at ? c.created_at.substring(0,10) : '-'}</td>
                </tr>
            `).join('');
        }

        function renderPagination(data) {
            document.getElementById('paginationInfo').innerHTML =
                `Showing <span class="text-white">${data.from || 0}</span> to <span class="text-white">${data.to || 0}</span> of <span class="text-blue-400">${data.total}</span> entries`;
            const container = document.getElementById('paginationLinks');
            container.innerHTML = '';
            if (data.last_page <= 1) return;
            const btn = (label, page, active = false, disabled = false) => {
                const b = document.createElement('button');
                b.innerHTML = label;
                b.disabled = disabled;
                b.className = `px-3 py-1 rounded-lg text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-400 hover:bg-gray-600'} ${disabled ? 'opacity-40 cursor-not-allowed' : ''}`;
                if (!disabled && !active) b.onclick = () => loadTable(page);
                return b;
            };
            container.appendChild(btn('Previous', data.current_page - 1, false, data.current_page === 1));
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    container.appendChild(btn(i, i, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.className = 'px-1 text-gray-500';
                    container.appendChild(dots);
                }
            }
            container.appendChild(btn('Next', data.current_page + 1, false, data.current_page === data.last_page));
        }

        // Search Table (AJAX debounce)
        function searchTable() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadTable(1), 400);
        }

        function esc(s) {
            return String(s || '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        // Per-page change
        document.querySelector('select')?.addEventListener('change', function() {
            currentPerPage = parseInt(this.value);
            loadTable(1);
        });

        document.addEventListener('DOMContentLoaded', () => loadTable(1));
    </script>
</x-dashonic-horizontal-layout>