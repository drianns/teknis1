@extends('layouts.app')

@section('content')
    <div class="px-6 pt-4 pb-6 min-h-screen"
        x-data="{ expanded: true, orderModalOpen: false, currentTicket: '' }">
        <!-- Main Content -->
        <main class="flex-1 space-y-6">
            <!-- Header & Action Section -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-6 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bx bx-history text-white text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-white">History Ticketing</h1>
                    </div>
                    <nav class="flex text-sm text-gray-400 ml-0">
                        <a href="#" class="hover:text-blue-400">Home</a>
                        <span class="mx-2">/</span>
                        <a href="#" class="hover:text-blue-400">Apps</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-300">History Ticketing</span>
                    </nav>
                </div>

                <!-- Date Range Bar Relocated Here -->
                <div class="relative">
                    <div onclick="toggleDateFilter()" id="dateFilterBtn" class="date-range-bar">
                        <div class="bar-icon">
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <div class="date-segment">
                            <span class="segment-label">Start Date</span>
                            <span id="barStartDate" class="segment-value">Select Date</span>
                        </div>
                        <div class="date-divider"></div>
                        <div class="date-segment">
                            <span class="segment-label">End Date</span>
                            <span id="barEndDate" class="segment-value">Select Date</span>
                        </div>
                        <div class="bar-chevron">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Modern Date Picker Popup -->
                    <div id="dateFilterPopup" class="hidden absolute right-0 mt-3 modern-popup z-50 p-5">
                        <div class="space-y-4">
                            <!-- Presets -->
                            <div>
                                <span class="popup-label">Quick Selection</span>
                                <div class="preset-grid">
                                    <button onclick="setPreset('today')" class="preset-btn">Today</button>
                                    <button onclick="setPreset('yesterday')" class="preset-btn">Yesterday</button>
                                    <button onclick="setPreset('last7days')" class="preset-btn">Last 7 Days</button>
                                    <button onclick="setPreset('thismonth')" class="preset-btn">This Month</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="popup-label">Start Date</label>
                                    <input type="date" id="startDate" class="popup-input">
                                </div>
                                <div>
                                    <label class="popup-label">End Date</label>
                                    <input type="date" id="endDate" class="popup-input">
                                </div>
                            </div>

                            <div>
                                <label class="popup-label">Email Address</label>
                                <div class="relative">
                                    <i
                                        class="bx bx-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <input type="email" id="emailAddress" placeholder="Enter email..."
                                        class="popup-input pl-10">
                                </div>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button onclick="applyDateFilter()"
                                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                                    Apply Filter
                                </button>
                                <button onclick="closeDateFilter()"
                                    class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-4 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center text-gray-400 text-sm">
                        <span>Show</span>
                        <select id="perPageSelect"
                            class="mx-2 bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <!-- Search -->
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bx-search text-gray-500"></i>
                            </div>
                            <input type="text" id="searchInput"
                                class="bg-gray-900 border border-gray-700 text-gray-300 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full pl-12 p-2.5 transition-all outline-none"
                                placeholder="Search histories...">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
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
                                        Category <i
                                            class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Agent <i class="bx bx-sort text-gray-600 group-hover:text-gray-400 text-sm"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center gap-1">
                                        Posisi <i
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
                        <tbody id="dataTableBody" class="divide-y divide-gray-800 bg-transparent">
                            <!-- Data render via Javascript -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="dataTableInfo">Showing 0 to 0 of 0 entries</span>
                    <div class="mt-2 md:mt-0" id="paginationContainer">
                        <!-- Pagination buttons populated by JS -->
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

    @include('components.loading-overlay')

    {{-- Config element: passes PHP/Blade values to the JS module --}}
    <div id="history-ticketing-config" class="hidden"
        data-endpoint="{{ route('apps.history-ticketing.getData') }}"
    ></div>

@push('scripts')
    @vite('resources/js/pages/apps/history-ticketing.js')
@endpush
@endsection
