@extends('layouts.admin.default')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-row items-center gap-5">
        <a href="{{ route('manage.events') }}"
                class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20"
                title="Back">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Event Comparison</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Comparing performance of completed events
            </p>
        </div>
    </div>
        
    <div class="flex items-center gap-3">
        <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 transition-all">
            Export Report
        </button>
    </div>
</div>

{{-- Chart Comparison Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4 uppercase tracking-wider">Revenue Comparison</h3>
        <div class="h-64">
            <canvas id="revenueCompareChart"></canvas>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4 uppercase tracking-wider">Attendance Rate (%)</h3>
        <div class="h-64">
            <canvas id="attendanceCompareChart"></canvas>
        </div>
    </div>
</div>

{{-- Detailed Comparison Table --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-700/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Event Details</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Revenue</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Tickets Sold</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Attendance</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Fill Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($events ?? [] as $event)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $event->category->name }} • {{ \Carbon\Carbon::parse($event->start_time)->format('M Y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-semibold text-emerald-500">Rp {{ number_format($event->orders_sum_amount ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($event->total_sold) }}</p>
                        <p class="text-[10px] text-gray-400">of {{ number_format($event->quota) }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($event->total_scanned) }}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                {{ $event->total_sold > 0 ? round(($event->total_scanned / $event->total_sold) * 100) : 0 }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php $fillPercent = $event->quota > 0 ? round(($event->total_sold / $event->quota) * 100) : 0; @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 transition-all duration-500" style="width: {{ $fillPercent }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1 font-medium">{{ $fillPercent }}% capacity</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-gray-400 text-sm italic">No completed events found for comparison.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = () => document.documentElement.classList.contains('dark');
    const labelColor = () => isDark() ? '#9ca3af' : '#6b7280';
    const gridColor = () => isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    const eventLabels = @json(collect($events ?? [])->pluck('title'));
    
    // Revenue Chart
    new Chart(document.getElementById('revenueCompareChart'), {
        type: 'bar',
        data: {
            labels: eventLabels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: @json(collect($events ?? [])->pluck('orders_sum_amount')),
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor() }, ticks: { color: labelColor() } },
                x: { grid: { display: false }, ticks: { color: labelColor() } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Attendance Rate Chart
    new Chart(document.getElementById('attendanceCompareChart'), {
        type: 'line',
        data: {
            labels: eventLabels,
            datasets: [{
                label: 'Attendance %',
                data: @json(collect($events ?? [])->map(fn($e) => $e->total_sold > 0 ? round(($e->total_scanned / $e->total_sold) * 100) : 0)),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { min: 0, max: 100, grid: { color: gridColor() }, ticks: { color: labelColor(), callback: v => v + '%' } },
                x: { grid: { display: false }, ticks: { color: labelColor() } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>

@endsection