<?php

namespace App\Http\Middleware;

use App\Models\Queue;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureQueueAccess
{
    /**
     * Ensure the authenticated user has an active queue entry for the event
     * they are trying to access via checkout or payment.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Determine the event ID from the route or the request
        $eventId = $this->resolveEventId($request);

        if (!$eventId) {
            // If we can't determine the event, let the downstream controller handle it
            return $next($request);
        }

        // Check for an active queue entry
        $queueEntry = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->whereIn('status', [
                Queue::STATUS_ACTIVE,
                Queue::STATUS_PROCESSING,
                Queue::STATUS_PURCHASED,
            ])
            ->first();

        if (!$queueEntry) {
            return redirect("/queue/{$eventId}")
                ->with('error', 'You need to join the queue before accessing checkout.');
        }

        // For active entries, verify the timer hasn't expired
        if ($queueEntry->status === Queue::STATUS_ACTIVE && $queueEntry->expires_at && now()->greaterThan($queueEntry->expires_at)) {
            $queueEntry->update(['status' => Queue::STATUS_EXPIRED]);

            return redirect("/queue/{$eventId}")
                ->with('error', 'Your checkout session has expired. You have been placed back in the queue.');
        }

        return $next($request);
    }

    /**
     * Resolve the event ID from the request context.
     * Looks at route parameter, query string, or the related order's event_id.
     */
    private function resolveEventId(Request $request): ?int
    {
        // Direct event_id in query string (checkout page)
        if ($request->has('event_id')) {
            return (int) $request->input('event_id');
        }

        // Route parameter 'id' might be an order ID (payment page) or event ID (checkout store)
        $routeId = $request->route('id');

        if ($routeId) {
            // If this is the payment route, the id is an order id — resolve the event from it
            if ($request->is('payment/*')) {
                $order = \App\Models\Order::find($routeId);
                return $order ? (int) $order->event_id : null;
            }

            // For checkout store, the id is the event id
            if ($request->is('checkout/*')) {
                return (int) $routeId;
            }
        }

        return null;
    }
}
