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

            @php
                $waLink = $registration->registration_type == 'idad' 
                    ? 'https://chat.whatsapp.com/LGKVsMypiFIKlElUw1Zs9s' 
                    : 'https://chat.whatsapp.com/Bhza652IzShIJSNC0z83gk';
            @endphp

            <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 mb-8">
                <p class="text-sm font-bold text-emerald-800 mb-3">Langkah Selanjutnya:</p>
                <p class="text-xs text-emerald-700 mb-4 leading-relaxed">
                    Silakan bergabung ke grup WhatsApp resmi calon mahasiswa untuk mendapatkan informasi jadwal test dan pengumuman selanjutnya.
                </p>
                <a href="{{ $waLink }}" target="_blank" class="flex items-center justify-center space-x-2 w-full py-3 bg-[#25D366] hover:bg-[#20BA5A] text-white rounded-xl font-bold transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                    <span>Gabung Grup WhatsApp</span>
                </a>
            </div>

            <div class="space-y-4">
                <a href="{{ url('/') }}" class="block w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
