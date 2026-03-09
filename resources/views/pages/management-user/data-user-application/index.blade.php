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
                        <template x-for="user in users" :key="user.id">
                            <tr class="hover:bg-gray-700/30 transition-colors group">
                                <td class="px-3 py-3 font-mono text-xs text-blue-400" x-text="user.id"></td>
                                <td class="px-3 py-3 font-medium text-white" x-text="user.user_name"></td>
                                <td class="px-3 py-3" x-text="user.name"></td>
                                <td class="px-3 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" 
                                          :class="user.level_user === 'Administrator' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500'" 
                                          x-text="user.level_user"></span>
                                </td>
                                <td class="px-3 py-3 truncate text-gray-400" x-text="user.email"></td>
                                <td class="px-3 py-3" x-text="user.group_agent || '-'"></td>
                                <td class="px-3 py-3" x-text="user.department || '-'"></td>
                                <td class="px-3 py-3 text-xs" x-text="user.site || '-'"></td>
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex w-2 h-2 rounded-full mr-1.5" :class="user.status === 'Aktif' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-gray-600'"></span>
                                    <span x-text="user.status"></span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="previewUser(user)" class="p-1.5 hover:bg-gray-600 rounded-lg text-gray-400 hover:text-white transition-all"><i class='bx bx-show'></i></button>
                                        <button @click="editUser(user)" class="p-1.5 hover:bg-blue-500/20 rounded-lg text-blue-400 hover:text-blue-300 transition-all"><i class='bx bx-edit'></i></button>
                                        <button @click="deleteUser(user.id)" class="p-1.5 hover:bg-red-500/20 rounded-lg text-red-400 hover:text-red-300 transition-all"><i class='bx bx-trash'></i></button>
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

        <!-- Add/Edit User Modal -->
        <!-- ... (Existing modal code remains, just showing script update below) ... -->

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
                        id: user.id,
                        userName: user.user_name,
                        name: user.name,
                        email: user.email,
                        password: '',
                        levelUser: user.level_user,
                        department: user.department || '',
                        groupAgent: user.group_agent || '',
                        site: user.site || '',
                        status: user.status,
                        channelAgent: user.channels || {
                            email: false, wa: false, inbound: false, outbound: false,
                            instagram: false, facebook: false, twitter: false, telegram: false
                        },
                        description: user.description || '',
                        photoUrl: user.photo_url || ''
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

                async deleteUser(id) {
                    if (!confirm('Are you sure you want to delete this user?')) return;

                    try {
                        const response = await fetch(`/management-user/data-user-application/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();
                        if (response.ok) {
                            alert(result.message);
                            this.loadTable();
                        } else {
                            alert("Error: " + result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting user:', error);
                    }
                }
            };
        }
    </script>
</x-dashonic-horizontal-layout>