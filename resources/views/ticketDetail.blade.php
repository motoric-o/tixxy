@extends('layouts.default')

@section('content')
@php
    $event = $ticket->order->event;
    $order = $ticket->order;
@endphp

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back Link -->
        <a href="/tickets" class="text-indigo-600 dark:text-indigo-400 flex items-center gap-2 font-medium hover:underline mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to My Tickets
        </a>

        <!-- E-Ticket Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

            <!-- Header Banner -->
            <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 px-8 py-10 text-white overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full"></div>

                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            <span class="text-indigo-200 text-sm font-semibold uppercase tracking-wider">E-Ticket</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight">{{ $event->title }}</h1>
                        <span class="inline-block mt-3 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-xs font-bold uppercase tracking-wider">
                            {{ $event->category->name ?? 'Event' }}
                        </span>
                    </div>
                    <div class="text-right hidden sm:block">
                        <span class="text-xs text-indigo-200 uppercase tracking-wider block">Ticket #</span>
                        <span class="text-lg font-mono font-bold">{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Left: Event Details -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Event Details</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase font-semibold">Date & Time</p>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ \Carbon\Carbon::parse($event->start_time)->format('l, d M Y') }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase font-semibold">Venue</p>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ $event->location }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase font-semibold">Attendee</p>
                                        <p class="text-gray-900 dark:text-white font-medium">{{ $order->user->name ?? 'N/A' }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $order->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-3 p-4 rounded-2xl
                            @if($ticket->is_scanned) bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50
                            @elseif($order->status === 'completed') bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/50
                            @else bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 @endif">
                            @if($ticket->is_scanned)
                                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-amber-800 dark:text-amber-300">Already Scanned</p>
                                    <p class="text-xs text-amber-600 dark:text-amber-400">This ticket has been used.</p>
                                </div>
                            @elseif($order->status === 'completed')
                                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-green-800 dark:text-green-300">Valid Ticket</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">Present this QR code at the entrance.</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-700 dark:text-gray-200">{{ ucfirst($order->status) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Order status: {{ $order->status }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: QR Code -->
                    @if($order->status === 'completed')
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-white p-6 rounded-2xl shadow-inner border-2 border-gray-100 dark:border-gray-600">
                                <div id="qr-code-container"></div>
                            </div>
                            <p class="mt-4 text-xs text-gray-400 dark:text-gray-500 text-center font-mono tracking-wider select-all">
                                {{ $ticket->qr_code_hash }}
                            </p>
                            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 text-center">
                                Scan this code at the event entrance
                            </p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-600">
                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Ticket Locked</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Your QR code will be generated and displayed once the payment process is fully completed.</p>
                            @if($order->status === 'pending')
                                <a href="{{ route('payment.show', $order->id) }}" class="mt-6 inline-flex px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors">Complete Payment</a>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 dark:border-gray-700 px-8 py-5 bg-gray-50/50 dark:bg-gray-800/50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                    <div class="flex items-center gap-4">
                        <span>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <span>&bull;</span>
                        <span>Purchased {{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Verified by Tixxy
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
