
        const configElement = document.getElementById('ticket-notification-config');
        const AJAX_URL = document.getElementById('ticket-notification-config').dataset.ajaxUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const masterUsers = JSON.parse(configElement.dataset.masterUsers);
        let currentGridPage = 1, searchTimer = null;

window.loadUserGrid = function(page = 1) {
            currentGridPage = page;
            const search = document.getElementById('user-grid-search').value;
            const perPage = document.getElementById('entries-per-page').value;
            const container = document.getElementById('user-grid-container');

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderUserGrid(data.data);
                renderGridPagination(data);
            })
            .catch(err => {
                console.error('Grid load error:', err);
                container.innerHTML = '<div class="col-span-full p-20 text-center text-red-500">Failed to load notification users.</div>';
            });
        }

window.renderUserGrid = function(items) {
            const container = document.getElementById('user-grid-container');
            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center p-20 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed">
                        <i class='bx bx-user-x text-5xl mb-3 opacity-50'></i>
                        <p>No email notification users found.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = items.map(nu => {
                const user = nu.user;
                const dept = user.company ? user.company.name : 'N/A';
                const escName = esc(user.name);
                const escEmail = esc(user.email);
                
                return `
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 relative group cursor-pointer" 
                        onclick='editNotificationModal(${JSON.stringify(nu)}, ${JSON.stringify(user)})'>
                        <div class="absolute inset-0 bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px] z-10">
                            <div class="w-10 h-10 rounded-full bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                <i class='bx bx-edit text-xl'></i>
                            </div>
                        </div>
                        <div class="w-24 h-24 flex-shrink-0 rounded-full bg-[#A7A7DD] mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=A7A7DD&color=fff&size=128" alt="${escName}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-white font-semibold text-center text-sm mb-2 px-2 truncate w-full group-hover:text-blue-400 transition-colors">${escName}</h3>
                        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3 shadow-sm select-none">${esc(dept)}</div>
                        <p class="text-gray-400 text-xs text-center truncate w-full px-2" title="${escEmail}">${escEmail}</p>
                    </div>
                `;
            }).join('');
        }

window.renderGridPagination = function(data) {
            document.getElementById('grid-pagination-info').innerText = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} users`;
            const container = document.getElementById('grid-pagination-links');
            container.innerHTML = '';
            
            if (data.last_page <= 1) return;

            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button');
                btn.innerHTML = label;
                btn.disabled = disabled;
                btn.className = `w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold transition-all ${active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadUserGrid(page);
                return btn;
            };

            container.appendChild(createBtn('<i class="bx bx-left-arrow-alt"></i>', data.current_page - 1, data.current_page <= 1, false));
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    container.appendChild(createBtn(i, i, false, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const span = document.createElement('span');
                    span.innerText = '...';
                    span.className = 'text-gray-600 self-end px-1';
                    container.appendChild(span);
                }
            }
            container.appendChild(createBtn('<i class="bx bx-right-arrow-alt"></i>', data.current_page + 1, data.current_page >= data.last_page, false));
        }

window.esc = function(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

window.debounceSearch = function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => loadUserGrid(1), 500);
        }

window.toggleSetting = function(name, isActive) {
            fetch('/ticket-notification-system/setting', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ name: name, is_active: isActive })
            }).then(res => res.json())
            .then(data => {
                if(data.success) showToast('Setting saved');
                else showToast('Error saving setting', 'error');
            }).catch(() => showToast('Server error', 'error'));
        }

window.openNotificationModal = function() { window.dispatchEvent(new CustomEvent('notification-modal')); }

window.editNotificationModal = function(nuObj, userObj) {
            window.dispatchEvent(new CustomEvent('notification-modal', { detail: { nuObj, userObj } }));
        }

window.notificationModalData = function() {
            return {
                open: false, allUsers: masterUsers, userSearch: '', selectedUserObj: null,
                formData: { user_id: '', email: '', status: 'Yes', level: '', department: '', group_agent: '', is_ticket_create: false, is_ticket_over_sla: false, is_ticket_closed: false, is_ticket_escalation: false },
                get filteredUsers() {
                    const q = this.userSearch.toLowerCase();
                    return q === '' ? this.allUsers.slice(0, 50) : this.allUsers.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)).slice(0, 50);
                },
                selectUser(usr) {
                    this.selectedUserObj = usr;
                    if(usr) {
                        this.formData.user_id = usr.id; this.formData.email = usr.email;
                        this.formData.level = usr.level; this.formData.department = usr.department;
                        this.formData.group_agent = usr.group_agent;
                    } else {
                        this.formData.user_id = ''; this.formData.email = '';
                        this.formData.level = ''; this.formData.department = ''; this.formData.group_agent = '';
                    }
                },
                initData(detail) {
                    this.userSearch = '';
                    if (detail && detail.nuObj) {
                        const nu = detail.nuObj;
                        const loadedUser = this.allUsers.find(u => u.id == nu.user_id);
                        this.selectUser(loadedUser);
                        this.formData.status = nu.status;
                        this.formData.is_ticket_create = !!nu.is_ticket_create;
                        this.formData.is_ticket_over_sla = !!nu.is_ticket_over_sla;
                        this.formData.is_ticket_closed = !!nu.is_ticket_closed;
                        this.formData.is_ticket_escalation = !!nu.is_ticket_escalation;
                    } else {
                        this.selectedUserObj = null; this.selectUser(null);
                        this.formData.status = 'Yes';
                        this.formData.is_ticket_create = this.formData.is_ticket_over_sla = this.formData.is_ticket_closed = this.formData.is_ticket_escalation = false;
                    }
                },
                saveUserConf() {
                    if (!this.formData.user_id) { showToast('Please select a User Name', 'error'); return; }
                    showLoading('Saving...');
                    fetch('/ticket-notification-system/user', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({
                            user_id: this.formData.user_id, status: this.formData.status,
                            is_ticket_create: this.formData.is_ticket_create, is_ticket_over_sla: this.formData.is_ticket_over_sla,
                            is_ticket_closed: this.formData.is_ticket_closed, is_ticket_escalation: this.formData.is_ticket_escalation
                        })
                    }).then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showToast(data.message); this.open = false;
                            loadUserGrid(currentGridPage);
                        } else { showToast(data.message || 'Error', 'error'); }
                    }).catch(() => { hideLoading(); showToast('Server error', 'error'); });
                }
            }
        }

        // UI Helpers
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

        document.addEventListener('DOMContentLoaded', () => loadUserGrid(1));
    










