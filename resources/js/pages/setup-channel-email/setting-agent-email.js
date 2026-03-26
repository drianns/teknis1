
        const configElement = document.getElementById('setting-agent-email-config');
        const AJAX_URL = document.getElementById('setting-agent-email-config').dataset.ajaxUrl;

window.agentEmailPage = function() {
            return {
                users: [],
                isLoading: false,
                searchQuery: '',
                perPage: 20,
                pagination: {},

                init() {
                    this.loadTable();
                },

                loadTable(page = 1) {
                    this.isLoading = true;
                    fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(this.searchQuery)}&per_page=${this.perPage}`)
                        .then(res => res.json())
                        .then(data => {
                            this.users = data.data;
                            this.pagination = data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.isLoading = false;
                            showToast('Error loading agents', 'error');
                        });
                },

                editAgentEmailModal(user) {
                    window.dispatchEvent(new CustomEvent('agent-email-modal', {
                        detail: { userObj: user, isEdit: true }
                    }));
                },

                deleteAgentEmail(id) {
                    Swal.fire({
                        title: 'Hapus Agent Email?',
                        text: "Data setelan agent ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#f43f5e',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showToast('Data berhasil dihapus (Simulasi).', 'success');
                            // In real app: this.loadTable(this.pagination.current_page);
                        }
                    })
                }
            }
        }

window.openAgentEmailModal = function() {
            window.dispatchEvent(new CustomEvent('agent-email-modal'));
        }

window.agentEmailModalData = function() {
            return {
                open: false,
                isEditMode: false,
                isModalLoading: false,
                modalsearchQuery: '',
                modalPerPage: 10,
                modalUsers: [],
                modalPagination: {},
                selectedUsers: [],
                formData: {
                    account_email: '',
                    max_distribution: ''
                },

                initData(detail) {
                    this.modalSearchQuery = '';
                    this.selectedUsers = [];
                    this.formData = {
                        account_email: '',
                        max_distribution: ''
                    };
                    
                    if (detail && detail.isEdit && detail.userObj) {
                        this.isEditMode = true;
                        this.selectedUsers = [detail.userObj.id];
                        this.formData.account_email = 'club.indonesia@nespresso.co.id';
                        this.formData.max_distribution = 20;
                    } else {
                        this.isEditMode = false;
                    }
                    this.loadModalData(1);
                },

                loadModalData(page = 1) {
                    this.isModalLoading = true;
                    fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(this.modalSearchQuery)}&per_page=${this.modalPerPage}`)
                        .then(res => res.json())
                        .then(data => {
                            this.modalUsers = data.data;
                            this.modalPagination = data;
                            this.isModalLoading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.isModalLoading = false;
                        });
                },

                toggleSelection(id) {
                    const idx = this.selectedUsers.indexOf(id);
                    if (idx > -1) {
                        this.selectedUsers.splice(idx, 1);
                    } else {
                        this.selectedUsers.push(id);
                    }
                },

                saveAgentEmail() {
                    if (this.selectedUsers.length === 0) {
                        showToast('Harap pilih minimal 1 Agent', 'error');
                        return;
                    }
                    if (!this.formData.account_email) {
                        showToast('Harap pilih Account Email', 'error');
                        return;
                    }
                    if (this.formData.max_distribution === '') {
                        showToast('Harap pilih Maximal Distribution Data', 'error');
                        return;
                    }

                    // Simulated Saving Process
                    this.open = false;
                    showToast('Setting Agent Email berhasil disimpan!', 'success');
                    // In real app, you would reload the main table:
                    // window.dispatchEvent(new CustomEvent('reload-agent-table'));
                }
            }
        }

window.showToast = function(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;

            let iconClass = 'bx-check-circle';
            let iconColor = 'text-emerald-500';
            let bgLine = 'bg-emerald-500';

            if (type === 'error') {
                iconClass = 'bx-error-circle';
                iconColor = 'text-red-500';
                bgLine = 'bg-red-500';
            }

            toast.className = `fixed top-6 right-6 bg-gray-900 border border-gray-800 shadow-xl rounded-xl flex items-center overflow-hidden z-[600] min-w-[300px] toast-enter`;

            toast.innerHTML = `
                <div class="w-1.5 h-full self-stretch ${bgLine}"></div>
                <div class="px-4 py-3 flex items-center w-full">
                    <i class='bx ${iconClass} ${iconColor} text-2xl mr-3'></i>
                    <div class="flex-1">
                        <p class="text-white text-sm font-semibold">${type === 'error' ? 'Error' : 'Success'}</p>
                        <p class="text-gray-400 text-[13px]">${message}</p>
                    </div>
                    <button onclick="document.getElementById('${toastId}').classList.add('toast-exit')" class="ml-4 text-gray-500 hover:text-white transition-colors">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                const el = document.getElementById(toastId);
                if (el) {
                    el.classList.add('toast-exit');
                    setTimeout(() => el.remove(), 300);
                }
            }, 4000);
        }
    






