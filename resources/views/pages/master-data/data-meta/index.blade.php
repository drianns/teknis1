@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <x-page-header title="Data Meta" :breadcrumbs="['Master Data', 'Data Meta']">
            <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                <i class='bx bx-plus-circle text-lg'></i><span>Add Meta</span>
            </button>
        </x-page-header>
        <x-data-table>
            <x-slot name="thead">
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Brand</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Type</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Category</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Meta Name</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
            </x-slot>
        </x-data-table>
    </div>
    <x-modal id="masterModal" title="Add New Data Meta" maxWidth="max-w-lg">
        <input type="hidden" id="editId">
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Brand</label>
            <select id="modal_brand_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                <option value="">Select Brand</option>
                @foreach($brands as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
            </select>
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Type</label>
            <select id="modal_type_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                <option value="">Select Type</option>
                @foreach($types as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
            </select>
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Category</label>
            <select id="modal_category_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                <option value="">Select Category</option>
                @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Meta Name</label>
            <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all placeholder-gray-600" placeholder="Enter meta name">
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Status</label>
            <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                <option value="Aktif">Aktif</option><option value="Non Aktif">Non Aktif</option>
            </select>
        </div>
        <x-slot name="footer">
            <button onclick="CrudTable.closeModal('masterModal')" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white">Cancel</button>
            <button onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm">Save Changes</button>
        </x-slot>
    </x-modal>
    <div id="master-data-config" class="hidden"
        data-ajax-url="{{ url('data-meta/data') }}"
        data-store-url="{{ route('data-meta.store') }}"
        data-update-base="{{ url('data-meta') }}"
        data-delete-base="{{ url('data-meta') }}"
        data-csrf="{{ csrf_token() }}"></div>

    @push('scripts')
        @vite('resources/js/pages/master-data/data-meta.js')
    @endpush
@endsection

