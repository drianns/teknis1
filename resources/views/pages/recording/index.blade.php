@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Roatex Recording</h1>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="hover:text-blue-400 cursor-pointer transition-colors">Recording</span>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="current text-blue-500 font-semibold">Voice Recording</span>
                </div>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full space-y-4">
            <!-- Filter Section -->
            <div class="bg-gray-800/80 backend-blur-md rounded-2xl border border-gray-700/50 p-6 shadow-xl ring-1 ring-white/5">
                <form action="#" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Start</label>
                            <div class="relative">
                                <input type="date" name="start_date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">End</label>
                            <div class="relative">
                                <input type="date" name="end_date" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter forms updated -->
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 space-y-2 w-full">
                            <input type="text" id="searchInput" name="unique_id" placeholder="Unique Id or Ticket Number" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all placeholder-gray-600">
                        </div>
                        <button type="button" onclick="loadTableData(1)" class="w-full md:w-32 px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-600/20 transition-all font-bold text-sm h-[42px] flex items-center justify-center gap-2">
                            <i class='bx bx-search text-lg'></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50">
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Unique Id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Call Date</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ticket Number</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Disposition</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agent</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Duration</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Recording File</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">STT</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">QA</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody" class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            <!-- Populated dynamically via JS AJAX -->
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500" id="dataTableInfo">Showing <span class="text-white font-bold">0</span> to <span class="text-white font-bold">0</span> of <span class="text-white font-bold">0</span> entries</div>
                    <div id="paginationContainer" class="flex gap-1"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Player Modal -->
    <div id="recordingModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-950/80 backdrop-blur-sm" aria-hidden="true" onclick="closeRecordingModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-gray-800 rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-700 ring-1 ring-white/10">
                <div class="px-6 py-5 border-b border-gray-700/50 flex justify-between items-center bg-gray-900/50">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="modal-title">Voice Recording</h3>
                        <p class="text-xs text-gray-400 mt-0.5" id="modal-subtitle">REC-000000</p>
                    </div>
                    <button onclick="closeRecordingModal()" class="text-gray-400 hover:text-white transition-colors bg-gray-800 rounded-lg p-2 border border-gray-700">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                
                <div class="p-8">
                    <!-- Waveform Visualizer Placeholder -->
                    <div class="mb-8 flex items-end justify-center gap-1 h-16 w-full px-4 overflow-hidden">
                        @for($i = 0; $i < 40; $i++)
                            <div class="w-1.5 bg-blue-500/30 rounded-full animate-wave" style="height: {{ rand(20, 100) }}%; animation-delay: {{ $i * 0.05 }}s"></div>
                        @endfor
                    </div>

                    <div class="space-y-6">
                        <!-- Playback Status -->
                        <div class="flex justify-between items-center px-1">
                            <span class="text-sm font-mono text-blue-400" id="currentTime">00:00</span>
                            <span class="text-sm font-mono text-gray-400" id="totalDuration">00:00</span>
                        </div>

                        <!-- Audio Element -->
                        <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700/50 shadow-inner">
                            <audio id="audioPlayer" controls class="w-full filter invert hue-rotate-180 brightness-200">
                                <source src="" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>

                        <!-- Meta Info Grid -->
                        <div class="grid grid-cols-2 gap-4 pt-4">
                            <div class="bg-gray-900/30 rounded-xl p-3 border border-white/5">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Agent</span>
                                <span class="text-sm text-gray-200" id="modalAgent">-</span>
                            </div>
                            <div class="bg-gray-900/30 rounded-xl p-3 border border-white/5">
                                <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Customer</span>
                                <span class="text-sm text-gray-200" id="modalCustomer">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-900/50 border-t border-gray-700/50 flex justify-end">
                    <button onclick="closeRecordingModal()" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-xl text-sm font-bold transition-all border border-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .backend-blur-md {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .animate-wave {
            animation: wave 1.2s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: scaleY(0.5); opacity: 0.3; }
            50% { transform: scaleY(1.3); opacity: 1; }
        }
    </style>
    
    <script>
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // Load table on init
            loadTableData();

            // Search input event
            const searchInput = document.getElementById('searchInput');
            let debounceTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        currentPage = 1;
                        loadTableData();
                    }, 400);
                });
            }
        });

        function loadTableData(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput')?.value || '';
            const tbody = document.getElementById('dataTableBody');

            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-10"><i class="bx bx-loader-alt bx-spin text-3xl text-blue-500"></i><p class="mt-2 text-gray-500">Loading records...</p></td></tr>`;

            const url = `{{ route('recording.getData') }}?page=${page}&search=${encodeURIComponent(search)}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    tbody.innerHTML = `<tr><td colspan="10" class="px-6 py-8 text-center text-red-500"><i class="bx bx-error text-4xl mb-2"></i><p>Failed to load data</p></td></tr>`;
                });
        }

        function renderTable(data) {
            const tbody = document.getElementById('dataTableBody');
            tbody.innerHTML = '';

            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class='bx bx-microphone text-5xl mb-3 opacity-20'></i>
                                <p>No recording records found</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-blue-500/[0.03] transition-colors';
                
                const uid = item.unique_id || '-';
                const calldt = item.call_date || item.created_at || '-';
                const tn = item.ticket_number || '-';
                const disp = item.disposition || 'Call';
                const cust = item.customer || '-';
                const ag = item.agent || '-';
                const dur = item.duration || '00:00:00';
                const stt = item.stt || 'N/A';
                const qa = item.qa || '0%';

                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <span class="font-mono text-blue-400 font-medium">${uid}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">${calldt}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded bg-gray-700 text-gray-200 text-[11px] font-semibold">${tn}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">
                            ${disp}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">${cust}</td>
                    <td class="px-6 py-4 text-gray-400">${ag}</td>
                    <td class="px-6 py-4 text-center text-gray-300 font-mono">${dur}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openRecordingModal(${JSON.stringify(item).replace(/"/g, '&quot;')})" class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white transition-all inline-flex items-center justify-center border border-blue-500/30" title="Play Recording">
                            <i class='bx bx-play text-xl'></i>
                        </button>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-lg bg-teal-500/10 text-teal-400 text-[10px] font-bold border border-teal-500/20 uppercase">
                            ${stt}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold text-white">${qa}</span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openRecordingModal(item) {
            const modal = document.getElementById('recordingModal');
            const audio = document.getElementById('audioPlayer');
            
            document.getElementById('modal-subtitle').innerText = item.unique_id || 'N/A';
            document.getElementById('modalAgent').innerText = item.agent || '-';
            document.getElementById('modalCustomer').innerText = item.customer || '-';
            document.getElementById('totalDuration').innerText = item.duration || '00:00';
            
            // Set dummy recording if file not available
            const source = item.recording_file || 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3';
            audio.src = source;
            
            modal.classList.remove('hidden');
            
            // Sync time display
            audio.ontimeupdate = function() {
                const mins = Math.floor(audio.currentTime / 60);
                const secs = Math.floor(audio.currentTime % 60);
                document.getElementById('currentTime').innerText = 
                    `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            };
        }

        function closeRecordingModal() {
            const modal = document.getElementById('recordingModal');
            const audio = document.getElementById('audioPlayer');
            audio.pause();
            audio.src = '';
            modal.classList.add('hidden');
        }

        function renderPagination(data) {
            const info = document.getElementById('dataTableInfo');
            const container = document.getElementById('paginationContainer');

            let from = data.from || 0;
            let to = data.to || 0;
            info.innerHTML = `Showing <span class="text-white font-bold">${from}</span> to <span class="text-white font-bold">${to}</span> of <span class="text-white font-bold">${data.total}</span> entries`;

            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Prev
            html += `<button onclick="loadTableData(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Previous</button>`;
            
            // Next
            html += `<button onclick="loadTableData(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded-lg disabled:opacity-50 text-white disabled:cursor-not-allowed">Next</button>`;
            
            container.innerHTML = html;
        }
    </script>
@endsection
