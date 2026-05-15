@extends('layouts.app')

@section('title', 'KTM Digital - ' . $user->name)

@php
    function getBase64Image($url) {
        try {
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);
            $image = @file_get_contents($url, false, $context);
            if ($image) {
                return 'data:image/png;base64,' . base64_encode($image);
            }
        } catch (\Exception $e) {}
        return $url;
    }

    // Logo
    $logoPath = public_path('assets/logo.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : asset('assets/logo.png');
    
    // Avatar
    $fotoUrl = $user->foto_profil;
    $fotoBase64 = $fotoUrl;
    if (str_starts_with($fotoUrl, 'http') && str_contains($fotoUrl, 'ui-avatars.com')) {
        $fotoBase64 = getBase64Image($fotoUrl);
    } elseif ($user->profil && $user->profil->foto) {
        $path = storage_path('app/public/' . $user->profil->foto);
        if(file_exists($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $fotoBase64 = 'data:image/' . ($ext ?: 'jpeg') . ';base64,' . base64_encode(file_get_contents($path));
        }
    }

    // QR Code
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($user->qr_url) . "&color=111827";
    $qrBase64 = getBase64Image($qrUrl);
@endphp

@section('content')
<div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 lg:px-12 max-w-4xl">
        <div class="mb-6 flex justify-between items-center no-print">
            @php
                $isMahasiswa = Auth::user()->role === 'mahasiswa';
                $backUrl = $isMahasiswa ? route('backend.mahasiswa.dashboard') : route('backend.admin.mahasiswa');
                $backText = $isMahasiswa ? 'Kembali ke Dashboard' : 'Kembali ke Manajemen Mahasiswa';
            @endphp
            <a href="{{ $backUrl }}" class="text-gray-500 hover:text-primary font-bold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>{{ $backText }}</span>
            </a>
            <button id="downloadKtmBtn" class="px-6 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark">Unduh KTM (Gambar)</button>
        </div>

        <!-- KTM CONTAINER (FRONT & BACK) -->
        <div id="ktm-container" class="flex flex-col md:flex-row gap-6 lg:gap-10 justify-center items-center print-card py-4">
            
            <!-- FRONT CARD -->
            <div class="relative overflow-hidden bg-white w-full shadow-2xl rounded-[1rem] border border-gray-200 flex-shrink-0" style="width: 324px; height: 516px; font-family: 'Inter', sans-serif;">
                <!-- Top Green Angled Shape -->
                <svg class="absolute top-0 left-0 w-full h-[190px]" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon points="0,0 100,0 100,65 0,100" fill="#3EB521" />
                </svg>
                
                <!-- Logo & Title -->
                <div class="relative z-10 flex items-center justify-between p-5">
                    <img src="{{ $logoBase64 }}" class="h-16 w-auto bg-white/90 p-2 rounded-xl shadow-sm border border-white/50" alt="Logo">
                    <div class="text-right text-white">
                        <h2 class="font-extrabold text-[13px] leading-tight tracking-wide">MATLA ISLAMIC</h2>
                        <h2 class="font-extrabold text-[13px] leading-tight tracking-wide">ACADEMY</h2>
                    </div>
                </div>

                <!-- Photo -->
                <div class="relative z-10 flex justify-center mt-3">
                    <div class="p-1 bg-white rounded-2xl shadow-lg border border-gray-100">
                        <img src="{{ $fotoBase64 }}" class="w-32 h-40 object-cover rounded-xl border-2 border-white" alt="Foto">
                    </div>
                </div>

                <!-- Details -->
                <div class="relative z-10 text-center mt-5 px-5">
                    <h3 class="text-xl font-black text-[#3EB521] uppercase tracking-tight leading-none mb-1">{{ $user->name }}</h3>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-5">{{ $user->education['program_studi'] ?? 'Program Studi' }}</p>
                    
                    <div class="text-left bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                        <table class="w-full text-[11px] text-gray-800 font-medium">
                            <tr>
                                <td class="pb-1.5 w-14 font-bold text-[#3EB521]">ID / NIM</td>
                                <td class="pb-1.5 px-2 text-gray-400">:</td>
                                <td class="pb-1.5 font-bold tracking-wider">{{ $user->nim ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="pb-1.5 font-bold text-[#3EB521]">TTL</td>
                                <td class="pb-1.5 px-2 text-gray-400">:</td>
                                <td class="pb-1.5">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold text-[#3EB521]">Email</td>
                                <td class="px-2 text-gray-400">:</td>
                                <td class="truncate max-w-[140px] inline-block align-bottom">{{ $user->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BACK CARD -->
            <div class="relative overflow-hidden bg-white w-full shadow-2xl rounded-[1rem] border border-gray-200 flex-shrink-0" style="width: 324px; height: 516px; font-family: 'Inter', sans-serif;">
                <!-- Top small angled shape -->
                <svg class="absolute top-0 right-0 w-40 h-40 opacity-10" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon points="100,0 0,0 100,100" fill="#3EB521" />
                </svg>

                <!-- Bottom Green Angled Shape -->
                <svg class="absolute bottom-0 left-0 w-full h-[115px]" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <polygon points="0,20 100,0 100,100 0,100" fill="#3EB521" />
                </svg>

                <!-- Header -->
                <div class="relative z-10 text-center pt-8">
                    <img src="{{ $logoBase64 }}" class="h-14 w-auto mx-auto mb-3" alt="Logo">
                    <h2 class="font-extrabold text-sm text-[#3EB521] uppercase tracking-wider">Matla Islamic Academy</h2>
                    <p class="text-[9px] text-gray-500 font-bold tracking-widest uppercase mt-1">Kartu Identitas Resmi</p>
                </div>

                <!-- Ketentuan -->
                <div class="relative z-10 px-7 mt-6">
                    <h4 class="text-[#3EB521] text-[11px] font-extrabold mb-2 uppercase">Ketentuan Kartu :</h4>
                    <ul class="text-[9px] text-gray-600 space-y-2 list-disc pl-3 text-justify leading-relaxed font-medium pr-1">
                        <li>Kartu ini adalah identitas resmi mahasiswa Matla Islamic Academy dan harus ditunjukkan saat diperlukan.</li>
                        <li>Kartu ini tidak dapat dipindahtangankan kepada orang lain dengan alasan apapun.</li>
                        <li>Jika kartu ini hilang atau rusak, segera hubungi bagian administrasi akademik kampus.</li>
                        <li class="font-bold text-[#3EB521]">Bila menemukan kartu ini, harap hubungi pihak kampus atau kembalikan ke alamat resmi Matla Islamic Academy.</li>
                    </ul>
                </div>

                <!-- Footer info -->
                <div class="absolute bottom-5 left-0 w-full px-6 flex justify-between items-end z-10">
                    <div class="text-white text-[9px] font-medium leading-relaxed pb-1">
                        <p class="mb-1"><span class="font-bold text-white/70 uppercase text-[8px] block mb-0.5">Diterbitkan</span> {{ now()->format('d/m/Y') }}</p>
                        <p><span class="font-bold text-white/70 uppercase text-[8px] block mb-0.5">Berlaku s.d</span> Selama Menjadi Mahasiswa</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="text-right text-white/90 text-[7px] font-bold uppercase leading-tight tracking-wider">
                            Scan QR untuk<br>melihat profil<br>& portofolio
                        </div>
                        <div class="bg-white p-1.5 rounded-lg shadow-lg">
                            <img src="{{ $qrBase64 }}" alt="QR Code" class="w-14 h-14">
                        </div>
                    </div>
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

<!-- html-to-image library (modern replacement for html2canvas) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>
<script>
    document.getElementById('downloadKtmBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerText;
        btn.innerText = 'Memproses...';
        btn.disabled = true;

        const card = document.querySelector('.print-card');

        // Capture the card as PNG
        htmlToImage.toPng(card, {
            pixelRatio: 3, // High resolution
            backgroundColor: null
        }).then(function (dataUrl) {
            // Create a download link
            const link = document.createElement('a');
            link.download = 'KTM_{{ $user->nim ?? $user->name }}.png';
            link.href = dataUrl;
            link.click();

            // Restore button
            btn.innerText = originalText;
            btn.disabled = false;
        }).catch(function (err) {
            console.error('Error generating KTM:', err);
            alert('Terjadi kesalahan: ' + (err.message || err));
            btn.innerText = originalText;
            btn.disabled = false;
        });
    });
</script>
@endsection
