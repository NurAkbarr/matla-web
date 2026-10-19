<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Keuangan') - Matla Backend</title>
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
                    <button onclick="closeSidebar()" class="lg:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-[var(--primary-700)] focus:outline-none" aria-label="Tutup menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Sidebar Scrollable Menu --}}
                <nav class="flex-1 overflow-y-auto scrollbar-hide py-5 px-3.5 space-y-1">
                    {{-- SISTEM KEUANGAN --}}
                    <p class="px-3 pt-3 pb-2 text-[10px] font-semibold text-[var(--primary-300)]/70 dark:text-emerald-300/50 uppercase tracking-widest">Sistem Keuangan</p>

                    <a href="{{ route('backend.keuangan.dashboard') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.keuangan.dashboard') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('backend.keuangan.dashboard') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('backend.keuangan.mahasiswa.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.keuangan.mahasiswa.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('backend.keuangan.mahasiswa.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-sm">Data Mahasiswa</span>
                    </a>

                    <a href="{{ route('backend.keuangan.verifikasi.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.keuangan.verifikasi.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 self-center {{ request()->routeIs('backend.keuangan.verifikasi.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm leading-snug">Verifikasi Pembayaran</span>
                    </a>

                    <a href="{{ route('backend.keuangan.tagihan.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.keuangan.tagihan.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('backend.keuangan.tagihan.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-sm">Manajemen Tagihan</span>
                    </a>

                    <a href="{{ route('backend.keuangan.laporan.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-150 group {{ request()->routeIs('backend.keuangan.laporan.*') ? 'bg-[var(--primary-500)] text-white shadow-sm border-l-4 border-[var(--secondary-500)] font-semibold' : 'text-white/75 hover:bg-[var(--primary-700)] hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('backend.keuangan.laporan.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm">Laporan Keuangan</span>
                    </a>
                </nav>

                {{-- Logout Button --}}
                <div class="p-3 border-t border-white/10 shrink-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-white/75 hover:text-white hover:bg-[var(--primary-700)] dark:hover:bg-rose-950/30 transition-colors text-sm font-medium">
                            <svg class="w-5 h-5 shrink-0 text-white/60 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT AREA ===== --}}
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                
                {{-- Topbar (Fixed Height) --}}
                <header class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3.5 shrink-0 z-30 transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        {{-- Hamburger Button (mobile only, 44x44px target) --}}
                        <button id="hamburger-btn" 
                                onclick="openSidebar()"
                                class="min-w-[44px] min-h-[44px] flex items-center justify-center p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-100)] transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--primary-500)]/20 lg:hidden"
                                aria-label="Buka menu"
                                aria-expanded="false">
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
                            @yield('title', 'Dashboard Keuangan')
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
                                <p class="text-sm font-semibold text-[var(--text-heading)] capitalize leading-tight">{{ Auth::user()->name ?? 'Staff Keuangan' }}</p>
                                <p class="text-[11px] text-[var(--text-muted)] font-normal mt-0.5">{{ date('l, d M Y') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-[var(--primary-100)] text-[var(--primary-600)] dark:text-[var(--primary-300)] flex items-center justify-center font-bold text-sm shadow-sm ring-1 ring-[var(--border)]">
                                <span>{{ substr(Auth::user()->name ?? 'K', 0, 1) }}</span>
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
            var btn = document.getElementById('hamburger-btn');
            if (sidebar && overlay) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0', 'shadow-2xl');
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
                sidebar.classList.remove('translate-x-0', 'shadow-2xl');
                overlay.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        }

        // Close sidebar on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

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
