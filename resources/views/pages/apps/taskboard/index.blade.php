@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-900 w-full overflow-x-hidden"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <div class="p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                        <div class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bxs-dashboard text-blue-500"></i>
                        </div>
                        Taskboard
                    </h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium text-gray-500">
                            <li class="inline-flex items-center">
                                <a href="#" class="hover:text-blue-400 transition-colors">Home</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <a href="#" class="hover:text-blue-400 transition-colors">Apps</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center text-blue-400/80">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <span>Taskboard</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>

            <!-- Statistics Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Open Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'open']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'open' ? 'ring-1 ring-blue-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <!-- Icon -->
                        <div
                            class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-blue-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-coupon text-2xl text-blue-500"></i>
                        </div>

                        <!-- Text -->
                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['open'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Open</span>
                        </div>
                    </div>

                    <!-- Bottom Limit Line -->
                    <div
                        class="h-[3px] w-full bg-blue-500 rounded-full group-hover:shadow-[0_0_8px_rgba(59,130,246,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Pending Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'pending']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'pending' ? 'ring-1 ring-yellow-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-yellow-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-error-circle text-2xl text-yellow-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['pending'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Pending</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-yellow-500 rounded-full group-hover:shadow-[0_0_8px_rgba(234,179,8,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- In Progress -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'in_progress']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'in_progress' ? 'ring-1 ring-teal-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-teal-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-teal-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-like text-2xl text-teal-400"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['in_progress'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">In progress</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-teal-500 rounded-full group-hover:shadow-[0_0_8px_rgba(20,184,166,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Closed Tickets -->
                <div onclick="showLoading(); window.location.href='{{ route('apps.taskboard', ['status' => 'closed']) }}'"
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent hover:-translate-y-0.5 cursor-pointer transition-all duration-300 {{ request('status') == 'closed' ? 'ring-1 ring-red-500/50' : '' }}">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-red-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-red-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-check-circle text-2xl text-red-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['closed'] ?? 0 }}</h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Closed</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-red-500 rounded-full group-hover:shadow-[0_0_8px_rgba(239,68,68,0.5)] transition-shadow duration-500">
                    </div>
                </div>

            </div>

            <!-- Table Section -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5">

                <!-- Table Controls -->
                <div
                    class="px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bx-list-ul text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Ticket Management</h2>
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">List of all reported
                                tickets</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <select id="perPageSelect" class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6">
                                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <!-- Search Box -->
                        <div class="relative w-full sm:w-64 group">
                            <i
                                class="bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                            <input type="text" id="searchInput" value="{{ request('search') }}"
                                class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600"
                                placeholder="Search tickets...">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Ticket Number <i
                                            class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Name <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Kategori <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">SLA <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Note SLA <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Position
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Agent
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Department
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Status
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    Date Create
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="taskboardTableBody" class="divide-y divide-gray-700/50">
                            <!-- Data Table will be populated here via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                <div class="px-6 py-4 border-t border-gray-700/50 flex flex-col sm:flex-row justify-between items-center bg-gray-800/30 gap-4" id="paginationContainer">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest" id="tableInfo">
                        Showing <span class="text-white">0</span> - <span class="text-white">0</span> of <span class="text-blue-400">0</span> tickets
                    </div>
                     <div id="paginationLinks">
                        <!-- Pagination buttons will be injected here via javascript -->
                     </div>
                </div>

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
                            <button @click="orderModalOpen = false"
                                class="text-gray-500 hover:text-white transition-colors">
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
            <p class="mt-6 text-white text-xs font-black uppercase tracking-[0.3em] animate-pulse">Processing Request</p>
        </div>
    </div>

    {{-- Config element: passes PHP/Blade values to the JS module via data-* attributes --}}
    <div id="taskboard-config" class="hidden"
        data-endpoint="{{ route('apps.taskboard.getData') }}"
        data-journey-url="{{ url('journey') }}"
        data-status="{{ request('status', '') }}"
    ></div>

@push('scripts')
    @vite('resources/js/pages/apps/taskboard.js')
@endpush
@endsection
