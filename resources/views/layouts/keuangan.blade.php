<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Keuangan') - Matla Backend</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo-bulat.png') }}">
    
    {{-- Google Fonts: Figtree (Body & UI) & Schibsted Grotesk (Display & Numbers) per design.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Schibsted+Grotesk:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .dropdown-content { overflow: hidden; transition: max-height 0.2s ease-out; max-height: 0; }
        .dropdown-content.open { max-height: 300px; }
        .chevron-rotate { transform: rotate(180deg); }
        .scrollbar-hide::-webkit-scrollbar { display: none !important; }
        .scrollbar-hide { -ms-overflow-style: none !important; scrollbar-width: none !important; }
    </style>

    {{-- Early Dark Mode Detection: Light mode default unless explicitly set to dark --}}
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark') {
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
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-xs lg:hidden"
             style="display: none;"
             onclick="closeSidebar()">
        </div>

        <div class="flex h-screen bg-[var(--bg-page)] text-[var(--text-body)] overflow-hidden w-full">
            {{-- ===== SIDEBAR (Mobile: Drawer, Desktop: 240px Static Side) ===== --}}
            <aside id="sidebar" 
                   class="fixed inset-y-0 left-0 z-50 w-[240px] bg-[var(--primary-900)] text-white border-r border-white/5 transform -translate-x-full transition-transform duration-250 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shrink-0 overflow-hidden">
                
                {{-- Logo Area --}}
                <div class="h-16 px-5 border-b border-white/10 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 bg-white p-1 rounded-[var(--radius-xs)] flex items-center justify-center shrink-0">
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold font-display text-sm text-white leading-tight truncate">MATLA</span>
                            <span class="text-[10px] font-medium tracking-wide text-[var(--secondary-400)] truncate">University Portal</span>
                        </div>
                    </div>
                    <button onclick="closeSidebar()" class="lg:hidden p-1.5 text-white/70 hover:text-white rounded-[var(--radius-xs)] hover:bg-white/10 focus:outline-none cursor-pointer" aria-label="Tutup menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Sidebar Scrollable Menu --}}
                <nav class="flex-1 overflow-y-auto scrollbar-hide py-3 space-y-0.5">
                    {{-- Section Group Header (Sentence case, normal weight) --}}
                    <p class="px-5 pt-3 pb-1.5 text-xs font-medium text-white/50">Sistem keuangan</p>

                    @php
                        $isDashboard = request()->routeIs('backend.keuangan.dashboard');
                        $isMahasiswa = request()->routeIs('backend.keuangan.mahasiswa.*');
                        $isVerifikasi = request()->routeIs('backend.keuangan.verifikasi.*');
                        $isMasterTA = request()->routeIs('backend.keuangan.master-tahun-akademik.*');
                        $isTagihan = request()->routeIs('backend.keuangan.tagihan.*');
                        $isRekapSpp = request()->routeIs('backend.keuangan.rekap-spp.*');
                        $isRiwayat = request()->routeIs('backend.keuangan.riwayat-transaksi.*');
                        $isPemasukanPengeluaran = request()->routeIs('backend.keuangan.pemasukan-pengeluaran.*');
                        
                        // Count pending verifications for amber badge
                        $pendingVerifCount = \App\Models\Pembayaran::where('status', 'pending')->count();
                    @endphp

                    {{-- 1. Dashboard --}}
                    <a href="{{ route('backend.keuangan.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isDashboard ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>

                    {{-- 2. Data Mahasiswa --}}
                    <a href="{{ route('backend.keuangan.mahasiswa.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isMahasiswa ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="truncate">Data Mahasiswa</span>
                    </a>

                    {{-- 3. Verifikasi Pembayaran --}}
                    <a href="{{ route('backend.keuangan.verifikasi.index') }}" 
                       class="flex items-center justify-between px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isVerifikasi ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="truncate">Verifikasi Pembayaran</span>
                        </div>
                        @if($pendingVerifCount > 0)
                            <span class="px-1.5 py-0.5 text-[11px] font-bold bg-[var(--secondary-500)] text-[var(--text-on-secondary)] rounded-full tabular-nums">
                                {{ $pendingVerifCount }}
                            </span>
                        @endif
                    </a>

                    {{-- 4. Tahun & Semester --}}
                    <a href="{{ route('backend.keuangan.master-tahun-akademik.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isMasterTA ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate">Tahun & Semester</span>
                    </a>

                    {{-- 5. Manajemen Tagihan --}}
                    <a href="{{ route('backend.keuangan.tagihan.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isTagihan ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="truncate">Manajemen Tagihan</span>
                    </a>

                    {{-- 6. Rekap SPP Semester --}}
                    <a href="{{ route('backend.keuangan.rekap-spp.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isRekapSpp ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="truncate">Rekap SPP Semester</span>
                    </a>

                    {{-- 7. Riwayat Transaksi --}}
                    <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isRiwayat ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="truncate">Riwayat Transaksi</span>
                    </a>

                    {{-- 8. Pemasukan & Pengeluaran (Menu Baru) --}}
                    <a href="{{ route('backend.keuangan.pemasukan-pengeluaran.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors border-l-[3px] {{ $isPemasukanPengeluaran ? 'bg-[var(--primary-800)] text-white border-[var(--secondary-500)] font-semibold' : 'text-white/80 hover:bg-[var(--primary-800)]/60 hover:text-white border-transparent' }}">
                        <svg class="w-5 h-5 shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="truncate">Pemasukan & Pengeluaran</span>
                    </a>
                </nav>

                {{-- Logout Button (Separated with meaningful border) --}}
                <div class="p-3 border-t border-white/10 mt-auto shrink-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-[var(--radius-xs)] text-white/75 hover:text-white hover:bg-white/10 transition-colors text-sm font-medium cursor-pointer">
                            <svg class="w-5 h-5 shrink-0 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT AREA ===== --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                
                {{-- Topbar (64px Fixed Height per design.md) --}}
                <header class="h-16 bg-[var(--surface)] border-b border-[var(--border)] flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 z-30">
                    <div class="flex items-center space-x-3">
                        {{-- Hamburger Button (Mobile only, 40x40 target) --}}
                        <button id="hamburger-btn" 
                                onclick="openSidebar()"
                                class="w-10 h-10 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] transition-colors focus:outline-none lg:hidden cursor-pointer"
                                aria-label="Buka navigasi menu"
                                aria-expanded="false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Logo (Mobile only) --}}
                        <div class="lg:hidden flex items-center space-x-2">
                            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-6 w-auto">
                            <span class="font-bold font-display text-[var(--primary-500)] text-sm">MATLA</span>
                        </div>

                        {{-- Page Title (Desktop) --}}
                        <h2 class="text-base font-bold font-display text-[var(--text-heading)] hidden lg:block tracking-tight">
                            @yield('title', 'Dashboard Keuangan')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-3 sm:space-x-4">
                        {{-- Dark Mode Toggle --}}
                        <button id="theme-toggle" onclick="toggleDarkMode()" 
                                class="w-10 h-10 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] transition-colors focus:outline-none cursor-pointer" 
                                aria-label="Ganti mode gelap atau terang"
                                title="Ganti mode tema">
                            <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden text-[var(--secondary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        {{-- Notification Dropdown Wrapper --}}
                        <div class="relative" id="notif-dropdown-wrapper">
                            <button id="notif-bell-btn" 
                                    type="button"
                                    onclick="toggleNotificationDropdown(event)" 
                                    class="relative w-10 h-10 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] transition-colors cursor-pointer focus:outline-none" 
                                    aria-label="Notifikasi sistem"
                                    aria-expanded="false"
                                    title="Notifikasi Pembayaran">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                {{-- Badge Counter --}}
                                <span id="notif-badge" 
                                      class="hidden absolute -top-1 -right-1 min-w-[19px] h-[19px] px-1 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-[var(--surface)] shadow-xs leading-none">
                                    0
                                </span>
                            </button>

                            {{-- Dropdown Panel --}}
                            <div id="notif-dropdown-panel" 
                                 class="hidden absolute right-0 top-full mt-2 w-[320px] sm:w-[380px] md:w-[420px] bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-[var(--shadow-dropdown)] z-50 overflow-hidden transition-all duration-200 origin-top-right">
                                
                                {{-- Header --}}
                                <div class="px-4 py-3 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold font-display text-[var(--text-heading)] uppercase tracking-wider">
                                            Notifikasi
                                        </h3>
                                        <span id="notif-header-unread-badge" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300">
                                            0 Baru
                                        </span>
                                    </div>
                                    <button type="button" 
                                            id="btn-mark-all-read"
                                            onclick="markAllNotificationsAsRead()" 
                                            class="text-[11px] font-semibold text-[var(--primary-600)] hover:text-[var(--primary-700)] hover:underline flex items-center gap-1 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7m-14 0l4 4L19 7"/>
                                        </svg>
                                        <span>Tandai Semua Dibaca</span>
                                    </button>
                                </div>

                                {{-- Notification List Container --}}
                                <div id="notif-list-container" class="max-h-[360px] overflow-y-auto divide-y divide-[var(--border)] scrollbar-hide">
                                    {{-- Loading Skeleton --}}
                                    <div id="notif-loading" class="p-4 space-y-3">
                                        <div class="flex items-start gap-3 animate-pulse">
                                            <div class="w-8 h-8 rounded-full bg-[var(--border)] shrink-0"></div>
                                            <div class="flex-1 space-y-1.5">
                                                <div class="h-3 bg-[var(--border)] rounded w-1/3"></div>
                                                <div class="h-2.5 bg-[var(--border)] rounded w-5/6"></div>
                                                <div class="h-2 bg-[var(--border)] rounded w-1/4"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Empty State --}}
                                    <div id="notif-empty" class="hidden py-8 px-4 text-center">
                                        <div class="w-12 h-12 rounded-full bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)] flex items-center justify-center mx-auto mb-2.5">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-bold text-[var(--text-heading)]">Belum Ada Notifikasi</p>
                                        <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Pemberitahuan pembayaran baru dari mahasiswa akan muncul di sini secara otomatis.</p>
                                    </div>

                                    {{-- Items rendered dynamically via JS --}}
                                    <div id="notif-items" class="divide-y divide-[var(--border)]"></div>
                                </div>

                                {{-- Footer Link --}}
                                <div class="px-4 py-2.5 bg-[var(--bg-page)] border-t border-[var(--border)] flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-[var(--text-muted)] flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Real-time sync aktif
                                    </span>
                                    <a href="{{ route('backend.keuangan.verifikasi.index') }}" class="font-semibold text-[var(--primary-600)] hover:text-[var(--primary-700)] hover:underline flex items-center gap-1">
                                        <span>Buka Verifikasi</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- User Profile --}}
                        <div class="flex items-center space-x-3 pl-3 border-l border-[var(--border)]">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-semibold text-[var(--text-heading)] leading-tight">{{ Auth::user()->name ?? 'Staff Keuangan' }}</p>
                                <p class="text-[11px] text-[var(--text-muted)] tabular-nums mt-0.5">{{ date('d M Y') }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-[var(--primary-100)] text-[var(--primary-600)] flex items-center justify-center font-bold text-xs border border-[var(--primary-200)] shrink-0">
                                <span>{{ strtoupper(substr(Auth::user()->name ?? 'K', 0, 1)) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Interactive Browser Notification Permission Banner --}}
                <div id="notif-permission-banner" class="hidden bg-emerald-50 dark:bg-emerald-950/40 border-b border-emerald-200 dark:border-emerald-800/60 px-4 py-2.5 text-xs text-emerald-900 dark:text-emerald-200 transition-all shrink-0">
                    <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 shrink-0 font-bold text-sm shadow-xs">🔔</span>
                            <div class="leading-tight">
                                <p class="font-bold text-[var(--text-heading)]">Aktifkan Notifikasi Pop-up Real-Time</p>
                                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Izinkan notifikasi agar Anda mendapatkan peringatan pop-up saat ada pembayaran SPP baru yang masuk.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                            <button type="button" onclick="requestBrowserNotificationPermission()" class="px-3 py-1.5 rounded-[var(--radius-xs)] bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white font-semibold transition cursor-pointer shadow-xs text-xs">
                                Izinkan Notifikasi
                            </button>
                            <button type="button" onclick="dismissNotificationBanner()" class="px-2.5 py-1.5 rounded-[var(--radius-xs)] hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-[var(--text-muted)] hover:text-[var(--text-heading)] font-medium transition cursor-pointer text-xs">
                                Nanti Saja
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Content Area (Scrollable, 1440px max width centered on large desktop) --}}
                <main class="flex-1 overflow-y-auto bg-[var(--bg-page)]">
                    <div class="max-w-[1440px] mx-auto p-4 sm:p-6 lg:p-8 min-h-full flex flex-col">
                        {{-- Success Modal --}}
                        @if(session('success'))
                        <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-xs" onclick="document.getElementById('success-modal').style.display='none'"></div>
                            <div class="relative bg-[var(--surface)] rounded-[var(--radius-sm)] max-w-sm w-full p-6 text-center border border-[var(--border)] shadow-[var(--shadow-modal)]">
                                <div class="w-12 h-12 bg-[var(--primary-50)] text-[var(--primary-500)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold font-display text-[var(--text-heading)] mb-1">Berhasil</h3>
                                <p class="text-[var(--text-muted)] text-xs mb-5 leading-relaxed">{{ session('success') }}</p>
                                <button type="button" onclick="document.getElementById('success-modal').style.display='none'"
                                        class="w-full h-10 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] font-semibold text-xs transition-colors cursor-pointer">
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

                {{-- Footer --}}
                <footer class="px-4 sm:px-8 py-3 border-t border-[var(--border)] bg-[var(--surface)] shrink-0">
                    <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row justify-between items-center text-xs text-[var(--text-muted)] gap-2">
                        <p>&copy; {{ date('Y') }} MATLA University Portal. Hak cipta dilindungi.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-[var(--primary-500)] transition-colors">Bantuan</a>
                            <a href="#" class="hover:text-[var(--primary-500)] transition-colors">Kebijakan privasi</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script>
        // ===== Sidebar Toggle (Mobile Drawer) =====
        function openSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var btn = document.getElementById('hamburger-btn');
            if (sidebar && overlay) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0', 'shadow-[var(--shadow-drawer)]');
                overlay.style.display = 'block';
                document.body.classList.add('overflow-hidden');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            }
        }

        function closeSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var btn = document.getElementById('hamburger-btn');
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0', 'shadow-[var(--shadow-drawer)]');
                overlay.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

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

    {{-- SweetAlert2 for notifications --}}
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

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        function confirmDelete(title = 'Konfirmasi Hapus Data', text = 'Apakah Anda yakin ingin menghapus data yang dipilih? Tindakan ini tidak dapat dibatalkan.') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                iconColor: '#D93025',
                showCancelButton: true,
                confirmButtonColor: '#D93025',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900',
                    title: 'text-base font-bold text-slate-900 dark:text-slate-100 font-display',
                    htmlContainer: 'text-xs text-slate-600 dark:text-slate-400',
                    confirmButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-sm cursor-pointer',
                    cancelButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-2xs cursor-pointer'
                }
            });
        }
    </script>

    {{-- Floating Real-Time Toast Container --}}
    <div id="notif-toast-container" class="fixed bottom-5 right-5 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

    {{-- Script Real-Time Notification & Pop-Up --}}
    <script>
        (function() {
            const NOTIF_CONFIG = {
                indexUrl: '{{ route("backend.keuangan.notifikasi.index") }}',
                unreadCountUrl: '{{ route("backend.keuangan.notifikasi.unreadCount") }}',
                readAllUrl: '{{ route("backend.keuangan.notifikasi.markAllAsRead") }}',
                csrfToken: '{{ csrf_token() }}',
                verifUrl: '{{ route("backend.keuangan.verifikasi.index") }}',
                pollIntervalMs: 15000,
                logoUrl: '{{ asset("assets/logo-bulat.png") }}'
            };

            let notifState = {
                unreadCount: 0,
                latestId: parseInt(localStorage.getItem('keuangan_last_notif_id') || '0', 10),
                isOpen: false,
                items: [],
                hasInitialLoaded: false
            };

            // Audio Chime Synthesizer via Web Audio API (Pemberitahuan Suara Elegan)
            window.playNotificationChime = function() {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) return;
                    const audioCtx = new AudioContext();
                    const now = audioCtx.currentTime;

                    // Tone 1 (D5)
                    const osc1 = audioCtx.createOscillator();
                    const gain1 = audioCtx.createGain();
                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(587.33, now);
                    gain1.gain.setValueAtTime(0.12, now);
                    gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                    osc1.connect(gain1);
                    gain1.connect(audioCtx.destination);
                    osc1.start(now);
                    osc1.stop(now + 0.25);

                    // Tone 2 (A5)
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(880, now + 0.12);
                    gain2.gain.setValueAtTime(0.15, now + 0.12);
                    gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.start(now + 0.12);
                    osc2.stop(now + 0.5);
                } catch (e) {
                    // Audio context blocked or unsupported
                }
            };

            // Toggle Dropdown
            window.toggleNotificationDropdown = function(event) {
                if (event) {
                    event.stopPropagation();
                }
                const panel = document.getElementById('notif-dropdown-panel');
                const btn = document.getElementById('notif-bell-btn');
                if (!panel) return;

                notifState.isOpen = !notifState.isOpen;
                if (notifState.isOpen) {
                    panel.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                    fetchNotifications(false);
                } else {
                    panel.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                }
            };

            window.closeNotificationDropdown = function() {
                const panel = document.getElementById('notif-dropdown-panel');
                const btn = document.getElementById('notif-bell-btn');
                if (!panel) return;
                notifState.isOpen = false;
                panel.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            };

            // Browser Notification Permission Request
            window.requestBrowserNotificationPermission = function() {
                if (!('Notification' in window)) {
                    alert('Browser Anda tidak mendukung Web Notification.');
                    return;
                }

                Notification.requestPermission().then(function(permission) {
                    const banner = document.getElementById('notif-permission-banner');
                    if (permission === 'granted') {
                        if (banner) banner.classList.add('hidden');
                        sessionStorage.setItem('dismiss_notif_banner', 'true');

                        // Show Welcome Confirmation Notification
                        try {
                            new Notification("Notifikasi Keuangan Aktif", {
                                body: "Anda akan mendapatkan pop-up otomatis saat ada pembayaran SPP baru yang diunggah mahasiswa.",
                                icon: NOTIF_CONFIG.logoUrl,
                                tag: 'matla-welcome'
                            });
                        } catch (e) {}

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notifikasi Aktif!',
                                text: 'Peringatan pop-up browser akan muncul otomatis ketika ada pembayaran SPP masuk.',
                                timer: 3000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    } else if (permission === 'denied') {
                        if (banner) banner.classList.add('hidden');
                        sessionStorage.setItem('dismiss_notif_banner', 'true');
                    }
                });
            };

            window.dismissNotificationBanner = function() {
                const banner = document.getElementById('notif-permission-banner');
                if (banner) banner.classList.add('hidden');
                sessionStorage.setItem('dismiss_notif_banner', 'true');
            };

            // In-App Toast Notification
            window.showInAppToast = function(notif) {
                const container = document.getElementById('notif-toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = 'pointer-events-auto p-4 rounded-[var(--radius-sm)] bg-[var(--surface)] border-l-4 border-l-[var(--primary-500)] border border-[var(--border)] shadow-xl flex items-start gap-3 transition-all duration-300 transform translate-y-4 opacity-0';
                
                toast.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-xs font-bold font-display text-[var(--text-heading)] truncate">${escapeHtml(notif.title)}</p>
                            <span class="text-[9.5px] text-[var(--primary-600)] font-semibold shrink-0">Baru Saja</span>
                        </div>
                        <p class="text-[11.5px] text-[var(--text-body)] mt-0.5 leading-snug line-clamp-2">${escapeHtml(notif.message)}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="${notif.url}" onclick="markNotificationAsRead(${notif.id})" class="text-[11px] font-bold text-[var(--primary-600)] hover:underline inline-flex items-center gap-1">
                                <span>Verifikasi Sekarang</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    <button type="button" class="text-[var(--text-muted)] hover:text-[var(--text-heading)] p-1 rounded transition cursor-pointer" onclick="this.closest('div').remove()">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                `;

                container.appendChild(toast);

                // Animate in
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-4', 'opacity-0');
                });

                // Auto remove after 7 seconds
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => toast.remove(), 300);
                }, 7000);
            };

            // Show Browser Native Notification
            window.showNativeNotification = function(notif) {
                if (!('Notification' in window) || Notification.permission !== 'granted') {
                    return;
                }

                try {
                    const nativeNotif = new Notification(notif.title, {
                        body: notif.message,
                        icon: NOTIF_CONFIG.logoUrl,
                        tag: 'matla-spp-' + notif.id,
                        requireInteraction: false
                    });

                    nativeNotif.onclick = function() {
                        window.focus();
                        markNotificationAsRead(notif.id);
                        window.location.href = notif.url;
                        nativeNotif.close();
                    };
                } catch (e) {
                    console.warn('Native notification error:', e);
                }
            };

            // Fetch Notifications from Server
            window.fetchNotifications = function(isPolling = false) {
                fetch(NOTIF_CONFIG.indexUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    const newItems = data.notifications || [];
                    const unread = data.unread_count || 0;
                    const latestId = data.latest_id || 0;

                    // Deteksi jika ada notifikasi BARU masuk (saat polling berjalan)
                    if (notifState.hasInitialLoaded && latestId > notifState.latestId) {
                        const brandNewItems = newItems.filter(item => item.id > notifState.latestId && !item.is_read);
                        brandNewItems.forEach(item => {
                            showNativeNotification(item);
                            showInAppToast(item);
                        });

                        if (brandNewItems.length > 0) {
                            playNotificationChime();
                        }
                    }

                    notifState.hasInitialLoaded = true;
                    notifState.latestId = latestId;
                    notifState.unreadCount = unread;
                    notifState.items = newItems;
                    localStorage.setItem('keuangan_last_notif_id', latestId.toString());

                    // Update UI Badges
                    updateBadges(unread);

                    // Render List
                    renderNotificationList(newItems);
                })
                .catch(err => {
                    // Silently fail on network hiccup
                });
            };

            function updateBadges(unread) {
                const badge = document.getElementById('notif-badge');
                const headerBadge = document.getElementById('notif-header-unread-badge');

                if (badge) {
                    if (unread > 0) {
                        badge.textContent = unread > 99 ? '99+' : unread;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                if (headerBadge) {
                    if (unread > 0) {
                        headerBadge.textContent = unread + ' Baru';
                        headerBadge.classList.remove('hidden');
                    } else {
                        headerBadge.classList.add('hidden');
                    }
                }
            }

            function renderNotificationList(items) {
                const loading = document.getElementById('notif-loading');
                const empty = document.getElementById('notif-empty');
                const itemsContainer = document.getElementById('notif-items');

                if (loading) loading.classList.add('hidden');

                if (!items || items.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    if (itemsContainer) itemsContainer.innerHTML = '';
                    return;
                }

                if (empty) empty.classList.add('hidden');
                if (!itemsContainer) return;

                let html = '';
                items.forEach(item => {
                    const isUnread = !item.is_read;
                    const unreadClasses = isUnread 
                        ? 'bg-[var(--primary-50)]/50 dark:bg-emerald-950/20 border-l-4 border-l-[var(--primary-500)] hover:bg-[var(--primary-50)] dark:hover:bg-emerald-950/30' 
                        : 'bg-[var(--surface)] hover:bg-[var(--bg-page)] opacity-90';

                    const iconColor = isUnread
                        ? 'bg-[var(--primary-100)] text-[var(--primary-700)] dark:bg-[var(--primary-800)] dark:text-[var(--primary-200)]'
                        : 'bg-[var(--border)] text-[var(--text-muted)]';

                    const dotHtml = isUnread
                        ? '<span class="w-2 h-2 rounded-full bg-[var(--primary-500)] ring-2 ring-[var(--surface)] shrink-0"></span>'
                        : '';

                    html += `
                        <a href="${item.url}" 
                           onclick="markNotificationAsReadAndRedirect(event, ${item.id}, '${item.url}')" 
                           class="block px-4 py-3 transition-colors cursor-pointer ${unreadClasses}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full ${iconColor} flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <p class="text-xs font-bold text-[var(--text-heading)] truncate flex items-center gap-1.5">
                                            ${dotHtml}
                                            <span>${escapeHtml(item.title)}</span>
                                        </p>
                                        <span class="text-[10px] text-[var(--text-muted)] shrink-0 font-medium">${item.time_ago || ''}</span>
                                    </div>
                                    <p class="text-[11.5px] text-[var(--text-body)] leading-relaxed line-clamp-2">
                                        ${escapeHtml(item.message)}
                                    </p>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-[9.5px] font-semibold text-[var(--primary-600)]">Verifikasi Pembayaran &rarr;</span>
                                        ${isUnread ? '<span class="text-[9.5px] px-1 bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 rounded-[2px] font-semibold">Belum Dibaca</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        </a>
                    `;
                });

                itemsContainer.innerHTML = html;
            }

            // Mark single as read
            window.markNotificationAsRead = function(id) {
                fetch(`/backend/keuangan/notifikasi/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': NOTIF_CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                }).catch(() => {});
            };

            window.markNotificationAsReadAndRedirect = function(event, id, targetUrl) {
                if (event) {
                    event.preventDefault();
                }
                markNotificationAsRead(id);
                setTimeout(() => {
                    window.location.href = targetUrl || NOTIF_CONFIG.verifUrl;
                }, 80);
            };

            // Mark all as read
            window.markAllNotificationsAsRead = function() {
                const btn = document.getElementById('btn-mark-all-read');
                if (btn) {
                    btn.classList.add('opacity-50', 'pointer-events-none');
                }

                fetch(NOTIF_CONFIG.readAllUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': NOTIF_CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (btn) btn.classList.remove('opacity-50', 'pointer-events-none');
                    if (data.success) {
                        updateBadges(0);
                        notifState.unreadCount = 0;
                        notifState.items.forEach(item => item.is_read = true);
                        renderNotificationList(notifState.items);
                    }
                })
                .catch(() => {
                    if (btn) btn.classList.remove('opacity-50', 'pointer-events-none');
                });
            };

            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.toString().replace(/[&<>"']/g, m => map[m]);
            }

            // Close on outside click
            document.addEventListener('click', function(e) {
                const wrapper = document.getElementById('notif-dropdown-wrapper');
                if (wrapper && !wrapper.contains(e.target)) {
                    closeNotificationDropdown();
                }
            });

            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeNotificationDropdown();
                }
            });

            // Initialize on DOM Ready
            document.addEventListener('DOMContentLoaded', function() {
                // Check if browser permission banner should show
                if ('Notification' in window && Notification.permission === 'default') {
                    if (sessionStorage.getItem('dismiss_notif_banner') !== 'true') {
                        const banner = document.getElementById('notif-permission-banner');
                        if (banner) banner.classList.remove('hidden');
                    }
                }

                // Initial fetch
                fetchNotifications(false);

                // Polling
                setInterval(() => {
                    if (document.visibilityState === 'visible') {
                        fetchNotifications(true);
                    }
                }, NOTIF_CONFIG.pollIntervalMs);

                // Recheck immediately when window gains focus
                document.addEventListener('visibilitychange', function() {
                    if (document.visibilityState === 'visible') {
                        fetchNotifications(true);
                    }
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
