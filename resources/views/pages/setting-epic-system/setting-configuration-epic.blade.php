@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Configurasi EPIC System</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting EPIC System</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Data Configurasi EPIC System</span>
            </div>
        </header>

        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400">
                        <span>Show</span>
                        <select class="entries-select"><option>10</option><option>25</option><option>50</option></select>
                        <span>entries</span>
                    </div>
                    <div class="relative">
                        <input type="text" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500" placeholder="Search..." />
                        <i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i>
                    </div>
                </div>

                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse" style="min-width:3200px">
                        <thead>
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">AES</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aes User</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aes Password</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Port</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">IP Database</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Database User</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Database Password</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Database Name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Dial Code</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Call History Endpoint</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agent Endpoint</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inbound Endpoint</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outbound Endpoint</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Browser Path</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Theme</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ACW</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">PBX Login</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">PBX LogOut</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">PBX Aux</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">PBX AutoIn</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @php
                                $configs = [
                                    [
                                        'id'             => 1,
                                        'aes'            => 'AVAYA#BRILIFEAES#CSTA#BRILIFEAES',
                                        'aes_user'       => 'support',
                                        'aes_pass'       => 'Avaya123!',
                                        'port'           => 65004,
                                        'ip_db'          => '10.28.2.224',
                                        'db_user'        => 'sa',
                                        'db_pass'        => 'Sa212',
                                        'db_name'        => 'BRILIFE_OmniChannel',
                                        'dial_code'      => '9',
                                        'call_history'   => '',
                                        'agent_ep'       => 'http://10.28.2.222/brilifecc/auth_login.aspx',
                                        'inbound_ep'     => 'http://10.28.2.222/brilifecc/apps/TrxDirect.aspx',
                                        'outbound_ep'    => '',
                                        'browser_path'   => 'C:\Program Files\Google\Chrome\Application\chrome.exe',
                                        'theme'          => 'Default',
                                        'acw'            => 0,
                                        'pbx_login'      => '*95',
                                        'pbx_logout'     => '*96',
                                        'pbx_aux'        => '*94',
                                        'pbx_autoin'     => '*93',
                                    ],
                                ];
                            @endphp
                            @foreach($configs as $c)
                            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                                <td class="px-4 py-3 font-mono text-blue-400 font-medium text-center whitespace-nowrap">{{ $c['id'] }}</td>
                                <td class="px-4 py-3 text-blue-300 font-semibold text-xs whitespace-nowrap">{{ $c['aes'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $c['aes_user'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono whitespace-nowrap">{{ $c['aes_pass'] }}</td>
                                <td class="px-4 py-3 text-gray-300 text-center whitespace-nowrap">{{ $c['port'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $c['ip_db'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $c['db_user'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono whitespace-nowrap">{{ $c['db_pass'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $c['db_name'] }}</td>
                                <td class="px-4 py-3 text-gray-300 text-center whitespace-nowrap">{{ $c['dial_code'] ?: '—' }}</td>
                                <td class="px-4 py-3 text-gray-500 italic whitespace-nowrap">{{ $c['call_history'] ?: '—' }}</td>
                                <td class="px-4 py-3 text-blue-400 text-xs whitespace-nowrap">{{ $c['agent_ep'] }}</td>
                                <td class="px-4 py-3 text-blue-400 text-xs whitespace-nowrap">{{ $c['inbound_ep'] }}</td>
                                <td class="px-4 py-3 text-gray-500 italic whitespace-nowrap">{{ $c['outbound_ep'] ?: '—' }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs font-mono whitespace-nowrap">{{ $c['browser_path'] }}</td>
                                <td class="px-4 py-3 text-gray-300 text-center whitespace-nowrap">{{ $c['theme'] }}</td>
                                <td class="px-4 py-3 text-gray-300 text-center whitespace-nowrap">{{ $c['acw'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-center whitespace-nowrap">{{ $c['pbx_login'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-center whitespace-nowrap">{{ $c['pbx_logout'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-center whitespace-nowrap">{{ $c['pbx_aux'] }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-center whitespace-nowrap">{{ $c['pbx_autoin'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="relative flex justify-center" x-data="{ open: false }">
                                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white">
                                            <i class='bx bx-dots-vertical-rounded'></i>
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden" style="display:none;">
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3">
                                                <i class='bx bx-edit-alt text-blue-400 text-lg'></i>
                                                <span class="font-medium">Edit</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500">Showing <span class="text-white font-bold">1</span> to <span class="text-white font-bold">1</span> of <span class="text-white font-bold">1</span> entries</div>
                    <div class="flex gap-1">
                        <button class="px-3 py-1.5 text-xs font-bold text-gray-500 border border-gray-700 rounded-lg hover:bg-gray-700 transition-colors">Previous</button>
                        <button class="px-3 py-1.5 text-xs font-bold bg-blue-600 text-white rounded-lg">1</button>
                        <button class="px-3 py-1.5 text-xs font-bold text-gray-500 border border-gray-700 rounded-lg hover:bg-gray-700 transition-colors">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.setup-channel-email.partials._scrollbar')
@endsection
