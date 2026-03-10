@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4 text-center mt-20">
            <h1 class="text-[40px] font-bold text-white tracking-tight mb-4">{{ $name }}</h1>
            <div class="flex items-center justify-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold italic">{{ $name }}</span>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full items-center justify-center">
            <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl p-12 text-center max-w-2xl transform hover:scale-[1.02] transition-all duration-300">
                <div class="w-24 h-24 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6 ring-4 ring-blue-500/20">
                    <i class='bx bx-stopwatch text-5xl text-blue-500 animate-pulse'></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-4">Under Construction</h2>
                <p class="text-gray-400 leading-relaxed mb-8">
                    The <span class="text-blue-400 font-bold italic">{{ $name }}</span> module is currently under development. 
                    We're working hard to bring you something amazing. Stay tuned!
                </p>
                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-blue-400 w-3/4 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.5)]"></div>
                </div>
                <div class="text-[10px] text-gray-500 mt-4 uppercase tracking-[0.2em] font-bold">Progress: 75%</div>
            </div>
        </div>
    </div>
@endsection
