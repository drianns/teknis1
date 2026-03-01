<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Ticketing Department
    </x-slot>

    <div class="min-h-screen bg-gray-900">
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
                                                                                            <div class="flex items-center justify-center">
                                                                                                <a href="{{ route('journey.index', [
                                    'ticket_number' => $ticket->ticket_number,
                                    'name' => $ticket->name,
                                    'category' => $ticket->kategori,
                                    'agent' => $ticket->agent,
                                    'posisi' => $ticket->department,
                                    'status' => $ticket->status,
                                    'date' => $ticket->created_at,
                                ]) }}"
                                                                                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 p-2 rounded transition-all hover:scale-110 flex items-center gap-1.5 group"
                                                                                                    title="Follow Up">
                                                                                                    <i class="bx bx-right-arrow-circle text-lg"></i>
                                                                                                    <span
                                                                                                        class="text-xs font-semibold overflow-hidden w-0 group-hover:w-auto transition-all duration-300">Follow
                                                                                                        Up</span>
                                                                                                </a>
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
                    <span>Showing 1 to 10 of 23 entries</span>
                    <div class="flex gap-1 mt-2 md:mt-0">
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50"
                            disabled>Previous</button>
                        <button class="px-3 py-1 bg-blue-600 text-white rounded-lg">1</button>
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg">2</button>
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg">3</button>
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg">Next</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-dashonic-horizontal-layout>