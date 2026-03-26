import '../../css/pages/apps/ticketing-system.css';

            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Quill Editors
                initQuillEditor("#instan-note-editor", "note");
                initQuillEditor("#editor-customer-question", "customer_question");
                initQuillEditor("#editor-agent-response", "agent_response");
            });

window.initQuillEditor = function(selector, inputName) {
                if (document.querySelector(selector)) {
                    var quill = new Quill(selector, {
                        theme: "snow",
                        placeholder: 'Type your text here...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['link', 'image', 'table'],
                                ['clean']
                            ]
                        }
                    });

                    // Sync with textarea on submit or change
                    quill.on('text-change', function () {
                        var input = document.querySelector('textarea[name=' + inputName + ']');
                        if (input) input.value = quill.root.innerHTML;
                    });
                }
            }

            // Generic toggle for animation
window.toggleElementWithAnimation = function(elementId) {
                const el = document.getElementById(elementId);
                if (el.classList.contains('hidden')) {
                    // Open
                    el.classList.remove('hidden');
                    el.classList.remove('animate-close');
                    el.classList.add('animate-open');
                } else {
                    // Close
                    el.classList.remove('animate-open');
                    el.classList.add('animate-close');
                    el.addEventListener('animationend', function () {
                        if (el.classList.contains('animate-close')) {
                            el.classList.add('hidden');
                        }
                    }, { once: true });
                }
            }

window.toggleSidebarSearch = function() {
                toggleElementWithAnimation('sidebar-search-overlay');
            }

            const managedPopups = [
                'popup-new-customer',
                'popup-api-customer',
                'popup-other-channel',
                'popup-add-channel-customer'
            ];

window.togglePopup = function(popupId) {
                const targetEl = document.getElementById(popupId);
                if (!targetEl) return;

                // If currently closed, we want to open it. 
                // Before opening, close any other currently open managed popups.
                if (targetEl.classList.contains('hidden') || targetEl.classList.contains('animate-close')) {
                    managedPopups.forEach(id => {
                        if (id !== popupId) {
                            const otherEl = document.getElementById(id);
                            if (otherEl && !otherEl.classList.contains('hidden') && !otherEl.classList.contains('animate-close')) {
                                toggleElementWithAnimation(id); // Trigger close
                            }
                        }
                    });
                }

                toggleElementWithAnimation(popupId);
            }

            // Search Customer Logic
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('sidebar-search-input');
                const searchResults = document.getElementById('sidebar-search-results');
                const searchPlaceholder = document.getElementById('sidebar-search-placeholder');
                const searchActions = document.getElementById('sidebar-search-actions');

                if (searchInput && searchResults && searchPlaceholder && searchActions) {
                    const mockData = [];

                    searchInput.addEventListener('input', function (e) {
                        const keyword = e.target.value.toLowerCase();

                        if (keyword.length > 0) {
                            searchPlaceholder.classList.add('hidden');
                            searchActions.classList.add('hidden');
                            searchResults.classList.remove('hidden');

                            // Filter mock data (simple substring match)
                            const filtered = mockData.filter(item =>
                                item.name.toLowerCase().includes(keyword) ||
                                item.email.toLowerCase().includes(keyword)
                            );

                            // Render Results
                            searchResults.innerHTML = filtered.length ? filtered.map(item => `
                                <div onclick="selectCustomer('${item.name}')" class="cursor-pointer bg-gray-800 rounded-lg p-3 flex items-center gap-3 border border-gray-700 hover:border-gray-500 hover:bg-gray-700 transition-all group">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center shrink-0 ring-2 ring-gray-600 group-hover:ring-blue-500 transition-all">
                                        <i class="bx bx-user text-2xl text-gray-400 group-hover:text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="text-white text-sm font-bold mb-0.5 truncate group-hover:text-blue-400 transition-colors">${item.name}</h6>
                                        <p class="text-gray-400 text-xs mb-0.5 truncate">${item.email}</p>
                                        <p class="text-blue-400 text-xs font-mono truncate">${item.phone}</p>
                                    </div>
                                    <div class="shrink-0 text-gray-500 group-hover:text-blue-400 transition-colors">
                                        <i class="bx bx-chevron-right text-xl"></i>
                                    </div>
                                </div>
                            `).join('') : `
                                <div class="text-center py-8 text-gray-500">
                                    <i class="bx bx-search text-3xl mb-2 opacity-50"></i>
                                    <p class="text-sm">No results found for "${escapeHtml(keyword)}"</p>
                                </div>
                            `;

                        } else {
                            searchPlaceholder.classList.remove('hidden');
                            searchActions.classList.remove('hidden');
                            searchResults.classList.add('hidden');
                            searchResults.innerHTML = '';
                        }
                    });
                }
            });

            // Mock selection function
