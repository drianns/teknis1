@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 report-base-on-transaction-page">

    {{-- Page Header --}}
    <header class="flex-shrink-0 mb-3 px-6 pt-4">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-[28px] font-bold text-white tracking-tight">Report Base on Transaction</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Report</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="current text-blue-500 font-semibold">Base on Transaction</span>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full gap-4">

        {{-- Filter Card --}}
        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-xl ring-1 ring-white/5 shrink-0 px-5 py-4">
            <form id="filter-form" method="GET" action="{{ route('report.base-on-transaction') }}">
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

                    {{-- Reset --}}
                    @if(request('start_date') || request('end_date'))
                    <a href="{{ route('report.base-on-transaction') }}"
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

                <div class="flex items-center gap-3 text-gray-500 text-sm"
                     x-show="groupedColumns.length === 0">
                    <i class='bx bx-move text-lg'></i>
                    <span>Drag a column header here to group by that column</span>
                </div>

                <div class="flex flex-wrap gap-2 items-center w-full"
                     x-show="groupedColumns.length > 0" x-cloak>
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

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 shrink-0">
            @php $total = $tickets->total(); @endphp

            <div class="bg-gray-800/80 border border-gray-700/50 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                    <i class='bx bx-transfer-alt text-blue-400 text-lg'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Transactions</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($total) }}</p>
                </div>
            </div>

            <div class="bg-gray-800/80 border border-gray-700/50 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                    <i class='bx bx-check-circle text-emerald-400 text-lg'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Closed</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($closedCount) }}</p>
                </div>
            </div>

            <div class="bg-gray-800/80 border border-gray-700/50 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <i class='bx bx-time-five text-amber-400 text-lg'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Open / Pending</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($openCount) }}</p>
                </div>
            </div>

            <div class="bg-gray-800/80 border border-gray-700/50 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center shrink-0">
                    <i class='bx bx-up-arrow-alt text-red-400 text-lg'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Escalated</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($escalatedCount) }}</p>
                </div>
            </div>
        </div>

        {{-- Data Table Container --}}
        <div class="table-container bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl ring-1 ring-white/5 flex-1 flex flex-col min-h-0 overflow-hidden">

            <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                <table class="data-table w-full text-left border-collapse table-fixed min-w-[1300px]"
                       x-data="transactionTableData()">
                    <thead class="bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th draggable="true" @dragstart="handleDragStart($event,'No')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-12 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>No</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Ticket Number')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Ticket Number</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Customer')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Customer</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Agent')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-40 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Agent</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Category')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-36 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Category</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Sub Category')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-36 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Sub Category</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Priority')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-28 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Priority</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Status')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-28 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Status</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Created At')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-40 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Created At</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Duration')" @dragend="handleDragEnd($event)"
                                class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-32 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Duration</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        @forelse($tickets as $index => $ticket)
                            @php
                                $agentName    = $ticket->userAgent->full_name
                                    ?? ($ticket->userAgent->user->name ?? 'Unassigned');
                                $customerName = $ticket->chat_ticket_user->name ?? '—';

                                // Duration: created → updated
                                $durationMin = $ticket->created_at
                                    ? (int) $ticket->created_at->diffInMinutes($ticket->updated_at ?? now())
                                    : 0;
                                $durH = floor($durationMin / 60);
                                $durM = $durationMin % 60;
                                $durationStr = sprintf('%dh %02dm', $durH, $durM);

                                // Priority badge
                                $priority    = strtolower($ticket->priority ?? '');
                                $prioClass   = match(true) {
                                    str_contains($priority, 'high')   => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    str_contains($priority, 'medium') => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                    str_contains($priority, 'low')    => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    default                           => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                };
                                $prioLabel   = ucfirst($ticket->priority ?? 'Normal');

                                // Status badge
                                $ticketStatus = strtolower($ticket->status ?? '');
                                $statusClass  = match(true) {
                                    str_contains($ticketStatus, 'open')    => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    str_contains($ticketStatus, 'close')   => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                    str_contains($ticketStatus, 'pending') => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                    str_contains($ticketStatus, 'escalat') => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    default                                => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                };
                            @endphp
                            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-500 text-xs">
                                    {{ $tickets->firstItem() + $index }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-blue-400 font-mono font-semibold text-xs tracking-wide">
                                        {{ $ticket->ticket_number }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-teal-600/30 border border-teal-500/30 flex items-center justify-center shrink-0">
                                            <i class='bx bx-user text-teal-400 text-xs'></i>
                                        </div>
                                        <span class="text-gray-300 truncate max-w-[110px]" title="{{ $customerName }}">
                                            {{ $customerName }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600/30 border border-indigo-500/30 flex items-center justify-center shrink-0">
                                            <i class='bx bx-headphone text-indigo-400 text-xs'></i>
                                        </div>
                                        <span class="text-gray-300 truncate max-w-[110px]" title="{{ $agentName }}">
                                            {{ $agentName }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-400 text-xs truncate">
                                    {{ $ticket->category ?? '—' }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-500 text-xs truncate">
                                    {{ $ticket->subcategory ?? '—' }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $prioClass }}">
                                        {{ $prioLabel }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ str_contains($ticketStatus, 'open') ? 'bg-emerald-400 animate-pulse' : 'bg-current' }}"></span>
                                        {{ ucfirst($ticket->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-400 text-xs">
                                    {{ $ticket->created_at ? $ticket->created_at->format('d M Y H:i') : '—' }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs text-gray-400">{{ $durationStr }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-600">
                                        <i class='bx bx-transfer text-5xl'></i>
                                        <p class="text-sm font-medium">No transaction records found</p>
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

            {{-- Footer: Pagination + Export --}}
            <div class="px-5 py-3 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30 shrink-0">

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="text-sm text-gray-500 hidden lg:block">
                        Showing <span class="text-white font-semibold">{{ $tickets->firstItem() ?? 0 }}</span>
                        to <span class="text-white font-semibold">{{ $tickets->lastItem() ?? 0 }}</span>
                        of <span class="text-white font-semibold">{{ $tickets->total() }}</span> results
                    </div>
                    <div>
                        {{ $tickets->appends(request()->query())->links('components.pagination') }}
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Show</label>
                        <select id="per-page-select"
                                class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 appearance-none py-1.5 pl-3 pr-8 cursor-pointer outline-none transition-colors hover:border-gray-600"
                                onchange="window.location.href='?per_page='+this.value+'&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}'">
                            <option value="10"  {{ request('per_page', 10) == 10  ? 'selected' : '' }}>10</option>
                            <option value="25"  {{ request('per_page') == 25  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

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
    .draggable-header.dragging {
        opacity: 0.4;
        background-color: #1e3a8a !important;
        border: 1px dashed #3b82f6;
    }
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
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.25s ease forwards; }
</style>

<script>
    function dragDropData() {
        return {
            groupedColumns: [],
            isDraggingOver: false,
            init() {
                this.$watch('groupedColumns', value => { window.reportGroupedColumns = value; });
            },
            handleDrop(event) {
                this.isDraggingOver = false;
                const col = event.dataTransfer.getData('text/plain');
                if (col && !this.groupedColumns.includes(col)) this.groupedColumns.push(col);
                document.querySelectorAll('.draggable-header').forEach(el => el.classList.remove('dragging'));
            },
            removeGroupedColumn(index) { this.groupedColumns.splice(index, 1); }
        };
    }

    function transactionTableData() {
        return {
            handleDragStart(event, columnName) {
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', columnName);
                event.target.closest('th').classList.add('dragging');
            },
            handleDragEnd(event) {
                event.target.closest('th').classList.remove('dragging');
            }
        };
    }

    function exportReport(format) {
        const params = new URLSearchParams({
            start_date: document.getElementById('start_date')?.value || '',
            end_date:   document.getElementById('end_date')?.value   || '',
            format:     format,
            export:     '1'
        });
        window.location.href = '{{ route("report.base-on-transaction") }}?' + params.toString();
    }
</script>
@endsection
