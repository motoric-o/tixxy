@props(['variant' => 'blue'])

@php
    $variants = [
        'green'  => 'bg-green-500/15 text-green-400 border-green-500/20',
        'blue'   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        'amber'  => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'red'    => 'bg-red-500/10 text-red-400 border-red-500/20',
        'purple' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300',
        'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'indigo' => 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-500/30 text-indigo-600 dark:text-indigo-400',
        'gray'   => 'bg-gray-100 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-500',
    ];
    $variantClass = $variants[$variant] ?? $variants['blue'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border whitespace-nowrap ' . $variantClass]) }}>
    {{ $slot }}
</span>
