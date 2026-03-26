@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 w-full relative">
        <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1 overflow-y-auto custom-scrollbar">

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
            <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">

                <!-- Table Controls -->
                <div class="px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-col lg:flex-row justify-between items-center gap-4 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bx-list-ul text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Data Users</h2>
                            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">List of user login statuses</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <select id="perPage" class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6">
                                <option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option>
                            </select>
                        </div>

                        <div class="relative w-full sm:w-64 group">
                            <i class="bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                            <input type="text" id="searchInput" class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <div class="overflow-auto flex-1 custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50 sticky top-0 z-10">
                            <tr>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Username</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Name</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Email Address</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Level User</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Aux Description</th>
                                <th class="px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-gray-700/50">
                            <tr><td colspan="7" class="py-12 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-700/50 flex flex-col sm:flex-row justify-between items-center bg-gray-800/30 gap-4 shrink-0">
                    <div id="paginationInfo" class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Loading...</div>
                    <div id="paginationLinks" class="flex gap-1"></div>
                </div>

            </div>
    </div>
    </div>

    <div id="monitoring-login-config" class="hidden" data-endpoint="{{ route('monitoring.login.getData') }}"></div>
@push('scripts')
    @vite('resources/js/pages/data-login/monitoring-login.js')
@endpush
@endsection