window.selectCustomer = function(name) {
                // Here you would typically fill the form or specific logic
                console.log('Customer Selected:', name);
                const input = document.getElementById('customer_name');
                if (input) input.value = name;

                // Close sidebar search for demo feeling
                toggleSidebarSearch();
            }

            // -- Ticketing JS Fetch implementation --
            let currentHistoryPage = 1;
            let currentCustomerPage = 1;
            let historyDebounceTimer;
            let customerDebounceTimer;

            document.addEventListener('DOMContentLoaded', () => {
                loadHistoryData();
                loadCustomerData();

                // History Events
                document.getElementById('historySearch')?.addEventListener('input', function() {
                    clearTimeout(historyDebounceTimer);
                    historyDebounceTimer = setTimeout(() => {
                        currentHistoryPage = 1;
                        loadHistoryData();
                    }, 300);
                });
                document.getElementById('historyPerPage')?.addEventListener('change', function() {
                    currentHistoryPage = 1;
                    loadHistoryData();
                });

                // Customer Events
                document.getElementById('customerSearch')?.addEventListener('input', function() {
                    clearTimeout(customerDebounceTimer);
                    customerDebounceTimer = setTimeout(() => {
                        currentCustomerPage = 1;
                        loadCustomerData();
                    }, 300);
                });
                document.getElementById('customerPerPage')?.addEventListener('change', function() {
                    currentCustomerPage = 1;
                    loadCustomerData();
                });
            });

            // --- History Ticketing ---
window.loadHistoryData = function(page = 1) {
                currentHistoryPage = page;
                const search = document.getElementById('historySearch')?.value || '';
                const perPage = document.getElementById('historyPerPage')?.value || 10;
                const tbody = document.getElementById('historyTableBody');

                if(!tbody) return;
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading data...</p></td></tr>`;

                const url = `'' /* FIXED BY MIGRATION */?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        renderHistoryTable(data);
                        renderHistoryPagination(data);
                    })
                    .catch(error => {
                        console.error('Error fetching history:', error);
                        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                    });
            }

window.renderHistoryTable = function(data) {
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';

                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500"><i class="bx bx-folder-open text-4xl mb-2"></i><p>No data available in table</p></td></tr>`;
                    return;
                }

                data.data.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-800/20';

                    const tno = item.ticket_number || '-';
                    const cat = item.category || '-';
                    const st = item.status ? item.status.toLowerCase() : 'closed';
                    
                    let statusColor = 'bg-gray-700 text-gray-300';
                    if (st === 'open') statusColor = 'bg-blue-900/50 text-blue-300 border border-blue-800';
                    if (st === 'closed') statusColor = 'bg-green-900/50 text-green-300 border border-green-800';
                    if (st === 'pending') statusColor = 'bg-yellow-900/50 text-yellow-300 border border-yellow-800';

                    const userName = item.chat_ticket_user && item.chat_ticket_user.name ? item.chat_ticket_user.name : 'Unknown';
                    const photoSrc = item.chat_ticket_user && item.chat_ticket_user.photo_src ? item.chat_ticket_user.photo_src : '';
                    let avatarHtml = '';
                    if (photoSrc) {
                        avatarHtml = `<img src="${photoSrc}" class="w-6 h-6 rounded-full">`;
                    } else {
                        avatarHtml = `<div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs">${userName.charAt(0)}</div>`;
                    }

                    const dt = item.created_at ? new Date(item.created_at).toLocaleString('en-US') : '-';

                    tr.innerHTML = `
                        <td class="px-4 py-3 font-medium text-blue-400">${tno}</td>
                        <td class="px-4 py-3">${cat}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs ${statusColor}">${item.status || 'N/A'}</span></td>
                        <td class="px-4 py-3"><div class="flex items-center gap-2">${avatarHtml}<span>${userName}</span></div></td>
                        <td class="px-4 py-3">${dt}</td>
                        <td class="px-4 py-3 text-center"><a href="#" class="btn btn-sm bg-gray-700 hover:bg-gray-600 text-white rounded px-2 py-1"><i class="bx bx-show"></i></a></td>
                    `;
                    tbody.appendChild(tr);
                });
            }

window.renderHistoryPagination = function(data) {
                const info = document.getElementById('historyTableInfo');
                const container = document.getElementById('historyPaginationContainer');
                
                info.innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0} entries`;
                if (!data.last_page || data.last_page <= 1) {
                    container.innerHTML = '';
                    return;
                }

                let html = '<div class="flex gap-1">';
                html += `<button onclick="loadHistoryData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white">Prev</button>`;
                
                for (let i = 1; i <= data.last_page; i++) {
                    if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                        if (i === data.current_page) {
                            html += `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg">${i}</button>`;
                        } else {
                            html += `<button onclick="loadHistoryData(${i})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">${i}</button>`;
                        }
                    } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                        html += `<span class="px-2 py-1 text-gray-500">...</span>`;
                    }
                }

                html += `<button onclick="loadHistoryData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white">Next</button></div>`;
                container.innerHTML = html;
            }

            // --- Customer Ticketing ---
