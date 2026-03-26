
        const AJAX_URL = '';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentPage = 1, searchTimer = null;

window.loadTable = function(page = 1) {
            currentPage = page;
            const search = document.getElementById('table-search').value;
            const perPage = document.getElementById('entries-per-page').value;

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Load error:', err);
                document.getElementById('table-body').innerHTML = '<tr><td colspan="6" class="p-12 text-center text-red-500">Failed to load data.</td></tr>';
            });
        }

window.renderTable = function(items) {
            const tbody = document.getElementById('table-body');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-12 text-center text-gray-500">No sub menus found.</td></tr>';
                return;
            }

            tbody.innerHTML = items.map(item => `
                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                    <td class="px-3 py-3 whitespace-nowrap font-mono text-gray-300 font-medium">${item.id}</td>
                    <td class="px-3 py-3 whitespace-nowrap font-medium text-white">${esc(item.menu_name)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400">${esc(item.sub_menu_name)}</td>
                    <td class="px-3 py-3 whitespace-nowrap text-gray-400 truncate">${esc(item.url)}</td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="${item.type === 'Yes' ? 'bg-teal-500' : 'bg-red-500'} text-white px-3 py-1 rounded-full text-xs shadow-sm">
                            ${item.type}
                        </span>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                        <div class="action-dropdown relative flex justify-center" x-data="{ dropdownOpen: false }">
                            <button @click.stop="dropdownOpen = !dropdownOpen" class="w-8 h-8 rounded-lg hover:bg-gray-800 flex items-center justify-center transition-all text-blue-400">
                                <i class='bx bx-dots-vertical-rounded text-xl'></i>
                            </button>
                            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-transition class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden text-left" style="display: none;">
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3 transition-colors border-b border-gray-700/50" onclick="editSubMenu(${item.id})">
                                    <i class='bx bx-edit-alt text-blue-400 text-lg'></i> Edit
                                </button>
                                <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 flex items-center gap-3 transition-colors" onclick="deleteSubMenu(${item.id})">
                                    <i class='bx bx-trash text-red-500 text-lg'></i> Delete
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
            
            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button');
                btn.innerHTML = label;
                btn.disabled = disabled;
                btn.className = `px-3 py-1 rounded-lg text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadTable(page);
                return btn;
            };

            container.appendChild(createBtn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, data.current_page <= 1, false));
            for (let i = Math.max(1, data.current_page - 1); i <= Math.min(data.last_page, data.current_page + 1); i++) {
                container.appendChild(createBtn(i, i, false, i === data.current_page));
            }
            container.appendChild(createBtn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, data.current_page >= data.last_page, false));
        }

window.esc = function(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

window.debounceSearch = function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadTable(1), 500);
        }

window.changeEntriesPerPage = function() {
            loadTable(1);
        }

        // Global triggers
window.openAddSubMenuModal = function() {
            window.dispatchEvent(new CustomEvent('sub-menu-modal'));
        }

window.editSubMenu = function(id) {
            window.dispatchEvent(new CustomEvent('sub-menu-modal', { detail: { subMenuId: id } }));
        }

window.deleteSubMenu = function(id) {
            if (!confirm('Are you sure?')) return;
            showLoading('Deleting...');
            fetch(`/sub-menu-application/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast(data.message);
                    loadTable(currentPage);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => { hideLoading(); showToast('Error deleting', 'error'); });
        }

        // Alpine JS Component Scope
window.subMenuModalData = function() {
            return {
                open: false, isEdit: false,
                formData: { id: null, menuName: '', subMenuName: '', url: '', type: '' },
                resetForm() {
                    this.isEdit = false;
                    this.formData = { id: null, menuName: '', subMenuName: '', url: '', type: '' };
                },
                loadSubMenuData(detail) {
                    if (detail && detail.subMenuId) {
                        this.isEdit = true;
                        showLoading('Loading details...');
                        fetch(`/sub-menu-application/${detail.subMenuId}`, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(data => {
                            hideLoading();
                            this.formData = { id: data.id, menuName: data.menu_name, subMenuName: data.sub_menu_name, url: data.url || '', type: data.type };
                        })
                        .catch(() => { hideLoading(); showToast('Failed to load', 'error'); this.open = false; });
                    } else { this.resetForm(); }
                },
                saveSubMenu() {
                    if (!this.formData.menuName || !this.formData.subMenuName || !this.formData.type) {
                        showToast('Fill required fields', 'error'); return;
                    }
                    const url = this.isEdit ? `/sub-menu-application/${this.formData.id}` : '/sub-menu-application/store';
                    const method = this.isEdit ? 'PUT' : 'POST';
                    showLoading('Saving...');
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ menu_name: this.formData.menuName, sub_menu_name: this.formData.subMenuName, url: this.formData.url, type: this.formData.type })
                    })
                    .then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showToast(data.message);
                            this.open = false;
                            loadTable(this.isEdit ? currentPage : 1);
                        } else { showToast(data.message, 'error'); }
                    })
                    .catch(() => { hideLoading(); showToast('Error saving', 'error'); });
                }
            };
        }

        // UI Helpers
window.showLoading = function(msg = 'Processing...') {
            let overlay = document.getElementById('global-loader');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'global-loader';
                overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[200] opacity-0 transition-opacity duration-300';
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
            toast.className = `fixed top-6 right-6 px-6 py-3 rounded-xl border shadow-2xl z-[300] transition-all transform translate-x-full ${type === 'success' ? 'bg-gray-900 border-green-500/50 text-green-400' : 'bg-gray-900 border-red-500/50 text-red-500'}`;
            toast.innerHTML = `<div class="flex items-center gap-3"><i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl'></i><span class="font-semibold text-sm">${msg}</span></div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateX(0)', 10);
            setTimeout(() => { toast.style.transform = 'translateX(full)'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => loadTable(1));
    













