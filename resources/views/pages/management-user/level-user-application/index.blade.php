@extends('layouts.app')
@section('content')
    <div class="level-user-application-page min-h-screen bg-gray-900 text-gray-100 p-6 w-full flex flex-col">

        <!-- Page Header -->
        <header class="page-header mb-8 flex-shrink-0">
            <div class="header-left">
                <div class="title-with-action flex items-center gap-4 mb-2">
                    <h1 class="text-2xl font-bold text-white">Level User Application</h1>
                </div>
                <nav class="breadcrumb text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer">Home</span>
                    <span class="mx-2">/</span>
                    <span class="hover:text-blue-400 cursor-pointer">Management User</span>
                    <span class="mx-2">/</span>
                    <span class="current text-blue-500 font-semibold">Level User Application</span>
                </nav>
            </div>
        </header>

        <!-- User Level Cards Grid -->
        <div
            class="user-level-cards-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5 lg:gap-6 max-w-[1600px]">

            <!-- Card 1: Layer 1 -->
            <div onclick="window.location.href='{{ route('management-user.data-user-application', ['level' => 'layer1']) }}'"
                class="bg-[#121215] rounded-[28px] p-6 pb-8 flex flex-col items-center text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_60px_-15px_rgba(20,184,166,0.2)] hover:-translate-y-2 transition-all duration-300 relative group cursor-pointer border border-[#27272a] hover:border-[#14b8a6]/40">
                <!-- Avatar Area (Concentric Circles) -->
                <div class="relative w-24 h-24 mb-5 mt-1">
                    <!-- Outer Rings -->
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#14b8a6]/20 scale-[1.12] transition-transform duration-500 group-hover:scale-[1.18] group-hover:border-[#14b8a6]/40">
                    </div>
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#14b8a6]/10 scale-[1.26] transition-transform duration-500 group-hover:scale-[1.34]">
                    </div>

                    <!-- Main avatar circle -->
                    <div
                        class="w-full h-full rounded-full bg-[#1c1c21] border-[5px] border-[#121215] shadow-sm flex items-center justify-center relative z-10 transition-colors duration-300 group-hover:bg-[#14b8a6]/10">
                        <i
                            class='bx bx-user-voice text-[36px] text-[#14b8a6] group-hover:scale-110 transition-transform duration-300'></i>
                    </div>

                    <!-- Floating Badge (User Count) -->
                    <div
                        class="absolute -top-1 -right-2 bg-[#14b8a6] text-[#121215] w-[32px] h-[32px] rounded-full flex flex-col items-center justify-center shadow-lg border-[2px] border-[#121215] z-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-teal-400">
                        <span class="text-[11px] font-extrabold leading-none tracking-tight"
                            id="count-layer1">{{ $counts['layer1'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- Text Content -->
                <h3
                    class="text-white font-extrabold text-[18px] mb-1 tracking-tight group-hover:text-[#14b8a6] transition-colors duration-300">
                    Layer 1</h3>
                <p class="text-[#14b8a6] font-bold text-[11px] tracking-wide mb-3">SUPPORT</p>

                <div
                    class="w-8 h-1 bg-[#27272a] rounded-full mb-4 group-hover:w-12 group-hover:bg-[#14b8a6]/50 transition-all duration-300">
                </div>

                <p class="text-gray-200 text-[13px] font-bold mb-1.5 line-clamp-1 w-full">Agent Contact Center</p>
                <p class="text-gray-500 text-[12px] leading-relaxed line-clamp-2 px-1">First Handling Problem Service
                </p>

                <!-- Action text that appears on hover -->
                <div
                    class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-[#14b8a6] font-bold text-[12px] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0">
                    View Data <i class='bx bx-right-arrow-alt text-[16px]'></i>
                </div>
            </div>

            <!-- Card 2: Layer 2 -->
            <div onclick="window.location.href='{{ route('management-user.data-user-application', ['level' => 'layer2']) }}'"
                class="bg-[#121215] rounded-[28px] p-6 pb-8 flex flex-col items-center text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_60px_-15px_rgba(255,176,0,0.2)] hover:-translate-y-2 transition-all duration-300 relative group cursor-pointer border border-[#27272a] hover:border-[#ffb000]/40">
                <div class="relative w-24 h-24 mb-5 mt-1">
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#ffb000]/20 scale-[1.12] transition-transform duration-500 group-hover:scale-[1.18] group-hover:border-[#ffb000]/40">
                    </div>
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#ffb000]/10 scale-[1.26] transition-transform duration-500 group-hover:scale-[1.34]">
                    </div>

                    <div
                        class="w-full h-full rounded-full bg-[#1c1c21] border-[5px] border-[#121215] shadow-sm flex items-center justify-center relative z-10 transition-colors duration-300 group-hover:bg-[#ffb000]/10">
                        <i
                            class='bx bx-user-check text-[36px] text-[#ffb000] group-hover:scale-110 transition-transform duration-300'></i>
                    </div>

                    <div
                        class="absolute -top-1 -right-2 bg-[#ffb000] text-[#121215] w-[32px] h-[32px] rounded-full flex flex-col items-center justify-center shadow-lg border-[2px] border-[#121215] z-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-amber-400">
                        <span class="text-[11px] font-extrabold leading-none tracking-tight"
                            id="count-layer2">{{ $counts['layer2'] ?? 0 }}</span>
                    </div>
                </div>

                <h3
                    class="text-white font-extrabold text-[18px] mb-1 tracking-tight group-hover:text-[#ffb000] transition-colors duration-300">
                    Layer 2</h3>
                <p class="text-[#ffb000] font-bold text-[11px] tracking-wide mb-3">ESCALATION</p>

                <div
                    class="w-8 h-1 bg-[#27272a] rounded-full mb-4 group-hover:w-12 group-hover:bg-[#ffb000]/50 transition-all duration-300">
                </div>

                <p class="text-gray-200 text-[13px] font-bold mb-1.5 line-clamp-1 w-full">Support Agent</p>
                <p class="text-gray-500 text-[12px] leading-relaxed line-clamp-2 px-1">Escalation Handling Problem
                    Service</p>

                <div
                    class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-[#ffb000] font-bold text-[12px] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0">
                    View Data <i class='bx bx-right-arrow-alt text-[16px]'></i>
                </div>
            </div>

            <!-- Card 3: Layer 3 -->
            <div onclick="window.location.href='{{ route('management-user.data-user-application', ['level' => 'layer3']) }}'"
                class="bg-[#121215] rounded-[28px] p-6 pb-8 flex flex-col items-center text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_60px_-15px_rgba(244,63,94,0.2)] hover:-translate-y-2 transition-all duration-300 relative group cursor-pointer border border-[#27272a] hover:border-[#f43f5e]/40">
                <div class="relative w-24 h-24 mb-5 mt-1">
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#f43f5e]/20 scale-[1.12] transition-transform duration-500 group-hover:scale-[1.18] group-hover:border-[#f43f5e]/40">
                    </div>
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#f43f5e]/10 scale-[1.26] transition-transform duration-500 group-hover:scale-[1.34]">
                    </div>

                    <div
                        class="w-full h-full rounded-full bg-[#1c1c21] border-[5px] border-[#121215] shadow-sm flex items-center justify-center relative z-10 transition-colors duration-300 group-hover:bg-[#f43f5e]/10">
                        <i
                            class='bx bx-user-pin text-[36px] text-[#f43f5e] group-hover:scale-110 transition-transform duration-300'></i>
                    </div>

                    <div
                        class="absolute -top-1 -right-2 bg-[#f43f5e] text-[#121215] w-[32px] h-[32px] rounded-full flex flex-col items-center justify-center shadow-lg border-[2px] border-[#121215] z-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-rose-400">
                        <span class="text-[11px] font-extrabold leading-none tracking-tight"
                            id="count-layer3">{{ $counts['layer3'] ?? 0 }}</span>
                    </div>
                </div>

                <h3
                    class="text-white font-extrabold text-[18px] mb-1 tracking-tight group-hover:text-[#f43f5e] transition-colors duration-300">
                    Layer 3</h3>
                <p class="text-[#f43f5e] font-bold text-[11px] tracking-wide mb-3">DEPARTMENT</p>

                <div
                    class="w-8 h-1 bg-[#27272a] rounded-full mb-4 group-hover:w-12 group-hover:bg-[#f43f5e]/50 transition-all duration-300">
                </div>

                <p class="text-gray-200 text-[13px] font-bold mb-1.5 line-clamp-1 w-full">Department Unit Case</p>
                <p class="text-gray-500 text-[12px] leading-relaxed line-clamp-2 px-1">Escalation Handling Problem
                    Department</p>

                <div
                    class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-[#f43f5e] font-bold text-[12px] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0">
                    View Data <i class='bx bx-right-arrow-alt text-[16px]'></i>
                </div>
            </div>

            <!-- Card 4: Supervisor -->
            <div onclick="window.location.href='{{ route('management-user.data-user-application', ['level' => 'supervisor']) }}'"
                class="bg-[#121215] rounded-[28px] p-6 pb-8 flex flex-col items-center text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_60px_-15px_rgba(168,85,247,0.2)] hover:-translate-y-2 transition-all duration-300 relative group cursor-pointer border border-[#27272a] hover:border-[#a855f7]/40">
                <div class="relative w-24 h-24 mb-5 mt-1">
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#a855f7]/20 scale-[1.12] transition-transform duration-500 group-hover:scale-[1.18] group-hover:border-[#a855f7]/40">
                    </div>
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#a855f7]/10 scale-[1.26] transition-transform duration-500 group-hover:scale-[1.34]">
                    </div>

                    <div
                        class="w-full h-full rounded-full bg-[#1c1c21] border-[5px] border-[#121215] shadow-sm flex items-center justify-center relative z-10 transition-colors duration-300 group-hover:bg-[#a855f7]/10">
                        <i
                            class='bx bxs-user-badge text-[36px] text-[#a855f7] group-hover:scale-110 transition-transform duration-300'></i>
                    </div>

                    <div
                        class="absolute -top-1 -right-2 bg-[#a855f7] text-[#121215] w-[32px] h-[32px] rounded-full flex flex-col items-center justify-center shadow-lg border-[2px] border-[#121215] z-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-purple-400">
                        <span class="text-[11px] font-extrabold leading-none tracking-tight"
                            id="count-supervisor">{{ $counts['supervisor'] ?? 0 }}</span>
                    </div>
                </div>

                <h3
                    class="text-white font-extrabold text-[18px] mb-1 tracking-tight group-hover:text-[#a855f7] transition-colors duration-300">
                    Supervisor</h3>
                <p class="text-[#a855f7] font-bold text-[11px] tracking-wide mb-3">MONITORING</p>

                <div
                    class="w-8 h-1 bg-[#27272a] rounded-full mb-4 group-hover:w-12 group-hover:bg-[#a855f7]/50 transition-all duration-300">
                </div>

                <p class="text-gray-200 text-[13px] font-bold mb-1.5 line-clamp-1 w-full">Dept. Unit Case</p>
                <p class="text-gray-500 text-[12px] leading-relaxed line-clamp-2 px-1">Monitoring Handling Problem
                    Department</p>

                <div
                    class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-[#a855f7] font-bold text-[12px] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0">
                    View Data <i class='bx bx-right-arrow-alt text-[16px]'></i>
                </div>
            </div>

            <!-- Card 5: Administrator -->
            <div onclick="window.location.href='{{ route('management-user.data-user-application', ['level' => 'administrator']) }}'"
                class="bg-[#121215] rounded-[28px] p-6 pb-8 flex flex-col items-center text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_60px_-15px_rgba(59,130,246,0.2)] hover:-translate-y-2 transition-all duration-300 relative group cursor-pointer border border-[#27272a] hover:border-[#3b82f6]/40">
                <div class="relative w-24 h-24 mb-5 mt-1">
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#3b82f6]/20 scale-[1.12] transition-transform duration-500 group-hover:scale-[1.18] group-hover:border-[#3b82f6]/40">
                    </div>
                    <div
                        class="absolute inset-0 rounded-full border-[3px] border-[#3b82f6]/10 scale-[1.26] transition-transform duration-500 group-hover:scale-[1.34]">
                    </div>

                    <div
                        class="w-full h-full rounded-full bg-[#1c1c21] border-[5px] border-[#121215] shadow-sm flex items-center justify-center relative z-10 transition-colors duration-300 group-hover:bg-[#3b82f6]/10">
                        <i
                            class='bx bx-user-circle text-[36px] text-[#3b82f6] group-hover:scale-110 transition-transform duration-300'></i>
                    </div>

                    <div
                        class="absolute -top-1 -right-2 bg-[#3b82f6] text-[#121215] w-[32px] h-[32px] rounded-full flex flex-col items-center justify-center shadow-lg border-[2px] border-[#121215] z-20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-blue-400">
                        <span class="text-[11px] font-extrabold leading-none tracking-tight"
                            id="count-administrator">{{ $counts['administrator'] ?? 0 }}</span>
                    </div>
                </div>

                <h3
                    class="text-white font-extrabold text-[18px] mb-1 tracking-tight group-hover:text-[#3b82f6] transition-colors duration-300">
                    Administrator</h3>
                <p class="text-[#3b82f6] font-bold text-[11px] tracking-wide mb-3">SYSTEM</p>

                <div
                    class="w-8 h-1 bg-[#27272a] rounded-full mb-4 group-hover:w-12 group-hover:bg-[#3b82f6]/50 transition-all duration-300">
                </div>

                <p class="text-gray-200 text-[13px] font-bold mb-1.5 line-clamp-1 w-full">System Admin</p>
                <p class="text-gray-500 text-[12px] leading-relaxed line-clamp-2 px-1">Operations Application System</p>

                <div
                    class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-1 text-[#3b82f6] font-bold text-[12px] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0">
                    View Data <i class='bx bx-right-arrow-alt text-[16px]'></i>
                </div>
            </div>

        </div>

    </div>

    <div id="level-user-config" class="hidden" data-get="{{ route('management-user.level-user-application.counts') }}"></div>
    @push('scripts')
        @vite('resources/js/pages/management-user/level-user-application.js')
    @endpush
@endsection