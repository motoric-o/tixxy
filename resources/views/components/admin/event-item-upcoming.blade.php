@props(['event'])

<a href="/manage/events/{{ $event->id }}/edit"
    class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-600/20 transition-colors duration-200 border-b border-gray-100 dark:border-gray-600/50 last:border-0 grow">
    <div class="flex items-start justify-between gap-2">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5 mb-0.5">
                @if ($event->status === 'pending')
                    <span
                        class="text-xs px-1.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium whitespace-nowrap">Pending</span>
                @else
                    <span
                        class="text-xs px-1.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-medium whitespace-nowrap">Prep</span>
                @endif
                <span class="text-xs text-gray-400 truncate">{{ $event->type }}</span>
            </div>
            <p class="text-sm font-semibold text-gray-800 dark:text-white leading-tight truncate">
                {{ $event->title }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $event->start_time->format('d M Y, H:i') }}
            </p>
        </div>
        <div class="text-right shrink-0">
            <p class="text-xs font-bold text-blue-400">
                in {{ now()->diffForHumans($event->start_time, true) }}
            </p>
            @if ($event->queues_waiting_count > 0)
                <div class="mt-1 flex items-center justify-end gap-1 text-xs text-amber-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $event->queues_waiting_count }} queued
                </div>
            @endif
        </div>
    </div>
</a>
