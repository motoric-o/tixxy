@props(['header' => null, 'headerActions' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden']) }}>
    @if($header || $headerActions)
        <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex flex-row items-center justify-between gap-4">
            @if($header)
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">{{ $header }}</h3>
            @endif
            @if($headerActions)
                <div class="flex items-center gap-3">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    <div {{ $attributes->has('no-padding') ? '' : 'class=p-8' }}>
        {{ $slot }}
    </div>
</div>
