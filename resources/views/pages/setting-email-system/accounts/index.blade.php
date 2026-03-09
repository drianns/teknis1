@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Email Account</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Email System</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Data Email Account</span>
            </div>
        </header>

    <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
        <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
            <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3 text-sm text-gray-400">
                    <span>Show</span>
                    <select id="per-page" onchange="loadTable(1)" class="bg-gray-800 border-gray-700 rounded-lg px-2 py-1 text-xs text-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="relative">
                    <input type="text" id="search-input" oninput="debounceSearch()" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500 shadow-inner" placeholder="Search..." />
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                </div>
            </div>

            <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                <table class="w-full text-left border-collapse" style="min-width: 2500px;">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Name</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Incoming User</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Incoming Pass</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Incoming Server</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Incoming Port</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Encryption</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outgoing User</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outgoing Pass</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outgoing Server</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outgoing Port</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Encryption Out</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Need Login</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Protocol In</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Protocol Out</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Server Profile</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Signature</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Method</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-28 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        <tr>
                            <td colspan="20" class="px-4 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                    <p>Loading data...</p>
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
    const AJAX_URL = "{{ route('setting-email-system.accounts.getData') }}";
    let searchTimeout;

    function loadTable(page = 1) {
        const tableBody = document.getElementById('table-body');
        const search = document.getElementById('search-input').value;
        const perPage = document.getElementById('per-page').value;

        fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(err => {
            console.error(err);
            tableBody.innerHTML = '<tr><td colspan="20" class="px-4 py-12 text-center text-red-400 font-bold italic">Error loading data. Please try again.</td></tr>';
        });
    }

    function renderTable(rows) {
        const tableBody = document.getElementById('table-body');
        if (!rows || rows.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="20" class="px-4 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No email accounts found</p></div></td></tr>';
            return;
        }

        tableBody.innerHTML = rows.map(row => `
            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row text-xs">
                <td class="px-4 py-3 font-mono text-blue-400 font-medium text-center">${row.id}</td>
                <td class="px-4 py-3 font-medium text-white">${esc(row.name)}</td>
                <td class="px-4 py-3 text-gray-300 font-normal">${esc(row.incoming_user || '-')}</td>
                <td class="px-4 py-3 text-gray-400 font-mono">${row.incoming_pass ? '••••••••' : '-'}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.incoming_server || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.incoming_port || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.encrypted_connection || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.outgoing_user || '-')}</td>
                <td class="px-4 py-3 text-gray-400 font-mono">${row.outgoing_pass ? '••••••••' : '-'}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.outgoing_server || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.outgoing_port || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.encrypted_connection_out || '-')}</td>
                <td class="px-4 py-3 text-center">
                    <span class="text-[10px] ${row.need_login ? 'text-blue-400' : 'text-gray-500'} font-bold">${row.need_login ? 'YES' : 'NO'}</span>
                </td>
                <td class="px-4 py-3 text-gray-300">${esc(row.server_protocol || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.server_protocol_out || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.server_profile_id || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.email_signature_id || '-')}</td>
                <td class="px-4 py-3 text-gray-300">${esc(row.email_service_method_id || '-')}</td>
                <td class="px-4 py-3 text-center">
                    <span class="${row.status === 'active' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'} px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider shadow-sm">
                        ${esc(row.status)}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="relative flex justify-center" x-data="{ open: false }">
                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all shadow-sm">
                            <i class='bx bx-dots-vertical-rounded text-lg'></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-32 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                            <button class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 border-b border-gray-700/30 transition-colors"><i class='bx bx-edit-alt text-lg'></i><span class="font-bold">Edit</span></button>
                            <button class="w-full text-left px-4 py-2.5 text-xs text-red-400 hover:bg-red-500/10 flex items-center gap-3 transition-colors"><i class='bx bx-trash text-lg'></i><span class="font-bold">Delete</span></button>
                        </div>
                    </div>
                </td>
            </tr>
        `).join('');
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

    document.addEventListener('DOMContentLoaded', () => loadTable(1));
</script>
@endsection
