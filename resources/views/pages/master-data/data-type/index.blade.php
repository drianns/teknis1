@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight text-glow-blue">Data Type</h1>
                <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                    <i class='bx bx-plus-circle text-lg'></i>
                    <span>Add Type</span>
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Master Data</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="current text-blue-500 font-semibold">Data Type</span>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select class="entries-select bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-gray-300 focus:outline-none focus:border-blue-500">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative">
                        <input type="text" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500" placeholder="Search..." />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>
                </div>

                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50">
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Brand</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest font-bold text-white">Data Type</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @forelse($items as $item)
                            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                                <td class="px-6 py-4 font-mono text-blue-400 font-medium text-center whitespace-nowrap">{{ $item->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded bg-gray-700/50 text-gray-300 border border-gray-600/30">{{ $item->brand->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <span class="text-gray-100 font-medium">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->status == 'Aktif' ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="relative flex justify-center" x-data="{ open: false }">
                                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-colors border border-gray-700/50">
                                            <i class='bx bx-dots-vertical-rounded text-lg'></i>
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                                            <button onclick="openEditModal({{ $item->id }}, {{ $item->data_brand_name_id ?? 'null' }}, '{{ addslashes($item->name) }}', '{{ $item->status }}')" class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3">
                                                <i class='bx bx-edit-alt text-blue-400 text-lg'></i>
                                                <span class="font-medium">Edit</span>
                                            </button>
                                            <form action="{{ route('data-type.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Silahkan konfirmasi untuk menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 flex items-center gap-3">
                                                    <i class='bx bx-trash text-lg'></i>
                                                    <span class="font-medium">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class='bx bx-data text-5xl mb-3 opacity-20'></i>
                                        <p>No type records found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500">Showing <span class="text-white font-bold">{{ $items->firstItem() ?? 0 }}</span> to <span class="text-white font-bold">{{ $items->lastItem() ?? 0 }}</span> of <span class="text-white font-bold">{{ $items->total() }}</span> entries</div>
                    <div class="flex gap-1">
                        {{ $items->links('vendor.pagination.custom-dark') }}
                    </div>
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
                    <h3 id="modalTitle" class="text-xl font-bold text-white tracking-tight text-glow-blue">Add New Data Type</h3>
                    <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                
                <form id="modalForm" method="POST">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="POST">
                    <div class="p-6 space-y-5">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-400">Brand</label>
                            <select name="data_brand_name_id" id="modal_brand_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all appearance-none" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-400">Type Name</label>
                            <input type="text" name="name" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all placeholder-gray-600" placeholder="Enter type name" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-400">Status</label>
                            <select name="status" id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all appearance-none" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-700 bg-gray-900/50 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all font-bold text-sm text-glow-blue">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('pages.setup-channel-email.partials._scrollbar')

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Add New Data Type';
            document.getElementById('modalForm').action = "{{ route('data-type.store') }}";
            document.getElementById('methodField').value = 'POST';
            document.getElementById('modal_brand_id').value = '';
            document.getElementById('modal_name').value = '';
            document.getElementById('modal_status').value = 'Aktif';
            document.getElementById('masterModal').classList.remove('hidden');
        }

        function openEditModal(id, brand_id, name, status) {
            document.getElementById('modalTitle').innerText = 'Edit Data Type';
            document.getElementById('modalForm').action = "/data-type/" + id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('modal_brand_id').value = brand_id;
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_status').value = status;
            document.getElementById('masterModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('masterModal').classList.add('hidden');
        }
    </script>
@endsection