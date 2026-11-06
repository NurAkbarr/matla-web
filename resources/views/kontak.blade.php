@extends('layouts.app')

@section('title', 'Hubungi Kami - Matla Islamic University')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[380px] lg:min-h-[420px] flex items-center overflow-hidden bg-[#006837]" style="background-image: url('{{ asset('assets/doodle-bg.svg') }}'); background-repeat: repeat;">
    <div class="container mx-auto px-4 lg:px-12 relative z-10 text-center text-white py-16 lg:py-24">
        <!-- Badge -->
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-6">
            <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
            <span class="text-white text-xs md:text-sm font-semibold tracking-wide">Layanan Informasi Kampus</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight font-display">Hubungi Kami</h1>
        <p class="text-base md:text-xl text-emerald-50/90 max-w-2xl mx-auto leading-relaxed">
            Punya pertanyaan atau butuh informasi lebih lanjut? Silakan hubungi kami langsung melalui saluran komunikasi resmi MATLA di bawah ini.
        </p>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="py-16 md:py-24 bg-white -mt-10 lg:-mt-16 relative z-20">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
            <!-- Email Card -->
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl shadow-gray-100/80 border border-gray-100 flex flex-col items-center text-center group hover:border-[#006837] hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#006837] group-hover:text-white transition-all text-[#006837] shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">Email Resmi</h3>
                <p class="text-gray-900 font-bold text-base md:text-lg mb-6 break-all">matlaislamicuniversity@gmail.com</p>
                <a href="mailto:matlaislamicuniversity@gmail.com" class="mt-auto inline-flex items-center space-x-2 px-5 py-2.5 bg-emerald-50 hover:bg-[#006837] text-[#006837] hover:text-white rounded-xl text-sm font-bold transition-all group-hover:bg-[#006837] group-hover:text-white">
                    <span>Kirim Email</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- WhatsApp Card (Center Highlight) -->
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl shadow-gray-100/80 border-2 border-emerald-500/30 flex flex-col items-center text-center group hover:border-[#006837] hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative">
                <div class="absolute -top-3.5 px-3 py-1 bg-amber-400 text-stone-900 font-extrabold text-[11px] rounded-full uppercase tracking-wider shadow-sm">
                    Respon Cepat
                </div>
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#006837] group-hover:text-white transition-all text-[#006837] shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">WhatsApp Admin</h3>
                <p class="text-gray-900 font-bold text-base md:text-lg mb-6">+62 877-8453-8820</p>
                <a href="https://wa.me/6287784538820" target="_blank" class="mt-auto inline-flex items-center space-x-2 px-6 py-2.5 bg-[#006837] hover:bg-[#004d29] text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-700/20 transition-all">
                    <span>Chat WhatsApp</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Telepon Card -->
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl shadow-gray-100/80 border border-gray-100 flex flex-col items-center text-center group hover:border-[#006837] hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#006837] group-hover:text-white transition-all text-[#006837] shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">Telepon Langsung</h3>
                <p class="text-gray-900 font-bold text-base md:text-lg mb-6">+62 877-8453-8820</p>
                <a href="tel:+6287784538820" class="mt-auto inline-flex items-center space-x-2 px-5 py-2.5 bg-emerald-50 hover:bg-[#006837] text-[#006837] hover:text-white rounded-xl text-sm font-bold transition-all group-hover:bg-[#006837] group-hover:text-white">
                    <span>Hubungi Telepon</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Reassuring Info Banner -->
        <div class="max-w-3xl mx-auto mt-16 p-6 rounded-2xl bg-[#F4F8F6] border border-[#E3EBE7] text-center">
            <div class="flex items-center justify-center space-x-2 text-primary font-bold mb-1">
                <svg class="w-5 h-5 text-[#006837]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Pelayanan Kuliah Full Online</span>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
                Seluruh administrasi dan perkuliahan Matla Islamic University diselenggarakan secara fleksibel dan daring. Tim kami siap membantu seluruh kebutuhan informasi calon mahasiswa dan civitas akademika melalui saluran kontak di atas.
            </p>
        </div>
    </div>
</section>
@endsection
