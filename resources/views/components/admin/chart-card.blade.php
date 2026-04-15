@props([
    'title',
    'subtitle' => null,
    'totalValue' => null,
    'totalLabel' => 'total',
    'canvasId',
    'color' => 'emerald' // emerald, amber, blue, indigo
])

@php
    $badgeColors = [
        'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'amber'   => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
        'blue'    => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
        'indigo'  => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
    ];
    $badgeColor = $badgeColors[$color] ?? $badgeColors['emerald'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden']) }}>
    {{-- Header bar matching x-admin.card style --}}
    <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs text-gray-400 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($totalValue)
            <span class="text-xs px-2.5 py-1 rounded-full {{ $badgeColor }} font-medium border whitespace-nowrap">
                {{ $totalValue }} {{ $totalLabel }}
            </span>
        @endif
    </div>
    {{-- Chart area --}}
    <div class="p-5">
        <div class="relative h-52">
            <canvas id="{{ $canvasId }}"></canvas>
            {{ $slot }}
        </div>
    </div>
</div>
