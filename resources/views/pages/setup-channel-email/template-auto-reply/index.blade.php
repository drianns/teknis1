@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div>
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Template Auto Reply Email</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Template Auto Reply Email</span>
                </div>
            </div>
        </header>

    <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
        <!-- Action Header -->
        <div class="mb-4 flex justify-between items-center">
            <div></div> <!-- Spacer -->
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/30">
                <i class='bx bx-plus text-lg'></i> Template Auto Reply Email
            </button>
        </div>

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
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Template Auto Reply</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Account Email</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-28 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">
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

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-700">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white" id="modalTitle">Form Template Auto Reply Email</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <form id="templateForm" onsubmit="submitForm(event)">
                <input type="hidden" id="template_id" name="template_id">
                <div class="bg-gray-800 px-4 py-5 sm:p-6 space-y-5 flex-1 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Template Auto Reply (Maksimal 7000 Character) <span class="text-red-500">*</span></label>
                        <textarea id="body" name="body" class="summernote" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Account Email <span class="text-red-500">*</span></label>
                            <select id="account_email" name="account_email" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                                <option value="">Select</option>
                                @foreach($emailAccounts as $acc)
                                    <option value="{{ $acc->incoming_user }}">{{ $acc->incoming_user }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Status <span class="text-red-500">*</span></label>
                            <select id="is_active" name="is_active" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                                <option value="">Select</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="bg-gray-900/50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-700">
                    <button type="submit" id="submitBtn" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Submit
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-red-500/50 shadow-sm px-6 py-2.5 bg-red-500/10 text-base font-medium text-red-400 hover:bg-red-500/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('pages.setup-channel-email.partials._scrollbar')

<!-- Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<style>
    .note-editor.note-frame {
        border-color: #374151;
        border-radius: 0.75rem;
        background: #111827;
    }
    .note-editor .note-toolbar {
        background: #1f2937;
        border-bottom-color: #374151;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .note-editor .note-editable {
        background: #111827;
        color: #d1d5db;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
    }
    .note-btn { background: #374151 !important; color: #d1d5db !important; border-color: #4b5563 !important; }
    .note-btn:hover { background: #4b5563 !important; }
    .note-icon-caret { display: none; }
</style>

<script>
    const AJAX_URL = "{{ route('setup-channel-email.template-auto-reply.getData') }}";
    const STORE_URL = "{{ route('setup-channel-email.template-auto-reply.store') }}";
    const DELETE_URL = "{{ url('setup-channel-email/template-auto-reply') }}";
    let searchTimeout;

    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['help', ['help']]
            ],
            callbacks: {
                onChange: function(contents) {
                    $('#body').val(contents);
                }
            }
        });
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
            tableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-12 text-center text-red-400 font-bold italic">Error loading data. Please try again.</td></tr>';
        });
    }

    function renderTable(rows) {
        const tableBody = document.getElementById('table-body');
        if (!rows || rows.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No auto reply templates found</p></div></td></tr>';
            return;
        }

        tableBody.innerHTML = rows.map(row => {
            // Strip HTML tags for preview and encode single quotes for JSON
            const rawBody = row.body ? row.body : '';
            const plainBody = rawBody.replace(/<[^>]+>/g, '');
            const truncatedBody = plainBody.length > 500 ? plainBody.substring(0, 500) + '...' : plainBody;
            
            // Create a safe stringified version of the row for the edit function
            const safeRow = esc(JSON.stringify(row));
            
            return `
            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row border-b border-gray-700/30">
                <td class="px-4 py-4 font-mono text-blue-400 font-medium text-center align-top">${row.id}</td>
                <td class="px-4 py-4 text-gray-300 font-normal align-top">
                    <div class="prose prose-sm prose-invert max-w-none text-xs text-gray-400">
                        ${rawBody}
                    </div>
                </td>
                <td class="px-4 py-4 text-white font-medium align-top">${esc(row.account_email)}</td>
                <td class="px-4 py-4 text-center align-top">
                    <span class="${row.is_active ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'} px-3 py-1 rounded-full text-[11px] font-bold border whitespace-nowrap tracking-wider shadow-sm">
                        ${row.is_active ? 'Aktif' : 'Non-Aktif'}
                    </span>
                </td>
                <td class="px-4 py-4 text-center align-top">
                    <div class="relative flex justify-center" x-data="{ open: false }">
                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all shadow-sm">
                            <i class='bx bx-dots-vertical-rounded text-lg'></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-32 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                            <button onclick="editModal('${safeRow}')" class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 border-b border-gray-700/30 transition-colors"><i class='bx bx-edit-alt text-lg'></i><span class="font-bold">Edit</span></button>
                            <button onclick="deleteTemplate(${row.id})" class="w-full text-left px-4 py-2.5 text-xs text-red-400 hover:bg-red-500/10 flex items-center gap-3 transition-colors"><i class='bx bx-trash text-lg'></i><span class="font-bold">Delete</span></button>
                        </div>
                    </div>
                </td>
            </tr>
        `}).join('');
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

    // Modal logic
    function openModal() {
        document.getElementById('templateForm').reset();
        document.getElementById('template_id').value = '';
        $('#body').summernote('code', '');
        document.getElementById('modalTitle').innerText = 'Form Template Auto Reply Email';
        document.getElementById('formModal').classList.remove('hidden');
    }

    function editModal(rowStr) {
        try {
            // Unescape single quotes potentially in HTML attributes if we used them, but we used encodeURIComponent
            const doc = new DOMParser().parseFromString(rowStr, "text/html");
            const row = JSON.parse(doc.documentElement.textContent);
            
            document.getElementById('template_id').value = row.id;
            document.getElementById('account_email').value = row.account_email;
            document.getElementById('is_active').value = row.is_active;
            
            $('#body').summernote('code', row.body);
            
            document.getElementById('modalTitle').innerText = 'Edit Template Auto Reply Email';
            document.getElementById('formModal').classList.remove('hidden');
        } catch(e) { console.error('Error parsing row data', e); }
    }

    function closeModal() {
        document.getElementById('formModal').classList.add('hidden');
    }

    function submitForm(e) {
        e.preventDefault();
        
        const id = document.getElementById('template_id').value;
        const formData = {
            body: $('#body').summernote('code'),
            account_email: document.getElementById('account_email').value,
            is_active: document.getElementById('is_active').value
        };

        const url = id ? `${DELETE_URL}/${id}` : STORE_URL;
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal();
                loadTable(1);
            } else {
                alert('Gagal menyimpan data');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan');
        });
    }

    function deleteTemplate(id) {
        if (!confirm('Apakah anda yakin ingin menghapus template ini?')) return;

        fetch(`${DELETE_URL}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadTable(1);
            } else {
                alert('Gagal menghapus data');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan');
        });
    }

    document.addEventListener('DOMContentLoaded', () => loadTable(1));
</script>
@endsection

