<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Matla Backend</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo-bulat.png') }}">
    
    {{-- Google Fonts: Poppins (Primary) & Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .dropdown-content { overflow: hidden; transition: max-height 0.2s ease-out; max-height: 0; }
        .dropdown-content.open { max-height: 300px; }
        .chevron-rotate { transform: rotate(180deg); }
        .scrollbar-hide::-webkit-scrollbar { display: none !important; }
        .scrollbar-hide { -ms-overflow-style: none !important; scrollbar-width: none !important; }
    </style>

    {{-- Early Dark Mode Detection to prevent flash --}}
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-[var(--bg-page)] text-[var(--text-body)] antialiased font-sans">
    <div class="flex min-h-screen">

        {{-- ===== MOBILE SIDEBAR OVERLAY ===== --}}
        <div id="sidebar-overlay"
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs lg:hidden"
             style="display: none;"
             onclick="closeSidebar()">
        </div>

        <div class="flex h-screen bg-[var(--bg-page)] text-[var(--text-body)] overflow-hidden w-full">
            {{-- ===== SIDEBAR (Mobile: Overlay, Desktop: Static Side) ===== --}}
            <aside id="sidebar" 
                   class="fixed inset-y-0 left-0 z-50 w-72 bg-[var(--primary-800)] dark:bg-[#07170F] border-r border-[var(--primary-900)]/80 dark:border-[var(--border)] shadow-sidebar transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shrink-0 overflow-hidden">
                
                {{-- Logo Area --}}
                <div class="p-5 border-b border-white/10 flex items-center justify-between shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white p-1.5 rounded-xl shadow-sm flex items-center justify-center shrink-0">
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-white tracking-tight leading-none uppercase">MATLA</span>
                            <span class="text-[9px] font-semibold text-[var(--secondary-400)] uppercase tracking-wider mt-1">University Portal</span>
                        </div>
                    </div>
                    <button onclick="closeSidebar()" class="lg:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-[var(--primary-700)]" aria-label="Tutup menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Sidebar Scrollable Menu --}}
                <nav class="flex-1 overflow-y-auto scrollbar-hide py-5 px-3.5 space-y-1">
                    {{-- SISTEM UTAMA --}}
                    <p class="px-3 pt-3 pb-2 text-[10px] font-semibold text-[var(--primary-300)]/70 dark:text-emerald-300/50 uppercase tracking-widest">Sistem Utama</p>
                    
                    <a href="{{ route('backend.admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.dashboard') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.dashboard') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('backend.admin.users.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.users.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.users.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-sm">User Management</span>
                    </a>

                    <div x-data="{ open: {{ request()->routeIs('backend.admin.pmb.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.pmb.*') ? 'bg-[var(--primary-700)] text-white font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.pmb.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm">PMB</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 text-white/60" :class="open ? 'rotate-180 text-white' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak class="mt-1 ml-3.5 pl-3 border-l-2 border-[var(--primary-700)] space-y-1">
                            <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.pmb.registrations.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Daftar Pendaftar</a>
                            <a href="{{ route('backend.admin.affiliates.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.affiliates.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Manajemen Afiliasi</a>
                            <a href="{{ route('backend.admin.pmb.brosur.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.pmb.brosur.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Manajemen Brosur</a>
                            <a href="{{ route('backend.admin.pmb.settings') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.pmb.settings') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Pengaturan PMB</a>
                        </div>
                    </div>

                    <div x-data="{ open: {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'bg-[var(--primary-700)] text-white font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                                <span class="text-sm">Akademik</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 text-white/60" :class="open ? 'rotate-180 text-white' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak class="mt-1 ml-3.5 pl-3 border-l-2 border-[var(--primary-700)] space-y-1">
                            <a href="{{ route('backend.admin.mahasiswa') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.mahasiswa') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Mahasiswa</a>
                            <a href="{{ route('backend.admin.dosen') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.dosen') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Dosen</a>
                            <a href="{{ route('backend.admin.rekap-honor.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.rekap-honor.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Rekap Honor Dosen</a>
                            <a href="{{ route('backend.admin.program-studi.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.program-studi.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Program Studi</a>
                            <a href="{{ route('backend.admin.mata-kuliah.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.mata-kuliah.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Mata Kuliah</a>
                            <a href="{{ route('backend.admin.jadwal.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.jadwal.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Jadwal Matkul</a>
                            <a href="{{ route('backend.admin.kelompok-kelas.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.kelompok-kelas.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Kelompok Kelas</a>
                            <a href="{{ route('backend.admin.assignments.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.assignments.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Tugas MHS</a>
                            <a href="{{ route('backend.admin.cuti.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.cuti.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Pengajuan Cuti</a>
                            <a href="{{ route('backend.admin.khs.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('backend.admin.khs.*') ? 'text-[var(--secondary-400)] bg-[var(--primary-900)]/60 font-semibold' : 'text-white/70 hover:text-white hover:bg-[var(--primary-700)]/50' }}">Upload KHS</a>
                        </div>
                    </div>

                    {{-- MANAJEMEN KONTEN --}}
                    <p class="px-3 pt-6 pb-2 text-[10px] font-semibold text-[var(--primary-300)]/70 dark:text-emerald-300/50 uppercase tracking-widest">Manajemen Konten</p>
                    
                    <a href="{{ route('backend.admin.messages.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.messages.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.messages.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">Kotak Pesan</span>
                    </a>

                    <a href="{{ route('backend.admin.quick-infos.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.quick-infos.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.quick-infos.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm">Quick Info Ticker</span>
                    </a>

                    <a href="{{ route('backend.admin.announcements.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.announcements.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.announcements.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="text-sm">Berita & Pengumuman</span>
                    </a>

                    <a href="{{ route('backend.admin.instagram-posts.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.instagram-posts.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.instagram-posts.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                        <span class="text-sm">Postingan Instagram</span>
                    </a>

                    {{-- SYSTEM --}}
                    <p class="px-3 pt-6 pb-2 text-[10px] font-semibold text-[var(--primary-300)]/70 dark:text-emerald-300/50 uppercase tracking-widest">System</p>
                    
                    <a href="{{ route('backend.admin.maintenance') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.admin.maintenance') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.maintenance') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">Maintenance</span>
                    </a>

                    <div class="pt-8 pb-6">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-rose-300 hover:text-white hover:bg-rose-500/20 transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            {{-- ===== MAIN CONTENT AREA ===== --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

                {{-- Header (Topbar) --}}
                <header class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3.5 shrink-0 z-30 transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        {{-- Hamburger Button (mobile only) --}}
                        <button onclick="openSidebar()"
                                class="lg:hidden p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-100)] transition-colors focus:outline-none"
                                aria-label="Buka menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Logo (mobile) --}}
                        <div class="lg:hidden flex items-center space-x-2">
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-7 w-auto">
                            <span class="font-bold text-[var(--primary-500)] text-sm">MATLA</span>
                        </div>

                        {{-- Breadcrumb / Title (desktop) --}}
                        <h2 class="text-lg font-semibold text-[var(--text-heading)] hidden lg:block tracking-tight">
                            @yield('title', 'Admin Dashboard')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4 sm:space-x-5">
                        {{-- Dark Mode Toggle & Notification --}}
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            <button id="theme-toggle" onclick="toggleDarkMode()" 
                                    class="p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-100)] transition-colors focus:outline-none" 
                                    aria-label="Ganti mode gelap atau terang"
                                    title="Toggle Mode Gelap/Terang">
                                <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden text-[var(--secondary-400)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>

                            <button class="relative p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-100)] transition-colors" 
                                    aria-label="Lihat notifikasi"
                                    title="Notifikasi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[var(--danger)] ring-2 ring-[var(--surface)]"></span>
                            </button>
                        </div>

                        {{-- User Profile --}}
                        <div class="flex items-center space-x-3 pl-2 border-l border-[var(--border)]">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-[var(--text-heading)] capitalize leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-[var(--text-muted)] font-normal mt-0.5">{{ date('l, d M Y') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-[var(--primary-100)] text-[var(--primary-600)] dark:text-[var(--primary-300)] flex items-center justify-center font-bold text-sm shadow-sm ring-1 ring-[var(--border)]">
                                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Content Area (Scrollable) --}}
                <main class="flex-1 overflow-y-auto bg-[var(--bg-page)] transition-colors duration-200">
                    <div class="p-4 sm:p-6 lg:p-8 min-h-full flex flex-col">
                        {{-- Success Modal --}}
                        @if(session('success'))
                        <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-xs" onclick="document.getElementById('success-modal').style.display='none'"></div>
                            <div class="relative bg-[var(--surface)] rounded-2xl shadow-xl max-w-sm w-full p-8 text-center border border-[var(--border)]">
                                <div class="w-16 h-16 bg-[var(--primary-100)] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-[var(--text-heading)] mb-2 tracking-tight">Berhasil!</h3>
                                <p class="text-[var(--text-muted)] text-sm mb-6 leading-relaxed">{{ session('success') }}</p>
                                <button type="button" onclick="document.getElementById('success-modal').style.display='none'"
                                        class="w-full py-3 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition-colors shadow-sm active:scale-95">
                                    Lanjutkan
                                </button>
                            </div>
                        </div>
                        @endif

                        <div class="flex-1">
                            @yield('content')
                        </div>
                    </div>
                </main>

                {{-- Mini Footer --}}
                <footer class="px-4 sm:px-8 py-3.5 border-t border-[var(--border)] bg-[var(--surface)] transition-colors duration-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center text-xs text-[var(--text-muted)] gap-2">
                        <p>&copy; {{ date('Y') }} Matla University. All Rights Reserved.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-[var(--primary-500)] transition-colors">Support</a>
                            <a href="#" class="hover:text-[var(--primary-500)] transition-colors">Privacy</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script>
        // ===== Sidebar Toggle (Mobile) =====
        function openSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0', 'shadow-2xl');
                overlay.style.display = 'block';
            }
        }

        function closeSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0', 'shadow-2xl');
                overlay.style.display = 'none';
            }
        }

        // ===== Dropdown Toggle =====
        function toggleDropdown(name) {
            var dropdown = document.getElementById('dropdown-' + name);
            var chevron = document.getElementById('chevron-' + name);
            if (dropdown && chevron) {
                if (dropdown.classList.contains('open')) {
                    dropdown.classList.remove('open');
                    chevron.classList.remove('chevron-rotate');
                } else {
                    dropdown.classList.add('open');
                    chevron.classList.add('chevron-rotate');
                }
            }
        }

        // ===== Dark Mode Handler =====
        function updateThemeIcons() {
            var isDark = document.documentElement.classList.contains('dark');
            var darkIcon = document.getElementById('theme-toggle-dark-icon');
            var lightIcon = document.getElementById('theme-toggle-light-icon');
            if (darkIcon && lightIcon) {
                if (isDark) {
                    darkIcon.classList.add('hidden');
                    lightIcon.classList.remove('hidden');
                } else {
                    darkIcon.classList.remove('hidden');
                    lightIcon.classList.add('hidden');
                }
            }
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        function confirmDelete(title = 'Apakah Anda yakin?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#006837',
                cancelButtonColor: '#D93025',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
