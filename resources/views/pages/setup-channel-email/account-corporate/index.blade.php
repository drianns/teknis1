@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Account Email Corporate</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Account Email Corporate</span>
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
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                        <tr>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Account Email Corporate</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Unit Kerja</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-24 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300 font-medium">
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
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
        const AJAX_URL = "{{ route('setup-channel-email.account-corporate.getData') }}";
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
                tableBody.innerHTML = '<tr><td colspan="8" class="px-4 py-12 text-center text-red-400 font-bold italic">Error loading data. Please try again.</td></tr>';
            });
        }

        function renderTable(rows) {
            const tableBody = document.getElementById('table-body');
            if (!rows || rows.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No account email corporate found</p></div></td></tr>';
                return;
            }

            tableBody.innerHTML = rows.map(row => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row border-b border-gray-700/50">
                    <td class="px-6 py-5 text-gray-300 font-medium font-inter">
                        ${esc(row.account_id || row.email)}
                    </td>
                    <td class="px-6 py-5 text-gray-400 font-inter">
                        ${esc(row.name || (row.account_id && row.account_id.includes('nespresso') ? 'Nespresso' : 'Kanmo'))}
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="relative flex justify-center" x-data="{ open: false }">
                            <button @click.stop="open = !open" class="text-blue-500 hover:text-blue-400 transition-all">
                                <i class='bx bx-dots-vertical text-2xl'></i>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-32 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                                <button onclick='openEditModal(${JSON.stringify(row)})' class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 transition-colors"><i class='bx bx-edit-alt text-lg'></i><span class="font-bold">Edit</span></button>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openEditModal(data) {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-account_id').value = data.account_id || data.email;
            document.getElementById('edit-name').value = data.name || (data.account_id && data.account_id.includes('nespresso') ? 'Nespresso' : 'Kanmo');
            
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        const UPDATE_URL = "{{ url('setup-channel-email/account-corporate') }}";
        function handleUpdate(e) {
            e.preventDefault();
            const id = document.getElementById('edit-id').value;
            const account_id = document.getElementById('edit-account_id').value;
            const name = document.getElementById('edit-name').value;
            const token = document.querySelector('input[name="_token"]').value;

            fetch(`${UPDATE_URL}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ account_id, name })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    loadTable();
                }
            })
            .catch(err => console.error(err));
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

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity" onclick="closeModal()"></div>
    <div class="bg-[#f0f4f8] border border-gray-300 rounded-2xl w-full max-w-md shadow-2xl relative overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-white flex justify-between items-center">
            <h3 class="text-base font-bold text-[#64748b] tracking-tight font-inter">Form Account Email Corporate</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <form id="editForm" onsubmit="handleUpdate(event)">
            @csrf
            <input type="hidden" id="edit-id">
            <div class="p-6 space-y-6 bg-white">
                <div>
                    <label class="block text-[13px] font-bold text-[#64748b] mb-2 font-inter">Account Email Corporate</label>
                    <input type="text" id="edit-account_id" name="account_id" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all font-inter shadow-sm" placeholder="Email address...">
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-[#64748b] mb-2 font-inter">Group Agent</label>
                    <div class="relative">
                        <select id="edit-name" name="name" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all font-inter shadow-sm appearance-none cursor-pointer">
                            <option value="Kanmo">Kanmo</option>
                            <option value="Nespresso">Nespresso</option>
                        </select>
                        <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl'></i>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-between gap-3">
                <button type="button" onclick="closeModal()" class="px-8 py-2.5 bg-[#ff4d4d] hover:bg-[#ff3333] text-white text-sm font-bold rounded-full transition-all shadow-md active:scale-95 font-inter">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-2.5 bg-[#6b9eff] hover:bg-[#5289ff] text-white text-sm font-bold rounded-full transition-all shadow-md active:scale-95 font-inter">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    .font-inter { font-family: 'Inter', sans-serif; }
</style>
@endsection

