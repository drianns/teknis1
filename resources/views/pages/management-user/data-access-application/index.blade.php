<x-dashonic-horizontal-layout>
    <div class="data-access-page min-h-screen bg-gray-900 text-gray-100 p-6">

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
        <div
            class="filter-section bg-gray-800/50 backdrop-blur-md border border-white/5 rounded-2xl p-6 mb-8 shadow-xl">
            <div class="filter-row flex flex-wrap items-end gap-6">
                <!-- Level User Dropdown -->
                <div class="filter-item">
                    <label for="level-user"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Level User</label>
                    <select id="level-user"
                        class="form-select bg-gray-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all min-w-[200px]"
                        onchange="filterByLevel(this.value)">
                        <option value="">Select Level</option>
                        <option value="layer1">layer1</option>
                        <option value="layer2">layer2</option>
                        <option value="layer3">layer3</option>
                        <option value="Admin">Admin</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </div>

                <!-- Setting Button -->
                <div class="filter-item filter-action ml-auto">
                    <button
                        class="btn-setting flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all shadow-lg hover:shadow-blue-500/20 transform hover:-translate-y-0.5"
                        onclick="openSettingModal()">
                        <i class='bx bx-cog text-xl'></i>
                        <span>Setting Data Menu Application</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div
            class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5">
            <!-- Table controls -->
            <div
                class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="show-entries flex items-center gap-3 text-sm text-gray-400">
                    <span>Show</span>
                    <select
                        class="bg-gray-900 border border-white/10 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:outline-none text-gray-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="search-box relative">
                    <input type="text" placeholder="Search data..."
                        class="bg-gray-900 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 focus:w-80 transition-all" />
                    <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-wrapper overflow-x-auto">
                <table class="data-table w-full text-left border-collapse">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                ID</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                Level User</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                Menu Level 1</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                Menu Level 2</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                Menu Level 3</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                User Create</th>
                            <th
                                class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">
                                Date Create</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap text-center">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody id="data-table-body" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class='bx bx-lock-open-alt text-5xl mb-3 opacity-20'></i>
                                    <p class="text-sm font-medium">No access data found</p>
                                    <p class="text-xs text-gray-600 mt-1">Use the Setting button above to assign menu access</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="table-pagination px-6 py-4 border-t border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="pagination-info text-sm text-gray-500">
                    Showing <span class="text-white font-bold">0</span> entries
                </div>
                <div class="pagination-controls flex gap-2">
                    <button disabled
                        class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 text-gray-400 text-sm opacity-50 cursor-not-allowed">Previous</button>
                    <button disabled
                        class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 text-gray-400 text-sm opacity-50 cursor-not-allowed">Next</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Setting Modal -->
    <div x-data="settingMenuData()" @setting-modal.window="open = true; resetForm()" class="relative z-50">
        <div x-show="open" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div x-show="open" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div @click.away="open = false"
                    class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20">
                                <i class='bx bx-cog text-xl'></i>
                            </div>
                            <h3 class="text-lg font-bold text-white leading-6">Form Setting Menu</h3>
                        </div>
                        <button @click="open = false" class="text-gray-500 hover:text-white transition-colors">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6 space-y-5">
                        <!-- Agent Role -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Agent
                                Role</label>
                            <div class="relative">
                                <select x-model="formData.agentRole"
                                    class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none">
                                    <option value="">Select Role</option>
                                    <option value="Kanmo">Kanmo</option>
                                    <option value="MP">MP</option>
                                    <option value="Nespresso">Nespresso</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Supervisor">Supervisor</option>
                                </select>
                                <i
                                    class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <!-- Menu Level 1 -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level
                                1</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel1"
                                    class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none"
                                    @change="updateMenuLevel2Options()">
                                    <option value="">Select Level 1</option>
                                    <option value="Master Data">Master Data</option>
                                    <option value="Apps">Apps</option>
                                    <option value="Dashboard">Dashboard</option>
                                    <option value="Management User">Management User</option>
                                    <option value="Master Customer">Master Customer</option>
                                    <option value="File Manager">File Manager</option>
                                    <option value="Report">Report</option>
                                    <option value="Channel">Channel</option>
                                </select>
                                <i
                                    class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <!-- Menu Level 2 -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level
                                2</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel2"
                                    class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed"
                                    @change="updateMenuLevel3Options()"
                                    :disabled="!formData.menuLevel1 || menuLevel2Options.length === 0">
                                    <option value="">Select Level 2</option>
                                    <template x-for="option in menuLevel2Options" :key="option">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>
                                <i
                                    class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <!-- Menu Level 3 -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Level
                                3</label>
                            <div class="relative">
                                <select x-model="formData.menuLevel3"
                                    class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="!formData.menuLevel2 || menuLevel3Options.length === 0">
                                    <option value="">Select Level 3</option>
                                    <template x-for="option in menuLevel3Options" :key="option">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>
                                <i
                                    class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                            <textarea x-model="formData.description"
                                class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all resize-none h-24"
                                placeholder="Enter description..."></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-900/50 border-t border-white/5 flex justify-end gap-3">
                        <button @click="open = false"
                            class="px-5 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-semibold">Cancel</button>
                        <button @click="saveMenuSetting()"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/20 transition-all text-sm font-bold flex items-center gap-2">
                            <i class='bx bx-save'></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-data="previewMenuData()" @preview-modal.window="open = true; loadPreviewData($event.detail.id)"
        class="relative z-50">
        <div x-show="open" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div x-show="open" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div @click.away="open = false"
                    class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-gray-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-400 border border-teal-500/20">
                                <i class='bx bx-show text-xl'></i>
                            </div>
                            <h3 class="text-lg font-bold text-white leading-6">Preview Access</h3>
                        </div>
                        <button @click="open = false" class="text-gray-500 hover:text-white transition-colors">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6 space-y-5">
                        <!-- Read-only Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2 col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Agent
                                    Role</label>
                                <input x-model="previewData.agentRole" readonly
                                    class="w-full bg-gray-900/50 border border-white/5 text-gray-300 text-sm rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Level
                                    1</label>
                                <input x-model="previewData.menuLevel1" readonly
                                    class="w-full bg-gray-900/50 border border-white/5 text-gray-300 text-sm rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Level
                                    2</label>
                                <input x-model="previewData.menuLevel2" readonly
                                    class="w-full bg-gray-900/50 border border-white/5 text-gray-300 text-sm rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Level
                                    3</label>
                                <input x-model="previewData.menuLevel3" readonly
                                    class="w-full bg-gray-900/50 border border-white/5 text-gray-300 text-sm rounded-xl px-4 py-3">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                                <textarea x-model="previewData.description" readonly
                                    class="w-full bg-gray-900/50 border border-white/5 text-gray-300 text-sm rounded-xl px-4 py-3 h-20 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div
                            class="bg-gray-900/30 rounded-xl p-4 border border-white/5 flex justify-between items-center text-xs">
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-bold mb-1">Created By</span>
                                <span class="text-blue-400 font-mono" x-text="previewData.userCreate"></span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-gray-500 font-bold mb-1">Created Date</span>
                                <span class="text-gray-300" x-text="previewData.dateCreate"></span>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-900/50 border-t border-white/5 flex justify-end">
                        <button @click="open = false"
                            class="px-6 py-2.5 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition-all text-sm font-semibold">Close
                            Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Section -->
    <script>
        const menuHierarchy = {
            "Master Data": {
                "Data Type": [],
                "Data Category": [],
                "Data Meta": []
            },
            "Apps": {
                "Ticketing Department": [],
                "Taskboard": [],
                "Thread System": [],
                "Ticketing": [],
                "History Ticketing": []
            },
            "Dashboard": [],
            "Management User": {
                "Data User Application": [],
                "Data Access Application": []
            },
            "Master Customer": {
                "Data Table Customer": [],
                "Data Customer": []
            },
            "File Manager": [],
            "Setup Channel Email": {
                "Dashboard Email": [],
                "Monitoring Email Response": []
            },
            "Channel": {
                "Email": {
                    "Inbox Email": [],
                    "History Email": []
                }
            }
        };

        function settingMenuData() {
            return {
                open: false,
                formData: {
                    agentRole: '',
                    menuLevel1: '',
                    menuLevel2: '',
                    menuLevel3: '',
                    description: ''
                },
                menuLevel2Options: [],
                menuLevel3Options: [],

                resetForm() {
                    this.formData = {
                        agentRole: '',
                        menuLevel1: '',
                        menuLevel2: '',
                        menuLevel3: '',
                        description: ''
                    };
                    this.menuLevel2Options = [];
                    this.menuLevel3Options = [];
                },

                updateMenuLevel2Options() {
                    const level1 = this.formData.menuLevel1;
                    this.formData.menuLevel2 = '';
                    this.formData.menuLevel3 = '';
                    this.menuLevel3Options = [];

                    if (level1 && menuHierarchy[level1]) {
                        // Check if it's an array or object
                        if (Array.isArray(menuHierarchy[level1])) {
                            this.menuLevel2Options = [];
                        } else {
                            this.menuLevel2Options = Object.keys(menuHierarchy[level1]);
                        }
                    } else {
                        this.menuLevel2Options = [];
                    }
                },

                updateMenuLevel3Options() {
                    const level1 = this.formData.menuLevel1;
                    const level2 = this.formData.menuLevel2;
                    this.formData.menuLevel3 = '';

                    if (level1 && level2 && menuHierarchy[level1][level2]) {
                        // Ensure it returns array for loop
                        const level3Data = menuHierarchy[level1][level2];
                        if (Array.isArray(level3Data)) {
                            this.menuLevel3Options = level3Data;
                        } else {
                            this.menuLevel3Options = Object.keys(level3Data);
                        }
                    } else {
                        this.menuLevel3Options = [];
                    }
                },

                saveMenuSetting() {
                    // Logic to save data to backend would go here
                    alert("Data Saved Successfully! (Simulation)");
                    this.open = false;
                    // Optional: Refresh table
                }
            };
        }

        function previewMenuData() {
            return {
                open: false,
                previewData: {
                    agentRole: '',
                    menuLevel1: '',
                    menuLevel2: '',
                    menuLevel3: '',
                    description: '',
                    userCreate: '',
                    dateCreate: ''
                },

                loadPreviewData(id) {
                    // Simulation of fetching data
                    this.previewData = {
                        agentRole: 'Administrator',
                        menuLevel1: 'Master Data',
                        menuLevel2: '-',
                        menuLevel3: '-',
                        description: 'Full access to master data configuration.',
                        userCreate: 'Agent1',
                        dateCreate: '10/15/2020 4:54:38 PM'
                    };
                }
            };
        }

        function openSettingModal() {
            window.dispatchEvent(new CustomEvent('setting-modal'));
        }

        function openPreview(id) {
            window.dispatchEvent(new CustomEvent('preview-modal', {
                detail: { id: id }
            }));
        }

        function deleteAccess(id) {
            if (confirm('Are you sure you want to delete this access?')) {
                alert('Access deleted!');
            }
        }

        function filterByLevel(level) {
            console.log("Filtering by level:", level);
        }
    </script>
</x-dashonic-horizontal-layout>