@extends('layouts.default')

@section('content')
<div class="min-h-screen py-12" style="background: linear-gradient(135deg, #0f0c29, #1a0533, #0f0c29);">

    {{-- Hero Header --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold uppercase tracking-widest" style="color: #a855f7;">Account</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3 leading-tight">
                My Orders
            </h1>
            <p class="text-lg" style="color: #c4b5fd;">
                Track your event bookings and access your e-tickets.
            </p>
        </div>

        {{-- Stats Row --}}
        @if($orders->isNotEmpty())
        @php
            $totalOrders = $orders->count();
            $completedOrders = $orders->where('status', 'completed')->count();
            $pendingOrders = $orders->where('status', 'pending')->count();
            $totalTickets = $orders->sum(fn($o) => $o->tickets->count());
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            @foreach([
                ['label' => 'Total Orders',   'value' => $totalOrders,    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Confirmed',       'value' => $completedOrders,'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Pending',         'value' => $pendingOrders,  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Total Tickets',   'value' => $totalTickets,   'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
            ] as $stat)
            <div class="rounded-2xl p-5 border border-white/10 text-center transition-all duration-300 hover:border-purple-500/40 hover:-translate-y-0.5"
                 style="background: rgba(255,255,255,0.05); backdrop-filter: blur(12px);">
                <svg class="w-5 h-5 mx-auto mb-2" style="color:#a855f7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                </svg>
                <p class="text-2xl font-extrabold text-white">{{ $stat['value'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color:#c4b5fd;">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Orders List --}}
        <div class="space-y-6">
            @forelse($orders as $order)
            @php
                $event = $order->event;
                $statusColor = match($order->status) {
                    'completed' => ['bg' => 'rgba(16,185,129,0.15)', 'border' => 'rgba(16,185,129,0.4)', 'text' => '#34d399', 'dot' => '#10b981'],
                    'pending'   => ['bg' => 'rgba(245,158,11,0.15)', 'border' => 'rgba(245,158,11,0.4)', 'text' => '#fbbf24', 'dot' => '#f59e0b'],
                    default     => ['bg' => 'rgba(239,68,68,0.15)',  'border' => 'rgba(239,68,68,0.4)',  'text' => '#f87171', 'dot' => '#ef4444'],
                };
                $statusLabel = match($order->status) {
                    'completed' => 'Confirmed',
                    'pending'   => 'Pending Payment',
                    default     => 'Cancelled',
                };
            @endphp

            <div class="rounded-3xl border overflow-hidden transition-all duration-300 hover:border-purple-500/50 hover:shadow-[0_0_30px_rgba(168,85,247,0.15)]"
                 style="background: rgba(255,255,255,0.04); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1);">

                {{-- Order Header --}}
                <div class="px-6 py-5 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                     style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07);">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background: linear-gradient(135deg, #5b21b6, #7c3aed);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-white leading-tight">
                                {{ $event->title ?? 'Event Unavailable' }}
                            </h2>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                @if($event)
                                <span class="text-sm" style="color:#c4b5fd;">
                                    <svg class="inline w-3.5 h-3.5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('D, d M Y · H:i') }}
                                </span>
                                <span class="text-xs" style="color:#7c3aed;">•</span>
                                <span class="text-sm" style="color:#c4b5fd;">
                                    <svg class="inline w-3.5 h-3.5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->location ?? 'TBA' }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border"
                              style="background: {{ $statusColor['bg'] }}; border-color: {{ $statusColor['border'] }}; color: {{ $statusColor['text'] }};">
                            <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $statusColor['dot'] }};"></span>
                            {{ $statusLabel }}
                        </span>
                        {{-- Amount --}}
                        <div class="text-right">
                            <p class="text-xs font-medium mb-0.5" style="color:#a78bfa;">Total Paid</p>
                            <p class="text-lg font-extrabold text-white">
                                Rp {{ number_format($order->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tickets Grid --}}
                <div class="p-6">
                    <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#7c3aed;">
                        {{ $order->tickets->count() }} {{ Str::plural('Ticket', $order->tickets->count()) }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($order->tickets as $ticket)
                        @php
                            // Find this ticket's type from orderDetails
                            $detail = $order->orderDetails->firstWhere('ticket_id', $ticket->id);
                            $ticketTypeName = $detail?->eventTicketType?->ticketType?->name ?? 'General Admission';
                            $ticketTypePrice = $detail?->eventTicketType?->price ?? null;
                        @endphp
                        <div class="group relative rounded-2xl border overflow-hidden transition-all duration-300 hover:border-purple-500/60 hover:-translate-y-0.5 hover:shadow-[0_4px_20px_rgba(168,85,247,0.2)]"
                             style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08);">

                            {{-- Ticket type color strip --}}
                            <div class="h-1 w-full"
                                 style="background: linear-gradient(90deg, #7c3aed, #a855f7, #c084fc);"></div>

                            <div class="p-4">
                                {{-- Ticket Type Badge --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold"
                                          style="background: rgba(124,58,237,0.2); color:#c084fc; border: 1px solid rgba(124,58,237,0.3);">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        {{ $ticketTypeName }}
                                    </span>

                                    {{-- Scan status --}}
                                    @if($ticket->is_scanned)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold"
                                          style="background: rgba(245,158,11,0.15); color:#fbbf24;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4"/>
                                        </svg>
                                        Used
                                    </span>
                                    @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold"
                                          style="background: rgba(16,185,129,0.15); color:#34d399;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Ready
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold"
                                          style="background: rgba(107,114,128,0.15); color:#9ca3af;">
                                        Locked
                                    </span>
                                    @endif
                                </div>

                                {{-- Price --}}
                                @if($ticketTypePrice)
                                <p class="text-xs mb-3" style="color:#9ca3af;">
                                    Rp {{ number_format($ticketTypePrice, 0, ',', '.') }} / ticket
                                </p>
                                @endif

                                {{-- QR hint strip --}}
                                @if($order->status === 'completed' && !$ticket->is_scanned)
                                <div class="flex items-center gap-2 rounded-lg px-3 py-2 mb-3"
                                     style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);">
                                    <svg class="w-4 h-4 shrink-0" style="color:#34d399" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0zM6.5 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm7 0a.5.5 0 11-1 0 .5.5 0 011 0zm-7 7a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                    <p class="text-xs font-medium" style="color:#6ee7b7;">QR code available</p>
                                </div>
                                @elseif($order->status === 'pending')
                                <div class="flex items-center gap-2 rounded-lg px-3 py-2 mb-3"
                                     style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
                                    <svg class="w-4 h-4 shrink-0" style="color:#fbbf24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <p class="text-xs font-medium" style="color:#fde68a;">Awaiting payment</p>
                                </div>
                                @endif

                                {{-- View Ticket Button --}}
                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-300 hover:shadow-[0_0_20px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95"
                                   style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    View E-Ticket
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Quick action for pending orders --}}
                    @if($order->status === 'pending')
                    <div class="mt-5 flex items-center justify-between rounded-2xl px-5 py-4 border"
                         style="background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.25);">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" style="color:#fbbf24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold" style="color:#fde68a;">Payment pending</p>
                                @if($order->expired_at)
                                <p class="text-xs mt-0.5" style="color:#fbbf24;">
                                    Expires {{ \Carbon\Carbon::parse($order->expired_at)->diffForHumans() }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('payment.show', $order->id) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                            Pay Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                    @endif

                    {{-- Order meta footer --}}
                    <div class="mt-4 flex items-center justify-between pt-4 border-t" style="border-color: rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-1.5 text-xs" style="color:#6b7280;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ordered {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs" style="color:#6b7280;">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Verified by Tixxy
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Empty State --}}
            <div class="text-center py-24 rounded-3xl border"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
                     style="background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);">
                    <svg class="w-10 h-10" style="color:#7c3aed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-white mb-3">No orders yet</h3>
                <p class="text-base mb-8 max-w-sm mx-auto" style="color:#9ca3af;">
                    You haven't purchased any event tickets yet. Discover amazing events happening near you.
                </p>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 rounded-2xl text-sm font-bold text-white transition-all duration-300 hover:shadow-[0_0_25px_rgba(124,58,237,0.5)] hover:-translate-y-0.5"
                   style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Explore Events
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection