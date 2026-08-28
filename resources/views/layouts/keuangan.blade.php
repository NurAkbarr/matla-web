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
               class="fixed inset-y-0 left-0 z-50 w-60 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shrink-0 overflow-hidden"
               style="background: linear-gradient(180deg, #004d29 0%, #00703C 40%, #005a30 100%);">
            
            {{-- SVG Background Pattern --}}
            <div class="absolute inset-0 opacity-[0.06] pointer-events-none overflow-hidden">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="sidebar-pattern" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
                            {{-- Calculator --}}
                            <rect x="10" y="10" width="20" height="28" rx="3" fill="none" stroke="white" stroke-width="1.5"/>
                            <rect x="14" y="14" width="12" height="6" rx="1" fill="white" opacity="0.5"/>
                            <circle cx="16" cy="26" r="1.5" fill="white"/>
                            <circle cx="20" cy="26" r="1.5" fill="white"/>
                            <circle cx="24" cy="26" r="1.5" fill="white"/>
                            <circle cx="16" cy="32" r="1.5" fill="white"/>
                            <circle cx="20" cy="32" r="1.5" fill="white"/>
                            <circle cx="24" cy="32" r="1.5" fill="white"/>
                            {{-- Chart --}}
                            <rect x="55" y="25" width="5" height="15" rx="1" fill="white"/>
                            <rect x="63" y="18" width="5" height="22" rx="1" fill="white"/>
                            <rect x="71" y="30" width="5" height="10" rx="1" fill="white"/>
                            {{-- Dollar Sign --}}
                            <circle cx="90" cy="80" r="12" fill="none" stroke="white" stroke-width="1.5"/>
                            <text x="90" y="85" text-anchor="middle" fill="white" font-size="14" font-weight="bold">$</text>
                            {{-- Document --}}
                            <rect x="15" y="70" width="18" height="24" rx="2" fill="none" stroke="white" stroke-width="1.5"/>
                            <line x1="19" y1="78" x2="29" y2="78" stroke="white" stroke-width="1"/>
                            <line x1="19" y1="82" x2="29" y2="82" stroke="white" stroke-width="1"/>
                            <line x1="19" y1="86" x2="26" y2="86" stroke="white" stroke-width="1"/>
                            {{-- Gear --}}
                            <circle cx="70" cy="75" r="6" fill="none" stroke="white" stroke-width="1.5"/>
                            <circle cx="70" cy="75" r="2.5" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#sidebar-pattern)"/>
                </svg>
            </div>

            {{-- Logo Area --}}
            <div class="relative z-10 p-5 pb-6 flex items-center justify-between shrink-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-9 w-auto">
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-white tracking-tighter leading-none uppercase">MATLA</span>
                        <span class="text-[8px] font-bold text-emerald-200/80 uppercase tracking-widest mt-0.5">University Portal</span>
                    </div>
                </div>
                <button onclick="closeSidebar()" class="lg:hidden p-2 text-emerald-200 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Sidebar Scrollable Menu --}}
            <nav class="relative z-10 flex-1 overflow-y-auto scrollbar-hide py-4 px-3 space-y-1">
                <p class="px-3 pt-2 pb-3 text-[10px] font-bold text-emerald-300/70 uppercase tracking-widest">Sistem Keuangan</p>

                <a href="{{ route('backend.keuangan.dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group {{ request()->routeIs('backend.keuangan.dashboard') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="text-[13px] font-bold">Dashboard</span>
                </a>

                <a href="{{ route('backend.keuangan.mahasiswa.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group {{ request()->routeIs('backend.keuangan.mahasiswa.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-[13px] font-bold">Data Mahasiswa</span>
                </a>

                <a href="{{ route('backend.keuangan.verifikasi.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group {{ request()->routeIs('backend.keuangan.verifikasi.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[13px] font-bold">Verifikasi Pembayaran</span>
                </a>

                <a href="{{ route('backend.keuangan.tagihan.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group {{ request()->routeIs('backend.keuangan.tagihan.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-[13px] font-bold">Manajemen Tagihan</span>
                </a>

                <a href="{{ route('backend.keuangan.laporan.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group {{ request()->routeIs('backend.keuangan.laporan.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-[13px] font-bold">Laporan Keuangan</span>
                </a>
            </nav>

            {{-- Logout --}}
            <div class="relative z-10 px-3 pb-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-emerald-300 hover:bg-white/10 hover:text-white transition-all font-bold">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-[13px]">Logout</span>
                    </button>
                </form>
            </div>
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
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-100 via-slate-200/50 to-slate-200">
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
