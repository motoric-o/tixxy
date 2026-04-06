@extends('layouts.admin.default')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Scan Ticket QR</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Arahkan kamera ke kode QR tiket pengunjung untuk melakukan verifikasi dan check-in otomatis.
    </p>
</div>

<div class="max-w-2xl mx-auto" x-data="qrScanner()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- Area Preview Kamera --}}
        <div class="relative bg-black aspect-square md:aspect-video flex items-center justify-center overflow-auto border-b border-gray-200 dark:border-gray-700">
            <div id="reader" class="w-full h-full"></div>
            
            {{-- Overlay saat scanner tidak aktif --}}
            <template x-if="!isScanning">
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/90 text-white p-6 text-center z-10">
                    <div class="p-4 rounded-full bg-white/10 mb-4">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold">Siap Memindai</h3>
                    <p class="text-sm text-gray-400 mb-6">Izin akses kamera diperlukan untuk memproses tiket</p>
                    <button @click="startScanning()" class="px-8 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-r from-[#4a00e0] to-[#8e2de2] shadow-lg shadow-purple-500/20 hover:scale-105 transition-all duration-300">
                        Aktifkan Kamera
                    </button>
                </div>
            </template>
        </div>

        {{-- Feedback Status & Riwayat --}}
        <div class="p-6">
            <div x-show="message" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 :class="messageType === 'success' ? 'bg-green-500/10 border-green-500/20 text-green-600 dark:text-green-400' : 'bg-red-500/10 border-red-500/20 text-red-600 dark:text-red-400'"
                 class="mb-6 p-4 rounded-xl border flex items-center gap-3">
                <div :class="messageType === 'success' ? 'bg-green-500' : 'bg-red-500'" class="p-1 rounded-full text-white">
                    <template x-if="messageType === 'success'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </template>
                    <template x-if="messageType === 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </template>
                </div>
                <span x-text="message" class="font-semibold text-sm"></span>
            </div>

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 dark:text-white">Aktivitas Terakhir</h3>
                <button @click="stopScanning()" x-show="isScanning" class="text-xs text-red-400 hover:text-red-500 transition-colors font-medium">
                    Matikan Kamera
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(log, index) in history" :key="index">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-600/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="log.name"></span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold" x-text="log.time"></span>
                        </div>
                        <span class="text-[11px] px-2.5 py-1 rounded-lg bg-green-500/10 text-green-500 font-bold border border-green-500/20">VALID</span>
                    </div>
                </template>
                <template x-if="history.length === 0">
                    <div class="text-center py-10">
                        <p class="text-sm text-gray-400 dark:text-gray-500">Menunggu pemindaian pertama...</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- Library html5-qrcode dari CDN --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qrScanner', () => ({
            isScanning: false,
            html5QrCode: null,
            message: '',
            messageType: '',
            history: [],

            async startScanning() {
                this.isScanning = true;
                this.message = '';
                
                if (!this.html5QrCode) {
                    this.html5QrCode = new Html5Qrcode("reader");
                }

                const config = { fps: 15, qrbox: { width: 250, height: 250 } };

                try {
                    await this.html5QrCode.start(
                        { facingMode: "environment" }, 
                        config, 
                        (decodedText) => this.processScan(decodedText)
                    );
                } catch (err) {
                    this.isScanning = false;
                    this.displayFeedback("Gagal mengakses kamera: Pastikan izin diberikan.", "error");
                }
            },

            async stopScanning() {
                if (this.html5QrCode && this.isScanning) {
                    await this.html5QrCode.stop();
                    this.isScanning = false;
                }
            },

            async processScan(qrHash) {
                // Hentikan scanner sementara agar tidak terjadi double request saat memproses
                await this.stopScanning();
                
                try {
                    const response = await fetch(`/manage/tickets/scan`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ qr_code_hash: qrHash })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        this.displayFeedback(result.message || "Tiket Berhasil Diverifikasi", "success");
                        this.history.unshift({
                            name: result.user_name || "Pemilik Tiket",
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                        if (this.history.length > 5) this.history.pop();
                    } else {
                        this.displayFeedback(result.message || "Tiket Tidak Valid / Sudah Digunakan", "error");
                    }
                } catch (error) {
                    this.displayFeedback("Terjadi kesalahan koneksi ke server.", "error");
                }

                // Jalankan kembali scanner setelah jeda feedback (3 detik)
                setTimeout(() => this.startScanning(), 3000);
            },

            displayFeedback(text, type) {
                this.message = text;
                this.messageType = type;
            }
        }));
    });
</script>
@endsection