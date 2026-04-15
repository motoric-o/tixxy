@extends('layouts.admin.default')

@section('content')

    {{-- ═══════════════════════════════════════════════════════════════
    Page Header
    ═══════════════════════════════════════════════════════════════ --}}
    <x-admin.page-header title="Dashboard" subtitle="Overview as of {{ now()->format('l, d F Y') }}" />

    {{-- ═══════════════════════════════════════════════════════════════
    Financial Stat Cards
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card 
            title="Total Revenue" 
            value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" 
            color="emerald"
            iconPath="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
            From <span class="text-emerald-400 font-semibold">{{ number_format($totalOrdersCompleted) }}</span> completed orders
        </x-admin.stat-card>

        <x-admin.stat-card 
            title="Revenue This Month" 
            value="Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}" 
            color="blue"
            iconPath="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
            {{ now()->format('F Y') }}
        </x-admin.stat-card>

        <x-admin.stat-card 
            title="Pending Orders" 
            value="Rp {{ number_format($pendingOrdersValue, 0, ',', '.') }}" 
            color="amber"
            iconPath="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
            <span class="text-amber-400 font-semibold">{{ number_format($totalOrdersPending) }}</span> orders awaiting payment
        </x-admin.stat-card>

        <x-admin.stat-card 
            title="Avg. Order Value" 
            value="Rp {{ number_format($avgOrderValue, 0, ',', '.') }}" 
            color="purple"
            iconPath="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            <span class="text-purple-400 font-semibold">{{ number_format($totalTicketsScanned) }}</span> / {{ number_format($totalTicketsSold) }} tickets scanned
        </x-admin.stat-card>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
    Financial Charts
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <x-admin.chart-card 
            title="Monthly Revenue" 
            subtitle="Last 6 months — completed orders" 
            totalValue="Rp {{ number_format($sixMonths->sum('total'), 0, ',', '.') }}" 
            canvasId="revenueChart" 
            color="emerald" />

        <x-admin.chart-card 
            title="Monthly Spending" 
            subtitle="Last 6 months — all orders" 
            totalValue="Rp {{ number_format($sixMonthsSpending->sum('total'), 0, ',', '.') }}" 
            canvasId="spendingChart" 
            color="amber" />
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
    Events Overview
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

        {{-- ── Ongoing Events (wider, 3/5) ────────────────────────── --}}
        <div class="lg:col-span-3 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
                </span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ongoing Events</h3>
                <span class="ml-auto text-xs text-gray-400">{{ $ongoingEvents->count() }} live</span>
            </div>

            @forelse ($ongoingEvents as $event)
                <x-admin.event-card-ongoing :event="$event" />
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 p-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm text-gray-400">No events are currently ongoing</p>
                </div>
            @endforelse
        </div>

        {{-- ── Upcoming Events ────────────────────── --}}
        <div class="lg:col-span-2 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Upcoming Events</h3>
                <span class="ml-auto text-xs text-gray-400">{{ $upcomingEvents->count() }} scheduled</span>
            </div>

            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 overflow-hidden shadow-sm divide-y divide-gray-100 dark:divide-gray-600/50">
                @forelse ($upcomingEvents as $event)
                    <x-admin.event-item-upcoming :event="$event" />
                @empty
                    <div class="p-8 text-center">
                        <p class="text-sm text-gray-400">No upcoming events</p>
                    </div>
                @endforelse
            </div>

            {{-- Quick stats footer --}}
            <div class="grid grid-cols-2 gap-3">
                <div
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-3 text-center shadow-sm">
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($totalTicketsSold) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Tickets Sold</p>
                </div>
                <div
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-3 text-center shadow-sm">
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($totalTicketsScanned) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Checked In</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
    Chart.js Scripts
    ═══════════════════════════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const T = window.ChartThemes;

            // ── Revenue Bar Chart ────────────────────────────────────────────
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenues = @json($sixMonths->pluck('total'));
            const months = @json($sixMonths->pluck('month'));

            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: revenues,
                        backgroundColor: T.getGradient(revenueCtx, 'emerald'),
                        borderColor: 'rgba(16, 185, 129, 0.8)',
                        borderWidth: 1,
                        borderRadius: 12,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            ...T.getTooltipStyle(),
                            callbacks: {
                                label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: T.getGridColor(), display: false },
                            ticks: { color: T.getLabelColor(), font: { size: 10 } },
                        },
                        y: {
                            grid: { color: T.getGridColor() },
                            ticks: {
                                color: T.getLabelColor(),
                                font: { size: 10 },
                                callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                            },
                            beginAtZero: true,
                        }
                    }
                }
            });

            // ── Spending Bar Chart ───────────────────────────────────────────
            const spendingCtx = document.getElementById('spendingChart').getContext('2d');
            const spendings = @json($sixMonthsSpending->pluck('total'));
            const spendingMonths = @json($sixMonthsSpending->pluck('month'));

            new Chart(spendingCtx, {
                type: 'bar',
                data: {
                    labels: spendingMonths,
                    datasets: [{
                        label: 'Spending (Rp)',
                        data: spendings,
                        backgroundColor: T.getGradient(spendingCtx, 'amber'),
                        borderColor: 'rgba(245,158,11,0.8)',
                        borderWidth: 1,
                        borderRadius: 12,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            ...T.getTooltipStyle(),
                            callbacks: {
                                label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: T.getGridColor(), display: false },
                            ticks: { color: T.getLabelColor(), font: { size: 10 } },
                        },
                        y: {
                            grid: { color: T.getGridColor() },
                            ticks: {
                                color: T.getLabelColor(),
                                font: { size: 10 },
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
                                'rgba(16, 185, 129, 0.85)',
                                'rgba(245, 158, 11, 0.85)',
                                'rgba(248, 113, 113, 0.85)',
                            ],
                            borderColor: T.isDark() ? '#374151' : '#ffffff',
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
                            tooltip: T.getTooltipStyle()
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