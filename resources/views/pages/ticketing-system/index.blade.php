<x-dashonic-horizontal-layout sidebar="1" with-sidebar="{{ request()->get('with-sidebar') ?? 1 }}"
    with-header="{{ request()->get('with-header') ?? 1 }}" with-footer="{{ request()->get('with-footer') ?? 0 }}">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-2 bg-gray-800 p-2 rounded-xl h-[calc(100vh-3rem)] overflow-hidden">
        <!-- Sidebar Section (Reused) -->
        <div class="lg:col-span-3">
            <div
                class="card bg-gray-900 border-0 mb-0 rounded-xl shadow-lg overflow-y-auto backdrop-blur-sm p-4 h-[calc(100vh-4rem)]">

                <!-- Sidebar Content Wrapper (Relative for overlay positioning) -->
                <div class="relative h-full flex flex-col">
                    <!-- Header -->
                    <div class="card-header bg-gray-900 rounded-t-xl border-0 p-4">
                        <h5 class="card-title mb-0 text-white flex items-center justify-between">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Profile
                            </span>
                            <!-- Search Trigger (Striking Icon) -->
                            <button onclick="toggleSidebarSearch()"
                                class="btn btn-soft-primary btn-sm rounded-full w-8 h-8 flex items-center justify-center transition-all hover:bg-blue-600 hover:text-white"
                                title="Search Customer">
                                <i class="bx bx-search text-lg font-bold"></i>
                            </button>
                        </h5>
                    </div>

                    <!-- Profile Container (Form Input) -->
                    <div class="p-4 space-y-4 flex flex-col flex-grow overflow-y-auto">
                        <!-- Avatar Section -->
                        <div class="flex justify-center mb-4">
                            <div class="relative">
                                <div
                                    class="w-20 h-20 rounded-full p-1 ring-2 ring-blue-500/50 bg-gray-800 overflow-hidden">
                                    <img src="/assets/images/users/Profile.png" alt="Profile"
                                        class="w-full h-full object-cover rounded-full"
                                        onerror="this.onerror=null; this.src='/assets/images/users/user-dummy-img.jpg';">
                                </div>
                                <!-- Edit Icon (Optional Visual Hint) -->
                                <button
                                    class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-1.5 text-white shadow-lg hover:bg-blue-500 transition-colors">
                                    <i class="bx bx-camera text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-4">
                            <!-- Full Name -->
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-blue-400 mb-1">Full
                                    Name</label>
                                <input type="text" id="customer_name" name="customer_name"
                                    class="w-full bg-gray-800 border-0 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 placeholder-gray-500"
                                    placeholder="Nama Lengkap" value="">
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-blue-400 mb-1">Phone
                                    Number</label>
                                <input type="text" id="customer_phone" name="customer_phone"
                                    class="w-full bg-gray-800 border-0 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 placeholder-gray-500"
                                    placeholder="Phone Number" value="">
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-blue-400 mb-1">Email
                                    Address</label>
                                <input type="email" id="customer_email" name="customer_email"
                                    class="w-full bg-gray-800 border-0 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 placeholder-gray-500"
                                    placeholder="Email Address" value="">
                            </div>

                            <!-- Member ID -->
                            <div>
                                <label for="customer_member_id"
                                    class="block text-sm font-medium text-blue-400 mb-1">Member ID</label>
                                <input type="text" id="customer_member_id" name="customer_member_id"
                                    class="w-full bg-gray-800 border-0 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 placeholder-gray-500"
                                    placeholder="Member ID" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Search Overlay (Full Height) -->
                    <div id="sidebar-search-overlay"
                        class="hidden absolute inset-0 bg-gray-900 z-50 border border-gray-700 rounded-xl">
                        <!-- Header -->
                        <div
                            class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                            <h5 class="text-white font-semibold m-0">Search Customer</h5>
                            <button onclick="toggleSidebarSearch()"
                                class="text-gray-400 hover:text-white transition-colors">
                                <i class="bx bx-x text-2xl"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex-grow flex flex-col">
                            <!-- Search Bar -->
                            <div class="relative mb-4">
                                <input type="text" id="sidebar-search-input"
                                    class="form-control bg-gray-800 border-gray-600 text-white placeholder-gray-500 rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search customer...">
                                <i class="bx bx-search absolute left-3 top-2.5 text-gray-400 text-lg"></i>
                            </div>

                            <!-- Search Results Container -->
                            <div id="sidebar-search-results"
                                class="hidden max-h-[350px] overflow-y-auto mb-4 space-y-3 custom-scrollbar">
                                <!-- Dynamic Content will be injected here -->
                            </div>

                            <!-- Results Placeholder -->
                            <div id="sidebar-search-placeholder"
                                class="flex-grow overflow-y-auto mb-4 text-center text-gray-500 flex items-center justify-center border border-dashed border-gray-700 rounded-lg">
                                <div>
                                    <i class="bx bx-search-alt text-4xl mb-2 opacity-50"></i>
                                    <p class="text-sm">Enter keyword to search</p>
                                </div>
                            </div>

                            <!-- Action Section -->
                            <div id="sidebar-search-actions" class="mt-auto">
                                <h6
                                    class="text-gray-400 text-xs uppercase font-semibold mb-3 border-b border-gray-700 pb-2">
                                    Action</h6>
                                <div class="grid grid-cols-1 gap-2">
                                    <button onclick="togglePopup('popup-new-customer')"
                                        class="btn btn-outline-secondary bg-gray-800 border-gray-600 text-gray-300 hover:text-white hover:bg-gray-700 text-left flex items-center gap-2 py-2 rounded-lg">
                                        <i class="bx bx-user-plus text-blue-400"></i> New Costumer
                                    </button>
                                    <button onclick="togglePopup('popup-api-customer')"
                                        class="btn btn-outline-secondary bg-gray-800 border-gray-600 text-gray-300 hover:text-white hover:bg-gray-700 text-left flex items-center gap-2 py-2 rounded-lg">
                                        <i class="bx bx-code-alt text-green-400"></i> API Costumer
                                    </button>
                                    <button onclick="togglePopup('popup-other-channel')"
                                        class="btn btn-outline-secondary bg-gray-800 border-gray-600 text-gray-300 hover:text-white hover:bg-gray-700 text-left flex items-center gap-2 py-2 rounded-lg">
                                        <i class="bx bx-planet text-purple-400"></i> Other Channel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section (Tabs) -->
        <div class="lg:col-span-9 relative">
            <!-- New Customer Popup (Panel) -->
            <div id="popup-new-customer"
                class="hidden absolute top-0 left-0 w-full h-full bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50 flex flex-col">
                <!-- Header -->
                <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                    <h5 class="text-white font-semibold m-0">Form Add Customer</h5>
                    <button onclick="togglePopup('popup-new-customer')"
                        class="text-gray-400 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <!-- Body (Form) -->
                <div class="p-6 bg-[#0f1422] overflow-y-auto flex-grow">
                    <form action="#" class="space-y-4">
                        <div class="grid grid-cols-2 gap-5">
                            <!-- Full Name -->
                            <div class="col-span-2 space-y-1.5">
                                <label class="font-semibold text-gray-300 text-sm">Full Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text"
                                    class="w-full bg-[#1b2131] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 shadow-sm placeholder-gray-500 transition-colors"
                                    placeholder="Full Name">
                            </div>

                            <!-- Email Address -->
                            <div class="col-span-1 space-y-1.5">
                                <label class="font-semibold text-gray-300 text-sm">Email Address <span
                                        class="text-red-500">*</span></label>
                                <input type="email"
                                    class="w-full bg-[#1b2131] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 shadow-sm placeholder-gray-500 transition-colors"
                                    placeholder="Email Address">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-span-1 space-y-1.5">
                                <label class="font-semibold text-gray-300 text-sm">Phone Number <span
                                        class="text-red-500">*</span></label>
                                <input type="text"
                                    class="w-full bg-[#1b2131] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 shadow-sm placeholder-gray-500 transition-colors"
                                    placeholder="Phone Number">
                            </div>

                            <!-- Member ID -->
                            <div class="col-span-2 space-y-1.5">
                                <label class="font-semibold text-gray-300 text-sm">Member ID</label>
                                <input type="text"
                                    class="w-full bg-[#1b2131] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 shadow-sm placeholder-gray-500 transition-colors"
                                    placeholder="Member ID">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl">
                    <button onclick="togglePopup('popup-new-customer')"
                        class="btn btn-outline-danger border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-x-circle"></i> Close
                    </button>
                    <button
                        class="btn btn-info bg-cyan-500 hover:bg-cyan-600 border-none text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-save"></i> Save
                    </button>
                </div>
            </div>

            <!-- API Customer Popup (Panel) -->
            <div id="popup-api-customer"
                class="hidden absolute top-0 left-0 w-full h-full bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50 flex flex-col">
                <!-- Header -->
                <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                    <h5 class="text-white font-semibold m-0">Form Add Customer</h5>
                    <button onclick="togglePopup('popup-api-customer')"
                        class="text-gray-400 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <!-- Body (Form) -->
                <div class="p-6 overflow-y-auto flex-grow">
                    <form action="#">
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm mb-1">Channel Value <span
                                    class="text-red-500">*</span></label>
                            <input type="text"
                                class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Channel Value">
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl">
                    <button onclick="togglePopup('popup-api-customer')"
                        class="btn btn-outline-danger border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-x-circle"></i> Close
                    </button>
                    <button
                        class="btn btn-info bg-cyan-500 hover:bg-cyan-600 border-none text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-save"></i> Submit
                    </button>
                </div>
            </div>

            <!-- Other Channel Popup (Panel) -->
            <div id="popup-other-channel"
                class="hidden absolute top-0 left-0 w-full h-full bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50 flex flex-col">
                <!-- Header -->
                <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                    <h5 class="text-white font-semibold m-0">Form Other Channel</h5>
                    <button onclick="togglePopup('popup-other-channel')"
                        class="text-gray-400 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <!-- Body (Grid Visual) -->
                <div class="p-6 overflow-y-auto flex-grow bg-gray-900">
                    <div class="border-b border-gray-700 py-2 mb-4">
                        <p class="text-gray-400 text-sm">Drag a column header here to group by that column</p>
                    </div>

                    <!-- Empty State / Grid Placeholder -->
                    <div
                        class="flex items-center justify-center h-64 border border-dashed border-gray-700 rounded-lg mb-4 bg-gray-800/50">
                        <span class="text-gray-500">No records available</span>
                    </div>

                    <!-- Pagination Mockup -->
                    <div class="flex justify-between items-center text-sm text-gray-400">
                        <span>Page 1 of 3238 (32371 items)</span>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-600 text-gray-400 hover:bg-gray-700 hover:text-white transition-colors"><i
                                        class="bx bx-chevron-left"></i></button>
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-sm bg-blue-600 text-white shadow-lg shadow-blue-500/30">1</button>
                                <span>...</span>
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-600 text-gray-400 hover:bg-gray-700 hover:text-white transition-colors"><i
                                        class="bx bx-chevron-right"></i></button>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Page size:</span>
                                <select
                                    class="form-select bg-gray-800 border-gray-600 text-white text-sm py-1 rounded focus:ring-blue-500 focus:border-blue-500">
                                    <option>10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-700 flex justify-end bg-gray-800 rounded-b-xl">
                    <button onclick="togglePopup('popup-other-channel')"
                        class="btn btn-outline-danger border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-x-circle"></i> Close
                    </button>
                </div>
            </div>

            <!-- Add Channel Customer Popup (Panel) -->
            <div id="popup-add-channel-customer"
                class="hidden absolute top-0 left-0 w-full h-full bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50 flex flex-col">
                <!-- Header -->
                <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800 rounded-t-xl">
                    <h5 class="text-white font-semibold m-0">Form Add Channel Customer</h5>
                    <button onclick="togglePopup('popup-add-channel-customer')"
                        class="text-gray-400 hover:text-white transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <!-- Body (Form) -->
                <div class="p-6 overflow-y-auto flex-grow bg-gray-900">
                    <form action="#">
                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm mb-1">Channel Value <span
                                    class="text-red-500">*</span></label>
                            <input type="text"
                                class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Channel Value">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-400 text-sm mb-1">Channel Type</label>
                            <select
                                class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option selected>Select</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="twitter">Twitter</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-700 flex justify-between bg-gray-800 rounded-b-xl">
                    <button onclick="togglePopup('popup-add-channel-customer')"
                        class="btn btn-outline-danger border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-x-circle"></i> Close
                    </button>
                    <button
                        class="btn btn-info bg-cyan-500 hover:bg-cyan-600 border-none text-white rounded-full px-6 flex items-center gap-2">
                        <i class="bx bx-save"></i> Save
                    </button>
                </div>
            </div>

            <div x-data="{ activeTab: 'data-content' }"
                class="card bg-gray-900 border-0 mb-0 rounded-xl shadow-lg overflow-y-auto backdrop-blur-sm h-[calc(100vh-4rem)]">
                <!-- Tab Navigation using Separated Modern Tabs -->
                <div class="card-header bg-gray-900 border-b border-gray-700 p-4">
                    <ul class="nav nav-pills nav-fill w-full flex gap-4" id="ticketTab" role="tablist">
                        <li class="nav-item flex-1" role="presentation">
                            <button
                                class="nav-link w-full flex items-center justify-center gap-2 py-3 rounded-lg transition-all duration-300 font-medium relative overflow-hidden group"
                                :class="{ 'active': activeTab === 'data-content' }" @click="activeTab = 'data-content'"
                                id="data-tab" type="button" role="tab">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="bx bx-purchase-tag-alt text-xl"></i>
                                    Data Ticketing
                                </span>
                            </button>
                        </li>
                        <li class="nav-item flex-1" role="presentation">
                            <button
                                class="nav-link w-full flex items-center justify-center gap-2 py-3 rounded-lg transition-all duration-300 font-medium relative overflow-hidden group"
                                :class="{ 'active': activeTab === 'history-content' }"
                                @click="activeTab = 'history-content'" id="history-tab" type="button" role="tab">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="bx bx-list-ul text-xl"></i>
                                    History Ticketing
                                </span>
                            </button>
                        </li>
                        <li class="nav-item flex-1" role="presentation">
                            <button
                                class="nav-link w-full flex items-center justify-center gap-2 py-3 rounded-lg transition-all duration-300 font-medium relative overflow-hidden group"
                                :class="{ 'active': activeTab === 'customer-content' }"
                                @click="activeTab = 'customer-content'" id="customer-tab" type="button" role="tab">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="bx bx-id-card text-xl"></i>
                                    Customer Data
                                </span>
                            </button>
                        </li>
                        <li class="nav-item flex-1" role="presentation">
                            <button
                                class="nav-link w-full flex items-center justify-center gap-2 py-3 rounded-lg transition-all duration-300 font-medium relative overflow-hidden group"
                                :class="{ 'active': activeTab === 'note-content' }" @click="activeTab = 'note-content'"
                                id="note-tab" type="button" role="tab">
                                <span class="relative z-10 flex items-center gap-2">
                                    <i class="bx bx-note text-xl"></i>
                                    Instan Note
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="card-body p-6">
                    <div class="tab-content" id="ticketTabContent">
                        <!-- Data Ticketing Tab -->
                        <div x-show="activeTab === 'data-content'" x-transition id="data-content" role="tabpanel">
                            <form action="#" method="POST" id="form-ticketing">
                                @csrf
                                <div class="space-y-8">
                                    <!-- Section 1: Reporter Information -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-user-pin text-sm"></i> Reporter Information
                                            </h6>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                                            <!-- Full Name -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Full Name
                                                    Reported</label>
                                                <input type="text"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputName" placeholder="Full Name">
                                            </div>
                                            <!-- Email -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Email
                                                    Reported</label>
                                                <input type="email"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputEmail" placeholder="E-mail">
                                            </div>
                                            <!-- Contact Number -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Contact
                                                    Number Reported</label>
                                                <input type="text"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputPhone" placeholder="Phone">
                                            </div>
                                            <!-- Contact Account -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Contact
                                                    Account Reported</label>
                                                <input type="text"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputAccount" placeholder="Channel Contact">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2: Transaction & Source -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-transfer text-sm"></i> Transaction & Source
                                            </h6>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                                            <!-- Date -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Date of
                                                    Transaction</label>
                                                <input type="date"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputDate">
                                            </div>
                                            <!-- Agent Name -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Agent
                                                    Name</label>
                                                <input type="text"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full cursor-not-allowed opacity-75"
                                                    value="{{ Auth::user()->name ?? 'Current Agent' }}" readonly>
                                            </div>
                                            <!-- Channel -->
                                            <div>
                                                <label
                                                    class="block text-gray-400 text-xs mb-1 font-medium">Channel</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($channels as $channel)
                                                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Source -->
                                            <div>
                                                <label
                                                    class="block text-gray-400 text-xs mb-1 font-medium">Source</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="call">Call</option>
                                                    <option value="chat">Chat</option>
                                                </select>
                                            </div>
                                            <!-- Order ID -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Order
                                                    ID</label>
                                                <input type="text"
                                                    class="form-control bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="inputOrderId" placeholder="Order ID">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 3: Ticket Classification -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-category text-sm"></i> Ticket Classification
                                            </h6>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                                            <!-- Category (Full Width) -->
                                            <div class="lg:col-span-2">
                                                <label
                                                    class="block text-gray-400 text-xs mb-1 font-medium">Category</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="selectCategory">
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach($chat_ticket_categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Sub Category (Full Width) -->
                                            <div class="lg:col-span-2">
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Sub
                                                    Category</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors"
                                                    id="selectSubCategory">
                                                    <option value="" selected>Select Sub Category</option>
                                                    <option value="1">Sub Category Demo</option>
                                                </select>
                                            </div>
                                            <!-- Activity -->
                                            <div class="lg:col-span-2">
                                                <label
                                                    class="block text-gray-400 text-xs mb-1 font-medium">Activity</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Activity</option>
                                                </select>
                                            </div>
                                            <!-- Type -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Type</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Type</option>
                                                </select>
                                            </div>
                                            <!-- Ticket Status -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Ticket
                                                    Status</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    @foreach($chat_ticket_statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 4: Product Details -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-package text-sm"></i> Product Details
                                            </h6>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                                            <!-- Brand Name -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Brand
                                                    Name</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Brand</option>
                                                </select>
                                            </div>
                                            <!-- Brand Category -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Brand
                                                    Category</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Brand Cat</option>
                                                </select>
                                            </div>
                                            <!-- Group Name -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Group
                                                    Name</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Group</option>
                                                </select>
                                            </div>
                                            <!-- Fulfillment -->
                                            <div>
                                                <label
                                                    class="block text-gray-400 text-xs mb-1 font-medium">Fulfillment</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Dummy Fulfillment 1</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 5: Escalation & Meta -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-trending-up text-sm"></i> Escalation & Meta
                                            </h6>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
                                            <!-- Escalation Unit -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Escalation
                                                    Unit</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Unit 1</option>
                                                </select>
                                            </div>
                                            <!-- Escalation Ticket -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Escalation
                                                    Ticket</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Select</option>
                                                    <option value="1">Tiket 1</option>
                                                </select>
                                            </div>
                                            <!-- Meta (Full Width) -->
                                            <div class="lg:col-span-2">
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Meta</label>
                                                <select
                                                    class="form-select bg-gray-800 border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full hover:border-gray-600 transition-colors">
                                                    <option value="" selected>Meta</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 6: Interaction Details -->
                                    <div>
                                        <div class="border-b border-gray-700 pb-2 mb-4">
                                            <h6
                                                class="text-blue-400 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                                <i class="bx bx-message-detail text-sm"></i> Interaction Details
                                            </h6>
                                        </div>
                                        <div class="space-y-4">
                                            <!-- Customer Question (Rich Text) -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Customer
                                                    Question</label>
                                                <div
                                                    class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-inner">
                                                    <div id="editor-customer-question"
                                                        style="height: 150px; border: none;" class="text-gray-300">
                                                    </div>
                                                    <textarea name="customer_question" class="hidden"></textarea>
                                                </div>
                                            </div>

                                            <!-- Agent Response (Rich Text) -->
                                            <div>
                                                <label class="block text-gray-400 text-xs mb-1 font-medium">Agent
                                                    Response</label>
                                                <div
                                                    class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-inner">
                                                    <div id="editor-agent-response" style="height: 150px; border: none;"
                                                        class="text-gray-300"></div>
                                                    <textarea name="agent_response" class="hidden"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <!-- Action Buttons -->
                                <div class="mt-8 flex justify-between items-center pt-4 border-t border-gray-700">
                                    <!-- Left Section: Attachment -->
                                    <div>
                                        <input type="file" id="ticketAttachment" class="hidden" multiple>
                                        <button type="button"
                                            onclick="document.getElementById('ticketAttachment').click()"
                                            class="btn bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white border-0 rounded-lg px-4 py-2 flex items-center gap-2 transition-colors">
                                            <i class="bx bx-paperclip text-lg"></i> Attachment
                                        </button>
                                    </div>

                                    <!-- Right Section: Form Actions -->
                                    <div class="flex gap-3">
                                        <button type="button"
                                            class="btn bg-gray-700 text-white hover:bg-gray-600 border-0 rounded-lg px-6 py-2">Reset</button>
                                        <button type="submit"
                                            class="btn bg-blue-600 text-white hover:bg-blue-700 border-0 rounded-lg px-8 py-2 font-semibold shadow-lg shadow-blue-500/30">
                                            <i class="bx bx-save mr-2"></i> Save Ticket
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <!-- History Ticketing Tab -->
                        <div x-show="activeTab === 'history-content'" x-transition id="history-content" role="tabpanel">
                            <!-- Controls Header -->
                            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                                <h4 class="text-white text-md font-semibold">Previous Tickets</h4>

                                <div class="flex items-center gap-3">
                                    <!-- Show Entries -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400 text-sm">Show</span>
                                        <select
                                            onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}#history-content'"
                                            class="form-select bg-gray-800 border-gray-700 text-white text-sm rounded-lg py-1 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10
                                            </option>
                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25
                                            </option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                            </option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                            </option>
                                        </select>
                                        <span class="text-gray-400 text-sm">entries</span>
                                    </div>

                                    <!-- Search -->
                                    <div class="relative">
                                        <form action="" method="GET">
                                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                class="form-control bg-gray-800 border-gray-700 text-white text-sm rounded-lg pl-3 pr-10 py-1.5 focus:ring-blue-500 focus:border-blue-500 w-48 md:w-64"
                                                placeholder="Search tickets...">
                                            <button type="submit"
                                                class="absolute right-2 top-1.5 text-gray-400 hover:text-white">
                                                <i class="bx bx-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-lg">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-800 text-gray-400 text-xs uppercase">
                                        <tr>
                                            <th class="px-4 py-3">Ticket Number <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                            <th class="px-4 py-3">Category <i class="bx bx-sort text-gray-600 ml-1"></i>
                                            </th>
                                            <th class="px-4 py-3">Status <i class="bx bx-sort text-gray-600 ml-1"></i>
                                            </th>
                                            <th class="px-4 py-3">User Create <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                            <th class="px-4 py-3">Date Create <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                            <th class="px-4 py-3 text-center">Action <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-gray-300 divide-y divide-gray-800 bg-transparent">
                                        @forelse($ticket_history as $index => $ticket)
                                            <tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-800/20">
                                                <td class="px-4 py-3 font-medium text-blue-400">
                                                    {{ $ticket->ticket_number ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $ticket->category ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @php
                                                        $statusColor = 'bg-gray-700 text-gray-300';
                                                        if (strtolower($ticket->status) == 'open')
                                                            $statusColor = 'bg-blue-900/50 text-blue-300 border border-blue-800';
                                                        if (strtolower($ticket->status) == 'closed')
                                                            $statusColor = 'bg-green-900/50 text-green-300 border border-green-800';
                                                        if (strtolower($ticket->status) == 'pending')
                                                            $statusColor = 'bg-yellow-900/50 text-yellow-300 border border-yellow-800';
                                                    @endphp
                                                    <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">
                                                        {{ $ticket->status ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        @if($ticket->chat_ticket_user && $ticket->chat_ticket_user->photo_src)
                                                            <img src="{{ $ticket->chat_ticket_user->photo_src }}"
                                                                class="w-6 h-6 rounded-full">
                                                        @else
                                                            <div
                                                                class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs">
                                                                {{ substr($ticket->chat_ticket_user->name ?? 'U', 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <span>{{ $ticket->chat_ticket_user->name ?? 'Unknown' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $ticket->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <a href="#"
                                                        class="btn btn-sm bg-gray-700 hover:bg-gray-600 text-white rounded px-2 py-1"
                                                        title="View Details">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                    <i class="bx bx-folder-open text-4xl mb-2"></i>
                                                    <p>No data available in table</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $ticket_history->links('pagination::bootstrap-5') }}
                                <!-- Using standard pagination view for now -->
                            </div>
                        </div>

                        <!-- Customer Data Tab -->
                        <div x-show="activeTab === 'customer-content'" x-transition id="customer-content"
                            role="tabpanel">
                            <!-- Controls Header -->
                            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                                <h4 class="text-white text-md font-semibold">Customer List</h4>

                                <div class="flex items-center gap-3">
                                    <!-- Show Entries -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400 text-sm">Show</span>
                                        <select
                                            onchange="window.location.href='?per_page_customer='+this.value+'&search_customer={{ request('search_customer') }}#customer-content'"
                                            class="form-select bg-gray-800 border-gray-700 text-white text-sm rounded-lg py-1 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="10" {{ request('per_page_customer') == 10 ? 'selected' : '' }}>
                                                10</option>
                                            <option value="25" {{ request('per_page_customer') == 25 ? 'selected' : '' }}>
                                                25</option>
                                            <option value="50" {{ request('per_page_customer') == 50 ? 'selected' : '' }}>
                                                50</option>
                                            <option value="100" {{ request('per_page_customer') == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        <span class="text-gray-400 text-sm">entries</span>
                                    </div>

                                    <!-- Search & Add -->
                                    <div class="flex items-center gap-2">
                                        <div class="relative">
                                            <form action="" method="GET">
                                                <input type="hidden" name="per_page_customer"
                                                    value="{{ request('per_page_customer', 10) }}">
                                                <input type="text" name="search_customer"
                                                    value="{{ request('search_customer') }}"
                                                    class="form-control bg-gray-800 border-gray-700 text-white text-sm rounded-lg pl-3 pr-10 py-1.5 focus:ring-blue-500 focus:border-blue-500 w-48 md:w-64"
                                                    placeholder="Search customers...">
                                                <button type="submit"
                                                    class="absolute right-2 top-1.5 text-gray-400 hover:text-white">
                                                    <i class="bx bx-search"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <button onclick="togglePopup('popup-add-channel-customer')"
                                            class="btn btn-sm bg-gray-700 hover:bg-gray-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors shadow-lg"
                                            title="Add Channel Customer">
                                            <i class="bx bx-plus text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-lg">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-800 text-gray-400 text-xs uppercase">
                                        <tr>
                                            <th class="px-4 py-3">Channel <i class="bx bx-sort text-gray-600 ml-1"></i>
                                            </th>
                                            <th class="px-4 py-3">Account <i class="bx bx-sort text-gray-600 ml-1"></i>
                                            </th>
                                            <th class="px-4 py-3">Status <i class="bx bx-sort text-gray-600 ml-1"></i>
                                            </th>
                                            <th class="px-4 py-3">User Create <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                            <th class="px-4 py-3 text-center">Action <i
                                                    class="bx bx-sort text-gray-600 ml-1"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-gray-300 divide-y divide-gray-800 bg-transparent">
                                        @forelse($customers as $index => $customer)
                                            <tr class="hover:bg-gray-800/50 transition-colors even:bg-gray-800/20">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        @if($customer->channel && $customer->channel->logo)
                                                            <img src="{{ $customer->channel->logo }}"
                                                                class="w-5 h-5 object-contain">
                                                        @endif
                                                        <span>{{ $customer->channel->name ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 font-medium text-white">
                                                    {{ $customer->account_id ?? $customer->name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs bg-green-900/50 text-green-300 border border-green-800">Active</span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        @if($customer->chat_ticket_user && $customer->chat_ticket_user->photo_src)
                                                            <img src="{{ $customer->chat_ticket_user->photo_src }}"
                                                                class="w-6 h-6 rounded-full">
                                                        @endif
                                                        <span>{{ $customer->chat_ticket_user->name ?? 'Unknown' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="text-gray-400 hover:text-white transition-colors"
                                                        title="View Details">
                                                        <i class="bx bx-show text-lg"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                    <i class="bx bx-user-x text-4xl mb-2"></i>
                                                    <p>No customer data found.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $customers->appends(['customer_page' => $customers->currentPage()])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        <!-- Instan Note Tab -->
                        <div x-show="activeTab === 'note-content'" x-transition id="note-content" role="tabpanel">
                            <form action="#" method="POST" id="form-note">
                                @csrf
                                <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                                    <!-- Quill Toolbar container is auto-generated, but we wrapper needs to handle border -->
                                    <div id="instan-note-editor" style="height: 300px; border: none;"
                                        class="text-gray-300"></div>
                                    <textarea name="note" class="hidden"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button type="button"
                                        class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white border-none px-6 py-2 rounded-lg flex items-center gap-2 shadow-lg">
                                        <i class="bx bx-save"></i> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Custom Style for Separated Tabs & Form Overrides -->
    <style>
        /* Base State for Nav Pills */
        .nav-pills .nav-link {
            background-color: #1f2937;
            /* gray-800 */
            color: #9ca3af;
            /* gray-400 */
            border: 1px solid #374151;
            /* gray-700 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Hover State */
        .nav-pills .nav-link:hover {
            background-color: #374151;
            /* gray-700 */
            color: #f3f4f6;
            /* gray-100 */
            border-color: #4b5563;
            /* gray-600 */
            transform: translateY(-1px);
        }

        /* Active State */
        .nav-pills .nav-link.active {
            background-color: #2563eb;
            /* blue-600 */
            color: white;
            border-color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4), 0 2px 4px -1px rgba(37, 99, 235, 0.2);
            font-weight: 600;
        }

        .nav-pills .nav-link.active i {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        /* FORM OVERRIDES FOR DARK MODE */
        /* Force background color on focus and disabled states to override Bootstrap/Browser defaults */
        #form-ticketing .form-control:focus,
        #form-ticketing .form-select:focus {
            background-color: #1f2937 !important;
            /* bg-gray-800 */
            color: #ffffff !important;
            border-color: #3b82f6 !important;
            /* blue-500 */
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }

        #form-ticketing .form-control:disabled,
        #form-ticketing .form-select:disabled,
        #form-ticketing .form-control[readonly] {
            background-color: #1f2937 !important;
            /* bg-gray-800 */
            color: #d1d5db !important;
            /* text-gray-300 */
            opacity: 1;
            /* Reset opacity for readability */
            border-color: #374151 !important;
            /* border-gray-700 */
        }

        /* Placeholder color */
        #form-ticketing .form-control::placeholder {
            color: #9ca3af;
            /* gray-400 */
        }
    </style>





    <x-slot name="css">
        <link href="{{ url('/') }}/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('/') }}/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />
        <style>
            /* Custom Dark Mode for Quill */
            .ql-toolbar.ql-snow {
                background-color: #374151;
                /* gray-700 */
                border-color: #4b5563 !important;
                /* gray-600 */
                color: #e5e7eb;
                /* gray-200 */
                border-top-left-radius: 0.5rem;
                border-top-right-radius: 0.5rem;
            }

            .ql-container.ql-snow {
                border-color: #4b5563 !important;
                /* gray-600 */
                background-color: #1f2937;
                /* gray-800 */
                color: #d1d5db;
                /* gray-300 */
                font-family: 'Inter', sans-serif;
                border-bottom-left-radius: 0.5rem;
                border-bottom-right-radius: 0.5rem;
            }

            .ql-snow .ql-stroke {
                stroke: #e5e7eb;
                /* gray-200 */
            }

            .ql-snow .ql-fill {
                fill: #e5e7eb;
                /* gray-200 */
            }

            .ql-snow .ql-picker {
                color: #e5e7eb;
            }

            /* Active State for Toolbar Buttons */
            .ql-snow .ql-active .ql-stroke {
                stroke: #3b82f6 !important;
                /* blue-500 */
            }

            .ql-snow .ql-active .ql-fill {
                fill: #3b82f6 !important;
                /* blue-500 */
            }

            /* Force Bold and Italic rendering in editor (overcoming Tailwind Preflight) */
            .ql-editor strong {
                font-weight: bold !important;
            }

            .ql-editor em {
                font-style: italic !important;
            }

            /* Sidebar Search Input Override */
            #sidebar-search-input:focus {
                background-color: #1f2937 !important;
                /* gray-800 */
                color: #ffffff !important;
                border-color: #3b82f6 !important;
                /* blue-500 */
                box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
            }

            /* New Customer Popup Inputs Override */
            #popup-new-customer .form-control:focus {
                background-color: #1f2937 !important;
                /* gray-800 */
                color: #ffffff !important;
                border-color: #3b82f6 !important;
                /* blue-500 */
                box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
            }

            /* API Customer Popup Inputs Override */
            #popup-api-customer .form-control:focus {
                background-color: #1f2937 !important;
                /* gray-800 */
                color: #ffffff !important;
                border-color: #3b82f6 !important;
                /* blue-500 */
                box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
            }

            /* Add Channel Customer & Other Channel Popup Inputs Override */
            #popup-add-channel-customer .form-control:focus,
            #popup-other-channel .form-control:focus,
            #popup-add-channel-customer .form-select:focus,
            #popup-other-channel .form-select:focus {
                background-color: #1f2937 !important;
                /* gray-800 */
                color: #ffffff !important;
                border-color: #3b82f6 !important;
                /* blue-500 */
                box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
            }

            /* "Bwop" Pop-in Animation */
            @keyframes popIn {
                0% {
                    opacity: 0;
                    transform: scale(0.9) translateY(10px);
                }

                50% {
                    transform: scale(1.02);
                }

                100% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            /* Pop-out Animation (Bwop Close) */
            @keyframes popOut {
                0% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }

                40% {
                    transform: scale(1.02);
                }

                /* Anticipation bounce */
                100% {
                    opacity: 0;
                    transform: scale(0.9) translateY(10px);
                }
            }

            .animate-open {
                animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            }

            .animate-close {
                animation: popOut 0.3s ease-in forwards;
            }
        </style>
    </x-slot>



    <x-slot name="js">
        <script src="{{ url('/') }}/assets/libs/quill/quill.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Quill Editors
                initQuillEditor("#instan-note-editor", "note");
                initQuillEditor("#editor-customer-question", "customer_question");
                initQuillEditor("#editor-agent-response", "agent_response");
            });

            function initQuillEditor(selector, inputName) {
                if (document.querySelector(selector)) {
                    var quill = new Quill(selector, {
                        theme: "snow",
                        placeholder: 'Type your text here...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['link', 'image', 'table'],
                                ['clean']
                            ]
                        }
                    });

                    // Sync with textarea on submit or change
                    quill.on('text-change', function () {
                        var input = document.querySelector('textarea[name=' + inputName + ']');
                        if (input) input.value = quill.root.innerHTML;
                    });
                }
            }

            // Generic toggle for animation
            function toggleElementWithAnimation(elementId) {
                const el = document.getElementById(elementId);
                if (el.classList.contains('hidden')) {
                    // Open
                    el.classList.remove('hidden');
                    el.classList.remove('animate-close');
                    el.classList.add('animate-open');
                } else {
                    // Close
                    el.classList.remove('animate-open');
                    el.classList.add('animate-close');
                    el.addEventListener('animationend', function () {
                        if (el.classList.contains('animate-close')) {
                            el.classList.add('hidden');
                        }
                    }, { once: true });
                }
            }

            function toggleSidebarSearch() {
                toggleElementWithAnimation('sidebar-search-overlay');
            }

            const managedPopups = [
                'popup-new-customer',
                'popup-api-customer',
                'popup-other-channel',
                'popup-add-channel-customer'
            ];

            function togglePopup(popupId) {
                const targetEl = document.getElementById(popupId);
                if (!targetEl) return;

                // If currently closed, we want to open it. 
                // Before opening, close any other currently open managed popups.
                if (targetEl.classList.contains('hidden') || targetEl.classList.contains('animate-close')) {
                    managedPopups.forEach(id => {
                        if (id !== popupId) {
                            const otherEl = document.getElementById(id);
                            if (otherEl && !otherEl.classList.contains('hidden') && !otherEl.classList.contains('animate-close')) {
                                toggleElementWithAnimation(id); // Trigger close
                            }
                        }
                    });
                }

                toggleElementWithAnimation(popupId);
            }

            // Search Customer Logic
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('sidebar-search-input');
                const searchResults = document.getElementById('sidebar-search-results');
                const searchPlaceholder = document.getElementById('sidebar-search-placeholder');
                const searchActions = document.getElementById('sidebar-search-actions');

                if (searchInput && searchResults && searchPlaceholder && searchActions) {
                    const mockData = [
                        { name: 'Boy', email: 'diorahmad777@gmail.com', phone: '6285359473293' },
                        { name: 'Tiara', email: 'tiararahmani93@gmail.com', phone: '6281320007679' },
                        { name: 'Rahmat Hidayat', email: 'fifimurfiyasih13@icloud.com', phone: '6282217782746' },
                        { name: 'Ahmad', email: 'moejeep@gmail.com', phone: '6282114957673' },
                        { name: 'Yanti', email: 'yanti.rahman76@gmail.com', phone: '6285244310008' },
                        { name: 'Ahmad Zariruddin', email: 'a.zariruddin@gmail.com', phone: '6285212348983' }
                    ];

                    searchInput.addEventListener('input', function (e) {
                        const keyword = e.target.value.toLowerCase();

                        if (keyword.length > 0) {
                            searchPlaceholder.classList.add('hidden');
                            searchActions.classList.add('hidden');
                            searchResults.classList.remove('hidden');

                            // Filter mock data (simple substring match)
                            const filtered = mockData.filter(item =>
                                item.name.toLowerCase().includes(keyword) ||
                                item.email.toLowerCase().includes(keyword)
                            );

                            // Render Results
                            searchResults.innerHTML = filtered.length ? filtered.map(item => `
                                <div onclick="selectCustomer('${item.name}')" class="cursor-pointer bg-gray-800 rounded-lg p-3 flex items-center gap-3 border border-gray-700 hover:border-gray-500 hover:bg-gray-700 transition-all group">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center shrink-0 ring-2 ring-gray-600 group-hover:ring-blue-500 transition-all">
                                        <i class="bx bx-user text-2xl text-gray-400 group-hover:text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="text-white text-sm font-bold mb-0.5 truncate group-hover:text-blue-400 transition-colors">${item.name}</h6>
                                        <p class="text-gray-400 text-xs mb-0.5 truncate">${item.email}</p>
                                        <p class="text-blue-400 text-xs font-mono truncate">${item.phone}</p>
                                    </div>
                                    <div class="shrink-0 text-gray-500 group-hover:text-blue-400 transition-colors">
                                        <i class="bx bx-chevron-right text-xl"></i>
                                    </div>
                                </div>
                            `).join('') : `
                                <div class="text-center py-8 text-gray-500">
                                    <i class="bx bx-search text-3xl mb-2 opacity-50"></i>
                                    <p class="text-sm">No results found for "${escapeHtml(keyword)}"</p>
                                </div>
                            `;

                        } else {
                            searchPlaceholder.classList.remove('hidden');
                            searchActions.classList.remove('hidden');
                            searchResults.classList.add('hidden');
                            searchResults.innerHTML = '';
                        }
                    });
                }
            });

            // Mock selection function
            function selectCustomer(name) {
                // Here you would typically fill the form or specific logic
                console.log('Customer Selected:', name);
                const input = document.getElementById('customer_name');
                if (input) input.value = name;

                // Close sidebar search for demo feeling
                toggleSidebarSearch();
            }

            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        </script>
    </x-slot>

</x-dashonic-horizontal-layout>