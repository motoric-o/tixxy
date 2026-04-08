@extends('layouts.default')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-4">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        </div>

        @php
            // Gracefully handle older seeded data where expired_at might be null
            $expiryDate = $order->expired_at ?? $order->created_at->addHour();
            $isCanceled = $order->status === 'canceled' || (now()->greaterThanOrEqualTo($expiryDate) && $order->status === 'pending');
            $isAwaitingConfirmation = $order->status === 'pending' && $order->payment_proof;
        @endphp

        <!-- Cancelled State -->
        @if($isCanceled)
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-3xl p-8 text-center shadow-sm">
                <div class="w-20 h-20 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500 dark:text-red-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Order Canceled</h2>
                <p class="text-gray-600 dark:text-gray-400">This order has been canceled either because the payment window expired or it was manually canceled.</p>
                <a href="{{ route('events.index') }}" class="inline-block mt-6 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-colors">
                    Browse Other Events
                </a>
            </div>

        <!-- Awaiting Confirmation State -->
        @elseif($isAwaitingConfirmation)
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-3xl p-8 text-center shadow-sm">
                <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-800 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-500 dark:text-indigo-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Waiting for Confirmation</h2>
                <p class="text-gray-600 dark:text-gray-400">Your payment proof has been successfully uploaded and is waiting to be verified by the organizer. You will be notified once it's confirmed!</p>
                <a href="/tickets" class="inline-block mt-6 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-colors">
                    Go to My Tickets
                </a>
            </div>

        <!-- Active Payment State -->
        @elseif($order->status === 'completed')
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-3xl p-8 text-center shadow-sm">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500 dark:text-green-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Payment Confirmed!</h2>
                <p class="text-gray-600 dark:text-gray-400">Thank you! Your payment was successful and your tickets are ready.</p>
                <a href="/tickets" class="inline-block mt-6 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-lg hover:bg-gray-800 transition-colors">
                    View My E-Tickets
                </a>
            </div>

        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Instruksi Pembayaran -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Payment Instructions</h2>
                        
                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/50 rounded-2xl p-6 mb-8 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-orange-600 dark:text-orange-400 font-semibold mb-1">Time remaining to pay:</p>
                                <p id="countdown" class="text-3xl font-extrabold text-orange-700 dark:text-orange-300 tracking-tight">00:00:00</p>
                            </div>
                            <svg class="w-12 h-12 text-orange-300 dark:text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transfer to Bank Account</h3>
                            <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900/50">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">BCA - Bank Central Asia</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-2xl font-mono font-bold text-gray-900 dark:text-white tracking-widest">8812 3456 7890</p>
                                    <button onclick="navigator.clipboard.writeText('881234567890'); alert('Copied!')" class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold hover:underline bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-lg">Copy</button>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Account Name: PT Tixxy Event Indonesia</p>
                            </div>
                            
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 list-disc list-inside mt-4">
                                <li>Transfer the exact total amount before the timer runs out.</li>
                                <li>Take a clear screenshot or photo of your transfer receipt.</li>
                                <li>Upload the proof of payment on the right section.</li>
                                <li>Our team will manually confirm your payment within 24 hours.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Rincian Order + Upload -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h3>
                        
                        <div class="flex items-center mb-6 border-b border-gray-100 dark:border-gray-700 pb-6">
                            @if($order->event->image_path)
                                <img src="{{ asset('storage/' . $order->event->image_path) }}" alt="Event" class="w-16 h-16 rounded-xl object-cover mr-4">
                            @endif
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $order->event->title }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->event->start_time)->format('D, d M Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Total Amount</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($order->amount, 2) }}</span>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">To Pay</span>
                                <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                                    ${{ number_format($order->amount, 2) }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Proof of Payment</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl relative hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors overflow-hidden group">
                                    <img id="image-preview" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover z-0 transition-opacity duration-300 opacity-50 group-hover:opacity-20" />
                                    
                                    <div id="upload-container" class="space-y-1 text-center relative z-10 p-4 transition-all duration-300">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                            <label for="proof" class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                                                <span id="upload-label-text" class="px-2 py-1 bg-white dark:bg-gray-800 rounded-md shadow-sm">Upload a file</span>
                                                <input id="proof" name="payment_proof" type="file" class="sr-only" required accept="image/*" onchange="previewImage(event)">
                                            </label>
                                        </div>
                                        <p id="upload-hint" class="text-xs text-gray-500 bg-white/80 dark:bg-gray-800/80 px-2 py-1 rounded inline-block">PNG, JPG, up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function previewImage(event) {
                                    var reader = new FileReader();
                                    reader.onload = function() {
                                        var output = document.getElementById('image-preview');
                                        output.src = reader.result;
                                        output.classList.remove('hidden');
                                        document.getElementById('upload-label-text').innerText = 'Change Image';
                                        document.getElementById('upload-hint').style.display = 'none';
                                        document.getElementById('upload-container').classList.add('bg-white/80', 'dark:bg-gray-900/80', 'backdrop-blur-sm', 'rounded-xl', 'shadow-sm');
                                    };
                                    if(event.target.files[0]) {
                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                }
                            </script>
                            
                            <button type="submit" class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                                Submit Proof of Payment
                            </button>
                        </form>
                        
                    </div>
                </div>
            </div>

            <!-- Countdown Timer Script -->
            <script>
                const expiredAtStr = "{{ $expiryDate->toIso8601String() }}";
                const countDownDate = new Date(expiredAtStr).getTime();

                const x = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = countDownDate - now;

                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("countdown").innerHTML = "EXPIRED";
                        // Reload to show the canceled layout
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("countdown").innerHTML = 
                        String(hours).padStart(2, '0') + "h " + 
                        String(minutes).padStart(2, '0') + "m " + 
                        String(seconds).padStart(2, '0') + "s";
                }, 1000);
            </script>
        @endif

    </div>
</div>

<script>
    // Prevent the user from navigating back and forth with the browser Back button
    history.pushState(null, null, window.location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, window.location.href);
    });
</script>
@endsection