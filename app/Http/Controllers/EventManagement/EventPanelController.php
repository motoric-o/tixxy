<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventPanelController extends Controller
{
    public function index(Request $request, $id)
    {
        $event = Event::with(['eventTicketTypes' => function($q) {
                $q->withCount('orderDetails')->with('ticketType');
            }])
            ->when(Auth::user()->role === 'organizer', fn($q) => $q->where('user_id', Auth::id()))
            ->where('id', $id)
            ->firstOrFail();

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $ticketTypes = TicketType::orderBy('name')->get();
        $organizers = User::whereIn('role', ['organizer', 'admin'])->pluck('name', 'id');

        $ticketTypesData = $ticketTypes->map(function ($t) {
            return ['id' => (string) $t->id, 'name' => $t->name];
        })->values();

        $eventTicketTypesData = $event->eventTicketTypes->map(function ($tt) {
            return [
                'id'             => $tt->id,
                'ticket_type_id' => (string) $tt->ticket_type_id,
                'price'          => $tt->price,
                'capacity'       => $tt->capacity,
                'sold_count'     => $tt->order_details_count ?? 0,
                'editMode'       => false,
                'isNew'          => false,
            ];
        })->values();

        // Fetch performance data for the unified dashboard
        $eventsList = Event::orderBy('start_time', 'desc')->get(['id', 'title', 'status']);
        $perfViewModel = new \App\ViewModels\EventPerformanceViewModel($eventsList, $id, '30d');
        $performanceData = $perfViewModel->toArray();

        // Fetch full paginated orders for the new Orders tab with filters
        $ordersQuery = \App\Models\Order::with(['user', 'orderDetails.eventTicketType.ticketType'])
            ->where('event_id', $id);

        // Apply Filters
        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('ticket_type_id')) {
            $ordersQuery->whereHas('orderDetails', function ($q) use ($request) {
                $q->where('event_ticket_type_id', $request->ticket_type_id);
            });
        }

        if ($request->filled('has_payment_proof')) {
            if ($request->has_payment_proof == 'yes') {
                $ordersQuery->whereNotNull('payment_proof');
            } else {
                $ordersQuery->whereNull('payment_proof');
            }
        }

        if ($request->filled('date_from')) {
            $ordersQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $ordersQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $ordersQuery->orderBy('created_at', 'asc');
                break;
            case 'amount_desc':
                $ordersQuery->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $ordersQuery->orderBy('amount', 'asc');
                break;
            case 'customer_asc':
                $ordersQuery->join('users', 'orders.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('orders.*');
                break;
            case 'customer_desc':
                $ordersQuery->join('users', 'orders.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('orders.*');
                break;
            default:
                $ordersQuery->orderBy('created_at', 'desc');
                break;
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        $viewModel = new ManageEventViewModel($event, 'index', $categories, $ticketTypes, $organizers, $ticketTypesData, $eventTicketTypesData, $performanceData, $orders);
        return view('admin.event', $viewModel->toArray());
    }

    /**
     * Return AJAX JSON payload for the dashboard charts & metrics.
     */
    public function performanceData(Request $request, $id)
    {
        $range = $request->get('range', '30d');
        $eventsList = Event::orderBy('start_time', 'desc')->get(['id', 'title', 'status']);
        $viewModel = new \App\ViewModels\EventPerformanceViewModel($eventsList, $id, $range);

        return response()->json($viewModel->toArray());
    }

    public function update(Request $request, $id) {
        if (Auth::user()->role === 'organizer') abort(403, 'Unauthorized action.');

        $event = Event::when(Auth::user()->role === 'organizer', fn($q) => $q->where('user_id', Auth::id()))
            ->where('id', $id)
            ->firstOrFail();

        // 1. Validation Parity
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_time'  => 'required|date_format:Y-m-d\TH:i',
            'end_time'    => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota'       => 'required|integer|min:1',
            'status'      => 'required|in:' . implode(',', array_keys(Event::getStatuses())),
            'banner_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // 2. Extra Business Rules (Price/Quota/Deletion Guards)
        if ($request->has('ticket_types')) {
            $incomingTicketTypes = collect($request->ticket_types);
            $totalIncomingCapacity = $incomingTicketTypes->sum('capacity');

            // Quota Guard
            if ($totalIncomingCapacity > $request->quota) {
                return back()->withInput()->withErrors(['quota' => 'The total capacity of ticket types cannot exceed the event quota.']);
            }

            $existingTypes = $event->eventTicketTypes()->withCount('orderDetails')->get();
            $existingIds = $existingTypes->pluck('id')->toArray();
            $incomingIds = $incomingTicketTypes->pluck('id')->filter()->toArray();

            // Deletion Guard: Check if any removed types had sales
            foreach ($existingTypes as $existing) {
                if (!in_array($existing->id, $incomingIds) && $existing->order_details_count > 0) {
                    return back()->withInput()->withErrors(['ticket_types' => "Cannot remove ticket type '{$existing->ticketType->name}' because it has existing orders."]);
                }
            }

            // Price & Capacity Floor Guards
            foreach ($request->ticket_types as $tt) {
                if (!empty($tt['id'])) {
                    $existing = $existingTypes->find($tt['id']);
                    if ($existing && $existing->order_details_count > 0) {
                        // Price Lock
                        if ((float)$existing->price !== (float)$tt['price']) {
                            return back()->withInput()->withErrors(['ticket_types' => "Cannot change price for '{$existing->ticketType->name}' because tickets have already been sold."]);
                        }
                        // Capacity Floor
                        if ($tt['capacity'] < $existing->order_details_count) {
                            return back()->withInput()->withErrors(['ticket_types' => "Capacity for '{$existing->ticketType->name}' cannot be lower than the number of tickets sold ({$existing->order_details_count})."]);
                        }
                    }
                }
            }
        }

        // 3. Perform Updates
        $data = $request->only(['title', 'category_id', 'start_time', 'end_time', 'location', 'description', 'quota', 'status']);
        
        if ($request->hasFile('banner_path')) {
            // Delete old banner if exists
            if ($event->banner_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->banner_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->banner_path);
            }
            $data['banner_path'] = $request->file('banner_path')->store('events/banners', 'public');
        }
        
        // Security: Ensure organizer can't reassign event owner via this form
        if (Auth::user()->role === 'admin' && $request->has('user_id')) {
            $data['user_id'] = $request->user_id;
        }

        $event->update($data);

        if ($request->has('ticket_types')) {
            // Non-destructive update (Upsert)
            $incomingIds = [];
            foreach($request->ticket_types as $tt) {
                if (!empty($tt['ticket_type_id'])) {
                    $record = $event->eventTicketTypes()->updateOrCreate(
                        ['id' => $tt['id'] ?? null],
                        [
                            'ticket_type_id' => $tt['ticket_type_id'],
                            'price' => $tt['price'],
                            'capacity' => $tt['capacity'],
                        ]
                    );
                    $incomingIds[] = $record->id;
                }
            }
            // Now safe to delete those that aren't in incoming and have no orders (guarded above)
            $event->eventTicketTypes()->whereNotIn('id', $incomingIds)->delete();
        }

        return back()->with('success', 'Event updated successfully.');
    }
}
