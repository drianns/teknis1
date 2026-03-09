@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Report Statistic Call</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Report</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="current text-blue-500 font-semibold">Statistic Call</span>
                </div>
            </div>
        </header>

        <div class="flex-1 px-4 pb-4">
            <div class="w-full h-full bg-gray-800 rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 relative group">
                <!-- Iframe Container -->
                <iframe 
                    src="https://issabel.example.com" 
                    id="issabelReportFrame"
                    class="w-full h-full border-none rounded-2xl"
                    allow="camera; microphone; autoplay; display-capture; fullscreen"
                    loading="lazy">
                </iframe>
                
                <!-- Loading & Info Overlay (Optional) -->
                <div id="iframeOverlay" class="absolute inset-0 bg-gray-900 flex flex-col items-center justify-center transition-opacity duration-500 pointer-events-none opacity-0">
                    <i class='bx bx-loader-alt animate-spin text-4xl text-blue-500 mb-4'></i>
                    <p class="text-gray-400 text-sm font-medium">Connecting to Issabel Server...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple helper to handle iframe loading state if needed
        const frame = document.getElementById('issabelReportFrame');
        const overlay = document.getElementById('iframeOverlay');
        
        // You can change 'https://issabel.example.com' to your actual Issabel IP/Domain
        const ISSABEL_URL = 'http://127.0.0.1'; // Update this with your actual Issabel URL
        
        if (frame) {
            frame.src = ISSABEL_URL;
            frame.onload = function() {
                if (overlay) overlay.style.opacity = '0';
            };
        }
    </script>
@endsection
