@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Data Group Name</h1>
                <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                    <i class='bx bx-plus-circle text-lg'></i>
                    <span>Add Group Name</span>
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Master Data</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="current text-blue-500 font-semibold">Data Group Name</span>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select id="perPage" class="entries-select bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-gray-300 focus:outline-none focus:border-blue-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative">
                        <input type="text" id="searchInput" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500" placeholder="Search..." />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>
                </div>

                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50">
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div id="paginationInfo" class="text-sm text-gray-500">Loading...</div>
                    <div id="paginationLinks" class="flex gap-1"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    <div id="masterModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-12">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <div class="relative bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-700 ring-1 ring-white/5">
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
                    <h3 id="modalTitle" class="text-xl font-bold text-white tracking-tight">Add New Data Group Name</h3>
                    <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <input type="hidden" id="editId">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-400">Group Name</label>
                        <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all placeholder-gray-600" placeholder="Enter name" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-400">Status</label>
                        <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all appearance-none">
                            <option value="Aktif">Aktif</option>
                            <option value="Non Aktif">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900/50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button type="button" onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all font-bold text-sm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    @include('pages.setup-channel-email.partials._scrollbar')

    <script>
        const AJAX_URL    = '{{ url("data-group-name/data") }}';
        const STORE_URL   = '{{ route("data-group-name.store") }}';
        const UPDATE_BASE = '{{ url("data-group-name") }}';
        const DELETE_BASE = '{{ url("data-group-name") }}';
        const CSRF        = '{{ csrf_token() }}';

        let currentPage = 1;
        let searchTimer = null;

        function loadTable(page = 1) {
            currentPage = page;
            const search  = document.getElementById('searchInput').value;
            const perPage = document.getElementById('perPage').value;

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                renderTable(data);
                renderPagination(data);
            })
            .catch(() => {
                document.getElementById('tableBody').innerHTML =
                    '<tr><td colspan="4" class="px-6 py-16 text-center text-red-400">Failed to load data.</td></tr>';
            });
        }

        function renderTable(data) {
            const tbody = document.getElementById('tableBody');
            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class='bx bx-data text-5xl mb-3 opacity-20'></i><p>No data records found</p>
                        </div></td></tr>`;
                return;
            }
            tbody.innerHTML = data.data.map(item => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors">
                    <td class="px-6 py-4 font-mono text-blue-400 font-medium text-center whitespace-nowrap">${item.id}</td>
                    <td class="px-6 py-4 text-gray-100 font-medium whitespace-nowrap">${escHtml(item.name)}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${item.status === 'Aktif' ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20'}">
                            ${item.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openEditModal(${item.id},'${escJs(item.name)}','${escJs(item.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 hover:text-blue-300 transition-colors border border-blue-500/20" title="Edit">
                                <i class='bx bx-edit-alt'></i>
                            </button>
                            <button onclick="deleteData(${item.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 hover:text-rose-300 transition-colors border border-rose-500/20" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </td>
                </tr>`).join('');
        }

        function renderPagination(data) {
            document.getElementById('paginationInfo').innerHTML =
                `Showing <span class="text-white font-bold">${data.from ?? 0}</span> to <span class="text-white font-bold">${data.to ?? 0}</span> of <span class="text-white font-bold">${data.total}</span> entries`;

            const links = document.getElementById('paginationLinks');
            links.innerHTML = '';
            const btn = (label, page, disabled, active) => {
                const b = document.createElement('button');
                b.innerHTML = label;
                b.disabled = disabled;
                b.className = `px-3 py-1 rounded-lg text-sm font-medium transition-colors ${active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'} ${disabled ? 'opacity-40 cursor-not-allowed' : ''}`;
                if (!disabled) b.onclick = () => loadTable(page);
                return b;
            };
            links.appendChild(btn('&laquo;', data.current_page - 1, data.current_page <= 1, false));
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                links.appendChild(btn(i, i, false, i === data.current_page));
            }
            links.appendChild(btn('&raquo;', data.current_page + 1, data.current_page >= data.last_page, false));
        }

        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Add New Data Group Name';
            document.getElementById('editId').value = '';
            document.getElementById('modal_name').value = '';
            document.getElementById('modal_status').value = 'Aktif';
            document.getElementById('masterModal').classList.remove('hidden');
        }

        function openEditModal(id, name, status) {
            document.getElementById('modalTitle').innerText = 'Edit Data Group Name';
            document.getElementById('editId').value = id;
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_status').value = status;
            document.getElementById('masterModal').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('masterModal').classList.add('hidden'); }

        function saveData() {
            const id     = document.getElementById('editId').value;
            const body   = new URLSearchParams({
                _token: CSRF,
                name:   document.getElementById('modal_name').value,
                status: document.getElementById('modal_status').value,
            });
            const isEdit = id !== '';
            if (isEdit) body.append('_method', 'PUT');

            const url    = isEdit ? `${UPDATE_BASE}/${id}` : STORE_URL;
            const method = 'POST';

            document.getElementById('saveBtn').disabled = true;
            document.getElementById('saveBtn').textContent = 'Saving...';

            fetch(url, {
                method,
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) { closeModal(); loadTable(currentPage); showToast(res.message, 'success'); }
                else showToast(Object.values(res.errors || {}).flat().join(', '), 'error');
            })
            .catch(() => showToast('Error saving data.', 'error'))
            .finally(() => {
                document.getElementById('saveBtn').disabled = false;
                document.getElementById('saveBtn').textContent = 'Save Changes';
            });
        }

        function deleteData(id) {
            if (!confirm('Silahkan konfirmasi untuk menghapus data ini?')) return;
            fetch(`${DELETE_BASE}/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams({ _token: CSRF, _method: 'DELETE' })
            })
            .then(r => r.json())
            .then(res => { if (res.success) { loadTable(currentPage); showToast(res.message, 'success'); } })
            .catch(() => showToast('Error deleting data.', 'error'));
        }

        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.className = `fixed bottom-6 right-6 z-[9999] px-5 py-3 rounded-xl shadow-2xl text-sm font-semibold transition-all ${type === 'success' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'}`;
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }

        function escHtml(str) { return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
        function escJs(str)   { return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'"); }

        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadTable(1), 400);
        });
        document.getElementById('perPage').addEventListener('change', () => loadTable(1));

        loadTable();
    </script>
@endsection