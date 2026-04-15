@props(['event'])

@php
    $totalDuration = $event->end_time->diffInMinutes($event->start_time);
    $elapsed = now()->diffInMinutes($event->start_time);
    $timePercent = $totalDuration > 0 ? min(100, round(($elapsed / $totalDuration) * 100)) : 100;

    $totalQueues = $event->queues_count ?: 1;
    $waitPct = min(100, round(($event->queues_waiting_count / $totalQueues) * 100));
    $donePct = min(100, round(($event->queues_completed_count / $totalQueues) * 100));
@endphp

<a href="/manage/events/{{ $event->id }}/edit"
    class="block rounded-2xl border border-green-500/20 bg-gradient-to-br from-green-500/5 to-transparent dark:bg-gray-700/40 p-5 shadow-sm hover:shadow-[0_0_18px_rgba(74,222,128,0.2)] transition-all duration-300 group/card">

    {{-- Header row --}}
    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span
                    class="text-xs px-2 py-0.5 rounded-full bg-green-500/15 text-green-400 font-medium border border-green-500/20 whitespace-nowrap">
                    LIVE
                </span>
                <span class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $event->type }}</span>
            </div>
            <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight truncate">
                {{ $event->title }}
            </h4>
        </div>
        <div class="text-right shrink-0">
            <p class="text-xs text-gray-400">Ends</p>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                {{ $event->end_time->format('d M, H:i') }}
            </p>
        </div>
    </div>

    {{-- Time progress bar --}}
    <div class="mb-4">
        <div class="flex justify-between text-xs text-gray-400 mb-1.5">
            <span>Started {{ $event->start_time->format('H:i') }}</span>
            <span class="text-green-400 font-semibold">{{ $timePercent }}% elapsed</span>
        </div>
        <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full transition-all duration-700"
                style="width: {{ $timePercent }}%"></div>
        </div>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-3 gap-3 mb-4">
        {{-- Capacity --}}
        <div class="rounded-xl bg-gray-100 dark:bg-gray-600/40 p-3">
            <p class="text-xs text-gray-400 mb-1">Capacity</p>
            <p class="text-sm font-bold text-gray-800 dark:text-white">
                {{ number_format($event->total_sold) }}
                <span class="font-normal text-gray-400">/ {{ number_format($event->total_capacity) }}</span>
            </p>
            <div class="mt-1.5 h-1 w-full bg-gray-300 dark:bg-gray-500 rounded-full overflow-hidden">
                <div class="h-full bg-blue-400 rounded-full" style="width: {{ $event->fill_percent }}%"></div>
            </div>
            <p class="text-xs text-blue-400 mt-1">{{ $event->fill_percent }}% sold</p>
        </div>

        {{-- Queue --}}
        <div class="rounded-xl bg-gray-100 dark:bg-gray-600/40 p-3">
            <p class="text-xs text-gray-400 mb-1">Queue</p>
            <p class="text-xl font-bold text-amber-400">{{ number_format($event->queues_waiting_count) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">waiting</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ number_format($event->queues_completed_count) }} served
            </p>
        </div>

        {{-- Quota --}}
        <div class="rounded-xl bg-gray-100 dark:bg-gray-600/40 p-3">
            <p class="text-xs text-gray-400 mb-1">Total Quota</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($event->quota) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">max attendees</p>
        </div>
    </div>

    {{-- Queue status bar --}}
    <div class="mb-4">
        <p class="text-xs text-gray-400 mb-1.5">Queue Status Distribution</p>
        <div class="flex h-2 rounded-full overflow-hidden gap-0.5">
            <div class="bg-amber-400 transition-all duration-700" style="width: {{ $waitPct }}%"
                title="Waiting"></div>
            <div class="bg-emerald-400 transition-all duration-700" style="width: {{ $donePct }}%"
                title="Served"></div>
            <div class="flex-1 bg-gray-200 dark:bg-gray-600"></div>
        </div>
        <div class="flex gap-4 mt-1.5 text-xs text-gray-400">
            <span><span class="text-amber-400">●</span> Waiting</span>
            <span><span class="text-emerald-400">●</span> Served</span>
            <span><span class="text-gray-400">●</span> Canceled</span>
        </div>
    </div>

    {{-- Orders by Status donut (per-event) --}}
    <div class="border-t border-gray-200 dark:border-gray-600/50 pt-4">
        <p class="text-xs text-gray-400 mb-3">Orders by Status</p>
        <div class="flex items-center gap-4">
            <div class="relative w-28 h-28 shrink-0">
                <canvas id="eventDonut{{ $event->id }}"></canvas>
            </div>
            <div class="flex flex-col gap-2 text-xs flex-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                        <span class="text-gray-500 dark:text-gray-400">Completed</span>
                    </div>
                    <span
                        class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_completed }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                        <span class="text-gray-500 dark:text-gray-400">Pending</span>
                    </div>
                    <span
                        class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_pending }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                        <span class="text-gray-500 dark:text-gray-400">Canceled</span>
                    </div>
                    <span
                        class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_canceled }}</span>
                </div>
            </div>
        </div>
    </div>
</a>
