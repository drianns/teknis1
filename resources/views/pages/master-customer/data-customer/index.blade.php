@extends('layouts.app')
@section('content')
<div class="data-customer-page">
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col mb-4 shrink-0">
            <div class="flex items-center gap-3 mb-1">
                <div
                    class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-600/20">
                    <i class="bx bx-user text-white text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white tracking-tight">Data Customer</h1>
            </div>
            <nav class="flex text-[11px] text-gray-400 font-medium uppercase tracking-wider">
                <a href="#" class="hover:text-blue-400 transition-colors">Home</a>
                <span class="mx-2 text-gray-600">/</span>
                <span class="hover:text-blue-400 transition-colors">Apps</span>
                <span class="mx-2 text-gray-600">/</span>
                <span class="text-gray-300">Data Customer</span>
            </nav>
        </div>

        <!-- Two Column Workspaces -->
        <div class="data-customer-workspaces">
            <!-- Left Workspace: Customer List -->
            <div class="workspace-card">
                <div class="workspace-card-header">

                    <!-- Search Bar -->
                    <div class="search-input-wrapper">
                        <i class="bx bx-search text-gray-500"></i>
                        <input type="text" id="customerSearch" onkeyup="searchCustomer()"
                            class="bg-gray-900 border border-gray-700 text-gray-300 text-sm rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full p-2.5 transition-all outline-none"
                            placeholder="Search Customer">
                    </div>
                </div>

                <div class="workspace-card-body p-0 customer-scroll" id="customerList">
                    @foreach ($customers as $customer)
                        <div class="customer-item p-4 border-b border-gray-700/50 hover:bg-gray-700/30 cursor-pointer transition-all relative group"
                            data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->name }}"
                            data-customer-email="{{ $customer->email }}" data-customer-phone="{{ $customer->phone }}"
                            data-customer-member-id="{{ $customer->member_id }}"
                            data-additional-contacts='@json($customer->additional_contacts)'
                            data-transactions='@json($customer->transactions)' onclick="selectCustomer(this)">

                            <!-- Arrow indicator (hidden default, show on select via JS later if needed) -->
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 opacity-0 group-hover:opacity-50 transition-opacity">
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Avatar -->
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $customer->avatar }}" alt="{{ $customer->name }}"
                                        class="w-11 h-11 rounded-full border-2 border-gray-700 group-hover:border-blue-500/50 transition-colors object-cover shadow-sm">
                                </div>

                                <!-- Customer Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-white font-bold text-sm truncate leading-tight">
                                            {{ $customer->name }}
                                        </h3>
                                        <span
                                            class="text-[10px] text-gray-500 font-medium">{{ $customer->member_id }}</span>
                                    </div>
                                    <p class="text-gray-400 text-xs truncate mt-0.5">{{ $customer->email }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <i class="bx bx-phone text-[10px] text-gray-500"></i>
                                        <p class="text-gray-500 text-xs font-medium">{{ $customer->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Workspace: Content Area -->
            <div class="workspace-card">
                <!-- Redesigned Header with actions -->
                <div class="workspace-card-header redesigned">
                    <div class="header-content">
                        <!-- Left side: Tab buttons -->
                        <div class="workspace-tabs">
                            <button onclick="switchTab('history')" id="historyTab" class="tab-button active">
                                <i class="bx bx-list-ul text-lg"></i>
                                <span>History Transaction</span>
                            </button>
                            <button onclick="switchTab('customer')" id="customerTab" class="tab-button">
                                <i class="bx bx-id-card text-lg"></i>
                                <span>Customer Data</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab Content Body -->
                <div class="workspace-card-body !p-0">
                    <!-- History Transaction Tab Content -->
                    <div id="historyContent" class="tab-content h-full">
                        <div class="overflow-x-auto h-full">
                            <table class="w-full text-sm text-left text-gray-400">
                                <thead class="text-[10px] text-gray-500 uppercase bg-gray-900/80 sticky top-0 z-10">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 font-bold tracking-widest border-b border-gray-700">
                                            Ticket
                                            Number</th>
                                        <th scope="col"
                                            class="px-6 py-4 font-bold tracking-widest border-b border-gray-700">
                                            Category</th>
                                        <th scope="col"
                                            class="px-6 py-4 font-bold tracking-widest border-b border-gray-700 text-center">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-4 font-bold tracking-widest border-b border-gray-700">
                                            User
                                            Create</th>
                                        <th scope="col"
                                            class="px-6 py-4 font-bold tracking-widest border-b border-gray-700">
                                            Date
                                            Create</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionHistoryBody" class="divide-y divide-gray-700/50">
                                    <!-- Dynamic content -->
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center text-gray-500 bg-gray-800/20">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-700/30 rounded-full flex items-center justify-center mb-4">
                                                    <i class="bx bx-folder-open text-3xl text-gray-600"></i>
                                                </div>
                                                <p class="text-gray-400 font-medium">No transactions available</p>
                                                <p class="text-gray-600 text-[11px] mt-1 uppercase tracking-tight">
                                                    Select a customer to view transaction history</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Data Tab Content -->
                    <div id="customerContent" class="tab-content hidden p-6 space-y-8 h-full overflow-y-auto">
                        <!-- Core Data Fields -->
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                    <i class="bx bx-user text-xs"></i> Full Name
                                </label>
                                <input type="text" id="customerFullName"
                                    class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full p-3 transition-all outline-none font-medium"
                                    placeholder="Full Name" readonly>
                            </div>

                            <!-- Multi-column row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                        <i class="bx bx-envelope text-xs"></i> Email Address
                                    </label>
                                    <input type="text" id="customerEmail"
                                        class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full p-3 transition-all outline-none font-medium"
                                        placeholder="Email Address" readonly>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                        <i class="bx bx-phone text-xs"></i> Phone Number
                                    </label>
                                    <input type="text" id="customerPhone"
                                        class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full p-3 transition-all outline-none font-medium"
                                        placeholder="Phone Number" readonly>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                        <i class="bx bx-id-card text-xs"></i> Member ID
                                    </label>
                                    <input type="text" id="customerMemberId"
                                        class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 block w-full p-3 transition-all outline-none font-medium"
                                        placeholder="Member ID" readonly>
                                </div>
                            </div>

                            <!-- Update Button -->
                            <div class="flex justify-end pt-2 border-t border-gray-700/50">
                                <button
                                    class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-lg shadow-blue-600/20 active:scale-95 uppercase tracking-wider">
                                    <i class="bx bx-save text-lg"></i>
                                    <span>Update Data</span>
                                </button>
                            </div>
                        </div>

                        <!-- Additional Contacts Section -->
                        <div class="space-y-4">
                            <h3
                                class="text-gray-100 font-bold text-xs uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                                Additional Contacts</h3>
                            <div class="overflow-x-auto rounded-xl border border-gray-700/50">
                                <table class="w-full text-sm text-left text-gray-400">
                                    <thead class="text-[10px] text-gray-500 uppercase bg-gray-900/80">
                                        <tr>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest">Channel</th>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest">Account</th>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest text-center">
                                                Status</th>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest">User Create
                                            </th>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest">Date Create
                                            </th>
                                            <th scope="col" class="px-4 py-4 font-bold tracking-widest text-right">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="additionalContactsBody"
                                        class="divide-y divide-gray-700/50 bg-gray-800/10">
                                        <!-- Dynamic content -->
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center opacity-50">
                                                    <i class="bx bx-paper-plane text-3xl mb-2"></i>
                                                    <p class="text-[10px] uppercase font-bold tracking-tighter">No
                                                        additional contacts linked</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Removed -->


    @push('scripts')
    @vite('resources/js/pages/master-customer/data-customer.js')
    @endpush
@endsection
