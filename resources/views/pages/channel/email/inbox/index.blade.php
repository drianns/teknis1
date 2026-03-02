<x-dashonic-horizontal-layout sidebar="1" with-sidebar="{{ request()->get('with-sidebar') ?? 1 }}"
    with-header="{{ request()->get('with-header') ?? 1 }}" with-footer="{{ request()->get('with-footer') ?? 0 }}"> {{--
    Alpine.js for view switching --}}

    {{-- Define handler functions BEFORE Alpine.js loads --}}
    <script>
        // Immediately define functions in global scope
        (function () {
            'use strict';

            // Compose Modal Functions
            let editorInstance;
            window.toggleComposeModal = function (event) {
                if (event) event.preventDefault();
                const modal = document.getElementById('compose-modal');
                const backdrop = document.getElementById('compose-backdrop');
                const modalContent = document.getElementById('compose-content');

                if (!modal) {
                    console.error('Modal element not found!');
                    return;
                }

                if (modal.classList.contains('hidden')) {
                    // Show
                    modal.classList.remove('hidden');
                    backdrop.classList.remove('hidden');
                    void modal.offsetWidth;
                    requestAnimationFrame(() => {
                        backdrop.classList.remove('opacity-0');
                        backdrop.classList.add('opacity-100');
                        modalContent.classList.remove('scale-75', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    });

                    // Initialize CKEditor if not already initialized
                    setTimeout(() => {
                        if (typeof ClassicEditor !== 'undefined' && !editorInstance && document.querySelector('#editor')) {
                            ClassicEditor
                                .create(document.querySelector('#editor'), {
                                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
                                    placeholder: 'Type your message here...'
                                })
                                .then(editor => {
                                    editorInstance = editor;
                                    console.log('CKEditor initialized in compose modal');
                                })
                                .catch(error => console.error('CKEditor error:', error));
                        }
                    }, 100);
                } else {
                    closeComposeModal();
                }
            };

            window.closeComposeModal = function () {
                const modal = document.getElementById('compose-modal');
                const backdrop = document.getElementById('compose-backdrop');
                const modalContent = document.getElementById('compose-content');
                const title = document.getElementById('compose-title');

                if (backdrop) {
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                }
                if (modalContent) {
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-75', 'opacity-0');
                }
                if (title) title.style.display = 'block';

                setTimeout(() => {
                    if (modal) modal.classList.add('hidden');
                    if (backdrop) backdrop.classList.add('hidden');
                }, 300);
            };

            // Action Handlers
            window.handleReplyAction = function (id, sender) {
                const title = document.getElementById('compose-title');
                if (title) title.style.display = 'none';
                const toInput = document.getElementById('compose-to');
                if (toInput) toInput.value = sender;
                toggleComposeModal();
            };

            window.handleForwardAction = function (id) {
                const title = document.getElementById('compose-title');
                if (title) title.style.display = 'none';
                const toInput = document.getElementById('compose-to');
                if (toInput) toInput.value = '';
                toggleComposeModal();
            };

            // Assign Modal Functions
            window.handleAssignAction = function (id) {
                const modal = document.getElementById('assign-modal');
                const content = document.getElementById('assign-content');
                const backdrop = document.getElementById('assign-backdrop');
                if (modal && content) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        if (backdrop) backdrop.classList.add('opacity-100');
                        content.classList.remove('scale-75', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            };

            window.closeAssignModal = function () {
                const modal = document.getElementById('assign-modal');
                const content = document.getElementById('assign-content');
                const backdrop = document.getElementById('assign-backdrop');
                if (backdrop) backdrop.classList.remove('opacity-100');
                if (content) {
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-75', 'opacity-0');
                }
                setTimeout(() => {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }, 300);
            };

            // Conversation Modal Functions
            window.handleConversationAction = function (id) {
                const modal = document.getElementById('conversation-modal');
                const content = document.getElementById('conversation-content');
                if (modal && content) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        content.classList.remove('scale-75', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            };

            window.closeConversationModal = function () {
                const modal = document.getElementById('conversation-modal');
                const content = document.getElementById('conversation-content');
                if (content) {
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-75', 'opacity-0');
                }
                setTimeout(() => {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }, 300);
            };

            // Preview Journey Modal Functions
            let pjEditorInstance;
            let reminderEditorInstance;
            
            window.handlePreviewJourneyAction = function(id) {
                const modal = document.getElementById('preview-journey-modal');
                const content = document.getElementById('pj-content');
                if (modal && content) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                    
                    // Initialize Editors after modal is visible
                    setTimeout(() => {
                        if (typeof ClassicEditor === 'undefined') return;
                        
                        // Data Ticket Editor
                        if (!pjEditorInstance && document.querySelector('#pj-editor')) {
                            ClassicEditor
                                .create(document.querySelector('#pj-editor'), {
                                    toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                                    placeholder: 'Type response to customer here...'
                                })
                                .then(editor => {
                                    pjEditorInstance = editor;
                                    console.log('PJ Ticket Editor initialized');
                                })
                                .catch(error => console.error('PJ Editor error:', error));
                        }
                        
                        // Reminder Editor
                        if (!reminderEditorInstance && document.querySelector('#reminder-editor')) {
                            ClassicEditor
                                .create(document.querySelector('#reminder-editor'), {
                                    toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo'],
                                    placeholder: 'Add reminder details...'
                                })
                                .then(editor => {
                                    reminderEditorInstance = editor;
                                    console.log('PJ Reminder Editor initialized');
                                })
                                .catch(error => console.error('Reminder Editor error:', error));
                        }
                    }, 150);
                }
            };

            window.closePreviewJourneyModal = function() {
                const modal = document.getElementById('preview-journey-modal');
                const content = document.getElementById('pj-content');
                if (content) {
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                }
                setTimeout(() => {
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }, 300);
            };

            // Mark email as read function
            window.markAsRead = function(rowElement, emailId) {
                // Check if already read
                if (rowElement.classList.contains('read')) {
                    return; // Already read, do nothing
                }
                
                console.log('Marking email ' + emailId + ' as read...');
                
                fetch(`/channel/email/inbox/${emailId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update row styling
                    rowElement.classList.remove('unread', 'font-semibold');
                    rowElement.classList.add('read', 'font-normal');
                    
                    // Update individual cells for visual consistency
                    const cells = rowElement.querySelectorAll('td');
                    
                    // ID column (index 0)
                    const idSpan = cells[0].querySelector('span');
                    if (idSpan) {
                        idSpan.classList.remove('text-blue-300', 'font-bold');
                        idSpan.classList.add('text-blue-400/60', 'font-medium');
                    }
                    
                    // Email Service (index 1)
                    const serviceSpan = cells[1].querySelector('span');
                    if (serviceSpan) {
                        serviceSpan.classList.remove('text-blue-300', 'font-semibold');
                        serviceSpan.classList.add('text-blue-400/50', 'font-normal');
                    }
                    
                    // From/To column (index 2)
                    cells[2].classList.remove('text-gray-100', 'font-semibold');
                    cells[2].classList.add('text-gray-400', 'font-normal');
                    
                    // Subject (index 3)
                    const subjectSpan = cells[3].querySelector('span');
                    if (subjectSpan) {
                        subjectSpan.classList.remove('text-white', 'font-bold');
                        subjectSpan.classList.add('text-gray-400', 'font-normal');
                    }
                    
                    // Date (index 5)
                    cells[5].classList.remove('text-gray-300');
                    cells[5].classList.add('text-gray-500');
                    
                    // Update status badge (index 4)
                    const statusBadge = cells[4].querySelector('span');
                    if (statusBadge && statusBadge.classList.contains('bg-yellow-500/20')) {
                        statusBadge.classList.remove('bg-yellow-500/20', 'text-yellow-400');
                        statusBadge.classList.add('bg-gray-500/20', 'text-gray-400');
                        statusBadge.textContent = 'Read';
                    }
                })
                .catch(error => {
                    console.error('Error marking as read:', error);
                });
            };

            console.log('✓ All modal functions loaded (IIFE)');
        })();
        
        // Double-check on Alpine init
        document.addEventListener('alpine:init', () => {
            console.log('✓ Alpine initialized - functions should be available');
        });
    </script>
    
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div x-data="{ activeView: 'inbox', showSpamModal: false, selectedEmailId: null, selectedEmail: null }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-2 p-2 overflow-hidden" style="height: calc(100vh - 3rem);">
            <!-- Sidebar Section -->
            <div class="lg:col-span-3 h-full overflow-hidden">
                <!-- Compose Button & Folders -->
                <div class="bg-gray-800 flex flex-col rounded-xl shadow-lg h-full p-4 overflow-hidden">
                    
                    <!-- Fixed Top Section (Compose & Folders) -->
                    <div class="flex-shrink-0 flex flex-col gap-4 mb-4">
                    <!-- Compose Button -->
                    <button onclick="toggleComposeModal()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-full shadow-lg shadow-blue-600/20 transition-all mb-6 flex items-center justify-center gap-2">
                        <i class="bx bx-plus text-xl"></i>
                        <span>Compose</span>
                    </button>
                    <!-- Folders Header -->
                    <div class="flex items-center gap-2 text-yellow-500 mb-4 px-2">
                        <i class="bx bx-folder text-xl"></i>
                        <h6 class="text-white font-semibold m-0 text-base">Folders</h6>
                    </div>
                    <!-- Folder List -->
                    <nav class="space-y-1">
                        <a href="#" @click.prevent="activeView = 'inbox'"
                            :class="activeView === 'inbox' ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg group transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bx bx-envelope text-lg"></i>
                                <span class="font-medium">Inbox</span>
                            </div>
                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" @click.prevent="activeView = 'sent'"
                            :class="activeView === 'sent' ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg group transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bx bx-send text-lg"></i>
                                <span class="font-medium">Sent</span>
                            </div>
                        </a>
                        <a href="#" @click.prevent="activeView = 'drafts'"
                            :class="activeView === 'drafts' ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg group transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bx bx-file text-lg"></i>
                                <span class="font-medium">Drafts</span>
                            </div>
                            <span class="bg-teal-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" @click.prevent="activeView = 'spam'"
                            :class="activeView === 'spam' ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg group transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bx bx-trash text-lg"></i>
                                <span class="font-medium">Spam</span>
                            </div>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" @click.prevent="activeView = 'department'"
                            :class="activeView === 'department' ? 'bg-blue-500/10 text-blue-400' : 'text-gray-400 hover:bg-gray-700 hover:text-white'"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg group transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="bx bx-star text-lg"></i>
                                <span class="font-medium">Department</span>
                            </div>
                            <span class="bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">55</span>
                        </a>
                    </nav>
                    </div> <!-- End Fixed Top Section -->

                    <!-- Scrollable Bottom Section (Profiles & Agents) -->
                    <div class="flex-1 overflow-y-auto space-y-4 pr-1 custom-scrollbar min-h-0 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-500">
                        
                    <!-- Selected Email Profile Card -->
                    <div class="pt-2 border-t border-gray-700 flex flex-col gap-3">
                        <div class="flex items-center justify-between p-2 bg-gray-700/50 rounded-lg border border-gray-600/50 transition-all" :class="selectedEmail ? 'border-blue-500/50 shadow-[0_0_10px_rgba(59,130,246,0.1)]' : ''">
                            <div class="flex items-center gap-3 w-full overflow-hidden">
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white ring-2 ring-gray-600 transition-colors" :class="!selectedEmail ? 'bg-gray-600' : ''">
                                        <i class="bx bx-user text-xl"></i>
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-gray-800 rounded-full" x-show="selectedEmail" style="display:none;"></span>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-500 border-2 border-gray-800 rounded-full" x-show="!selectedEmail"></span>
                                </div>
                                <div class="overflow-hidden flex-1">
                                    <h6 class="text-white text-sm font-semibold truncate transition-colors" :class="!selectedEmail ? 'text-gray-400' : ''" x-text="selectedEmail ? selectedEmail.from : 'No Target Selected'"></h6>
                                    <p class="text-[10px] text-gray-400 truncate uppercase tracking-wider" x-text="selectedEmail ? 'Selected Target' : 'Waiting...'"></p>
                                </div>
                            </div>
                            <button class="text-gray-400 transition-colors flex-shrink-0 cursor-default" title="Target Status">
                                <i class="bx bx-target-lock text-xl text-blue-500" x-show="selectedEmail" style="display:none;"></i>
                                <i class="bx bx-target-lock text-xl text-gray-600" x-show="!selectedEmail"></i>
                            </button>
                        </div>

                        <!-- Current User Profile -->
                        <div class="flex items-center justify-between p-2 bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white ring-2 ring-gray-600">
                                        <i class="bx bx-user text-xl"></i>
                                    </div>
                                    <span
                                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-gray-800 rounded-full"></span>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-white text-sm font-semibold truncate">
                                        {{ optional(current_agent())->name ?? 'Guest' }}
                                    </h6>
                                    <p class="text-xs text-gray-400 truncate max-w-[120px]">
                                        {{ optional(current_agent())->email ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <button onclick="window.location.reload()"
                                class="text-blue-400 hover:text-white transition-colors" title="Refresh List Agent">
                                <i class="bx bx-sync text-2xl text-blue-500"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Online Agents Section -->
                    <div class="mt-2 space-y-2 pr-1">
                        <!-- Other Agents List -->
                            @php $currentAgentId = optional(current_agent())->id;
                                $companyId = optional(current_agent())->company_id;
                                $otherAgents = collect();
                                if ($currentAgentId && $companyId) {
                                    $otherAgents = \App\Models\UserAgent::where('company_id', $companyId)->where('user_id', '!=', $currentAgentId)->with('user')->limit(10)->get();
                            }                            @endphp
                            @foreach($otherAgents as $agent)
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-gray-700/30 rounded-lg transition-colors cursor-pointer group">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-gray-300 flex-shrink-0">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <p
                                            class="text-gray-300 text-sm font-medium truncate group-hover:text-white transition-colors">
                                            {{ optional($agent->user)->name ?? 'Unknown Agent' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <span
                                            class="w-2 h-2 rounded-full {{ $agent->aux != 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        <span
                                            class="text-[10px] {{ $agent->aux != 0 ? 'text-green-400' : 'text-gray-500' }}">
                                            {{ $agent->aux != 0 ? 'Online' : 'Offline' }}
                                        </span>
                                    </div>
                            </div> @endforeach
                            <!-- Dummy Data for Visual Demo if list is empty --> @if($otherAgents->isEmpty())
                                @for ($i = 1; $i <= 6; $i++)
                                    <div
                                        class="flex items-center justify-between p-2 hover:bg-gray-700/30 rounded-lg transition-colors cursor-pointer group">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-gray-300 flex-shrink-0">
                                                <i class="bx bx-user"></i>
                                            </div>
                                            <p
                                                class="text-gray-300 text-sm font-medium truncate group-hover:text-white transition-colors">
                                                Demo Agent {{ $i }}</p>
                                        </div>
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $i % 2 == 0 ? 'bg-red-500' : 'bg-green-500' }}"></span>
                                            <span class="text-[10px] {{ $i % 2 == 0 ? 'text-gray-500' : 'text-green-400' }}">
                                                {{ $i % 2 == 0 ? 'Offline' : 'Online' }}
                                            </span>
                                        </div>
                            </div> @endfor @endif
                        </div>
                    </div> <!-- End Scrollable Bottom Section -->
                </div>
            </div>
            <!-- Main Content -->
            <div class="lg:col-span-9 h-full">
                <div class="bg-gray-800 rounded-xl shadow-lg flex flex-col h-full">
                    <!-- Header -->
                    <div
                        class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-800/50 backdrop-blur-sm">
                        <!-- Date Filter (Native HTML5 - Styled) -->
                        <div class="flex items-center bg-gray-700/50 rounded-lg p-1 border border-gray-600">
                            <div class="flex items-center px-3 border-r border-gray-600">
                                <i class="bx bx-calendar text-gray-400 text-lg"></i>
                            </div>
                            <input type="date" id="date-from" value="2026-02-02"
                                class="bg-transparent text-gray-300 px-3 py-1.5 text-sm outline-none border-none focus:ring-0 w-32 cursor-pointer [&::-webkit-calendar-picker-indicator]:filter [&::-webkit-calendar-picker-indicator]:invert-[0.6]">
                            <span class="text-gray-500 text-xs font-medium px-2">TO</span>
                            <input type="date" id="date-to" value="2026-02-04"
                                class="bg-transparent text-gray-300 px-3 py-1.5 text-sm outline-none border-none focus:ring-0 w-32 cursor-pointer [&::-webkit-calendar-picker-indicator]:filter [&::-webkit-calendar-picker-indicator]:invert-[0.6]">
                        </div>
                        <h5 class="text-white font-bold text-lg m-0">Inbox Email</h5>
                    </div>
                    <!-- Table Controls -->
                    <div class="p-6 pb-0 flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <span>Show</span>
                            <select
                                class="form-select bg-gray-900 border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <span>Search:</span>
                            <input type="text"
                                class="form-input bg-gray-900 border-gray-700 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-64">
                        </div>
                    </div>
                    <!-- Table Content -->
                    <div
                        class="flex-1 min-h-0 overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-gray-800 [&::-webkit-scrollbar-thumb]:bg-gray-600 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-500">
                        <div class="px-6">
                            <!-- INBOX TABLE -->
                            <div x-show="activeView === 'inbox'">
                                <table class="w-full text-left border-collapse table-fixed">
                                    <thead class="sticky top-0 bg-gray-800 z-10">
                                        <tr
                                            class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold w-[10%]">ID <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Email Service <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">From <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[25%]">Subject <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[10%]">Status <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Date Create <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold text-center w-[10%]">Action <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300 text-sm divide-y divide-gray-700">
                                        @php $mockEmails = [['id' => 40628, 'service' => 'support@kanmogroup.com', 'from' => 'Filiaratna@dummy.com', 'subject' => '[TicketNumber:2026020601490028Z] Product inquiry and support', 'status' => 'Unread', 'is_read' => false, 'date' => '2/6/2026 1:49:01 PM'], ['id' => 40627, 'service' => 'sales@techcorp.com', 'from' => 'john.doe@client.com', 'subject' => '[TicketNumber:2026020601230015A] Quotation request for enterprise plan', 'status' => 'Read', 'is_read' => true, 'date' => '2/6/2026 12:30:15 PM'], ['id' => 40626, 'service' => 'noreply@notification.io', 'from' => 'admin@platform.net', 'subject' => '[TicketNumber:2026020509450042B] Account verification completed', 'status' => 'Unread', 'is_read' => false, 'date' => '2/5/2026 9:45:42 AM'], ['id' => 40625, 'service' => 'hr@company.biz', 'from' => 'recruiter@talent.com', 'subject' => '[TicketNumber:2026020416320018C] Job application follow-up', 'status' => 'Read', 'is_read' => true, 'date' => '2/4/2026 4:32:18 PM'], ['id' => 40624, 'service' => 'billing@services.com', 'from' => 'finance@client.org', 'subject' => '[TicketNumber:2026020311150033D] Invoice #INV-2026-001 payment received', 'status' => 'Unread', 'is_read' => false, 'date' => '2/3/2026 11:15:33 AM'], ['id' => 40623, 'service' => 'marketing@agency.com', 'from' => 'campaign@media.io', 'subject' => '[TicketNumber:2026020214280051E] Campaign performance report Q1 2026', 'status' => 'Read', 'is_read' => true, 'date' => '2/2/2026 2:28:51 PM'], ['id' => 40622, 'service' => 'info@newsletter.net', 'from' => 'editor@tech.news', 'subject' => '[TicketNumber:2026020108450027F] Weekly technology digest - February 2026', 'status' => 'Unread', 'is_read' => false, 'date' => '2/1/2026 8:45:27 AM'], ['id' => 40621, 'service' => 'security@alert.com', 'from' => 'system@monitor.io', 'subject' => '[TicketNumber:2026013120150039G] Security alert: New login detected', 'status' => 'Read', 'is_read' => true, 'date' => '1/31/2026 8:15:39 PM'],];                                        @endphp
                                        @forelse($mockEmails as $email)
                                            <tr class="email-row group/row {{ !$email['is_read'] ? 'unread font-semibold' : 'read font-normal' }} {{ $loop->even ? 'bg-gray-700/50' : '' }} hover:bg-gray-700/30 transition-colors cursor-pointer"
                                                data-email-id="{{ $email['id'] }}" data-email-name="{{ $email['from'] }}"
                                                @click="markAsRead($el, {{ $email['id'] }}); selectedEmail = { from: $el.dataset.emailName }">
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$email['is_read'] ? 'text-blue-300 font-bold' : 'text-blue-400/60 font-medium' }}">
                                                        #{{ $email['id'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$email['is_read'] ? 'text-blue-300 font-semibold' : 'text-blue-400/50 font-normal' }} hover:text-blue-300 transition-colors cursor-pointer block truncate"
                                                        title="{{ $email['service'] }}">{{ $email['service'] }}</span>
                                                </td>
                                                <td class="py-3 px-4 {{ !$email['is_read'] ? 'text-gray-100 font-semibold' : 'text-gray-400 font-normal' }} truncate"
                                                    title="{{ $email['from'] }}"> {{ $email['from'] }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$email['is_read'] ? 'text-white font-bold' : 'text-gray-400 font-normal' }} block truncate"
                                                        title="{{ $email['subject'] }}">{{ $email['subject'] }}</span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs font-medium {{ $email['status'] == 'Unread' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-500/20 text-gray-400' }}">
                                                        {{ $email['status'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="py-3 px-4 {{ !$email['is_read'] ? 'text-gray-300' : 'text-gray-500' }}">
                                                    {{ $email['date'] }}
                                                </td>
                                                <td class="py-3 px-4 text-center relative" x-data="{ open: false }">
                                                    <button @click.stop="open = !open" @click.away="open = false"
                                                        class="text-gray-400 hover:text-blue-400 transition-colors">
                                                        <i class="bx bx-dots-vertical-rounded text-xl"></i>
                                                    </button>
                                                    <div x-show="open"
                                                        class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-2xl z-50 border border-white/10 py-2 text-left"
                                                        style="display: none;"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95">
                                                        <a href="javascript:void(0)"
                                                            @click.stop="selectedEmailId = {{ $email['id'] }}; showSpamModal = true; open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-error-circle text-lg"></i> Spam
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleReplyAction({{ $email['id'] }}, '{{ $email['from'] }}'); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-reply text-lg"></i> Reply
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleAssignAction({{ $email['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-user-plus text-lg"></i> Assign
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleForwardAction({{ $email['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-share text-lg"></i> Forward
                                                        </a>
                                                        <a href="#"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors opacity-50 cursor-not-allowed">
                                                            <i class="bx bx-show-alt text-lg"></i> Preview
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleConversationAction({{ $email['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-chat text-lg"></i> Conversation
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handlePreviewJourneyAction({{ $email['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-map-alt text-lg"></i> Preview Journey
                                                        </a>
                                                    </div>
                                                </td>
                                        </tr> @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"> No
                                                    inbox emails available
                                                </td>
                                        </tr> @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- DRAFTS TABLE -->
                            <div x-show="activeView === 'drafts'" style="display: none;">
                                <table class="w-full text-left border-collapse table-fixed">
                                    <thead class="sticky top-0 bg-gray-800 z-10">
                                        <tr
                                            class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold w-[10%]">ID <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Email Service <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">To <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[25%]">Subject <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[10%]">Status <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Date Create <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold text-center w-[10%]">Action <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300 text-sm divide-y divide-gray-700">
                                        @php $mockDrafts = [['id' => 50145, 'service' => 'draft@kanmogroup.com', 'to' => 'prospect@newclient.com', 'subject' => '[Draft] RE: [TicketNumber:2026020610250044H] Proposal for digital transformation', 'is_read' => false, 'date' => '2/6/2026 10:25:44 AM'], ['id' => 50144, 'service' => 'compose@techcorp.com', 'to' => 'board@company.com', 'subject' => '[Draft] FW: [TicketNumber:2026020515400062I] Q4 financial results presentation', 'is_read' => true, 'date' => '2/5/2026 3:40:62 PM'], ['id' => 50143, 'service' => 'draft@services.io', 'to' => 'legal@partner.biz', 'subject' => '[Draft] [TicketNumber:2026020412180035J] Contract amendment discussion', 'is_read' => false, 'date' => '2/4/2026 12:18:35 PM'], ['id' => 50142, 'service' => 'compose@agency.com', 'to' => 'client@customer.net', 'subject' => '[Draft] RE: [TicketNumber:2026020308550021K] Marketing campaign approval', 'is_read' => true, 'date' => '2/3/2026 8:55:21 AM'], ['id' => 50141, 'service' => 'draft@business.com', 'to' => 'vendor@supplier.org', 'subject' => '[Draft] [TicketNumber:2026020217320048L] Purchase order #PO-2026-0145', 'is_read' => false, 'date' => '2/2/2026 5:32:48 PM'],];                                        @endphp
                                        @forelse($mockDrafts as $draft)
                                            <tr class="email-row group/row {{ !$draft['is_read'] ? 'unread font-semibold' : 'read font-normal' }} {{ $loop->even ? 'bg-gray-700/50' : '' }} hover:bg-gray-700/30 transition-colors cursor-pointer"
                                                data-email-id="{{ $draft['id'] }}" data-email-name="{{ $draft['to'] }}"
                                                @click="markAsRead($el, {{ $draft['id'] }}); selectedEmail = { from: $el.dataset.emailName }">
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$draft['is_read'] ? 'text-blue-300 font-bold' : 'text-blue-400/60 font-medium' }}">
                                                        #{{ $draft['id'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$draft['is_read'] ? 'text-blue-300 font-semibold' : 'text-blue-400/50 font-normal' }} hover:text-blue-300 transition-colors cursor-pointer block truncate"
                                                        title="{{ $draft['service'] }}">{{ $draft['service'] }}</span>
                                                </td>
                                                <td class="py-3 px-4 {{ !$draft['is_read'] ? 'text-gray-100 font-semibold' : 'text-gray-400 font-normal' }} truncate"
                                                    title="{{ $draft['to'] }}"> {{ $draft['to'] }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$draft['is_read'] ? 'text-white font-bold' : 'text-gray-400 font-normal' }} block truncate"
                                                        title="{{ $draft['subject'] }}">{{ $draft['subject'] }}</span>
                                                </td>
                                                <td class="py-3 px-4"><span class="px-2 py-1 text-gray-500">-</span></td>
                                                <td
                                                    class="py-3 px-4 {{ !$draft['is_read'] ? 'text-gray-300' : 'text-gray-500' }}">
                                                    {{ $draft['date'] }}
                                                </td>
                                                <td class="py-3 px-4 text-center relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.away="open = false"
                                                        class="text-gray-400 hover:text-blue-400 transition-colors">
                                                        <i class="bx bx-dots-vertical-rounded text-xl"></i>
                                                    </button>
                                                    <div x-show="open"
                                                        class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-2xl z-50 border border-white/10 py-2 text-left"
                                                        style="display: none;"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95">
                                                        <a href="javascript:void(0)"
                                                            @click.stop="selectedEmailId = {{ $draft['id'] }}; showSpamModal = true; open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-error-circle text-lg"></i> Spam
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleReplyAction({{ $draft['id'] }}, '{{ $draft['to'] ?? '' }}'); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-reply text-lg"></i> Reply
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleAssignAction({{ $draft['id'] }}); open = false"
                                                            class="w-full px-4 py-2.5 text-sm text-gray-300 hover:bg-blue-600 hover:text-white flex items-center gap-3 transition-colors">
                                                            <i class="bx bx-user-plus text-lg"></i> Assign
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleForwardAction({{ $draft['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-share text-lg"></i> Forward
                                                        </a>
                                                        <a href="#"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors opacity-50 cursor-not-allowed">
                                                            <i class="bx bx-show-alt text-lg"></i> Preview
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleConversationAction({{ $draft['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-chat text-lg"></i> Conversation
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handlePreviewJourneyAction({{ $draft['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-map-alt text-lg"></i> Preview Journey
                                                        </a>
                                                    </div>
                                                </td>
                                        </tr> @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"> No
                                                    draft emails available
                                                </td>
                                        </tr> @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- SPAM TABLE -->
                            <div x-show="activeView === 'spam'" style="display: none;">
                                <table class="w-full text-left border-collapse table-fixed">
                                    <thead class="sticky top-0 bg-gray-800 z-10">
                                        <tr
                                            class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold w-[10%]">ID <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Email Service <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">From <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[25%]">Subject <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[10%]">Status <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Date Create <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold text-center w-[10%]">Action <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300 text-sm divide-y divide-gray-700">
                                        @php $mockSpam = [['id' => 60234, 'service' => 'lottery@scam.net', 'from' => 'winner@fake-lottery.com', 'subject' => '[SPAM] Congratulations! You won $5,000,000 - Claim now!', 'status' => 'Spam', 'is_read' => false, 'date' => '2/9/2026 3:25:18 AM'], ['id' => 60233, 'service' => 'prince@nigeria.fake', 'from' => 'urgent@business-proposal.net', 'subject' => '[SPAM] Urgent: Transfer 10 million USD - Need your help', 'status' => 'Spam', 'is_read' => true, 'date' => '2/8/2026 10:15:42 PM'], ['id' => 60232, 'service' => 'deals@phishing.com', 'from' => 'offer@free-iphone.biz', 'subject' => '[SPAM] Click NOW! Free iPhone 15 Pro Max waiting for you', 'status' => 'Spam', 'is_read' => false, 'date' => '2/8/2026 6:45:33 PM'], ['id' => 60231, 'service' => 'alert@fake-bank.net', 'from' => 'security@phishing-site.com', 'subject' => '[SPAM] URGENT: Your account will be suspended in 24 hours', 'status' => 'Spam', 'is_read' => true, 'date' => '2/7/2026 8:30:55 PM'], ['id' => 60230, 'service' => 'pharmacy@cheap.biz', 'from' => 'meds@discount-pills.org', 'subject' => '[SPAM] 90% OFF medications - Limited time offer!!!', 'status' => 'Spam', 'is_read' => false, 'date' => '2/7/2026 3:10:27 PM'], ['id' => 60229, 'service' => 'verify@suspicious.com', 'from' => 'paypal-fake@scam.net', 'subject' => '[SPAM] Verify your PayPal account immediately or lose access', 'status' => 'Spam', 'is_read' => true, 'date' => '2/6/2026 12:40:15 PM'],];                                        @endphp
                                        @forelse($mockSpam as $spam)
                                            <tr class="email-row group/row {{ !$spam['is_read'] ? 'unread font-semibold' : 'read font-normal' }} {{ $loop->even ? 'bg-gray-700/50' : '' }} hover:bg-gray-700/30 transition-colors cursor-pointer"
                                                data-email-id="{{ $spam['id'] }}" data-email-name="{{ $spam['from'] }}"
                                                @click="markAsRead($el, {{ $spam['id'] }}); selectedEmail = { from: $el.dataset.emailName }">
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$spam['is_read'] ? 'text-blue-300 font-bold' : 'text-blue-400/60 font-medium' }}">
                                                        #{{ $spam['id'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$spam['is_read'] ? 'text-blue-300 font-semibold' : 'text-blue-400/50 font-normal' }} hover:text-blue-300 transition-colors cursor-pointer block truncate"
                                                        title="{{ $spam['service'] }}">{{ $spam['service'] }}</span>
                                                </td>
                                                <td class="py-3 px-4 {{ !$spam['is_read'] ? 'text-gray-100 font-semibold' : 'text-gray-400 font-normal' }} truncate"
                                                    title="{{ $spam['from'] }}"> {{ $spam['from'] }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$spam['is_read'] ? 'text-white font-bold' : 'text-gray-400 font-normal' }} block truncate"
                                                        title="{{ $spam['subject'] }}">{{ $spam['subject'] }}</span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs font-medium {{ $spam['status'] == 'Spam' ? 'bg-red-500/20 text-red-400' : 'bg-gray-500/20 text-gray-400' }}">
                                                        {{ $spam['status'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="py-3 px-4 {{ !$spam['is_read'] ? 'text-gray-300' : 'text-gray-500' }}">
                                                    {{ $spam['date'] }}
                                                </td>
                                                <td class="py-3 px-4 text-centerrelative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.away="open = false"
                                                        class="text-gray-400 hover:text-blue-400 transition-colors">
                                                        <i class="bx bx-dots-vertical-rounded text-xl"></i>
                                                    </button>
                                                    <div x-show="open"
                                                        class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-2xl z-50 border border-white/10 py-2 text-left"
                                                        style="display: none;"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95">
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleReplyAction({{ $spam['id'] }}, '{{ $spam['from'] }}'); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-reply text-lg"></i> Reply
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleForwardAction({{ $spam['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-share text-lg"></i> Forward
                                                        </a>
                                                        <a href="#"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors opacity-50 cursor-not-allowed">
                                                            <i class="bx bx-show-alt text-lg"></i> Preview
                                                        </a>
                                                    </div>
                                                </td>
                                        </tr> @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"> No
                                                    spam emails
                                                </td>
                                        </tr> @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- DEPARTMENT TABLE -->
                            <div x-show="activeView === 'department'" style="display: none;">
                                <table class="w-full text-left border-collapse table-fixed">
                                    <thead class="sticky top-0 bg-gray-800 z-10">
                                        <tr
                                            class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold w-[10%]">ID <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Email Service <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">From <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[25%]">Subject <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[10%]">Status <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Date Create <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold text-center w-[10%]">Action <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300 text-sm divide-y divide-gray-700">
                                        @php $mockDepartment = [['id' => 70856, 'service' => 'hr@kanmogroup.com', 'from' => 'recruitment@talent-dept.com', 'subject' => '[Dept:HR] [TicketNumber:2026020609150055M] New hire orientation - Week of Feb 10', 'status' => 'Unread', 'is_read' => false, 'date' => '2/6/2026 9:15:55 AM'], ['id' => 70855, 'service' => 'finance@kanmogroup.com', 'from' => 'accounting@finance-dept.com', 'subject' => '[Dept:Finance] [TicketNumber:2026020508300042N] Q1 2026 budget allocation review', 'status' => 'Read', 'is_read' => true, 'date' => '2/5/2026 8:30:42 AM'], ['id' => 70854, 'service' => 'it@kanmogroup.com', 'from' => 'sysadmin@it-dept.com', 'subject' => '[Dept:IT] [TicketNumber:2026020417450028O] Server maintenance window - Feb 8-9, 2026', 'status' => 'Unread', 'is_read' => false, 'date' => '2/4/2026 5:45:28 PM'], ['id' => 70853, 'service' => 'marketing@kanmogroup.com', 'from' => 'campaigns@marketing-dept.com', 'subject' => '[Dept:Marketing] [TicketNumber:2026020314200019P] January campaign analytics report', 'status' => 'Read', 'is_read' => true, 'date' => '2/3/2026 2:20:19 PM'], ['id' => 70852, 'service' => 'sales@kanmogroup.com', 'from' => 'manager@sales-dept.com', 'subject' => '[Dept:Sales] [TicketNumber:2026020211000036Q] Monthly sales performance review', 'status' => 'Unread', 'is_read' => false, 'date' => '2/2/2026 11:00:36 AM'], ['id' => 70851, 'service' => 'legal@kanmogroup.com', 'from' => 'counsel@legal-dept.com', 'subject' => '[Dept:Legal] [TicketNumber:2026020116300052R] Contract approval request #CA-2026-089', 'status' => 'Read', 'is_read' => true, 'date' => '2/1/2026 4:30:52 PM'], ['id' => 70850, 'service' => 'operations@kanmogroup.com', 'from' => 'logistics@ops-dept.com', 'subject' => '[Dept:Operations] [TicketNumber:2026013110150044S] Warehouse inventory audit results', 'status' => 'Unread', 'is_read' => false, 'date' => '1/31/2026 10:15:44 AM'],];                                        @endphp
                                        @forelse($mockDepartment as $dept)
                                            <tr class="email-row group/row {{ !$dept['is_read'] ? 'unread font-semibold' : 'read font-normal' }} {{ $loop->even ? 'bg-gray-700/50' : '' }} hover:bg-gray-700/30 transition-colors cursor-pointer"
                                                data-email-id="{{ $dept['id'] }}" data-email-name="{{ $dept['from'] }}"
                                                @click="markAsRead($el, {{ $dept['id'] }}); selectedEmail = { from: $el.dataset.emailName }">
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$dept['is_read'] ? 'text-blue-300 font-bold' : 'text-blue-400/60 font-medium' }}">
                                                        #{{ $dept['id'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$dept['is_read'] ? 'text-blue-300 font-semibold' : 'text-blue-400/50 font-normal' }} hover:text-blue-300 transition-colors cursor-pointer block truncate"
                                                        title="{{ $dept['service'] }}">{{ $dept['service'] }}</span>
                                                </td>
                                                <td class="py-3 px-4 {{ !$dept['is_read'] ? 'text-gray-100 font-semibold' : 'text-gray-400 font-normal' }} truncate"
                                                    title="{{ $dept['from'] }}"> {{ $dept['from'] }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$dept['is_read'] ? 'text-white font-bold' : 'text-gray-400 font-normal' }} block truncate"
                                                        title="{{ $dept['subject'] }}">{{ $dept['subject'] }}</span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs font-medium {{ $dept['status'] == 'Unread' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-gray-500/20 text-gray-400' }}">
                                                        {{ $dept['status'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="py-3 px-4 {{ !$dept['is_read'] ? 'text-gray-300' : 'text-gray-500' }}">
                                                    {{ $dept['date'] }}
                                                </td>
                                                <td class="py-3 px-4 text-center relative" x-data="{ open: false }">
                                                    <button @click.stop="open = !open" @click.away="open = false"
                                                        class="text-gray-400 hover:text-blue-400 transition-colors">
                                                        <i class="bx bx-dots-vertical-rounded text-xl"></i>
                                                    </button>
                                                    <div x-show="open"
                                                        class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-2xl z-50 border border-white/10 py-2 text-left"
                                                        style="display: none;"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95">
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleReplyAction({{ $dept['id'] }}, '{{ $dept['from'] }}'); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-reply text-lg"></i> Reply
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleForwardAction({{ $dept['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-share text-lg"></i> Forward
                                                        </a>
                                                        <a href="#"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors opacity-50 cursor-not-allowed">
                                                            <i class="bx bx-show-alt text-lg"></i> Preview
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handleConversationAction({{ $dept['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-chat text-lg"></i> Conversation
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            @click.stop="handlePreviewJourneyAction({{ $dept['id'] }}); open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors">
                                                            <i class="bx bx-map-alt text-lg"></i> Preview Journey
                                                        </a>
                                                    </div>
                                                </td>
                                        </tr> @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"> No
                                                    department emails
                                                </td>
                                        </tr> @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- SENT TABLE -->
                            <div x-show="activeView === 'sent'" style="display: none;">
                                <table class="w-full text-left border-collapse table-fixed">
                                    <thead class="sticky top-0 bg-gray-800 z-10">
                                        <tr
                                            class="border-b border-gray-700 text-gray-400 text-sm uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold w-[10%]">ID <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Email Service <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">To <i
                                                    class="bx bx-sort text-xs ml-1"></i>
                                            </th>
                                            <th class="py-3 px-4 font-semibold w-[25%]">Subject <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[10%]">Status <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold w-[15%]">Date Create <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                            <th class="py-3 px-4 font-semibold text-center w-[10%]">Action <i
                                                    class="bx bx-sort text-xs ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300 text-sm divide-y divide-gray-700">
                                        @php $mockSent = [['id' => 80512, 'service' => 'sent@kanmogroup.com', 'to' => 'newclient@prospect.com', 'subject' => 'RE: [TicketNumber:2026020608300066T] Project proposal with pricing details', 'status' => 'Sent', 'is_read' => false, 'date' => '2/6/2026 8:30:66 AM'], ['id' => 80511, 'service' => 'outbox@techcorp.com', 'to' => 'team@internal.com', 'subject' => 'FW: [TicketNumber:2026020516450053U] Weekly team sync - Action items', 'status' => 'Delivered', 'is_read' => true, 'date' => '2/5/2026 4:45:53 PM'], ['id' => 80510, 'service' => 'sent@services.io', 'to' => 'billing@vendor.biz', 'subject' => 'RE: [TicketNumber:2026020414200041V] Payment confirmation #PAY-2026-1145', 'status' => 'Sent', 'is_read' => false, 'date' => '2/4/2026 2:20:41 PM'], ['id' => 80509, 'service' => 'outbox@agency.com', 'to' => 'partnership@business.net', 'subject' => '[TicketNumber:2026020311000028W] Partnership proposal discussion request', 'status' => 'Delivered', 'is_read' => true, 'date' => '2/3/2026 11:00:28 AM'], ['id' => 80508, 'service' => 'sent@company.biz', 'to' => 'manager@leadership.org', 'subject' => 'RE: [TicketNumber:2026020218300037X] Monthly progress report - January 2026', 'status' => 'Sent', 'is_read' => false, 'date' => '2/2/2026 6:30:37 PM'], ['id' => 80507, 'service' => 'outbox@business.com', 'to' => 'hr@corporate.com', 'subject' => '[TicketNumber:2026020109150045Y] Annual leave request - Feb 15-20, 2026', 'status' => 'Delivered', 'is_read' => true, 'date' => '2/1/2026 9:15:45 AM'], ['id' => 80506, 'service' => 'sent@sales.io', 'to' => 'contact@customer.net', 'subject' => 'FW: [TicketNumber:2026013115400059Z] Product demo follow-up and next steps', 'status' => 'Sent', 'is_read' => false, 'date' => '1/31/2026 3:40:59 PM'],];                                        @endphp
                                        @forelse($mockSent as $sent)
                                            <tr class="email-row group/row {{ !$sent['is_read'] ? 'unread font-semibold' : 'read font-normal' }} {{ $loop->even ? 'bg-gray-700/50' : '' }} hover:bg-gray-700/30 transition-colors cursor-pointer"
                                                data-email-id="{{ $sent['id'] }}" data-email-name="{{ $sent['to'] }}"
                                                @click="markAsRead($el, {{ $sent['id'] }}); selectedEmail = { from: $el.dataset.emailName }">
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$sent['is_read'] ? 'text-blue-300 font-bold' : 'text-blue-400/60 font-medium' }}">
                                                        #{{ $sent['id'] }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$sent['is_read'] ? 'text-blue-300 font-semibold' : 'text-blue-400/50 font-normal' }} hover:text-blue-300 transition-colors cursor-pointer block truncate"
                                                        title="{{ $sent['service'] }}">{{ $sent['service'] }}</span>
                                                </td>
                                                <td class="py-3 px-4 {{ !$sent['is_read'] ? 'text-gray-100 font-semibold' : 'text-gray-400 font-normal' }} truncate"
                                                    title="{{ $sent['to'] }}"> {{ $sent['to'] }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="{{ !$sent['is_read'] ? 'text-white font-bold' : 'text-gray-400 font-normal' }} block truncate"
                                                        title="{{ $sent['subject'] }}">{{ $sent['subject'] }}</span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs font-medium {{ $sent['status'] == 'Sent' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400' }}">
                                                        {{ $sent['status'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="py-3 px-4 {{ !$sent['is_read'] ? 'text-gray-300' : 'text-gray-500' }}">
                                                    {{ $sent['date'] }}
                                                </td>
                                                <td class="py-3 px-4 text-center relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.away="open = false"
                                                        class="text-gray-400 hover:text-blue-400 transition-colors">
                                                        <i class="bx bx-dots-vertical-rounded text-xl"></i>
                                                    </button>
                                                    <div x-show="open"
                                                        class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-2xl z-50 border border-white/10 py-2 text-left"
                                                        style="display: none;"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95">
                                                        <a href="#"
                                                            @click.stop="open = false"
                                                            class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white flex items-center gap-2 transition-colors opacity-50 cursor-not-allowed">
                                                            <i class="bx bx-show-alt text-lg"></i> Preview
                                                        </a>
                                                    </div>
                                                </td>
                                        </tr> @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="py-12 text-center text-gray-500 bg-gray-900/50 rounded-lg"> No
                                                    sent emails
                                                </td>
                                        </tr> @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Footer / Pagination -->
                    <!-- Footer / Pagination -->
                    <div
                        class="px-6 py-4 border-t border-gray-700 flex justify-between items-center text-sm text-gray-400 bg-gray-800">
                        <span>Showing 1 to 10 of 50 entries</span>
                        <div class="flex gap-1">
                            <button
                                class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Previous</button>
                            <button
                                class="px-3 py-1 rounded bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">1</button>
                            <button
                                class="px-3 py-1 rounded hover:bg-gray-700 text-gray-300 transition-colors">2</button>
                            <button
                                class="px-3 py-1 rounded hover:bg-gray-700 text-gray-300 transition-colors">3</button>
                            <span class="px-2 py-1 text-gray-500">...</span>
                            <button
                                class="px-3 py-1 rounded hover:bg-gray-700 text-gray-300 transition-colors">5</button>
                            <button
                                class="px-3 py-1 rounded bg-gray-700 text-gray-300 hover:bg-gray-600 disabled:opacity-50 transition-colors">Next</button>
                        </div>
                    </div>
                </div>
                <!-- Compose Modal Backdrop -->
                <div id="compose-backdrop"
                    class="fixed inset-0 bg-[#060910]/90 backdrop-blur-md z-[9998] hidden transition-opacity duration-300 opacity-0">
                </div>
                <!-- Compose Modal (Centered with Bounce Animation) -->
                <div id="compose-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                    <div id="compose-content"
                        class="bg-[#0c121e] rounded-2xl shadow-2xl shadow-blue-900/10 border border-gray-800 w-full max-w-3xl max-h-[90vh] flex flex-col transform transition-all duration-300 scale-75 opacity-0 mx-auto relative">
                        
                        <!-- Close button (floating top right) -->
                        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors p-2 rounded-lg border border-gray-700 hover:bg-gray-800 z-10 bg-[#0c121e]"
                            onclick="closeComposeModal()">
                            <i class="bx bx-x text-xl"></i>
                        </button>

                        <!-- Header (Icon & Titles) -->
                        <div class="flex flex-col items-center justify-center pt-8 pb-4 flex-shrink-0 relative">
                            <!-- Icon Circle -->
                            <div class="w-16 h-16 rounded-3xl bg-[#0c121e] border-2 border-[#1e293b] text-blue-500 hover:border-blue-500 transition-colors flex items-center justify-center mb-4 relative z-10 shadow-[0_0_15px_rgba(59,130,246,0.15)]">
                                <i class="bx bx-pencil text-2xl"></i>
                            </div>
                            <h2 id="compose-title" class="text-white font-black text-2xl tracking-wide m-0 mb-1">COMPOSE EMAIL</h2>
                            <p class="text-[9px] text-blue-500 font-bold uppercase tracking-[0.2em]">CREATE AND SEND A NEW PROFESSIONAL MESSAGE</p>
                            
                            <!-- Divider line -->
                            <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-700/50 to-transparent mt-6"></div>
                        </div>

                        <!-- Body (Form) -->
                        <div id="compose-body" class="flex flex-col flex-1 overflow-y-auto px-10 py-2 custom-scrollbar">
                            <div class="space-y-6 w-full pb-6">
                                
                                <!-- To Field -->
                                <div class="space-y-2 relative">
                                    <label class="flex items-center gap-2 text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                        <i class="bx bx-down-arrow-circle text-blue-500 text-sm"></i> TO <span class="text-red-500">*</span>
                                    </label>
                                    <div class="bg-[#121824] border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-3 transition-colors flex items-center shadow-inner">
                                        <input type="text" id="compose-to" placeholder="Add recipients..."
                                            class="w-full bg-transparent border-none text-gray-300 placeholder-gray-500 focus:ring-0 text-sm px-0">
                                    </div>
                                    <div class="flex gap-6 px-1 pt-1">
                                        <button type="button" onclick="document.getElementById('cc-field').classList.toggle('hidden')" class="text-[9px] text-gray-500 hover:text-gray-300 uppercase tracking-widest font-black flex items-center gap-1.5 transition-colors">
                                           <div class="w-1.5 h-1.5 rounded-full bg-gray-600"></div> ADD CC
                                        </button>
                                        <button type="button" onclick="document.getElementById('bcc-field').classList.toggle('hidden')" class="text-[9px] text-gray-500 hover:text-gray-300 uppercase tracking-widest font-black flex items-center gap-1.5 transition-colors">
                                           <div class="w-1.5 h-1.5 rounded-full bg-gray-600"></div> ADD BCC
                                        </button>
                                    </div>
                                </div>

                                <!-- CC & BCC (Hidden by default) -->
                                <div id="cc-field" class="space-y-2 hidden animate-fadeIn">
                                    <div class="bg-[#121824] border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-3 transition-colors flex items-center shadow-inner relative">
                                        <span class="text-gray-500 text-xs font-bold mr-3 border-r border-gray-700 pr-3">CC</span>
                                        <input type="text" placeholder="Add carbon copy recipients..."
                                            class="w-full bg-transparent border-none text-gray-300 placeholder-gray-500 focus:ring-0 text-sm px-0">
                                    </div>
                                </div>
                                <div id="bcc-field" class="space-y-2 hidden animate-fadeIn">
                                    <div class="bg-[#121824] border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-3 transition-colors flex items-center shadow-inner relative">
                                        <span class="text-gray-500 text-xs font-bold mr-3 border-r border-gray-700 pr-3">BCC</span>
                                        <input type="text" placeholder="Add blind carbon copy recipients..."
                                            class="w-full bg-transparent border-none text-gray-300 placeholder-gray-500 focus:ring-0 text-sm px-0">
                                    </div>
                                </div>
                                
                                <div class="w-full h-px border-b border-gray-800/80 my-4 shadow-sm"></div>

                                <!-- Subject Field -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                        <i class="bx bx-text text-blue-500 text-sm"></i> SUBJECT <span class="text-red-500">*</span>
                                    </label>
                                    <div class="bg-[#121824] border border-gray-800 hover:border-gray-700 rounded-xl px-4 py-3 transition-colors shadow-inner">
                                        <input type="text" placeholder="Enter your email subject..."
                                            class="w-full bg-transparent border-none text-gray-300 placeholder-gray-500 focus:ring-0 text-sm px-0">
                                    </div>
                                </div>
                                
                                <div class="w-full h-px border-b border-gray-800/80 my-4 shadow-sm"></div>

                                <!-- Message Field -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                        <i class="bx bx-align-left text-blue-500 text-sm"></i> MESSAGE <span class="text-red-500">*</span>
                                    </label>
                                    <div class="bg-[#121824] border border-gray-800 rounded-xl overflow-hidden shadow-inner flex flex-col relative h-[300px]">
                                        <div id="editor" class="flex-1 w-full bg-transparent border-none rounded-xl h-full [&_.ck-editor]:h-full [&_.ck-editor__main]:h-[calc(100%-40px)] [&_.ck-content]:h-full [&_.ck-content]:bg-transparent [&_.ck-toolbar]:bg-[#121824] [&_.ck-toolbar]:border-none [&_.ck-toolbar]:border-b [&_.ck-toolbar]:border-gray-800 [&_.ck-button]:text-gray-400 [&_.ck-button:hover]:bg-gray-800 [&_.ck-button.ck-on]:bg-gray-800"></div>
                                        
                                        <!-- Attachments Preview inside text area block -->
                                        <div id="attachment-preview" class="p-4 space-y-2 hidden bg-[#121824] border-t border-gray-800">
                                            <div class="flex items-center justify-between p-2 lg:w-[45%] bg-[#1a2130] rounded-lg border border-gray-700 group">
                                                <div class="flex items-center gap-3 overflow-hidden">
                                                    <div class="w-8 h-8 rounded bg-gray-800 flex items-center justify-center flex-shrink-0 text-gray-400">
                                                        <i class="bx bx-file"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-sm text-gray-200 font-medium truncate">project-requirements.pdf</p>
                                                        <p class="text-xs text-blue-500/70">2.4 MB</p>
                                                    </div>
                                                </div>
                                                <button class="text-gray-500 hover:text-red-500 p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <i class="bx bx-x text-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="px-8 py-5 border-t border-gray-800/80 flex items-center justify-between flex-shrink-0 bg-[#0c121e] rounded-b-2xl relative z-20">
                            <div class="flex items-center gap-2">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2 uppercase tracking-wide">
                                    <i class="bx bx-send text-lg"></i>
                                    <span>Send Email</span>
                                </button>
                                <input type="file" id="file-upload" class="hidden" multiple onchange="handleFileUpload(event)">
                                <button onclick="document.getElementById('file-upload').click()"
                                    class="text-gray-400 hover:text-white p-2.5 rounded-xl hover:bg-gray-800 border border-transparent hover:border-gray-700 transition-colors ml-2" title="Attach file">
                                    <i class="bx bx-paperclip text-lg"></i>
                                </button>
                            </div>
                            <div class="flex items-center gap-5">
                                <span id="draft-status" class="text-xs text-gray-500 italic hidden">Saved as draft</span>
                                <button class="text-gray-400 hover:text-blue-400 text-xs font-black uppercase tracking-widest transition-colors flex items-center gap-2 group">
                                    <i class="bx bx-save text-lg text-gray-500 group-hover:text-blue-400 transition-colors"></i> Draft
                                </button>
                                <button class="text-gray-400 hover:text-red-500 p-2.5 rounded-xl hover:bg-red-500/10 transition-colors group border border-transparent hover:border-red-500/20" title="Discard">
                                    <i class="bx bx-trash text-lg group-hover:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Assign Modal -->
                <div id="assign-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                    <div id="assign-backdrop"
                        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
                    </div>
                    <div id="assign-content"
                        class="bg-gray-800 rounded-2xl shadow-2xl border border-white/10 w-full max-w-lg flex flex-col transform transition-all duration-300 scale-75 opacity-0 z-10 overflow-hidden">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-700 flex items-center justify-between bg-gray-800/50">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-600/10 rounded-xl flex items-center justify-center border border-blue-500/20">
                                    <i class="bx bx-user-plus text-xl text-blue-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white">Assign Agent</h3>
                            </div>
                            <button type="button" class="text-gray-500 hover:text-white transition-colors"
                                onclick="closeAssignModal()">
                                <i class="bx bx-x text-3xl"></i>
                            </button>
                        </div>
                        <!-- Body -->
                        <div class="p-8 space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="bx bx-user"></i> User Agent
                                </label>
                                <select id="assign-agent"
                                    class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="">Select Agent</option>
                                    <option>Adjie Sona</option>
                                    <option>Siti Muntaha</option>
                                    <option>Shifa Riani</option>
                                    <option>Andrean Setiawan</option>
                                    <option>Visa Damayanti</option>
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
            </div>
            <!-- Spam Confirmation Modal -->
            <div x-show="showSpamModal"
                class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div @click.away="showSpamModal = false"
                    class="bg-gray-800 rounded-xl shadow-2xl border border-gray-700 w-full max-w-sm overflow-hidden"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100">
                    <div class="px-6 py-8 text-center">
                        <div
                            class="w-16 h-16 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bx bx-help-circle text-3xl text-blue-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Move to Spam?</h3>
                        <p class="text-gray-400 text-sm">Are you sure you want to move this message to the spam folder?
                        </p>
                    </div>
                    <div class="flex border-t border-gray-700">
                        <button @click="showSpamModal = false"
                            class="flex-1 px-6 py-4 text-sm font-semibold text-gray-400 hover:text-white hover:bg-gray-700/50 transition-colors border-r border-gray-700">
                            Cancel
                        </button>
                        <button @click="executeSpamAction()"
                            class="flex-1 px-6 py-4 text-sm font-semibold text-blue-500 hover:text-blue-400 hover:bg-gray-700/50 transition-colors">
                            Process
                        </button>
                    </div>
                </div>
            </div>
            <!-- Conversation Modal -->
            <div id="conversation-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                <div id="conversation-backdrop"
                    class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300">
                </div>
                <div id="conversation-content"
                    class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 w-full max-w-2xl max-h-[80vh] flex flex-col transform transition-all duration-300 scale-75 opacity-0 z-10">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-6 py-4 bg-gray-900 rounded-t-lg border-b border-gray-700">
                        <h5 class="text-white font-semibold text-base m-0">Conversation History</h5>
                        <button type="button" class="text-gray-400 hover:text-white transition-colors"
                            onclick="closeConversationModal()">
                            <i class="bx bx-x text-xl"></i>
                        </button>
                    </div>
                    <!-- Body (Timeline) -->
                    <div class="p-6 overflow-y-auto space-y-6 bg-gray-800/50">
                        <!-- Timeline items simulating Journey style -->
                        <div class="relative pl-8 border-l-2 border-gray-700 space-y-8">
                            <!-- Agent Message -->
                            <div class="relative">
                                <div
                                    class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-blue-500 border-4 border-gray-800">
                                </div>
                                <div class="bg-blue-600/10 border border-blue-500/20 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-blue-400 text-xs font-bold uppercase tracking-wider">Agent
                                            (Adjie Sona)</span>
                                        <span class="text-gray-500 text-[10px]">2026-02-11 10:30</span>
                                    </div>
                                    <p class="text-gray-300 text-sm">Halo Bapak/Ibu, ada yang bisa kami bantu terkait
                                        pesanan Anda?</p>
                                </div>
                            </div>
                            <!-- Customer Message -->
                            <div class="relative">
                                <div
                                    class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-red-500 border-4 border-gray-800">
                                </div>
                                <div class="bg-red-600/10 border border-red-500/20 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span
                                            class="text-red-400 text-xs font-bold uppercase tracking-wider">Customer</span>
                                        <span class="text-gray-500 text-[10px]">2026-02-11 10:35</span>
                                    </div>
                                    <p class="text-gray-300 text-sm">Saya ingin menanyakan status refund untuk invoice
                                        #INV-2026-001.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Preview Journey Modal -->
            <div id="preview-journey-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
                <div id="pj-backdrop"
                    class="fixed inset-0 bg-black/40 backdrop-blur-md transition-opacity duration-300">
                </div>
                <div id="pj-content"
                    class="bg-[#1a1c23] rounded-xl shadow-2xl border border-gray-700 w-full max-w-[95%] h-[90vh] flex flex-col transform transition-all duration-300 scale-95 opacity-0 z-10 overflow-hidden"
                    x-data="{ activeTab: 'data-ticket' }">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between px-6 py-4 bg-gray-900/50 border-b border-gray-700 flex-shrink-0">
                        <h5 class="text-white font-bold text-lg m-0 flex items-center gap-2">
                            <i class="bx bx-map-alt text-blue-500"></i> Preview Journey
                        </h5>
                        <button type="button"
                            class="text-gray-400 hover:text-white transition-colors bg-gray-800 p-1.5 rounded-lg"
                            onclick="closePreviewJourneyModal()">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>
                    <!-- Main Layout (Grid) -->
                    <div class="flex flex-1 overflow-hidden">
                        <!-- Left Sidebar (Sticky-like via flex) -->
                        <div
                            class="w-80 border-r border-gray-700 bg-gray-900/30 overflow-y-auto p-6 hidden lg:block scrollbar-hide">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-700/50 pb-4 text-blue-400">
                                <i class="bx bx-user-circle text-2xl"></i>
                                <h6 class="font-bold uppercase tracking-widest text-xs m-0">Personal Information</h6>
                            </div>
                            <div class="space-y-5">
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Full
                                        Name</label>
                                    <input type="text" value="Fitiaratna" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Email
                                        Address</label>
                                    <input type="text" value="fitiaratna@dummy.com" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Phone
                                        Number</label>
                                    <input type="text" value="081234567890" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Date
                                        of Birth</label>
                                    <input type="text" value="1990-01-01" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Gender</label>
                                    <div class="flex gap-4 px-1">
                                        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-not-allowed">
                                            <input type="radio" checked disabled class="accent-blue-500"> Female
                                        </label>
                                        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-not-allowed">
                                            <input type="radio" disabled class="accent-blue-500"> Male
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Polis
                                        Number</label>
                                    <input type="text" value="POL-998877" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">NIK</label>
                                    <input type="text" value="3271234567890001" disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold px-1">Address</label>
                                    <textarea disabled
                                        class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-sm h-20 resize-none">Jl. Melati No. 123, Jakarta Selatan</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Right Content Area -->
                        <div class="flex-1 flex flex-col overflow-hidden bg-[#16181d]">
                            <!-- Tabs Navigation -->
                            <div class="flex items-center px-6 gap-2 border-b border-gray-700 bg-gray-900/40">
                                <button @click="activeTab = 'data-ticket'"
                                    :class="activeTab === 'data-ticket' ? 'text-blue-400 border-b-2 border-blue-400 bg-blue-400/5' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-300'"
                                    class="px-5 py-2.5 text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="bx bxs-detail text-base"></i> Data Ticket
                                </button>
                                <button @click="activeTab = 'journey-ticket'"
                                    :class="activeTab === 'journey-ticket' ? 'text-blue-400 border-b-2 border-blue-400 bg-blue-400/5' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-300'"
                                    class="px-5 py-2.5 text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="bx bx-git-branch text-base"></i> Journey Ticket
                                </button>
                                <button @click="activeTab = 'internal-note'"
                                    :class="activeTab === 'internal-note' ? 'text-blue-400 border-b-2 border-blue-400 bg-blue-400/5' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-300'"
                                    class="px-5 py-2.5 text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="bx bxs-notepad text-base"></i> Internal Note
                                </button>
                                <button @click="activeTab = 'reminder-ticket'"
                                    :class="activeTab === 'reminder-ticket' ? 'text-blue-400 border-b-2 border-blue-400 bg-blue-400/5' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-300'"
                                    class="px-5 py-2.5 text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="bx bxs-alarm text-base"></i> Reminder Ticket
                                </button>
                            </div>
                            <!-- Tabs Content -->
                            <div class="flex-1 overflow-hidden p-0">
                                <!-- Tab 1: Data Ticket -->
                                <div x-show="activeTab === 'data-ticket'"
                                    class="h-full overflow-y-auto p-8 space-y-8 animate-fadeIn">
                                    <!-- Section 1: Data Fields Grid -->
                                    <div
                                        class="grid grid-cols-4 gap-6 bg-gray-800/30 p-6 rounded-xl border border-gray-700/50">
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Date of
                                                Transaction
                                            </p>
                                            <p class="text-xs text-gray-300">2026-02-11 10:25</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Agent Name</p>
                                            <p class="text-xs text-gray-300">Adjie Sona</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Product Type</p>
                                            <p class="text-xs text-gray-300">Retail</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Product Name</p>
                                            <p class="text-xs text-gray-300">GoCycle Pro</p>
                                        </div>
                                        <!-- Second Row -->
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Customer Status
                                            </p>
                                            <p class="text-xs text-gray-300">Premium</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Customer
                                                Category
                                            </p>
                                            <p class="text-xs text-gray-300">Loyalty</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Polis Number</p>
                                            <p class="text-xs text-gray-300">POL-998877</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] text-blue-400/70 font-bold uppercase">Ticket Channel
                                            </p>
                                            <p class="text-xs text-gray-300">Email</p>
                                        </div>
                                    </div>
                                    <!-- Section 2: Compose Area -->
                                    <div class="flex gap-6">
                                        <div class="flex-1 space-y-4">
                                            <div id="pj-editor-container"
                                                class="rounded-lg overflow-hidden border border-gray-700">
                                                <div id="pj-editor"></div>
                                            </div>
                                            <div class="flex items-center justify-between pt-2">
                                                <button
                                                    class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg text-sm transition-colors border border-gray-600">
                                                    <i class="bx bx-paperclip"></i> Attachment
                                                </button>
                                                <button
                                                    class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg shadow-blue-600/20">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                        <div class="w-64 space-y-4">
                                            <div
                                                class="bg-gray-800/30 p-6 rounded-xl border border-gray-700/50 flex flex-col items-center">
                                                <div class="w-24 h-24 rounded-full bg-blue-500/10 border-4 border-gray-700 flex items-center justify-center mb-4 overflow-hidden shadow-xl relative group cursor-pointer"
                                                    onclick="document.getElementById('customer-avatar-input').click()">
                                                    <img id="customer-avatar-preview"
                                                        src="{{ asset('dashonic/assets/images/users/avatar-1.jpg') }}"
                                                        class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                                    <div
                                                        class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="bx bx-camera text-white text-xl mb-1"></i>
                                                        <span
                                                            class="text-[8px] text-white font-bold uppercase tracking-tighter">Change
                                                            Photo</span>
                                                    </div>
                                                    <input type="file" id="customer-avatar-input" class="hidden"
                                                        accept="image/*" onchange="previewCustomerAvatar(event)">
                                                </div>
                                                <h6 class="text-sm font-bold m-0 text-white">Adjie Sona</h6>
                                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Lead
                                                    Agent
                                                </p>
                                            </div>
                                            <div class="space-y-4">
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Select
                                                        Status</label>
                                                    <select
                                                        class="w-full bg-gray-700 border border-gray-600 text-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                                        <option>Open</option>
                                                        <option>Pending</option>
                                                        <option>In Progress</option>
                                                        <option>Closed</option>
                                                    </select>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label
                                                        class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Select
                                                        Escalation</label>
                                                    <select
                                                        class="w-full bg-gray-700 border border-gray-600 text-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                                                        <option>No</option>
                                                        <option>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Section 3: Journey Conversation -->
                                    <div class="space-y-4 pt-4 border-t border-gray-700/50">
                                        <h6 class="text-xs font-bold uppercase tracking-widest text-blue-400">Journey
                                            Conversation</h6>
                                        <div class="relative pl-8 border-l-2 border-gray-700 space-y-8 py-2">
                                            <div class="relative">
                                                <div
                                                    class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-blue-500 border-4 border-[#16181d]">
                                                </div>
                                                <div
                                                    class="bg-blue-600/10 border border-blue-500/20 p-4 rounded-lg max-w-2xl">
                                                    <p class="text-gray-300 text-sm">Terima kasih telah menghubungi
                                                        kami, Fitiaratna.</p>
                                                </div>
                                            </div>
                                            <div class="relative text-right flex flex-col items-end">
                                                <div
                                                    class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-red-500 border-4 border-[#16181d]">
                                                </div>
                                                <div
                                                    class="bg-red-600/10 border border-red-500/20 p-4 rounded-lg max-w-2xl">
                                                    <p class="text-gray-300 text-sm">Mohon segera diproses pengembalian
                                                        dana saya.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab 2: Journey Ticket -->
                                <div x-show="activeTab === 'journey-ticket'"
                                    class="h-full overflow-y-auto p-8 space-y-8 animate-fadeIn">
                                    <div class="relative pl-8 border-l-2 border-gray-700 space-y-10 py-6">
                                        <div class="relative">
                                            <div
                                                class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-blue-500 border-4 border-[#16181d] shadow-lg shadow-blue-500/20">
                                            </div>
                                            <div
                                                class="bg-gray-800/40 p-5 rounded-xl border border-gray-700 flex flex-col gap-2">
                                                <span
                                                    class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Agent
                                                    Update</span>
                                                <p class="text-sm text-gray-300">Ticket created and assigned to billing
                                                    department.</p>
                                                <span class="text-[10px] text-gray-550 italic">2 hours ago</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <div
                                                class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-emerald-500 border-4 border-[#16181d] shadow-lg shadow-emerald-500/20">
                                            </div>
                                            <div
                                                class="bg-gray-800/40 p-5 rounded-xl border border-gray-700 flex flex-col gap-2">
                                                <span
                                                    class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">System</span>
                                                <p class="text-sm text-gray-300">Status changed from Open to In
                                                    Progress.
                                                </p>
                                                <span class="text-[10px] text-gray-550 italic">1 hour ago</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab 3: Internal Note -->
                                <div x-show="activeTab === 'internal-note'"
                                    class="h-full overflow-y-auto p-8 flex flex-col items-center justify-center text-gray-600">
                                    <i class="bx bx-note text-6xl mb-4 opacity-20"></i>
                                    <p class="text-sm italic font-medium">Internal notes feature coming soon...</p>
                                </div>
                                <!-- Tab 4: Reminder -->
                                <div x-show="activeTab === 'reminder-ticket'"
                                    class="h-full overflow-y-auto p-8 space-y-6 animate-fadeIn pb-20">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] uppercase font-bold text-gray-500 px-1">Judul
                                                Reminder</label>
                                            <input type="text" placeholder="Misal: Follow up refund"
                                                class="w-full bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-blue-500">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] uppercase font-bold text-gray-500 px-1">Set
                                                Date</label>
                                            <div class="flex items-center gap-2">
                                                <input type="date"
                                                    class="flex-1 bg-gray-800/50 border border-gray-700 text-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-blue-500">
                                                <button
                                                    class="p-2.5 bg-gray-700 rounded-lg text-gray-400 hover:text-white border border-gray-600">
                                                    <i class="bx bx-calendar"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] uppercase font-bold text-gray-500 px-1">Reminder
                                            Content</label>
                                        <div class="rounded-lg overflow-hidden border border-gray-700">
                                            <div id="reminder-editor"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end pt-2">
                                        <button
                                            class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg shadow-blue-600/20">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-slot name="css">
            <style>
                /* CKEditor Dark Theme Overrides */
                .ck-editor__editable {
                    background-color: #1f2937 !important;
                    /* gray-800 */
                    color: #d1d5db !important;
                    /* text-gray-300 */
                    min-height: 200px;
                    /* Reduced min-height to accommodate attachments */
                    border-radius: 0.5rem;
                }

                .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                    border-color: #374151 !important;
                    /* gray-700 */
                }

                .ck.ck-editor__main>.ck-editor__editable.ck-focused {
                    border-color: #3b82f6 !important;
                    /* blue-500 */
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                }

                .ck.ck-toolbar {
                    background-color: #374151 !important;
                    /* gray-700 */
                    border-color: #4b5563 !important;
                    border-radius: 0.5rem 0.5rem 0 0;
                }

                .ck.ck-icon,
                .ck.ck-icon * {
                    color: #9ca3af !important;
                    /* gray-400 */
                }

                .ck.ck-button:hover .ck.ck-icon * {
                    color: #fff !important;
                }

                .ck.ck-button.ck-on {
                    background-color: #4b5563 !important;
                }

                .ck.ck-button:hover:not(.ck-disabled) {
                    background-color: #4b5563 !important;
                }

                /* Bwop Animation using Cubic Bezier */
                .ease-bounce {
                    transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
                }

                /* Fade In Animation */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(5px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .animate-fadeIn {
                    animation: fadeIn 0.3s ease-out forwards;
                }

                /* Minimized State Styles */
                .modal-minimized {
                    position: fixed !important;
                    bottom: 0 !important;
                    right: 1.5rem !important;
                    /* right-6 */
                    top: auto !important;
                    left: auto !important;
                    width: 20rem !important;
                    /* w-80 */
                    margin: 0 !important;
                    transform: none !important;
                    pointer-events: auto !important;
                    /* Ensure content is clickable */
                }
            </style>
        </x-slot>
        <x-slot name="js">
            <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
            <script>            console.log('Inbox Email Page - Script loaded via Slot'); let editorInstance; let ccBccVisible = false;            // Handle File Upload Preview            window.handleFileUpload = function (event) {                const preview = document.getElementById('attachment-preview');                const files = event.target.files;                if (files.length > 0) {                    preview.classList.remove('hidden');                    // Normally you would loop through files and append generic template logic here                    // For UI demo, we just show the container which has 1 static item example                }            }            // Define modal functions FIRST            window.toggleComposeModal = function (event) {                console.log('toggleComposeModal called');                if (event) event.preventDefault();                const modal = document.getElementById('compose-modal');                const backdrop = document.getElementById('compose-backdrop');                const modalContent = document.getElementById('compose-content');                if (!modal) {                    console.error('Modal element not found!');                    return;                }                if (modal.classList.contains('hidden')) {                    // Show                    modal.classList.remove('hidden');                    backdrop.classList.remove('hidden');                    // Trigger reflow                    void modal.offsetWidth;                    requestAnimationFrame(() => {                        backdrop.classList.remove('opacity-0');                        backdrop.classList.add('opacity-100');                        // Add bounce class and show                        modalContent.classList.add('ease-bounce');                        modalContent.classList.remove('scale-75', 'opacity-0');                        modalContent.classList.add('scale-100', 'opacity-100');                    });                } else {                    // Hide                    closeComposeModal();                }            }            window.closeComposeModal = function () {                const modal = document.getElementById('compose-modal');                const backdrop = document.getElementById('compose-backdrop');                const modalContent = document.getElementById('compose-content');                backdrop.classList.remove('opacity-100');                backdrop.classList.add('opacity-0');                // Scale down                modalContent.classList.remove('scale-100', 'opacity-100', 'ease-bounce'); // Remove bounce for exit can be smoother/faster                modalContent.classList.add('scale-75', 'opacity-0');                // Reset minimize state if closed                if (isMinimized) {                    toggleMinimizeModal(null, true);                }                setTimeout(() => {                    modal.classList.add('hidden');                    backdrop.classList.add('hidden');                }, 300);            }            window.toggleCcBcc = function () {                ccBccVisible = !ccBccVisible;                const ccField = document.getElementById('cc-field');                const bccField = document.getElementById('bcc-field');                if (ccBccVisible) {                    ccField.classList.remove('hidden');                    ccField.classList.add('flex');                    bccField.classList.remove('hidden');                    bccField.classList.add('flex');                } else {                    ccField.classList.add('hidden');                    ccField.classList.remove('flex');                    bccField.classList.add('hidden');                    bccField.classList.remove('flex');                }            }            let isMinimized = false;            window.toggleMinimizeModal = function (event, forceReset = false) {                if (event) event.stopPropagation();                const modal = document.getElementById('compose-modal');                const modalContent = document.getElementById('compose-content');                const body = document.getElementById('compose-body');                const backdrop = document.getElementById('compose-backdrop');                // Get the minimize button icon                const minBtn = event ? event.currentTarget : document.querySelector('button[onclick="toggleMinimizeModal(event)"]');                const icon = minBtn ? minBtn.querySelector('i') : null;                if (forceReset || isMinimized) {                    // RESTORE/MAXIMIZE                    body.style.display = 'flex';                    // Remove custom minimize styles                    modal.classList.remove('pointer-events-none');                    modalContent.classList.remove('modal-minimized');                    // Restore backdrop                    backdrop.classList.remove('hidden');                    // Change icon back to minus                    if (icon) {                        icon.classList.remove('bx-expand');                        icon.classList.add('bx-minus');                    }                    isMinimized = false;                } else {                    // MINIMIZE                    body.style.display = 'none';                    // Hide backdrop                    backdrop.classList.add('hidden');                    // Allow clicking through the container, but keep content clickable                    modal.classList.add('pointer-events-none');                    // modalContent.classList.remove('pointer-events-none'); // Handled by CSS now                    // Move to bottom right                    modalContent.classList.add('modal-minimized');                    // Change icon to expand                    if (icon) {                        icon.classList.remove('bx-minus');                        icon.classList.add('bx-expand');                    }                    isMinimized = true;                }            }            // Inbox Actions Logic            window.executeSpamAction = function () {                const id = Alpine.store('selectedEmailId') || document.querySelector('[x-data]').__x.$data.selectedEmailId;                if (!id) return;                fetch(`/channel/email/inbox/${id}/spam`, {                    method: 'POST',                    headers: {                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',                        'Accept': 'application/json',                        'Content-Type': 'application/json'                    }                })                    .then(response => response.json())                    .then(data => {                        const row = document.querySelector(`tr[data-email-id="${id}"]`);                        if (row) {                            row.style.transition = 'all 0.5s ease';                            row.style.opacity = '0';                            row.style.transform = 'translateX(20px)';                            setTimeout(() => row.remove(), 500);                        }                        // Reset Alpine state                        const alpineData = document.querySelector('[x-data]').__x.$data;                        alpineData.showSpamModal = false;                        alpineData.selectedEmailId = null;                        Swal.fire({                            title: 'Success!',                            text: 'Message successfully moved to Spam folder.',                            icon: 'success',                            background: '#1f2937',                            color: '#fff',                            toast: true,                            position: 'top-end',                            showConfirmButton: false,                            timer: 3000                        });                    })                    .catch(error => {                        console.error('Error moving to spam:', error);                        Swal.fire({                            title: 'Error!',                            text: 'An error occurred while moving the message to the Spam folder.',                            icon: 'error',                            background: '#1f2937',                            color: '#fff'                        });                    });            }            window.handleSpamAction = function (id) {                // This legacy function is now replaced by Alpine.js trigger                console.warn('handleSpamAction called directly. Use Alpine.js instead.');            }            window.handleReplyAction = function (id, sender) {                // Change title                const title = document.getElementById('compose-title');                if (title) title.style.display = 'none'; // Hide title as requested                // Set recipient                const toInput = document.getElementById('compose-to');                if (toInput) toInput.value = sender;                // Open modal                toggleComposeModal();            }            // Patch existing closeComposeModal to restore title            const originalCloseComposeModal = window.closeComposeModal;            window.closeComposeModal = function () {                const title = document.getElementById('compose-title');                if (title) title.style.display = 'block';                originalCloseComposeModal();            }            // Assign Modal Logic            window.handleAssignAction = function (id) {                const modal = document.getElementById('assign-modal');                const content = document.getElementById('assign-content');                const backdrop = document.getElementById('assign-backdrop');                modal.classList.remove('hidden');                modal.classList.add('flex');                setTimeout(() => {                    if (backdrop) backdrop.classList.add('opacity-100');                    content.classList.remove('scale-75', 'opacity-0');                    content.classList.add('scale-100', 'opacity-100');                }, 10);            }            window.closeAssignModal = function () {                const modal = document.getElementById('assign-modal');                const content = document.getElementById('assign-content');                const backdrop = document.getElementById('assign-backdrop');                if (backdrop) backdrop.classList.remove('opacity-100');                content.classList.remove('scale-100', 'opacity-100');                content.classList.add('scale-75', 'opacity-0');                setTimeout(() => {                    modal.classList.add('hidden');                    modal.classList.remove('flex');                }, 300);            }            window.submitAssignAction = function () {                const agent = document.getElementById('assign-agent').value;                const reason = document.getElementById('assign-reason').value;                if (!agent) {                    Swal.fire({                        title: 'Error!',                        text: 'Please select a User Agent.',                        icon: 'error',                        background: '#1f2937',                        color: '#fff'                    });                    return;                }                Swal.fire({                    title: 'Success!',                    text: `Assigned to ${agent} successfully.`,                    icon: 'success',                    background: '#1f2937',                    color: '#fff'                });                closeAssignModal();            }            window.handleForwardAction = function (id) {                // Similar to reply                const title = document.getElementById('compose-title');                if (title) title.style.display = 'none';                // Set recipient dummy for forward                const toInput = document.getElementById('compose-to');                if (toInput) toInput.value = ''; // Clear or set placeholder                toggleComposeModal();            }            // Conversation Modal Functions            window.handleConversationAction = function (id) {                const modal = document.getElementById('conversation-modal');                const content = document.getElementById('conversation-content');                modal.classList.remove('hidden');                modal.classList.add('flex');                setTimeout(() => {                    content.classList.remove('scale-75', 'opacity-0');                    content.classList.add('scale-100', 'opacity-100');                }, 10);            }            window.closeConversationModal = function () {                const modal = document.getElementById('conversation-modal');                const content = document.getElementById('conversation-content');                content.classList.remove('scale-100', 'opacity-100');                content.classList.add('scale-75', 'opacity-0');                setTimeout(() => {                    modal.classList.add('hidden');                    modal.classList.remove('flex');                }, 300);            }            // Preview Journey Modal Functions            let pjEditorInstance;            let reminderEditorInstance;            window.handlePreviewJourneyAction = function (id) {                const modal = document.getElementById('preview-journey-modal');                const content = document.getElementById('pj-content');                modal.classList.remove('hidden');                modal.classList.add('flex');                // Initialize Editors if not already done                initPJEditors();                setTimeout(() => {                    content.classList.remove('scale-95', 'opacity-0');                    content.classList.add('scale-100', 'opacity-100');                }, 10);            }            window.closePreviewJourneyModal = function () {                const modal = document.getElementById('preview-journey-modal');                const content = document.getElementById('pj-content');                content.classList.remove('scale-100', 'opacity-100');                content.classList.add('scale-95', 'opacity-0');                setTimeout(() => {                    modal.classList.add('hidden');                    modal.classList.remove('flex');                }, 300);            }            window.previewCustomerAvatar = function (event) {                const input = event.target;                const preview = document.getElementById('customer-avatar-preview');                if (input.files && input.files[0]) {                    const reader = new FileReader();                    reader.onload = function (e) {                        preview.src = e.target.result;                        Swal.fire({                            title: 'Photo updated!',                            text: 'Customer profile photo has been updated locally.',                            icon: 'success',                            toast: true,                            position: 'top-end',                            showConfirmButton: false,                            timer: 3000,                            background: '#1f2937',                            color: '#fff'                        });                    }                    reader.readAsDataURL(input.files[0]);                }            }            function initPJEditors() {                if (typeof ClassicEditor === 'undefined') return;                // Data Ticket Editor                if (!pjEditorInstance && document.querySelector('#pj-editor')) {                    ClassicEditor                        .create(document.querySelector('#pj-editor'), {                            toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],                            placeholder: 'Type response to customer here...'                        })                        .then(editor => {                            pjEditorInstance = editor;                        })                        .catch(error => console.error(error));                }                // Reminder Editor                if (!reminderEditorInstance && document.querySelector('#reminder-editor')) {                    ClassicEditor                        .create(document.querySelector('#reminder-editor'), {                            toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo'],                            placeholder: 'Add reminder details...'                        })                        .then(editor => {                            reminderEditorInstance = editor;                        })                        .catch(error => console.error(error));                }            }            // Close modal when clicking backdrop            document.addEventListener('click', function (e) {                const backdrop = document.getElementById('compose-backdrop');                if (e.target === backdrop) {                    closeComposeModal();                }                const assignBackdrop = document.getElementById('assign-backdrop');                if (e.target === assignBackdrop) {                    closeAssignModal();                }                const conversationBackdrop = document.getElementById('conversation-backdrop');                if (e.target === conversationBackdrop) {                    closeConversationModal();                }                const pjBackdrop = document.getElementById('pj-backdrop');                if (e.target === pjBackdrop) {                    closePreviewJourneyModal();                }            });            // Initialize CKEditor            document.addEventListener('DOMContentLoaded', function () {                if (typeof ClassicEditor === 'undefined') return;                try {                    ClassicEditor                        .create(document.querySelector('#editor'), {                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],                            placeholder: 'Type your message here...'                        })                        .then(editor => {                            editorInstance = editor;                            console.log('CKEditor initialized successfully');                        })                        .catch(error => {                            console.error('CKEditor initialization error:', error);                        });                } catch (error) {                    console.error('CKEditor creation failed:', error);                }            });            // Mark email as read function            window.markAsRead = function (rowElement, emailId) {                // Check if already read                if (rowElement.classList.contains('read')) {                    return; // Already read, do nothing                }                console.log('Marking email ' + emailId + ' as read...');                fetch(`/channel/email/inbox/${emailId}/mark-read`, {                    method: 'POST',                    headers: {                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',                        'Accept': 'application/json',                        'Content-Type': 'application/json'                    }                })                    .then(response => response.json())                    .then(data => {                        // Update row styling                        rowElement.classList.remove('unread', 'font-semibold');                        rowElement.classList.add('read', 'font-normal');                        // Update individual cells for visual consistency                        const cells = rowElement.querySelectorAll('td');                        // ID column (index 0)                        const idSpan = cells[0].querySelector('span');                        if (idSpan) {                            idSpan.classList.remove('text-blue-300', 'font-bold');                            idSpan.classList.add('text-blue-400/60', 'font-medium');                        }                        // Email Service (index 1)                        const serviceSpan = cells[1].querySelector('span');                        if (serviceSpan) {                            serviceSpan.classList.remove('text-blue-300', 'font-semibold');                            serviceSpan.classList.add('text-blue-400/50', 'font-normal');                        }                        // From/To column (index 2)                        cells[2].classList.remove('text-gray-100', 'font-semibold');                        cells[2].classList.add('text-gray-400', 'font-normal');                        // Subject (index 3)                        const subjectSpan = cells[3].querySelector('span');                        if (subjectSpan) {                            subjectSpan.classList.remove('text-white', 'font-bold');                            subjectSpan.classList.add('text-gray-400', 'font-normal');                        }                        // Date (index 5)                        cells[5].classList.remove('text-gray-300');                        cells[5].classList.add('text-gray-500');                        // Update status badge (index 4)                        const statusBadge = cells[4].querySelector('span');                        if (statusBadge && statusBadge.classList.contains('bg-yellow-500/20')) {                            statusBadge.classList.remove('bg-yellow-500/20', 'text-yellow-400');                            statusBadge.classList.add('bg-gray-500/20', 'text-gray-400');                            statusBadge.textContent = 'Read';                        }                    })                    .catch(error => {                        console.error('Error marking as read:', error);                    });            }    
            </script>
    </div>
    </x-slot>
</x-dashonic-horizontal-layout>