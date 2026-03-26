@extends('layouts.app')

@section('content')
    <div class="px-6 pt-4 pb-6 min-h-screen">
        <!-- Main Content -->
        <main class="flex-1 space-y-6">
            <!-- Header & Breadcrumb -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Data Channel Transaction</h1>
                    <nav class="flex text-sm text-gray-400 mt-1">
                        <a href="#" class="hover:text-blue-400">Apps</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-300">Thread Transaction</span>
                    </nav>
                </div>
            </div>

            <!-- Submit Chat ID Section (Dashboard Cards) -->
            <div class="mb-6">
                <h2 class="text-gray-400 text-sm font-medium mb-3">Submit Chat ID</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Card 1: Call -->
                    <div onclick="showLoading(); window.location.href='{{ route('apps.thread-system', ['channel' => 'call']) }}'"
                        class="bg-gray-800 rounded-xl sm:rounded-2xl p-3 sm:p-6 transform hover:scale-[1.02] transition-all duration-300 shadow-lg cursor-pointer status-card min-w-0 {{ request('channel') == 'call' ? 'border-2 border-blue-500' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-4 min-w-0 w-full">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-xl sm:rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-500/20 to-blue-600/20">
                                        <i class="bx bx-phone-call text-2xl text-blue-500"></i>
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-gray-800">
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 text-center">
                                    <h3 class="text-2xl font-bold text-white">{{ $stats['call'] ?? 0 }}</h3>
                                    <p class="text-gray-400 text-sm truncate">Voice Call</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 h-1 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 w-full"></div>
                        </div>
                    </div>

                    <!-- Card 2: Email -->
                    <div onclick="showLoading(); window.location.href='{{ route('apps.thread-system', ['channel' => 'email']) }}'"
                        class="bg-gray-800 rounded-xl sm:rounded-2xl p-3 sm:p-6 transform hover:scale-[1.02] transition-all duration-300 shadow-lg cursor-pointer status-card min-w-0 {{ request('channel') == 'email' ? 'border-2 border-green-500' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-4 min-w-0 w-full">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-xl sm:rounded-2xl flex items-center justify-center bg-gradient-to-br from-green-500/20 to-green-600/20">
                                        <i class="bx bx-envelope text-2xl text-green-500"></i>
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-gray-800">
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 text-center">
                                    <h3 class="text-2xl font-bold text-white">{{ $stats['email'] ?? 0 }}</h3>
                                    <p class="text-gray-400 text-sm truncate">Email</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 h-1 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 w-full"></div>
                        </div>
                    </div>

                    <!-- Card 3: Instagram -->
                    <div onclick="showLoading(); window.location.href='{{ route('apps.thread-system', ['channel' => 'instagram']) }}'"
                        class="bg-gray-800 rounded-xl sm:rounded-2xl p-3 sm:p-6 transform hover:scale-[1.02] transition-all duration-300 shadow-lg cursor-pointer status-card min-w-0 {{ request('channel') == 'instagram' ? 'border-2 border-pink-500' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-4 min-w-0 w-full">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-xl sm:rounded-2xl flex items-center justify-center bg-gradient-to-br from-pink-500/20 to-pink-600/20">
                                        <i class="bx bxl-instagram text-2xl text-pink-500"></i>
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-gray-800">
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 text-center">
                                    <h3 class="text-2xl font-bold text-white">{{ $stats['instagram'] ?? 0 }}</h3>
                                    <p class="text-gray-400 text-sm truncate">Instagram</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 h-1 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-pink-500 w-full"></div>
                        </div>
                    </div>

                    <!-- Card 4: Facebook -->
                    <div onclick="showLoading(); window.location.href='{{ route('apps.thread-system', ['channel' => 'facebook']) }}'"
                        class="bg-gray-800 rounded-xl sm:rounded-2xl p-3 sm:p-6 transform hover:scale-[1.02] transition-all duration-300 shadow-lg cursor-pointer status-card min-w-0 {{ request('channel') == 'facebook' ? 'border-2 border-indigo-500' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-4 min-w-0 w-full">
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-xl sm:rounded-2xl flex items-center justify-center bg-gradient-to-br from-indigo-500/20 to-indigo-600/20">
                                        <i class="bx bxl-facebook-circle text-2xl text-indigo-500"></i>
                                    </div>
                                    <div
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-gray-800">
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 text-center">
                                    <h3 class="text-2xl font-bold text-white">{{ $stats['facebook'] ?? 0 }}</h3>
                                    <p class="text-gray-400 text-sm truncate">Facebook</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 h-1 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 w-full"></div>
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
                        <input type="text" id="searchInput"
                            class="bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-2.5"
                            placeholder="Search...">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Type <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Channel <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Name <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Number ID <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Account <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Subject <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Agent <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold cursor-pointer hover:text-white group">
                                    <div class="flex items-center justify-between">
                                        Date Create <i class="bx bx-sort text-gray-600 group-hover:text-gray-400"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        Action <i class="bx bx-sort text-gray-600"></i>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="threadTableBody" class="divide-y divide-gray-800 bg-transparent">
                            <!-- Table rows will be populated by JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400" id="paginationContainer">
                    <span id="tableInfo">Showing 0 to 0 of 0 entries</span>
                    <div class="flex gap-1 mt-2 md:mt-0" id="paginationLinks">
                        <!-- Pagination links generated by JS -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('components.loading-overlay')

    @push('scripts')
    <script>
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading on browser back button (bfcache restore)
        window.addEventListener('pageshow', function (event) {
            document.getElementById('loading-overlay').classList.add('hidden');
        });
        
        // AJAX Data Fetching Logic for Thread Transaction
        document.addEventListener('DOMContentLoaded', () => {
            let searchTimeout = null;
            const state = {
                page: 1,
                perPage: 10,
                search: '',
                channel: '{{ request("channel", "") }}'
            };

            const els = {
                tbody: document.getElementById('threadTableBody'),
                tableInfo: document.getElementById('tableInfo'),
                pagination: document.getElementById('paginationLinks'),
                searchInput: document.getElementById('searchInput'),
                perPageSelect: document.getElementById('perPageSelect')
            };

            const endpointUrl = `{{ route('apps.thread-system.getData') }}`;

            function loadTableData() {
                els.tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-8 text-center text-gray-400"><div class="flex flex-col items-center justify-center gap-2 animate-pulse"><i class="bx bx-loader-alt bx-spin text-4xl mb-1 text-blue-500"></i><p>Loading records...</p></div></td></tr>`;

                const queryParams = new URLSearchParams({
                    page: state.page,
                    per_page: state.perPage,
                    search: state.search,
                    channel: state.channel
                });

                fetch(`${endpointUrl}?${queryParams.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        renderTable(data.data);
                        renderPagination(data);
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        els.tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-8 text-center text-red-500"><div class="flex flex-col items-center justify-center gap-2"><i class="bx bx-error text-4xl mb-1"></i><p>Error loading data</p></div></td></tr>`;
                    });
            }

            function renderTable(items) {
                if (!items || items.length === 0) {
                    els.tbody.innerHTML = `<tr>
                                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center border-t border-gray-800 pt-8">
                                            <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                            <p>No data available in table</p>
                                        </div>
                                    </td>
                                </tr>`;
                    return;
                }

                els.tbody.innerHTML = items.map((trx) => {
                    const typeIcon = trx.type === 'call' 
                        ? `<div class="bg-orange-500/10 text-orange-500 p-1.5 rounded-lg inline-flex"><i class="bx bx-phone-call text-lg"></i></div>`
                        : `<div class="bg-blue-500/10 text-blue-500 p-1.5 rounded-lg inline-flex"><i class="bx bx-message-rounded-dots text-lg"></i></div>`;
                    
                    let channelIcon = `<i class="bx bx-question-mark text-gray-500 text-xl"></i>`;
                    if(trx.channel === 'whatsapp') channelIcon = `<i class="bx bxl-whatsapp text-green-500 text-xl"></i>`;
                    else if(trx.channel === 'facebook') channelIcon = `<i class="bx bxl-facebook-circle text-blue-600 text-xl"></i>`;
                    else if(trx.channel === 'instagram') channelIcon = `<i class="bx bxl-instagram text-pink-500 text-xl"></i>`;
                    else if(trx.channel === 'email') channelIcon = `<i class="bx bx-envelope text-blue-400 text-xl"></i>`;

                    const dateObj = trx.created_at ? new Date(trx.created_at) : new Date();
                    const dateFormatted = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + dateObj.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });

                    return `<tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-900/40">
                        <td class="px-6 py-4">${typeIcon}</td>
                        <td class="px-6 py-4">${channelIcon}</td>
                        <td class="px-6 py-4 font-medium text-white">${trx.name || '-'}</td>
                        <td class="px-6 py-4 text-cyan-400">${trx.number_id || '-'}</td>
                        <td class="px-6 py-4">${trx.account || '-'}</td>
                        <td class="px-6 py-4">${trx.subject || '-'}</td>
                        <td class="px-6 py-4">${trx.agent || '-'}</td>
                        <td class="px-6 py-4 text-gray-500">${dateFormatted}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2" x-data="{ open: false }">
                                <div class="relative">
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                                        <i class="bx bx-dots-vertical-rounded text-base"></i>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute right-0 mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden"
                                        style="display: none;">
                                        <button
                                            class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                                            <i class="bx bx-show text-sm"></i> View
                                        </button>
                                        <button
                                            class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors">
                                            <i class="bx bx-edit text-sm"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            }
            
            function renderPagination(data) {
                const total = data.total || 0;
                const from = data.from || 0;
                const to = data.to || 0;
                els.tableInfo.innerHTML = \`Showing \${from} to \${to} of \${total} entries\`;

                if (!data.links || data.links.length === 0) {
                    els.pagination.innerHTML = '';
                    return;
                }
                
                let paginationHtml = '';
                
                data.links.forEach((link, index) => {
                    let label = link.label;
                    if (label.includes('Previous')) label = 'Previous';
                    if (label.includes('Next')) label = 'Next';

                    // Using simple button style for Thread Transaction
                    let className = "px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors";
                    if (link.active) {
                        className = "px-3 py-1 bg-blue-600 text-white font-bold rounded-lg cursor-default";
                    }

                    if (link.url) {
                        const parsedUrl = new URL(link.url);
                        const pageNum = parsedUrl.searchParams.get('page');
                        paginationHtml += \`
                            <button type="button" class="\${className}" data-page="\${pageNum}">
                                \${label}
                            </button>\`;
                    } else {
                        paginationHtml += \`
                            <button disabled class="\${className} disabled:opacity-50 cursor-not-allowed">
                                \${label}
                            </button>\`;
                    }
                });

                els.pagination.innerHTML = paginationHtml;

                // Add event listeners to pagination buttons
                const buttons = els.pagination.querySelectorAll('button[data-page]');
                buttons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        state.page = parseInt(btn.getAttribute('data-page'));
                        loadTableData();
                    });
                });
            }

            // Event Listeners for Search and Per-Page elements
            els.searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    state.search = e.target.value;
                    state.page = 1;
                    loadTableData();
                }, 300);
            });

            els.perPageSelect.addEventListener('change', (e) => {
                state.perPage = e.target.value;
                state.page = 1;
                loadTableData();
            });

            // Initial load
            loadTableData();
        });
    </script>
    @endpush
@endsection