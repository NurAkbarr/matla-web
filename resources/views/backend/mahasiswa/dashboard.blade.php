@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard - Matla Islamic University')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                </svg>
            </div>
            
            <div class="relative z-10">
                <span class="inline-block px-4 py-1.5 bg-orange-100 text-orange-700 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Role: Mahasiswa</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Halo, Semangat Belajarnya!</h1>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl">Selamat datang di SIAKAD Matla, {{ Auth::user()->name }}. Semua kebutuhan akademik Anda bisa dikelola melalui portal ini.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="block text-2xl font-bold text-primary mb-1">3.85</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-none">IPK Terakhir</span>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="block text-2xl font-bold text-primary mb-1">24</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-none">SKS Diambil</span>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="block text-2xl font-bold text-primary mb-1">8</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-none">Mata Kuliah</span>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="block text-2xl font-bold text-primary mb-1">92%</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-none">Kehadiran</span>
                    </div>
                </div>

                <div class="mt-8 mb-6 flex flex-wrap gap-4">
                    <a href="{{ route('mahasiswa.ktm') }}" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        <span>Lihat Kartu Mahasiswa (KTM)</span>
                    </a>
                    
                    <a href="{{ route('mahasiswa.profil') }}" class="px-8 py-4 bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 rounded-xl font-bold shadow-sm transition-all flex items-center space-x-2">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Lengkapi Profil & Portofolio</span>
                    </a>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-8 pt-8 border-t border-gray-100">
                    @csrf
                    <button type="submit" class="px-8 py-3 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-all inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
