import '../../../css/pages/channel/email/inbox.css';

// Global variables for pagination and search
let currentPage = 1;
let currentSearch = '';
let currentPerPage = 10;
let editorInstance = null;

// Modal Functions
window.toggleComposeModal = function (event) {
    if (event) event.preventDefault();
    const modal = document.getElementById('compose-modal');
    const backdrop = document.getElementById('compose-backdrop');
    const modalContent = document.getElementById('compose-content');

    if (!modal) return;

    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        if (backdrop) backdrop.classList.remove('hidden');
        void modal.offsetWidth;
        requestAnimationFrame(() => {
            if (backdrop) {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }
            if (modalContent) {
                modalContent.classList.remove('scale-75', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }
        });

        setTimeout(() => {
            const editorEl = document.querySelector('#editor');
            if (typeof ClassicEditor !== 'undefined' && !editorInstance && editorEl) {
                ClassicEditor.create(editorEl, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
                    placeholder: 'Type your message here...'
                }).then(editor => {
                    editorInstance = editor;
                }).catch(err => console.error(err));
            }
        }, 100);
    } else {
        window.closeComposeModal();
    }
};

window.closeComposeModal = function () {
    const modal = document.getElementById('compose-modal');
    const backdrop = document.getElementById('compose-backdrop');
    const modalContent = document.getElementById('compose-content');
    
    if (backdrop) {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
    }
    if (modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-75', 'opacity-0');
    }
    
    setTimeout(() => {
        if (modal) modal.classList.add('hidden');
        if (backdrop) backdrop.classList.add('hidden');
    }, 300);
};

window.handleReplyAction = function (id, sender) {
    const toInput = document.getElementById('compose-to');
    if (toInput) toInput.value = sender;
    window.toggleComposeModal();
};

window.handleForwardAction = function (id) {
    const toInput = document.getElementById('compose-to');
    if (toInput) toInput.value = '';
    window.toggleComposeModal();
};

window.handleAssignAction = function (id) {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-content');
    if (modal && content) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-75', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
};

window.closeAssignModal = function () {
    const modal = document.getElementById('assign-modal');
    const content = document.getElementById('assign-content');
    if (content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-75', 'opacity-0');
    }
    setTimeout(() => {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }, 300);
};

window.markAsRead = function(el, id) {
    if (!el || el.classList.contains('read')) return;
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('/channel/email/inbox/' + id + '/mark-read', { 
        method: 'POST', 
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } 
    }).then(() => {
        el.classList.remove('unread', 'font-semibold');
        el.classList.add('read', 'font-normal');
    }).catch(err => console.error(err));
};

function loadEmailData(view = null) {
    const activeView = view || 'inbox';
    const endpoint = '/channel/email/inbox/get-inbox-data';
    const tableBodyId = activeView + 'TableBody';

    const tbody = document.getElementById(tableBodyId);
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"><i class="bx bx-loader-alt bx-spin text-3xl mb-3 text-blue-500"></i><p>Loading data...</p></td></tr>';
    }

    const url = new URL(endpoint, window.location.origin);
    url.searchParams.append('page', currentPage);
    url.searchParams.append('search', currentSearch);
    url.searchParams.append('per_page', currentPerPage);

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            renderTable(data.data, tableBodyId, activeView);
            updatePagination(data);
        })
        .catch(err => {
            console.error(err);
            if (tbody) tbody.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-red-500 bg-red-900/20 rounded-lg">Error loading data</td></tr>';
        });
}

function renderTable(items, tbodyId, activeView) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg">No emails found</td></tr>';
        return;
    }

    let html = '';
    items.forEach((item, index) => {
        const emailId = item.id || '-';
        const fromOrTo = item.ticket_number || 'System';
        const subject = item.subject || 'No Subject';
        const statusText = item.status || 'Unknown';
        const dateStr = item.created_at ? new Date(item.created_at).toLocaleString() : '-';

        const rowClass = (item.status === 'read' ? 'read' : 'unread font-semibold') + 
                         (index % 2 === 1 ? ' bg-gray-700/50' : '') + 
                         ' hover:bg-gray-700/30 transition-colors cursor-pointer';

        html += '<tr class="email-row group/row ' + rowClass + '" onclick="window.markAsRead(this, ' + emailId + ')">';
        html += '<td class="py-3 px-4 text-blue-300">#' + emailId + '</td>';
        html += '<td class="py-3 px-4">' + (item.source_type || 'email') + '</td>';
        html += '<td class="py-3 px-4 truncate">' + fromOrTo + '</td>';
        html += '<td class="py-3 px-4 truncate">' + subject + '</td>';
        html += '<td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs bg-gray-500/20">' + statusText + '</span></td>';
        html += '<td class="py-3 px-4 text-gray-400">' + dateStr + '</td>';
        html += '<td class="py-3 px-4 text-center relative">';
        html += '<button onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle(\'hidden\')" class="text-gray-400 hover:text-blue-400"><i class="bx bx-dots-vertical-rounded"></i></button>';
        html += '<div class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl z-50 border border-white/10 py-2 text-left">';
        html += '<a href="javascript:void(0)" onclick="window.handleReplyAction(' + emailId + ', \'' + fromOrTo + '\')" class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 flex items-center gap-2">Reply</a>';
        html += '</div></td></tr>';
    });
    tbody.innerHTML = html;
}

function updatePagination(data) {
    const info = document.getElementById('tableInfo');
    const container = document.getElementById('paginationContainer');
    if (info) info.textContent = 'Showing ' + (data.from || 0) + ' to ' + (data.to || 0) + ' of ' + data.total + ' entries';
    if (container) {
        container.innerHTML = '<button class="px-3 py-1 rounded bg-gray-700">Prev</button><button class="px-3 py-1 rounded bg-gray-700">Next</button>';
    }
}

document.addEventListener('alpine:init', () => {
    const dataEl = document.querySelector('[x-data]');
    if (dataEl && dataEl.__x && dataEl.__x.$data) {
        Alpine.effect(() => {
            const activeView = dataEl.__x.$data.activeView || 'inbox';
            currentPage = 1;
            loadEmailData(activeView);
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadEmailData();
});
