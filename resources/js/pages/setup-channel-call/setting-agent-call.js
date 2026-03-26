
        const AJAX_URL = document.getElementById('setting-agent-call-config').dataset.ajaxUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentPage = 1, searchTimer = null;

window.loadGrid = function(page = 1) {
            currentPage = page;
            const search = document.getElementById('agent-call-search').value;
            const perPage = document.getElementById('entries-per-page').value;
            const container = document.getElementById('agent-grid-container');

            fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                renderCards(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Grid load error:', err);
                container.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Failed to load data.</div>';
            });
        }

window.renderCards = function(items) {
            const container = document.getElementById('agent-grid-container');
            if (!items || items.length === 0) {
                container.innerHTML = '<div class="col-span-full flex flex-col items-center justify-center p-12 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed"><i class="bx bx-user-x text-5xl mb-3 opacity-50"></i><p>No agents found.</p></div>';
                return;
            }

            container.innerHTML = items.map(user => `
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 pt-8 relative group cursor-default">
                    <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-row items-center justify-center gap-3 backdrop-blur-[2px] z-10 rounded-2xl pointer-events-none group-hover:pointer-events-auto">
                        <button onclick='editAgentCallModal(${JSON.stringify(user)})' title="Edit" class="w-10 h-10 rounded-[10px] bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-600 hover:-translate-y-1">
                            <i class='bx bx-edit text-xl'></i>
                        </button>
                        <button onclick="syncAgentCall(${user.id})" title="Sync / Reset Password" class="w-10 h-10 rounded-[10px] bg-emerald-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-75 hover:bg-emerald-600 hover:-translate-y-1">
                            <i class='bx bx-refresh text-2xl'></i>
                        </button>
                        <button onclick="deleteAgentCall(${user.id})" title="Delete" class="w-10 h-10 rounded-[10px] bg-red-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-150 hover:bg-red-600 hover:-translate-y-1">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                    <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm z-0">Not Login</div>
                    <div class="w-24 h-24 flex-shrink-0 rounded-full bg-teal-500/20 mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors z-0">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=2dd4bf&color=fff&size=128" alt="${esc(user.name)}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-white font-semibold text-center text-[15px] mb-1 px-2 truncate w-full group-hover:text-blue-400 transition-colors z-0">${esc(user.name)}</h3>
                    <p class="text-gray-400 text-[11px] text-center truncate w-full px-2 mb-4 z-0">User 101${user.id % 99} - Pin 101${user.id % 99} - Epic Not Login</p>
                    <div class="bg-blue-500 text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-sm select-none z-0 hover:-translate-y-0.5 transition-transform">Inbound & Outbound Call</div>
                </div>
            `).join('');
        }

window.renderPagination = function(data) {
            document.getElementById('grid-pagination-info').innerText = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} entries`;
            const container = document.getElementById('grid-pagination-links');
            container.innerHTML = '';
            if (data.last_page <= 1) return;
            const nav = document.createElement('nav'); nav.className = 'flex gap-2';
            const createBtn = (label, page, disabled, active) => {
                const btn = document.createElement('button'); btn.innerHTML = label; btn.disabled = disabled;
                btn.className = `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all ${active ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 border border-gray-700/50'} ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
                if (!disabled) btn.onclick = () => loadGrid(page);
                return btn;
            };
            nav.appendChild(createBtn('<i class="bx bx-chevron-left"></i>', data.current_page - 1, data.current_page <= 1));
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    nav.appendChild(createBtn(i, i, false, i === data.current_page));
                } else if (i === 2 || i === data.last_page - 1) {
                    const dots = document.createElement('span'); dots.className = 'w-5 h-10 flex items-center justify-center text-gray-600';
                    dots.innerText = '...'; nav.appendChild(dots);
                }
            }
            nav.appendChild(createBtn('<i class="bx bx-chevron-right"></i>', data.current_page + 1, data.current_page >= data.last_page));
            container.appendChild(nav);
        }

window.esc = function(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
window.debounceSearch = function(q) { clearTimeout(searchTimer); searchTimer = setTimeout(() => loadGrid(1), 500); }

window.openAgentCallModal = function() { window.dispatchEvent(new CustomEvent('agent-call-modal')); }
window.editAgentCallModal = function(userObj) { window.dispatchEvent(new CustomEvent('agent-call-modal', { detail: { userObj } })); }

window.syncAgentCall = function(id) {
            Swal.fire({
                title: 'Sync Agent Call?', text: "Anda akan mensinkronisasi data/kredensial agen ini.", icon: 'question',
                showCancelButton: true, confirmButtonColor: '#10b981', cancelButtonColor: '#f43f5e', confirmButtonText: 'Ya, Sinkronisasi!'
            }).then((result) => { if (result.isConfirmed) showToast('Sinkronisasi berhasil (Simulasi).'); });
        }

window.deleteAgentCall = function(id) {
            Swal.fire({
                title: 'Hapus Agent Call?', text: "Agent Call ini akan dihapus secara permanen!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3b82f6', cancelButtonColor: '#f43f5e', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) showToast('Data berhasil dihapus (Simulasi).'); });
        }

window.agentCallModalData = function() {
            return {
                open: false, allUsers: [], userSearch: '', selectedUserObj: null,
                formData: { user_id: '', call_type: '', password_epic: '', user_pabx: '', password_pabx: '', pin_pabx: '' },
                async init() {
                    const res = await fetch(AJAX_URL + '?per_page=100');
                    const data = await res.json();
                    this.allUsers = data.data;
                },
                get filteredUsers() {
                    if (this.userSearch === '') return this.allUsers.slice(0, 50);
                    return this.allUsers.filter(usr => usr.name.toLowerCase().includes(this.userSearch.toLowerCase()) || usr.email.toLowerCase().includes(this.userSearch.toLowerCase())).slice(0, 50);
                },
                selectUser(usr) { this.selectedUserObj = usr; this.formData.user_id = usr ? usr.id : ''; },
                initData(detail) {
                    this.userSearch = ''; this.formData = { user_id: '', call_type: '', password_epic: '', user_pabx: '', password_pabx: '', pin_pabx: '' };
                    if (detail && detail.userObj) { this.selectUser(detail.userObj); this.formData.call_type = 'Inbound & Outbound Call'; }
                    else { this.selectedUserObj = null; this.selectUser(null); }
                },
                saveAgentCall() {
                    if (!this.formData.user_id) { showToast('Please select a User Agent', 'error'); return; }
                    this.open = false; showToast('Setting Agent Call saved successfully!');
                }
            }
        }

window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-6 right-6 bg-gray-900 border ${type === 'success' ? 'border-green-500/50 text-green-400' : 'border-red-500/50 text-red-500'} shadow-2xl rounded-xl flex items-center p-4 z-[600] transition-all transform translate-x-full`;
            toast.innerHTML = `<i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-2xl mr-3'></i><span class="font-semibold text-sm">${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.style.transform = 'translateX(0)', 10);
            setTimeout(() => { toast.style.transform = 'translateX(full)'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => loadGrid(1));
    











