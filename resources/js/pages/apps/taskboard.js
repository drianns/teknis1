// =====================================================
// Taskboard Page Module
// Extracted from: pages/apps/taskboard/index.blade.php
// Reads config from: data-endpoint and data-status attrs
// on #taskboard-config element.
// =====================================================

document.addEventListener('DOMContentLoaded', () => {
    const config = document.getElementById('taskboard-config');
    if (!config) return;

    const endpointUrl  = config.dataset.endpoint;
    const initialStatus = config.dataset.status || '';

    let searchTimeout = null;

    const state = {
        page: 1,
        perPage: 10,
        search: '',
        status: initialStatus
    };

    const els = {
        tbody:        document.getElementById('taskboardTableBody'),
        tableInfo:    document.getElementById('tableInfo'),
        pagination:   document.getElementById('paginationLinks'),
        searchInput:  document.getElementById('searchInput'),
        perPageSelect:document.getElementById('perPageSelect')
    };

    const journeyBaseUrl = config.dataset.journeyUrl || '/journey';

    // ── AJAX fetch ────────────────────────────────────
    function loadTableData() {
        els.tbody.innerHTML = `<tr><td colspan="11" class="py-12 text-center text-gray-400 group/row"><div class="flex flex-col items-center justify-center gap-2 animate-pulse"><i class="bx bx-loader-alt bx-spin text-4xl mb-1 text-blue-500"></i><p>Loading records...</p></div></td></tr>`;

        const queryParams = new URLSearchParams({
            page:     state.page,
            per_page: state.perPage,
            search:   state.search,
            status:   state.status
        });

        fetch(`${endpointUrl}?${queryParams.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Error fetching taskboard data:', err);
                els.tbody.innerHTML = `<tr><td colspan="11" class="py-12 text-center text-red-500"><div class="flex flex-col items-center justify-center gap-2"><i class="bx bx-error text-4xl mb-1"></i><p>Error loading data</p></div></td></tr>`;
            });
    }

    // ── Render table rows ──────────────────────────────
    function renderTable(items) {
        if (!items || items.length === 0) {
            els.tbody.innerHTML = `<tr>
                <td colspan="11" class="py-20 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center gap-4 animate-fade-in">
                        <div class="w-20 h-20 bg-gray-700/30 rounded-full flex items-center justify-center border border-gray-600/50 shadow-inner">
                            <i class="bx bx-folder-open text-5xl text-gray-500"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xl font-bold text-white">Data tidak ada</h3>
                            <p class="text-sm text-gray-500">Belum ada tiket yang sesuai dengan filter ini.</p>
                        </div>
                    </div>
                </td>
            </tr>`;
            return;
        }

        const pillStyles = {
            'open':        'bg-blue-500 text-white',
            'pending':     'bg-yellow-500 text-white',
            'in_progress': 'bg-teal-500 text-white',
            'process':     'bg-teal-500 text-white',
            'resolved':    'bg-green-500 text-white',
            'closed':      'bg-gray-500 text-white',
        };

        els.tbody.innerHTML = items.map(ticket => {
            const ticketNumber = ticket.ticket_number || '-';

            let name = '-';
            if (ticket.chat_ticket_user?.name) {
                name = ticket.chat_ticket_user.name;
            } else if (ticket.extra_data?.name) {
                name = ticket.extra_data.name;
            }

            let category = '-';
            if (typeof ticket.category === 'string') {
                category = ticket.category;
            } else if (ticket.category?.nama_kategori) {
                category = ticket.category.nama_kategori;
            }

            const sla       = ticket.sla || '-';
            const noteSla   = (ticket.note_sla || '-').replace(' Days ', '<br>Days ');
            const position  = ticket.ticket_position ? 'Layer ' + ticket.ticket_position : 'Layer 1';

            let agent = '-';
            if (ticket.user_agent) {
                agent = ticket.user_agent.full_name
                    || (ticket.user_agent.user ? ticket.user_agent.user.name : ticket.user_agent.username || '-');
            }

            const department = ticket.department || '-';
            const statusVal  = ticket.status || 'closed';
            const style      = pillStyles[statusVal.toLowerCase()] || 'bg-gray-500 text-white';

            const ticketDateObj = ticket.created_at ? new Date(ticket.created_at) : new Date();
            const dt1 = ticketDateObj.toLocaleDateString('en-US');
            const dt2 = ticketDateObj.toLocaleTimeString('en-US');

            const journeyUrl = `${journeyBaseUrl}?ticket_number=${encodeURIComponent(ticketNumber)}&name=${encodeURIComponent(name)}&category=${encodeURIComponent(category)}&agent=${encodeURIComponent(agent)}&posisi=${encodeURIComponent(position)}&status=${encodeURIComponent(statusVal)}&date=${encodeURIComponent(ticket.created_at || '')}`;

            return `<tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-300">${ticketNumber}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-sm font-medium text-white max-w-[150px] truncate block" title="${name}">${name}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-xs text-gray-400">${category}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-xs text-gray-400">${sla}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-[10px] font-bold text-gray-400 block leading-tight">${noteSla}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-xs text-gray-400">${position}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-xs text-gray-300">${agent}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="text-xs text-gray-400">${department}</span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold ${style} tracking-wide">
                        ${statusVal.charAt(0).toUpperCase() + statusVal.slice(1)}
                    </span>
                </td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-300">${dt1}</span>
                        <span class="text-[10px] font-medium text-gray-500">${dt2}</span>
                    </div>
                </td>
                <td class="px-3 py-3 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-2" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" @click.outside="open = false"
                                class="p-1.5 bg-gray-900 border border-gray-700/50 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all">
                                <i class="bx bx-dots-vertical-rounded text-base"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50 p-1.5 overflow-hidden"
                                style="display: none;">
                                <button
                                    @click="orderModalOpen = true; currentTicket = '${ticketNumber}'; open = false"
                                    class="w-full text-left flex items-center gap-3 px-3 py-2 text-xs font-semibold text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors">
                                    <i class="bx bx-hash text-sm"></i> Order ID
                                </button>
                                <div class="h-px bg-gray-700 my-1"></div>
                                <a href="${journeyUrl}"
                                    class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors">
                                    <i class="bx bx-right-arrow-circle text-sm"></i> Follow Up
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    // ── Render pagination ──────────────────────────────
    function renderPagination(data) {
        const total = data.total || 0;
        const from  = data.from  || 0;
        const to    = data.to    || 0;
        els.tableInfo.innerHTML = `Showing <span class="text-white">${from}</span> - <span class="text-white">${to}</span> of <span class="text-blue-400">${total}</span> tickets`;

        if (!data.links || data.links.length === 0) {
            els.pagination.innerHTML = '';
            return;
        }

        let html = `<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center lg:justify-end">
                <div><span class="relative z-0 inline-flex shadow-sm rounded-md">`;

        data.links.forEach((link, index) => {
            let label = link.label;
            if (label.includes('Previous')) label = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>`;
            if (label.includes('Next'))     label = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>`;

            let className = "relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-gray-800 border border-gray-600 cursor-pointer leading-5 hover:text-white hover:bg-gray-700 transition-colors";
            if (index === 0)                    className += ' rounded-l-md px-2';
            if (index === data.links.length - 1) className += ' rounded-r-md px-2';
            if (link.active) {
                className = "relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-gray-600 cursor-default leading-5";
            }

            if (link.url) {
                const parsedUrl = new URL(link.url);
                const pageNum   = parsedUrl.searchParams.get('page');
                html += `<span aria-current="${link.active ? 'page' : 'false'}">
                    <button type="button" class="${className}" data-page="${pageNum}">${label}</button>
                </span>`;
            } else {
                html += `<span aria-disabled="true">
                    <span class="${className} opacity-50 cursor-not-allowed">${label}</span>
                </span>`;
            }
        });

        html += `</span></div></div></nav>`;
        els.pagination.innerHTML = html;

        els.pagination.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                state.page = parseInt(btn.getAttribute('data-page'));
                loadTableData();
            });
        });
    }

    // ── Event listeners ────────────────────────────────
    els.searchInput.addEventListener('input', e => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            state.search = e.target.value;
            state.page   = 1;
            loadTableData();
        }, 300);
    });

    els.perPageSelect.addEventListener('change', e => {
        state.perPage = e.target.value;
        state.page    = 1;
        loadTableData();
    });

    // Initial load
    loadTableData();
});

