@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard - Matla Islamic University')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Green Header Banner -->
    <div class="bg-primary text-white pt-10 pb-24 px-4 lg:px-12 relative overflow-hidden rounded-b-[3rem] shadow-sm">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg class="w-64 h-64 text-white transform translate-x-16 -translate-y-16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
            </svg>
        </div>
        
        <div class="container mx-auto relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between">
            <div class="flex flex-col md:flex-row items-center space-x-0 md:space-x-6 text-center md:text-left">
                <div class="w-20 h-20 bg-white/20 p-1 rounded-2xl backdrop-blur-sm shadow-inner mb-4 md:mb-0">
                    <img src="{{ Auth::user()->foto_profil }}" alt="Profile" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold mb-1">{{ Auth::user()->name }}</h1>
                    <p class="text-emerald-100 font-medium text-sm">
                        {{ Auth::user()->education['program_studi'] ?? 'Program Studi Belum Diisi' }} • NIM: {{ Auth::user()->nim ?? '-' }}
                    </p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-6 md:mt-0">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold transition-all inline-flex items-center space-x-2 text-xs uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 -mt-14 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Menus & Quick Stats -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Quick Menu -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-extrabold text-gray-900">Menu Akademik</h2>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Akses Cepat</span>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Fitur Aktif: KTM -->
                        <a href="{{ route('mahasiswa.ktm') }}" target="_blank" class="flex flex-col items-center p-4 rounded-2xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all group">
                            <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 text-center">KTM Digital</span>
                        </a>

                        <!-- Fitur Aktif: Profil -->
                        <a href="{{ route('mahasiswa.profil') }}" class="flex flex-col items-center p-4 rounded-2xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all group">
                            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 text-center">Biodata</span>
                        </a>

                        <!-- Placeholder: Jadwal -->
                        <div class="flex flex-col items-center p-4 rounded-2xl opacity-60 cursor-not-allowed">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-3 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 text-center">Jadwal Kuliah</span>
                            <span class="text-[8px] text-gray-400 uppercase mt-1">Segera</span>
                        </div>

                        <!-- Placeholder: Invoice -->
                        <div class="flex flex-col items-center p-4 rounded-2xl opacity-60 cursor-not-allowed">
                            <div class="w-14 h-14 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mb-3 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 text-center">Tagihan UKT</span>
                            <span class="text-[8px] text-gray-400 uppercase mt-1">Segera</span>
                        </div>
                    </div>
                </div>

                <!-- Akademik Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
                        <span class="block text-3xl font-black text-primary mb-1">0.00</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">IPK Terakhir</span>
                    </div>
                    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
                        <span class="block text-3xl font-black text-primary mb-1">0</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">SKS Diambil</span>
                    </div>
                    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
                        <span class="block text-3xl font-black text-primary mb-1">0</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Mata Kuliah</span>
                    </div>
                    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
                        <span class="block text-3xl font-black text-primary mb-1">0%</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Kehadiran</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Schedule & Tasks -->
            <div class="space-y-6">
                <!-- Today's Schedule -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900">Jadwal Hari Ini</h2>
                            <p class="text-xs font-medium text-gray-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="p-2 bg-emerald-50 text-primary rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="py-12 text-center flex flex-col items-center">
                        <img src="https://ui-avatars.com/api/?name=Jadwal&background=f3f4f6&color=9ca3af&rounded=true&size=64" class="opacity-50 grayscale mb-4 w-16 h-16 rounded-full" alt="Empty">
                        <p class="text-sm font-bold text-gray-400">Tidak ada jadwal kelas di tanggal ini</p>
                    </div>
                </div>

                <!-- To-Do List -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900">Perlu Dikerjakan</h2>
                            <p class="text-xs font-medium text-gray-500 mt-1">Tugas atau kuis yang menanti.</p>
                        </div>
                        <span class="w-6 h-6 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold">0</span>
                    </div>
                    
                    <div class="py-8 text-center border-2 border-dashed border-gray-100 rounded-2xl">
                        <p class="text-sm font-bold text-gray-400">Hore! Belum ada tugas untukmu. 🎉</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
