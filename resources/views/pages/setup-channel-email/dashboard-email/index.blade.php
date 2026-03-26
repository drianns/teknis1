@extends('layouts.app')
@section('content')
    <div class="p-6 space-y-8 bg-gray-900 min-h-screen text-gray-200" x-data="dashboardEmail()">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Dashboard Data Incoming Email</h1>
                <p class="text-gray-400 mt-1">Analytics and monitoring overview</p>
            </div>
            <div class="flex items-center gap-3 bg-gray-800/50 px-4 py-2.5 rounded-xl border border-gray-700 shadow-sm">
                <span id="current-date" class="text-gray-300 font-medium">-</span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                <span id="current-day" class="text-gray-300 font-medium">-</span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                <span id="current-time" class="text-blue-400 font-bold tracking-wider">-</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-800/80 backdrop-blur-md p-6 rounded-2xl border border-gray-700 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                <!-- Start Date -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Start Date</label>
                    <div class="relative">
                        <i class="bx bx-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="date" id="start-date"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-10 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all outline-none"
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- End Date -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">End Date</label>
                    <div class="relative">
                        <i class="bx bx-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="date" id="end-date"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-10 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all outline-none"
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Email Account -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Email
                        Account</label>
                    <div class="relative">
                        <i class="bx bx-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <select id="email-account"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-10 py-2.5 text-sm text-white appearance-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all outline-none">
                            <option value="club.indonesia@nespresso.co.id">club.indonesia@nespresso.co.id</option>
                            <option value="support@example.com">support@example.com</option>
                            <option value="info@company.com">info@company.com</option>
                        </select>
                        <i class="bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <button @click="applyFilters()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center justify-center gap-2 group">
                    <i class="bx bx-search-alt-2 text-xl group-hover:rotate-12 transition-transform"></i>
                    <span>Submit</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Email Received -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 border border-blue-500/30 ring-1 ring-white/5 transition-all duration-300 relative overflow-hidden group shadow-lg hover:shadow-2xl hover:-translate-y-1">
                <div class="card-glow bg-blue-500/20"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-500/10 rounded-xl border border-blue-500/20">
                            <i class="bx bx-envelope-open text-2xl text-blue-400"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-sm font-medium">Email Received</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-white tracking-tight" id="email-received">0</span>
                        <span class="text-gray-500 text-xs">Total</span>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-gray-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-[65%] rounded-full shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                </div>
            </div>

            <!-- Card 2: Email Response -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 border border-emerald-500/30 ring-1 ring-white/5 transition-all duration-300 relative overflow-hidden group shadow-lg hover:shadow-2xl hover:-translate-y-1">
                <div class="card-glow bg-emerald-500/20"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                            <i class="bx bx-check-double text-2xl text-emerald-400"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-sm font-medium">Email Response</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-white tracking-tight" id="email-response">0</span>
                        <span class="text-gray-500 text-xs">Total</span>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-gray-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 w-[78%] rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                </div>
            </div>

            <!-- Card 3: Email Not Response -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 border border-rose-500/30 ring-1 ring-white/5 transition-all duration-300 relative overflow-hidden group shadow-lg hover:shadow-2xl hover:-translate-y-1">
                <div class="card-glow bg-rose-500/20"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-rose-500/10 rounded-xl border border-rose-500/20">
                            <i class="bx bx-x-circle text-2xl text-rose-400"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-sm font-medium">Email Not Response</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-white tracking-tight" id="email-not-response">0</span>
                        <span class="text-gray-500 text-xs">Total</span>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-gray-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500 w-[25%] rounded-full shadow-[0_0_8px_rgba(244,63,94,0.5)]"></div>
                </div>
            </div>

            <!-- Card 4: Email Queueing -->
            <div
                class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 border border-amber-500/30 ring-1 ring-white/5 transition-all duration-300 relative overflow-hidden group shadow-lg hover:shadow-2xl hover:-translate-y-1">
                <div class="card-glow bg-amber-500/20"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20">
                            <i class="bx bx-time-five text-2xl text-amber-400"></i>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-sm font-medium">Email Queueing</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-white tracking-tight" id="email-queueing">0</span>
                        <span class="text-gray-500 text-xs">Total</span>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-gray-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 w-[42%] rounded-full shadow-[0_0_8px_rgba(245,158,11,0.5)]"></div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12">
            <!-- Data Summary Email Agent -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div
                    class="px-6 py-5 border-b border-gray-700 bg-gray-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500/10 rounded-lg">
                            <i class="bx bx-user-voice text-xl text-blue-400"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Data Summary Email Agent</h2>
                    </div>
                    <div class="relative w-full sm:w-48">
                        <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="text" placeholder="Search agent..."
                            class="w-full bg-gray-900 border border-gray-700 rounded-full pl-9 pr-4 py-1.5 text-xs text-white focus:ring-1 focus:ring-blue-500/50 outline-none">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Agent
                                    Name</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">
                                    Received</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">
                                    Response</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">
                                    Not Response</th>
                            </tr>
                        </thead>
                        <tbody id="agent-summary-tbody">
                            <tr class="animate-pulse">
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="bx bx-loader-alt bx-spin text-3xl"></i>
                                        <span>Loading agent summary...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-700 flex justify-between items-center bg-gray-800/30">
                    <span class="text-xs text-gray-500" x-show="agentSummary.length > 0">Showing <span class="text-gray-300 font-medium" x-text="`1-${agentSummary.length}`"></span> of <span class="text-gray-300 font-medium" x-text="agentSummary.length"></span> agents</span>
                    <span class="text-xs text-gray-500" x-show="agentSummary.length === 0">No agents to show</span>
                    <div class="flex gap-1" x-show="agentSummary.length > 0">
                        <button
                            class="p-1 px-3 rounded hover:bg-gray-700 text-gray-400 disabled:opacity-30 transition-colors text-xs">Prev</button>
                        <button class="p-1 px-3 rounded bg-blue-600 text-white font-medium text-xs">1</button>
                        <button class="p-1 px-3 rounded hover:bg-gray-700 text-gray-400 text-xs">Next</button>
                    </div>
                </div>
            </div>

            <!-- Data Email Queueing -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-700 bg-gray-800/50 flex items-center gap-3">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <i class="bx bx-list-ul text-xl text-amber-400"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Data Email Queueing</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Services</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">From
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Subject</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date
                                </th>
                            </tr>
                        </thead>
                        <tbody id="queueing-tbody">
                            <tr class="animate-pulse">
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="bx bx-loader-alt bx-spin text-3xl"></i>
                                        <span>Loading queue...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-700 flex justify-between items-center bg-gray-800/30">
                    <span class="text-xs text-gray-500" x-show="queueing.length > 0">Showing <span class="text-gray-300 font-medium" x-text="`1-${queueing.length}`"></span> of <span class="text-gray-300 font-medium" x-text="queueing.length"></span> items</span>
                    <span class="text-xs text-gray-500" x-show="queueing.length === 0">No items in queue</span>
                    <button
                        class="text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors uppercase tracking-wider">View
                        Full Table</button>
                </div>
            </div>
        </div>

        <!-- Scripts -->
@push('scripts')
@push('scripts')
    {{-- Config element for JS --}}
    <div id="dashboard-email-config" class="hidden"
        data-endpoint="{{ route('dashboard.email.data') }}"
        data-csrf="{{ csrf_token() }}"
    ></div>
    @vite('resources/js/pages/setup-channel-email/dashboard-email.js')
@endpush
@endsection
