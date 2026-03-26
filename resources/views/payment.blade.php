@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="/events" class="text-indigo-600 dark:text-indigo-400 flex items-center gap-2 font-medium hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Events
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-4">Review Your Order</h1>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri: Rincian Event (Lebih Besar) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row gap-8">
                            @if($event->image_path)
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full md:w-56 h-56 object-cover rounded-2xl shadow-md">
                            @else
                                <div class="w-full md:w-56 h-56 bg-gray-200 dark:bg-gray-700 rounded-2xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="flex-grow">
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-xs font-bold uppercase rounded-lg">Event Detail</span>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-3 mb-4">{{ $event->title }}</h2>
                                
                                <div class="space-y-3 text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center text-sm">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center mr-3 text-indigo-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('D, d M Y • H:i') }}
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center mr-3 text-indigo-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        {{ $event->location }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10 pt-8 border-t border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">About this event</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $event->description ?? 'No description available for this event.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Biaya (Lebih Kecil) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Standard Ticket</span>
                            <span class="font-medium text-gray-900 dark:text-white">${{ number_format($event->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Service Fee (5%)</span>
                            <span class="font-medium text-gray-900 dark:text-white">${{ number_format($event->price * 0.05, 2) }}</span>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</span>
                            <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($event->price * 1.05, 2) }}
                            </span>
                        </div>
                    </div>

                    <button class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                        Proceed to Payment
                    </button>
                    
                    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Secure checkout powered by Stripe
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection