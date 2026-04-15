@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'emerald', // emerald, blue, amber, purple
    'subtext' => null,
    'iconPath' => null
])

@php
    $colorMap = [
        'emerald' => [
            'bg' => 'from-emerald-500/20 to-emerald-700/10',
            'border' => 'border-emerald-500/20 dark:border-emerald-500/10',
            'text' => 'text-emerald-400',
            'icon_bg' => 'bg-emerald-500/20',
            'circle' => 'bg-emerald-500/10'
        ],
        'blue' => [
            'bg' => 'from-blue-500/20 to-blue-700/10',
            'border' => 'border-blue-500/20 dark:border-blue-500/10',
            'text' => 'text-blue-400',
            'icon_bg' => 'bg-blue-500/20',
            'circle' => 'bg-blue-500/10'
        ],
        'amber' => [
            'bg' => 'from-amber-500/20 to-amber-700/10',
            'border' => 'border-amber-500/20 dark:border-amber-500/10',
            'text' => 'text-amber-400',
            'icon_bg' => 'bg-amber-500/20',
            'circle' => 'bg-amber-500/10'
        ],
        'purple' => [
            'bg' => 'from-purple-500/20 to-purple-700/10',
            'border' => 'border-purple-500/20 dark:border-purple-500/10',
            'text' => 'text-purple-400',
            'icon_bg' => 'bg-purple-500/20',
            'circle' => 'bg-purple-500/10'
        ],
    ];

    $c = $colorMap[$color] ?? $colorMap['emerald'];
@endphp

<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br {{ $c['bg'] }} border {{ $c['border'] }} p-5 shadow-sm dark:bg-gray-700/40">
    <div class="flex items-start justify-between mb-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest {{ $c['text'] }}">{{ $title }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                {{ $value }}
            </p>
        </div>
        <div class="p-2.5 rounded-xl {{ $c['icon_bg'] }} {{ $c['text'] }}">
            @if($iconPath)
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                </svg>
            @elseif($icon)
                {{ $icon }}
            @endif
        </div>
    </div>
    @if($subtext)
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {!! $subtext !!}
        </div>
    @elseif(isset($slot) && $slot->isNotEmpty())
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $slot }}
        </div>
    @endif
    <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full {{ $c['circle'] }}"></div>
</div>
