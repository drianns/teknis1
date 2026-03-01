<x-dashonic-horizontal-layout sidebar="1" with-sidebar="{{ request()->get('with-sidebar') ?? 1 }}"
    with-header="{{ request()->get('with-header') ?? 1 }}" with-footer="{{ request()->get('with-footer') ?? 0 }}">

    <div class="p-6 space-y-8 bg-gray-900 min-h-screen text-gray-200" x-data="dashboardEmail()">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Dashboard Data Incoming Email</h1>
                <p class="text-gray-400 mt-1">Analytics and monitoring overview</p>
            </div>
            <div class="flex items-center gap-3 bg-gray-800/50 px-4 py-2.5 rounded-xl border border-gray-700 shadow-sm">
                <span id="current-date" class="text-gray-300 font-medium">12 Februari 2026</span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                <span id="current-day" class="text-gray-300 font-medium">Kamis</span>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                <span id="current-time" class="text-blue-400 font-bold tracking-wider">9:48 WIB</span>
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
                    <span class="text-xs text-gray-500">Showing <span class="text-gray-300 font-medium">1-5</span> of 24
                        agents</span>
                    <div class="flex gap-1">
                        <button
                            class="p-1 px-3 rounded hover:bg-gray-700 text-gray-400 disabled:opacity-30 transition-colors text-xs">Prev</button>
                        <button class="p-1 px-3 rounded bg-blue-600 text-white font-medium text-xs">1</button>
                        <button class="p-1 px-3 rounded hover:bg-gray-700 text-gray-400 text-xs">2</button>
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
                    <span class="text-xs text-gray-500">Showing <span class="text-gray-300 font-medium">1-4</span> of 12
                        items</span>
                    <button
                        class="text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors uppercase tracking-wider">View
                        Full Table</button>
                </div>
            </div>
        </div>

        <!-- Custom Styling -->
        <style>
            .card-glow {
                @apply absolute -right-4 -bottom-4 w-24 h-24 rounded-full blur-3xl opacity-0 transition-opacity duration-300;
            }

            .group:hover .card-glow {
                @apply opacity-100;
            }

            .submenu-active {
                @apply text-blue-400 font-bold bg-blue-500/10;
            }
        </style>

        <!-- Scripts -->
        <script>
            function dashboardEmail() {
                return {
                    init() {
                        this.updateDateTime();
                        setInterval(() => this.updateDateTime(), 1000);
                        this.applyFilters();
                    },

                    updateDateTime() {
                        const now = new Date();
                        const options = { day: 'numeric', month: 'long', year: 'numeric' };
                        const dateStr = now.toLocaleDateString('id-ID', options);
                        const dayStr = now.toLocaleDateString('id-ID', { weekday: 'long' });
                        const timeStr = now.toLocaleTimeString('id-ID', {
                            hour: '2-digit', minute: '2-digit', hour12: false
                        }) + ' WIB';

                        document.getElementById('current-date').textContent = dateStr;
                        document.getElementById('current-day').textContent = dayStr;
                        document.getElementById('current-time').textContent = timeStr;
                    },

                    applyFilters() {
                        const startDate = document.getElementById('start-date').value;
                        const endDate = document.getElementById('end-date').value;
                        const emailAccount = document.getElementById('email-account').value;

                        fetch('{{ route("dashboard.email.data") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ start_date: startDate, end_date: endDate, email_account: emailAccount })
                        })
                            .then(res => res.json())
                            .then(data => {
                                this.updateStats(data.statistics);
                                this.updateAgentSummary(data.agent_summary);
                                this.updateQueueing(data.queueing);
                            })
                            .catch(err => console.error('Error fetching dashboard data:', err));
                    },

                    updateStats(stats) {
                        const animateValue = (id, target) => {
                            const el = document.getElementById(id);
                            let current = 0;
                            const step = Math.ceil(target / 20);
                            const timer = setInterval(() => {
                                current += step;
                                if (current >= target) {
                                    el.textContent = target;
                                    clearInterval(timer);
                                } else {
                                    el.textContent = current;
                                }
                            }, 30);
                        };

                        animateValue('email-received', stats.received);
                        animateValue('email-response', stats.response);
                        animateValue('email-not-response', stats.not_response);
                        animateValue('email-queueing', stats.queueing);
                    },

                    updateAgentSummary(data) {
                        const tbody = document.getElementById('agent-summary-tbody');
                        if (!data || data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No data available for selected period</td></tr>';
                            return;
                        }

                        tbody.innerHTML = data.map(agent => `
                            <tr class="hover:bg-gray-700/30 transition-colors border-b border-gray-700/50 last:border-none">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-blue-500/20 shadow-lg">
                                            ${agent.name.charAt(0)}
                                        </div>
                                        <span class="text-sm font-medium text-white">${agent.name}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-900 text-blue-400 px-3 py-1 rounded-full text-xs font-bold ring-1 ring-blue-400/20">${agent.received}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-900 text-emerald-400 px-3 py-1 rounded-full text-xs font-bold ring-1 ring-emerald-400/20">${agent.response}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-900 text-rose-400 px-3 py-1 rounded-full text-xs font-bold ring-1 ring-rose-400/20">${agent.not_response}</span>
                                </td>
                            </tr>
                        `).join('');
                    },

                    updateQueueing(data) {
                        const tbody = document.getElementById('queueing-tbody');
                        if (!data || data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No emails in queue</td></tr>';
                            return;
                        }

                        tbody.innerHTML = data.map(email => `
                            <tr class="hover:bg-gray-700/30 transition-colors border-b border-gray-700/50 last:border-none">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-blue-400 font-medium truncate max-w-[120px]" title="${email.service}">${email.service}</span>
                                        <span class="text-[10px] text-gray-500">Auto-routed</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-300 font-medium">${email.from}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-200 font-medium truncate max-w-[150px] inline-block" title="${email.subject}">${email.subject}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col text-right sm:text-left">
                                        <span class="text-xs text-gray-300 font-bold">${this.formatDate(email.date)}</span>
                                        <span class="text-[10px] text-amber-500 font-medium">${this.formatTime(email.date)}</span>
                                    </div>
                                </td>
                            </tr>
                        `).join('');
                    },

                    formatDate(dateStr) {
                        const d = new Date(dateStr);
                        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    },

                    formatTime(dateStr) {
                        const d = new Date(dateStr);
                        return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
                    }
                }
            }
        </script>
    </div>
</x-dashonic-horizontal-layout>