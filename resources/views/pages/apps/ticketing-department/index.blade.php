<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Ticketing Department
    </x-slot>

    <div class="min-h-screen bg-gray-900 w-full overflow-x-hidden"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <!-- Main Content -->
        <main class="flex-1 p-2 md:p-4">
            <!-- Header & Breadcrumb -->
            <div class="flex flex-col mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="bx bx-table text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-white">Data Taskboar Ticket Department</h1>
                </div>
                <nav class="flex text-sm text-gray-400 ml-0">
                    <a href="#" class="hover:text-blue-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="#" class="hover:text-blue-400">Apps</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-300">Ticketing Department</span>
                </nav>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-3 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="flex items-center text-gray-400 text-sm">
                        <span>Show</span>
                        <select
                            class="mx-2 bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-500"></i>
                        </div>
                        <input type="text"
                            class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-2.5"
                            placeholder="Search...">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col"
                                    class="px-3 py-3 font-bold cursor-pointer hover:text-white group whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        ID <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Ticket Number <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Name <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Kategori <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Department <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3 font-bold cursor-pointer hover:text-white group whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        SLA <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Agent <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Status <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Date <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold text-center whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-transparent">
                            @forelse($tickets as $ticket)
                                                        <tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-900/40">
                                                            <td class="px-3 py-3 font-medium text-blue-400 whitespace-nowrap">{{ $ticket->id }}</td>
                                                            <td class="px-3 py-3 text-cyan-400 max-w-[140px]">
                                                                <div class="truncate" title="{{ $ticket->ticket_number }}">
                                                                    {{ $ticket->ticket_number }}
                                                                </div>
                                                            </td>
                                                            <td class="px-3 py-3 text-white font-medium max-w-[120px]">
                                                                <div class="truncate" title="{{ $ticket->name }}">{{ $ticket->name }}</div>
                                                            </td>
                                                            <td class="px-3 py-3 max-w-[100px]">
                                                                <div class="truncate" title="{{ $ticket->kategori }}">{{ $ticket->kategori }}</div>
                                                            </td>
                                                            <td class="px-3 py-3 max-w-[100px]">
                                                                <div class="truncate" title="{{ $ticket->department }}">{{ $ticket->department }}
                                                                </div>
                                                            </td>
                                                            <td class="px-3 py-3 whitespace-nowrap">{{ $ticket->sla }}</td>
                                                            <td class="px-3 py-3 max-w-[100px]">
                                                                <div class="truncate" title="{{ $ticket->agent }}">{{ $ticket->agent }}</div>
                                                            </td>
                                                            <td class="px-3 py-3 whitespace-nowrap">
                                                                @php
                                                                    $statusColor = match (strtolower($ticket->status)) {
                                                                        'open' => 'bg-blue-500 text-white',
                                                                        'pending' => 'bg-yellow-500 text-white',
                                                                        'in progress' => 'bg-teal-500 text-white',
                                                                        'resolved' => 'bg-green-500 text-white',
                                                                        'closed' => 'bg-gray-500 text-white',
                                                                        default => 'bg-gray-500 text-white'
                                                                    };
                                                                @endphp
                                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                                    {{ $ticket->status }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3 text-gray-500 text-xs whitespace-nowrap">
                                                                {{ $ticket->created_at }}
                                                            </td>
                                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                                <div class="flex items-center justify-center gap-2" x-data="{ open: false }">
                                                                    <div class="relative">
                                                                        <button @click="open = !open" @click.outside="open = false"
                                                                            class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                                                                            <i class="bx bx-dots-vertical-rounded text-base"></i>
                                                                        </button>
                                                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                                            x-transition:enter-start="opacity-0 scale-95"
                                                                            x-transition:enter-end="opacity-100 scale-100"
                                                                            class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden"
                                                                            style="display: none;">
                                                                            <button
                                                                                @click="orderModalOpen = true; currentTicket = '{{ $ticket->ticket_number }}'; open = false"
                                                                                class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors">
                                                                                <i class="bx bx-hash text-sm"></i> Order ID
                                                                            </button>
                                                                            <div class="h-px bg-gray-700 my-1"></div>
                                                                            <a href="{{ route('journey.index', [
                                        'ticket_number' => $ticket->ticket_number,
                                        'name' => $ticket->name,
                                        'category' => $ticket->kategori,
                                        'agent' => $ticket->agent,
                                        'posisi' => $ticket->department,
                                        'status' => $ticket->status,
                                        'date' => $ticket->created_at,
                                    ]) }}"
                                                                                class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                                                                                <i class="bx bx-right-arrow-circle text-sm"></i> Follow Up
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                            <p>No tickets available</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span>Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries</span>
                    <div class="mt-2 md:mt-0">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </main>

        <!-- Order ID Modal -->
        <div x-show="orderModalOpen" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm">
            <div x-show="orderModalOpen" @click.outside="orderModalOpen = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl w-full max-w-md p-6 relative">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Input Order ID</h3>
                    <button @click="orderModalOpen = false" class="text-gray-500 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <p class="text-gray-400 text-sm mb-4">Please enter the Order ID for Ticket <span
                        class="text-blue-400 font-bold" x-text="currentTicket"></span></p>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Order
                        ID</label>
                    <div class="relative">
                        <i class="bx bx-hash absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="text"
                            class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all placeholder-gray-600"
                            placeholder="e.g. ORD-2026-001">
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button @click="orderModalOpen = false"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all">
                        Close
                    </button>
                    <button
                        @click="orderModalOpen = false; showLoading(); setTimeout(() => { document.getElementById('loading-overlay').classList.add('hidden') }, 1000)"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm hidden">
        <div class="flex flex-col items-center">
            <div class="relative">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>
                <div class="absolute inset-0 animate-ping rounded-full h-16 w-16 border-2 border-blue-500/20"></div>
            </div>
            <p class="mt-6 text-white text-xs font-black uppercase tracking-[0.3em] animate-pulse">Processing Request
            </p>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading on browser back button (bfcache restore)
        window.addEventListener('pageshow', function (event) {
            document.getElementById('loading-overlay').classList.add('hidden');
        });
    </script>
</x-dashonic-horizontal-layout>