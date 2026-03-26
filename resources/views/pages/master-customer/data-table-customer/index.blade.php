@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-900">
        <!-- Main Content -->
        <main class="flex-1 p-2 md:p-4">
            <!-- Header & Breadcrumb -->
            <div class="flex flex-col mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="bx bx-table text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-white">Data Table Customer</h1>
                </div>
                <nav class="flex text-sm text-gray-400 ml-0">
                    <a href="#" class="hover:text-blue-400">Home</a>
                    <span class="mx-2">/</span>
                    <a href="#" class="hover:text-blue-400">Apps</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-300">Data Table Customer</span>
                </nav>
            </div>

            <!-- Drag & Drop Grouping Area -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-4 mb-4">
                <div id="dropZone"
                    class="border-2 border-dashed border-gray-600 rounded-lg p-4 min-h-[60px] flex items-center gap-2">
                    <i class="bx bx-move text-gray-500 text-xl"></i>
                    <span class="text-gray-400 text-sm" id="dropZoneText">Drag a column header here to group by that
                        column</span>
                    <div id="groupedColumns" class="flex gap-2 flex-wrap"></div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Controls -->
                <div class="p-3 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="flex items-center gap-3">
                        <!-- Show Entries -->
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

                        <!-- Export Excel -->
                        <button onclick="exportToExcel()"
                            class="flex items-center gap-2 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="bx bx-download text-base"></i>
                            <span class="text-sm">Export</span>
                        </button>

                        <!-- Export Format Dropdown -->
                        <div class="relative">
                            <button onclick="toggleExportDropdown()" id="exportDropdownBtn"
                                class="flex items-center gap-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors border border-gray-600">
                                <span class="text-sm" id="selectedFormat">Excel</span>
                                <i class="bx bx-chevron-down text-base"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="exportDropdown"
                                class="hidden absolute left-0 mt-2 w-40 bg-gray-700 border border-gray-600 rounded-lg shadow-xl z-50">
                                <div class="py-1">
                                    <button onclick="selectExportFormat('Excel')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        Excel
                                    </button>
                                    <button onclick="selectExportFormat('Excel 97-2003')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        Excel 97-2003
                                    </button>
                                    <button onclick="selectExportFormat('CSV')"
                                        class="w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-600 transition-colors">
                                        CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-500"></i>
                        </div>
                        <input type="text" id="searchInput" onkeyup="searchTable()"
                            class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-2.5"
                            placeholder="Search...">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400" id="customerTable">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col" draggable="true" data-column="id" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>ID</div>
                                </th>
                                <th scope="col" draggable="true" data-column="name" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Name</div>
                                </th>
                                <th scope="col" draggable="true" data-column="email" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Email</div>
                                </th>
                                <th scope="col" draggable="true" data-column="phone" class="px-3 py-3 font-bold cursor-move hover:bg-gray-800 group">
                                    <div class="flex items-center gap-1"><i class="bx bx-move text-gray-600 group-hover:text-blue-400"></i>Phone</div>
                                </th>
                                <th scope="col" class="px-3 py-3 font-bold">Created At</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-gray-800 bg-transparent">
                            <tr><td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                    <p class="font-bold tracking-widest uppercase text-xs">Loading data...</p>
                                </div>
                            </td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span id="paginationInfo">Showing 0 to 0 of 0 entries</span>
                    <div id="paginationLinks" class="flex gap-1 mt-2 md:mt-0"></div>
                </div>
            </div>
        </main>
    </div>


    
    <div id="data-table-config" data-get="{{ route('master-customer.data-table.getData') }}" class="hidden"></div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        @vite('resources/js/pages/master-customer/data-table-customer.js')
    @endpush
@endsection
