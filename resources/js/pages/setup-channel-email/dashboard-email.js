// =====================================================
// Dashboard Email Page Module
// Extracted from: pages/setup-channel-email/dashboard-email/index.blade.php
// Reads config from: #dashboard-email-config data-* attrs
// =====================================================

window.dashboardEmail = function () {
    return {
        agentSummary: [],
        queueing: [],
        
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

            const dObj = document.getElementById('current-date');
            if (dObj) dObj.textContent = dateStr;
            const dayObj = document.getElementById('current-day');
            if (dayObj) dayObj.textContent = dayStr;
            const tObj = document.getElementById('current-time');
            if (tObj) tObj.textContent = timeStr;
        },

        applyFilters() {
            const config = document.getElementById('dashboard-email-config');
            if (!config) return;

            const endpointUrl = config.dataset.endpoint;
            const csrfToken   = config.dataset.csrf;

            const startDate = document.getElementById('start-date')?.value || '';
            const endDate   = document.getElementById('end-date')?.value || '';
            const emailAcc  = document.getElementById('email-account')?.value || '';

            fetch(endpointUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ start_date: startDate, end_date: endDate, email_account: emailAcc })
            })
                .then(res => res.json())
                .then(data => {
                    this.updateStats(data.statistics || { received: 0, response: 0, not_response: 0, queueing: 0 });
                    this.agentSummary = data.agent_summary || [];
                    this.queueing = data.queueing || [];
                    this.updateAgentSummary(this.agentSummary);
                    this.updateQueueing(this.queueing);
                })
                .catch(err => console.error('Error fetching dashboard data:', err));
        },

        updateStats(stats) {
            const animateValue = (id, target) => {
                const el = document.getElementById(id);
                if (!el) return;
                let current = 0;
                const step = Math.ceil(target / 20);
                if (target === 0) { el.textContent = 0; return; }
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
            if (!tbody) return;
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
            if (!tbody) return;
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
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        },

        formatTime(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
        }
    }
};

