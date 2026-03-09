<x-dashonic-horizontal-layout>
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none export-user-application-page">

        <!-- Page Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Export Data User</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Management User</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold tracking-wide">Export User Application</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 overflow-hidden w-full gap-4">

            <!-- Drag & Drop Group Zone Wrapper -->
            <div class="bg-gray-800/80 rounded-xl border border-gray-700/50 p-4 shrink-0 shadow-sm"
                x-data="dragDropData()">
                <div id="drop-zone"
                    class="drop-zone border border-dashed border-gray-500 rounded-lg px-4 py-2 flex items-center min-h-[46px] transition-all duration-300 relative group"
                    :class="{ 'border-blue-500 bg-blue-900/10 shadow-[0_0_15px_rgba(59,130,246,0.1)]': isDraggingOver }"
                    @dragover.prevent="isDraggingOver = true" @dragleave.prevent="isDraggingOver = false"
                    @drop="handleDrop($event)">

                    <!-- Empty State -->
                    <div class="drop-zone-placeholder flex items-center gap-3 text-gray-400 transition-opacity duration-300 text-sm"
                        x-show="groupedColumns.length === 0">
                        <i class='bx bx-move text-lg'></i>
                        <span>Drag a column header here to group by that column</span>
                    </div>

                    <!-- Grouped Columns Display -->
                    <div class="grouped-columns flex flex-wrap gap-2 items-center w-full"
                        x-show="groupedColumns.length > 0" x-cloak>
                        <div class="text-xs font-semibold text-gray-400 tracking-wider mr-2">GROUPED BY:</div>
                        <template x-for="(column, index) in groupedColumns" :key="column">
                            <div
                                class="grouped-column-chip flex items-center gap-1.5 px-2.5 py-1 bg-gray-900 border border-gray-600 text-gray-300 rounded-md text-xs font-medium shadow-sm animate-fade-in-up">
                                <span x-text="column"></span>
                                <button @click="removeGroupedColumn(index)"
                                    class="btn-remove hover:text-red-400 transition-colors ml-1">
                                    <i class='bx bx-x text-sm'></i>
                                </button>
                                <i class='bx bx-chevron-right text-gray-500 ml-1'
                                    x-show="index < groupedColumns.length - 1"></i>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Data Table Container -->
            <div
                class="table-container bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl ring-1 ring-white/5 flex-1 flex flex-col min-h-0 overflow-hidden">
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar" x-data="exportTableData()" x-init="init()">
                    <table class="data-table w-full text-left border-collapse table-fixed min-w-[1000px]">
                        <thead class="bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th draggable="true" @dragstart="handleDragStart($event, 'User Name')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-40 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>User Name</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Name')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-48 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Name</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Level User')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-32 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Level User</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Email Address')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-64 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Email Address</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Department')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-48 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Department</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Group')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-32 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-r border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Group</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                                <th draggable="true" @dragstart="handleDragStart($event, 'Status')"
                                    @dragend="handleDragEnd($event)"
                                    class="sticky top-0 z-10 bg-gray-900 draggable-header px-3 py-3 w-24 hover:bg-gray-700/50 transition-colors group cursor-grab active:cursor-grabbing border-l border-gray-700/50 whitespace-nowrap">
                                    <div class="header-content flex items-center justify-between">
                                        <span>Status</span>
                                        <i class='bx bx-grid-vertical text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity'></i>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <template x-for="user in users" :key="user.id">
                                <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                    <td class="px-3 py-3 whitespace-nowrap truncate" x-text="user.name"></td>
                                    <td class="px-3 py-3 whitespace-nowrap truncate" x-text="user.name"></td>
                                    <td class="px-3 py-3 whitespace-nowrap truncate">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full" 
                                              :class="user.role && user.role.name.toLowerCase().includes('admin') ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : (user.role && (user.role.name.toLowerCase().includes('spv') || user.role.name.toLowerCase().includes('supervisor')) ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-teal-500/20 text-teal-400 border border-teal-500/30')"
                                              x-text="user.role ? (user.role.name.toLowerCase().includes('admin') ? 'Administrator' : (user.role.name.toLowerCase().includes('spv') || user.role.name.toLowerCase().includes('supervisor') ? 'Supervisor' : 'Layer 1')) : 'Layer 1'">
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap truncate" x-text="user.email"></td>
                                    <td class="px-3 py-3 whitespace-nowrap truncate" x-text="user.company ? user.company.name : 'N/A'"></td>
                                    <td class="px-3 py-3 whitespace-nowrap truncate">-</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="users.length === 0">
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-500 border-b border-gray-800">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class='bx bx-folder-open text-4xl text-gray-600'></i>
                                            <p>No user data available</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination & Controls Footer -->
                <div class="px-6 py-4 border-t border-gray-700/50 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-800/30 shrink-0" x-data="{}" x-init="">
                    <template x-if="true">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
                        <!-- Left: Info -->
                        <div class="text-sm font-medium text-gray-400 hidden lg:block">
                            Showing <span class="text-white" x-text="pagination.from || 0"></span> to <span
                                class="text-white" x-text="pagination.to || 0"></span> of <span
                                class="text-white" x-text="pagination.total"></span> results
                        </div>

                        <!-- Center: Pagination Links -->
                        <div class="flex items-center gap-1 bg-gray-900 rounded-lg p-1 border border-gray-700">
                             <button @click="loadTable(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" class="px-3 py-1 text-xs font-medium text-gray-400 hover:text-white disabled:opacity-50 transition-colors">Prev</button>
                             <button @click="loadTable(pagination.next_page_url)" :disabled="!pagination.next_page_url" class="px-3 py-1 text-xs font-medium text-gray-400 hover:text-white disabled:opacity-50 transition-colors">Next</button>
                        </div>

                        <!-- Right: Page Size & Export Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Page Size -->
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Show</label>
                                <select x-model="limit" @change="loadTable()"
                                    class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 appearance-none py-1.5 pl-3 pr-8 cursor-pointer outline-none transition-colors hover:border-gray-600">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <div class="w-px h-6 bg-gray-700"></div>

                            <!-- Export Action -->
                            <div class="flex items-center gap-2" x-data="{ format: 'excel' }">
                                <select id="export-format" x-model="format"
                                    class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 appearance-none py-1.5 pl-3 pr-8 cursor-pointer outline-none transition-colors hover:border-gray-600 min-w-[100px]">
                                    <option value="excel">Excel (.xlsx)</option>
                                    <option value="csv">CSV (.csv)</option>
                                    <option value="pdf">PDF (.pdf)</option>
                                    <option value="json">JSON (.json)</option>
                                </select>

                                <button type="button" @click="exportData(format)"
                                    class="flex items-center gap-2 px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-lg transition-all border border-blue-500 shadow-[0_0_10px_rgba(37,99,235,0.2)] hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] active:scale-95 group">
                                    <i
                                        class='bx bx-cloud-download text-lg group-hover:-translate-y-0.5 transition-transform'></i>
                                    <span>Export</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="export-loading-overlay"
        class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 flex-col items-center justify-center gap-4 hidden opacity-0 transition-opacity duration-300">
        <div
            class="w-16 h-16 border-4 border-blue-500/30 border-t-blue-500 rounded-full animate-spin shadow-[0_0_15px_rgba(59,130,246,0.5)]">
        </div>
        <h3 class="text-xl font-bold text-white tracking-wide">Generating Export File...</h3>
        <p class="text-sm text-gray-400 font-medium tracking-wide">Please don't close this window.</p>
    </div>

    @push('styles')
        <style>
            /* Draggable visual styling */
            .draggable-header.dragging {
                opacity: 0.4;
                background-color: #1e3a8a !important;
                /* blue-900 */
                border: 1px dashed #3b82f6;
            }

            /* Custom scrollbar for tables */
            .custom-scrollbar::-webkit-scrollbar {
                height: 8px;
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #111827;
                /* gray-900 */
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #374151;
                /* gray-700 */
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #4b5563;
                /* gray-600 */
            }

            /* Select dropdown arrow replacement */
            select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path শাস্ত্র>%3C/svg%3E");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.25rem 1.25rem;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // Register grouped columns array globally so the export button can access it
                window.exportGroupedColumns = [];
            });

            function dragDropData() {
                return {
                    groupedColumns: [],
                    isDraggingOver: false,

                    init() {
                        this.$watch('groupedColumns', value => {
                            window.exportGroupedColumns = value;
                            this.applyGrouping();
                        });
                    },

                    handleDrop(event) {
                        this.isDraggingOver = false;
                        const columnName = event.dataTransfer.getData('text/plain');

                        if (columnName && !this.groupedColumns.includes(columnName)) {
                            this.groupedColumns.push(columnName);
                        }

                        document.querySelectorAll('.draggable-header').forEach(el => {
                            el.classList.remove('dragging');
                        });
                    },

                    removeGroupedColumn(index) {
                        this.groupedColumns.splice(index, 1);
                    },

                    applyGrouping() {
                        console.log('Currently grouped by:', this.groupedColumns);
                        if (this.groupedColumns.length > 0) {
                            const tableBody = document.querySelector('tbody');
                            tableBody.style.opacity = '0.5';
                            setTimeout(() => tableBody.style.opacity = '1', 300);
                        }
                    }
                };
            }

            function exportTableData() {
                return {
                    users: [],
                    pagination: {},
                    limit: 10,
                    search: '',

                    init() {
                        this.loadTable();
                    },

                    async loadTable(url = '{{ route('management-user.export.user.application.getData') }}') {
                        try {
                            const params = new URLSearchParams({
                                limit: this.limit,
                                search: this.search
                            });

                            const fetchUrl = url.includes('?') ? `${url}&${params.toString()}` : `${url}?${params.toString()}`;

                            const response = await fetch(fetchUrl, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            if (!response.ok) throw new Error('Failed to fetch data');

                            const data = await response.json();
                            this.users = data.data;
                            this.pagination = {
                                current_page: data.current_page,
                                last_page: data.last_page,
                                prev_page_url: data.prev_page_url,
                                next_page_url: data.next_page_url,
                                from: data.from,
                                to: data.to,
                                total: data.total
                            };
                        } catch (error) {
                            console.error('Error loading table:', error);
                        }
                    },

                    handleDragStart(event, columnName) {
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', columnName);
                        event.target.closest('th').classList.add('dragging');
                    },
                    handleDragEnd(event) {
                        event.target.closest('th').classList.remove('dragging');
                    }
                };
            }

            // Global export function called by the export button
            function exportData(format) {
                const overlay = document.getElementById('export-loading-overlay');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Show loading overlay
                overlay.classList.remove('hidden');
                // Small delay to allow display:block to apply before animating opacity
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);

                fetch('{{ route("management-user.export.user.application.download") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json, application/pdf, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/csv'
                    },
                    body: JSON.stringify({
                        format: format,
                        grouped_by: window.exportGroupedColumns || []
                    })
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Export generation failed');

                        // For JSON, handle text differently
                        if (format === 'json') {
                            return response.json().then(data => {
                                const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                                return { blob, filename: `user_export_${Date.now()}.json` };
                            });
                        }

                        // Get content disposition filename if available, else generate one
                        let filename = `user_export_${Date.now()}.${format === 'excel' ? 'xlsx' : format}`;
                        const disposition = response.headers.get('content-disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            const matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) {
                                filename = matches[1].replace(/['"]/g, '');
                            }
                        }

                        return response.blob().then(blob => ({ blob, filename }));
                    })
                    .then(({ blob, filename }) => {
                        // Create download link and trigger click
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();

                        // Cleanup
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                        // Hide overlay
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    })
                    .catch(error => {
                        console.error('Export Error:', error);
                        alert('Export failed. Please check the console for details.');

                        // Hide overlay on error
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    });
            }
        </script>
    @endpush
</x-dashonic-horizontal-layout>