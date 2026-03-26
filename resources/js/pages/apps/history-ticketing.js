// =====================================================
// History Ticketing Page Module
// Extracted from: pages/apps/history-ticketing/index.blade.php
// Reads config from: #history-ticketing-config data-* attrs
// =====================================================

// ── Date Range Bar helpers ─────────────────────────
function toggleDateFilter() {
    const popup = document.getElementById('dateFilterPopup');
    if (popup) popup.classList.toggle('hidden');
}
window.toggleDateFilter = toggleDateFilter;

function closeDateFilter() {
    const popup = document.getElementById('dateFilterPopup');
    if (popup) popup.classList.add('hidden');
}
window.closeDateFilter = closeDateFilter;

function formatDisplayDate(dateStr) {
    if (!dateStr) return 'Select Date';
    const d      = new Date(dateStr);
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

function updateBarLabels() {
    const startDate = document.getElementById('startDate')?.value;
    const endDate   = document.getElementById('endDate')?.value;
    const barStart  = document.getElementById('barStartDate');
    const barEnd    = document.getElementById('barEndDate');
    if (barStart) barStart.innerText = formatDisplayDate(startDate);
    if (barEnd)   barEnd.innerText   = formatDisplayDate(endDate);
}

function setPreset(type) {
    const startInput   = document.getElementById('startDate');
    const endInput     = document.getElementById('endDate');
    const today        = new Date();
    const fmtDate      = d => d.toISOString().split('T')[0];
    let start, end;

    switch (type) {
        case 'today':
            start = end = today; break;
        case 'yesterday':
            const y = new Date(today); y.setDate(today.getDate() - 1);
            start = end = y; break;
        case 'last7days':
            const l7 = new Date(today); l7.setDate(today.getDate() - 7);
            start = l7; end = today; break;
        case 'thismonth':
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            end   = today; break;
    }
    if (start && end) {
        startInput.value = fmtDate(start);
        endInput.value   = fmtDate(end);
        updateBarLabels();
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.innerText.toLowerCase().replace(/\s/g, '') === type) {
                btn.classList.add('active');
            }
        });
    }
}
window.setPreset = setPreset;

function applyDateFilter() {
    updateBarLabels();
    closeDateFilter();
    loadTableData(1);  // Reload table with new dates
}
window.applyDateFilter = applyDateFilter;

// ── Status colour helper ───────────────────────────
function getStatusColor(status) {
    if (!status) return 'bg-gray-500 text-white';
    const s = status.toLowerCase();
    if (s === 'open')        return 'bg-blue-500 text-white';
    if (s === 'pending')     return 'bg-yellow-500 text-white';
    if (s === 'in progress') return 'bg-teal-500 text-white';
    if (s === 'resolved')    return 'bg-green-500 text-white';
    if (s === 'closed')      return 'bg-gray-500 text-white';
    return 'bg-gray-500 text-white';
}

// ── AJAX table ─────────────────────────────────────
let currentPage  = 1;
let debounceTimer;

function loadTableData(page = 1) {
    const config    = document.getElementById('history-ticketing-config');
    const endpoint  = config?.dataset.endpoint || '';

    currentPage     = page;
    const search    = document.getElementById('searchInput')?.value || '';
    const perPage   = document.getElementById('perPageSelect')?.value || 10;
    const tbody     = document.getElementById('dataTableBody');
    const startDate = document.getElementById('startDate')?.value || '';
    const endDate   = document.getElementById('endDate')?.value || '';

    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading data...</p></td></tr>`;

    const url = `${endpoint}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}&start_date=${startDate}&end_date=${endDate}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            renderHistoryTable(data);
            renderHistoryPagination(data);
        })
        .catch(err => {
            console.error('Error fetching history data:', err);
            tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
        });
}
window.loadTableData = loadTableData;

