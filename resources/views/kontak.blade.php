@extends('layouts.app')

@section('title', 'Hubungi Kami - Matla Islamic University')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[400px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/mosque-pmb.png') }}" alt="Contact Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-primary-dark/85 backdrop-blur-[2px]"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">Hubungi Kami</h1>
        <p class="text-lg md:text-xl text-emerald-50/80 max-w-3xl mx-auto leading-relaxed">
            Punya pertanyaan atau butuh informasi lebih lanjut? Jangan ragu untuk menghubungi kami melalui formulir di bawah atau kontak langsung.
        </p>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="py-16 bg-white -mt-10 lg:-mt-20 relative z-20">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Alamat Card -->
            <div class="bg-white p-8 rounded-3xl shadow-lg shadow-gray-100 border border-gray-50 flex flex-col items-center text-center group hover:border-primary transition-all">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors text-primary group-hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">Alamat</h3>
                <p class="text-gray-700 font-medium">Jl. Conspet No. 123, Kota Tangerang, Banten, Indonesia</p>
            </div>

            <!-- Email Card -->
            <div class="bg-white p-8 rounded-3xl shadow-lg shadow-gray-100 border border-gray-50 flex flex-col items-center text-center group hover:border-primary transition-all">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors text-primary group-hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">Email</h3>
                <a href="mailto:setyaakbar33@gmail.com" class="text-primary hover:underline font-medium">setyaakbar33@gmail.com</a>
            </div>

            <!-- Telepon Card -->
            <div class="bg-white p-8 rounded-3xl shadow-lg shadow-gray-100 border border-gray-50 flex flex-col items-center text-center group hover:border-primary transition-all">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors text-primary group-hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">Telepon</h3>
                <a href="tel:+6282124306742" class="text-gray-700 font-medium hover:text-primary transition-colors">082124306742</a>
            </div>

            <!-- WhatsApp Card -->
            <div class="bg-white p-8 rounded-3xl shadow-lg shadow-gray-100 border border-gray-50 flex flex-col items-center text-center group hover:border-primary transition-all">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors text-primary group-hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-2">WhatsApp</h3>
                <a href="https://wa.me/6282124306742" target="_blank" class="text-gray-700 font-medium hover:text-primary transition-colors">082124306742</a>
            </div>
        </div>
    </div>
</section>

<!-- Form & Sidebar Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Column: Form -->
            <div class="lg:col-span-2">
                <div class="mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Kirim Pesan</h2>
                    <p class="text-gray-500">Isi formulir di bawah ini dan kami akan segera merespon pesan Anda.</p>
                </div>

                @if(session('success'))
                <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start space-x-4 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-emerald-800 font-bold mb-1">Berhasil!</h4>
                        <p class="text-sm text-emerald-700/80">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('kontak.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" required placeholder="Masukkan nama Anda" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:bg-white transition-all text-gray-700" value="{{ old('name') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                                </svg>
                                Email
                            </label>
                            <input type="email" name="email" required placeholder="Masukkan email Anda" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:bg-white transition-all text-gray-700" value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Subjek
                        </label>
                        <input type="text" name="subject" required placeholder="Tentang apa pesan Anda?" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:bg-white transition-all text-gray-700" value="{{ old('subject') }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            Pesan
                        </label>
                        <textarea name="message" rows="5" required placeholder="Tulis pesan Anda di sini..." class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:bg-white transition-all text-gray-700 resize-none">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold flex items-center justify-center space-x-3 shadow-lg shadow-emerald-500/20 transition-all transform hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Kirim Pesan</span>
                    </button>
                </form>
            </div>

            <!-- Right Column: Info -->
            <div class="space-y-8">
                <!-- Map Placeholder -->
                <div class="bg-emerald-50 aspect-video rounded-3xl flex flex-col items-center justify-center text-primary group cursor-pointer border border-emerald-100 hover:bg-emerald-100/50 transition-all">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg border border-emerald-100 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg">Peta Lokasi Kampus</h4>
                    <span class="text-sm text-emerald-600/70">Tangerang, Banten</span>
                </div>

                <!-- Jam Operasional -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900">Jam Operasional</h4>
                    </div>

                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Senin - Jumat</span>
                            <span class="text-gray-900 font-bold">08.00 - 16.00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Sabtu</span>
                            <span class="text-gray-900 font-bold">09.00 - 12.00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-gray-500 font-medium">Minggu & Hari Libur</span>
                            <span class="text-red-500 font-bold">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
