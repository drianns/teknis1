@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-900 w-full overflow-x-hidden" x-data="{ expanded: true, orderModalOpen: false }">
        <div class="p-4 sm:p-6 lg:p-8 space-y-6">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                        <div class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bx-data text-blue-500"></i>
                        </div>
                        Monitoring User Login
                    </h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium text-gray-500">
                            <li class="inline-flex items-center">
                                <a href="#" class="hover:text-blue-400 transition-colors">Home</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <a href="#" class="hover:text-blue-400 transition-colors">Data Login</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center text-blue-400/80">
                                    <i class="bx bx-chevron-right mx-1 text-gray-600"></i>
                                    <span>Monitoring Login</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>

            <!-- Statistics Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Total User -->
                <div
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent cursor-default transition-all duration-300">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-blue-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-user text-2xl text-blue-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['total_user'] ?? 0 }}
                            </h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Total User</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-blue-500 rounded-full group-hover:shadow-[0_0_8px_rgba(59,130,246,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Not Login -->
                <div
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent cursor-default transition-all duration-300">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-red-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-red-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-user-x text-2xl text-red-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['not_login'] ?? 0 }}
                            </h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Not Login</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-red-500 rounded-full group-hover:shadow-[0_0_8px_rgba(239,68,68,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Login -->
                <div
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent cursor-default transition-all duration-300">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-user-check text-2xl text-emerald-400"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['login'] ?? 0 }}
                            </h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Login</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-emerald-500 rounded-full group-hover:shadow-[0_0_8px_rgba(16,185,129,0.5)] transition-shadow duration-500">
                    </div>
                </div>

                <!-- Aux -->
                <div
                    class="bg-[#1e2532] rounded-2xl p-4 sm:p-5 relative overflow-hidden group shadow border-b-2 border-transparent hover:border-transparent cursor-default transition-all duration-300">
                    <div class="flex items-center justify-between w-full mb-3">
                        <div
                            class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center shrink-0 border border-yellow-500/20 group-hover:scale-105 transition-transform duration-500">
                            <i class="bx bxs-user-pin text-2xl text-yellow-500"></i>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center pr-2">
                            <h3 class="text-[26px] font-bold text-white leading-none tracking-tight">
                                {{ $cardStats['aux'] ?? 0 }}
                            </h3>
                            <span class="text-[13px] text-gray-400 font-medium mt-1">Aux</span>
                        </div>
                    </div>

                    <div
                        class="h-[3px] w-full bg-yellow-500 rounded-full group-hover:shadow-[0_0_8px_rgba(234,179,8,0.5)] transition-shadow duration-500">
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
                            <h2 class="text-lg font-bold text-white">Data Users</h2>
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">List of user login
                                statuses</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <!-- Entries Selector -->
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <form method="GET" action="{{ route('monitoring.login.index') }}">
                                <select name="entries" onchange="this.form.submit()"
                                    class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6">
                                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                            </form>
                        </div>

                        <!-- Search Box -->
                        <div class="relative w-full sm:w-64 group">
                            <i
                                class="bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                            <form method="GET" action="{{ route('monitoring.login.index') }}">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600"
                                    placeholder="Search username...">
                                @if(request('entries'))
                                    <input type="hidden" name="entries" value="{{ request('entries') }}">
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">ID <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Username <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Name <i class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Email Address <i
                                            class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Level User <i class="bx bx-sort text-[10px]"></i>
                                    </div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                    <div class="flex items-center gap-1">Aux Description <i
                                            class="bx bx-sort text-[10px]"></i></div>
                                </th>
                                <th
                                    class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @forelse($users as $user)
                                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-300">{{ $user->id }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="text-sm font-medium text-white block">{{ $user->username ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-300">{{ $user->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="text-xs text-gray-400">{{ $user->email ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <!-- Level User -->
                                        <span class="text-xs text-gray-400">{{ $user->level ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <!-- Aux Description -->
                                        <span class="text-xs text-gray-400">{{ $user->aux_description ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button title="Action"
                                                class="p-1.5 w-8 h-8 flex items-center justify-center border border-orange-500/30 bg-orange-500/10 rounded-lg text-orange-400 hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                                <i class="bx bx-chevron-right text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="bx bx-folder-open text-4xl mb-1"></i>
                                            <p>No users found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                <div
                    class="px-6 py-4 border-t border-gray-700/50 flex flex-col sm:flex-row justify-between items-center bg-gray-800/30 gap-4">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        Showing <span class="text-white">{{ $users->firstItem() ?? 0 }}</span> - <span
                            class="text-white">{{ $users->lastItem() ?? 0 }}</span> of <span
                            class="text-blue-400">{{ $users->total() }}</span> entries
                    </div>
                    <div>
                        @if($users->hasPages())
                            {{ $users->appends(request()->query())->links('pages.taskboard.pagination') }}
                        @else
                            {{-- Static Pagination for Empty State --}}
                            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center lg:justify-end">
                                    <div>
                                        <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                            <span aria-disabled="true" aria-label="&laquo; Previous">
                                                <span
                                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-gray-800 border border-gray-600 cursor-default rounded-l-md leading-5">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span aria-current="page">
                                                <span
                                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-gray-600 cursor-default leading-5">
                                                    1
                                                </span>
                                            </span>
                                            <span aria-disabled="true" aria-label="Next &raquo;">
                                                <span
                                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-gray-800 border border-gray-600 cursor-default rounded-r-md leading-5">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </nav>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>


    <style>
        .card-glow {
            @apply absolute -right-8 -bottom-8 w-32 h-32 rounded-full blur-3xl opacity-0 transition-opacity duration-500;
        }

        .group:hover .card-glow {
            @apply opacity-100;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

@endsection