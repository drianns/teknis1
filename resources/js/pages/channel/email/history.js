import '../../../css/pages/channel/email/history.css';

        // Search function - AJAX Handled
        // Removed old DOM search onkeyup based filtering


        // Toggle dropdown
window.toggleDropdown = function(emailId) {
            const dropdown = document.getElementById(`dropdown-${emailId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== `dropdown-${emailId}`) {
                    d.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        });

        // Date Filter Functions
window.toggleDateFilter = function() {
            const popup = document.getElementById('dateFilterPopup');
            popup.classList.toggle('hidden');
        }

window.closeDateFilter = function() {
            const popup = document.getElementById('dateFilterPopup');
            popup.classList.add('hidden');
        }

        // Helper to format date for display (e.g., "17 Feb 2026")
window.formatDisplayDate = function(dateStr) {
            if (!dateStr) return 'Select Date';
            const date = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Update the labels on the Bar
window.updateBarLabels = function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            const barStart = document.getElementById('barStartDate');
            const barEnd = document.getElementById('barEndDate');
            
            if(barStart) barStart.innerText = formatDisplayDate(startDate);
            if(barEnd) barEnd.innerText = formatDisplayDate(endDate);
        }

window.setPreset = function(type) {
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            const today = new Date();
            let start, end;

            // Helper to format date as YYYY-MM-DD for input fields
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
                startInput.value = formatInputDate(start);
                endInput.value = formatInputDate(end);
                
                updateBarLabels(); // Sync bar immediately

                // Update active state of preset buttons
                document.querySelectorAll('.preset-btn').forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.innerText.toLowerCase().replace(/\s/g, '') === type) {
                        btn.classList.add('active');
                    }
                });
            }
        }

window.applyDateFilter = function() {
            updateBarLabels(); // Sync bar labels
            closeDateFilter();
            
            // Trigger AJAX Real Load
            currentPage = 1;
            loadTableData();
        }

        // Initialize dates on load
        window.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            
            if(startInput && !startInput.value) startInput.value = today;
            if(endInput && !endInput.value) endInput.value = today;
            
            updateBarLabels();
            loadTableData(); // Initial JS Load
            
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    loadTableData();
                }, 300);
            });

            document.getElementById('perPageSelect').addEventListener('change', function() {
                currentPage = 1;
                loadTableData();
            });
        });

        let currentPage = 1;
        let debounceTimer;

        // Note: For demonstration since real attachments require DB logic we use a mock payload
        // But table rendering is real via AJAX
        let currentDataBucket = {}; 

window.loadTableData = function(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput').value;
            const perPage = document.getElementById('perPageSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const emailAddress = document.getElementById('emailAddress').value;
            const feature = document.getElementById('selectFeature').value; // if we can pass it, we can filter in backend too, ignored for now
            
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-400">Loading emails...</p></td></tr>`;

            const url = `/channel/email/history/get-data?page=${page}&search=${encodeURIComponent(search)}&per_page=${perPage}&start_date=${startDate}&end_date=${endDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Error loading data</p></td></tr>`;
                });
        }

