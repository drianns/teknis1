@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <h1 class="text-[28px] font-bold text-white tracking-tight mb-2">Data Email Account</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Home</span>
                <span class="mx-2">/</span>
                <span class="hover:text-blue-400 cursor-pointer transition-colors">Setting Email System</span>
                <span class="mx-2">/</span>
                <span class="current text-blue-500 font-semibold">Data Email Account</span>
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
                    <table class="w-full text-left border-collapse" style="min-width:2800px">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">incoming_account_name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">incoming_password</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">incoming_server</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">incoming_port</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">encrypted_connection</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">outgoing_account_name</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">outgoing_password</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">outgoing_server</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">outgoing_port</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">encrypted_connection_out</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">need_login</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">incoming_back_up</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">outgoing_back_up</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">server_protocol</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">server_protocol_out</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">server_profile_id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">email_signiture_id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">email_service_method_id</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="sticky top-0 z-10 bg-gray-900 px-4 py-3 w-16 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50 text-sm text-gray-300">
                            @foreach([
                                ['id'=>11,'name'=>'Nespresso','inc_acc'=>'club.indonesia@nespresso.co.id','inc_pass'=>'*******','inc_srv'=>'outlook.office365.com','inc_port'=>993,'enc'=>'True','out_acc'=>'club.indonesia@nespresso.co.id','out_pass'=>'*******','out_srv'=>'outlook.office365.com','out_port'=>587,'enc_out'=>'True','need_login'=>'True','inc_bk'=>'C:\KANMO\FileEmail\Inbox','out_bk'=>'C:\KANMO\FileEmail\Outbox','srv_p'=>2,'srv_p_out'=>1,'srv_prof'=>2,'sig'=>9,'method'=>3,'status'=>'Active'],
                                ['id'=>13,'name'=>'Kanmo','inc_acc'=>'support@kanmogroup.com','inc_pass'=>'*******','inc_srv'=>'outlook.office365.com','inc_port'=>993,'enc'=>'True','out_acc'=>'support@kanmogroup.com','out_pass'=>'*******','out_srv'=>'outlook.office365.com','out_port'=>587,'enc_out'=>'True','need_login'=>'True','inc_bk'=>'C:\KANMO\FileEmail\Inbox','out_bk'=>'C:\KANMO\FileEmail\Outbox','srv_p'=>2,'srv_p_out'=>1,'srv_prof'=>2,'sig'=>9,'method'=>3,'status'=>'Active'],
                            ] as $row)
                            <tr class="hover:bg-blue-500/[0.03] transition-colors group/row">
                                <td class="px-4 py-3 font-mono text-blue-400 font-medium text-center whitespace-nowrap">{{ $row['id'] }}</td>
                                <td class="px-4 py-3 font-medium text-white whitespace-nowrap">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['inc_acc'] }}</td>
                                <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $row['inc_pass'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['inc_srv'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['inc_port'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['enc'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['out_acc'] }}</td>
                                <td class="px-4 py-3 text-gray-400 whitespace-nowrap">{{ $row['out_pass'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['out_srv'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['out_port'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['enc_out'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['need_login'] }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $row['inc_bk'] }}</td>
                                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $row['out_bk'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['srv_p'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['srv_p_out'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['srv_prof'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['sig'] }}</td>
                                <td class="px-4 py-3 text-gray-300 whitespace-nowrap">{{ $row['method'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap"><span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-500/30 whitespace-nowrap">{{ $row['status'] }}</span></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="relative flex justify-center" x-data="{ open: false }">
                                        <button @click.stop="open = !open" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white"><i class='bx bx-dots-vertical-rounded'></i></button>
                                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 top-full mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden" style="display:none;">
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white flex items-center gap-3"><i class='bx bx-edit-alt text-blue-400 text-lg'></i><span class="font-medium">Edit</span></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div class="text-sm text-gray-500">Showing <span class="text-white font-bold">1</span> to <span class="text-white font-bold">2</span> of <span class="text-white font-bold">2</span> entries</div>
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
