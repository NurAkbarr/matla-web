<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matla Backend</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo-bulat.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .dropdown-content { overflow: hidden; transition: max-height 0.2s ease-out; max-height: 0; }
        .dropdown-content.open { max-height: 300px; }
        .chevron-rotate { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex min-h-screen">

        {{-- ===== MOBILE SIDEBAR OVERLAY ===== --}}
        <div id="sidebar-overlay"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             style="display: none;"
             onclick="closeSidebar()">
        </div>

    <div class="flex h-screen bg-gray-50 font-sans text-gray-900 overflow-hidden w-full">
        {{-- ===== SIDEBAR (Mobile: Overlay, Desktop: Static Side) ===== --}}
        <aside id="sidebar" 
               class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shrink-0 overflow-hidden">
            
            {{-- Logo Area --}}
            <div class="p-6 border-b border-gray-50 flex items-center justify-between shrink-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-9 w-auto">
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-primary-dark tracking-tighter leading-none uppercase">MATLA</span>
                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">University Portal</span>
                    </div>
                </div>
                <button onclick="closeSidebar()" class="lg:hidden p-2 text-gray-400 hover:text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Sidebar Scrollable Menu --}}
            <nav class="flex-1 overflow-y-auto scrollbar-hide py-6 px-4 space-y-1">
                {{-- SISTEM UTAMA --}}
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem Utama</p>
                <a href="{{ route('backend.admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="text-sm font-bold">Dashboard</span>
                </a>

                <a href="{{ route('backend.admin.users.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.users.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-sm font-bold">User Management</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('backend.admin.pmb.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.pmb.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.pmb.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm font-bold">PMB</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                        <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.pmb.registrations.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Daftar Pendaftar</a>
                        <a href="{{ route('backend.admin.affiliates.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.affiliates.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Manajemen Afiliasi</a>
                        <a href="{{ route('backend.admin.pmb.brosur.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.pmb.brosur.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Manajemen Brosur</a>
                        <a href="{{ route('backend.admin.pmb.settings') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.pmb.settings') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Pengaturan PMB</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.akademik.*') || request()->routeIs('backend.admin.dosen.*') || request()->routeIs('backend.admin.mahasiswa') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                            <span class="text-sm font-bold">Akademik</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                        <a href="{{ route('backend.admin.mahasiswa') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.mahasiswa') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Mahasiswa</a>
                        <a href="{{ route('backend.admin.dosen') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.dosen') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Dosen</a>
                        <a href="{{ route('backend.admin.rekap-honor.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.rekap-honor.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Rekap Honor Dosen</a>
                        <a href="{{ route('backend.admin.program-studi.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.program-studi.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Program Studi</a>
                        <a href="{{ route('backend.admin.mata-kuliah.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.mata-kuliah.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Mata Kuliah</a>
                        <a href="{{ route('backend.admin.jadwal.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.jadwal.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Jadwal Matkul</a>
                        <a href="{{ route('backend.admin.kelompok-kelas.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.kelompok-kelas.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Kelompok Kelas</a>
                        <a href="{{ route('backend.admin.assignments.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.assignments.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Tugas MHS</a>
                        <a href="{{ route('backend.admin.cuti.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('backend.admin.cuti.*') ? 'text-primary' : 'text-slate-400 hover:text-primary' }}">Pengajuan Cuti</a>
                    </div>
                </div>

                {{-- MANAJEMEN KONTEN --}}
                <p class="px-4 pt-8 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Konten</p>
                <a href="{{ route('backend.admin.messages.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.messages.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.messages.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-bold">Kotak Pesan</span>
                </a>

                <a href="{{ route('backend.admin.quick-infos.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.quick-infos.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.quick-infos.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-bold">Quick Info Ticker</span>
                </a>

                <a href="{{ route('backend.admin.announcements.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.announcements.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.announcements.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span class="text-sm font-bold">Berita & Pengumuman</span>
                </a>
                <a href="{{ route('backend.admin.instagram-posts.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.instagram-posts.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.instagram-posts.*') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                    <span class="text-sm font-bold">Postingan Instagram</span>
                </a>

                {{-- SYSTEM --}}
                <p class="px-4 pt-8 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">System</p>
                <a href="{{ route('backend.admin.maintenance') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('backend.admin.maintenance') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-50 hover:text-primary' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('backend.admin.maintenance') ? 'text-white' : 'text-slate-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-bold">Maintenance</span>
                </a>

                <div class="pt-10 pb-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-bold">
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

            {{-- Header (Fixed Height) --}}
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 shrink-0 z-30">
                <div class="flex items-center space-x-3">
                    {{-- Hamburger Button (mobile only) --}}
                    <button onclick="openSidebar()"
                            class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20"
                            aria-label="Buka menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Logo (mobile) --}}
                    <div class="lg:hidden flex items-center space-x-2">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-7 w-auto">
                        <span class="font-bold text-primary-dark text-sm">MATLA</span>
                    </div>

                    {{-- Breadcrumb (desktop) --}}
                    <h2 class="text-lg font-bold text-slate-800 hidden lg:block tracking-tight">
                        @yield('title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Notification & Moon Icons (SIAKAD style) -->
                    <div class="hidden md:flex items-center space-x-4 text-slate-400">
                        <button class="hover:text-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                        <button class="hover:text-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                    </div>
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 capitalize">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ date('l, d M Y') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-slate-100 flex items-center justify-center">
                        <span class="font-bold text-slate-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>

            {{-- Content Area (Scrollable) --}}
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-4 sm:p-6 lg:p-8 min-h-full flex flex-col">
                    {{-- Success Modal --}}
                    @if(session('success'))
                    <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="document.getElementById('success-modal').style.display='none'"></div>
                        <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-sm w-full p-8 sm:p-10 text-center border border-white">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-5 ring-8 ring-emerald-50/50">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 tracking-tight">Berhasil!</h3>
                            <p class="text-gray-500 text-sm mb-6 sm:mb-8 leading-relaxed font-semibold italic">{{ session('success') }}</p>
                            <button type="button" onclick="document.getElementById('success-modal').style.display='none'"
                                    class="w-full py-3 sm:py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 active:scale-95">
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
            <footer class="px-4 sm:px-8 py-4 border-t border-gray-100 bg-white">
                <div class="flex flex-col sm:flex-row justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest gap-2">
                    <p>&copy; {{ date('Y') }} Matla University. All Rights Reserved.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-primary transition-colors">Support</a>
                        <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // ===== Sidebar Toggle (Mobile) =====
        function openSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0', 'shadow-2xl');
            overlay.style.display = 'block';
        }

        function closeSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0', 'shadow-2xl');
            overlay.style.display = 'none';
        }

        // ===== Dropdown Toggle =====
        function toggleDropdown(name) {
            var dropdown = document.getElementById('dropdown-' + name);
            var chevron = document.getElementById('chevron-' + name);
            if (dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                chevron.classList.remove('chevron-rotate');
            } else {
                dropdown.classList.add('open');
                chevron.classList.add('chevron-rotate');
            }
        }
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
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
