@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<div class="min-h-screen bg-gray-50 py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden flex items-center justify-center">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400/10 rounded-full -translate-x-1/2 translate-y-1/2 blur-3xl"></div>

    <div class="max-w-2xl w-full relative z-10">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Cek Status Registrasi</h1>
            <p class="mt-3 text-gray-500 font-medium">Pantau terus perkembangan berkas pendaftaran Anda</p>
        </div>

        @if(!isset($registration))
        <!-- State 1: Search Form -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-xl p-8 md:p-12 border border-white">
            
            @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start space-x-3 text-red-600">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('pmb.status.check') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Registrasi PMB</label>
                    <input type="text" name="registration_code" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-lg font-bold tracking-wider placeholder-gray-300" placeholder="PMB-202X-XXX" value="{{ old('registration_code') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Induk Kependudukan (NIK)</label>
                    <input type="number" name="nik" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-lg font-bold tracking-wider placeholder-gray-300" placeholder="Sesuai pengisian awal..." value="{{ old('nik') }}">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl shadow-xl shadow-primary/20 transition-all text-lg flex items-center justify-center space-x-2">
                        <span>Cari Data Saya</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
        @else
        <!-- State 2: Status Result -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-xl p-8 md:p-12 border border-white text-center">
            
            @if($registration->status == 'pending')
            <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">Sedang Diperiksa</h2>
            <p class="text-gray-500 font-medium mb-8">Berkas Anda sedang dalam antrean pengecekan oleh Tim Admisi kami.</p>

            @elseif($registration->status == 'verified')
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-blue-500 animate-[spin_3s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">Terverifikasi (Tahap Penilaian)</h2>
            <p class="text-gray-500 font-medium mb-8">Data dan pembayaran Anda telah divalidasi. Keputusan akhir kelulusan akan segera diumumkan.</p>

            @elseif($registration->status == 'rejected')
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">Mohon Maaf, Ditolak</h2>
            <p class="text-gray-500 font-medium mb-6">Pendaftaran Anda tidak dapat kami proses saat ini.</p>
            @if($registration->admin_notes)
            <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl text-sm italic mb-8 max-w-md mx-auto text-left">
                <strong>Catatan Admin:</strong> {{ $registration->admin_notes }}
            </div>
            @endif

            @elseif($registration->status == 'accepted')
            <div class="relative">
                <!-- Confetti animation background could be added here, but static looks clean too -->
                <div class="w-28 h-28 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_40px_rgba(52,211,153,0.4)]">
                    <svg class="w-14 h-14 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                <div class="absolute top-10 -left-6 w-5 h-5 bg-blue-400 rounded-full animate-pulse"></div>
            </div>

            <h2 class="text-3xl font-black text-gray-900 mb-2">SELAMAT ANDA LULUS!</h2>
            <p class="text-gray-500 font-medium mb-8">Alhamdulillah, Anda telah resmi diterima sebagai Mahasiswa Baru Matla Islamic University.</p>

            <a href="{{ route('pmb.loa', $registration->registration_code) }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl shadow-xl shadow-primary/20 transition-all text-lg mb-4">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Cetak Surat Kelulusan (LoA)
            </a>
            @endif

            <div class="mt-8 pt-8 border-t border-gray-100">
                <a href="{{ route('pmb.status') }}" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors">← Kembali ke Form Pencarian</a>
            </div>
            
        </div>
        @endif

    </div>
</div>
@endsection
