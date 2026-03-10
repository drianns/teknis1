<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Monitoring Email Response
    </x-slot>

    <style>
        /* Date Range Bar (V2) */
        .date-range-bar {
            display: flex;
            align-items: center;
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 14px;
            padding: 4px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            gap: 2px;
        }

        .date-range-bar:hover {
            background: rgba(31, 41, 55, 0.8);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .date-range-bar:active {
            transform: translateY(0);
        }

        .date-segment {
            display: flex;
            flex-direction: column;
            padding: 8px 16px;
            min-width: 120px;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .date-segment:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .segment-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #60a5fa;
            margin-bottom: 2px;
        }

        .segment-value {
            font-size: 13px;
            font-weight: 600;
            color: #f3f4f6;
            white-space: nowrap;
        }

        .date-divider {
            width: 1px;
            height: 24px;
            background: rgba(75, 85, 99, 0.4);
            margin: 0 4px;
        }

        .bar-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 20px;
        }

        .bar-chevron {
            padding-right: 12px;
            color: #4b5563;
            font-size: 18px;
        }

        /* Modern Popup */
        .modern-popup {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            width: 340px;
        }

        .popup-label {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .popup-input {
            width: 100%;
            background: #111827;
            border: 1px solid #374151;
            color: white;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            transition: all 200ms ease;
            outline: none;
        }

        .popup-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* Quick Presets Grid */
        .preset-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .preset-btn {
            padding: 8px;
            background: #111827;
            border: 1px solid #374151;
            border-radius: 8px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 200ms ease;
            text-align: center;
        }

        .preset-btn:hover {
            border-color: #3b82f6;
            color: white;
            background: #2563eb10;
        }

        .preset-btn.active {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }
    </style>

    <div class="min-h-screen bg-gray-900" x-data="monitoringEmail()">
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header & Action Section -->
            <div
                class="flex flex-col md:flex-row md:items-end md:justify-between mb-6 gap-4 border-b border-gray-800 pb-4">
                <div>
                    <h1 class="text-2xl font-semibold text-white flex items-center gap-3">
                        Data Monitoring Email Response
                        <button @click="openDateBar = true" class="text-gray-400 hover:text-blue-400 transition-colors focus:outline-none">
                            <i class='bx bx-cog text-2xl'></i>
                        </button>
                    </h1>
                    <nav class="flex text-sm text-gray-400 mt-1">
                        <a href="#" class="hover:text-blue-400">Apps</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-300">Monitoring Email Response</span>
                    </nav>
                </div>
            </div>

            <!-- Filter Modal (Triggered by Gear Icon) -->
            <div x-show="openDateBar" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="openDateBar = false"></div>
                <div x-show="openDateBar" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-gray-800 rounded-2xl shadow-2xl border border-white/10 w-full max-w-lg flex flex-col z-10 overflow-hidden text-left">
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-900 border-b border-gray-700">
                        <h5 class="text-blue-400 font-bold text-lg m-0 flex items-center gap-2">Filter Monitoring Email</h5>
                        <button type="button" class="text-gray-400 hover:text-white transition-colors p-1" @click="openDateBar = false">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="relative">
                            <select x-model="filters.emailAccount" class="w-full bg-gray-900 border border-gray-700 text-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Select</option>
                                @foreach($emailServices as $service)
                                    <option value="{{ $service->name }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                            <i class="bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                        <div class="relative">
                            <input type="date" x-model="filters.startDate" class="w-full bg-gray-900 border border-gray-700 text-gray-400 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all cursor-pointer placeholder-gray-500">
                        </div>
                        <div class="relative">
                            <input type="date" x-model="filters.endDate" class="w-full bg-gray-900 border border-gray-700 text-gray-400 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all cursor-pointer placeholder-gray-500">
                        </div>
                    </div>
                    <div class="p-5 bg-gray-900/30 border-t border-gray-700 flex justify-between gap-4">
                        <button @click="openDateBar = false" class="px-8 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/50 text-rose-400 font-bold rounded-full transition-all tracking-wide">
                            Close
                        </button>
                        <button @click="applyFilters(); openDateBar = false" class="px-8 py-2.5 bg-blue-500 hover:bg-blue-600 border border-transparent text-white font-bold rounded-full transition-all tracking-wide shadow-lg shadow-blue-500/30">
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gray-800 rounded-xl shadow-lg border border-white/5 overflow-hidden">
                <!-- Table Controls -->
                <div
                    class="p-6 border-b border-gray-700/50 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center text-gray-400 text-sm gap-2">
                            <span>Show</span>
                            <select x-model="perPage" @change="refreshData()"
                                class="bg-gray-900 border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 outline-none transition-all">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-500 text-lg"></i>
                        </div>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="applySearch()"
                            class="bg-gray-900 border border-gray-700 text-gray-300 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full pl-10 p-3 outline-none transition-all"
                            placeholder="Search emails...">
                    </div>
                </div>

                <!-- Main Table -->
                <div class="relative overflow-hidden">
                    <!-- Loading Overlay -->
                    <div x-show="isLoading" class="absolute inset-0 bg-gray-900/60 backdrop-blur-[2px] z-[60] flex items-center justify-center transition-opacity duration-300" style="display: none;">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 shadow-lg shadow-blue-500/20"></div>
                            <p class="text-blue-400 font-bold animate-pulse tracking-widest text-xs uppercase">Loading data...</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('email_service')">
                                    <div class="flex items-center gap-1">
                                        Email Service <i class="bx bx-sort" :class="getSortIcon('email_service')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('from')">
                                    <div class="flex items-center gap-1">
                                        From <i class="bx bx-sort" :class="getSortIcon('from')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('subject')">
                                    <div class="flex items-center gap-1">
                                        Subject <i class="bx bx-sort" :class="getSortIcon('subject')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('status')">
                                    <div class="flex items-center gap-1 text-center justify-center">
                                        Status <i class="bx bx-sort" :class="getSortIcon('status')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('agent')">
                                    <div class="flex items-center gap-1">
                                        Agent <i class="bx bx-sort" :class="getSortIcon('agent')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold cursor-pointer hover:text-white transition-colors"
                                    @click="sortBy('created_at')">
                                    <div class="flex items-center gap-1">
                                        Date Create <i class="bx bx-sort" :class="getSortIcon('created_at')"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 bg-transparent min-h-[200px]">
                            <template x-if="!isLoading && displayedEmails.length === 0">
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bx bx-info-circle text-5xl mb-4 text-gray-600"></i>
                                            <p class="text-lg font-medium text-gray-400">No results found</p>
                                            <p class="text-sm mt-1 text-gray-500">Try adjusting your filters or search query</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="email in displayedEmails" :key="email.id">
                                <tr class="hover:bg-blue-500/[0.03] transition-colors border-b border-gray-700/50 last:border-none group">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-gray-300 font-medium" x-text="email.email_service"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-300" x-text="email.from"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 max-w-sm">
                                        <p class="text-gray-200 line-clamp-2" x-text="email.subject" :title="email.subject"></p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-[11px] font-bold text-white shadow-sm transition-all"
                                            :class="email.status === 'Response' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20'" x-text="email.status">
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-gray-400 font-medium" x-text="email.agent"></span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-gray-300" x-text="formatDate(email.created_at)"></span>
                                            <span class="text-gray-400" x-text="formatTime(email.created_at)"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center relative" x-data="{ open: false }">
                                        <div class="relative flex justify-center">
                                            <button @click.stop="open = !open" @click.outside="open = false"
                                                class="w-8 h-8 rounded-lg bg-gray-900 border border-gray-700/50 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all shadow-sm">
                                                <i class="bx bx-dots-vertical-rounded text-lg"></i>
                                            </button>

                                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                class="absolute right-full top-0 mr-2 w-40 bg-gray-900 border border-gray-700/50 rounded-xl shadow-2xl z-20 overflow-hidden ring-1 ring-white/5"
                                                style="display: none;">
                                                <button @click="handleAssign(email.id); open = false"
                                                    class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 border-b border-gray-700/30 transition-colors">
                                                    <i class="bx bx-share-alt text-lg text-blue-500"></i> <span class="font-bold">Assign</span>
                                                </button>
                                                <button class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 border-b border-gray-700/30 transition-colors" title="Preview coming soon">
                                                    <i class="bx bx-hide text-lg text-amber-500"></i> <span class="font-bold">Preview</span>
                                                </button>
                                                <button @click="handleConversation(email.id); open = false"
                                                    class="w-full text-left px-4 py-2.5 text-xs text-gray-300 hover:bg-blue-500/10 hover:text-blue-400 flex items-center gap-3 transition-colors">
                                                    <i class="bx bx-message-rounded-dots text-lg text-emerald-500"></i> <span class="font-bold">Conversation</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div
                    class="px-6 py-4 border-t border-gray-700/50 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-400">
                        Showing <span class="text-white font-medium" x-text="pagination.start"></span> to
                        <span class="text-white font-medium" x-text="pagination.end"></span> of
                        <span class="text-white font-medium" x-text="pagination.total"></span> entries
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="prevPage()" :disabled="currentPage === 1"
                            class="px-4 py-2 bg-gray-700/50 hover:bg-gray-700 text-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Previous
                        </button>
                        <div class="flex items-center gap-1 mx-2">
                            <template x-for="p in pagination.totalPages" :key="p">
                                <button @click="goToPage(p)"
                                    class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium transition-all"
                                    :class="currentPage === p ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                                    x-text="p"></button>
                            </template>
                        </div>
                        <button @click="nextPage()" :disabled="currentPage === pagination.totalPages"
                            class="px-4 py-2 bg-gray-700/50 hover:bg-gray-700 text-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Removed Filter Modal as it's now inline in the table -->
        <!-- Modals Container -->
        <div id="modals-placeholder">
            <!-- Assign Modal -->
            <div id="assign-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                <div id="assign-backdrop"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300"></div>
                <div id="assign-content"
                    class="bg-gray-800 rounded-2xl shadow-2xl border border-white/10 w-full max-w-md flex flex-col transform transition-all duration-300 scale-75 opacity-0 z-10 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-900 border-b border-gray-700">
                        <h5 class="text-white font-bold text-lg m-0 flex items-center gap-2">
                            <i class="bx bx-user-plus text-blue-500"></i> Assign Agent
                        </h5>
                        <button type="button"
                            class="text-gray-400 hover:text-white transition-colors bg-gray-800 p-1 rounded-lg"
                            onclick="closeAssignModal()">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="bx bx-user"></i> User Agent
                            </label>
                            <select id="assign-agent"
                                class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all cursor-pointer">
                                <option value="">Select Agent</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="bx bx-comment-detail"></i> Alasan Assign
                            </label>
                            <textarea id="assign-reason" rows="4" placeholder="Masukkan alasan penugasan..."
                                class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all resize-none"></textarea>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-900/30 border-t border-gray-700 flex gap-4">
                        <button onclick="closeAssignModal()"
                            class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all">Cancel</button>
                        <button onclick="submitAssignAction()"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-600/20">OK</button>
                    </div>
                </div>
            </div>

            <!-- Conversation Modal -->
            <div id="conversation-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                <div id="conversation-backdrop"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300"></div>
                <div id="conversation-content"
                    class="bg-gray-800 rounded-2xl shadow-2xl border border-white/10 w-full max-w-2xl max-h-[85vh] flex flex-col transform transition-all duration-300 scale-75 opacity-0 z-10 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-900 border-b border-gray-700">
                        <h5 class="text-white font-bold text-lg m-0 flex items-center gap-2">
                            <i class="bx bx-chat text-indigo-500"></i> Conversation History
                        </h5>
                        <button type="button"
                            class="text-gray-400 hover:text-white transition-colors bg-gray-800 p-1 rounded-lg"
                            onclick="closeConversationModal()">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>
                    <div class="p-8 overflow-y-auto space-y-8 bg-gray-800/50 scrollbar-hide">
                        <div class="relative pl-8 border-l-2 border-gray-700 space-y-10 py-2">
                            <!-- No conversation logic yet -->
                            <p class="text-gray-500 text-sm italic">No conversation history available.</p>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-900/30 border-t border-gray-700 flex justify-end">
                        <button onclick="closeConversationModal()"
                            class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all">Close
                            History</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Store/Logic -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal Helper Functions (Global Scope for simplicity with Inline HTML onclick)
        window.openAssignModal = function (id) {
            const modal = document.getElementById('assign-modal');
            const content = document.getElementById('assign-content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-75', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        window.closeAssignModal = function () {
            const modal = document.getElementById('assign-modal');
            const content = document.getElementById('assign-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-75', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        };

        window.submitAssignAction = function () {
            const agent = document.getElementById('assign-agent').value;
            if (!agent) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select a User Agent.',
                    icon: 'error',
                    background: '#1f2937',
                    color: '#fff',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            Swal.fire({
                title: 'Success!',
                text: `Assigned to ${agent} successfully.`,
                icon: 'success',
                background: '#1f2937',
                color: '#fff',
                confirmButtonColor: '#3b82f6'
            });
            closeAssignModal();
        };

        window.openConversationModal = function (id) {
            const modal = document.getElementById('conversation-modal');
            const content = document.getElementById('conversation-content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-75', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        window.closeConversationModal = function () {
            const modal = document.getElementById('conversation-modal');
            const content = document.getElementById('conversation-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-75', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        };

        document.addEventListener('click', function (e) {
            if (e.target.id === 'assign-backdrop') closeAssignModal();
            if (e.target.id === 'conversation-backdrop') closeConversationModal();
        });

        function monitoringEmail() {
            return {
                isLoading: false,
                perPage: 10,
                currentPage: 1,
                searchQuery: '',
                sortCol: 'created_at',
                sortDir: 'desc',
                emails: [],
                displayedEmails: [],
                openDateBar: false,
                activePreset: '',
                pagination: {
                    start: 0,
                    end: 0,
                    total: 0,
                    totalPages: 1
                },
                filters: {
                    emailAccount: '',
                    startDate: '',
                    endDate: ''
                },

                formatDateForBar(dateStr) {
                    if (!dateStr) return 'Select Date';
                    const date = new Date(dateStr);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
                },

                setPreset(type) {
                    this.activePreset = type;
                    const today = new Date();
                    let start, end;

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
                        this.filters.startDate = formatInputDate(start);
                        this.filters.endDate = formatInputDate(end);
                        this.applyFilters();
                    }
                },

                init() {
                    this.fetchData();
                },

                fetchData() {
                    this.isLoading = true;

                    fetch('{{ route("monitoring.email.response.data") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            per_page: this.perPage,
                            page: this.currentPage,
                            search: this.searchQuery,
                            sort_col: this.sortCol,
                            sort_dir: this.sortDir,
                            email_account: this.filters.emailAccount,
                            start_date: this.filters.startDate,
                            end_date: this.filters.endDate
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.emails = data.emails;
                            this.displayedEmails = data.emails;
                            this.pagination = data.pagination;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Error fetching data:', err);
                            this.isLoading = false;
                        });
                },

                applyFilters() {
                    this.currentPage = 1;
                    this.fetchData();
                },

                applySearch() {
                    this.currentPage = 1;
                    this.fetchData();
                },

                refreshData() {
                    this.currentPage = 1;
                    this.fetchData();
                },

                sortBy(col) {
                    if (this.sortCol === col) {
                        this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortCol = col;
                        this.sortDir = 'asc';
                    }
                    this.fetchData();
                },

                getSortIcon(col) {
                    if (this.sortCol !== col) return 'bx-sort opacity-30';
                    return this.sortDir === 'asc' ? 'bx-sort-up text-blue-500' : 'bx-sort-down text-blue-500';
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.fetchData();
                    }
                },

                nextPage() {
                    if (this.currentPage < this.pagination.totalPages) {
                        this.currentPage++;
                        this.fetchData();
                    }
                },

                goToPage(p) {
                    this.currentPage = p;
                    this.fetchData();
                },

                formatDate(dateStr) {
                    const d = new Date(dateStr);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = months[d.getMonth()];
                    const year = d.getFullYear();
                    return `${day} ${month} ${year}`;
                },

                formatTime(dateStr) {
                    const d = new Date(dateStr);
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    return `${hours}.${minutes} WIB`;
                },

                handleAssign(id) {
                    if (typeof openAssignModal === 'function') {
                        openAssignModal(id);
                    } else {
                        console.warn('openAssignModal function not injected yet');
                    }
                },

                handleConversation(id) {
                    if (typeof openConversationModal === 'function') {
                        openConversationModal(id);
                    } else {
                        console.warn('openConversationModal function not injected yet');
                    }
                }
            }
        }
    </script>
</x-dashonic-horizontal-layout>