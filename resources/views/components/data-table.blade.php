@props(['columns' => []])

<div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
    <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
        <!-- Controls -->
        <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-3 text-sm text-gray-400">
                <span>Show</span>
                <select id="perPage" class="entries-select bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-gray-300 focus:outline-none focus:border-blue-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-4">
                @if(isset($actions))
                    {{ $actions }}
                @endif
                <div class="relative">
                    <input type="text" id="searchInput" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500 transition-all focus:w-80" placeholder="Search..." />
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
            <table class="w-full text-left border-collapse table-auto md:table-fixed">
                <thead>
                    <tr class="bg-gray-900/50">
                        @if(isset($thead))
                            {{ $thead }}
                        @else
                            @foreach($columns as $col)
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap {{ strtolower($col) === 'action' || strtolower($col) === 'status' || strtolower($col) === 'id' ? 'text-center' : '' }} {{ strtolower($col) === 'id' ? 'w-16' : '' }} {{ strtolower($col) === 'action' ? 'w-24' : '' }}">
                                    {{ $col }}
                                </th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                    <tr>
                        <td colspan="100%" class="px-6 py-16 text-center text-gray-500">
                            <i class="bx bx-loader-alt bx-spin text-3xl mb-2 text-blue-500"></i>
                            <p>Loading data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
            <div id="paginationInfo" class="text-sm text-gray-500">Loading...</div>
            <div id="paginationLinks" class="flex gap-1"></div>
        </div>
    </div>
</div>
