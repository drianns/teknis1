// =====================================================
// Monitoring Email Response Page Module
// Extracted from: pages/setup-channel-email/monitoring-email-response/index.blade.php
// Provides: global modal helpers and Alpine.js component monitoringEmail()
// Reads config from: #monitoring-email-config data-* attrs
// =====================================================

// ── Dropdown / Modal Helpers ───────────────────────
window.openAssignModal = function (id) {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-content');
    if (!modal || !content) return;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-75', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeAssignModal = function () {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-content');
    if (!modal || !content) return;

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-75', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

window.submitAssignAction = function () {
    const agent = document.getElementById('assign-agent').value;
    if (!agent) {
        if (window.Swal) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a User Agent.',
                icon: 'error',
                background: '#1f2937',
                color: '#fff',
                confirmButtonColor: '#3b82f6'
            });
        } else {
            alert('Please select a User Agent.');
        }
        return;
    }
    
    if (window.Swal) {
        Swal.fire({
            title: 'Success!',
            text: `Assigned to ${agent} successfully.`,
            icon: 'success',
            background: '#1f2937',
            color: '#fff',
            confirmButtonColor: '#3b82f6'
        });
    } else {
        alert(`Assigned to ${agent} successfully.`);
    }
    window.closeAssignModal();
};

window.openConversationModal = function (id) {
    const modal = document.getElementById('conversation-modal');
    const content = document.getElementById('conversation-content');
    if (!modal || !content) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-75', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeConversationModal = function () {
    const modal = document.getElementById('conversation-modal');
    const content = document.getElementById('conversation-content');
    if (!modal || !content) return;

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-75', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

document.addEventListener('click', function (e) {
    if (e.target.id === 'assign-backdrop') window.closeAssignModal();
    if (e.target.id === 'conversation-backdrop') window.closeConversationModal();
});

// ── Alpine.js Component ────────────────────────────
window.monitoringEmail = function () {
    return {
        isLoading: false,
        perPage: 10,
        currentPage: 1,
        searchQuery: '',
        sortCol: 'created_at',
        sortDir: 'desc',
        emails: [],
        displayedEmails: [],
        openDateBar: false,
        activePreset: '',
        pagination: {
            start: 0,
            end: 0,
            total: 0,
            totalPages: 1
        },
        filters: {
            emailAccount: 'support@kanmogroup.com',
            startDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 30 days ago
            endDate: new Date().toISOString().split('T')[0]
        },

        formatDateForBar(dateStr) {
            if (!dateStr) return 'Select Date';
            const date = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        },

        setPreset(type) {
            this.activePreset = type;
            const today = new Date();
            let start, end;

            const formatInputDate = (date) => date.toISOString().split('T')[0];

            switch (type) {
                case 'today':
                    start = end = today;
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    start = end = yesterday;
                    break;
                case 'last7days':
                    const last7 = new Date(today);
                    last7.setDate(today.getDate() - 7);
                    start = last7;
                    end = today;
                    break;
                case 'thismonth':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = today;
                    break;
            }

            if (start && end) {
                this.filters.startDate = formatInputDate(start);
                this.filters.endDate   = formatInputDate(end);
                this.applyFilters();
            }
        },

        init() {
            this.fetchData();
        },

        fetchData() {
            const config = document.getElementById('monitoring-email-config');
            if (!config) return;

            const endpointUrl = config.dataset.endpoint;
            const csrfToken   = config.dataset.csrf;

            this.isLoading = true;

            fetch(endpointUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    per_page: this.perPage,
                    page: this.currentPage,
                    search: this.searchQuery,
                    sort_col: this.sortCol,
                    sort_dir: this.sortDir,
                    email_account: this.filters.emailAccount,
                    start_date: this.filters.startDate,
                    end_date: this.filters.endDate
                })
            })
                .then(res => res.json())
                .then(data => {
                    this.emails          = data.emails || [];
                    this.displayedEmails = data.emails || [];
                    this.pagination      = data.pagination || this.pagination;
                    this.isLoading       = false;
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    this.isLoading = false;
                });
        },

        applyFilters() {
            this.currentPage = 1;
            this.fetchData();
        },

        applySearch() {
            this.currentPage = 1;
            this.fetchData();
        },

        refreshData() {
            this.currentPage = 1;
            this.fetchData();
        },

        sortBy(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = col;
                this.sortDir = 'asc';
            }
            this.fetchData();
        },

        getSortIcon(col) {
            if (this.sortCol !== col) return 'bx-sort opacity-30';
            return this.sortDir === 'asc' ? 'bx-sort-up text-blue-500' : 'bx-sort-down text-blue-500';
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchData();
            }
        },

        nextPage() {
            if (this.currentPage < this.pagination.totalPages) {
                this.currentPage++;
                this.fetchData();
            }
        },

        goToPage(p) {
            this.currentPage = p;
            this.fetchData();
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        },

        formatTime(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false }) + ' WIB';
        },

        handleAssign(id) {
            if (typeof window.openAssignModal === 'function') {
                window.openAssignModal(id);
            } else {
                console.warn('openAssignModal function not injected yet');
            }
        },

        handleConversation(id) {
            if (typeof window.openConversationModal === 'function') {
                window.openConversationModal(id);
            } else {
                console.warn('openConversationModal function not injected yet');
            }
        }
    }
};

