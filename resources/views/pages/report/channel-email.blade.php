@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 report-channel-email-page">

    {{-- Page Header --}}
    <header class="flex-shrink-0 mb-3 px-6 pt-4">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-[28px] font-bold text-white tracking-tight">Report Channel Email</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Report</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="current text-blue-500 font-semibold">Channel Email</span>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full gap-4">

        {{-- Filter Card --}}
        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-xl ring-1 ring-white/5 shrink-0 px-5 py-4">
            <form id="filter-form" method="GET" action="{{ route('report.channel-email') }}">
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
                    <a href="{{ route('report.channel-email') }}"
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
                <table class="data-table w-full text-left border-collapse table-fixed min-w-[1200px]"
                       x-data="sharedTableData()">
                    <thead class="bg-gray-900/50 sticky top-0 z-20 shadow-sm text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th draggable="true" @dragstart="handleDragStart($event,'No')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-12 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>No</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Ticket Number')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Ticket Number</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Subject')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-56 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Subject</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'From')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-52 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>From</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Agent')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Agent</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Status')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-32 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Status</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Response Time')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-36 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/40 whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Response Time</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                            <th draggable="true" @dragstart="handleDragStart($event,'Received At')" @dragend="handleDragEnd($event)"
                                class="bg-gray-900 draggable-header px-3 py-3 w-44 hover:bg-gray-800/80 transition-colors group cursor-grab active:cursor-grabbing whitespace-nowrap">
                                <div class="flex items-center justify-between"><span>Received At</span>
                                <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        @forelse($emailData as $index => $row)
                            @php
                                $statusClass = match(strtolower($row['status'])) {
                                    'closed'   => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                    'open'     => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'pending'  => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                    'replied'  => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    default    => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                };
                                $statusPulse = strtolower($row['status']) === 'open';
                            @endphp
                            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                                <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-500 text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-blue-400 font-mono font-semibold text-xs tracking-wide">
                                        {{ $row['ticket_number'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 max-w-[200px] truncate">
                                    <span class="text-white font-medium text-sm" title="{{ $row['subject'] }}">
                                        {{ $row['subject'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class='bx bx-envelope text-cyan-400 text-base'></i>
                                        <span class="text-gray-400 text-xs truncate max-w-[160px]" title="{{ $row['from'] }}">
                                            {{ $row['from'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600/30 border border-indigo-500/30 flex items-center justify-center shrink-0 text-[10px] font-bold text-white">
                                            {{ strtoupper(substr($row['agent'], 0, 1)) }}
                                        </div>
                                        <span class="text-gray-300 text-xs truncate max-w-[115px]" title="{{ $row['agent'] }}">
                                            {{ $row['agent'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusPulse ? 'bg-emerald-400 animate-pulse' : 'bg-current' }}"></span>
                                        {{ ucfirst($row['status']) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs {{ $row['response_minutes'] <= 60 ? 'text-emerald-400' : ($row['response_minutes'] <= 240 ? 'text-amber-400' : 'text-red-400') }}">
                                        {{ $row['response_time'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-gray-400 text-xs">
                                    {{ $row['received_at'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-600">
                                        <i class='bx bx-envelope-open text-5xl'></i>
                                        <p class="text-sm font-medium">No channel email records found</p>
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
                        Showing <span class="text-white font-semibold">{{ count($emailData) }}</span> records
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



    <div id="shared-report-config" class="hidden" data-endpoint="{{ route('report.channel-email') }}"></div>
@push('scripts')
    @vite('resources/js/pages/report/shared-report.js')
@endpush
@endsection

