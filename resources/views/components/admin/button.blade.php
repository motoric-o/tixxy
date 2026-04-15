@props(['type' => 'button', 'variant' => 'primary', 'href' => null])

@php
    $variants = [
        'primary' => 'bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-[length:200%_auto] hover:bg-[position:right_center] shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 text-white',
        'secondary' => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm',
        'danger' => 'bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30 text-rose-500 hover:bg-rose-500 hover:text-white',
        'action-purple' => 'p-2.5 text-purple-100 hover:text-white rounded-xl bg-gradient-to-br from-purple-600 to-indigo-700 border border-white/10 hover:shadow-lg hover:shadow-purple-500/30',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $baseClasses = 'inline-flex items-center justify-center gap-2.5 transition-all duration-500 hover:-translate-y-0.5 active:scale-95';
    
    // Default padding if not specialized
    $paddingClass = ($variant === 'action-purple') ? '' : 'px-7 py-3 text-sm font-bold rounded-xl';
    
    $classes = "$baseClasses $paddingClass $variantClass";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
