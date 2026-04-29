@extends('layouts.app')

@section('title', 'KTM Digital - ' . $user->name)

@section('content')
<div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 lg:px-12 max-w-4xl">
        <div class="mb-6 flex justify-between items-center no-print">
            <a href="{{ route('backend.mahasiswa.dashboard') }}" class="text-gray-500 hover:text-primary font-bold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Dashboard</span>
            </a>
            <button id="downloadKtmBtn" class="px-6 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark">Unduh KTM (Gambar)</button>
        </div>

        <!-- KTM CARD -->
        <div class="bg-white rounded-[1.5rem] shadow-2xl border border-gray-100 overflow-hidden relative mx-auto print-card flex flex-col" style="max-width: 380px; min-height: 540px;">
            
            <!-- Background Ornaments -->
            <div class="absolute top-10 -right-20 w-64 h-64 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60"></div>
            <div class="absolute bottom-20 -left-20 w-64 h-64 bg-amber-50 rounded-full mix-blend-multiply filter blur-3xl opacity-80"></div>
            
            <!-- Header KTM -->
            <div class="bg-primary text-white p-5 relative z-10 border-b border-emerald-400/30">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-extrabold text-lg tracking-tight leading-none mb-1">KARTU MAHASISWA</h2>
                        <p class="text-[9px] font-bold text-emerald-100 uppercase tracking-widest">Matla Islamic University</p>
                    </div>
                    <img src="{{ asset('assets/logo.png') }}" class="h-10 w-auto bg-white/10 rounded-lg p-1.5 backdrop-blur-sm" alt="Logo">
                </div>
            </div>

            <!-- Body KTM -->
            <div class="p-6 relative z-10 flex-grow flex flex-col justify-center">
                <div class="flex justify-center mb-6 relative">
                    <!-- Photo frame effect -->
                    <div class="absolute inset-0 border-2 border-emerald-500/20 transform rotate-3 rounded-2xl w-32 mx-auto h-40"></div>
                    <img src="{{ $user->foto_profil }}" class="w-32 h-40 object-cover rounded-2xl border-4 border-white shadow-xl relative z-10" alt="Foto">
                </div>
                
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black text-gray-900 uppercase leading-none tracking-tight">{{ $user->name }}</h3>
                    <p class="text-base font-bold text-primary mt-2 tracking-widest">{{ $user->nim ?? 'NIM Belum Diatur' }}</p>
                </div>

                <div class="space-y-3 text-xs bg-white/60 backdrop-blur-md p-4 rounded-xl border border-white shadow-sm">
                    <div class="flex justify-between items-center border-b border-gray-200/50 pb-2">
                        <span class="text-gray-500 font-bold uppercase text-[9px] tracking-wider">Program Studi</span>
                        <span class="font-black text-gray-900 text-right">{{ $user->education['program_studi'] ?? 'Belum Diatur' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-200/50 pb-2">
                        <span class="text-gray-500 font-bold uppercase text-[9px] tracking-wider">Angkatan</span>
                        <span class="font-black text-gray-900 text-right">{{ $user->angkatan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 font-bold uppercase text-[9px] tracking-wider">Status</span>
                        <span class="font-black text-emerald-600 uppercase">{{ $user->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer / QR -->
            <div class="bg-gray-900 text-white p-5 flex items-center justify-between relative z-10">
                <div class="text-[9px] leading-relaxed text-gray-400 max-w-[160px] font-medium">
                    Kartu ini sah dan terverifikasi secara elektronik. Scan QR Code untuk melihat detail profil.
                </div>
                <div class="bg-white p-1.5 rounded-lg shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($user->qr_url) }}&color=111827" alt="QR Code" class="w-14 h-14">
                </div>
            </div>
        </div>

    </div>
</div>
<style>
@media print {
    body * { visibility: hidden; }
    .print-card, .print-card * { visibility: visible; }
    .print-card {
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        box-shadow: none !important;
    }
    .no-print { display: none !important; }
}
</style>

<!-- html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('downloadKtmBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerText;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        const card = document.querySelector('.print-card');

        // Capture the card as canvas
        html2canvas(card, {
            scale: 3, // High resolution
            useCORS: true, // Allow cross-origin images (like the UI-avatars or QR code)
            backgroundColor: null // transparent background
        }).then(canvas => {
            // Convert to image URL
            const imgData = canvas.toDataURL('image/png');
            
            // Create a fake download link
            const link = document.createElement('a');
            link.download = 'KTM_{{ $user->nim ?? $user->name }}.png';
            link.href = imgData;
            link.click();

            // Restore button
            btn.innerText = originalText;
            btn.disabled = false;
        }).catch(err => {
            console.error('Error generating KTM:', err);
            alert('Terjadi kesalahan saat mengunduh KTM. Pastikan koneksi stabil.');
            btn.innerText = originalText;
            btn.disabled = false;
        });
    });
</script>
@endsection
