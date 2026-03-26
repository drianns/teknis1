// =====================================================
// Monitoring Login Page Module
// Extracted from: pages/data-login/monitoring-login/index.blade.php
// Reads config from: #monitoring-login-config
// =====================================================

document.addEventListener('DOMContentLoaded', () => {
    const config = document.getElementById('monitoring-login-config');
    if (!config) return;
    
    const AJAX_URL = config.dataset.endpoint;
    let searchTimer = null;
    let currentPage = 1;

    window.loadTable = function(page = 1) {
        currentPage = page;
        const searchInput = document.getElementById('searchInput');
        const perPageInput = document.getElementById('perPage');
        const tableBody = document.getElementById('tableBody');
        
        const s = searchInput ? searchInput.value : '';
        const p = perPageInput ? perPageInput.value : '10';
        
        fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(s)}&per_page=${p}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            renderTable(d);
            renderPagination(d);
        })
        .catch(() => {
            if (tableBody) tableBody.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-red-400 font-bold">Failed to load data.</td></tr>';
        });
    };

    function renderTable(d) {
        const b = document.getElementById('tableBody');
        if(!b) return;

        if(!d.data || !d.data.length) {
            b.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-gray-500"><div class="flex flex-col items-center justify-center gap-2"><i class="bx bx-folder-open text-4xl mb-1"></i><p>No users found</p></div></td></tr>';
            return;
        }
        b.innerHTML = d.data.map(i => `
            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-sm font-medium text-gray-300">${i.id}</span></td>
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-sm font-medium text-white block">${escHtml(i.username)}</span></td>
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-sm text-gray-300">${escHtml(i.name)}</span></td>
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-xs text-gray-400">${escHtml(i.email)}</span></td>
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-xs text-gray-400">${escHtml(i.level || '-')}</span></td>
                <td class="px-3 py-3 whitespace-nowrap"><span class="text-xs text-gray-400">${escHtml(i.aux_description || '-')}</span></td>
                <td class="px-3 py-3 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center gap-2">
                        <button title="Action" class="p-1.5 w-8 h-8 flex items-center justify-center border border-orange-500/30 bg-orange-500/10 rounded-lg text-orange-400 hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                            <i class="bx bx-chevron-right text-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>`).join('');
    }

    function renderPagination(d) {
        const info = document.getElementById('paginationInfo');
        const l = document.getElementById('paginationLinks');
        
        if (info) info.innerHTML = `Showing <span class="text-white">${d.from ?? 0}</span> - <span class="text-white">${d.to ?? 0}</span> of <span class="text-blue-400">${d.total}</span> entries`;
        if (l) {
            l.innerHTML = '';
            
            const btn = (lb, pg, dis, act) => {
                const b = document.createElement('button');
                b.innerHTML = lb;
                b.disabled = dis;
                b.className = `px-3 py-1 rounded-lg text-xs font-bold transition-all ${act ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white border border-gray-700'} ${dis ? 'opacity-40 cursor-not-allowed' : ''}`;
                if(!dis) b.onclick = () => window.loadTable(pg);
                return b;
            };

            l.appendChild(btn('<i class="bx bx-chevron-left"></i>', d.current_page - 1, d.current_page <= 1, false));
            for(let i = Math.max(1, d.current_page - 1); i <= Math.min(d.last_page, d.current_page + 1); i++) {
                l.appendChild(btn(i, i, false, i === d.current_page));
            }
            l.appendChild(btn('<i class="bx bx-chevron-right"></i>', d.current_page + 1, d.current_page >= d.last_page, false));
        }
    }

    function escHtml(s) { 
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); 
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => window.loadTable(1), 400);
        });
    }

    const perPageInput = document.getElementById('perPage');
    if (perPageInput) {
        perPageInput.addEventListener('change', () => window.loadTable(1));
    }

    // Initial load
    window.loadTable();
});

