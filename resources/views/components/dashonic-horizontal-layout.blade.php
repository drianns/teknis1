@props(['sidebar' => '1', 'withSidebar' => '1', 'withHeader' => '1', 'withFooter' => '0'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Kanmo CRM') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{ $css ?? '' }}
</head>

<body class="font-sans antialiased text-gray-200 bg-gray-900 h-screen overflow-hidden flex flex-col">
    <div class="flex h-full overflow-hidden">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <div
            class="flex-1 flex flex-col min-w-0 transition-all duration-300 ml-[60px] h-full overflow-y-auto custom-scrollbar">
            <!-- Header (Optional) -->
            @if($withHeader == '1' && false) {{-- Set to false because user wants to replace navbar with sidebar --}}
                @include('layouts.navigation')
            @endif

            <!-- Main Page Content -->
            <main class="flex-1 flex flex-col p-0 relative">
                {{ $slot }}
            </main>

            <!-- Footer (Optional) -->
            @if($withFooter == '1')
                <footer class="py-6 text-center text-gray-500 text-sm border-t border-gray-800 bg-gray-900/50">
                    &copy; {{ date('Y') }} Kanmo Group. All rights reserved.
                </footer>
            @endif
        </div>
    </div>

    {{ $js ?? '' }}
</body>

</html>