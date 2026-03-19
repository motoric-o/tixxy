<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Financial Stat Cards ──────────────────────────────────────────────
        $totalRevenue = Order::where('status', 'completed')->sum('amount');

        $revenueThisMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $pendingOrdersValue = Order::where('status', 'pending')->sum('amount');

        $avgOrderValue = Order::where('status', 'completed')->avg('amount') ?? 0;

        // ── Orders breakdown counts ───────────────────────────────────────────
        $totalOrdersCompleted = Order::where('status', 'completed')->count();
        $totalOrdersPending   = Order::where('status', 'pending')->count();
        $totalOrdersCanceled  = Order::where('status', 'canceled')->count();

        // ── Tickets ───────────────────────────────────────────────────────────
        $totalTicketsSold    = Ticket::count();
        $totalTicketsScanned = Ticket::where('is_scanned', true)->count();

        // ── Chart: Monthly Revenue (last 6 months) ────────────────────────────
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("TO_CHAR(created_at, 'Mon') AS month"),
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') AS month_key"),
                DB::raw('SUM(amount) AS total')
            )
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();

        // Fill any missing months with 0 so the chart always shows 6 bars
        $sixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key  = $date->format('Y-m');
            $existing = $monthlyRevenue->firstWhere('month_key', $key);
            $sixMonths->push([
                'month' => $date->format('M'),
                'total' => $existing ? (float) $existing->total : 0,
            ]);
        }

        // ── Chart: Monthly Spending (all orders by month, last 6 months) ──────
        $monthlySpending = Order::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("TO_CHAR(created_at, 'Mon') AS month"),
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') AS month_key"),
                DB::raw('SUM(amount) AS total')
            )
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();

        $sixMonthsSpending = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $key      = $date->format('Y-m');
            $existing = $monthlySpending->firstWhere('month_key', $key);
            $sixMonthsSpending->push([
                'month' => $date->format('M'),
                'total' => $existing ? (float) $existing->total : 0,
            ]);
        }

        // ── Events Overview ───────────────────────────────────────────────────
        $ongoingEvents = Event::where('status', 'ongoing')
            ->withCount([
                'queues',
                'queues as queues_waiting_count'  => fn ($q) => $q->where('status', 'waiting'),
                'queues as queues_completed_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->with(['eventTicketTypes'])
            ->orderBy('start_time')
            ->get();

        $upcomingEvents = Event::whereIn('status', ['preparation', 'pending'])
            ->withCount([
                'queues as queues_waiting_count' => fn ($q) => $q->where('status', 'waiting'),
            ])
            ->orderBy('start_time')
            ->limit(6)
            ->get();

        // Ticket capacity stats + per-event order status breakdown
        foreach ($ongoingEvents as $event) {
            $totalCapacity = $event->eventTicketTypes->sum('capacity');
            $totalSold     = DB::table('order_details')
                ->join('event_ticket_types', 'order_details.event_ticket_type_id', '=', 'event_ticket_types.id')
                ->where('event_ticket_types.event_id', $event->id)
                ->sum('order_details.quantity');

            $event->total_capacity = $totalCapacity ?: 0;
            $event->total_sold     = $totalSold ?: 0;
            $event->fill_percent   = $totalCapacity > 0
                ? min(100, round(($totalSold / $totalCapacity) * 100))
                : 0;

            // Per-event order status counts for the donut chart
            $statusCounts = Order::where('event_id', $event->id)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            $event->orders_completed = (int) ($statusCounts['completed'] ?? 0);
            $event->orders_pending   = (int) ($statusCounts['pending']   ?? 0);
            $event->orders_canceled  = (int) ($statusCounts['canceled']  ?? 0);
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'revenueThisMonth',
            'pendingOrdersValue',
            'avgOrderValue',
            'totalOrdersCompleted',
            'totalOrdersPending',
            'totalOrdersCanceled',
            'totalTicketsSold',
            'totalTicketsScanned',
            'sixMonths',
            'sixMonthsSpending',
            'ongoingEvents',
            'upcomingEvents',
        ));
    }
}
