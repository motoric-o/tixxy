<?php

namespace App\ViewModels;

use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Arrayable;

class EventPerformanceViewModel implements Arrayable
{
    private $events;
    private $selectedEventId;
    private $range;

    public function __construct($events, $eventId = null, $range)
    {
        $this->events = $events;
        $this->selectedEventId = $eventId;
        $this->range = $range;
    }

    public function toArray(): array
    {
        return [
            'events'              => $this->events,
            'selectedEventId'     => $this->selectedEventId,
            'totalRevenue'        => $this->totalRevenue(),
            'pendingOrdersValue'  => $this->pendingOrdersValue(),
            'totalOrdersCompleted' => $this->totalOrdersCompleted(),
            'totalOrdersPending'  => $this->totalOrdersPending(),
            'totalOrdersCanceled' => $this->totalOrdersCanceled(),
            'sellThroughRate'     => $this->sellThroughRate(),
            'totalCapacity'       => $this->totalCapacity(),
            'totalTicketsSold'    => $this->totalTicketsSold(),
            'avgOrderValue'       => $this->avgOrderValue(),
            'conversionRate'      => $this->conversionRate(),
            'attendanceRate'      => $this->attendanceRate(),
            'totalTicketsScanned' => $this->totalTicketsScanned(),
            'tierBreakdown'       => $this->tierBreakdown(),
            'chartLabels'         => $this->velocityChartData()['labels'],
            'chartVelocity'       => $this->velocityChartData()['data'],
            'chartRevenueVelocity'=> $this->revenueVelocityChartData()['data'],
        ];
    }

    private function totalRevenue()
    {
        return Order::where('event_id', $this->selectedEventId)->where('status', 'completed')->sum('amount');
    }

    private function pendingOrdersValue()
    {
        return Order::where('event_id', $this->selectedEventId)->where('status', 'pending')->sum('amount');
    }

    private function totalOrdersCompleted()
    {
        return Order::where('event_id', $this->selectedEventId)->where('status', 'completed')->count();
    }

    private function totalOrdersPending()
    {
        return Order::where('event_id', $this->selectedEventId)->where('status', 'pending')->count();
    }

    private function totalOrdersCanceled()
    {
        // Note: Order model uses 'canceled' (single L)
        return Order::where('event_id', $this->selectedEventId)->where('status', 'canceled')->count();
    }

    private function sellThroughRate()
    {
        $capacity = $this->totalCapacity();
        if ($capacity <= 0) return 0;

        return round(($this->totalTicketsSold() / $capacity) * 100, 1);
    }

    private function totalCapacity()
    {
        $event = Event::find($this->selectedEventId);
        return $event ? (int) $event->quota : 0;
    }

    private function totalTicketsSold()
    {
        return Ticket::whereHas('order', function ($query) {
            $query->where('event_id', $this->selectedEventId)
                ->where('status', 'completed');
        })->count();
    }

    private function avgOrderValue()
    {
        $totalOrders = $this->totalOrdersCompleted();
        if ($totalOrders <= 0) return 0;

        return round($this->totalRevenue() / $totalOrders, 0);
    }

    private function conversionRate()
    {
        $totalOrders = $this->totalOrdersCompleted() + $this->totalOrdersPending() + $this->totalOrdersCanceled();
        if ($totalOrders <= 0) return 0;

        return round(($this->totalOrdersCompleted() / $totalOrders) * 100, 1);
    }

    private function attendanceRate()
    {
        $sold = $this->totalTicketsSold();
        if ($sold <= 0) return 0;

        return round(($this->totalTicketsScanned() / $sold) * 100, 1);
    }

    private function totalTicketsScanned()
    {
        return Ticket::whereHas('order', function ($query) {
            $query->where('event_id', $this->selectedEventId)
                ->where('status', 'completed');
        })->where('is_scanned', true)->count();
    }

    /**
     * Build tier breakdown from EventTicketType for the selected event.
     * Returns a collection of arrays with: name, price, capacity, sold, revenue, fill.
     */
    private function tierBreakdown()
    {
        $tiers = EventTicketType::where('event_id', $this->selectedEventId)
            ->with('ticketType')
            ->get();

        return $tiers->map(function ($ett) {
            // Count tickets sold for this specific event_ticket_type via order_details
            $sold = OrderDetail::where('event_ticket_type_id', $ett->id)
                ->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                })
                ->count();

            $capacity = (int) $ett->capacity;
            $price    = (float) $ett->price;
            $revenue  = $sold * $price;
            $fill     = $capacity > 0 ? round(($sold / $capacity) * 100, 1) : 0;

            return [
                'name'     => $ett->ticketType->name ?? 'Unknown',
                'price'    => $price,
                'capacity' => $capacity,
                'sold'     => $sold,
                'revenue'  => $revenue,
                'fill'     => $fill,
            ];
        })->toArray();
    }

    private function velocityChartData()
    {
        $labels = [];
        $data = [];

        if ($this->range == '24h') {
            // Every 3 hours over the last 24h
            for ($i = 21; $i >= 0; $i -= 3) {
                $start = now()->subHours($i + 3);
                $end   = now()->subHours($i);
                $labels[] = $end->format('H:00');
                $data[] = $this->countTicketsInDateRange($start, $end);
            }
        } elseif ($this->range == '7d') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('D'); // Mon, Tue
                $data[] = $this->countTicketsInDateRange($date->copy()->startOfDay(), $date->copy()->endOfDay());
            }
        } elseif ($this->range == '30d') {
            // By week for the last 30 days, or just roughly every 7 days (4 points)
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subDays(($i * 7) + 7);
                $end   = now()->subDays($i * 7);
                $labels[] = 'Week ' . (4 - $i);
                $data[] = $this->countTicketsInDateRange($start, $end);
            }
        } else {
            // all time - by month for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->startOfMonth()->subMonths($i);
                $labels[] = $date->format('M');
                $data[] = $this->countTicketsInDateRange($date->copy()->startOfMonth(), $date->copy()->endOfMonth());
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function countTicketsInDateRange($start, $end)
    {
        return Ticket::whereHas('order', function ($q) use ($start, $end) {
            $q->where('event_id', $this->selectedEventId)
              ->where('status', 'completed')
              ->whereBetween('created_at', [$start, $end]);
        })->count();
    }

    private function revenueVelocityChartData()
    {
        $labels = [];
        $data = [];

        if ($this->range == '24h') {
            // Every 3 hours over the last 24h
            for ($i = 21; $i >= 0; $i -= 3) {
                $start = now()->subHours($i + 3);
                $end   = now()->subHours($i);
                $labels[] = $end->format('H:00');
                $data[] = $this->calculateRevenueInDateRange($start, $end);
            }
        } elseif ($this->range == '7d') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('D'); // Mon, Tue
                $data[] = $this->calculateRevenueInDateRange($date->copy()->startOfDay(), $date->copy()->endOfDay());
            }
        } elseif ($this->range == '30d') {
            // By week for the last 30 days, or just roughly every 7 days (4 points)
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subDays(($i * 7) + 7);
                $end   = now()->subDays($i * 7);
                $labels[] = 'Week ' . (4 - $i);
                $data[] = $this->calculateRevenueInDateRange($start, $end);
            }
        } else {
            // all time - by month for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->startOfMonth()->subMonths($i);
                $labels[] = $date->format('M');
                $data[] = $this->calculateRevenueInDateRange($date->copy()->startOfMonth(), $date->copy()->endOfMonth());
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function calculateRevenueInDateRange($start, $end)
    {
        return Order::where('event_id', $this->selectedEventId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');
    }
}
