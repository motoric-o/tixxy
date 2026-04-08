@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                My Tickets
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400">
                View your purchased tickets and events.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tickets as $ticket)
                {{-- Mengambil data event melalui relasi: Ticket -> Order -> Event --}}
                @php
                    $event = $ticket->order->event ?? null;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 flex flex-col h-full">
                    <div class="p-6 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            @if ($ticket->order->status == "completed")
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    Purchased
                                </span>
                            @elseif ($ticket->order->status == "pending")
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                    Pending
                                </span>
                            @elseif ($ticket->order->status == "canceled")
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                    Cancelled
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($event->start_time ?? now())->format('d M Y')}}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $event->title ?? 'Event Name Unavailable' }}
                        </h3>

                        <div class="space-y-2 mb-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($event->start_time ?? now())->format('H:i') }}
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $event->location ?? 'TBA' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end border-t border-gray-100 mt-auto dark:bg-gray-800 dark:border-gray-700">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            View Ticket
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets found</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't purchased any tickets yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection