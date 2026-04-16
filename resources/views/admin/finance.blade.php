@extends('layouts.admin.default')

@section('content')
<style>
    .growth-text-up { color: #059669 !important; }
    .growth-text-down { color: #dc2626 !important; }
    .dark .growth-text-up { color: #34d399 !important; }
    .dark .growth-text-down { color: #f87171 !important; }
    .hover-glow-blue:hover { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) !important; border-color: rgba(59, 130, 246, 0.5) !important; }
    .hover-glow-green:hover { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3) !important; border-color: rgba(34, 197, 94, 0.5) !important; }
</style>

{{-- ═══════════════════════════════════════════════════════════════
     Page Header
═══════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Financial Overview</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Detailed financial analytics — {{ now()->format('l, d F Y') }}
        </p>
    </div>
    <div class="flex gap-3">
        <a href="/manage/finances/export/csv" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-purple-600 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export CSV
        </a>
        <a href="/manage/finances/export/pdf" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-purple-600 border border-transparent rounded-xl hover:bg-purple-700 dark:hover:bg-purple-500 transition-colors shadow-lg shadow-purple-500/20 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export PDF
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     ROW 1 – Summary Cards
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Total Revenue --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-700/10 border border-emerald-500/20 dark:border-emerald-500/10 p-5 shadow-sm dark:bg-gray-800">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400">Total Revenue</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            From <span class="text-emerald-400 font-semibold">{{ number_format($totalOrdersCompleted) }}</span> completed orders
        </p>
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-emerald-500/10"></div>
    </div>

    {{-- Revenue This Month + Growth --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-700/10 border border-blue-500/20 dark:border-blue-500/10 p-5 shadow-sm dark:bg-gray-800">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">This Month</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-2.5 rounded-xl bg-blue-500/20 text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            @if($growthPercent >= 0)
                <svg class="w-4 h-4 growth-text-up" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                <span class="text-xs font-semibold growth-text-up">+{{ $growthPercent }}%</span>
            @else
                <svg class="w-4 h-4 growth-text-down" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                <span class="text-xs font-semibold growth-text-down">{{ $growthPercent }}%</span>
            @endif
            <span class="text-xs text-gray-400">vs last month</span>
        </div>
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-blue-500/10"></div>
    </div>

    {{-- Pending Orders Value --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-700/10 border border-amber-500/20 dark:border-amber-500/10 p-5 shadow-sm dark:bg-gray-800">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">Pending Orders</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($pendingOrdersValue, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-2.5 rounded-xl bg-amber-500/20 text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            <span class="text-amber-400 font-semibold">{{ number_format($totalOrdersPending) }}</span> orders awaiting payment
        </p>
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-amber-500/10"></div>
    </div>

    {{-- Average Order Value --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/20 to-purple-700/10 border border-purple-500/20 dark:border-purple-500/10 p-5 shadow-sm dark:bg-gray-800">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-purple-400">Avg. Order Value</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
                </p>
            </div>
            <div class="p-2.5 rounded-xl bg-purple-500/20 text-purple-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            <span class="text-purple-400 font-semibold">{{ number_format($totalTicketsScanned) }}</span>
            / {{ number_format($totalTicketsSold) }} tickets scanned
        </p>
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-purple-500/10"></div>
    </div>

</div>{{-- end row 1 --}}

{{-- ═══════════════════════════════════════════════════════════════
     ROW 2 – Charts: 30-Day Trend + Revenue by Category
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- 30-Day Sales Trend (Line Chart) --}}
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">Sales Trend</h3>
                <p class="text-xs text-gray-400 mt-0.5">Revenue performance over time</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span id="trendRangeBadge" class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-medium border border-emerald-500/20 whitespace-nowrap">
                    Rp {{ number_format($monthlySalesTrend->sum('total'), 0, ',', '.') }} (30d)
                </span>
                <div class="relative">
                    <select id="trendRangeSelector" class="appearance-none text-xs bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg pl-2 pr-8 py-1.5 focus:ring-emerald-500 focus:border-emerald-500 transition-colors cursor-pointer">
                        <option value="weekly">Last 7 Days</option>
                        <option value="monthly" selected>Last 30 Days</option>
                        <option value="six_months">Last 6 Months</option>
                        <option value="yearly">Last Year</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="relative h-[400px]" style="height: 400px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Revenue by Category (Doughnut Chart) --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">Revenue by Category</h3>
            <p class="text-xs text-gray-400 mt-0.5">Income distribution across categories</p>
        </div>
        <div class="p-5">
            <div class="relative h-48 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                @foreach ($revenueByCategory as $cat)
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ $cat['name'] }}: Rp {{ number_format($cat['revenue'], 0, ',', '.') }}
                </span>
                @endforeach
            </div>
        </div>
    </div>

</div>{{-- end row 2 --}}

{{-- ═══════════════════════════════════════════════════════════════
     ROW 3 – Ticket Type Breakdown + Top Events
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Ticket Type Breakdown --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">Ticket Type Analysis</h3>
                <p class="text-xs text-gray-400 mt-0.5">Revenue and volume by ticket tier</p>
            </div>
        </div>
        <div class="p-5">
        @if($ticketTypeBreakdown->count() > 0)
        <div class="relative h-52 mb-4">
            <canvas id="ticketTypeChart"></canvas>
        </div>
        <div class="space-y-2">
            @foreach ($ticketTypeBreakdown as $tt)
            <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 inline-block"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $tt->name }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ number_format($tt->total_sold) }} sold</span>
                    <span class="font-semibold text-emerald-400">Rp {{ number_format($tt->total_revenue, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center justify-center h-48 text-sm text-gray-400">No ticket type data available</div>
        @endif
        </div>
    </div>

    {{-- Top Events (Profitability Leaderboard) --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-[0.1em]">Profitability Leaderboard</h3>
                <p class="text-xs text-gray-400 mt-0.5">Top 10 events by revenue</p>
            </div>
        </div>
        <div class="p-5">
        @if($topEvents->count() > 0)
        <div class="space-y-2 max-h-[420px] overflow-y-auto pr-1">
            @foreach ($topEvents as $index => $event)
            <div class="cursor-pointer flex items-center gap-3 py-3 px-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors"
                 onclick="window.location='{{ route('manage.events.edit', $event['id']) }}'">
                {{-- Rank --}}
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                    {{ $index === 0 ? 'bg-gradient-to-br from-yellow-400 to-amber-500 text-white' :
                       ($index === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white' :
                       ($index === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700 text-white' :
                       'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-300')) }}
                    text-xs font-bold">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ $event['title'] }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        @php
                            $statusColors = [
                                'preparation' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'ongoing'     => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'completed'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'canceled'    => 'bg-red-500/10 text-red-400 border-red-500/20',
                                'pending'     => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                            ];
                        @endphp
                        <span class="text-xs px-1.5 py-0.5 rounded-full border {{ $statusColors[$event['status']] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }} font-medium capitalize">
                            {{ $event['status'] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $event['completed_orders'] }} orders</span>
                    </div>
                </div>
                <p class="text-sm font-bold text-emerald-400 shrink-0">Rp {{ number_format($event['total_revenue'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center justify-center h-48 text-sm text-gray-400">No profitable events yet</div>
        @endif
        </div>
    </div>

</div>{{-- end row 3 --}}

{{-- ═══════════════════════════════════════════════════════════════
     ROW 4 – Pipeline Event Performance (Preparation + Ongoing)
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Ongoing Events --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-2">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
            </span>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ongoing Events</h3>
            <span class="ml-auto text-xs text-gray-400">{{ $ongoingEvents->count() }} live</span>
        </div>

        @forelse ($ongoingEvents as $event)
        <a href="/manage/events/{{ $event->id }}/edit" class="block rounded-2xl border border-green-500/20 bg-gradient-to-br from-green-500/5 to-transparent dark:from-green-500/10 dark:to-transparent dark:bg-gray-800 p-5 shadow-sm hover-glow-green transition-all duration-300 group/card">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-500/15 text-green-400 font-medium border border-green-500/20 whitespace-nowrap">LIVE</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $event->category->name ?? '' }}</span>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight truncate">{{ $event->title }}</h4>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs text-gray-400">Ends</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                        {{ $event->end_time ? $event->end_time->format('d M, H:i') : 'TBD' }}
                    </p>
                </div>
            </div>

            {{-- Time progress bar --}}
            @php
                $totalDuration = $event->end_time && $event->start_time ? $event->end_time->diffInMinutes($event->start_time) : 0;
                $elapsed       = $event->start_time ? now()->diffInMinutes($event->start_time) : 0;
                $timePercent   = $totalDuration > 0 ? min(100, round(($elapsed / $totalDuration) * 100)) : 100;
            @endphp
            <div class="mb-4">
                <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                    <span>Started {{ $event->start_time ? $event->start_time->format('H:i') : '' }}</span>
                    <span class="text-green-400 font-semibold">{{ $timePercent }}% elapsed</span>
                </div>
                <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full transition-all duration-700"
                         style="width: {{ $timePercent }}%"></div>
                </div>
            </div>

            {{-- Capacity & Revenue --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700 p-3">
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
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700 p-3">
                    <p class="text-xs text-gray-400 mb-1">Revenue</p>
                    <p class="text-sm font-bold text-emerald-400">Rp {{ number_format($event->revenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700 p-3">
                    <p class="text-xs text-gray-400 mb-1">Orders</p>
                    <div class="flex flex-col gap-0.5 text-xs mt-1 dark:text-white">
                        <span><span class="text-emerald-400 font-semibold">{{ $event->orders_completed_count ?? 0 }}</span> done</span>
                        <span><span class="text-amber-400 font-semibold">{{ $event->orders_pending_count ?? 0 }}</span> pending</span>
                        <span><span class="text-red-400 font-semibold">{{ $event->orders_canceled_count ?? 0 }}</span> canceled</span>
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

    {{-- Preparation Events --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-400 inline-block"></span>
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Preparation Events</h3>
            <span class="ml-auto text-xs text-gray-400">{{ $preparationEvents->count() }} events</span>
        </div>

        @forelse ($preparationEvents as $event)
        <a href="/manage/events/{{ $event->id }}/edit" class="block rounded-2xl border border-blue-500/20 bg-gradient-to-br from-blue-500/5 to-transparent dark:from-blue-500/10 dark:to-transparent dark:bg-gray-800 p-5 shadow-sm hover-glow-blue transition-all duration-300 group/card">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-400 font-medium border border-blue-500/20 whitespace-nowrap">PREP</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $event->category->name ?? '' }}</span>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight truncate">{{ $event->title }}</h4>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs text-gray-400">Starts</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                        {{ $event->start_time ? $event->start_time->format('d M, H:i') : 'TBD' }}
                    </p>
                </div>
            </div>

            {{-- Capacity & Revenue --}}
            <div class="grid grid-cols-3 gap-3 mb-3">
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700/50 p-3">
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
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700 p-3">
                    <p class="text-xs text-gray-400 mb-1">Revenue</p>
                    <p class="text-sm font-bold text-emerald-400">Rp {{ number_format($event->revenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-gray-100 dark:bg-gray-700 p-3">
                    <p class="text-xs text-gray-400 mb-1">Orders</p>
                    <div class="flex flex-col gap-0.5 text-xs mt-1 dark:text-white">
                        <span><span class="text-emerald-400 font-semibold">{{ $event->orders_completed_count ?? 0 }}</span> done</span>
                        <span><span class="text-amber-400 font-semibold">{{ $event->orders_pending_count ?? 0 }}</span> pending</span>
                        <span><span class="text-red-400 font-semibold">{{ $event->orders_canceled_count ?? 0 }}</span> canceled</span>
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
            <p class="text-sm text-gray-400">No events in preparation</p>
        </div>
        @endforelse
    </div>

</div>{{-- end row 4 --}}


{{-- ═══════════════════════════════════════════════════════════════
     Chart.js Scripts
═══════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const T = window.ChartThemes;

    // ── Sales Trend (Line Chart) ───────────────────────────
    const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
    
    // Store all trend data sets
    const trendDatasets = {
        weekly:     { data: @json($weeklySalesTrend->pluck('total')),    labels: @json($weeklySalesTrend->pluck('date')),    sumLabel: '7d' },
        monthly:    { data: @json($monthlySalesTrend->pluck('total')),   labels: @json($monthlySalesTrend->pluck('date')),   sumLabel: '30d' },
        six_months: { data: @json($sixMonthsSalesTrend->pluck('total')), labels: @json($sixMonthsSalesTrend->pluck('date')), sumLabel: '6m' },
        yearly:     { data: @json($yearlySalesTrend->pluck('total')),    labels: @json($yearlySalesTrend->pluck('date')),    sumLabel: '1y' }
    };

    const salesTrendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendDatasets.monthly.labels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: trendDatasets.monthly.data,
                borderColor: 'rgba(52, 211, 153, 0.9)',
                backgroundColor: T.getGradient(trendCtx, 'emerald', 400),
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(52, 211, 153, 1)',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
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
                    },
                }
            },
            scales: {
                x: {
                    grid: { color: T.getGridColor(), display: false },
                    ticks: {
                        color: T.getLabelColor(),
                        font: { size: 10 },
                        maxTicksLimit: 12,
                    },
                },
                y: {
                    grid: { color: T.getGridColor() },
                    beginAtZero: true,
                    ticks: {
                        color: T.getLabelColor(),
                        font: { size: 10 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    },
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // Handle range selection
    document.getElementById('trendRangeSelector').addEventListener('change', function(e) {
        const range = e.target.value;
        const trend  = trendDatasets[range];
        
        // Update Chart
        salesTrendChart.data.labels = trend.labels;
        salesTrendChart.data.datasets[0].data = trend.data;
        salesTrendChart.update();

        // Update Badge
        const sum = trend.data.reduce((a, b) => a + b, 0);
        document.getElementById('trendRangeBadge').innerHTML = `Rp ${new Intl.NumberFormat('id-ID').format(sum)} (${trend.sumLabel})`;
    });

    // ── Revenue by Category (Doughnut Chart) ─────────────────────
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    const catLabels = @json($revenueByCategory->pluck('name'));
    const catData   = @json($revenueByCategory->pluck('revenue'));

    const catColors = [
        'rgba(139, 92, 246, 0.85)',
        'rgba(59, 130, 246, 0.85)',
        'rgba(245, 158, 11, 0.85)',
        'rgba(16, 185, 129, 0.85)',
        'rgba(248, 113, 113, 0.85)',
        'rgba(236, 72, 153, 0.85)',
        'rgba(34, 211, 238, 0.85)',
        'rgba(163, 230, 53, 0.85)',
    ];

    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: catColors.slice(0, catLabels.length),
                borderColor: T.isDark() ? '#374151' : '#ffffff',
                borderWidth: 2,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: T.getLabelColor(),
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 10 },
                    }
                },
                tooltip: {
                    ...T.getTooltipStyle(),
                    callbacks: {
                        label: ctx => ctx.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                    },
                }
            }
        }
    });

    // ── Ticket Type Analysis (Horizontal Bar Chart) ──────────────
    @if($ticketTypeBreakdown->count() > 0)
    const ttCtx = document.getElementById('ticketTypeChart').getContext('2d');
    const ttLabels  = @json($ticketTypeBreakdown->pluck('name'));
    const ttRevenue = @json($ticketTypeBreakdown->pluck('total_revenue'));

    new Chart(ttCtx, {
        type: 'bar',
        data: {
            labels: ttLabels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: ttRevenue,
                backgroundColor: T.getGradient(ttCtx, 'indigo', 400),
                borderColor: 'rgba(99, 102, 241, 0.8)',
                borderWidth: 1,
                borderRadius: 12,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...T.getTooltipStyle(),
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                    },
                }
            },
            scales: {
                x: {
                    grid: { color: T.getGridColor() },
                    ticks: {
                        color: T.getLabelColor(),
                        font: { size: 10 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v),
                    },
                    beginAtZero: true,
                },
                y: {
                    grid: { display: false },
                    ticks: { color: T.getLabelColor(), font: { size: 11 } },
                }
            }
        }
    });
    @endif

})();
</script>

@endsection
