@extends('layouts.app')
@section('content')
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
        <div class="relative z-50" x-show="open" style="display: none;">
            <div x-show="open" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div @click.away="open = false"
                        class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <!-- Modal Header -->
                        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-gray-900/50">
                            <h3 class="text-lg font-bold text-white leading-6" x-text="modalTitle"></h3>
                            <button @click="open = false" class="text-gray-500 hover:text-white transition-colors">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-6 space-y-6">

                            <!-- Form Grid (3 columns) -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <!-- Row 1 -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">User
                                        Name</label>
                                    <input type="text" x-model="formData.userName" :disabled="isPreview"
                                        class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                                        placeholder="User Name" />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Name</label>
                                    <input type="text" x-model="formData.name" :disabled="isPreview"
                                        class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                                        placeholder="Name" />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Email
                                        Address</label>
                                    <input type="email" x-model="formData.email" :disabled="isPreview"
                                        class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                                        placeholder="Email Address" />
                                </div>

                                <!-- Row 2 -->
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Password</label>
                                    <input type="password" x-model="formData.password"
                                        class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all"
                                        placeholder="Password" />
                                </div>

                                <!-- Level User - TRIGGERS CONDITIONAL LOGIC -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Level
                                        User</label>
                                    <div class="relative">
                                        <select x-model="formData.levelUser" @change="handleLevelUserChange()"
                                            :disabled="isPreview"
                                            class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:bg-gray-800 disabled:text-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Select</option>
                                            <option value="layer1">Layer 1</option>
                                            <option value="layer2">Layer 2</option>
                                            <option value="layer3">Layer 3</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Supervisor">Supervisor</option>
                                        </select>
                                        <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'
                                            :class="{'opacity-50': isPreview}"></i>
                                    </div>
                                </div>

                                <!-- Department - CONDITIONAL -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider"
                                        :class="{'opacity-50': !isDepartmentActive || isPreview}">Department</label>
                                    <div class="relative">
                                        <select x-model="formData.department"
                                            :disabled="!isDepartmentActive || isPreview"
                                            class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:bg-gray-800 disabled:text-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Select</option>
                                            <option value="CRC">CRC</option>
                                            <option value="IT">IT</option>
                                            <option value="Aftersales Service">Aftersales Service</option>
                                            <option value="CX Ops">CX Ops</option>
                                            <option value="Finance">Finance</option>
                                            <option value="MD">MD</option>
                                            <option value="Membership">Membership</option>
                                            <option value="Warehouse">Warehouse</option>
                                            <option value="OPS Logistic">OPS Logistic</option>
                                            <option value="Tech Team">Tech Team</option>
                                            <option value="Store COACH">Store COACH</option>
                                            <option value="Store GINGERSNAPS">Store GINGERSNAPS</option>
                                            <option value="Store HAVAIANAS">Store HAVAIANAS</option>
                                            <option value="Store JUSTICE">Store JUSTICE</option>
                                            <option value="Store KANMO AIRPORT">Store KANMO AIRPORT</option>
                                        </select>
                                        <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'
                                            :class="{'opacity-50': !isDepartmentActive || isPreview}"></i>
                                    </div>
                                </div>

                                <!-- Row 3 -->
                                <!-- Group Agent - CONDITIONAL -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider"
                                        :class="{'opacity-50': !isGroupAgentActive || isPreview}">Group Agent</label>
                                    <div class="relative">
                                        <select x-model="formData.groupAgent"
                                            :disabled="!isGroupAgentActive || isPreview"
                                            class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:bg-gray-800 disabled:text-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Select</option>
                                            <option value="Kanmo">Kanmo</option>
                                            <option value="MP">MP</option>
                                            <option value="Nespresso">Nespresso</option>
                                        </select>
                                        <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'
                                            :class="{'opacity-50': !isGroupAgentActive || isPreview}"></i>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Site</label>
                                    <div class="relative">
                                        <select x-model="formData.site" :disabled="isPreview"
                                            class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:bg-gray-800 disabled:text-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Select</option>
                                            <option value="Jakarta">Jakarta</option>
                                            <option value="Surabaya">Surabaya</option>
                                            <option value="Bandung">Bandung</option>
                                        </select>
                                        <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'
                                            :class="{'opacity-50': isPreview}"></i>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Status</label>
                                    <div class="relative">
                                        <select x-model="formData.status" :disabled="isPreview"
                                            class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none disabled:bg-gray-800 disabled:text-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Select</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non Aktif">Non Aktif</option>
                                        </select>
                                        <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'
                                            :class="{'opacity-50': isPreview}"></i>
                                    </div>
                                </div>

                            </div>

                            <!-- Channel Data Agent (Only for Layer 1) -->
                            <div x-show="formData.levelUser === 'layer1'" x-transition
                                class="col-span-1 md:col-span-3 mt-4 border border-blue-500/20 rounded-xl overflow-hidden bg-gray-900/30">
                                <div
                                    class="px-5 py-3 flex justify-between items-center border-b border-white/5 bg-blue-500/5">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-broadcast text-blue-400 text-lg'></i>
                                        <h4 class="text-white font-bold text-sm tracking-wide">Channel Data Agent</h4>
                                    </div>
                                    <div class="flex gap-2">
                                        <button @click="toggleAllChannels(true)"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 hover:text-blue-300 transition-all border border-blue-500/20">Select
                                            All</button>
                                        <button @click="toggleAllChannels(false)"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-700/50 text-gray-400 hover:bg-gray-700 hover:text-white transition-all border border-white/5">Clear</button>
                                    </div>
                                </div>
                                <div class="p-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <template x-for="(label, key) in channelOptions" :key="key">
                                        <label
                                            class="relative flex items-center p-3 rounded-xl border border-white/5 bg-gray-800/50 hover:bg-blue-500/5 hover:border-blue-500/30 cursor-pointer group transition-all duration-200"
                                            :class="{'border-blue-500/50 bg-blue-500/10': formData.channelAgent[key]}">
                                            <div class="relative flex items-center justify-center w-5 h-5 rounded border border-gray-600 bg-gray-900 transition-all group-hover:border-blue-400"
                                                :class="{'bg-blue-500 border-blue-500': formData.channelAgent[key]}">
                                                <input type="checkbox" x-model="formData.channelAgent[key]"
                                                    :disabled="isPreview"
                                                    class="absolute opacity-0 w-full h-full cursor-pointer" />
                                                <i class="bx bx-check text-white text-sm opacity-0 transform scale-50 transition-all duration-200"
                                                    :class="{'opacity-100 scale-100': formData.channelAgent[key]}"></i>
                                            </div>
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-400 group-hover:text-white transition-colors"
                                                :class="{'text-white font-semibold': formData.channelAgent[key]}"
                                                x-text="label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Description (Full width) -->
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                                <div class="flex gap-1 p-2 bg-gray-800 border border-white/10 rounded-t-xl border-b-0">
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/5 text-gray-400 hover:text-white transition-colors"><i
                                            class='bx bx-bold'></i></button>
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/5 text-gray-400 hover:text-white transition-colors"><i
                                            class='bx bx-italic'></i></button>
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/5 text-gray-400 hover:text-white transition-colors"><i
                                            class='bx bx-list-ul'></i></button>
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/5 text-gray-400 hover:text-white transition-colors"><i
                                            class='bx bx-at'></i></button>
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/5 text-gray-400 hover:text-white transition-colors"><i
                                            class='bx bx-link'></i></button>
                                </div>
                                <textarea x-model="formData.description" :disabled="isPreview"
                                    class="w-full bg-gray-900 border border-white/10 text-gray-200 text-sm rounded-b-xl rounded-tr-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all resize-none h-32 disabled:opacity-60 disabled:cursor-not-allowed"
                                    placeholder="Enter description..."></textarea>
                            </div>

                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-gray-900/50 border-t border-white/5 flex justify-end gap-3">
                            <button @click="open = false"
                                class="px-5 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-semibold">
                                <span x-text="isPreview ? 'Close' : 'Cancel'"></span>
                            </button>
                            <button x-show="!isPreview" @click="saveUser()"
                                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/20 transition-all text-sm font-bold flex items-center gap-2">
                                <i class='bx bx-save'></i> <span x-text="isEdit ? 'Save Changes' : 'Save'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Modal -->
        <div class="relative z-50" x-show="profileOpen" style="display: none;">
            <div x-show="profileOpen" class="fixed inset-0 bg-gray-900/90 backdrop-blur-md transition-opacity"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <div @click.away="profileOpen = false"
                        class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all w-full max-w-sm"
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                        <div class="absolute top-4 right-4 z-10">
                            <button @click="profileOpen = false"
                                class="w-8 h-8 rounded-full bg-black/50 hover:bg-black/70 flex items-center justify-center text-white transition-all backdrop-blur-sm">
                                <i class='bx bx-x text-xl'></i>
                            </button>
                        </div>

                        <div class="p-8 flex flex-col items-center">
                            <div class="w-32 h-32 rounded-full border-4 border-blue-500/30 p-1 mb-6 relative group">
                                <div class="w-full h-full rounded-full overflow-hidden relative">
                                    <img :src="formData.photoUrl || 'https://ui-avatars.com/api/?name=' + formData.name + '&background=random'"
                                        alt="Profile Photo" class="w-full h-full object-cover">
                                </div>
                                <div
                                    class="absolute inset-0 rounded-full border-2 border-blue-400 animate-pulse opacity-50">
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-white mb-1" x-text="formData.name"></h3>
                            <p class="text-sm text-blue-400 font-medium bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20 mb-4"
                                x-text="formData.levelUser"></p>

                            <div class="w-full space-y-3 bg-gray-900/50 rounded-xl p-4 border border-white/5">
                                <div class="flex items-center gap-3 text-gray-300 text-sm">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-gray-500">
                                        <i class='bx bx-envelope'></i>
                                    </div>
                                    <span x-text="formData.email"></span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300 text-sm">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-gray-500">
                                        <i class='bx bx-map'></i>
                                    </div>
                                    <span x-text="formData.site || '-'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div id="data-user-config" class="hidden" data-get="{{ route('management-user.data-user-application.getData') }}" data-store="{{ route('management-user.data-user-application.store') }}"></div>
    @push('scripts')
        @vite('resources/js/pages/management-user/data-user-application.js')
    @endpush
@endsection
