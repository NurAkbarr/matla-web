@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard - Matla Islamic University')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
        .premium-banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('assets/motifbatik.jpg') }}');
            background-size: 100% auto !important;
            background-repeat: repeat-y !important;
            opacity: 0.08;
            filter: invert(1);
            mix-blend-mode: screen;
            pointer-events: none;
        }
    </style>
    <div class="premium-banner text-white pt-12 pb-20 px-4 lg:px-12 relative overflow-hidden rounded-b-[4rem] shadow-2xl" style="background-color: #065F46;">
        <!-- Large decorative circles (right side) -->
        <div class="absolute top-1/2 right-[-5rem] -translate-y-1/2 w-[22rem] h-[22rem] rounded-full border-2 border-white/10 pointer-events-none"></div>
        <div class="absolute top-1/2 right-[-3rem] -translate-y-1/2 w-[17rem] h-[17rem] rounded-full border border-white/[0.06] pointer-events-none"></div>
        
        <div class="container mx-auto relative z-10">
            <div class="flex flex-col space-y-6">
                <!-- Date & Semester info -->
                <div class="flex flex-wrap items-center gap-3 text-emerald-100/80 text-xs font-bold uppercase tracking-widest">
                    <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>Semester {{ Auth::user()->semester % 2 == 0 ? 'Genap' : 'Ganjil' }} {{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}</span>
                    </div>
                </div>

                <!-- Main Greeting -->
                <div>
                    <h1 class="text-3xl md:text-5xl font-black mb-2 tracking-tight">{{ Auth::user()->name }}</h1>
                    <p class="text-emerald-100/90 font-medium text-lg md:text-xl">Sistem Informasi Akademik</p>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 pt-2">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        <span class="text-xs font-bold tracking-widest uppercase">NIM: {{ Auth::user()->nim ?? '-' }}</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                        <span class="text-xs font-bold tracking-widest uppercase">{{ Auth::user()->education['program_studi'] ?? 'Belum Diisi' }}</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl flex items-center space-x-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold tracking-widest uppercase">{{ Auth::user()->status ?? 'Aktif' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 -mt-10 relative z-20">
        
        @php
            $user = Auth::user();
            $profil = $user->profil;
            $isProfileIncomplete = empty($user->phone) || empty($user->tanggal_lahir) || empty($user->jenis_kelamin) || empty($user->address) || empty($profil?->tentang_saya);
        @endphp

        @if($isProfileIncomplete)
            <div class="mb-6 p-5 bg-white border border-amber-200 shadow-xl shadow-amber-500/5 rounded-2xl flex items-start">
                <div class="p-3 bg-amber-50 text-amber-500 rounded-xl mr-4 flex-shrink-0">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-lg mb-1 tracking-tight">Halo {{ explode(' ', $user->name)[0] }}, biodata kamu belum lengkap!</h3>
                    <p class="text-sm font-medium text-gray-500 leading-relaxed mb-3">Sistem mendeteksi ada beberapa data profil yang masih kosong. Yuk, lengkapi datamu sekarang agar informasi di sistem sesuai dan lengkap.</p>
                    <a href="{{ route('mahasiswa.profil') }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-amber-500/20">
                        Lengkapi Sekarang
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- Stats Grid (New Premium Style) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- IPK Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-emerald-900/5 border border-emerald-50 flex items-center justify-between group hover:border-emerald-200 transition-all">
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">IPK Terakhir</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-black text-gray-900 tracking-tight">0.00</span>
                    </div>
                    <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Skala 4.00</p>
                </div>
            </div>

            <!-- SKS Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-emerald-900/5 border border-emerald-50 flex flex-col group hover:border-emerald-200 transition-all">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">SKS Diambil</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-black text-gray-900 tracking-tight">{{ $totalSKS }}</span>
                </div>
                <div class="w-full h-2 mt-4 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($totalSKS / 144) * 100 }}%"></div>
                </div>
                <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Dari 144 SKS</p>
            </div>

            <!-- Jadwal Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-emerald-900/5 border border-emerald-50 flex flex-col group hover:border-emerald-200 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Jadwal Hari Ini</p>
                @if($jadwalHariIni->count() > 0)
                    <div class="mt-2 space-y-3">
                        @foreach($jadwalHariIni->take(2) as $jhi)
                        <div class="flex items-start space-x-3">
                            <div class="text-[10px] font-black bg-emerald-50 text-emerald-600 px-2 py-1 rounded-lg">{{ substr($jhi->jam_mulai, 0, 5) }}</div>
                            <div class="min-w-0">
                                <h4 class="text-[11px] font-bold text-gray-900 truncate">{{ $jhi->mata_kuliah }}</h4>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs font-bold text-gray-400 mt-2">Tidak ada jadwal</p>
                @endif
            </div>

            <!-- Tugas Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-emerald-900/5 border border-emerald-50 flex flex-col group hover:border-emerald-200 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-{{ $pendingTasks > 0 ? 'red' : 'emerald' }}-50 text-{{ $pendingTasks > 0 ? 'red' : 'emerald' }}-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    @if($pendingTasks > 0)
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    @endif
                </div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Tugas/Kuis</p>
                <div class="flex items-baseline space-x-2">
                    <span class="text-4xl font-black text-gray-900 tracking-tight">{{ $pendingTasks }}</span>
                </div>
                <p class="text-[10px] font-bold text-{{ $pendingTasks > 0 ? 'red' : 'emerald' }}-600 mt-2 uppercase tracking-widest">
                    {{ $pendingTasks > 0 ? 'Perlu Dikerjakan' : 'Sudah Selesai' }}
                </p>
            </div>
        </div>

        <!-- Menu Akademik Section -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Menu Akademik</h2>
                <a href="#" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Lihat semua</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- KTM Digital -->
                <a href="{{ route('mahasiswa.ktm') }}" target="_blank" class="group flex flex-col items-center justify-center p-6 bg-white rounded-3xl border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 transition-all">
                    <div class="w-12 h-12 flex items-center justify-center mb-3 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-700">KTM Digital</span>
                </a>

                <!-- Biodata -->
                <a href="{{ route('mahasiswa.profil') }}" class="group flex flex-col items-center justify-center p-6 bg-white rounded-3xl border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 transition-all">
                    <div class="w-12 h-12 flex items-center justify-center mb-3 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-700">Biodata</span>
                </a>

                <!-- Jadwal Kuliah -->
                <a href="{{ route('mahasiswa.elearning.index') }}" class="group flex flex-col items-center justify-center p-6 bg-white rounded-3xl border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 transition-all">
                    <div class="w-12 h-12 flex items-center justify-center mb-3 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-700">Jadwal Kuliah</span>
                </a>

                <!-- Tagihan -->
                <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-3xl border border-gray-100 opacity-60 cursor-not-allowed">
                    <div class="w-12 h-12 flex items-center justify-center mb-3 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-700">Tagihan UKT</span>
                </div>
            </div>
        </div>

        <!-- Berita & Pengumuman Section -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Berita & Pengumuman</h2>
                <a href="#" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Selengkapnya</a>
            </div>
            
            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    @php
                        $colorClass = 'emerald';
                        if($announcement->category == 'Akademik') $colorClass = 'amber';
                        if($announcement->category == 'Prestasi') $colorClass = 'indigo';
                        if($announcement->category == 'Event') $colorClass = 'purple';
                        
                        $link = $announcement->pdf_file ? asset('storage/' . $announcement->pdf_file) : '#';
                    @endphp
                    <a href="{{ $link }}" target="_blank" class="group flex items-center p-4 bg-white rounded-3xl border border-gray-100 hover:border-{{ $colorClass }}-200 hover:shadow-lg hover:shadow-{{ $colorClass }}-500/5 transition-all cursor-pointer">
                        <div class="w-16 h-16 flex-shrink-0 bg-{{ $colorClass }}-50 rounded-2xl flex items-center justify-center mr-6 text-2xl group-hover:scale-110 transition-transform">
                            {{ $announcement->icon }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="px-3 py-1 bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 text-[10px] font-bold uppercase tracking-wider rounded-full">{{ $announcement->category }}</span>
                                @if($announcement->pdf_file)
                                    <span class="flex items-center text-[9px] font-black text-red-500 uppercase tracking-tighter">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        PDF
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-gray-800 group-hover:text-{{ $colorClass }}-600 transition-colors">{{ $announcement->title }}</h3>
                            <div class="flex items-center mt-1 text-gray-400 text-xs font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $announcement->published_at->format('d M Y') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50/50">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 text-emerald-100">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <p class="text-gray-400 font-bold tracking-tight">Belum ada informasi terbaru</p>
                        <p class="text-gray-300 text-xs mt-1">Berita dan pengumuman akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
