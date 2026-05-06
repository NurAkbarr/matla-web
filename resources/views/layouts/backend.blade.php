<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matla Backend</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo-bulat.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        'primary-dark': '#065f46',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

        {{-- ===== MOBILE SIDEBAR OVERLAY ===== --}}
        <div x-show="sidebarOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false">
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col
                       sidebar-transition
                       lg:static lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'">

            {{-- Logo & Close Button --}}
            <div class="p-6 flex items-center justify-between mb-2 border-b border-gray-50">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-9 w-auto">
                    <span class="text-xl font-extrabold text-primary-dark tracking-wider">MATLA</span>
                </div>
                {{-- Close button (mobile only) --}}
                <button @click="sidebarOpen = false"
                        class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-4">
                {{-- SISTEM UTAMA --}}
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem Utama</p>
                <a href="{{ route('backend.admin.dashboard') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                <a href="{{ route('backend.admin.users.index') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.users.*') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm">Manajemen User</span>
                </a>
                @endif

                {{-- PMB Dropdown --}}
                <div x-data="{ open: {{ request()->routeIs('backend.admin.pmb.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.pmb.*') ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm">PMB</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-12 space-y-1 mt-1">
                        <a href="{{ route('backend.admin.pmb.registrations.index') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.pmb.registrations.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Daftar Pendaftar</a>
                        <a href="{{ route('backend.admin.pmb.brosur.index') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.pmb.brosur.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Manajemen Brosur</a>
                        <a href="{{ route('backend.admin.pmb.settings') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.pmb.settings') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Pengaturan PMB</a>
                    </div>
                </div>

                {{-- AKADEMIK Dropdown --}}
                <div x-data="{ open: {{ (request()->routeIs('backend.admin.mahasiswa') || request()->routeIs('backend.admin.dosen') || request()->routeIs('backend.admin.jadwal.*') || request()->routeIs('backend.admin.program-studi.*') || request()->routeIs('backend.admin.rekap-honor.*')) ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ (request()->routeIs('backend.admin.mahasiswa') || request()->routeIs('backend.admin.dosen') || request()->routeIs('backend.admin.jadwal.*') || request()->routeIs('backend.admin.program-studi.*') || request()->routeIs('backend.admin.rekap-honor.*')) ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm">Akademik</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-12 space-y-1 mt-1">
                        <a href="{{ route('backend.admin.mahasiswa') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.mahasiswa') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Manajemen Mahasiswa</a>
                        <a href="{{ route('backend.admin.dosen') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.dosen') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Data Dosen</a>
                        <a href="{{ route('backend.admin.rekap-honor.index') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.rekap-honor.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Rekap Honor Dosen</a>
                        <a href="{{ route('backend.admin.jadwal.index') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.jadwal.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Jadwal Perkuliahan</a>
                        <a href="{{ route('backend.admin.program-studi.index') }}" @click="sidebarOpen = false" class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.program-studi.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Program Studi</a>
                    </div>
                </div>

                {{-- MANAJEMEN KONTEN --}}
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Konten</p>
                <a href="{{ route('backend.admin.messages.index') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.messages.*') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8zM3 8l9 6 9-6" />
                    </svg>
                    <span class="text-sm">Kotak Pesan</span>
                </a>

                @if(Auth::user()->role === 'super_admin')
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">System</p>
                <a href="{{ route('backend.admin.maintenance') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.maintenance') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">Maintenance</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

            {{-- Header --}}
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 sticky top-0 z-30">
                <div class="flex items-center space-x-3">
                    {{-- Hamburger Button (mobile only) --}}
                    <button @click.stop="sidebarOpen = true"
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
                    <h2 class="text-sm font-bold text-gray-500 hidden lg:block uppercase tracking-wider">
                        @yield('breadcrumb', 'Backend Portal')
                    </h2>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-900 capitalize">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden shadow-sm border-2 border-primary/20 flex-shrink-0">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8 flex-1"
                  x-data="{ showAlert: {{ session('success') ? 'true' : 'false' }}, alertMessage: '{{ session('success') }}' }">

                {{-- Success Modal --}}
                <div x-show="showAlert" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="showAlert = false"
                         x-show="showAlert"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"></div>
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-sm w-full p-8 sm:p-10 text-center border border-white"
                         x-show="showAlert"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-5 ring-8 ring-emerald-50/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 tracking-tight">Berhasil!</h3>
                        <p class="text-gray-500 text-sm mb-6 sm:mb-8 leading-relaxed font-semibold italic" x-text="alertMessage"></p>
                        <button @click="showAlert = false"
                                class="w-full py-3 sm:py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 active:scale-95">
                            Lanjutkan
                        </button>
                    </div>
                </div>

                @yield('content')
            </main>

            {{-- Mini Footer --}}
            <footer class="px-4 sm:px-8 py-4 border-t border-gray-100">
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
</body>
</html>
