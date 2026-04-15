@extends('layouts.admin.default')

@section('content')
    @php
        $grouped = $order->orderDetails->groupBy('event_ticket_type_id');
    @endphp

    <!-- Back Link -->
    <a href="{{ route('manage.events.edit', $order->event_id) }}#orders"
        class="group inline-flex items-center gap-2.5 text-sm font-semibold text-gray-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-all duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Back to Event Orders
    </a>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Payment Approval</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Review payment proof and manage order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <span
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
            @if ($order->status === 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800/50
            @elseif($order->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50
            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800/50 @endif">
            <span
                class="w-1.5 h-1.5 rounded-full
                @if ($order->status === 'completed') bg-green-500
                @elseif($order->status === 'pending') bg-amber-500
                @else bg-red-500 @endif"></span>
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ====== LEFT COLUMN: Payment Proof ====== -->
        <div class="lg:col-span-2">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

                <!-- Payment Proof Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Payment Proof</h3>
                        <p class="text-xs text-gray-400">Uploaded by {{ $order->user->name ?? 'Unknown' }}</p>
                    </div>
                </div>

                <!-- Payment Proof Image -->
                <div class="p-6">
                    @if ($order->payment_proof)
                        <div
                            class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}"
                                alt="Payment proof for Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}"
                                class="w-full h-auto max-h-[600px] object-contain">
                        </div>
                    @else
                        <div
                            class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-600 p-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-sm font-medium text-gray-400 dark:text-gray-500">No payment proof uploaded</p>
                            <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">The user has not submitted any payment
                                evidence yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if ($order->status === 'pending' && $order->payment_proof)
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form action="/manage/orders/{{ $order->id }}/approve" method="POST" class="flex-1"
                                onsubmit="return confirm('Approve this payment? This will mark the order as completed and send a confirmation email to the customer.')">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/20 hover:shadow-green-500/40 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Approve Payment
                                </button>
                            </form>
                            <form action="/manage/orders/{{ $order->id }}/decline" method="POST" class="flex-1"
                                onsubmit="return confirm('Cancel this order? This will permanently cancel the order, delete the tickets, and release the spot to the next person in the queue.')">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg shadow-red-500/20 hover:shadow-red-500/40 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    Cancel Order
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($order->status === 'completed')
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        <div
                            class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50">
                            <div
                                class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-green-800 dark:text-green-300">Payment Approved</p>
                                <p class="text-xs text-green-600 dark:text-green-400">This order has been completed and the
                                    confirmation email was sent.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ====== RIGHT COLUMN: Order & User Info ====== -->
        <div class="space-y-6">

            <!-- Order Summary Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Order Summary</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Order ID</span>
                        <span
                            class="text-sm font-bold text-gray-800 dark:text-white">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Order Date</span>
                        <span
                            class="text-sm text-gray-600 dark:text-gray-300">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Event</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-white text-right max-w-[60%] truncate">{{ $order->event->title ?? 'N/A' }}</span>
                    </div>
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-3">Tickets</p>
                        @foreach ($grouped as $eventTicketTypeId => $details)
                            @php
                                $firstDetail = $details->first();
                                $ticketTypeName = $firstDetail->eventTicketType->ticketType->name ?? 'Unknown';
                                $price = $firstDetail->eventTicketType->price ?? 0;
                                $quantity = $details->count();
                            @endphp
                            <div class="flex justify-between items-center py-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $ticketTypeName }}</p>
                                    <p class="text-xs text-gray-400">{{ $quantity }}x @ Rp
                                        {{ number_format($price, 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Rp
                                    {{ number_format($price * $quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800 dark:text-white">Total</span>
                        <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">Rp
                            {{ number_format($order->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- User Information Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Attendee Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Full Name</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-1">Email</p>
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                            {{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Event Details Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Event Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Date & Time</p>
                            @if (\Carbon\Carbon::parse($order->event->start_time)->isSameDay(\Carbon\Carbon::parse($order->event->end_time)))
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($order->event->start_time)->format('l, d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($order->event->start_time)->format('h:i A') }}
                                    — {{ \Carbon\Carbon::parse($order->event->end_time)->format('h:i A') }}
                                </p>
                            @else
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($order->event->start_time)->format('l, d M Y — h:i A') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    to {{ \Carbon\Carbon::parse($order->event->end_time)->format('l, d M Y — h:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Venue</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                {{ $order->event->location ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
