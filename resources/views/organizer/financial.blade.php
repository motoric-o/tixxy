@extends('layouts.admin.default')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     Page Header
═══════════════════════════════════════════════════════════════ --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Financial Overview</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Detailed financial performance and revenue metrics
    </p>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     ROW 1 – Financial Stat Cards
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Total Revenue --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-700/10 border border-emerald-500/20 dark:border-emerald-500/10 p-5 shadow-sm dark:bg-gray-700/40 group hover:shadow-[0_0_20px_rgba(52,211,153,0.2)] transition-all duration-300">
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
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-all duration-500"></div>
    </div>

    {{-- Revenue This Month --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-700/10 border border-blue-500/20 dark:border-blue-500/10 p-5 shadow-sm dark:bg-gray-700/40 group hover:shadow-[0_0_20px_rgba(59,130,246,0.2)] transition-all duration-300">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">Revenue This Month</p>
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
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ now()->format('F Y') }}
        </p>
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-blue-500/10 group-hover:bg-blue-500/20 transition-all duration-500"></div>
    </div>

    {{-- Pending Orders Value --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-700/10 border border-amber-500/20 dark:border-amber-500/10 p-5 shadow-sm dark:bg-gray-700/40 group hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-all duration-300">
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
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-amber-500/10 group-hover:bg-amber-500/20 transition-all duration-500"></div>
    </div>

    {{-- Average Order Value --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/20 to-purple-700/10 border border-purple-500/20 dark:border-purple-500/10 p-5 shadow-sm dark:bg-gray-700/40 group hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] transition-all duration-300">
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
        <div class="absolute -bottom-3 -right-3 w-20 h-20 rounded-full bg-purple-500/10 group-hover:bg-purple-500/20 transition-all duration-500"></div>
    </div>

</div>{{-- end row 1 --}}

{{-- ═══════════════════════════════════════════════════════════════
     ROW 2 – Financial Charts
═══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Bar Chart: Monthly Revenue (spans 2/3) --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Monthly Revenue</h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 6 months — completed orders</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-medium border border-emerald-500/20">
                Rp {{ number_format($sixMonths->sum('total'), 0, ',', '.') }} total
            </span>
        </div>
        <div class="relative h-52">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Bar Chart: Monthly Spending (spans 1/3) --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/40 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Monthly Spending</h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 6 months — all orders</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 font-medium border border-amber-500/20">
                Rp {{ number_format($sixMonthsSpending->sum('total'), 0, ',', '.') }} total
            </span>
        </div>
        <div class="relative h-52">
            <canvas id="spendingChart"></canvas>
        </div>
    </div>

</div>{{-- end row 2 --}}


        // Removed per-event donut scripts as the HTML was removed


})();
</script>

@endsection