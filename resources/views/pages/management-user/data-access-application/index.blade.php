<x-dashonic-horizontal-layout sidebar="1" with-sidebar="{{ request()->get('with-sidebar') ?? 1 }}"
    with-header="{{ request()->get('with-header') ?? 1 }}" with-footer="{{ request()->get('with-footer') ?? 0 }}">
    
    <div x-data="accessTableData()" class="data-access-page min-h-screen bg-gray-900 text-gray-100 p-6">
        <!-- Page Header -->
        <header class="page-header mb-8">
            <div class="header-left">
                <h1 class="text-2xl font-bold text-white mb-2">Data Access Application</h1>
                <nav class="breadcrumb text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer">Management User</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Data Access Application</span>
                </nav>
            </div>
        </header>

        <!-- Filter Section -->
        <div class="filter-section bg-gray-800/50 backdrop-blur-md border border-white/5 rounded-2xl p-6 mb-8 shadow-xl">
            <div class="filter-row flex flex-wrap items-end gap-6">
                <!-- Level User Dropdown -->
                <div class="filter-item">
                    <label for="level-user" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Level User</label>
                    <select id="level-user" x-model="filterLevel"
                        class="form-select bg-gray-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all min-w-[200px]">
                        <option value="">Select Level</option>
                        <option value="layer1">layer1</option>
                        <option value="layer2">layer2</option>
                        <option value="layer3">layer3</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </div>

                <!-- Setting Button -->
                <div class="filter-item filter-action ml-auto">
                    <button @click="openSettingModal()"
                        class="btn-setting flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all shadow-lg hover:shadow-blue-500/20 transform hover:-translate-y-0.5">
                        <i class='bx bx-cog text-xl'></i>
                        <span>Setting Data Menu Application</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5">
            <!-- Table controls -->
            <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="show-entries flex items-center gap-3 text-sm text-gray-400">
                    <span>Show</span>
                    <select x-model="limit" class="bg-gray-900 border border-white/10 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:outline-none text-gray-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="search-box relative">
                    <input type="text" x-model="search" placeholder="Search data..."
                        class="bg-gray-900 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 focus:w-80 transition-all" />
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-wrapper overflow-x-auto">
                <table class="data-table w-full text-left border-collapse">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Level User</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu Level 1</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu Level 2</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Menu Level 3</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">User Create</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Date Create</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        <template x-for="item in accesses" :key="item.id">
                            <tr class="hover:bg-gray-700/30 transition-colors group">
                                <td class="px-3 py-3 font-mono text-xs text-blue-400" x-text="item.id"></td>
                                <td class="px-3 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-500" x-text="item.level_user"></span>
                                </td>
                                <td class="px-3 py-3" x-text="item.menu_level1"></td>
                                <td class="px-3 py-3" x-text="item.menu_level2 || '-'"></td>
                                <td class="px-3 py-3" x-text="item.menu_level3 || '-'"></td>
                                <td class="px-3 py-3 text-gray-400" x-text="item.created_by"></td>
                                <td class="px-3 py-3 text-gray-500 text-xs" x-text="new Date(item.created_at).toLocaleString()"></td>
                                <td class="px-3 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openPreview(item)" class="p-1.5 hover:bg-gray-600 rounded-lg text-gray-400 hover:text-white transition-all"><i class='bx bx-show'></i></button>
                                        <button @click="deleteAccess(item.id)" class="p-1.5 hover:bg-red-500/20 rounded-lg text-red-400 hover:text-red-300 transition-all"><i class='bx bx-trash'></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="accesses.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class='bx bx-lock-open-alt text-5xl mb-3 opacity-20'></i>
                                        <p class="text-sm font-medium">No access data found</p>
                                        <p class="text-xs text-gray-600 mt-1">Use the Setting button above to assign menu access</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-pagination px-6 py-4 border-t border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="pagination-info text-sm text-gray-500">
                    Showing <span class="text-white font-bold" x-text="pagination.from || 0"></span> to <span class="text-white font-bold" x-text="pagination.to || 0"></span> of <span class="text-white font-bold" x-text="pagination.total"></span> entries
                </div>
                <div class="pagination-controls flex gap-2">
                    <button @click="loadTable(pagination.prev_page_url)" :disabled="!pagination.prev_page_url"
                        class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all">Previous</button>
                    <button @click="loadTable(pagination.next_page_url)" :disabled="!pagination.next_page_url"
                        class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all">Next</button>
                </div>
            </div>
        </div>

        <!-- Setting Modal -->
        <div x-show="open" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div x-show="open" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div @click.away="open = false"
                    class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20">
                                <i class='bx bx-cog text-xl'></i>
                            </div>
                            <h3 class="text-lg font-bold text-white leading-6">Form Setting Menu</h3>
                        </div>
                        <button @click="open = false" class="text-gray-500 hover:text-white transition-colors">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 space-y-5">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Agent Role</label>
                            <div class="relative">
                                <select x-model="formData.agentRole" class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none">
                                    <option value="">Select Role</option>
                                    <option value="layer1">layer1</option>
                                    <option value="layer2">layer2</option>
                                    <option value="layer3">layer3</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Supervisor">Supervisor</option>
                                </select>
                                <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level 1</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel1" class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none" @change="updateMenuLevel2Options()">
                                    <option value="">Select Level 1</option>
                                    <template x-for="(sub, name) in menuHierarchy" :key="name">
                                        <option :value="name" x-text="name"></option>
                                    </template>
                                </select>
                                <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level 2</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel2" class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed" @change="updateMenuLevel3Options()" :disabled="!formData.menuLevel1 || menuLevel2Options.length === 0">
                                    <option value="">Select Level 2</option>
                                    <template x-for="option in menuLevel2Options" :key="option">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>
                                <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level 3</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel3" class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed" :disabled="!formData.menuLevel2 || menuLevel3Options.length === 0">
                                    <option value="">Select Level 3</option>
                                    <template x-for="option in menuLevel3Options" :key="option">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>
                                <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                            <textarea x-model="formData.description" class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all resize-none h-24" placeholder="Enter description..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-900/50 border-t border-white/5 flex justify-end gap-3">
                        <button @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-semibold">Cancel</button>
                        <button @click="saveMenuSetting()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/20 transition-all text-sm font-bold flex items-center gap-2">
                            <i class='bx bx-save'></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <div x-show="previewOpen" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div x-show="previewOpen" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div @click.away="previewOpen = false"
                    class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all w-full max-w-md"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-400 border border-teal-500/20">
                                <i class='bx bx-show text-xl'></i>
                            </div>
                            <h3 class="text-lg font-bold text-white leading-6">Preview Access</h3>
                        </div>
                        <button @click="previewOpen = false" class="text-gray-500 hover:text-white transition-colors">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="col-span-2 space-y-1">
                                <span class="text-gray-500 text-[10px] font-bold uppercase">Agent Role</span>
                                <div class="text-white bg-gray-900/50 px-4 py-2.5 rounded-xl border border-white/5" x-text="previewData.level_user"></div>
                            </div>
                            <div class="space-y-1">
                                <span class="text-gray-500 text-[10px] font-bold uppercase">Menu L1</span>
                                <div class="text-white bg-gray-900/50 px-4 py-2.5 rounded-xl border border-white/5" x-text="previewData.menu_level1"></div>
                            </div>
                            <div class="space-y-1">
                                <span class="text-gray-500 text-[10px] font-bold uppercase">Menu L2</span>
                                <div class="text-white bg-gray-900/50 px-4 py-2.5 rounded-xl border border-white/5" x-text="previewData.menu_level2 || '-'"></div>
                            </div>
                            <div class="col-span-2 space-y-1">
                                <span class="text-gray-500 text-[10px] font-bold uppercase">Menu L3</span>
                                <div class="text-white bg-gray-900/50 px-4 py-2.5 rounded-xl border border-white/5" x-text="previewData.menu_level3 || '-'"></div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <span class="text-gray-500 text-[10px] font-bold uppercase">Description</span>
                            <div class="text-gray-300 bg-gray-900/50 px-4 py-3 rounded-xl border border-white/5 text-xs min-h-[60px]" x-text="previewData.description || 'No description'"></div>
                        </div>

                        <div class="flex justify-between items-center text-[10px] text-gray-500 pt-2">
                            <span>By: <span class="text-blue-400 font-mono" x-text="previewData.created_by"></span></span>
                            <span x-text="new Date(previewData.created_at).toLocaleString()"></span>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-900/50 border-t border-white/5 flex justify-end">
                        <button @click="previewOpen = false" class="px-6 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition-all text-xs font-bold">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const menuHierarchy = {
            "Master Data": { "Data Type": [], "Data Category": [], "Data Meta": [] },
            "Apps": { "Ticketing Department": [], "Taskboard": [], "Thread System": [], "Ticketing": [], "History Ticketing": [] },
            "Dashboard": [],
            "Management User": { "Data User Application": [], "Data Access Application": [] },
            "Master Customer": { "Data Table Customer": [], "Data Customer": [] },
            "File Manager": [],
            "Setup Channel Email": { "Dashboard Email": [], "Monitoring Email Response": [] },
            "Channel": { "Email": { "Inbox Email": [], "History Email": [] } },
            "Report": []
        };

        function accessTableData() {
            return {
                accesses: [],
                pagination: { total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null },
                search: '',
                filterLevel: '',
                limit: 10,
                open: false,
                previewOpen: false,
                formData: { agentRole: '', menuLevel1: '', menuLevel2: '', menuLevel3: '', description: '' },
                previewData: {},
                menuLevel2Options: [],
                menuLevel3Options: [],

                init() {
                    this.loadTable();
                    this.$watch('search', () => this.loadTable());
                    this.$watch('filterLevel', () => this.loadTable());
                    this.$watch('limit', () => this.loadTable());
                },

                async loadTable(url = "{{ route('management-user.data-access-application.getData') }}") {
                    try {
                        const response = await fetch(`${url}${url.includes('?') ? '&' : '?'}search=${this.search}&level_user=${this.filterLevel}&limit=${this.limit}`);
                        const data = await response.json();
                        this.accesses = data.data;
                        this.pagination = { total: data.total, from: data.from, to: data.to, prev_page_url: data.prev_page_url, next_page_url: data.next_page_url };
                    } catch (error) {
                        console.error('Error loading accesses:', error);
                    }
                },

                openSettingModal() {
                    this.resetForm();
                    this.open = true;
                },

                resetForm() {
                    this.formData = { agentRole: '', menuLevel1: '', menuLevel2: '', menuLevel3: '', description: '' };
                    this.menuLevel2Options = [];
                    this.menuLevel3Options = [];
                },

                updateMenuLevel2Options() {
                    const l1 = this.formData.menuLevel1;
                    this.formData.menuLevel2 = '';
                    this.formData.menuLevel3 = '';
                    this.menuLevel2Options = (l1 && !Array.isArray(menuHierarchy[l1])) ? Object.keys(menuHierarchy[l1]) : [];
                    this.menuLevel3Options = [];
                },

                updateMenuLevel3Options() {
                    const l1 = this.formData.menuLevel1;
                    const l2 = this.formData.menuLevel2;
                    this.formData.menuLevel3 = '';
                    const sub = menuHierarchy[l1][l2];
                    this.menuLevel3Options = Array.isArray(sub) ? sub : (sub ? Object.keys(sub) : []);
                },

                async saveMenuSetting() {
                    if (!this.formData.agentRole || !this.formData.menuLevel1) return alert("Please fill required fields");
                    try {
                        const response = await fetch("{{ route('management-user.data-access-application.store') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify(this.formData)
                        });
                        const result = await response.json();
                        if (response.ok) { alert(result.message); this.open = false; this.loadTable(); }
                        else alert("Error: " + result.message);
                    } catch (error) { console.error('Error saving access:', error); }
                },

                openPreview(item) {
                    this.previewData = item;
                    this.previewOpen = true;
                },

                async deleteAccess(id) {
                    if (!confirm('Are you sure you want to delete this access?')) return;
                    try {
                        const response = await fetch(`/management-user/data-access-application/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });
                        const result = await response.json();
                        if (response.ok) { alert(result.message); this.loadTable(); }
                        else alert("Error: " + result.message);
                    } catch (error) { console.error('Error deleting access:', error); }
                }
            };
        }
    </script>
</x-dashonic-horizontal-layout>