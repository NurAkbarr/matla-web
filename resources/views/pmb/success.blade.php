@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400/10 rounded-full -translate-x-1/2 translate-y-1/2 blur-3xl"></div>

    <div class="max-w-lg w-full relative z-10">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl p-10 md:p-14 text-center border border-white">
            
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-8 animate-[bounce_1s_ease-in-out_2]">
                <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-4 tracking-tight">Pendaftaran Berhasil!</h1>
            <p class="text-gray-600 mb-8 leading-relaxed">
                Alhamdulillah, berkas pendaftaran Anda telah kami terima. Tim kami akan memverifikasi data dan mutasi pembayaran Anda dalam waktu 2x24 jam kerja.
            </p>

            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-8 inline-block select-all">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor Registrasi Anda</p>
                <p class="text-3xl font-black text-primary tracking-wider">{{ $registration->registration_code }}</p>
            </div>

            <p class="text-xs text-gray-400 mb-8 italic">Simpan nomor registrasi ini. Anda dapat menggunakannya untuk mengecek status lulus/tidak secara berkala.</p>

            <div class="space-y-4">
                <a href="{{ url('/') }}" class="block w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
