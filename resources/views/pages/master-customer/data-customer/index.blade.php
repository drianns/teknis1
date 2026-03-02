<x-dashonic-horizontal-layout sidebar="1" with-sidebar="1" with-header="1" with-footer="1">
    <x-slot name="title">
        Data Customer
    </x-slot>

    <style>
        /* Main page container */
        .data-customer-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-height: 100vh;
            padding: 20px 24px 20px 24px;
            box-sizing: border-box;
            overflow: hidden;
            background-color: #111827;
            /* Gray-900 */
        }

        /* Two-column workspace container */
        .data-customer-workspaces {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 20px;
            flex: 1;
            min-height: 0;
            /* Critical for scrollable children in flexbox */
        }

        /* Base card style for all workspaces */
        .workspace-card {
            display: flex;
            flex-direction: column;
            background-color: #1f2937;
            /* Gray-800 */
            border-radius: 0.75rem;
            /* 12px */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            height: 100%;
            overflow: hidden;
            border: 1px solid #374151;
            /* Gray-700 */
        }

        /* Card header */
        .workspace-card-header {
            flex-shrink: 0;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #374151;
            background-color: #1f2937;
        }

        /* Card body (scrollable content) */
        .workspace-card-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.25rem;
        }

        /* Custom scrollbar */
        .workspace-card-body::-webkit-scrollbar,
        .customer-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .workspace-card-body::-webkit-scrollbar-track,
        .customer-scroll::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .workspace-card-body::-webkit-scrollbar-thumb,
        .customer-scroll::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }

        /* Tab buttons */
        .tab-btn {
            background: transparent;
            color: #9CA3AF;
            /* Gray-400 */
        }

        .tab-btn:hover {
            background: #374151;
            /* Gray-700 */
            color: #F3F4F6;
            /* Gray-100 */
        }

        .tab-btn.active {
            background: #2563EB;
            /* Blue-600 */
            color: white;
        }

        /* Search input adjustments */
        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
        }

        .search-input-wrapper input {
            padding-left: 2.25rem;
        }

        /* ============================================
           WORKSPACE HEADER - REDESIGNED
           ============================================ */

        :root {
            --primary-color: #2563eb;
            --hover-bg: #374151;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --card-header-bg: #111827;
            --card-border: #374151;
            --divider-color: #374151;
        }

        .workspace-card-header.redesigned {
            flex: 0 0 auto;
            padding: 0;
            background: var(--card-header-bg);
            border-bottom: 1px solid var(--card-border);
        }

        /* Header content wrapper */
        .header-content {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        /* Left side - Tab buttons */
        .workspace-tabs {
            display: flex;
            gap: 0;
            flex: 1;
        }

        .tab-button {
            padding: 16px 24px;
            background: transparent;
            border: none;
            border-radius: 0;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 200ms ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }

        .tab-button.active {
            background: #1e293b;
            color: #60a5fa;
        }

        /* Active tab bottom tracking line */
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #3b82f6;
        }

        .tab-button:hover:not(.active) {
            background: rgba(255, 255, 255, 0.03);
            color: #d1d5db;
        }

        /* Vertical divider */
        .header-divider {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom,
                    transparent,
                    var(--divider-color) 20%,
                    var(--divider-color) 80%,
                    transparent);
            opacity: 0.5;
        }

        /* Right side - Actions container */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        /* ============================================
           SEARCH OTHER CHANNEL BUTTON - MODERN DESIGN
           ============================================ */

        .search-channel-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 250ms cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
            position: relative;
            overflow: hidden;
        }

        /* Hover effect */
        .search-channel-button:hover {
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        /* Active/Click effect */
        .search-channel-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        /* Shine effect on hover */
        .search-channel-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: left 500ms ease;
        }

        .search-channel-button:hover::before {
            left: 100%;
        }

        /* Ripple Effect */
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 600ms ease-out;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Button icon */
        .button-icon {
            font-size: 18px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Button text */
        .button-text {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Responsive behavior */
        @media (max-width: 1024px) {
            .header-content {
                gap: 12px;
            }

            .search-channel-button .button-text {
                display: none;
            }

            .search-channel-button {
                padding: 10px 12px;
                min-width: 44px;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .header-divider {
                display: none;
            }

            .header-content {
                flex-wrap: wrap;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        /* MODERN FILTER MODAL STYLING */
        .modern-filter-modal {
            background: #1f2937;
            border: 1px solid #374151;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-title-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            border-radius: 10px;
            font-size: 20px;
            color: white;
        }

        .date-input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            background: #111827;
            border: 1px solid #374151;
            border-radius: 8px;
            font-size: 13px;
            color: white;
            transition: all 200ms ease;
            outline: none;
        }

        .date-input:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .quick-date-presets {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .preset-button {
            padding: 8px;
            background: #111827;
            border: 1px solid #374151;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #9CA3AF;
            cursor: pointer;
            transition: all 200ms ease;
            text-transform: uppercase;
        }

        .preset-button:hover {
            border-color: #2563EB;
            color: #F3F4F6;
        }

        .preset-button.active {
            background: #2563EB;
            border-color: #2563EB;
            color: white;
        }
    </style>

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


    <script>
        let selectedCustomerId = null;

        // Search Customer
        function searchCustomer() {
            const input = document.getElementById('customerSearch');
            const filter = input.value.toLowerCase();
            const customerItems = document.querySelectorAll('.customer-item');

            customerItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        }

        // Select Customer
        function selectCustomer(element) {
            // Get customer data from data attributes
            const customerId = element.getAttribute('data-customer-id');
            const customerName = element.getAttribute('data-customer-name');
            const customerEmail = element.getAttribute('data-customer-email');
            const customerPhone = element.getAttribute('data-customer-phone');
            const customerMemberId = element.getAttribute('data-customer-member-id');
            const additionalContacts = JSON.parse(element.getAttribute('data-additional-contacts'));
            const transactions = JSON.parse(element.getAttribute('data-transactions'));

            selectedCustomerId = customerId;
            console.log('Selected customer:', customerId, customerName);

            // Remove previous active state
            document.querySelectorAll('.customer-item').forEach(item => {
                item.classList.remove('bg-blue-600/20', 'border-l-4', 'border-blue-500');
            });

            // Add active state to selected customer
            element.classList.add('bg-blue-600/20', 'border-l-4', 'border-blue-500');

            // Auto-fill customer data form
            document.getElementById('customerFullName').value = customerName;
            document.getElementById('customerEmail').value = customerEmail;
            document.getElementById('customerPhone').value = customerPhone;
            document.getElementById('customerMemberId').value = customerMemberId;

            // Populate transaction history table
            const transactionTbody = document.getElementById('transactionHistoryBody');
            transactionTbody.innerHTML = '';

            if (transactions && transactions.length > 0) {
                transactions.forEach(transaction => {
                    const statusColorMap = {
                        'open': 'bg-blue-500 text-white',
                        'in progress': 'bg-yellow-500 text-white',
                        'resolved': 'bg-green-500 text-white',
                        'closed': 'bg-gray-500 text-white'
                    };
                    const statusColor = statusColorMap[transaction.status.toLowerCase()] || 'bg-gray-500 text-white';

                    const row = `
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-cyan-400 font-medium">${transaction.ticket_number}</td>
                            <td class="px-4 py-3">${transaction.category}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                    ${transaction.status}
                                </span>
                            </td>
                            <td class="px-4 py-3">${transaction.user_create}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">${transaction.date_create}</td>
                        </tr>
                    `;
                    transactionTbody.innerHTML += row;
                });
            } else {
                transactionTbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No transactions available</p>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Populate additional contacts table
            const tbody = document.getElementById('additionalContactsBody');
            tbody.innerHTML = '';

            if (additionalContacts && additionalContacts.length > 0) {
                additionalContacts.forEach(contact => {
                    const statusColor = contact.status.toLowerCase() === 'active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white';

                    const row = `
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-cyan-400 font-medium">${contact.channel}</td>
                            <td class="px-4 py-3">${contact.account}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                    ${contact.status}
                                </span>
                            </td>
                            <td class="px-4 py-3">${contact.user_create}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">${contact.date_create}</td>
                            <td class="px-4 py-3">
                                <button class="text-blue-400 hover:text-blue-300">
                                    <i class="bx bx-edit text-lg"></i>
                                </button>
                                <button class="text-red-400 hover:text-red-300 ml-2">
                                    <i class="bx bx-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                                <p>No additional contacts</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        // Switch Tab
        function switchTab(tab) {
            const historyTab = document.getElementById('historyTab');
            const customerTab = document.getElementById('customerTab');
            const historyContent = document.getElementById('historyContent');
            const customerContent = document.getElementById('customerContent');

            if (tab === 'history') {
                historyTab.classList.add('active');
                customerTab.classList.remove('active');
                historyContent.classList.remove('hidden');
                customerContent.classList.add('hidden');
            } else {
                historyTab.classList.remove('active');
                customerTab.classList.add('active');
                historyContent.classList.add('hidden');
                customerContent.classList.remove('hidden');
            }
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        // Connect search channel button to existing popup
        function openSearchOtherChannelPopup() {
            openSearchOtherChannelModal();
        }

        // Ripple Effect Implementation
        document.addEventListener('click', function (e) {
            if (e.target.closest('.search-channel-button')) {
                const btn = e.target.closest('.search-channel-button');
                const ripple = document.createElement('span');
                const rect = btn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple-effect');

                btn.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            }
        });

        // Sync tab buttons with active class
        function updateTabStyles(activeTab) {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            if (activeTab === 'history') {
                document.getElementById('historyTab').classList.add('active');
            } else {
                document.getElementById('customerTab').classList.add('active');
            }
        }

        // Update switchTab function to use updateTabStyles
        const originalSwitchTab = switchTab;
        switchTab = function (tab) {
            originalSwitchTab(tab);
            updateTabStyles(tab);
        }
    </script>
</x-dashonic-horizontal-layout>