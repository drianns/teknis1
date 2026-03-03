@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 report-aux-page">

    {{-- Page Header --}}
    <header class="flex-shrink-0 mb-3 px-6 pt-4">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-[28px] font-bold text-white tracking-tight">Report AUX</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Report</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="current text-blue-500 font-semibold">AUX</span>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full gap-4">

        {{-- Filter Card --}}
        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-xl ring-1 ring-white/5 shrink-0 px-5 py-4">
            <form id="filter-form" method="GET" action="{{ route('report.aux') }}">
                <div class="flex flex-wrap items-end gap-4">

                    {{-- Start Date --}}
                    <div class="flex flex-col gap-1.5 min-w-[160px]">
                        <label for="start_date" class="text-xs font-semibold text-blue-400 uppercase tracking-wider">Start Date</label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ request('start_date') }}"
                            class="bg-gray-900 border border-gray-700 text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500 transition-colors hover:border-gray-600 cursor-pointer">
                    </div>

                    {{-- End Date --}}
                    <div class="flex flex-col gap-1.5 min-w-[160px]">
                        <label for="end_date" class="text-xs font-semibold text-blue-400 uppercase tracking-wider">End Date</label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ request('end_date') }}"
                            class="bg-gray-900 border border-gray-700 text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500 transition-colors hover:border-gray-600 cursor-pointer">
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="btn-submit-filter"
                        class="flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 active:scale-95 border border-blue-500/50">
                        <i class='bx bx-search-alt text-base'></i>
                        Submit
                    </button>

                    @if(request('start_date') || request('end_date'))
                    <a href="{{ route('report.aux') }}"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-semibold rounded-xl transition-all active:scale-95">
                        <i class='bx bx-reset text-base'></i>
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Drag & Drop Group Zone --}}
        <div class="bg-gray-800/80 rounded-xl border border-gray-700/50 px-4 py-3 shrink-0 shadow-sm"
             x-data="dragDropData()">
            <div id="drop-zone"
                 class="drop-zone border border-dashed border-gray-600 rounded-lg px-4 py-2.5 flex items-center min-h-[44px] transition-all duration-300"
                 :class="{ 'border-blue-500 bg-blue-900/10 shadow-[0_0_15px_rgba(59,130,246,0.1)]': isDraggingOver }"
                 @dragover.prevent="isDraggingOver = true"
                 @dragleave.prevent="isDraggingOver = false"
                 @drop="handleDrop($event)">
                <div class="flex items-center gap-3 text-gray-500 text-sm" x-show="groupedColumns.length === 0">
                    <i class='bx bx-move text-lg'></i>
                    <span>Drag a column header here to group by that column</span>
                </div>
                <div class="flex flex-wrap gap-2 items-center w-full" x-show="groupedColumns.length > 0" x-cloak>
                    <div class="text-xs font-semibold text-gray-400 tracking-wider mr-2">GROUPED BY:</div>
                    <template x-for="(column, index) in groupedColumns" :key="column">
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-900 border border-gray-600 text-gray-300 rounded-md text-xs font-medium shadow-sm animate-fade-in-up">
                            <span x-text="column"></span>
                            <button @click="removeGroupedColumn(index)" class="hover:text-red-400 transition-colors ml-1">
                                <i class='bx bx-x text-sm'></i>
                            </button>
                            <i class='bx bx-chevron-right text-gray-500 ml-1' x-show="index < groupedColumns.length - 1"></i>
                        </div>
                    </template>
                </div>
            </div>
        </div>


        {{-- Data Table --}}
        <div class="table-container bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl ring-1 ring-white/5 flex-1 flex flex-col min-h-0 overflow-hidden">

            <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                <table class="data-table w-full text-left border-collapse table-fixed min-w-[900px]"
                       x-data="auxTableData()">
                    <thead class="bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th draggable="true" @dragstart="handleDragStart($event,'No')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-12 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>No</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'AUX UserName')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>AUX UserName</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'AUX Description')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>AUX Description</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'AUX Start Date')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-48 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>AUX Start Date</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'AUX End Date')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-48 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>AUX End Date</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'AUX Interval')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-40 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>AUX Interval</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                        </tr>
                        {{-- Column search filters --}}
                        <tr class="bg-gray-900/80">
                            <td class="px-2 py-1.5 border-r border-gray-700/40"></td>
                            <td class="px-2 py-1.5 border-r border-gray-700/40">
                                <input type="text" id="filter-username" placeholder=""
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded-md px-2 py-1 focus:outline-none focus:border-blue-500"
                                    oninput="filterTable()">
                            </td>
                            <td class="px-2 py-1.5 border-r border-gray-700/40">
                                <input type="text" id="filter-desc" placeholder=""
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded-md px-2 py-1 focus:outline-none focus:border-blue-500"
                                    oninput="filterTable()">
                            </td>
                            <td class="px-2 py-1.5 border-r border-gray-700/40"></td>
                            <td class="px-2 py-1.5 border-r border-gray-700/40"></td>
                            <td class="px-2 py-1.5">
                                <input type="text" id="filter-interval" placeholder=""
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded-md px-2 py-1 focus:outline-none focus:border-blue-500"
                                    oninput="filterTable()">
                            </td>
                        </tr>
                    </thead>
                    <tbody id="aux-tbody" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        @forelse($auxData as $index => $row)
                            @php
                                $descColor = match(strtolower($row['description'])) {
                                    'lunch'   => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'toilet'  => 'bg-sky-500/20 text-sky-400 border-sky-500/30',
                                    'prayer'  => 'bg-violet-500/20 text-violet-400 border-violet-500/30',
                                    'break'   => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                    'meeting' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    'training'=> 'bg-pink-500/20 text-pink-400 border-pink-500/30',
                                    default   => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                };
                                $descIcon = match(strtolower($row['description'])) {
                                    'lunch'   => 'bx-food-menu',
                                    'toilet'  => 'bx-water',
                                    'prayer'  => 'bx-star',
                                    'break'   => 'bx-coffee',
                                    'meeting' => 'bx-group',
                                    'training'=> 'bx-book-open',
                                    default   => 'bx-time-five',
                                };
                            @endphp
                            <tr class="hover:bg-blue-500/[0.03] transition-colors aux-row"
                                data-username="{{ strtolower($row['username']) }}"
                                data-desc="{{ strtolower($row['description']) }}"
                                data-interval="{{ strtolower($row['interval']) }}">
                                <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-500 text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600/30 border border-indigo-500/30 flex items-center justify-center shrink-0 text-[10px] font-bold text-white">
                                            {{ strtoupper(substr($row['username'], 0, 1)) }}
                                        </div>
                                        <span class="text-gray-300 text-xs font-medium">{{ $row['username'] }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $descColor }}">
                                        <i class='bx {{ $descIcon }} text-sm'></i>
                                        {{ $row['description'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-400 text-xs font-mono">
                                    {{ $row['start_date'] }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-400 text-xs font-mono">
                                    {{ $row['end_date'] }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs text-cyan-400 bg-cyan-500/10 px-2 py-1 rounded-md border border-cyan-500/20">
                                        {{ $row['interval'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-600">
                                        <i class='bx bx-user-x text-5xl'></i>
                                        <p class="text-sm font-medium">No AUX records found</p>
                                        @if(request('start_date') || request('end_date'))
                                            <p class="text-xs text-gray-700">Try adjusting the date range filter</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30 shrink-0">

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="text-sm text-gray-500 hidden lg:block">
                        Showing <span class="text-white font-semibold">{{ count($auxData) }}</span> records
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-px h-6 bg-gray-700"></div>

                    <div class="flex items-center gap-2" x-data="{ fmt: 'Excel' }">
                        <select id="export-format" x-model="fmt"
                                class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 appearance-none py-1.5 pl-3 pr-8 cursor-pointer outline-none transition-colors hover:border-gray-600 min-w-[90px]">
                            <option value="Excel">Excel</option>
                            <option value="CSV">CSV</option>
                            <option value="PDF">PDF</option>
                        </select>
                        <button type="button" id="btn-export" @click="exportReport(fmt)"
                                class="flex items-center gap-2 px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all border border-blue-500 shadow-[0_0_10px_rgba(37,99,235,0.2)] hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] active:scale-95 group">
                            <i class='bx bx-cloud-download text-lg group-hover:-translate-y-0.5 transition-transform'></i>
                            <span>Export</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .draggable-header.dragging { opacity: 0.4; background-color: #1e3a8a !important; border: 1px dashed #3b82f6; }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #111827; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.25rem 1.25rem;
    }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.25s ease forwards; }
</style>

<script>
    function dragDropData() {
        return {
            groupedColumns: [], isDraggingOver: false,
            init() { this.$watch('groupedColumns', v => { window.reportGroupedColumns = v; }); },
            handleDrop(event) {
                this.isDraggingOver = false;
                const col = event.dataTransfer.getData('text/plain');
                if (col && !this.groupedColumns.includes(col)) this.groupedColumns.push(col);
                document.querySelectorAll('.draggable-header').forEach(el => el.classList.remove('dragging'));
            },
            removeGroupedColumn(index) { this.groupedColumns.splice(index, 1); }
        };
    }

    function auxTableData() {
        return {
            handleDragStart(event, columnName) {
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', columnName);
                event.target.closest('th').classList.add('dragging');
            },
            handleDragEnd(event) { event.target.closest('th').classList.remove('dragging'); }
        };
    }

    function filterTable() {
        const username = document.getElementById('filter-username').value.toLowerCase();
        const desc     = document.getElementById('filter-desc').value.toLowerCase();
        const interval = document.getElementById('filter-interval').value.toLowerCase();

        document.querySelectorAll('.aux-row').forEach(row => {
            const matchUser     = row.dataset.username.includes(username);
            const matchDesc     = row.dataset.desc.includes(desc);
            const matchInterval = row.dataset.interval.includes(interval);
            row.style.display = (matchUser && matchDesc && matchInterval) ? '' : 'none';
        });
    }

    function exportReport(format) {
        const params = new URLSearchParams({
            start_date: document.getElementById('start_date')?.value || '',
            end_date:   document.getElementById('end_date')?.value   || '',
            format, export: '1'
        });
        window.location.href = '{{ route("report.aux") }}?' + params.toString();
    }
</script>
@endsection