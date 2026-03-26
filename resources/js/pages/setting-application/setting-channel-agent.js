
        const AJAX_URL = '';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentPage = 1, searchTimer = null;

window.loadTable = function(page = 1) {
            currentPage = page;
            const search = document.getElementById('table-search').value;
            const perPage = document.getElementById('entries-per-page').value;
            const tbody = document.getElementById('agent-table-body');

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Table load error:', err);
                tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-red-500">Failed to load data.</td></tr>';
            });
        }

window.renderTable = function(items) {
            const tbody = document.getElementById('agent-table-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-gray-500">No records found.</td></tr>';
                return;
            }

            tbody.innerHTML = items.map(agent => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                    <td class="px-3 py-3 whitespace-nowrap font-mono text-blue-400 font-medium">${agent.id}</td>
                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">${esc(agent.user ? agent.user.name : '-')}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-300">${esc(agent.sub_menu)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-300">${esc(agent.detail_menu)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">${esc(agent.url)}</td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        ${agent.status === 'Yes'
                            ? '<span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/30">Aktif</span>'
                            : '<span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-semibold border border-red-500/30">Non-Aktif</span>'
                        }
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                        <div class="action-dropdown relative flex justify-center" x-data="{ dropdownOpen: false }">
                            <button @click.stop="dropdownOpen = !dropdownOpen" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center transition-all text-gray-400 hover:text-white">
                                <i class='bx bx-dots-vertical-rounded'></i>
                            </button>
                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" style="display: none;" class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden text-left">
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3 transition-colors border-b border-gray-700/50"
                                    onclick='openEditModal(${JSON.stringify(agent)})'>
                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> <span class="font-medium">Edit</span>
                                </button>
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors"
                                    onclick="deleteAgent(${agent.id})">
                                    <i class='bx bx-trash text-red-500 text-lg'></i> <span class="font-medium">Delete</span>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

window.renderPagination = function(data) {
            document.getElementById('pagination-info').innerHTML = `Showing <span class="text-white font-bold">${data.from || 0}</span> to <span class="text-white font-bold">${data.to || 0}</span> of <span class="text-white font-bold">${data.total}</span> entries`;
            const container = document.getElementById('pagination-links');
            container.innerHTML = '';

            if (data.last_page <= 1) return;

            const nav = document.createElement('nav');
            nav.className = 'flex gap-2';

            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button');
                btn.innerHTML = label;
                btn.disabled = disabled;
                btn.className = `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all ${active ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700/50'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadTable(page);
                return btn;
            };

            nav.appendChild(createBtn('<i class="bx bx-chevron-left text-xl"></i>', data.current_page - 1, data.current_page <= 1));
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    nav.appendChild(createBtn(i, i, false, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'w-10 h-10 flex items-center justify-center text-gray-600';
                    dots.innerText = '...';
                    nav.appendChild(dots);
                }
            }
            nav.appendChild(createBtn('<i class="bx bx-chevron-right text-xl"></i>', data.current_page + 1, data.current_page >= data.last_page));
            container.appendChild(nav);
        }

window.esc = function(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

window.debounceSearch = function(q) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadTable(1), 500);
        }

window.changeEntriesPerPage = function(v) { loadTable(1); }

window.openAddModal = function() { window.dispatchEvent(new CustomEvent('agent-modal')); }
window.openEditModal = function(agent) { window.dispatchEvent(new CustomEvent('agent-modal', { detail: agent })); }

window.deleteAgent = function(id) {
            if (!confirm('Are you sure you want to delete this agent channel?')) return;
            showLoading('Deleting...');
            fetch(`/setting-channel-agent/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.success) { showToast(data.message); loadTable(currentPage); }
                else { showToast(data.message || 'Error', 'error'); }
            }).catch(() => { hideLoading(); showToast('Server error', 'error'); });
        }

window.agentModalData = function() {
            return {
                open: false, isEdit: false,
                formData: { id: null, user_id: '', menu: '', sub_menu: '', detail_menu: '', url: '', status: '' },
                loadData(detail) {
                    if (detail && detail.id) { this.isEdit = true; this.formData = { ...detail }; }
                    else { this.isEdit = false; this.formData = { id: null, user_id: '', menu: '', sub_menu: '', detail_menu: '', url: '', status: '' }; }
                },
                saveAgent() {
                    if (!this.formData.user_id || !this.formData.url || !this.formData.status) {
                        showToast('Please fill all required fields', 'error'); return;
                    }
                    showLoading('Saving...');
                    const url = this.isEdit ? `/setting-channel-agent/${this.formData.id}` : `/setting-channel-agent`;
                    const method = this.isEdit ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify(this.formData)
                    }).then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) { showToast(data.message); this.open = false; loadTable(this.isEdit ? currentPage : 1); }
                        else { showToast(data.message || 'Error', 'error'); }
                    }).catch(() => { hideLoading(); showToast('Server error', 'error'); });
                }
            }
        }

        // Global UI Helpers (To be put into partial eventually)
window.showLoading = function(msg = 'Processing...') {
            let overlay = document.getElementById('global-loader');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'global-loader';
                overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[500] opacity-0 transition-opacity duration-300';
                overlay.innerHTML = `<div class="bg-gray-900 p-8 rounded-2xl border border-gray-800 shadow-2xl flex flex-col items-center">
                    <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-white font-bold dynamic-msg">${msg}</p>
                </div>`;
                document.body.appendChild(overlay);
                setTimeout(() => overlay.style.opacity = '1', 10);
            } else { overlay.querySelector('.dynamic-msg').innerText = msg; }
        }
window.hideLoading = function() {
            const el = document.getElementById('global-loader');
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
        }
window.showToast = function(msg, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 px-6 py-3 rounded-xl border shadow-2xl z-[600] transition-all transform translate-x-full ${type === 'success' ? 'bg-gray-900 border-green-500/50 text-green-400' : 'bg-gray-900 border-red-500/50 text-red-500'}`;
            toast.innerHTML = `<div class="flex items-center gap-3"><i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl'></i><span class="font-semibold text-sm">${msg}</span></div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateX(0)', 10);
            setTimeout(() => { toast.style.transform = 'translateX(full)'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => loadTable(1));
    














