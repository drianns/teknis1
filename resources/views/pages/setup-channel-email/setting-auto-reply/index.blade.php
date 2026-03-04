@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Setting Auto Reply Email</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Setting Auto Reply Email</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <!-- Table Controls -->
                <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="show-entries flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select class="entries-select">
                            <option>10</option><option>25</option><option>50</option><option>100</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="search-box relative">
                        <input type="text" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500" placeholder="Search..." />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Nama Tipe</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-28 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @forelse($rows as $row)
                            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-blue-400 font-medium text-center">{{ $row['id'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-white">{{ $row['nama'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ ($row['aktif'] ?? false) ? 'checked' : '' }}>
                                        <div class="relative w-11 h-6 bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-12 text-center text-gray-500 text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class='bx bx-folder-open text-4xl'></i>
                                        <p class="text-sm font-medium">No auto reply settings found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500">Showing <span class="text-white font-bold">{{ $rows->firstItem() ?? 0 }}</span> to <span class="text-white font-bold">{{ $rows->lastItem() ?? 0 }}</span> of <span class="text-white font-bold">{{ $rows->total() }}</span> entries</div>
                    <div class="flex gap-1">
                        {{ $rows->links('vendor.pagination.custom-dark') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.setup-channel-email.partials._scrollbar')
@endsection