window.loadCustomerData = function(page = 1) {
                currentCustomerPage = page;
                const search = document.getElementById('customerSearch')?.value || '';
                const perPage = document.getElementById('customerPerPage')?.value || 10;
                const tbody = document.getElementById('customerTableBody');

                if(!tbody) return;
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading data...</p></td></tr>`;

                // Reusing standard endpoint ticketing.getCustomerData?
                const url = `'' /* FIXED BY MIGRATION */?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        renderCustomerTable(data);
                        renderCustomerPagination(data);
                    })
                    .catch(error => {
                        console.error('Error fetching customers:', error);
                        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                    });
            }

window.renderCustomerTable = function(data) {
                const tbody = document.getElementById('customerTableBody');
                tbody.innerHTML = '';

                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500"><i class="bx bx-user-x text-4xl mb-2"></i><p>No customer data found.</p></td></tr>`;
                    return;
                }

                data.data.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-800/20';

                    const channelName = item.channel && item.channel.name ? item.channel.name : '-';
                    const channelLogo = item.channel && item.channel.logo ? `<img src="${item.channel.logo}" class="w-5 h-5 object-contain">` : '';
                    const accountId = item.account_id || item.name || '-';
                    
                    const userName = item.chat_ticket_user && item.chat_ticket_user.name ? item.chat_ticket_user.name : 'Unknown';
                    const photoSrc = item.chat_ticket_user && item.chat_ticket_user.photo_src ? item.chat_ticket_user.photo_src : '';
                    let avatarHtml = '';
                    if (photoSrc) avatarHtml = `<img src="${photoSrc}" class="w-6 h-6 rounded-full">`;

                    tr.innerHTML = `
                        <td class="px-4 py-3"><div class="flex items-center gap-2">${channelLogo}<span>${channelName}</span></div></td>
                        <td class="px-4 py-3 font-medium text-white">${accountId}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-green-900/50 text-green-300 border border-green-800">Active</span></td>
                        <td class="px-4 py-3"><div class="flex items-center gap-2">${avatarHtml}<span>${userName}</span></div></td>
                        <td class="px-4 py-3 text-center"><button class="text-gray-400 hover:text-white"><i class="bx bx-show text-lg"></i></button></td>
                    `;
                    tbody.appendChild(tr);
                });
            }

window.renderCustomerPagination = function(data) {
                const info = document.getElementById('customerTableInfo');
                const container = document.getElementById('customerPaginationContainer');
                
                info.innerHTML = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0} entries`;
                if (!data.last_page || data.last_page <= 1) {
                    container.innerHTML = '';
                    return;
                }

                let html = '<div class="flex gap-1">';
                html += `<button onclick="loadCustomerData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white">Prev</button>`;
                
                for (let i = 1; i <= data.last_page; i++) {
                    if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                        if (i === data.current_page) {
                            html += `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg">${i}</button>`;
                        } else {
                            html += `<button onclick="loadCustomerData(${i})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">${i}</button>`;
                        }
                    } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                        html += `<span class="px-2 py-1 text-gray-500">...</span>`;
                    }
                }

                html += `<button onclick="loadCustomerData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white">Next</button></div>`;
                container.innerHTML = html;
            }

window.escapeHtml = function(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // ---- AJAX Save Ticket ----
window.saveTicketAjax = function() {
                const btn = document.getElementById('btnSaveTicket');
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i> Saving...';

                const form = document.getElementById('form-ticketing');
                const formData = new FormData(form);

                // Add fields that have IDs but no names
                formData.set('customer_name', document.getElementById('customer_name')?.value || '');
                formData.set('customer_email', document.getElementById('customer_email')?.value || '');
                formData.set('customer_phone', document.getElementById('customer_phone')?.value || '');
                formData.set('order_id', document.getElementById('inputOrderId')?.value || '');

                fetch(`'' /* FIXED BY MIGRATION */`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 'document.querySelector('meta[name="csrf-token"]').content',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    if (data.success) {
                        // Show success notification
                        showTicketToast(data.message || 'Ticket berhasil disimpan!', 'success');
                        form.reset();
                        // Reload history table if visible
                        if (typeof loadHistoryData === 'function') loadHistoryData();
                    } else {
                        showTicketToast(data.message || 'Gagal menyimpan ticket.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Save ticket error:', err);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    showTicketToast('Terjadi error saat menyimpan ticket.', 'error');
                });
            }

window.showTicketToast = function(message, type = 'success') {
                const toast = document.createElement('div');
                const colors = type === 'success' 
                    ? 'border-green-500/50 text-green-400' 
                    : 'border-red-500/50 text-red-400';
                const icon = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
                toast.className = `fixed top-6 right-6 bg-gray-900 border ${colors} shadow-2xl rounded-xl flex items-center p-4 z-[9999] transition-all duration-300 transform translate-x-full`;
                toast.innerHTML = `<i class='bx ${icon} text-2xl mr-3'></i><span class="font-semibold text-sm">${message}</span>`;
                document.body.appendChild(toast);
                requestAnimationFrame(() => { toast.style.transform = 'translateX(0)'; });
                setTimeout(() => {
                    toast.style.transform = 'translateX(120%)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        }
    