window.renderTable = function(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';
            currentDataBucket = {}; // clear bucket

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No email history available</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-800/50 transition-colors even:bg-gray-900/40 relative z-0';

                const svc = item.email_service || '-';
                const addr = item.contact || item.email_address || '-';
                const subj = item.subject || '-';
                const agent = item.agent || item.agent_name || '-';
                const type = item.type || '-';
                const dt = item.created_at ? new Date(item.created_at).toLocaleString() : '-';
                const contentText = item.content || 'Content not available'; // Or handle real body
                const attachmentsMock = "[]"; // Replace with real if present
                
                // Store mapped data for modal viewing
                currentDataBucket[item.id] = {
                    subject: subj,
                    content: btoa(unescape(encodeURIComponent(contentText))),
                    attachments: attachmentsMock
                };

                let typeColorHtml = `<span class="text-gray-600 text-xs">-</span>`;
                if(type && type !== '-') {
                    const tc = type.toUpperCase() === 'OUT' ? 'bg-amber-500 text-white' : 'bg-blue-500 text-white';
                    typeColorHtml = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold ${tc}">${type}</span>`;
                }

                tr.innerHTML = `
                    <td class="px-3 py-3 text-gray-400 max-w-[160px]">
                        <div class="truncate" title="${svc}">${svc}</div>
                    </td>
                    <td class="px-3 py-3 text-cyan-400 max-w-[200px]">
                        <div class="truncate" title="${addr}">${addr}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-400 max-w-[180px]">
                        <div class="truncate" title="${subj}">${subj}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-400 max-w-[120px]">
                        <div class="truncate" title="${agent}">${agent}</div>
                    </td>
                    <td class="px-3 py-3 text-gray-500 text-xs whitespace-nowrap">${dt}</td>
                    <td class="px-3 py-3 whitespace-nowrap">${typeColorHtml}</td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        <div class="relative inline-block z-10">
                            <button onclick="toggleDropdown(${item.id})" class="text-gray-400 hover:text-white transition-colors">
                                <i class="bx bx-dots-vertical-rounded text-xl"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="dropdown-${item.id}" class="hidden absolute right-0 top-6 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-50">
                                <ul class="py-1 text-sm">
                                    <li>
                                        <a href="javascript:void(0)" onclick="viewEmailContent(${item.id})" class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors">
                                            <i class="bx bx-envelope text-base"></i>
                                            <span>File Email</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="viewAttachments(${item.id})" class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors">
                                            <i class="bx bx-paperclip text-base"></i>
                                            <span>Attachment</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

window.renderPagination = function(data) {
            const info = document.getElementById('dataTableInfo');
            const container = document.getElementById('paginationContainer');

            let from = data.from || 0;
            let to = data.to || 0;
            info.innerHTML = `Showing ${from} to ${to} of ${data.total} entries`;

            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Prev
            html += `<button onclick="loadTableData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Previous</button>`;
            
            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    if (i === data.current_page) {
                        html += `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg">${i}</button>`;
                    } else {
                        html += `<button onclick="loadTableData(${i})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">${i}</button>`;
                    }
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    html += `<span class="px-2 py-1 text-gray-500">...</span>`;
                }
            }

            // Next
            html += `<button onclick="loadTableData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Next</button>`;
            
            container.innerHTML = html;
        }

        // Close date filter popup when clicking outside
        document.addEventListener('click', function (event) {
            const popup = document.getElementById('dateFilterPopup');
            const button = document.getElementById('dateFilterBtn');

            if (popup && button && !popup.contains(event.target) && !button.contains(event.target)) {
                popup.classList.add('hidden');
            }
        });
        // View Email Content in new tab
window.viewEmailContent = function(id) {
            const stored = currentDataBucket[id];
            if (!stored) return;

            const subject = stored.subject;
            const content = atob(stored.content); // Decode base64

            const newTab = window.open('', '_blank');
            newTab.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${subject || 'Email Content'}</title>
                    <style>
                        body { 
                            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
                            line-height: 1.6; 
                            color: #333; 
                            max-width: 800px; 
                            margin: 40px auto; 
                            padding: 20px;
                            background: #f4f7f6;
                        }
                        .container {
                            background: white;
                            padding: 40px;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                        }
                        .header {
                            border-bottom: 1px solid #eee;
                            margin-bottom: 20px;
                            padding-bottom: 20px;
                        }
                        .subject { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .content { min-height: 200px; }
                    </style>

                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <div class="subject">${subject || '(No Subject)'}</div>
                        </div>
                        <div class="content">
                            ${content}
                        </div>
                    </div>
                </body>
                </html>
            `);
            newTab.document.close();
        }

        // Attachment Modal Functions
window.viewAttachments = function(id) {
            const stored = currentDataBucket[id];
            if (!stored) return;

            const subject = stored.subject;
            const attachments = JSON.parse(stored.attachments);

            const modal = document.getElementById('attachmentModal');
            const list = document.getElementById('attachmentList');
            const subjectLabel = document.getElementById('modalSubject');

            subjectLabel.textContent = subject || '(No Subject)';
            list.innerHTML = '';

            if (attachments.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <i class="bx bx-info-circle text-2xl mb-1"></i>
                        <p class="text-sm">No attachments found in this email.</p>
                    </div>
                `;
            } else {
                attachments.forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between p-3 bg-gray-900/50 rounded-lg border border-gray-700 hover:border-blue-500/50 transition-colors group';
                    item.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500/10 rounded flex items-center justify-center text-blue-400 group-hover:bg-blue-500/20 transition-colors">
                                <i class="bx ${getFileIcon(file.name)} text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-200">${file.name}</p>
                                <p class="text-xs text-gray-500">${file.size}</p>
                            </div>
                        </div>
                        <button class="p-2 text-gray-400 hover:text-blue-400 transition-colors" title="Download">
                            <i class="bx bx-download text-lg"></i>
                        </button>
                    `;
                    list.appendChild(item);
                });
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

window.closeAttachmentModal = function() {
            document.getElementById('attachmentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

window.getFileIcon = function(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            switch (ext) {
                case 'pdf': return 'bxs-file-pdf';
                case 'doc':
                case 'docx': return 'bxs-file-doc';
                case 'jpg':
                case 'jpeg':
                case 'png': return 'bxs-file-image';
                default: return 'bxs-file';
            }
        }
    





