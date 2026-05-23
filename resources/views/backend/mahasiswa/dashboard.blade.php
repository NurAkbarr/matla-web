<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIAKAD - Dashboard Mahasiswa</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif !important; background-color: #f8fafc; }
        .sidebar-active { background-color: #047857; color: white; } /* emerald-700 */
        .sidebar-active svg { color: white; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="text-slate-800 antialiased" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">

    @php
        $hour = now()->timezone('Asia/Jakarta')->format('H');
        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
    @endphp

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 bg-white shadow-xl w-72 z-50 transform transition-transform duration-300 flex flex-col"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen}">
        
        <!-- Sidebar Header -->
        <div class="h-[76px] flex items-center px-6 border-b border-slate-100">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain mr-3">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 leading-tight tracking-tight">Matla Academy</h1>
                <p class="text-[10px] text-slate-500 font-medium">Academic Management</p>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-1 overflow-y-auto py-5 px-4 space-y-6 no-scrollbar">
            
            <a href="{{ route('backend.mahasiswa.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl sidebar-active shadow-lg shadow-emerald-700/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-9 9h6m-6 0v-6"></path></svg>
                <span class="font-bold text-[13px] tracking-wide">Portal Akademik</span>
            </a>

            <div>
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-2">Akademik</p>
                <nav class="space-y-1">
                    <a href="{{ route('mahasiswa.profil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-semibold text-[13px]">Biodata</span>
                    </a>
                    <a href="{{ route('mahasiswa.elearning.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-semibold text-[13px]">Perkuliahan</span>
                    </a>
                    <a href="{{ route('mahasiswa.assignments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="font-semibold text-[13px]">Tugas Saya</span>
                    </a>
                    <a href="{{ route('mahasiswa.ktm') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3z"></path></svg>
                        <span class="font-semibold text-[13px]">KTM Digital</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Sidebar Footer / Profile -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ Auth::user()->foto_profil ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-10 h-10 rounded-xl border border-slate-200 shadow-sm object-cover" alt="Profile">
                <div>
                    <p class="text-[13px] font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-500 font-medium">{{ Auth::user()->education['program_studi'] ?? 'Teknik Informatika' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden" style="display: none;"></div>

    <!-- Main Content -->
    <div class="lg:ml-72 min-h-screen flex flex-col transition-all duration-300">
        
        <!-- Top Navbar -->
        <header class="h-[76px] bg-white border-b border-slate-100 flex items-center justify-between px-6 lg:px-8 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <!-- Hamburger hidden on mobile since we use bottom nav -->
                <button @click="sidebarOpen = true" class="hidden text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard</h2>
            </div>
            
            <div class="flex items-center gap-4 lg:gap-6">
                <!-- Theme toggle -->
                <button class="text-slate-400 hover:text-emerald-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
                
                <!-- Notifications -->
                <button class="text-slate-400 hover:text-emerald-700 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                
                <!-- Profile Dropdown -->
                <div class="relative pl-4 lg:pl-6 border-l border-slate-200" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-3 text-left focus:outline-none group">
                        <div class="text-right hidden sm:block group-hover:opacity-80 transition-opacity">
                            <p class="text-[13px] font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ now()->format('l, d F Y') }}</p>
                        </div>
                        <img src="{{ Auth::user()->foto_profil ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-9 h-9 rounded-full border-2 border-white shadow-sm object-cover ring-2 ring-transparent group-hover:ring-emerald-500 transition-all" alt="Profile">
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 py-4 z-50" style="display: none;">
                        
                        <div class="px-5 pb-4 border-b border-slate-100 mb-2">
                            <p class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase mb-2">Profil Singkat</p>
                            <h4 class="font-bold text-slate-800 text-sm truncate">{{ Auth::user()->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->nim ?? 'NIM Belum Diatur' }}</p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">{{ Auth::user()->education['program_studi'] ?? 'Prodi' }}</span>
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[10px] font-bold">SMT {{ Auth::user()->semester ?? 1 }}</span>
                            </div>
                        </div>

                        <a href="{{ route('mahasiswa.profil') }}" class="flex items-center px-5 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Lihat Biodata Lengkap
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="block mt-1 border-t border-slate-100 pt-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-5 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 pb-24 lg:p-8">
            <div class="max-w-7xl mx-auto">
                
                <!-- Top Green Header & Stats Section -->
                <div class="bg-emerald-700 text-white rounded-[2rem] p-8 lg:p-10 mb-8 relative shadow-xl shadow-emerald-900/10 overflow-hidden">
                    
                    <!-- Aesthetic Background Elements -->
                    <div class="absolute inset-0 pointer-events-none opacity-[0.15]">
                        <svg class="absolute -top-24 -right-10 w-96 h-96 text-white transform rotate-12" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 20 100 C 60 20, 140 20, 180 100 C 140 180, 60 180, 20 100 Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M 40 100 C 70 50, 130 50, 160 100 C 130 150, 70 150, 40 100 Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-dasharray="4 4" />
                            <path d="M 60 100 C 80 70, 120 70, 140 100 C 120 130, 80 130, 60 100 Z" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" />
                            <circle cx="180" cy="100" r="3" fill="currentColor" />
                            <circle cx="20" cy="100" r="3" fill="currentColor" />
                        </svg>
                        <svg class="absolute -bottom-20 -left-20 w-80 h-80 text-white transform -rotate-12" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M -20 150 Q 80 100 180 150 T 380 150" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                            <path d="M -20 170 Q 80 120 180 170 T 380 170" stroke="currentColor" stroke-width="1" stroke-linecap="round" fill="none" stroke-dasharray="6 6"/>
                            <circle cx="180" cy="150" r="2" fill="currentColor" />
                        </svg>
                        <svg class="absolute top-1/2 left-1/3 w-32 h-32 text-white transform -translate-y-1/2 opacity-50" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 10 50 Q 30 30 50 50 T 90 50" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                        </svg>
                    </div>

                    <!-- Relative wrapper to keep content above background -->
                    <div class="relative z-10">
                        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-10">
                            <div class="max-w-2xl">
                                <p class="text-xs uppercase tracking-[0.35em] text-emerald-200 font-bold mb-3">{{ Auth::user()->name }}</p>
                                <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-2">{{ $greeting }}!</h1>
                                <p class="text-emerald-100 text-sm opacity-90">Semoga harimu menyenangkan! Berikut adalah ringkasan akademik kamu hari ini.</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-4 min-w-[120px] text-center backdrop-blur-sm">
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-emerald-100 font-bold mb-1">Semester</p>
                                    @php $smt = Auth::user()->semester ?: 1; @endphp
                                    <p class="text-lg font-bold">{{ $smt % 2 == 0 ? 'Genap' : 'Ganjil' }} ({{ $smt }})<br><span class="text-sm font-medium opacity-80">{{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}</span></p>
                                </div>
                                <div class="rounded-2xl border border-white/20 bg-white/10 p-4 min-w-[120px] text-center backdrop-blur-sm">
                                    <p class="text-[10px] uppercase tracking-[0.2em] text-emerald-100 font-bold mb-1">Status</p>
                                    <p class="text-lg font-bold mt-2 uppercase tracking-wide">{{ Auth::user()->status ?? 'Aktif' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- 4 Stat Cards overlaying slightly -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">
                        <div class="rounded-[1.5rem] bg-emerald-600 border border-emerald-500/50 p-6 shadow-inner text-white">
                            <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-emerald-100 mb-4">IPK Terakhir</p>
                            <h3 class="text-4xl font-bold mb-1">0.00</h3>
                            <p class="text-[11px] text-emerald-200 uppercase tracking-widest">Skala 4.00</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-white p-6 shadow-md border border-slate-100 text-slate-800">
                            <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-4">Total Kelas</p>
                            <h3 class="text-4xl font-bold mb-1">{{ $totalMataKuliah ?? 0 }}</h3>
                            <p class="text-[11px] text-slate-500 uppercase tracking-widest">Sedang Diikuti</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-white p-6 shadow-md border border-slate-100 text-slate-800">
                            <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-4">Jadwal Hari Ini</p>
                            <h3 class="text-4xl font-bold mb-1">-</h3>
                            <p class="text-[11px] text-slate-500 uppercase tracking-widest">Belum ada jadwal</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-white p-6 shadow-md border border-slate-100 text-slate-800">
                            <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-4">Tugas / Kuis</p>
                            <h3 class="text-4xl font-bold mb-1">0</h3>
                            <p class="text-[11px] text-emerald-600 uppercase tracking-widest font-semibold">Sudah Selesai</p>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
                    
                    <!-- Menu Akademik (Fitur Cepat) -->
                    <div class="xl:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200">
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Biodata -->
                            <a href="{{ route('mahasiswa.profil') }}" class="flex flex-col items-center p-5 rounded-[1.5rem] border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 hover:shadow-md transition-all group">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <span class="font-bold text-[13px] text-slate-800 group-hover:text-emerald-700">Biodata</span>
                            </a>
                            <!-- Perkuliahan -->
                            <a href="{{ route('mahasiswa.elearning.index') }}" class="flex flex-col items-center p-5 rounded-[1.5rem] border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 hover:shadow-md transition-all group">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-bold text-[13px] text-slate-800 group-hover:text-emerald-700">Perkuliahan</span>
                            </a>
                            <!-- Tugas Saya -->
                            <a href="{{ route('mahasiswa.assignments.index') }}" class="flex flex-col items-center p-5 rounded-[1.5rem] border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 hover:shadow-md transition-all group">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <span class="font-bold text-[13px] text-slate-800 group-hover:text-emerald-700">Tugas Saya</span>
                            </a>
                            <!-- KTM Digital -->
                            <a href="{{ route('mahasiswa.ktm') }}" class="flex flex-col items-center p-5 rounded-[1.5rem] border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 hover:shadow-md transition-all group">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3z"></path></svg>
                                </div>
                                <span class="font-bold text-[13px] text-slate-800 group-hover:text-emerald-700">KTM Digital</span>
                            </a>
                        </div>
                    </div>

                    <!-- Grafik Kehadiran -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-[11px] uppercase tracking-[0.2em] text-emerald-600 font-bold mb-1">Grafik Kehadiran</p>
                                <h3 class="text-lg font-bold text-slate-800">Ringkasan Mingguan</h3>
                            </div>
                            <span class="text-sm font-bold text-emerald-600">0%</span>
                        </div>
                        <div class="space-y-4 mt-8">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="flex items-center gap-4">
                                    <span class="w-16 text-[12px] font-medium text-slate-500">Minggu {{ $i }}</span>
                                    <div class="h-2.5 flex-1 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-emerald-500" style="width: 0%;"></div>
                                    </div>
                                    <span class="w-8 text-right text-[12px] font-bold text-slate-400">0</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>

            </div>
        </main>

    </div>

    @include('partials.mobile-bottom-nav')
</body>
</html>
