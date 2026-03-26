@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <x-page-header title="Data Group Name" :breadcrumbs="['Master Data', 'Data Group Name']">
            <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                <i class='bx bx-plus-circle text-lg'></i>
                <span>Add Group Name</span>
            </button>
        </x-page-header>

        <x-data-table>
            <x-slot name="thead">
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Name</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
            </x-slot>
        </x-data-table>
    </div>

    <!-- Modal Create/Edit -->
    <x-modal id="masterModal" title="Add New Data Group Name" maxWidth="max-w-lg">
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
        
        <x-slot name="footer">
            <button type="button" onclick="CrudTable.closeModal('masterModal')" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">Cancel</button>
            <button type="button" onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all font-bold text-sm">Save Changes</button>
        </x-slot>
    </x-modal>
    <div id="master-data-config" class="hidden"
        data-ajax-url="{{ url('data-group-name/data') }}"
        data-store-url="{{ route('data-group-name.store') }}"
        data-update-base="{{ url('data-group-name') }}"
        data-delete-base="{{ url('data-group-name') }}"
        data-csrf="{{ csrf_token() }}"></div>

    @push('scripts')
        const TABLE_CONFIG = {
            ajaxUrl: '{{ url("data-group-name/data") }}',
            storeUrl: '{{ route("data-group-name.store") }}',
            updateBase: '{{ url("data-group-name") }}',
            deleteBase: '{{ url("data-group-name") }}',
            csrf: '{{ csrf_token() }}'
        };

        const renderRow = (item) => `
            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                <td class="px-6 py-4 font-mono text-blue-400 font-medium text-center whitespace-nowrap">${item.id}</td>
                <td class="px-6 py-4 text-gray-100 font-medium whitespace-nowrap">${window.CrudTable.escHtml(item.name)}</td>
                <td class="px-6 py-4 text-center">
                    ${item.status === 'Aktif' 
                        ? '<x-status-badge status="Aktif" class="tracking-wider" />' 
                        : '<x-status-badge status="Non Aktif" class="tracking-wider" />'}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openEditModal(${item.id},'${window.CrudTable.escJs(item.name)}','${window.CrudTable.escJs(item.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 hover:text-blue-300 transition-colors border border-blue-500/20" title="Edit">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button onclick="deleteData(${item.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 hover:text-rose-300 transition-colors border border-rose-500/20" title="Delete">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;

        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Add New Data Group Name';
            document.getElementById('editId').value = '';
            document.getElementById('modal_name').value = '';
            document.getElementById('modal_status').value = 'Aktif';
            window.CrudTable.openModal('masterModal');
        }

        function openEditModal(id, name, status) {
            document.getElementById('modalTitle').innerText = 'Edit Data Group Name';
            document.getElementById('editId').value = id;
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_status').value = status;
            window.CrudTable.openModal('masterModal');
        }

        function getFormData() {
            return {
                name: document.getElementById('modal_name').value,
                status: document.getElementById('modal_status').value,
            };
        }

        function saveData() {
            const id = document.getElementById('editId').value;
            window.CrudTable.save(TABLE_CONFIG, id, getFormData(), 'masterModal');
        }

        function deleteData(id) {
            window.CrudTable.delete(TABLE_CONFIG, id);
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.CrudTable.init(TABLE_CONFIG, renderRow);
        });
    </script>
@endpush
@endsection

