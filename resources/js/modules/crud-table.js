export const CrudTable = {
    // Show a toast notification
    showToast(msg, type) {
        let activeToasts = document.querySelectorAll('.toast-notification').length;
        const offset = activeToasts * 60; // Offset for spacing multiple toasts

        const t = document.createElement('div');
        t.className = `toast-notification fixed right-6 z-[9999] px-5 py-3 rounded-xl shadow-2xl text-sm font-semibold flex items-center gap-3 transition-all duration-300 transform translate-y-4 opacity-0 ${type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'} text-white`;
        t.style.bottom = `calc(1.5rem + ${offset}px)`; // 1.5rem = 6px * 4 = 24px default bottom
        
        t.innerHTML = `
            <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-x-circle'} text-xl'></i>
            <span>${msg}</span>
        `;
        document.body.appendChild(t);
        
        // Animate in
        requestAnimationFrame(() => {
            t.classList.remove('translate-y-4', 'opacity-0');
        });

        setTimeout(() => {
            t.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => t.remove(), 300);
            
            // Re-adjust remaining toasts
            document.querySelectorAll('.toast-notification').forEach((toast, index) => {
                 toast.style.bottom = `calc(1.5rem + ${index * 60}px)`;
            });
        }, 3000);
    },

    // Escape HTML to prevent XSS
    escHtml(s) {
        if (s === null || s === undefined) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    },

    // Escape JS to prevent breaking inline onclick handlers
    escJs(s) {
        if (s === null || s === undefined) return '';
        return String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    },

    // Initialize table with search and pagination support
    init(config, renderRowCallback) {
        this.currentConfig = config;
        this.renderRow = renderRowCallback;
        this.currentPage = 1;
        this.searchQuery = '';
        this.perPage = document.getElementById('perPage')?.value || 10;

        // Search Input Listener
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.searchQuery = e.target.value;
                    this.currentPage = 1;
                    this.load();
                }, 500);
            });
        }

        // Per Page Listener
        const perPageSelect = document.getElementById('perPage');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', (e) => {
                this.perPage = e.target.value;
                this.currentPage = 1;
                this.load();
            });
        }

        // Initial Load
        this.load();

        // Attach refresh method to config for external calls
        config.onSuccess = () => this.load();
    },

    // Internal load method
    async load() {
        const tableBody = document.getElementById('tableBody');
        if (tableBody) {
             tableBody.innerHTML = `<tr><td colspan="100%" class="px-6 py-16 text-center text-gray-500">
                <i class="bx bx-loader-alt bx-spin text-3xl mb-2 text-blue-500"></i>
                <p>Loading data...</p>
            </td></tr>`;
        }

        const data = await this.loadTableData(this.currentConfig, this.currentPage, this.searchQuery, this.perPage);
        this.render(data, 'tableBody', this.renderRow);
        this.renderPagination(data, 'paginationInfo', 'paginationLinks', (page) => {
            this.currentPage = page;
            this.load();
        });
    },

    // Render table with data mapper function
    render(data, tableBodyId, templateMapper, emptyMessage = 'No data found') {
        const b = document.getElementById(tableBodyId);
        if (!b) return;

        if (!data || !data.data || !data.data.length) {
            b.innerHTML = `<tr><td colspan="100%" class="px-6 py-16 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <i class='bx bx-data text-5xl mb-3 opacity-20'></i>
                    <p>${emptyMessage}</p>
                </div>
            </td></tr>`;
            return;
        }

        b.innerHTML = data.data.map(templateMapper).join('');
    },

    // Render pagination controls
    renderPagination(data, infoId, linksId, loadDataCallback) {
        const info = document.getElementById(infoId);
        const links = document.getElementById(linksId);
        
        if (info) {
            info.innerHTML = `Showing <span class="text-white font-bold">${data.from ?? 0}</span> to <span class="text-white font-bold">${data.to ?? 0}</span> of <span class="text-white font-bold">${data.total ?? 0}</span> entries`;
        }

        if (links) {
            links.innerHTML = '';
            if (!data.last_page || data.last_page <= 1) return;

            const btn = (lb, pg, dis, act) => {
                const b = document.createElement('button');
                b.innerHTML = lb;
                b.disabled = dis;
                b.className = `px-3 py-1 rounded-lg text-sm font-medium transition-colors ${act ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'} ${dis ? 'opacity-40 cursor-not-allowed' : ''}`;
                
                if (!dis) {
                    b.onclick = () => loadDataCallback(pg);
                }
                return b;
            };

            links.appendChild(btn('&laquo;', data.current_page - 1, data.current_page <= 1, false));
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                links.appendChild(btn(i, i, false, i === data.current_page));
            }
            links.appendChild(btn('&raquo;', data.current_page + 1, data.current_page >= data.last_page, false));
        }
    },

    // Fetch data wrapper
    async loadTableData(config, page, search, perPage) {
        try {
            const separator = config.ajaxUrl.includes('?') ? '&' : '?';
            const url = `${config.ajaxUrl}${separator}page=${page}&search=${encodeURIComponent(search || '')}&per_page=${perPage || 10}`;
            
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network response was not ok');
            
            return await response.json();
        } catch (error) {
            console.error('Data loading error:', error);
            this.showToast('Failed to load data', 'error');
            return { data: [], total: 0 };
        }
    },

    // Save Data (Create or Update)
    async save(config, id, formData, btnId = 'saveBtn') {
        const isEdit = id && id !== '';
        const url = isEdit ? `${config.updateBase}/${id}` : config.storeUrl;
        
        if (isEdit) formData.append('_method', 'PUT');
        
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-1"></i> Saving...';
        }

        try {
            const response = await fetch(url, {
                method: 'POST', // Always POST, method injected for PUT
                headers: {
                    'X-CSRF-TOKEN': config.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const res = await response.json();
            
            if (res.success) {
                this.showToast(res.message, 'success');
                if (config.onSuccess) config.onSuccess(res);
                return true;
            } else {
                const errorMsg = res.errors ? Object.values(res.errors).flat().join(', ') : (res.message || 'Validation error');
                this.showToast(errorMsg, 'error');
                return false;
            }
        } catch (error) {
            console.error('Save error:', error);
            this.showToast('A server error occurred.', 'error');
            return false;
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.originalText || 'Save Changes';
            }
        }
    },

    // Delete Data
    async delete(config, id) {
        if (!confirm('Are you sure you want to delete this data?')) return false;
        
        try {
            const formData = new URLSearchParams({ _token: config.csrf, _method: 'DELETE' });
            const response = await fetch(`${config.deleteBase}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const res = await response.json();
            if (res.success) {
                this.showToast(res.message, 'success');
                if (config.onSuccess) config.onSuccess(res);
                return true;
            } else {
                this.showToast(res.message || 'Validation error', 'error');
                return false;
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showToast('A server error occurred.', 'error');
            return false;
        }
    },

    // Modal Utility Helpers
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                const modalContent = modal.querySelector('.relative.bg-gray-800');
                if(modalContent) {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        }
    },

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const modalContent = modal.querySelector('.relative.bg-gray-800');
            if(modalContent) {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset form if exists
                const formInputs = modal.querySelectorAll('input:not([type="hidden"]), select, textarea');
                formInputs.forEach(el => el.value = '');
            }, 300);
        }
    },
    
    // Status Badge generator
    statusBadge(status) {
        const isAktif = String(status).toLowerCase() === 'aktif';
        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase ${isAktif ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20'}">${this.escHtml(status)}</span>`;
    }
};

window.CrudTable = CrudTable;

