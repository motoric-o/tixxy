<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Performance</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; color: #111; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .section-title { font-size: 14px; color: #111; border-bottom: 2px solid #6366f1; padding-bottom: 3px; margin-top: 30px; margin-bottom: 10px; }
        .stats-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .stats-grid td { width: 25%; padding: 10px; border: 1px solid #eee; text-align: center; }
        .stats-grid .stat-value { font-size: 16px; font-weight: bold; color: #1f2937; margin-top: 5px; }
        .stats-grid .stat-label { font-size: 10px; text-transform: uppercase; color: #6b7280; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data-table th { background-color: #f9fafb; font-size: 11px; font-weight: bold; text-transform: uppercase; color: #4b5563; }
        table.data-table td { font-size: 12px; color: #374151; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-emerald { color: #059669; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Event Performance Report</h1>
        <p><strong>Event:</strong> {{ $event->title }}</p>
        <p><strong>Date:</strong> {{ $event->start_time->format('d M Y, H:i') }} - {{ $event->end_time->format('d M Y, H:i') }}</p>
        <p><strong>Generated On:</strong> {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="section-title">Key Metrics</div>
    <table class="stats-grid">
        <tr>
            <td>
                <div class="stat-label">Gross Revenue</div>
                <div class="stat-value text-emerald">Rp {{ number_format($performanceData['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="stat-label">Tickets Sold</div>
                <div class="stat-value">{{ number_format($performanceData['totalTicketsSold'] ?? 0) }} / {{ number_format($performanceData['totalCapacity'] ?? 0) }}</div>
            </td>
            <td>
                <div class="stat-label">Sell-Through Rate</div>
                <div class="stat-value">{{ $performanceData['sellThroughRate'] ?? 0 }}%</div>
            </td>
            <td>
                <div class="stat-label">Avg Order Value</div>
                <div class="stat-value">Rp {{ number_format($performanceData['avgOrderValue'] ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="stat-label">Completed Orders</div>
                <div class="stat-value">{{ number_format($performanceData['totalOrdersCompleted'] ?? 0) }}</div>
            </td>
            <td>
                <div class="stat-label">Pending Orders</div>
                <div class="stat-value">{{ number_format($performanceData['totalOrdersPending'] ?? 0) }}</div>
            </td>
            <td>
                <div class="stat-label">Conversion Rate</div>
                <div class="stat-value">{{ $performanceData['conversionRate'] ?? 0 }}%</div>
            </td>
            <td>
                <div class="stat-label">Attendance Rate</div>
                <div class="stat-value">{{ $performanceData['attendanceRate'] ?? 0 }}%</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Ticket Tier Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tier</th>
                <th class="text-right">Price (Rp)</th>
                <th class="text-center">Capacity</th>
                <th class="text-center">Sold</th>
                <th class="text-center">Fill Rate</th>
                <th class="text-right">Revenue (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($performanceData['tierBreakdown']) && count($performanceData['tierBreakdown']) > 0)
                @foreach ($performanceData['tierBreakdown'] as $tier)
                    <tr>
                        <td>{{ $tier['name'] }}</td>
                        <td class="text-right">{{ number_format($tier['price'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($tier['capacity']) }}</td>
                        <td class="text-center">{{ number_format($tier['sold']) }}</td>
                        <td class="text-center">{{ $tier['fill'] }}%</td>
                        <td class="text-right text-emerald">{{ number_format($tier['revenue'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No ticket tiers configured.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p style="margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center;">Generated by Tixxy</p>

</body>
</html>
