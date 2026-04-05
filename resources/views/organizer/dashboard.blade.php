@extends('layouts.admin.default')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     Page Header
═══════════════════════════════════════════════════════════════ --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Overview as of {{ now()->format('l, d F Y') }}
    </p>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     ROW 3 – Events Overview
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

    {{-- ── Ongoing Events (wider, 3/5) ────────────────────────── --}}
    <div class="lg:col-span-3 flex flex-col gap-4">
        <div class="flex items-center gap-2">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
            </span>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ongoing Events</h3>
            <span class="ml-auto text-xs text-gray-400">{{ $ongoingEvents->count() }} live</span>
        </div>

        @forelse ($ongoingEvents as $event)
        <a href="/manage/events/{{ $event->id }}/edit" class="block rounded-2xl border border-green-500/20 bg-gradient-to-br from-green-500/5 to-transparent dark:bg-gray-700/40 p-5 shadow-sm hover:shadow-[0_0_18px_rgba(74,222,128,0.2)] transition-all duration-300 group/card">

            {{-- Header row --}}
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-500/15 text-green-400 font-medium border border-green-500/20 whitespace-nowrap">
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
            @php
                $totalDuration = $event->end_time->diffInMinutes($event->start_time);
                $elapsed       = now()->diffInMinutes($event->start_time);
                $timePercent   = $totalDuration > 0 ? min(100, round(($elapsed / $totalDuration) * 100)) : 100;
            @endphp
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
            @php
                $totalQueues = $event->queues_count ?: 1;
                $waitPct     = min(100, round(($event->queues_waiting_count / $totalQueues) * 100));
                $donePct     = min(100, round(($event->queues_completed_count / $totalQueues) * 100));
            @endphp
            <div class="mb-4">
                <p class="text-xs text-gray-400 mb-1.5">Queue Status Distribution</p>
                <div class="flex h-2 rounded-full overflow-hidden gap-0.5">
                    <div class="bg-amber-400 transition-all duration-700" style="width: {{ $waitPct }}%" title="Waiting"></div>
                    <div class="bg-emerald-400 transition-all duration-700" style="width: {{ $donePct }}%" title="Served"></div>
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
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_completed }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                                <span class="text-gray-500 dark:text-gray-400">Pending</span>
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_pending }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                                <span class="text-gray-500 dark:text-gray-400">Canceled</span>
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $event->orders_canceled }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </a>
        @empty
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 p-8 text-center">
            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm text-gray-400">No events are currently ongoing</p>
        </div>
        @endforelse
    </div>

    {{-- ── Upcoming Events (narrower, 2/5) ────────────────────── --}}
    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Upcoming Events</h3>
            <span class="ml-auto text-xs text-gray-400">{{ $upcomingEvents->count() }} scheduled</span>
        </div>

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 overflow-hidden shadow-sm divide-y divide-gray-100 dark:divide-gray-600/50">
            @forelse ($upcomingEvents as $event)
            <a href="/manage/events/{{ $event->id }}/edit" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-600/20 transition-colors duration-200 border-b border-gray-100 dark:border-gray-600/50 last:border-0 grow">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            @if ($event->status === 'pending')
                                <span class="text-xs px-1.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium">Pending</span>
                            @else
                                <span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-medium">Prep</span>
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
            @empty
            <div class="p-8 text-center">
                <p class="text-sm text-gray-400">No upcoming events</p>
            </div>
            @endforelse
        </div>

        {{-- Quick stats footer --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-3 text-center shadow-sm">
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($totalTicketsSold) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Tickets Sold</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-3 text-center shadow-sm">
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($totalTicketsScanned) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Checked In</p>
            </div>
        </div>

    </div>
</div>{{-- end row 3 --}}

{{-- ═══════════════════════════════════════════════════════════════
     Chart.js Scripts
═══════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const isDark = () => document.documentElement.classList.contains('dark');
    const gridColor  = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
    const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';

    // ── Revenue Bar Chart ────────────────────────────────────────────
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    const revenues = @json($sixMonths->pluck('total'));
    const months   = @json($sixMonths->pluck('month'));

    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 200);
    revenueGradient.addColorStop(0,   'rgba(52, 211, 153, 0.9)');
    revenueGradient.addColorStop(1,   'rgba(16, 185, 129, 0.4)');

    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (Rp)',
                data: revenues,
                backgroundColor: revenueGradient,
                borderColor: 'rgba(52,211,153,0.8)',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                    },
                    backgroundColor: isDark() ? '#1f2937' : '#ffffff',
                    titleColor: isDark() ? '#e5e7eb' : '#111827',
                    bodyColor:  isDark() ? '#9ca3af' : '#6b7280',
                    borderColor: isDark() ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor() },
                    ticks: { color: labelColor(), font: { size: 11 } },
                },
                y: {
                    grid: { color: gridColor() },
                    ticks: {
                        color: labelColor(),
                        font: { size: 11 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    },
                    beginAtZero: true,
                }
            }
        }
    });

    // ── Spending Bar Chart ───────────────────────────────────────────
    const spendingCtx = document.getElementById('spendingChart').getContext('2d');

    const spendings      = @json($sixMonthsSpending->pluck('total'));
    const spendingMonths = @json($sixMonthsSpending->pluck('month'));

    const spendingGradient = spendingCtx.createLinearGradient(0, 0, 0, 200);
    spendingGradient.addColorStop(0, 'rgba(245, 158, 11, 0.9)');
    spendingGradient.addColorStop(1, 'rgba(217, 119, 6, 0.4)');

    new Chart(spendingCtx, {
        type: 'bar',
        data: {
            labels: spendingMonths,
            datasets: [{
                label: 'Spending (Rp)',
                data: spendings,
                backgroundColor: spendingGradient,
                borderColor: 'rgba(245,158,11,0.8)',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                    },
                    backgroundColor: isDark() ? '#1f2937' : '#ffffff',
                    titleColor: isDark() ? '#e5e7eb' : '#111827',
                    bodyColor:  isDark() ? '#9ca3af' : '#6b7280',
                    borderColor: isDark() ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor() },
                    ticks: { color: labelColor(), font: { size: 11 } },
                },
                y: {
                    grid: { color: gridColor() },
                    ticks: {
                        color: labelColor(),
                        font: { size: 11 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    },
                    beginAtZero: true,
                }
            }
        }
    });

    // ── Per-Event Order Status Donuts ────────────────────────────────
    function makeEventDonut(canvasId, completed, pending, canceled) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Canceled'],
                datasets: [{
                    data: [completed, pending, canceled],
                    backgroundColor: [
                        'rgba(52, 211, 153, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(248, 113, 113, 0.85)',
                    ],
                    borderColor: isDark() ? '#374151' : '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark() ? '#1f2937' : '#ffffff',
                        titleColor: isDark() ? '#e5e7eb' : '#111827',
                        bodyColor:  isDark() ? '#9ca3af' : '#6b7280',
                        borderColor: isDark() ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    @foreach ($ongoingEvents as $event)
    makeEventDonut(
        'eventDonut{{ $event->id }}',
        {{ $event->orders_completed }},
        {{ $event->orders_pending }},
        {{ $event->orders_canceled }}
    );
    @endforeach

})();
</script>

@endsection