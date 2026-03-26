
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // Load table on init
            loadTableData();

            // Search input event
            const searchInput = document.getElementById('searchInput');
            let debounceTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        currentPage = 1;
                        loadTableData();
                    }, 400);
                });
            }
        });

window.loadTableData = function(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput')?.value || '';
            const tbody = document.getElementById('dataTableBody');

            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-10"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-500">Loading records...</p></td></tr>`;

            const configElement = document.getElementById('recording-config');
            const url = `${configElement.dataset.url}?page=${page}&search=${encodeURIComponent(search)}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    tbody.innerHTML = `<tr><td colspan="10" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Failed to load data</p></td></tr>`;
                });
        }

window.renderTable = function(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class='bx bx-microphone text-5xl mb-3 opacity-20'></i>
                                <p>No recording records found</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-blue-500/[0.03] transition-colors';
                
                const uid = item.unique_id || '-';
                const calldt = item.call_date || item.created_at || '-';
                const tn = item.ticket_number || '-';
                const disp = item.disposition || 'Call';
                const cust = item.customer || '-';
                const ag = item.agent || '-';
                const dur = item.duration || '00:00:00';
                const stt = item.stt || 'N/A';
                const qa = item.qa || '0%';

                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <span class="font-mono text-blue-400 font-medium">${uid}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">${calldt}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded bg-gray-700 text-gray-200 text-[11px] font-semibold">${tn}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">
                            ${disp}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">${cust}</td>
                    <td class="px-6 py-4 text-gray-400">${ag}</td>
                    <td class="px-6 py-4 text-center text-gray-300 font-mono">${dur}</td>
                    <td class="px-6 py-4 text-center">
                        <button class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white transition-all inline-flex items-center justify-center border border-blue-500/30" title="Play Recording">
                            <i class='bx bx-play text-xl'></i>
                        </button>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-lg bg-teal-500/10 text-teal-400 text-[10px] font-bold border border-teal-500/20 uppercase">
                            ${stt}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-white">${qa}</span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

window.renderPagination = function(data) {
            const info = document.getElementById('dataTableInfo');
            const container = document.getElementById('paginationContainer');

            let from = data.from || 0;
            let to = data.to || 0;
            info.innerHTML = `Showing <span class="text-white font-bold">${from}</span> to <span class="text-white font-bold">${to}</span> of <span class="text-white font-bold">${data.total}</span> entries`;

            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Prev
            html += `<button onclick="loadTableData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Previous</button>`;
            
            // Next
            html += `<button onclick="loadTableData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Next</button>`;
            
            container.innerHTML = html;
        }
    

