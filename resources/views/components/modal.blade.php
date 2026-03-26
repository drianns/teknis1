@props(['id', 'title', 'saveBtnId' => 'saveBtn', 'saveBtnText' => 'Save Changes', 'maxWidth' => 'max-w-lg'])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm" onclick="CrudTable.closeModal('{{ $id }}')"></div>
        <div class="relative bg-gray-800 rounded-2xl shadow-2xl w-full {{ $maxWidth }} overflow-hidden border border-gray-700 ring-1 ring-white/5 transition-all transform scale-95 opacity-0 duration-300">
            <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
                <h3 id="{{ $id }}_title" class="text-xl font-bold text-white tracking-tight">{{ $title }}</h3>
                <button onclick="CrudTable.closeModal('{{ $id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <div class="p-6 space-y-5">
                {{ $slot }}
            </div>

            <div class="px-6 py-4 border-t border-gray-700 bg-gray-900/50 flex justify-end gap-3 rounded-b-2xl">
                <button onclick="CrudTable.closeModal('{{ $id }}')" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">Cancel</button>
                <button id="{{ $saveBtnId }}" onclick="saveData()" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 flex items-center justify-center min-w-[120px]">
                    {{ $saveBtnText }}
                </button>
            </div>
        </div>
    </div>
</div>
