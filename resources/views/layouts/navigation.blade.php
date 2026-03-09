<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('apps.taskboard') }}" class="font-bold text-xl text-blue-600">
                        Kanmo
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex overflow-x-auto pb-2">
                    <a href="{{ route('apps.taskboard') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Taskboard</a>
                    <a href="{{ route('apps.ticketing') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Ticketing</a>
                    <a href="{{ route('channel.email.inbox') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Inbox</a>
                    <a href="{{ route('journey.index') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Journey</a>
                    <a href="{{ route('channel.email.history') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Hist
                        Email</a>
                    <a href="{{ route('apps.thread-system') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Thread</a>
                    <a href="{{ route('apps.ticketing-department') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Dept</a>
                    <a href="{{ route('apps.history-ticketing') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Hist
                        Ticket</a>
                    <a href="{{ route('master-customer.data-table') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Table
                        Cust</a>
                    <a href="{{ route('master-customer.data-customer') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">Data
                        Cust</a>
                </div>
            </div>
        </div>
    </div>
</nav>