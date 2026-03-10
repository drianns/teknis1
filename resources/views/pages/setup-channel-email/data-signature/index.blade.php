@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Signature Email</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Data Signature Email</span>
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
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-16 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Id</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Signature</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-24 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
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

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-4xl shadow-2xl relative overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-900/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-300 tracking-tight">Form Signature Email</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-white transition-colors">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <form id="editForm" onsubmit="handleUpdate(event)">
            @csrf
            <input type="hidden" id="edit-id">
            <div class="p-6">
                <div class="mb-6 h-[400px]">
                    <textarea id="edit-content" name="content"></textarea>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-800/30 border-t border-gray-700 flex justify-between gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-full transition-all shadow-lg shadow-rose-500/20">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-full transition-all shadow-lg shadow-blue-500/20">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

@include('pages.setup-channel-email.partials._scrollbar')

<!-- Summernote Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<style>
    .note-editor.note-frame {
        border: 1px solid rgba(55, 65, 81, 1) !important;
        background: transparent !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
    .note-toolbar {
        background: rgba(17, 24, 39, 0.5) !important;
        border-bottom: 1px solid rgba(55, 65, 81, 1) !important;
    }
    .note-btn {
        background: transparent !important;
        border: none !important;
        color: #9ca3af !important;
    }
    .note-btn:hover {
        background: rgba(59, 130, 246, 0.1) !important;
        color: #fff !important;
    }
    .note-editable {
        background: transparent !important;
        color: #d1d5db !important;
        font-size: 14px !important;
    }
</style>

<script>
    const AJAX_URL = "{{ route('setup-channel-email.data-signature.getData') }}";
    const UPDATE_URL = "{{ url('setup-channel-email/data-signature') }}";
    let searchTimeout;

    $(document).ready(function() {
        $('#edit-content').summernote({
            placeholder: 'Write signature content here...',
            tabsize: 2,
            height: 350,
            toolbar: [
                ['style', ['bold', 'italic']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['help']]
            ]
        });
        loadTable(1);
    });

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
            tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-12 text-center text-red-400 font-bold italic">Error loading data. Please try again.</td></tr>';
        });
    }

    function renderTable(rows) {
        const tableBody = document.getElementById('table-body');
        if (!rows || rows.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No signature email data found</p></div></td></tr>';
            return;
        }

        tableBody.innerHTML = rows.map(row => `
            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row border-b border-gray-700/50">
                <td class="px-6 py-5 font-medium text-gray-400 text-center">${row.id}</td>
                <td class="px-6 py-5">
                    <div class="text-gray-300 signature-preview">
                        ${row.content}
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <button onclick='openEditModal(${JSON.stringify(row)})' class="p-2 rounded-lg bg-gray-900 border border-gray-700/50 hover:bg-gray-700 text-blue-500 hover:text-blue-400 transition-all shadow-sm">
                        <i class='bx bx-edit-alt text-lg'></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function openEditModal(data) {
        document.getElementById('edit-id').value = data.id;
        $('#edit-content').summernote('code', data.content);
        
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function handleUpdate(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const content = $('#edit-content').summernote('code');
        const token = document.querySelector('input[name="_token"]').value;

        fetch(`${UPDATE_URL}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ content })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal();
                loadTable();
                // Optional: Show toast notification
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
</script>
@endsection

