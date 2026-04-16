@extends('layouts.default')

@section('content')
@php
    $event = $ticket->order->event;
    $order = $ticket->order;
    // Find specific ticket detail for this ticket record
    $ticketDetail = $order->orderDetails->where('ticket_id', $ticket->id)->first();
    $ticketType = $ticketDetail?->eventTicketType?->ticketType?->name ?? 'General Admission';
    $ticketPrice = $ticketDetail?->eventTicketType?->price ?? 0;
@endphp

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back Link -->
        <a href="/tickets" class="text-indigo-600 dark:text-indigo-400 flex items-center gap-2 font-medium hover:underline mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to My Orders
        </a>

        <!-- E-Ticket Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

            <!-- Header Banner with Event Image -->
            <div class="relative px-8 py-12 text-white overflow-hidden">
                @if($event->banner_path)
                    <div class="absolute inset-0 z-0">
                        <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 via-purple-900/80 to-indigo-900/90 backdrop-blur-[2px]"></div>
                    </div>
                @else
                    <div class="absolute inset-0 z-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800"></div>
                @endif
                
                <!-- Decorative circles -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full z-0"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full z-0"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <span class="text-indigo-200 text-sm font-semibold uppercase tracking-wider">E-Ticket</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight drop-shadow-lg">{{ $event->title }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-4">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-xs font-bold uppercase tracking-wider border border-white/20">
                            {{ $event->category->name ?? 'Event' }}
                        </span>
                        <span class="px-3 py-1 bg-indigo-500/30 backdrop-blur-sm rounded-lg text-xs font-bold uppercase tracking-wider border border-indigo-400/30">
                            {{ $ticketType }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ticket Tear Line -->
            <div class="relative">
                <div class="absolute left-0 top-0 w-6 h-6 bg-gray-50 dark:bg-gray-900 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute right-0 top-0 w-6 h-6 bg-gray-50 dark:bg-gray-900 rounded-full translate-x-1/2 -translate-y-1/2"></div>
                <div class="border-t-2 border-dashed border-gray-200 dark:border-gray-700 mx-8"></div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    <!-- Left: Event Details -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-5">Event Information</h3>
                            <div class="space-y-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider mb-0.5">Date & Time</p>
                                        <p class="text-gray-900 dark:text-white font-bold">{{ \Carbon\Carbon::parse($event->start_time)->format('l, d M Y') }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} 
                                            @if($event->end_time)
                                                - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider mb-0.5">Venue</p>
                                        <p class="text-gray-900 dark:text-white font-bold">{{ $event->location }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider mb-0.5">Attendee</p>
                                        <p class="text-gray-900 dark:text-white font-bold line-clamp-1">{{ $order->user->name ?? 'N/A' }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">{{ $order->user->email ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider mb-0.5">Ticket Info</p>
                                        <p class="text-gray-900 dark:text-white font-bold">{{ $ticketType }}</p>
                                        <p class="text-indigo-600 dark:text-indigo-400 text-sm font-bold">Rp {{ number_format($ticketPrice, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-4 p-5 rounded-2xl shadow-sm
                            @if($ticket->is_scanned) bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50
                            @elseif($order->status === 'completed') bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50
                            @else bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 @endif">
                            @if($ticket->is_scanned)
                                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-amber-900 dark:text-amber-300">Ticket Used</p>
                                    <p class="text-xs text-amber-700/70 dark:text-amber-400/70 font-medium">Already scanned at entrance</p>
                                </div>
                            @elseif($order->status === 'completed')
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-emerald-900 dark:text-emerald-300">Active Ticket</p>
                                    <p class="text-xs text-emerald-700/70 dark:text-emerald-400/70 font-medium">Ready for verification</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-300 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-gray-700 dark:text-gray-200">{{ ucfirst($order->status) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Waiting for completion</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: QR Code -->
                    <div class="flex flex-col items-center justify-center">
                        @if($order->status === 'completed')
                            <div class="bg-white p-6 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-700 ring-4 ring-indigo-50 dark:ring-indigo-900/20 transform hover:scale-[1.02] transition-transform duration-300">
                                <div id="qr-code-container" class="filter drop-shadow-md"></div>
                            </div>
                            <div class="mt-8 text-center bg-gray-100 dark:bg-gray-800/80 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700">
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-[0.2em] mb-0.5">Digital Signature</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 font-mono tracking-tighter select-all">
                                    {{ substr($ticket->qr_code_hash, 0, 8) }}...{{ substr($ticket->qr_code_hash, -8) }}
                                </p>
                            </div>
                            <p class="mt-4 text-xs text-gray-400 dark:text-gray-500 font-medium italic">
                                Scan at entrance for fast verification
                            </p>
                        @else
                            <div class="flex flex-col items-center justify-center text-center p-10 bg-gray-50 dark:bg-gray-800/40 rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-gray-700 max-w-sm">
                                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 text-gray-400 dark:text-gray-500">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">Ticket Locked</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Your digital ticket will be automatically unlocked and generated once the payment process is verified.</p>
                                @if($order->status === 'pending')
                                    <a href="{{ route('payment.show', $order->id) }}" class="mt-8 inline-flex px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">Complete Payment</a>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 dark:border-gray-700 px-8 py-6 bg-gray-50/50 dark:bg-gray-900/30">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-[11px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">
                    <div class="flex items-center gap-5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Purchased: {{ $order->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Secured by Tixxy
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Generation (lightweight, no PHP package needed) -->
@if($order->status === 'completed')
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qr = qrcode(0, 'M');
        qr.addData('{{ $ticket->qr_code_hash }}');
        qr.make();

        const container = document.getElementById('qr-code-container');
        if (container) {
            container.innerHTML = qr.createSvgTag({
                cellSize: 5,
                margin: 0,
                scalable: true
            });

            // Style the generated SVG
            const svg = container.querySelector('svg');
            if (svg) {
                svg.style.width = '200px';
                svg.style.height = '200px';
            }
        }
    });
</script>
@endif
@endsection
