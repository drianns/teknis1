@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <x-page-header title="Data Sub Category" :breadcrumbs="['Master Data', 'Data Sub Category']">
            <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                <i class='bx bx-plus-circle text-lg'></i><span>Add Sub Category</span>
            </button>
        </x-page-header>
        <x-data-table>
            <x-slot name="thead">
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Brand / Type / Category</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Meta</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Sub Category</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-28 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Unit/SLA</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
            </x-slot>
        </x-data-table>
    </div>

    <x-modal id="masterModal" title="Add New Data Sub Category" maxWidth="max-w-2xl">
        <input type="hidden" id="editId">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Brand</label>
                <select id="modal_brand_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
                </select>
            </div>
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Type</label>
                <select id="modal_type_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                    <option value="">Select Type</option>
                    @foreach($types as $type)<option value="{{ $type->id }}">{{ $type->name }} ({{ $type->brand->name ?? '-' }})</option>@endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Category</label>
                <select id="modal_category_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }} ({{ $category->dataType->name ?? '-' }})</option>@endforeach
                </select>
            </div>
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Meta</label>
                <select id="modal_meta_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                    <option value="">Select Meta</option>
                    @foreach($metas as $meta)<option value="{{ $meta->id }}">{{ $meta->name }} ({{ $meta->category->name ?? '-' }})</option>@endforeach
                </select>
            </div>
        </div>
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-400">Sub Category Name</label>
            <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="Enter sub category name">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Escalation Unit</label>
                <select id="modal_unit_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->name }}</option>@endforeach
                </select>
            </div>
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Layer</label>
                <input type="number" id="modal_layer" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="Layer">
            </div>
            <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">SLA (Hours)</label>
                <input type="number" id="modal_sla" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="SLA">
            </div>
        </div>
        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Status</label>
            <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                <option value="Aktif">Aktif</option><option value="Non Aktif">Non Aktif</option>
            </select>
        </div>

        <x-slot name="footer">
            <button onclick="CrudTable.closeModal('masterModal')" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white">Cancel</button>
            <button onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm">Save Changes</button>
        </x-slot>
    </x-modal>
    <div id="master-data-config" class="hidden"
        data-ajax-url="{{ url('data-sub-category/data') }}"
        data-store-url="{{ route('data-sub-category.store') }}"
        data-update-base="{{ url('data-sub-category') }}"
        data-delete-base="{{ url('data-sub-category') }}"
        data-csrf="{{ csrf_token() }}"></div>

    @push('scripts')
        @vite('resources/js/pages/master-data/data-sub-category.js')
    @endpush
@endsection

