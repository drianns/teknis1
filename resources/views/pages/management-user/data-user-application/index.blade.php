<x-dashonic-horizontal-layout>
    <div class="data-user-application-page flex-1 flex flex-col h-full overflow-hidden bg-gray-900 text-gray-100 p-4 w-full"
        x-data="userModalData()">

        <!-- Page Header -->
        <header class="page-header mb-6 flex-shrink-0">
            <div class="header-left">
                <div class="title-with-action flex items-center gap-4 mb-2">
                    <h1 class="text-2xl font-bold text-white">Data User Application</h1>
                </div>
                <nav class="breadcrumb text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer">Management User</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Data User Application</span>
                </nav>
            </div>
        </header>

        <!-- Table Section -->
        <div
            class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">

            <!-- Table Controls -->
            <div
                class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                <div class="show-entries flex items-center gap-3 text-sm text-gray-400">
                    <span>Show</span>
                    <select id="entries-per-page"
                        class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 focus:border-blue-500 focus:outline-none text-gray-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="search-box relative">
                        <input type="text" id="table-search" placeholder="Search user..."
                            class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 focus:w-80 transition-all placeholder-gray-500" />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>

                    <button
                        class="btn-add px-4 py-2 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all"
                        @click="openAddUserModal()">
                        <i class='bx bx-plus text-lg'></i> Add User
                    </button>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-wrapper flex-1 overflow-auto w-full">
                <table class="data-table w-full text-left border-collapse table-fixed">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">ID</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">User Name</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-40 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Name</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Level User</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-48 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Email Address</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Group Agent</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-32 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Department</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Site</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-24 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-3 py-3 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                        <template x-for="user in users" :key="user.USERID">
                            <tr class="hover:bg-gray-700/30 transition-colors group">
                                <td class="px-3 py-3 font-mono text-xs text-blue-400" x-text="user.USERID"></td>
                                <td class="px-3 py-3 font-medium text-white" x-text="user.USERNAME"></td>
                                <td class="px-3 py-3" x-text="user.NAME"></td>
                                <td class="px-3 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" 
                                          :class="user.LEVELUSER === 'Administrator' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500'" 
                                          x-text="user.LEVELUSER"></span>
                                </td>
                                <td class="px-3 py-3 truncate text-gray-400" x-text="user.EMAIL_ADDRESS"></td>
                                <td class="px-3 py-3" x-text="user.ORGANIZATION || '-'"></td>
                                <td class="px-3 py-3" x-text="'-'"></td>
                                <td class="px-3 py-3 text-xs" x-text="'-'"></td>
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex w-2 h-2 rounded-full mr-1.5" :class="user.NA === 'Y' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-gray-600'"></span>
                                    <span x-text="user.NA === 'Y' ? 'Aktif' : 'Non-Aktif'"></span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <div x-data="{ openDropdown: false }" class="relative inline-block text-left">
                                        <button @click="openDropdown = !openDropdown" @click.outside="openDropdown = false" class="p-1.5 hover:bg-gray-600 rounded-lg text-gray-400 hover:text-white transition-all focus:outline-none">
                                            <i class='bx bx-dots-vertical-rounded text-lg'></i>
                                        </button>
                                        
                                        <div x-show="openDropdown" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-7 top-0 w-36 rounded-xl shadow-lg bg-gray-800 border border-gray-700 z-50 overflow-hidden"
                                             style="display: none;">
                                            <div class="py-1">
                                                <button @click="editUser(user); openDropdown = false" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 flex items-center gap-3 transition-colors">
                                                    <i class='bx bx-pencil text-gray-400 text-base'></i> Edit
                                                </button>
                                                <button @click="showFoto(user); openDropdown = false" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 flex items-center gap-3 transition-colors">
                                                    <i class='bx bxs-user text-gray-400 text-base'></i> Foto
                                                </button>
                                                <div class="border-t border-gray-700 my-1"></div>
                                                <button @click="previewUser(user); openDropdown = false" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 flex items-center gap-3 transition-colors">
                                                    <i class='bx bx-right-arrow-circle text-gray-400 text-base'></i> Preview
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="users.length === 0">
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class='bx bx-group text-5xl mb-3 opacity-20'></i>
                                        <p class="text-sm font-medium">No users found</p>
                                        <p class="text-xs text-gray-600 mt-1">Click "Add User" to create a new user account</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
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

        <!-- Foto Modal -->
        <div x-show="openFotoModal" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm" @click.self="openFotoModal = false" style="display: none;">
            <div x-show="openFotoModal" x-transition.scale.origin.center class="bg-gray-800 border border-gray-700/50 rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 text-center">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-white">User Photo</h3>
                    <button @click="openFotoModal = false" class="p-1.5 hover:bg-gray-700 rounded-lg text-gray-400 hover:text-white transition-all">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <template x-if="selectedFotoUrl">
                        <img :src="selectedFotoUrl" alt="User Photo" class="w-48 h-48 object-cover rounded-full border-4 border-gray-700 shadow-xl">
                    </template>
                    <template x-if="!selectedFotoUrl">
                        <div class="w-48 h-48 rounded-full border-4 border-gray-700 bg-gray-900 flex items-center justify-center flex-col text-gray-500 shadow-xl">
                            <i class='bx bxs-user text-6xl mb-2 hover:text-gray-400 transition-colors'></i>
                            <span class="text-sm font-medium">No Photo</span>
                        </div>
                    </template>
                </div>
                <p class="text-white font-semibold text-lg" x-text="selectedUserName"></p>
                <p class="text-gray-400 text-sm mt-1" x-text="selectedUserLevel"></p>
            </div>
        </div>

        <!-- Add/Edit User Modal -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="open = false">
            <div x-show="open" x-transition.scale.origin.center class="bg-gray-800 border border-gray-700/50 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700/50">
                    <h2 class="text-lg font-bold text-white" x-text="modalTitle"></h2>
                    <button @click="open = false" class="p-1.5 hover:bg-gray-700 rounded-lg text-gray-400 hover:text-white transition-all">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-5 space-y-4">

                    <!-- Row 1: User Name & Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">User Name</label>
                            <input type="text" x-model="formData.userName" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Name</label>
                            <input type="text" x-model="formData.name" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                    </div>

                    <!-- Row 2: Email & Password -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                            <input type="email" x-model="formData.email" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                            <input type="password" x-model="formData.password" :disabled="isPreview"
                                :placeholder="isEdit ? '(Kosongkan jika tidak diubah)' : ''"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                    </div>

                    <!-- Row 3: Level User (dari API) & Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Level User</label>
                            <select x-model="formData.levelUser" @change="handleLevelUserChange()" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors">
                                <option value="">-- Pilih Level User --</option>
                                <!-- ★ INI BAGIAN KUNCI: x-for loop dari data API -->
                                <template x-for="level in levelUserOptions" :key="level.LevelUserID">
                                    <option :value="level.Name" x-text="level.Description"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                            <select x-model="formData.status" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors">
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Department & Group Agent (conditional) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Department</label>
                            <input type="text" x-model="formData.department" :disabled="isPreview || !isDepartmentActive"
                                :class="!isDepartmentActive ? 'opacity-30 cursor-not-allowed' : ''"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Group Agent</label>
                            <input type="text" x-model="formData.groupAgent" :disabled="isPreview || !isGroupAgentActive"
                                :class="!isGroupAgentActive ? 'opacity-30 cursor-not-allowed' : ''"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                    </div>

                    <!-- Row 5: Site & Description -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Site</label>
                            <input type="text" x-model="formData.site" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Description</label>
                            <input type="text" x-model="formData.description" :disabled="isPreview"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-blue-500 disabled:opacity-50 transition-colors" />
                        </div>
                    </div>

                    <!-- Channel Agent Checkboxes -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Channel Agent</label>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="(label, key) in channelOptions" :key="key">
                                <label class="flex items-center gap-2 px-3 py-1.5 rounded-lg border cursor-pointer transition-all text-sm"
                                    :class="formData.channelAgent[key] ? 'bg-blue-500/10 border-blue-500/50 text-blue-400' : 'bg-gray-900 border-gray-700 text-gray-400'">
                                    <input type="checkbox" x-model="formData.channelAgent[key]" :disabled="isPreview" class="hidden" />
                                    <i class='bx text-sm' :class="formData.channelAgent[key] ? 'bx-check-square' : 'bx-checkbox'"></i>
                                    <span x-text="label"></span>
                                </label>
                            </template>
                        </div>
                        <div class="flex gap-2 mt-2" x-show="!isPreview">
                            <button @click="toggleAllChannels(true)" class="text-xs text-blue-400 hover:underline">Select All</button>
                            <span class="text-gray-600">|</span>
                            <button @click="toggleAllChannels(false)" class="text-xs text-gray-400 hover:underline">Deselect All</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-700/50">
                    <button @click="open = false"
                        class="px-4 py-2 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700 text-sm font-medium transition-colors">
                        <span x-text="isPreview ? 'Close' : 'Cancel'"></span>
                    </button>
                    <button x-show="!isPreview" @click="saveUser()"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all">
                        <i class='bx bx-save mr-1'></i>
                        <span x-text="isEdit ? 'Update' : 'Save'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Section -->
    <script>
        function userModalData() {
            return {
                open: false,
                profileOpen: false,
                isEdit: false,
                isPreview: false,
                users: [],
                levelUserOptions: [],   //  Data dropdown dari API master-data
                pagination: { total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null },
                search: '',
                limit: 10,
                
                formData: {
                    id: null,
                    userName: '',
                    name: '',
                    email: '',
                    password: '',
                    levelUser: '',
                    department: '',
                    groupAgent: '',
                    site: '',
                    status: 'Aktif',
                    channelAgent: {
                        email: false, wa: false, inbound: false, outbound: false,
                        instagram: false, facebook: false, twitter: false, telegram: false
                    },
                    description: '',
                    photoUrl: ''
                },

                channelOptions: {
                    email: 'Email', wa: 'WA', inbound: 'Inbound', outbound: 'Outbound',
                    instagram: 'Instagram', facebook: 'Facebook', twitter: 'Twitter', telegram: 'Telegram'
                },

                init() {
                    this.loadTable();
                    this.loadLevelUsers();  //  Fetch data Level User dari API saat halaman dimuat
                    this.$watch('search', () => this.loadTable());
                    this.$watch('limit', () => this.loadTable());
                },

                async loadTable(url = "{{ route('management-user.data-user-application.getData') }}") {
                    try {
                        const response = await fetch(`${url}${url.includes('?') ? '&' : '?'}search=${this.search}&limit=${this.limit}`);
                        const data = await response.json();
                        this.users = data.data;
                        this.pagination = {
                            total: data.total,
                            from: data.from,
                            to: data.to,
                            prev_page_url: data.prev_page_url,
                            next_page_url: data.next_page_url
                        };
                    } catch (error) {
                        console.error('Error loading users:', error);
                    }
                },

                //  Ambil data Level User dari API master-data
                async loadLevelUsers() {
                    try {
                        const response = await fetch('/api/master-data?TrxAction=UIDESK01');
                        const data = await response.json();
                        this.levelUserOptions = data;
                        // data berisi: [{LevelUserID: 1, Name: "layer1", Description: "Layer 1"}, ...]
                    } catch (error) {
                        console.error('Gagal memuat data Level User:', error);
                    }
                },

                toggleAllChannels(value) {
                    if (this.isPreview) return;
                    for (let key in this.formData.channelAgent) {
                        this.formData.channelAgent[key] = value;
                    }
                },

                get isDepartmentActive() {
                    return ['Supervisor', 'layer3'].includes(this.formData.levelUser);
                },

                get isGroupAgentActive() {
                    return ['layer1', 'layer2'].includes(this.formData.levelUser);
                },

                get modalTitle() {
                    if (this.isPreview) return 'Preview User Application';
                    if (this.isEdit) return 'Edit User Application';
                    return 'Form Add User Application';
                },

                resetForm() {
                    this.isEdit = false;
                    this.isPreview = false;
                    this.formData = {
                        id: null,
                        userName: '',
                        name: '',
                        email: '',
                        password: '',
                        levelUser: '',
                        department: '',
                        groupAgent: '',
                        site: '',
                        status: 'Aktif',
                        channelAgent: {
                            email: false, wa: false, inbound: false, outbound: false,
                            instagram: false, facebook: false, twitter: false, telegram: false
                        },
                        description: '',
                        photoUrl: ''
                    };
                },

                handleLevelUserChange() {
                    if (!this.isDepartmentActive) this.formData.department = '';
                    if (!this.isGroupAgentActive) this.formData.groupAgent = '';
                },

                openAddUserModal() {
                    this.resetForm();
                    this.open = true;
                },

                editUser(user) {
                    this.resetForm();
                    this.isEdit = true;
                    this.formData = {
                        id: user.USERID,
                        userName: user.USERNAME,
                        name: user.NAME,
                        email: user.EMAIL_ADDRESS,
                        password: '',
                        levelUser: user.LEVELUSER,
                        department: user.ORGANIZATION || '',
                        groupAgent: '',
                        site: '',
                        status: user.NA === 'Y' ? 'Aktif' : 'Non-Aktif',
                        channelAgent: {
                            email: false, wa: false, inbound: false, outbound: false,
                            instagram: false, facebook: false, twitter: false, telegram: false
                        },
                        description: user.Description || '',
                        photoUrl: ''
                    };
                    this.open = true;
                },

                previewUser(user) {
                    this.editUser(user);
                    this.isPreview = true;
                },

                async saveUser() {
                    if (this.isPreview) {
                        this.open = false;
                        return;
                    }

                    const url = this.isEdit 
                        ? `/management-user/data-user-application/${this.formData.id}` 
                        : "{{ route('management-user.data-user-application.store') }}";
                    
                    const method = this.isEdit ? 'PUT' : 'POST';

                    try {
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();
                        if (response.ok) {
                            alert(result.message);
                            this.open = false;
                            this.loadTable();
                        } else {
                            alert("Error: " + (result.message || "Failed to save user"));
                        }
                    } catch (error) {
                        console.error('Error saving user:', error);
                        alert("Network error occurred");
                    }
                },

                openFotoModal: false,
                selectedFotoUrl: null,
                selectedUserName: '',
                selectedUserLevel: '',

                showFoto(user) {
                    this.selectedFotoUrl = user.FotoAgent ? `/storage/${user.FotoAgent}` : null;
                    this.selectedUserName = user.NAME;
                    this.selectedUserLevel = user.LEVELUSER;
                    this.openFotoModal = true;
                },

                // Panggil action buttons dengan USERID
                previewUser(user) {
                    this.editUser(user);
                    this.isPreview = true;
                }
            };
        }
    </script>
</x-dashonic-horizontal-layout>