@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Roatex Recording</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Recording</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="current text-blue-500 font-semibold">Voice Recording</span>
                </div>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full space-y-4">
            <!-- Filter Section -->
            <div class="bg-gray-800/80 backend-blur-md rounded-2xl border border-gray-700/50 p-6 shadow-xl ring-1 ring-white/5">
                <form action="#" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Start</label>
                            <div class="relative">
                                <input type="date" name="start_date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">End</label>
                            <div class="relative">
                                <input type="date" name="end_date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter forms updated -->
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 space-y-2 w-full">
                            <input type="text" id="searchInput" name="unique_id" placeholder="Unique Id or Ticket Number" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all placeholder-gray-600">
                        </div>
                        <button type="button" onclick="loadTableData(1)" class="w-full md:w-32 px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all font-bold text-sm h-[42px] flex items-center justify-center gap-2">
                            <i class='bx bx-search text-lg'></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50">
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Unique Id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Call Date</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ticket Number</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Disposition</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agent</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Duration</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Recording File</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">STT</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">QA</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <!-- Populated dynamically via JS AJAX -->
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500" id="dataTableInfo">Showing <span class="text-white font-bold">0</span> to <span class="text-white font-bold">0</span> of <span class="text-white font-bold">0</span> entries</div>
                    <div id="paginationContainer" class="flex gap-1"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .backend-blur-md {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    
    <script>
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

        function loadTableData(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput')?.value || '';
            const tbody = document.getElementById('dataTableBody');

            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-10"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-500">Loading records...</p></td></tr>`;

            const url = `{{ route('recording.getData') }}?page=${page}&search=${encodeURIComponent(search)}`;

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

        function renderTable(data) {
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

        function renderPagination(data) {
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
    </script>
@endsection
