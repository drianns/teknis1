@props(['status' => null, 'type' => null])

@php
    // Use status prop if provided, otherwise use slot content
    $statusContent = $status ?? $slot->toHtml();
    $statusContent = trim(strip_tags($statusContent));
    
    // Determine if active based on content
    $isAktif = strtolower($statusContent) === 'aktif' || strtolower($statusContent) === 'active' || $type === 'active';
    
    $classes = $isAktif 
        ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' 
        : 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20';
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $classes }} {{ $attributes->get('class') }}">
    {{ $statusContent }}
</span>
