<?php

namespace App\ViewModels;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Arrayable;

class OrganizerDashboardViewModel implements Arrayable
{
    // get from user ID from constructor
    private $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function toArray(): array
    {
        return [
            'totalRevenue' => $this->totalRevenue(),
            'revenueThisMonth' => $this->revenueThisMonth(),
            'pendingOrdersValue' => $this->pendingOrdersValue(),
            'avgOrderValue' => $this->avgOrderValue(),
            'totalOrdersCompleted' => $this->totalOrdersCompleted(),
            'totalOrdersPending' => $this->totalOrdersPending(),
            'totalOrdersCanceled' => $this->totalOrdersCanceled(),
            'totalTicketsSold' => $this->totalTicketsSold(),
            'totalTicketsScanned' => $this->totalTicketsScanned(),
            'sixMonths' => $this->sixMonthsRevenue(),
            'sixMonthsSpending' => $this->sixMonthsSpending(),
            'ongoingEvents' => $this->ongoingEvents(),
            'upcomingEvents' => $this->upcomingEvents(),
        ];
    }

    private function totalRevenue()
    {
        return Order::where('status', 'completed')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->sum('amount');
    }

    private function revenueThisMonth()
    {
        return Order::where('status', 'completed')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    private function pendingOrdersValue()
    {
        return Order::where('status', 'pending')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->sum('amount');
    }

    private function avgOrderValue()
    {
        return Order::where('status', 'completed')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->avg('amount') ?? 0;
    }

    private function totalOrdersCompleted()
    {
        return Order::where('status', 'completed')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->count();
    }

    private function totalOrdersPending()
    {
        return Order::where('status', 'pending')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->count();
    }

    private function totalOrdersCanceled()
    {
        return Order::where('status', 'canceled')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->count();
    }

    private function totalTicketsSold()
    {
        return Ticket::whereHas('order.event', fn($q) => $q->where('user_id', $this->userId))->count();
    }

    private function totalTicketsScanned()
    {
        return Ticket::where('is_scanned', true)
            ->whereHas('order.event', fn($q) => $q->where('user_id', $this->userId))
            ->count();
    }

    private function sixMonthsRevenue()
    {
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("TO_CHAR(created_at, 'Mon') AS month"),
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') AS month_key"),
                DB::raw('SUM(amount) AS total')
            )
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();

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
        return $sixMonths;
    }

    private function sixMonthsSpending()
    {
        $monthlySpending = Order::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->whereHas('event', fn($q) => $q->where('user_id', $this->userId))
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
        return $sixMonthsSpending;
    }

    private function ongoingEvents()
    {
        $ongoingEvents = Event::where('user_id', $this->userId)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->withCount([
                'queues',
                'queues as queues_waiting_count'  => fn ($q) => $q->where('status', 'waiting'),
                'queues as queues_completed_count' => fn ($q) => $q->where('status', 'completed'),
                'orders as orders_completed_count' => fn ($q) => $q->where('status', 'completed'),
                'orders as orders_pending_count'   => fn ($q) => $q->where('status', 'pending'),
                'orders as orders_canceled_count'  => fn ($q) => $q->where('status', 'canceled'),
            ])
            ->with(['eventTicketTypes'])
            ->orderBy('start_time')
            ->get();

        foreach ($ongoingEvents as $event) {
            $totalCapacity = $event->quota;
            
            $totalSold = DB::table('order_details')
                ->join('event_ticket_types', 'order_details.event_ticket_type_id', '=', 'event_ticket_types.id')
                ->where('event_ticket_types.event_id', $event->id)
                ->count();

            $event->total_capacity = $totalCapacity ?: 0;
            $event->total_sold     = $totalSold ?: 0;
            $event->fill_percent   = $totalCapacity > 0
                ? min(100, round(($totalSold / $totalCapacity) * 100))
                : 0;

            $event->orders_completed = (int) $event->orders_completed_count;
            $event->orders_pending   = (int) $event->orders_pending_count;
            $event->orders_canceled  = (int) $event->orders_canceled_count;
        }

        return $ongoingEvents;
    }

    private function upcomingEvents()
    {
        return Event::where('user_id', $this->userId)
            ->where('start_time', '>', now())
            ->whereIn('status', ['preparation', 'pending', 'ongoing'])
            ->withCount([
                'queues as queues_waiting_count' => fn ($q) => $q->where('status', 'waiting'),
            ])
            ->orderBy('start_time')
            ->limit(6)
            ->get();
    }
}
