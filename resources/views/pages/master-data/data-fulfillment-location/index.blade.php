@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <x-page-header title="Data Fulfillment Location" :breadcrumbs="['Master Data', 'Data Fulfillment Location']">
            <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                <i class='bx bx-plus-circle text-lg'></i><span>Add Location</span>
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

    <x-modal id="masterModal" title="Add New Fulfillment Location" maxWidth="max-w-lg">
        <input type="hidden" id="editId">
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Location Name</label>
            <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all placeholder-gray-600" placeholder="Enter name">
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Status</label>
            <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 appearance-none">
                <option value="Aktif">Aktif</option><option value="Non Aktif">Non Aktif</option>
            </select>
        </div>
        <x-slot name="footer">
            <button onclick="CrudTable.closeModal('masterModal')" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white">Cancel</button>
            <button onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm">Save Changes</button>
        </x-slot>
    </x-modal>
    <div id="master-data-config" class="hidden"
        data-ajax-url="{{ url('data-fulfillment-location/data') }}"
        data-store-url="{{ route('data-fulfillment-location.store') }}"
        data-update-base="{{ url('data-fulfillment-location') }}"
        data-delete-base="{{ url('data-fulfillment-location') }}"
        data-csrf="{{ csrf_token() }}"></div>

    @push('scripts')
        @vite('resources/js/pages/master-data/data-fulfillment-location.js')
    @endpush
@endsection

