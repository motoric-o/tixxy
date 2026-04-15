@extends('layouts.admin.default')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Ticket Scanner</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Scan attendee QR codes to validate entry.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Scanner Module -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <div id="reader" class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-900 aspect-square"></div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span id="status-text" class="text-sm font-medium text-gray-500 dark:text-gray-400 italic">Waiting for camera...</span>
                        <button id="switch-camera" class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-4 border border-indigo-100 dark:border-indigo-800/50">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-indigo-700 dark:text-indigo-300">
                        Hold the attendee's QR code steady in the center of the frame. The scanner will automatically detect and validate it.
                    </p>
                </div>
            </div>
        </div>

        <!-- Result Module -->
        <div id="result-card" class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 flex flex-col items-center justify-center text-center transition-all duration-300 min-h-[400px]">
            <div id="result-icon-container" class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 11v1m5-10v.5m0 9v.5M7 7v.5m0 9v.5M5 12h1m12 0h1M7 7l.354.354M7 17l.354-.354m10-10l-.354.354M17 17l-.354-.354"></path></svg>
            </div>
            <h2 id="result-title" class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ready to Scan</h2>
            <p id="result-message" class="text-gray-500 dark:text-gray-400 max-w-xs">Awaiting first scan to show validation results.</p>
            
            <div id="ticket-info" class="hidden mt-8 w-full space-y-3 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 text-left">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Attendee Name</span>
                    <p id="attendee-name" class="text-gray-900 dark:text-white font-bold text-lg"></p>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Event</span>
                    <p id="event-title" class="text-gray-600 dark:text-gray-300"></p>
                </div>
                <div>
                    <p id="timestamp" class="text-[10px] text-gray-400 mt-2 font-mono italic"></p>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultCard = document.getElementById('result-card');
        const resultIcon = document.getElementById('result-icon-container');
        const resultTitle = document.getElementById('result-title');
        const resultMessage = document.getElementById('result-message');
        const ticketInfo = document.getElementById('ticket-info');
        const statusText = document.getElementById('status-text');
        


        let isScanning = true;

        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (!isScanning) return;
            
            isScanning = false;
            statusText.innerText = "Processing...";
            
            // Stop scanning temporarily
            html5QrCode.pause();
            
            validateTicket(decodedText);
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .then(() => {
                statusText.innerText = "System Active";
            })
            .catch(err => {
                console.error(err);
                statusText.innerText = "Error accessing camera";
            });

        function validateTicket(hash) {
            fetch('{{ route("manage.scanner.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ hash: hash })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data);
                
                // Resume scanning after 3 seconds
                setTimeout(() => {
                    isScanning = true;
                    statusText.innerText = "System Active";
                    html5QrCode.resume();
                }, 3000);
            })
            .catch(error => {
                showResult({
                    status: 'error',
                    message: 'Connection failed or Invalid Code'
                });
                setTimeout(() => {
                    isScanning = true;
                    statusText.innerText = "System Active";
                    html5QrCode.resume();
                }, 3000);
            });
        }

        function showResult(data) {
            // Reset classes
            resultCard.className = "bg-white dark:bg-gray-800 rounded-3xl shadow-xl border p-8 flex flex-col items-center justify-center text-center transition-all duration-300 min-h-[400px]";
            resultIcon.className = "w-24 h-24 rounded-full flex items-center justify-center mb-6 transition-transform duration-300 scale-110";
            ticketInfo.classList.add('hidden');

            if (data.status === 'success') {
                resultCard.classList.add('border-green-500', 'bg-green-50/10');
                resultIcon.classList.add('bg-green-100', 'text-green-600', 'dark:bg-green-900/50');
                resultIcon.innerHTML = '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
                resultTitle.innerText = "Access Granted";
                resultTitle.className = "text-2xl font-bold text-green-600 mb-2";
                resultMessage.innerText = data.message;
                
                // Show Info
                ticketInfo.classList.remove('hidden');
                document.getElementById('attendee-name').innerText = data.attendee;
                document.getElementById('event-title').innerText = data.event;
                document.getElementById('timestamp').innerText = "Validated at " + new Date().toLocaleTimeString();
            } 
            else if (data.status === 'warning') {
                resultCard.classList.add('border-amber-500', 'bg-amber-50/10');
                resultIcon.classList.add('bg-amber-100', 'text-amber-600', 'dark:bg-amber-900/50');
                resultIcon.innerHTML = '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
                resultTitle.innerText = "Duplicate Scan";
                resultTitle.className = "text-2xl font-bold text-amber-600 mb-2";
                resultMessage.innerText = data.message;
            }
            else {
                resultCard.classList.add('border-red-500', 'bg-red-50/10');
                resultIcon.classList.add('bg-red-100', 'text-red-600', 'dark:bg-red-900/50');
                resultIcon.innerHTML = '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>';
                resultTitle.innerText = "Access Denied";
                resultTitle.className = "text-2xl font-bold text-red-600 mb-2";
                resultMessage.innerText = data.message;
            }
        }
    });
</script>
@endsection
