<x-dashonic-horizontal-layout>
    <style>
        /* Main page container */
        .journey-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-height: 100vh;
            padding: 20px 24px;
            box-sizing: border-box;
            overflow: hidden;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.05), transparent),
                radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.05), transparent),
                #0f172a;
        }

        /* Three-column workspace container */
        .journey-workspaces {
            display: grid;
            grid-template-columns: 320px 1fr 380px;
            gap: 16px;
            flex: 1;
            min-height: 0;
            margin-top: 0 !important;
        }

        /* Glassmorphism card style */
        .workspace-card {
            display: flex;
            flex-direction: column;
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            height: 100%;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        /* Card header */
        .workspace-card-header {
            flex-shrink: 0;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.3);
        }

        /* Card body (scrollable content) */
        .workspace-card-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.5rem;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Profile spacing */
        .profile-field-group {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .profile-field-group:hover {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(59, 130, 246, 0.2);
        }

        /* Timeline adjustments */
        .journey-timeline-line {
            width: 2px;
            background: linear-gradient(to bottom, rgba(99, 102, 241, 0.5), rgba(99, 102, 241, 0.1));
            position: absolute;
            left: 23px;
            top: 0;
            bottom: 0;
        }

        /* Pulse animation for live indicators */
        @keyframes pulse-soft {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }

        .animate-pulse-soft {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .journey-workspaces {
                grid-template-columns: 280px 1fr 340px;
            }
        }

        @media (max-width: 1200px) {
            .journey-workspaces {
                grid-template-columns: 260px 1fr 300px;
                gap: 12px;
            }

            .journey-page {
                padding: 16px 20px;
            }
        }

        @media (max-width: 1024px) {
            .journey-page {
                height: auto;
                max-height: none;
                overflow: visible;
            }

            .journey-workspaces {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .workspace-card {
                height: auto;
                max-height: 800px;
            }
        }
    </style>

    <div class="journey-page">
        <!-- Three Column Workspaces -->
        <div class="journey-workspaces">

            <!-- Left Sidebar: Customer Profile -->
            <div class="workspace-card card-glow-blue">
                <div class="workspace-card-header bg-blue-500/5">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20">
                            <i class="bx bxs-user-detail text-lg"></i>
                        </div>
                        <h5 class="text-blue-400 font-bold mb-0 tracking-tight">Information Profile</h5>
                    </div>
                </div>
                <div class="workspace-card-body custom-scrollbar space-y-5">
                    <!-- Full Name -->
                    <div class="group">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Full
                            Name</label>
                        <div class="profile-field-group">
                            <span
                                class="text-white text-sm font-semibold">{{ $ticketData['name'] ?? 'Guest Customer' }}</span>
                        </div>
                    </div>
                    <!-- Phone Number -->
                    <div class="group">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Phone
                            Number</label>
                        <div class="profile-field-group">
                            <div class="flex items-center gap-2">
                                <i class="bx bx-phone text-blue-400/60"></i>
                                <span
                                    class="text-white text-sm font-medium">{{ $ticketData['phone'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Email Address -->
                    <div class="group">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Email
                            Address</label>
                        <div class="profile-field-group">
                            <div class="flex items-center gap-2">
                                <i class="bx bx-envelope text-blue-400/60"></i>
                                <span class="text-white text-sm font-medium">{{ $ticketData['email'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Member ID -->
                    <div class="group">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Member
                            ID</label>
                        <div class="profile-field-group">
                            <div class="flex items-center gap-2">
                                <i class="bx bx-id-card text-blue-400/60"></i>
                                <span
                                    class="text-white text-sm font-medium">{{ $ticketData['member_id'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Journey Timeline -->
            <div class="flex flex-col min-h-0">
                <!-- Customer Header -->
                <div
                    class="bg-gray-800/40 backdrop-blur-md rounded-2xl shadow-xl p-5 mb-4 flex-none flex items-center justify-between shrink-0 border border-white/10">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div
                                class="w-14 h-14 rounded-2xl border-2 border-blue-500/30 flex items-center justify-center overflow-hidden bg-gray-900 shadow-inner">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($ticketData['name'] ?? 'Guest') }}&background=0284c7&color=fff"
                                    class="w-full h-full object-cover">
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-gray-800 rounded-full animate-pulse-soft">
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                {{ $ticketData['name'] ?? 'Guest Customer' }}
                                </h4>
                                <span
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase">Regular</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                <p class="text-blue-400/80 text-xs font-mono font-bold tracking-wider">
                                    #{{ $ticketData['ticket_number'] ?? 'NEW' }}
                                </p>
                                <span class="text-gray-500 text-[10px] flex items-center gap-1">
                                    <i class="bx bx-time-five"></i> 2 mins ago
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button id="email-compose-trigger"
                            class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all duration-300 border border-blue-500/20 shadow-lg shadow-blue-500/10 group">
                            <i class="bx bx-envelope text-xl group-hover:scale-110 transition-transform"></i>
                        </button>
                        <button id="call-trigger"
                            class="w-11 h-11 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all duration-300 border border-green-500/20 shadow-lg shadow-green-500/10 group">
                            <i class="bx bx-phone text-xl group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- Timeline Workspace Card -->
                <div class="workspace-card">
                    <div class="workspace-card-body custom-scrollbar">
                        <div class="journey-timeline">
                            <div class="journey-timeline-line"></div>

                            <!-- Timeline Start -->
                            <div class="flex gap-6 relative mb-10 group">
                                <div class="w-12 flex justify-center z-10">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg border-4 border-gray-900 group-hover:scale-110 transition-transform duration-300">
                                        <i class="bx bx-map text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="bg-indigo-500/10 backdrop-blur-sm rounded-2xl p-5 shadow-lg border border-indigo-500/20 flex justify-between items-center hover:bg-indigo-500/15 transition-all duration-300">
                                        <div>
                                            <h4 class="text-indigo-400 font-bold text-base tracking-tight">Journey
                                                Started</h4>
                                            <p class="text-gray-400 text-xs mt-1 font-medium">Ticket
                                                #{{ $ticketData['ticket_number'] ?? 'NEW' }} Created</p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-indigo-400/80 text-[10px] font-bold block uppercase tracking-widest">Initial
                                                Phase</span>
                                            <span
                                                class="text-gray-500 text-[10px] font-mono mt-1 block">{{ \Carbon\Carbon::parse($ticketData['date'] ?? now())->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @forelse($journey as $item)
                                <!-- Timeline Item -->
                                <div class="flex gap-6 relative mb-10 group">
                                    <div class="w-12 flex justify-center z-10">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center shadow-lg border-4 border-gray-900 group-hover:scale-110 transition-transform duration-300">
                                            <i class="bx bx-support text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="bg-gray-800/40 backdrop-blur-sm rounded-2xl p-6 shadow-xl border border-white/5 hover:border-orange-500/30 transition-all duration-300 group-hover:bg-gray-800/50">
                                            <div class="flex justify-between items-start mb-4 border-b border-white/5 pb-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400 border border-orange-500/20">
                                                        {{ substr($item->userAgent->full_name ?? 'Agent', 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <h4 class="text-white font-bold text-base tracking-tight">
                                                            {{ $item->userAgent->full_name ?? 'Support Agent' }}
                                                        </h4>
                                                        <p
                                                            class="text-orange-400/80 text-[10px] font-bold uppercase tracking-widest">
                                                            {{ $item->source_type ?? 'Agent Support' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-gray-300 text-xs font-bold block">{{ $item->created_at->format('H:i:s P') }}</span>
                                                    <span class="text-gray-500 text-[10px] font-medium">{{ $item->created_at->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-gray-300 text-sm leading-relaxed">
                                                <p class="font-medium">"{{ $item->question ?? $item->subject }}"</p>
                                                <p class="font-medium mt-2 text-blue-300">"{{ $item->answer }}"</p>
                                                <div class="mt-4 flex gap-2">
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-wide">
                                                        <i class="bx bx-tag-alt"></i> Category: {{ $item->category }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20">
                                    <div class="bg-gray-800/50 rounded-2xl p-10 border border-white/5 inline-block">
                                        <i class="bx bx-map-alt text-5xl text-gray-700 mb-4 block"></i>
                                        <h4 class="text-white font-bold mb-1">No Journey Found</h4>
                                        <p class="text-gray-500 text-sm">Wait for interactions to populate the timeline.</p>
                                    </div>
                                </div>
                            @endforelse

                            <!-- End Node -->
                            <div class="flex gap-6 relative group">
                                <div class="w-12 flex justify-center z-10">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gray-800/80 flex items-center justify-center shadow-lg border-4 border-gray-900 group-hover:scale-110 transition-transform duration-300">
                                        <i class="bx bx-flag text-gray-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1 pt-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <div
                                        class="bg-gray-900/30 rounded-2xl p-5 border border-white/5 backdrop-blur-sm shadow-inner text-center">
                                        <h4
                                            class="text-gray-500 font-bold text-sm uppercase tracking-widest">
                                            End of Timeline</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note Input -->
                        <div
                            class="mt-8 border border-white/5 rounded-2xl overflow-hidden shadow-2xl bg-gray-900/80 backdrop-blur-md group focus-within:ring-2 focus-within:ring-blue-500/20 transition-all duration-300">
                            <div class="bg-gray-800/50 p-3 flex gap-1 border-b border-white/5">
                                <div
                                    class="flex items-center gap-1 bg-gray-900/50 rounded-lg p-1 mr-2 px-2 border border-white/5">
                                    <button type="button" data-format="bold"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bx-bold"></i></button>
                                    <button type="button" data-format="italic"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bx-italic"></i></button>
                                    <button type="button" data-format="strikethrough"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bx-strikethrough"></i></button>
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-gray-900/50 rounded-lg p-1 mr-2 px-2 border border-white/5">
                                    <button type="button" data-format="ul"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bx-list-ul"></i></button>
                                    <button type="button" data-format="ol"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bx-list-ol"></i></button>
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-gray-900/50 rounded-lg p-1 mr-2 px-2 border border-white/5">
                                    <button type="button" data-format="quote"
                                        class="toolbar-btn p-2 hover:bg-gray-700/50 rounded-md text-gray-400 hover:text-white transition-all"><i
                                            class="bx bxs-quote-alt-left"></i></button>
                                </div>
                            </div>
                            <textarea id="note-textarea"
                                class="w-full bg-transparent border-0 text-white text-sm p-5 h-32 focus:ring-0 resize-none placeholder-gray-600 leading-relaxed"
                                placeholder="Type your note here..."></textarea>
                            <div
                                class="px-5 py-4 bg-gray-800/50 border-t border-white/5 flex justify-between items-center">
                                <div class="flex gap-3">
                                    <div class="relative">
                                        <select
                                            class="appearance-none bg-gray-900/60 border border-white/10 text-gray-300 text-xs rounded-xl pl-4 pr-10 py-2.5 focus:border-blue-500/50 cursor-pointer outline-none transition-all hover:bg-gray-900">
                                            <option>Open Status</option>
                                            <option>Closed Status</option>
                                            <option>Pending Status</option>
                                        </select>
                                        <i
                                            class="bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                                    </div>
                                    <div class="relative">
                                        <select
                                            class="appearance-none bg-gray-900/60 border border-white/10 text-gray-300 text-xs rounded-xl pl-4 pr-10 py-2.5 focus:border-blue-500/50 cursor-pointer outline-none transition-all hover:bg-gray-900">
                                            <option>No Escalation</option>
                                            <option>Escalated to L2</option>
                                            <option>Escalated to L3</option>
                                        </select>
                                        <i
                                            class="bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                                    </div>
                                </div>
                                <button
                                    class="h-11 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center gap-2 shadow-lg hover:shadow-blue-500/30 transition-all hover:scale-[1.05] active:scale-95 group font-bold text-sm">
                                    <span>Post Note</span>
                                    <i class="bx bx-send text-lg transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Ticket Details -->
            <div class="workspace-card card-glow-indigo">
                <div class="workspace-card-header bg-indigo-500/5">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                            <i class="bx bx-info-circle text-lg"></i>
                        </div>
                        <h5 class="text-indigo-400 font-bold mb-0 tracking-tight uppercase text-xs">Ticket
                            Information</h5>
                    </div>
                </div>
                <div class="workspace-card-body custom-scrollbar space-y-6">
                    <!-- Group: Reporter Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-1 h-4 bg-blue-500 rounded-full"></span>
                            <h6 class="text-white text-[11px] font-bold uppercase tracking-widest">Reporter Info
                            </h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                <label class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Full
                                    Name Reported</label>
                                <p class="text-gray-200 text-sm font-bold">{{ $ticketData['name'] ?? 'Guest Customer' }}
                                </p>
                            </div>
                            <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                <label class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Email
                                    Reported</label>
                                <p class="text-gray-200 text-sm font-semibold">{{ $ticketData['email'] ?? '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                    <label
                                        class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Contact</label>
                                    <p class="text-gray-200 text-xs font-semibold">
                                        {{ $ticketData['phone'] ?? '-' }}
                                    </p>
                                </div>
                                <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                    <label
                                        class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Account</label>
                                    <p class="text-gray-200 text-xs font-semibold">
                                        {{ $ticketData['account'] ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-white/5"></div>

                    <!-- Group: Transaction Details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-1 h-4 bg-indigo-500 rounded-full"></span>
                            <h6 class="text-white text-[11px] font-bold uppercase tracking-widest">Transaction
                                Details</h6>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                <label class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Created
                                    Date</label>
                                <p class="text-gray-200 text-xs font-bold">{{ $ticketData['date'] ?? '-' }}
                                </p>
                            </div>
                            <div class="bg-gray-900/40 p-3 rounded-xl border border-white/5">
                                <label class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Assigned
                                    Agent</label>
                                <p class="text-blue-400 text-xs font-bold">
                                    {{ $ticketData['agent'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="bg-blue-500/5 p-4 rounded-xl border border-blue-500/20 group hover:bg-blue-500/10 transition-all cursor-pointer">
                            <label
                                class="text-blue-400/60 text-[9px] uppercase font-bold tracking-widest block mb-1">Order
                                Identifier</label>
                            <div class="flex items-center justify-between">
                                <p class="text-blue-400 text-base font-black font-mono tracking-tighter">
                                    {{ $ticketData['order_id'] ?? '-' }}
                                </p>
                                <i class="bx bx-copy text-blue-500 group-hover:scale-125 transition-transform"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-purple-500/5 p-3 rounded-xl border border-purple-500/20">
                                <label
                                    class="text-purple-400/60 text-[9px] uppercase font-bold tracking-tighter">Channel</label>
                                <p class="text-purple-400 text-[10px] font-black uppercase">{{ $ticketData['channel'] ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-700/20 p-3 rounded-xl border border-white/5">
                                <label
                                    class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter">Fulfillment</label>
                                <p class="text-gray-300 text-[10px] font-black uppercase">{{ $ticketData['fulfillment'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-white/5"></div>

                    <!-- Layout: Escalation & Status -->
                    <div class="bg-gray-900/60 rounded-2xl p-4 border border-white/5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                             <div>
                                <label
                                    class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter block mb-1">Escalation</label>
                                <span
                                    class="inline-flex px-2 py-1 rounded bg-red-500/10 text-red-400 border border-red-500/20 text-[10px] font-black">{{ strtoupper($ticketData['posisi'] ?? '-') }}</span>
                            </div>
                            <div>
                                <label
                                    class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter block mb-1">Unit</label>
                                <p class="text-white text-xs font-bold">{{ $ticketData['department'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-white/5">
                            <label
                                class="text-gray-500 text-[9px] uppercase font-bold tracking-tighter block mb-2">Current
                                Status</label>
                            <span
                                class="flex items-center justify-center py-2 px-4 rounded-xl text-xs font-black uppercase tracking-widest {{ strtolower($ticketData['status'] ?? 'closed') == 'open' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-green-500/20 text-green-400 border border-green-500/30' }}">
                                <i class="bx bxs-circle text-[8px] mr-2 animate-pulse"></i>
                                {{ $ticketData['status'] ?? 'Closed' }}
                            </span>
                        </div>
                    </div>

                    <div class="h-px bg-white/5"></div>

                    <!-- Customer Complaints Pod -->
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bx bx-message-square-error text-red-400"></i>
                            <h6 class="text-white text-[11px] font-bold uppercase tracking-widest">Customer
                                Complaint</h6>
                        </div>
                        <div class="bg-gray-900/50 rounded-2xl p-4 border border-white/5 relative group cursor-help">
                            <div
                                class="absolute top-4 right-4 text-blue-500/30 group-hover:text-blue-500 transition-colors">
                                <i class="bx bxs-quote-right text-2xl"></i>
                            </div>
                            <p class="text-gray-300 text-xs leading-relaxed font-medium pr-8">
                                Customer is inquiring about Ticket #{{ $ticketData['ticket_number'] ?? 'NEW' }}
                                regarding {{ strtolower($ticketData['category'] ?? 'general services') }}.
                                Please ensure a timely response for {{ $ticketData['name'] ?? 'the customer' }}.
                            </p>
                            <button
                                class="text-blue-400 text-[10px] font-black mt-3 hover:text-blue-300 uppercase tracking-widest flex items-center gap-1">
                                <span>Read Full Complaint</span>
                                <i class="bx bx-right-arrow-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="h-px bg-white/5"></div>

                    <!-- Product Detail Pod -->
                    <div class="bg-indigo-500/5 rounded-2xl p-5 border border-indigo-500/10">
                        <h6 class="text-indigo-400 text-[11px] font-black uppercase tracking-widest mb-4">Product
                            Detail</h6>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span
                                    class="text-gray-500 font-bold uppercase tracking-tighter text-[10px]">Marketplace</span>
                                <span
                                    class="px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20 font-black text-[10px]">SHOPEE</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span
                                    class="text-gray-500 font-bold uppercase tracking-tighter text-[10px]">Quantity</span>
                                <span class="text-white font-black">1 Units</span>
                            </div>
                            <div
                                class="flex justify-between items-center bg-green-500/10 p-3 rounded-xl border border-green-500/20">
                                <span class="text-green-400 font-bold uppercase tracking-tighter text-[10px]">Total
                                    Order</span>
                                <span class="text-green-400 font-black text-sm">Rp 159.000</span>
                            </div>
                            <div class="pt-3 border-t border-white/5 space-y-3">
                                <div class="flex items-start gap-3">
                                    <i class="bx bx-map-pin text-gray-500 mt-0.5"></i>
                                    <div>
                                        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-tighter mb-1">
                                            Shipping Destination</p>
                                        <p class="text-gray-200 text-xs font-bold leading-tight">
                                            {{ $ticketData['name'] ?? 'Guest' }} -
                                            {{ isset($ticketData['name']) ? '628' . substr(crc32($ticketData['name']), 0, 10) : '-' }}
                                        </p>
                                        <p class="text-gray-400 text-[10px] mt-1 leading-relaxed">Jalan Mawar No.
                                            12, Kelurahan Melati, Kecamatan Anggrek, Jakarta Selatan</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="bx bxs-truck text-indigo-400"></i>
                                    <p class="text-indigo-400 text-[10px] font-black uppercase tracking-widest">JNE
                                        REGULAR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
        }
    </style>

    <!-- Email Compose Modal -->
    <div id="email-compose-modal"
        class="hidden fixed inset-0 z-[101] flex items-center justify-center p-4 transition-opacity duration-300">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-md"></div>

        <!-- Modal Content -->
        <div
            class="relative bg-gray-900/90 w-full max-w-4xl rounded-[2rem] shadow-2xl border border-white/10 flex flex-col max-h-[90vh] backdrop-blur-xl overflow-hidden glass-card">
            <!-- Header -->
            <div class="flex justify-between items-center p-8 border-b border-white/5 shrink-0 bg-white/5">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 border border-blue-500/30">
                        <i class="bx bx-envelope text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tight">Compose Email</h3>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-0.5">Customer Outreach
                            Portal</p>
                    </div>
                </div>
                <button id="close-email-modal"
                    class="text-gray-500 hover:text-white transition-all p-2 rounded-xl hover:bg-white/10">
                    <i class="bx bx-x text-3xl"></i>
                </button>
            </div>

            <!-- Form Body -->
            <div class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-gray-900/30">
                <!-- Selectors Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-gray-500 text-[10px] font-black uppercase tracking-widest ml-1">From
                            Address</label>
                        <div class="relative group">
                            <select
                                class="w-full bg-gray-950/50 border border-white/10 text-gray-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none">
                                <option>support@kanmogroup.com</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                <i class="bx bx-chevron-down text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-500 text-[10px] font-black uppercase tracking-widest ml-1">Email
                            Template</label>
                        <div class="relative group">
                            <select
                                class="w-full bg-gray-950/50 border border-white/10 text-gray-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all appearance-none">
                                <option>Standard Ticket Response</option>
                                <option>Product Defect Follow-up</option>
                                <option>Custom Content</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                <i class="bx bx-chevron-down text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recipients List -->
                <div class="space-y-4 bg-gray-950/30 p-6 rounded-2xl border border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-16 text-right">
                            <label class="text-gray-500 text-[10px] font-black uppercase tracking-tighter">To</label>
                        </div>
                        <input type="text"
                            value="{{ $ticketData['email'] ?? '' }}"
                            class="flex-1 bg-transparent border-b border-white/10 text-white font-bold text-sm py-2 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-16 text-right">
                            <label class="text-gray-500 text-[10px] font-black uppercase tracking-tighter">Cc</label>
                        </div>
                        <input type="text" placeholder="Add recipients..."
                            class="flex-1 bg-transparent border-b border-white/10 text-gray-400 text-sm py-2 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                <!-- Subject -->
                <div class="space-y-2">
                    <label class="text-gray-500 text-[10px] font-black uppercase tracking-widest ml-1">Subject
                        Line</label>
                    <input type="text"
                        value="[Ticket#{{ $ticketData['ticket_number'] ?? 'NEW' }}] Re: {{ $ticketData['category'] ?? 'Follow-up' }}"
                        class="w-full bg-gray-950/50 border border-white/10 text-white font-black text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Editor Surface -->
                <div class="border border-white/10 rounded-2xl overflow-hidden bg-gray-950 shadow-inner group">
                    <div class="bg-white/5 border-b border-white/10 p-3 flex flex-wrap gap-2 items-center">
                        <div class="flex bg-black/20 rounded-lg p-1 border border-white/5">
                            <button
                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors"><i
                                    class="bx bx-bold"></i></button>
                            <button
                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors"><i
                                    class="bx bx-italic"></i></button>
                        </div>
                        <div class="flex bg-black/20 rounded-lg p-1 border border-white/5">
                            <button
                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors"><i
                                    class="bx bx-list-ul"></i></button>
                            <button
                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors"><i
                                    class="bx bx-list-ol"></i></button>
                        </div>
                        <div class="h-6 w-px bg-white/10 mx-1"></div>
                        <button
                            class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors text-xs font-black uppercase tracking-widest">
                            <i class="bx bx-plus-circle mr-1"></i> Add Variable
                        </button>
                    </div>
                    <textarea
                        class="w-full bg-transparent text-gray-200 text-base p-6 h-80 focus:ring-0 outline-none custom-scrollbar resize-none leading-relaxed font-medium"
                        placeholder="Start typing your official response..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-white/5 bg-white/5 flex justify-between items-center shrink-0">
                <button
                    class="flex items-center gap-2 px-5 py-3 bg-white/5 text-gray-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-all border border-white/10 group">
                    <i class="bx bx-paperclip text-lg group-hover:rotate-45 transition-transform"></i> Attach Documents
                </button>
                <div class="flex gap-4 items-center">
                    <button
                        class="px-6 py-3 text-gray-500 hover:text-white text-xs font-black uppercase tracking-widest transition-colors"
                        id="cancel-email-modal">Dismiss</button>
                    <button
                        class="flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:translate-y-[-2px] hover:shadow-xl hover:shadow-blue-500/20 active:translate-y-0 transition-all">
                        <span>Send Message</span>
                        <i class="bx bx-send text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Call Confirmation Modal -->
    <div id="call-confirmation-modal"
        class="hidden fixed inset-0 z-[102] flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="fixed inset-0 bg-gray-950/90 backdrop-blur-md"></div>
        <div
            class="relative bg-gray-900 w-full max-w-sm rounded-[2.5rem] shadow-2xl p-10 flex flex-col items-center text-center border border-white/10 transform scale-100 transition-transform duration-300 glass-card overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-orange-500/20 rounded-full blur-3xl"></div>

            <!-- Icon -->
            <div
                class="relative w-28 h-28 rounded-[2rem] bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center mb-8 border border-orange-500/30">
                <i class="bx bx-phone-call text-6xl text-orange-500 animate-bounce"></i>
            </div>
            <!-- Title -->
            <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Initiate Voice Call?</h3>
            <p class="text-gray-400 text-sm mb-10 leading-relaxed font-medium">You are about to call the customer via
                the integrated voice gateway. Standard logs will be recorded.</p>

            <!-- Buttons -->
            <div class="flex flex-col gap-3 w-full">
                <button
                    class="w-full py-4 bg-orange-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 shadow-lg shadow-orange-500/30 active:scale-95 transition-all">
                    Yes, Connect Now
                </button>
                <button id="cancel-call-modal"
                    class="w-full py-4 bg-white/5 text-gray-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-colors border border-white/10">
                    Cancel Request
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes bwop-in {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bwop-out {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        .animate-bwop-in {
            animation: bwop-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .animate-bwop-out {
            animation: bwop-out 0.3s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailModal = document.getElementById('email-compose-modal');
            const emailTrigger = document.getElementById('email-compose-trigger');
            const closeEmailModal = document.getElementById('close-email-modal');
            const cancelEmailModal = document.getElementById('cancel-email-modal');
            const modalContent = emailModal?.querySelector('.relative.bg-gray-800'); // Select the content container

            if (emailModal && emailTrigger && closeEmailModal) {
                const openModal = () => {
                    emailModal.classList.remove('hidden');
                    // Reset animation classes
                    modalContent.classList.remove('animate-bwop-out');
                    modalContent.classList.add('animate-bwop-in');
                    document.body.style.overflow = 'hidden';
                };

                const closeModal = () => {
                    // Play exit animation
                    modalContent.classList.remove('animate-bwop-in');
                    modalContent.classList.add('animate-bwop-out');

                    // Hide after animation finishes
                    setTimeout(() => {
                        emailModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        modalContent.classList.remove('animate-bwop-out'); // Clean up
                    }, 300); // Match duration of bwop-out
                };

                emailTrigger.onclick = openModal;
                closeEmailModal.onclick = closeModal;

                if (cancelEmailModal) {
                    cancelEmailModal.onclick = closeModal;
                }

                emailModal.onclick = (e) => {
                    if (e.target === emailModal) {
                        closeModal();
                    }
                };
            }

            // Call Modal Logic
            const callModal = document.getElementById('call-confirmation-modal');
            const callTrigger = document.getElementById('call-trigger');
            const cancelCallModal = document.getElementById('cancel-call-modal');
            const callModalContent = callModal?.querySelector('.relative.bg-gray-800');

            if (callModal && callTrigger && cancelCallModal) {
                const openCallModal = () => {
                    callModal.classList.remove('hidden');
                    callModalContent.classList.remove('animate-bwop-out');
                    callModalContent.classList.add('animate-bwop-in');
                    document.body.style.overflow = 'hidden';
                }

                const closeCallModal = () => {
                    callModalContent.classList.remove('animate-bwop-in');
                    callModalContent.classList.add('animate-bwop-out');
                    setTimeout(() => {
                        callModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        callModalContent.classList.remove('animate-bwop-out');
                    }, 300);
                }

                callTrigger.onclick = openCallModal;
                cancelCallModal.onclick = closeCallModal;

                // Close on OK button for demo
                const okButton = cancelCallModal.nextElementSibling;
                if (okButton) okButton.onclick = closeCallModal;

                callModal.onclick = (e) => {
                    if (e.target === callModal) {
                        closeCallModal();
                    }
                };
            }

            // Rich Text Editor Toolbar Functionality
            const noteTextarea = document.getElementById('note-textarea');
            const toolbarButtons = document.querySelectorAll('.toolbar-btn');

            if (noteTextarea && toolbarButtons.length > 0) {
                toolbarButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const format = this.getAttribute('data-format');
                        const start = noteTextarea.selectionStart;
                        const end = noteTextarea.selectionEnd;
                        const selectedText = noteTextarea.value.substring(start, end);
                        const beforeText = noteTextarea.value.substring(0, start);
                        const afterText = noteTextarea.value.substring(end);

                        let formattedText = '';
                        let cursorOffset = 0;

                        switch (format) {
                            case 'bold':
                                formattedText = `**${selectedText || 'bold text'}**`;
                                cursorOffset = selectedText ? formattedText.length : 2;
                                break;
                            case 'italic':
                                formattedText = `*${selectedText || 'italic text'}*`;
                                cursorOffset = selectedText ? formattedText.length : 1;
                                break;
                            case 'strikethrough':
                                formattedText = `~~${selectedText || 'strikethrough text'}~~`;
                                cursorOffset = selectedText ? formattedText.length : 2;
                                break;
                            case 'ul':
                                const ulLines = selectedText ? selectedText.split('\n').map(line => `- ${line}`).join('\n') : '- List item';
                                formattedText = ulLines;
                                cursorOffset = formattedText.length;
                                break;
                            case 'ol':
                                const olLines = selectedText ? selectedText.split('\n').map((line, i) => `${i + 1}. ${line}`).join('\n') : '1. List item';
                                formattedText = olLines;
                                cursorOffset = formattedText.length;
                                break;
                            case 'quote':
                                const quoteLines = selectedText ? selectedText.split('\n').map(line => `> ${line}`).join('\n') : '> Quote text';
                                formattedText = quoteLines;
                                cursorOffset = formattedText.length;
                                break;
                        }

                        noteTextarea.value = beforeText + formattedText + afterText;
                        noteTextarea.focus();
                        noteTextarea.setSelectionRange(start + cursorOffset, start + cursorOffset);
                    });
                });
            }
        });
    </script>
</x-dashonic-horizontal-layout>