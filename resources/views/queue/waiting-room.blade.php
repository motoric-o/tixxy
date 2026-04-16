@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12" x-data="queueWaiting()" x-init="startPolling()">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back Link / Cancel Queue --}}
        <form action="{{ route('queue.cancel', $event->id) }}" method="POST" class="mb-8">
            @csrf
            <button type="submit"
                class="text-indigo-600 dark:text-indigo-400 flex items-center gap-2 font-medium hover:underline transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Leave Queue & Back to Events
            </button>
        </form>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 p-8 text-center">
                <h1 class="text-2xl font-bold text-white mb-2">{{ $event->title }}</h1>
                <div class="flex items-center justify-center gap-4 text-indigo-100 text-sm">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('D, M d Y') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $event->location }}
                    </span>
                </div>
            </div>

            {{-- Status Content --}}
            <div class="p-8 text-center">

                {{-- Flash Messages --}}
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl text-blue-700 dark:text-blue-300 text-sm">
                        {{ session('info') }}
                    </div>
                @endif

                {{-- QUEUED / WAITLISTED Status --}}
                <template x-if="status === 'queued' || status === 'waitlisted'">
                    <div>
                        {{-- Animated Pulse Icon --}}
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                             :class="status === 'queued' ? 'bg-indigo-100 dark:bg-indigo-900/30' : 'bg-amber-100 dark:bg-amber-900/30'">
                            <div class="w-12 h-12 rounded-full animate-pulse flex items-center justify-center"
                                 :class="status === 'queued' ? 'bg-indigo-200 dark:bg-indigo-800/50' : 'bg-amber-200 dark:bg-amber-800/50'">
                                <svg class="w-6 h-6"
                                     :class="status === 'queued' ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                            x-text="status === 'queued' ? 'You\'re in the Queue' : 'You\'re on the Waitlist'"></h2>

                        <p class="text-gray-500 dark:text-gray-400 mb-6"
                           x-text="status === 'queued'
                               ? 'Please keep this page open. You will be redirected automatically when it\'s your turn.'
                               : 'All tickets are currently reserved. If one becomes available, you will be notified via email.'">
                        </p>

                        {{-- Position Badge --}}
                        <div class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 mb-6">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Your position:</span>
                            <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400" x-text="position ?? '—'"></span>
                        </div>

                        {{-- Progress dots animation --}}
                        <div class="flex justify-center gap-1.5 mb-8">
                            <div class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </template>

                {{-- NOTIFIED Status (from waitlist, hasn't claimed yet) --}}
                <template x-if="status === 'notified'">
                    <div>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">A Ticket is Available!</h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Claim your spot now before time runs out. You have 15 minutes to complete checkout once claimed.</p>

                        {{-- Countdown Timer --}}
                        <template x-if="countdown">
                            <div class="mb-6">
                                <div class="inline-flex flex-col items-center gap-1 px-8 py-4 rounded-2xl"
                                     :class="countdown <= '1:00' ? 'bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-700' : 'bg-amber-50 dark:bg-amber-900/20 border-2 border-amber-200 dark:border-amber-700'">
                                    <span class="text-xs font-bold uppercase tracking-widest"
                                          :class="countdown <= '1:00' ? 'text-red-500 dark:text-red-400' : 'text-amber-500 dark:text-amber-400'">
                                        Time to Claim
                                    </span>
                                    <span class="text-4xl font-black tabular-nums"
                                          :class="countdown <= '1:00' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400'"
                                          x-text="countdown"></span>
                                </div>
                            </div>
                        </template>

                        <a href="/queue/claim/{{ $event->id }}"
                           class="inline-flex px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            Claim Your Ticket →
                        </a>
                    </div>
                </template>

                {{-- EXPIRED Status --}}
                <template x-if="status === 'expired'">
                    <div>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Session Expired</h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Your checkout window has expired. You can rejoin the queue to try again.</p>
                        <form action="{{ route('queue.join', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                Rejoin Queue
                            </button>
                        </form>
                    </div>
                </template>

                {{-- CANCELED Status --}}
                <template x-if="status === 'canceled'">
                    <div>
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">You Left the Queue</h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">You voluntarily left the queue. You can rejoin if you'd like.</p>
                        <form action="{{ route('queue.join', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                Rejoin Queue
                            </button>
                        </form>
                    </div>
                </template>



            </div>
        </div>
    </div>
</div>

<script>
function queueWaiting() {
    return {
        status: '{{ $queueEntry->status }}',
        position: {{ $position ?? 'null' }},
        expiresAt: @if($queueEntry->expires_at) '{{ $queueEntry->expires_at->toIso8601String() }}' @else null @endif,
        pollInterval: null,
        countdownInterval: null,
        countdown: '',

        startPolling() {
            // Poll every 5 seconds
            this.pollInterval = setInterval(() => this.checkStatus(), 5000);
            // Update countdown every second
            this.countdownInterval = setInterval(() => this.tickCountdown(), 1000);
            this.tickCountdown();
        },

        tickCountdown() {
            if (!this.expiresAt) {
                this.countdown = '';
                return;
            }
            const diff = Math.max(0, Math.floor((new Date(this.expiresAt) - Date.now()) / 1000));
            if (diff === 0) {
                this.countdown = '0:00';
                return;
            }
            const mins = Math.floor(diff / 60);
            const secs = String(diff % 60).padStart(2, '0');
            this.countdown = `${mins}:${secs}`;
        },

        async checkStatus() {
            try {
                const response = await fetch('/api/queue/status/{{ $event->id }}');
                if (!response.ok) return;

                const data = await response.json();
                this.status = data.status;
                this.position = data.position;
                this.expiresAt = data.expires_at;

                // Auto-redirect if the user has been activated
                if (data.status === 'active') {
                    clearInterval(this.pollInterval);
                    clearInterval(this.countdownInterval);
                    window.location.href = '/checkout?event_id={{ $event->id }}';
                }

                // Stop polling if the queue entry has reached a terminal state
                if (['purchased', 'expired', 'canceled'].includes(data.status)) {
                    clearInterval(this.pollInterval);
                    clearInterval(this.countdownInterval);
                }
            } catch (e) {
                // Silently ignore network errors — will retry on next poll
            }
        }
    };
}
</script>
@endsection
