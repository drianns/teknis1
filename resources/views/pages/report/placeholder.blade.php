@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">{{ $title }}</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Report</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="current text-blue-500 font-semibold">{{ $title }}</span>
                </div>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col items-center justify-center">
                <div class="text-center">
                    <i class='bx bx-building-house text-6xl text-blue-500/20 mb-4'></i>
                    <h2 class="text-xl font-bold text-white mb-2">Under Construction</h2>
                    <p class="text-gray-400">This report is currently being prepared by the development team.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
