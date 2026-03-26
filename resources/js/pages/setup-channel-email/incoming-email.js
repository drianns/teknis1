// =====================================================
// Incoming Email Page Module
// Extracted from: pages/setup-channel-email/incoming-email/index.blade.php
// Reads config from: #incoming-email-config data-* attrs
// =====================================================

document.addEventListener('DOMContentLoaded', () => {
    let searchTimeout;

    // Expose globals for inline events from Blade
    window.loadTable = function(page = 1) {
        const config = document.getElementById('incoming-email-config');
        if (!config) return;
        
        const ajaxUrl = config.dataset.endpoint;
        const tableBody = document.getElementById('table-body');
        const searchInput = document.getElementById('search-input');
        const perPageInput = document.getElementById('per-page');

        const search = searchInput ? searchInput.value : '';
        const perPage = perPageInput ? perPageInput.value : '10';

        fetch(`${ajaxUrl}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(err => {
            console.error(err);
            if(tableBody) tableBody.innerHTML = '<tr><td colspan="7" class="px-4 py-12 text-center text-red-400 font-bold italic">Error loading data.</td></tr>';
        });
    };

    window.debounceSearch = function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => window.loadTable(1), 500);
    };

    function renderTable(rows) {
        const tableBody = document.getElementById('table-body');
        if (!tableBody) return;

        if (!rows || rows.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="px-4 py-12 text-center text-gray-500"><div class="flex flex-col items-center gap-2"><i class="bx bx-folder-open text-4xl opacity-20"></i><p>No incoming email data found</p></div></td></tr>';
            return;
        }

        tableBody.innerHTML = rows.map(row => {
            const user = row.chat_ticket_user || {};
            const date = row.created_at ? new Date(row.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-';
            
            return `
            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                <td class="px-4 py-3 font-mono text-blue-400 font-medium text-center">${row.id}</td>
                <td class="px-4 py-3 font-medium text-white">${esc(user.name || '-')}</td>
                <td class="px-4 py-3 text-gray-400">${esc(user.email || '-')}</td>
                <td class="px-4 py-3 text-gray-300 font-semibold truncate max-w-[200px]" title="${esc(row.subject)}">${esc(row.subject || '-')}</td>
                <td class="px-4 py-3 text-gray-400 text-xs">${date}</td>
                <td class="px-4 py-3 text-center">
                    <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-500/30 whitespace-nowrap uppercase tracking-wider">
                        ${esc(row.status || 'inbound')}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="relative flex justify-center" x-data="{ open: false }">
                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all shadow-sm">
                            <i class='bx bx-dots-vertical-rounded text-lg'></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 top-full mt-2 w-32 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5" style="display:none;">
                            <button class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 transition-colors"><i class='bx bx-edit-alt text-lg'></i><span class="font-bold">Edit</span></button>
                        </div>
                    </div>
                </td>
            </tr>
            `;
        }).join('');

        // Re-inject Alpine instances if needed
        if (window.Alpine) {
            window.Alpine.initTree(tableBody);
        }
    }

    function renderPagination(data) {
        const info = document.getElementById('pagination-info');
        if (info) info.innerHTML = `Showing <span class="text-white">${data.from || 0}</span> to <span class="text-white">${data.to || 0}</span> of <span class="text-blue-500">${data.total}</span> entries`;
        
        const container = document.getElementById('pagination-links');
        if (!container) return;
        container.innerHTML = '';

        if (data.last_page <= 1) return;

        const btn = (label, page, active = false, disabled = false) => {
            const b = document.createElement('button');
            b.innerHTML = label;
            b.disabled = disabled;
            b.className = `w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 hover:text-white'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
            if (!disabled && !active) b.onclick = () => window.loadTable(page);
            return b;
        };

        container.appendChild(btn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, false, data.current_page === 1));

        for (let i = 1; i <= data.last_page; i++) {
            if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                container.appendChild(btn(i, i, i === data.current_page));
            } else if (i === 2 || i === data.last_page - 1) {
                const dots = document.createElement('span');
                dots.className = "px-1 text-gray-600 font-bold";
                dots.innerText = "...";
                container.appendChild(dots);
            }
        }

        container.appendChild(btn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, false, data.current_page === data.last_page));
    }

    function esc(s) {
        return String(s || '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
    }

    // Initial load
    window.loadTable(1);
});

