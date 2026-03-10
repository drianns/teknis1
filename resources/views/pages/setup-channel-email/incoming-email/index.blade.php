@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Incoming Email</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Data Incoming Email</span>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="px-6 py-5 border-b border-gray-700/50 bg-gray-800/50 flex flex-col xl:flex-row justify-between gap-6">
                    <!-- Filters Section -->
                    <div class="flex flex-wrap items-end gap-4">
                        <div class="space-y-1.5 flex-1 min-w-[200px]">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Start Date</label>
                            <input type="date" id="start-date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all shadow-inner">
                        </div>
                        <div class="space-y-1.5 flex-1 min-w-[200px]">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">End Date</label>
                            <input type="date" id="end-date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all shadow-inner">
                        </div>
                        <button onclick="loadTable(1)" class="h-[42px] px-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                            <span>Submit</span>
                        </button>
                    </div>

                    <!-- Export Section -->
                    <div class="flex flex-wrap items-end gap-3 xl:ml-auto">
                        <div class="relative w-40">
                            <select id="export-type" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 pr-10 text-sm text-gray-300 appearance-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all shadow-inner cursor-pointer">
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                                <option value="pdf">PDF</option>
                            </select>
                            <i class="bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                        <button class="h-[42px] px-6 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-2">
                            <span>Export</span>
                        </button>
                    </div>
                </div>

                <div class="table-controls px-6 py-4 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select id="per-page" onchange="loadTable(1)" class="bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 cursor-pointer">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative w-full sm:w-80">
                        <input type="text" id="search-input" oninput="debounceSearch()" class="w-full bg-gray-900 border border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 shadow-inner placeholder-gray-500" placeholder="Search Subject or Ticket..." />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>
                </div>

                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Pengirim</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Pengirim</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Subject</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-28 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300 font-medium">
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                        <p>Loading incoming emails...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div id="pagination-info" class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Showing 0 to 0 of 0 entries</div>
                    <div id="pagination-links" class="flex gap-1"></div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.setup-channel-email.partials._scrollbar')

    <script>
        const AJAX_URL = "{{ route('setup-channel-email.incoming-email.getData') }}";
        let searchTimeout;

        function loadTable(page = 1) {
            const tableBody = document.getElementById('table-body');
            const search = document.getElementById('search-input').value;
            const perPage = document.getElementById('per-page').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            // Optional: send start and end dates to controller if supported
            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}&start_date=${startDate}&end_date=${endDate}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error(err);
                tableBody.innerHTML = '<tr><td colspan="7" class="px-4 py-12 text-center text-red-400 font-bold italic">Error loading data.</td></tr>';
            });
        }

        function renderTable(rows) {
            const tableBody = document.getElementById('table-body');
            if (!rows || rows.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="px-4 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No incoming email data found</p></div></td></tr>';
                return;
            }

            tableBody.innerHTML = rows.map(row => {
                const user = row.chat_ticket_user || {};
                const date = row.created_at ? new Date(row.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-';
                
                return `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                    <td class="px-4 py-3 font-mono text-blue-400 font-medium text-center">${row.id}</td>
                    <td class="px-4 py-3 font-medium text-white">${esc(user.name || '-')}</td>
                    <td class="px-4 py-3 text-gray-400">${esc(user.email || '-')}</td>
                    <td class="px-4 py-3 text-gray-300 font-semibold truncate max-w-[200px]" title="${esc(row.subject)}">${esc(row.subject || '-')}</td>
                    <td class="px-4 py-3 text-gray-400 text-xs">${date}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-500/30 whitespace-nowrap uppercase tracking-wider">
                            ${esc(row.status || 'inbound')}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="relative flex justify-center" x-data="{ open: false }">
                            <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all shadow-sm">
                                <i class='bx bx-dots-vertical-rounded text-lg'></i>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-32 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                                <button class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 transition-colors"><i class='bx bx-edit-alt text-lg'></i><span class="font-bold">Edit</span></button>
                            </div>
                        </div>
                    </td>
                </tr>
            `;}).join('');
        }

        function renderPagination(data) {
            document.getElementById('pagination-info').innerHTML = `Showing <span class="text-white">${data.from || 0}</span> to <span class="text-white">${data.to || 0}</span> of <span class="text-blue-500">${data.total}</span> entries`;
            const container = document.getElementById('pagination-links');
            container.innerHTML = '';

            if (data.last_page <= 1) return;

            const btn = (label, page, active = false, disabled = false) => {
                const b = document.createElement('button');
                b.innerHTML = label;
                b.disabled = disabled;
                b.className = `w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 hover:text-white'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
                if (!disabled && !active) b.onclick = () => loadTable(page);
                return b;
            };

            container.appendChild(btn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, false, data.current_page === 1));

            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    container.appendChild(btn(i, i, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const dots = document.createElement('span');
                    dots.className = "px-1 text-gray-600 font-bold";
                    dots.innerText = "...";
                    container.appendChild(dots);
                }
            }

            container.appendChild(btn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, false, data.current_page === data.last_page));
        }

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadTable(1), 500);
        }

        function esc(s) {
            return String(s || '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date();
            const lastMonth = new Date(today);
            lastMonth.setDate(today.getDate() - 30);
            
            document.getElementById('start-date').value = lastMonth.toISOString().split('T')[0];
            document.getElementById('end-date').value = today.toISOString().split('T')[0];

            loadTable(1);
        });
    </script>
@endsection

