@props(['title', 'subtitle' => null])

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ $subtitle }}
        </p>
    @elseif(isset($slot) && $slot->isNotEmpty())
        <div class="mt-1">
            {{ $slot }}
        </div>
    @endif
</div>
