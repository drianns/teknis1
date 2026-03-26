@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900 border-none setting-agent-email-page" x-data="agentEmailPage()">
        <!-- Header -->
        <header class="flex-shrink-0 mb-3 px-6 pt-4 flex justify-between items-start">
            <div id="setting-agent-email-config" class="hidden" data-ajax-url="{{ route('setting.agent.email.data') }}"></div>
            <div class="header-left">
                <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Setting Agent Email</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Setup Channel Email</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Setting Agent Email</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex-1 p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full h-full">
            <div class="flex flex-col bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl h-full min-h-0 relative">
                
                <!-- Top Toolbar inside Panel -->
                <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-800/50">
                    <div class="flex items-center gap-4">
                        <button onclick="openAgentEmailModal()"
                            class="px-5 py-2 flex items-center gap-2 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-full text-white text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <i class='bx bx-plus text-blue-400 font-bold'></i> Add Agent Channel Email
                        </button>

                        <div class="flex items-center gap-2 text-xs text-gray-400 ml-4 border-l border-gray-700 pl-4">
                            <span>Show</span>
                            <select x-model="perPage" @change="loadTable(1)" class="bg-gray-800 border-gray-700 rounded-lg px-2 py-1 text-xs text-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                    </div>

                    <div class="relative w-72">
                        <input type="text" x-model="searchQuery" @input.debounce.500ms="loadTable(1)"
                            class="bg-gray-800 border-gray-700 rounded-full pl-4 pr-10 py-1.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500 w-full placeholder-gray-500 shadow-inner"
                            placeholder="Email or User Search" />
                        <i class='bx bx-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-500'></i>
                    </div>
                </div>

                <!-- User Cards Grid -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                    <template x-if="isLoading">
                        <div class="flex flex-col items-center justify-center py-24 gap-4">
                            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-gray-400 font-medium animate-pulse">Loading Agents...</p>
                        </div>
                    </template>

                    <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                        <template x-for="user in users" :key="user.id">
                            <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-500/50 transition-all flex flex-col items-center p-6 pt-8 relative group cursor-default">
                                
                                <!-- Hover Actions Overlay -->
                                <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-row items-center justify-center gap-3 backdrop-blur-[2px] z-10 rounded-2xl pointer-events-none group-hover:pointer-events-auto">
                                    <button @click="editAgentEmailModal(user)" title="Edit" 
                                        class="w-10 h-10 rounded-[10px] bg-blue-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-600 hover:-translate-y-1">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>

                                    <button @click="deleteAgentEmail(user.id)" title="Delete"
                                        class="w-10 h-10 rounded-[10px] bg-red-500 shadow-lg text-white flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 delay-75 hover:bg-red-600 hover:-translate-y-1">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>

                                <!-- Not Login Badge -->
                                <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm z-0">
                                    Not Login
                                </div>

                                <!-- Avatar -->
                                <div class="w-24 h-24 flex-shrink-0 rounded-full bg-teal-500/20 mb-4 flex items-center justify-center overflow-hidden border-4 border-gray-800 shadow-sm relative group-hover:border-blue-500/30 transition-colors z-0">
                                    <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=2dd4bf&color=fff&size=128`" :alt="user.name" class="w-full h-full object-cover">
                                </div>
                                
                                <!-- User Info -->
                                <h3 class="text-white font-semibold text-center text-[15px] mb-1 px-2 truncate w-full group-hover:text-blue-400 transition-colors z-0" x-text="user.name"></h3>
                                
                                <p class="text-gray-400 text-[11px] text-center truncate w-full px-2 mb-4 z-0" x-text="user.email"></p>

                                <div class="bg-blue-500 text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-sm select-none z-0 hover:-translate-y-0.5 transition-transform">
                                    Max Handle 20 - Now Handle 0
                                </div>
                            </div>
                        </template>

                        <div x-show="!isLoading && users.length === 0" class="col-span-full flex flex-col items-center justify-center p-12 text-gray-500 bg-gray-800/50 rounded-2xl border border-gray-700/50 border-dashed">
                            <i class='bx bx-user-x text-5xl mb-3 opacity-50'></i>
                            <p>No agents found.</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination Bottom Bar -->
                <div x-show="!isLoading && pagination.last_page > 1" class="px-6 py-4 border-t border-gray-800 bg-gray-800/20 flex justify-between items-center">
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        Showing <span class="text-white" x-text="pagination.from || 0"></span> to <span class="text-white" x-text="pagination.to || 0"></span> of <span class="text-blue-500" x-text="pagination.total"></span> agents
                    </div>
                    <div class="flex gap-1">
                        <button @click="loadTable(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                            class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 hover:text-white transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                            <i class="bx bx-chevron-left text-xl"></i>
                        </button>
                        
                        <template x-for="i in Math.min(5, pagination.last_page)" :key="i">
                            <button @click="loadTable(i)"
                                :class="pagination.current_page === i ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 hover:text-white'"
                                class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold transition-all" x-text="i">
                            </button>
                        </template>

                        <button @click="loadTable(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page"
                            class="w-9 h-9 rounded-lg flex items-center justify-center bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 hover:text-white transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                            <i class="bx bx-chevron-right text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal for Setting Agent Email -->
    <div id="agent-email-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        x-data="agentEmailModalData()" x-show="open" @agent-email-modal.window="open = true; initData($event.detail)"
        style="display: none;">

        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="open = false" x-show="open"
            x-transition.opacity></div>

        <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-5xl relative z-10 flex flex-col overflow-hidden max-h-[90vh]"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700 bg-gray-800 rounded-t-xl">
                <h3 class="text-lg font-bold text-gray-200" x-text="isEditMode ? 'Edit Setting Agent Email' : 'Add Agent Channel Email'"></h3>
                <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Body Area -->
            <div class="p-4 sm:p-6 bg-gray-800/30 flex flex-col overflow-hidden flex-1">
                
                <!-- Top Dropdowns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4">
                    <!-- Dropdown Account Email -->
                    <div class="space-y-1.5" x-data="{ openEmailDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">Select Account Email</label>
                        <div class="relative">
                            <button type="button" @click="openEmailDropdown = !openEmailDropdown"
                                @click.outside="openEmailDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="formData.account_email || 'Select'" class="truncate" :class="!formData.account_email ? 'text-gray-400' : ''"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openEmailDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openEmailDropdown" style="display: none;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200]">
                                <div @click="formData.account_email = ''; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Select</div>
                                <div @click="formData.account_email = 'club.indonesia@nespresso.co.id'; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">club.indonesia@nespresso.co.id</div>
                                <div @click="formData.account_email = 'support@kanmogroup.com'; openEmailDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">support@kanmogroup.com</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Max Distribution Data -->
                    <div class="space-y-1.5" x-data="{ openDistDropdown: false }">
                        <label class="font-semibold text-blue-300 text-sm">Select Maximal Distribution Data</label>
                        <div class="relative">
                            <button type="button" @click="openDistDropdown = !openDistDropdown"
                                @click.outside="openDistDropdown = false"
                                class="w-full bg-gray-800 border border-gray-600 focus:border-blue-500 text-white rounded-md px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all text-left shadow-sm">
                                <span x-text="formData.max_distribution || 'Select'" class="truncate" :class="!formData.max_distribution ? 'text-gray-400' : ''"></span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform' :class="openDistDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDistDropdown" style="display: none; max-height: 12rem;"
                                class="absolute left-0 top-full mt-1 w-full bg-gray-800 border border-gray-600 rounded-md shadow-xl z-[200] max-h-48 overflow-y-auto dropdown-scroll">
                                <div @click="formData.max_distribution = ''; openDistDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors">Select</div>
                                <template x-for="num in [10, 20, 30, 40, 50, 60, 120, 0, 1, 55, 70]" :key="num">
                                    <div @click="formData.max_distribution = num; openDistDropdown = false" class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-600 text-white transition-colors" x-text="num"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Line -->
                <hr class="border-gray-700/50 mb-4">

                <!-- Table Container (Taskboard Style) -->
                <div class="flex-1 overflow-hidden flex flex-col border border-gray-700/50 rounded-xl bg-gray-900 shadow-sm relative min-h-[250px]">
                    
                    <!-- Table Controls -->
                    <div class="px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex justify-between items-center gap-4">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-900 border border-gray-700/50 rounded-xl">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Show</span>
                            <select x-model="modalPerPage" @change="loadModalData(1)" class="bg-transparent border-none text-blue-400 text-sm font-bold focus:ring-0 cursor-pointer p-0 pr-6 pl-1 outline-none">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">entries</span>
                        </div>

                        <div class="relative w-full sm:w-64 group">
                            <span class="absolute left-min text-[10px] font-bold text-gray-500 uppercase -translate-x-12 top-1/2 -translate-y-1/2">Search:</span>
                            <input type="text" x-model="modalSearchQuery" @input.debounce.500ms="loadModalData(1)"
                                class="w-full bg-gray-900 border border-gray-700/50 text-white text-sm rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all outline-none placeholder-gray-600 shadow-inner"
                                placeholder="">
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-800 sticky top-0 z-10 border-b border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 w-12 text-center border-r border-gray-700/30">
                                        <div class="flex justify-center flex-col items-center">
                                            <i class="bx bx-sort text-[10px] text-gray-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">ID <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">Agent <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap border-r border-gray-700/30">
                                        <div class="flex items-center justify-between gap-1">Name <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                    <th class="px-4 py-3 text-[11px] font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap">
                                        <div class="flex items-center justify-between gap-1">Email <i class="bx bx-sort text-[10px] text-gray-500"></i></div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <template x-if="isModalLoading">
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center text-gray-500 italic animate-pulse">Loading users...</td>
                                    </tr>
                                </template>

                                <template x-for="mUser in modalUsers" :key="mUser.id">
                                    <tr class="hover:bg-blue-500/[0.05] transition-colors cursor-pointer" 
                                        @click="toggleSelection(mUser.id)">
                                        
                                        <td class="px-4 py-3 text-center border-r border-gray-700/30">
                                            <div class="flex justify-center items-center h-full">
                                                <div class="w-5 h-5 rounded border border-gray-600 flex items-center justify-center transition-all bg-gray-800"
                                                    :class="selectedUsers.includes(mUser.id) ? 'bg-blue-500 border-blue-500' : ''">
                                                    <i class="bx bx-check text-white text-sm" x-show="selectedUsers.includes(mUser.id)"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm font-medium text-gray-300" x-text="mUser.id"></span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm font-medium text-white" x-text="mUser.username || mUser.name.replace(/\s+/g, '_').toLowerCase()"></span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap border-r border-gray-700/30">
                                            <span class="text-sm text-gray-300" x-text="mUser.name"></span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-400" x-text="mUser.email"></span>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="!isModalLoading && modalUsers.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">No users found.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Pagination Bottom Bar -->
                    <div x-show="!isModalLoading && modalPagination.last_page > 1" class="px-4 py-2 border-t border-gray-800 bg-gray-800/20 flex justify-between items-center shrink-0">
                        <div class="text-[9px] font-bold text-gray-500 uppercase">
                            Page <span class="text-white" x-text="modalPagination.current_page"></span> of <span x-text="modalPagination.last_page"></span>
                        </div>
                        <div class="flex gap-1">
                            <button @click="loadModalData(modalPagination.current_page - 1)" :disabled="modalPagination.current_page === 1"
                                class="w-7 h-7 rounded bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 disabled:opacity-30">
                                <i class="bx bx-chevron-left"></i>
                            </button>
                            <button @click="loadModalData(modalPagination.current_page + 1)" :disabled="modalPagination.current_page === modalPagination.last_page"
                                class="w-7 h-7 rounded bg-gray-800 text-gray-500 hover:bg-gray-700 border border-gray-700/50 disabled:opacity-30">
                                <i class="bx bx-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl gap-4">
                <button @click="open = false"
                    class="px-8 py-2.5 rounded-full bg-[#f43f5e] hover:bg-rose-600 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none">
                    Cancel
                </button>
                <button @click="saveAgentEmail()"
                    class="px-8 py-2.5 rounded-full bg-[#60a5fa] hover:bg-blue-500 text-white font-semibold text-sm transition-shadow shadow hover:shadow-lg focus:outline-none min-w-[120px] flex justify-center">
                    <span>Submit</span>
                </button>
            </div>
        </div>
    </div>


    
    @push('styles')
        @vite('resources/css/pages/setup-channel-email/setting-agent-email.css')
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @vite('resources/js/pages/setup-channel-email/setting-agent-email.js')
    @endpush
@endsection

