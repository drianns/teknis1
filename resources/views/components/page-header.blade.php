@props(['title', 'breadcrumbs' => []])

<header class="flex-shrink-0 mb-3 px-6 pt-4">
    <div class="flex justify-between items-center mb-2">
        <h1 class="text-[28px] font-bold text-white tracking-tight">{{ $title }}</h1>
        @if(isset($slot) && $slot->isNotEmpty())
            <div class="flex items-center gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <span>Home</span>
        @foreach($breadcrumbs as $breadcrumb)
            <span class="mx-2 text-gray-600">/</span>
            @if($loop->last)
                <span class="text-blue-500 font-semibold">{{ $breadcrumb }}</span>
            @else
                <span>{{ $breadcrumb }}</span>
            @endif
        @endforeach
    </div>
</header>