function renderHistoryTable(data) {
    const tbody = document.getElementById('dataTableBody');
    tbody.innerHTML = '';

    if (!data.data || data.data.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                    <p>No history available</p>
                </div>
            </td>
        </tr>`;
        return;
    }

    data.data.forEach(item => {
        const tr    = document.createElement('tr');
        tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-900/40';

        const name  = item.chat_ticket_user ? item.chat_ticket_user.name : (item.name || '-');
        const tno   = item.ticket_number || '-';
        const cat   = item.category || '-';
        const ag    = item.agent || '-';
        const pos   = item.posisi || '-';
        const st    = item.status || '-';
        const sl    = item.sla || '-';
        const dt    = item.created_at ? new Date(item.created_at).toLocaleString() : '-';

        tr.innerHTML = `
            <td class="px-3 py-3 text-cyan-400 max-w-[140px]">
                <div class="truncate" title="${tno}">${tno}</div>
            </td>
            <td class="px-3 py-3 text-white font-medium max-w-[120px]">
                <div class="truncate" title="${name}">${name}</div>
            </td>
            <td class="px-3 py-3 max-w-[100px]">
                <div class="truncate" title="${cat}">${cat}</div>
            </td>
            <td class="px-3 py-3 max-w-[100px]">
                <div class="truncate" title="${ag}">${ag}</div>
            </td>
            <td class="px-3 py-3 max-w-[100px]">
                <div class="truncate" title="${pos}">${pos}</div>
            </td>
            <td class="px-3 py-3 whitespace-nowrap">${sl}</td>
            <td class="px-3 py-3 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${getStatusColor(st)}">${st}</span>
            </td>
            <td class="px-3 py-3 text-gray-500 text-xs whitespace-nowrap">${dt}</td>
            <td class="px-3 py-3 text-center whitespace-nowrap">
                <div class="flex items-center justify-center gap-2 relative z-10" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                        <i class="bx bx-dots-vertical-rounded text-base"></i>
                    </button>
                    <div x-show="open" style="display: none;"
                        class="absolute right-0 top-10 w-40 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden">
                        <button @click="open = false; $dispatch('open-order-modal', { ticket: '${tno}' })"
                            class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors">
                            <i class="bx bx-hash text-sm"></i> Order ID
                        </button>
                        <div class="h-px bg-gray-700 my-1"></div>
                        <a href="/journey?ticket_number=${tno}&name=${encodeURIComponent(name)}&category=${encodeURIComponent(cat)}&agent=${encodeURIComponent(ag)}&posisi=${encodeURIComponent(pos)}&status=${encodeURIComponent(st)}&date=${encodeURIComponent(dt)}"
                            class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                            <i class="bx bx-right-arrow-circle text-sm"></i> Follow Up
                        </a>
                    </div>
                </div>
            </td>`;
        tbody.appendChild(tr);
    });

    // Re-initialise Alpine for newly injected x-data elements
    if (window.Alpine) window.Alpine.initTree(tbody);
}

function renderHistoryPagination(data) {
    const info      = document.getElementById('dataTableInfo');
    const container = document.getElementById('paginationContainer');

    info.innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} entries`;

    if (data.last_page <= 1) { container.innerHTML = ''; return; }

    let html = '<div class="flex gap-1">';
    html += `<button onclick="loadTableData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Previous</button>`;

    for (let i = 1; i <= data.last_page; i++) {
        if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
            html += i === data.current_page
                ? `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg">${i}</button>`
                : `<button onclick="loadTableData(${i})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">${i}</button>`;
        } else if (i === data.current_page - 3 || i === data.current_page + 3) {
            html += `<span class="px-2 py-1 text-gray-500">...</span>`;
        }
    }

    html += `<button onclick="loadTableData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Next</button>`;
    html += '</div>';
    container.innerHTML = html;
}

// ── Bootstrap on DOM ready ─────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Initialise date inputs to today
    const today      = new Date().toISOString().split('T')[0];
    const startInput = document.getElementById('startDate');
    const endInput   = document.getElementById('endDate');
    if (startInput && !startInput.value) startInput.value = today;
    if (endInput   && !endInput.value)   endInput.value   = today;
    updateBarLabels();

    // Search debounce
    document.getElementById('searchInput')?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => { currentPage = 1; loadTableData(1); }, 300);
    });

    // Per-page change
    document.getElementById('perPageSelect')?.addEventListener('change', () => {
        currentPage = 1; loadTableData(1);
    });

    // Close popup on outside click
    document.addEventListener('click', event => {
        const popup = document.getElementById('dateFilterPopup');
        const btn   = document.getElementById('dateFilterBtn');
        if (popup && btn && !popup.contains(event.target) && !btn.contains(event.target)) {
            popup.classList.add('hidden');
        }
    });

    // Open order modal via custom Alpine event
    document.addEventListener('open-order-modal', e => {
        const root = document.querySelector('[x-data]');
        if (root && root._x_dataStack) {
            root._x_dataStack[0].currentTicket  = e.detail.ticket;
            root._x_dataStack[0].orderModalOpen = true;
        }
    });

    loadTableData(1);
});

