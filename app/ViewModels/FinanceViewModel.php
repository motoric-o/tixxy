<?php

namespace App\ViewModels;

use App\Models\Category;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Arrayable;

class FinanceViewModel implements Arrayable
{
    public function toArray(): array
    {
        return [
            // Summary cards
            'totalRevenue'          => $this->totalRevenue(),
            'revenueThisMonth'      => $this->revenueThisMonth(),
            'revenueLastMonth'      => $this->revenueLastMonth(),
            'pendingOrdersValue'    => $this->pendingOrdersValue(),
            'avgOrderValue'         => $this->avgOrderValue(),
            'totalOrdersCompleted'  => $this->totalOrdersCompleted(),
            'totalOrdersPending'    => $this->totalOrdersPending(),
            'totalOrdersCanceled'   => $this->totalOrdersCanceled(),
            'totalTicketsSold'      => $this->totalTicketsSold(),
            'totalTicketsScanned'   => $this->totalTicketsScanned(),

            // Growth
            'growthPercent'         => $this->growthPercent(),

            // Charts
            'weeklySalesTrend'      => $this->salesTrend(6),
            'monthlySalesTrend'     => $this->salesTrend(29),
            'sixMonthsSalesTrend'   => $this->salesTrend(179),
            'yearlySalesTrend'      => $this->salesTrend(364),
            'revenueByCategory'     => $this->revenueByCategory(),
            'ticketTypeBreakdown'   => $this->ticketTypeBreakdown(),
            'topEvents'             => $this->topEvents(),

            // Pipeline events
            'preparationEvents'     => $this->preparationEvents(),
            'ongoingEvents'         => $this->ongoingEvents(),
        ];
    }

    // ─── Summary helpers ────────────────────────────────────────────

    protected function baseOrderQuery()
    {
        return Order::query();
    }

    protected function baseEventQuery()
    {
        return Event::query();
    }

    protected function baseTicketQuery()
    {
        return Ticket::query();
    }

    private function totalRevenue()
    {
        return $this->baseOrderQuery()->where('status', 'completed')->sum('amount');
    }

    private function revenueThisMonth()
    {
        return $this->baseOrderQuery()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    private function revenueLastMonth()
    {
        $lastMonth = now()->subMonth();
        return $this->baseOrderQuery()
            ->where('status', 'completed')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('amount');
    }

    private function pendingOrdersValue()
    {
        return $this->baseOrderQuery()->where('status', 'pending')->sum('amount');
    }

    private function avgOrderValue()
    {
        return $this->baseOrderQuery()->where('status', 'completed')->avg('amount') ?? 0;
    }

    private function totalOrdersCompleted()
    {
        return $this->baseOrderQuery()->where('status', 'completed')->count();
    }

    private function totalOrdersPending()
    {
        return $this->baseOrderQuery()->where('status', 'pending')->count();
    }

    private function totalOrdersCanceled()
    {
        return $this->baseOrderQuery()->where('status', 'canceled')->count();
    }

    private function totalTicketsSold()
    {
        return $this->baseTicketQuery()->count();
    }

    private function totalTicketsScanned()
    {
        return $this->baseTicketQuery()->where('is_scanned', true)->count();
    }

    // ─── Growth (MoM) ──────────────────────────────────────────────

    private function growthPercent()
    {
        $thisMonth = $this->revenueThisMonth();
        $lastMonth = $this->revenueLastMonth();

        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    // ─── Sales Trend ─────────────────────────────────────────

    private function salesTrend($days)
    {
        $start = now()->subDays($days)->startOfDay();

        $dailyRevenue = $this->baseOrderQuery()
            ->where('status', 'completed')
            ->where('created_at', '>=', $start)
            ->select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM-DD') AS day"),
                DB::raw('SUM(amount) AS total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $trend = collect();
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key  = $date->format('Y-m-d');
            $trend->push([
                'date'  => $date->format('d M'),
                'total' => (float) ($dailyRevenue[$key] ?? 0),
            ]);
        }
        return $trend;
    }

    // ─── Revenue by Category (Pie) ──────────────────────────────────

    private function revenueByCategory()
    {
        $categories = Category::all();

        return $categories->map(function ($category) {
            $revenue = $this->baseOrderQuery()
                ->where('status', 'completed')
                ->whereHas('event', fn($q) => $q->where('category_id', $category->id))
                ->sum('amount');

            return [
                'name'    => $category->name,
                'revenue' => (float) $revenue,
            ];
        })->filter(fn($c) => $c['revenue'] > 0)->values();
    }

    // ─── Ticket Type Analysis ───────────────────────────────────────

    private function ticketTypeBreakdown()
    {
        return DB::table('order_details')
            ->join('event_ticket_types', 'order_details.event_ticket_type_id', '=', 'event_ticket_types.id')
            ->join('ticket_types', 'event_ticket_types.ticket_type_id', '=', 'ticket_types.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->when($this->scopeUserId(), function ($q) {
                $q->join('events', 'event_ticket_types.event_id', '=', 'events.id')
                  ->where('events.user_id', $this->scopeUserId());
            })
            ->where('orders.status', 'completed')
            ->select(
                'ticket_types.name',
                DB::raw('SUM(order_details.quantity) AS total_sold'),
                DB::raw('SUM(order_details.quantity * event_ticket_types.price) AS total_revenue')
            )
            ->groupBy('ticket_types.name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    // ─── Top Events (Profitability Leaderboard) ─────────────────────

    private function topEvents()
    {
        return $this->baseEventQuery()
            ->withSum(['orders as total_revenue' => fn($q) => $q->where('status', 'completed')], 'amount')
            ->withCount(['orders as completed_orders_count' => fn($q) => $q->where('status', 'completed')])
            ->whereHas('orders', fn($q) => $q->where('status', 'completed'))
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id'               => $event->id,
                    'title'            => $event->title,
                    'status'           => $event->status,
                    'total_revenue'    => (float) ($event->total_revenue ?? 0),
                    'completed_orders' => (int) ($event->completed_orders_count ?? 0),
                ];
            });
    }

    // ─── Preparation Events Performance ─────────────────────────────

    private function preparationEvents()
    {
        return $this->buildPipelineEvents('preparation');
    }

    // ─── Ongoing Events Performance ─────────────────────────────────

    private function ongoingEvents()
    {
        return $this->buildPipelineEvents('ongoing');
    }

    private function buildPipelineEvents(string $status)
    {
        $events = $this->baseEventQuery()
            ->where('status', $status)
            ->with(['eventTicketTypes.ticketType', 'category'])
            ->withCount([
                'orders as orders_completed_count' => fn($q) => $q->where('status', 'completed'),
                'orders as orders_pending_count'   => fn($q) => $q->where('status', 'pending'),
                'orders as orders_canceled_count'  => fn($q) => $q->where('status', 'canceled'),
            ])
            ->withSum(['orders as revenue' => fn($q) => $q->where('status', 'completed')], 'amount')
            ->orderBy('start_time', 'asc')
            ->get();

        foreach ($events as $event) {
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
        }

        return $events;
    }

    /**
     * Override in OrganizerFinanceViewModel to scope by user.
     */
    protected function scopeUserId(): ?int
    {
        return null;
    }
}
