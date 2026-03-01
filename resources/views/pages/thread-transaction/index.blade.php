<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Thread Transaction
    </x-slot>

    <div class="min-h-screen bg-gray-900">
        <!-- Main Content -->
        <main class="flex-1 p-6">
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
                        <tbody class="divide-y divide-gray-800 bg-transparent">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-900/40">
                                    <td class="px-6 py-4">
                                        @if($trx->type == 'call')
                                            <div class="bg-orange-500/10 text-orange-500 p-1.5 rounded-lg inline-flex">
                                                <i class="bx bx-phone-call text-lg"></i>
                                            </div>
                                        @else
                                            <div class="bg-blue-500/10 text-blue-500 p-1.5 rounded-lg inline-flex">
                                                <i class="bx bx-message-rounded-dots text-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($trx->channel == 'whatsapp')
                                            <i class="bx bxl-whatsapp text-green-500 text-xl"></i>
                                        @elseif($trx->channel == 'facebook')
                                            <i class="bx bxl-facebook-circle text-blue-600 text-xl"></i>
                                        @elseif($trx->channel == 'instagram')
                                            <i class="bx bxl-instagram text-pink-500 text-xl"></i>
                                        @else
                                            <i class="bx bx-question-mark text-gray-500 text-xl"></i>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-white">{{ $trx->name }}</td>
                                    <td class="px-6 py-4 text-cyan-400">{{ $trx->number_id }}</td>
                                    <td class="px-6 py-4">{{ $trx->account }}</td>
                                    <td class="px-6 py-4">{{ $trx->subject }}</td>
                                    <td class="px-6 py-4">{{ $trx->agent }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 p-2 rounded-lg transition-colors"
                                                title="View">
                                                <i class="bx bx-show"></i>
                                            </button>
                                            <button
                                                class="bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 p-2 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                            <p>No data available in table</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (Mock UI) -->
                <div
                    class="p-4 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                    <span>Showing 0 to 0 of 0 entries</span>
                    <div class="flex gap-1 mt-2 md:mt-0">
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50"
                            disabled>Previous</button>
                        <button class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50"
                            disabled>Next</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
            <p class="mt-4 text-white text-lg font-semibold tracking-wider">Loading...</p>
        </div>
    </div>

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