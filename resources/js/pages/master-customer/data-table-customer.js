
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

window.addGroupPill = function(column, text) {
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

window.removeGroup = function(column) {
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

window.groupTable = function(column) {
            // Simple visual feedback - actual grouping logic would be more complex
            console.log('Grouping by:', column);
        }

window.ungroupTable = function() {
            console.log('Ungrouping table');
        }

        // Export Format Dropdown
        let selectedExportFormat = 'Excel';

window.toggleExportDropdown = function() {
            const dropdown = document.getElementById('exportDropdown');
            dropdown.classList.toggle('hidden');
        }

window.selectExportFormat = function(format) {
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
window.exportToExcel = function() {
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
        const AJAX_URL = '';
        let searchTimeout;
        let currentPerPage = 10;

window.loadTable = function(page = 1) {
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

window.renderRows = function(rows) {
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

window.renderPagination = function(data) {
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
window.searchTable = function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadTable(1), 400);
        }

window.esc = function(s) {
            return String(s || '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        // Per-page change
        document.querySelector('select')?.addEventListener('change', function() {
            currentPerPage = parseInt(this.value);
            loadTable(1);
        });

        document.addEventListener('DOMContentLoaded', () => loadTable(1));
    






