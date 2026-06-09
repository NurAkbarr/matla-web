<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Matla Islamic University')</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/logo-bulat.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

        <!-- Alpine.js -->


        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white font-sans text-text-dark" x-data="{ mobileMenuOpen: false }">
        <!-- Navigation Bar -->
        <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 font-sans">
            <div class="container mx-auto px-4 lg:px-12 py-3 lg:py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-8 lg:h-10 w-auto">
                        <span class="text-lg lg:text-xl font-bold font-sans text-primary-dark tracking-wide">MATLA</span>
                    </a>

                    <!-- Desktop Navigation Items -->
                    @php
                        $isMahasiswa = request()->routeIs('backend.mahasiswa.*') || request()->is('mahasiswa/*');
                    @endphp
                    @if(!$isMahasiswa)
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="{{ url('/') }}#beranda" class="font-semibold text-gray-600 hover:text-primary transition-colors">Beranda</a>
                        <a href="{{ url('/') }}#tentang" class="font-semibold text-gray-600 hover:text-primary transition-colors">Tentang</a>
                        <a href="{{ url('/') }}#keunggulan" class="font-semibold text-gray-600 hover:text-primary transition-colors">Keunggulan</a>
                        
                        <!-- Dropdown Menu -->
                        <div class="relative group">
                            <button class="flex items-center space-x-1 font-semibold text-gray-600 hover:text-primary transition-colors outline-none pb-1">
                                <span>Informasi</span>
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden transform group-hover:translate-y-0 translate-y-2">
                                <div class="p-2 space-y-1">
                                    <a href="{{ route('informasi.prodi') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">Program Studi</a>
                                    <a href="{{ route('informasi.karya-mahasiswa') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">Karya Mahasiswa</a>
                                    <a href="{{ route('informasi.karya-dosen') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">Karya Dosen</a>
                                    <a href="{{ route('informasi.staf-pengajar') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">Staf Pengajar</a>
                                    <a href="{{ route('informasi.galeri') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">Galeri</a>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-1 font-semibold text-gray-600 hover:text-primary transition-colors outline-none pb-1">
                                <span>Layanan</span>
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden transform group-hover:translate-y-0 translate-y-2">
                                <div class="p-2 space-y-1">
                                    <a href="{{ route('informasi.leaderboard') }}" class="flex items-center space-x-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">
                                        <svg class="w-4 h-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        <span>Duta Kampus</span>
                                    </a>
                                    <a href="https://bendahara.matla.id/login-form" target="_blank" class="flex items-center space-x-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">
                                        <svg class="w-4 h-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                        <span>Kuitansi Matla</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pmb') }}" class="font-semibold text-gray-600 hover:text-primary transition-colors">PMB</a>
                        <a href="{{ route('kontak') }}" class="font-semibold text-gray-600 hover:text-primary transition-colors">Kontak</a>
                    </div>
                    @endif



                    <!-- Login Button & Hamburger -->
                    <div class="flex items-center space-x-3 lg:space-x-4">
                        @auth
                            @php
                                $dashboardRoute = match(Auth::user()->role) {
                                    'super_admin', 'admin' => route('backend.admin.dashboard'),
                                    'dosen' => route('backend.dosen.dashboard'),
                                    'mahasiswa' => route('backend.mahasiswa.dashboard'),
                                    default => url('/'),
                                };
                            @endphp
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                @if(Auth::user()->role === 'mahasiswa')
                                    <!-- User Profile Dropdown (Consolidated for Desktop & Mobile) -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="flex items-center space-x-2 sm:space-x-3 pl-2 sm:pl-3 border-l border-gray-100 outline-none group">
                                            <div class="text-right hidden md:block">
                                                <p class="text-sm font-bold text-gray-800 leading-tight group-hover:text-primary transition-colors">{{ Auth::user()->name }}</p>
                                                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">{{ Auth::user()->role }}</p>
                                            </div>
                                            <div class="relative">
                                                <img src="{{ Auth::user()->foto_profil }}" alt="Profile" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-emerald-50 shadow-sm group-hover:ring-primary/20 transition-all">
                                                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 bg-emerald-500 border-2 border-white rounded-full"></div>
                                            </div>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" 
                                             @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden" x-cloak>
                                            
                                            <div class="p-4 bg-gray-50/50 border-b border-gray-50">
                                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Akun Saya</p>
                                                <p class="text-sm font-black text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                            </div>

                                            <div class="p-2">
                                                <a href="{{ route('mahasiswa.profil') }}" class="flex items-center space-x-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-xl transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    <span>Edit Profil</span>
                                                </a>
                                                
                                                <div class="h-px bg-gray-100 my-1 mx-2"></div>

                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-colors text-left">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                        <span>Keluar Aplikasi</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ $dashboardRoute }}" class="px-6 py-2.5 bg-emerald-50 text-primary rounded-lg font-semibold hover:bg-emerald-100 transition-all">
                                        Dashboard
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-block px-6 lg:px-8 py-2 lg:py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold transition-all">
                                Login
                            </a>
                        @endauth

                        
                        <!-- Mobile Hamburger -->
                        @if(!$isMahasiswa)
                            <button id="mobile-menu-button" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path id="hamburger-icon" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 space-y-2 border-t border-gray-100 pt-4 max-h-[80vh] overflow-y-auto">
                    @if(request()->routeIs('backend.mahasiswa.*') || request()->is('mahasiswa/*'))
                        <form action="{{ route('logout') }}" method="POST" class="px-4 py-2">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 font-bold">Keluar</button>
                        </form>
                    @else
                        <a href="{{ url('/') }}#beranda" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Beranda</a>
                        <a href="{{ url('/') }}#tentang" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Tentang</a>
                        <a href="{{ url('/') }}#keunggulan" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Keunggulan</a>
                    @endif
                    
                    <!-- Mobile Informas Dropdown -->
                    <div class="px-4 py-1">
                        <button id="mobile-informasi-btn" class="flex items-center justify-between w-full py-2 text-gray-600 font-medium hover:text-primary transition-all">
                            <span>Informasi</span>
                            <svg id="mobile-informasi-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="mobile-informasi-menu" class="hidden mt-1 ml-4 space-y-1 border-l-2 border-emerald-100 pl-4">
                            <a href="{{ route('informasi.prodi') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Program Studi</a>
                            <a href="{{ route('informasi.karya-mahasiswa') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Karya Mahasiswa</a>
                            <a href="{{ route('informasi.karya-dosen') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Karya Dosen</a>
                            <a href="{{ route('informasi.staf-pengajar') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Staf Pengajar</a>
                            <a href="{{ route('informasi.galeri') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Galeri</a>
                        </div>
                    </div>

                    <a href="{{ route('pmb') }}" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">PMB</a>

                    <!-- Mobile Layanan Dropdown -->
                    <div class="px-4 py-1">
                        <button id="mobile-layanan-btn" class="flex items-center justify-between w-full py-2 text-gray-600 font-medium hover:text-primary transition-all">
                            <span>Layanan</span>
                            <svg id="mobile-layanan-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="mobile-layanan-menu" class="hidden mt-1 ml-4 space-y-1 border-l-2 border-emerald-100 pl-4">
                            <a href="{{ route('informasi.leaderboard') }}" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Duta Kampus</a>
                            <a href="https://bendahara.matla.id/login-form" target="_blank" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">Kuitansi Matla</a>
                        </div>
                    </div>

                    <a href="{{ route('kontak') }}" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Kontak</a>
                    <div class="px-4 pt-2">
                        @auth
                            @php
                                $dashboardRoute = match(Auth::user()->role) {
                                    'super_admin', 'admin' => route('backend.admin.dashboard'),
                                    'dosen' => route('backend.dosen.dashboard'),
                                    'mahasiswa' => route('backend.mahasiswa.dashboard'),
                                    default => url('/'),
                                };
                            @endphp
                            <a href="{{ $dashboardRoute }}" class="block w-full text-center px-4 py-2.5 bg-emerald-50 text-primary rounded-lg font-semibold mb-2">Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-center px-4 py-2.5 bg-red-50 text-red-600 rounded-lg font-semibold">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 bg-primary text-white rounded-lg font-semibold">Login</a>
                        @endauth
                    </div>
                </div>


            </div>
        </nav>

        <main>
            <!-- Global Flash Messages -->
            @if(session('error') || session('success'))
            <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[60] w-[90%] max-w-md animate-in fade-in slide-in-from-top-4 duration-500" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="{{ session('error') ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} border p-4 rounded-2xl shadow-xl flex items-start space-x-3 backdrop-blur-md">
                    @if(session('error'))
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @else
                        <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-bold">{{ session('error') ?? session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>

        @if(!request()->routeIs(['login', 'register']))
        <!-- Floating WhatsApp Button -->
        <a href="https://wa.me/6287784538820?text=Assalamu%27alaikum%20Warahmatullahi%20Wabarakatuh%20%F0%9F%8C%99%0A%0AHalo%20Admin%20Matla%20Islamic%20University%2C%20saya%20ingin%20bertanya%20mengenai%20informasi%20perkuliahan.%20Boleh%20saya%20minta%20bantuannya%3F%20%F0%9F%99%8F" target="_blank" class="fixed bottom-6 right-6 lg:bottom-8 lg:right-8 z-50 group">
            <div class="relative">
                <div class="absolute inset-0 bg-[#25D366] rounded-full blur-md opacity-40 group-hover:opacity-70 transition-opacity"></div>
                <div class="relative bg-[#25D366] hover:bg-[#20BA5A] text-white p-3.5 lg:p-4 rounded-full shadow-2xl transition-transform hover:scale-110">
                    <!-- Official WhatsApp Logo SVG -->
                    <svg class="w-7 h-7 lg:w-8 lg:h-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4C12.954 4 4 12.954 4 24c0 3.542.929 6.874 2.562 9.76L4 44l10.51-2.524A19.87 19.87 0 0024 44c11.046 0 20-8.954 20-20S35.046 4 24 4z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 7.5C14.835 7.5 7.5 14.835 7.5 24c0 3.163.876 6.12 2.404 8.643l.378.63-1.607 5.874 6.044-1.584.61.362A16.432 16.432 0 0024 40.5c9.165 0 16.5-7.335 16.5-16.5S33.165 7.5 24 7.5z" fill="#25D366"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M18.738 15c-.43-.005-.908.163-1.358.613-.447.447-1.706 1.668-1.706 4.066 0 2.4 1.745 4.72 1.988 5.047.243.326 3.38 5.317 8.289 7.25 1.033.4 1.84.638 2.467.817.988.282 1.888.242 2.598.147.793-.107 2.44-.997 2.783-1.96.344-.965.344-1.793.241-1.966-.102-.173-.375-.276-.786-.482-.41-.207-2.43-1.2-2.807-1.337-.376-.138-.649-.207-.922.207-.274.414-1.06 1.337-1.3 1.612-.24.276-.481.311-.892.104-.41-.207-1.733-.64-3.3-2.038-1.22-1.088-2.044-2.432-2.284-2.846-.24-.414-.026-.638.18-.844.186-.185.41-.482.616-.723.205-.24.274-.414.41-.689.137-.276.069-.517-.034-.723-.103-.207-.921-2.224-1.264-3.044-.333-.8-.67-.69-.921-.703l-.786-.013z" fill="white"/>
                    </svg>
                </div>
            </div>
        </a>
        @endif


        @if(!request()->routeIs(['login', 'register']))
        <!-- Main Footer -->
        <footer class="bg-gray-50 border-t border-gray-100 {{ request()->routeIs(['backend.*', 'mahasiswa.*']) ? 'pt-8 pb-8' : 'pt-12 sm:pt-16 pb-8' }}">
            <div class="container mx-auto px-5 sm:px-6 lg:px-12">
                @if(!request()->routeIs(['backend.*', 'mahasiswa.*']))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 sm:gap-12 lg:gap-8 mb-10 sm:mb-16">
                    <!-- Column 1: Tentang -->
                    <div>
                        <a href="#beranda" class="flex items-center space-x-2 mb-4">
                            <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-8 w-auto">
                            <span class="text-xl font-bold text-primary-dark tracking-wide uppercase">MATLA</span>
                        </a>
                        <p class="text-sm font-bold text-[#1F2937] mb-3 leading-snug">
                            Matla Islamic University — Kampus Islam Online Terdepan di Indonesia
                        </p>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Matla adalah kampus Islam online yang menyediakan program Bahasa Arab dan S1 Pendidikan Agama Islam yang fleksibel, terjangkau, dan bersanad.
                        </p>
                    </div>

                    <!-- Column 2: Program -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1F2937] mb-6">Program</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">Bahasa Arab</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">S1 Pendidikan Agama Islam</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Link Cepat -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1F2937] mb-6">Link Cepat</h4>
                        <ul class="space-y-4">
                            <li><a href="#beranda" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">Beranda</a></li>
                            <li><a href="#tentang" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">Tentang Kami</a></li>
                            <li><a href="#keunggulan" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">Keunggulan</a></li>
                            <li><a href="/pmb" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">PMB</a></li>
                            <li><a href="#kontak" class="text-gray-500 hover:text-primary transition-colors text-sm font-medium">Kontak</a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Kontak & Sosmed -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1F2937] mb-6">Kontak</h4>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center space-x-3 text-gray-600 text-[15px]">
                                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 1.846.503 3.576 1.385 5.073L2 22l5.073-1.385A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.333a8.307 8.307 0 01-4.24-1.157l-.304-.18-3.153.86.86-3.153-.18-.304A8.307 8.307 0 013.667 12C3.667 7.405 7.405 3.667 12 3.667c4.595 0 8.333 3.738 8.333 8.333S16.595 20.333 12 20.333zm4.594-5.91c-.252-.126-1.488-.735-1.718-.82-.23-.083-.398-.126-.566.126-.168.252-.648.82-.795.988-.147.168-.293.189-.545.063-.252-.126-1.06-.391-2.02-1.246-.747-.665-1.252-1.488-1.4-1.74-.147-.252-.016-.388.11-.514.113-.113.252-.294.378-.44.126-.147.168-.252.252-.42.084-.168.042-.315-.021-.441-.063-.126-.566-1.365-.775-1.87-.204-.492-.412-.425-.566-.433-.147-.008-.315-.008-.483-.008-.168 0-.441.063-.672.315-.23.252-.881.861-.881 2.099s.902 2.435 1.028 2.603c.126.168 1.776 2.71 4.3 3.8.6.258 1.068.412 1.434.527.602.191 1.15.164 1.583.1.485-.072 1.488-.609 1.698-1.197.21-.588.21-1.092.147-1.197-.063-.105-.23-.168-.482-.294z" fill="#25D366"/>
                                </svg>
                                <a href="tel:+6287784538820" class="hover:text-[#25D366] transition-colors font-medium">0877 8453 8820</a>
                            </li>
                            <li class="flex items-center space-x-3 text-gray-600 text-[15px]">
                                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6ZM20 6L12 11L4 6H20ZM20 18H4V8L12 13L20 8V18Z" fill="#EA4335"/>
                                </svg>
                                <a href="mailto:matlaislamicuniversity@gmail.com" class="hover:text-[#EA4335] transition-colors font-medium break-all">matlaislamicuniversity@gmail.com</a>
                            </li>
                            <li class="flex items-center space-x-3 text-gray-600 text-[15px]">
                                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 19.93C7.05 19.43 4 16.05 4 12C4 11.53 4.04 11.08 4.12 10.63L8.5 15V16C8.5 17.1 9.4 18 10.5 18V19.93H11ZM17.41 17.59C17.14 16.66 16.29 16 15.28 16H14.5V13C14.5 12.45 14.05 12 13.5 12H9.5V10H11.5C12.05 10 12.5 9.55 12.5 9V7H14.5C15.6 7 16.5 6.1 16.5 5V4.68C18.84 6.2 20.25 8.84 20.25 11.75C20.25 14.08 19.17 16.14 17.41 17.59Z" fill="#1A73E8"/>
                                </svg>
                                <a href="https://matla.id" target="_blank" class="hover:text-[#1A73E8] transition-colors font-medium">matla.id</a>
                            </li>
                        </ul>
                        
                        <h4 class="text-sm font-bold text-[#1F2937] mb-4">Sosmed</h4>
                        <div class="flex space-x-4">
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/kampusmatla?igsh=dzhyZXVqem5ndjQ5" target="_blank" class="hover:opacity-80 transition-opacity transform hover:scale-110" title="Instagram">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="ig-grad" x1="2" y1="2" x2="22" y2="22" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#f09433"/>
                                            <stop offset="0.25" stop-color="#e6683c"/>
                                            <stop offset="0.5" stop-color="#dc2743"/>
                                            <stop offset="0.75" stop-color="#cc2366"/>
                                            <stop offset="1" stop-color="#bc1888"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm3.975-9.61a1.44 1.44 0 100-2.88 1.44 1.44 0 000 2.88z" fill="url(#ig-grad)"/>
                                </svg>
                            </a>
                            <!-- YouTube -->
                            <a href="#" class="hover:opacity-80 transition-opacity transform hover:scale-110" title="YouTube">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.58 7.19A2.71 2.71 0 0019.67 5.3C17.98 4.83 12 4.83 12 4.83s-5.98 0-7.67.47A2.71 2.71 0 002.42 7.19C1.96 8.87 1.96 12 1.96 12s0 3.13.46 4.81a2.71 2.71 0 001.91 1.89C5.98 19.17 12 19.17 12 19.17s5.98 0 7.67-.47a2.71 2.71 0 001.91-1.89c.46-1.68.46-4.81.46-4.81s0-3.13-.46-4.81z" fill="#FF0000"/>
                                    <path d="M9.98 15.18L15.2 12l-5.22-3.18v6.36z" fill="#FFFFFF"/>
                                </svg>
                            </a>
                            <!-- Telegram -->
                            <a href="#" class="hover:opacity-80 transition-opacity transform hover:scale-110" title="Telegram">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" fill="#2AABEE"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Copyright -->
                <div class="{{ !request()->routeIs(['backend.*', 'mahasiswa.*']) ? 'pt-8 border-t border-gray-100' : '' }} flex justify-center items-center text-center">
                    <p class="text-gray-500 text-sm font-medium">
                        &copy; 2026 Matla Islamic University. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
        @endif



        <!-- Scripts -->
        <script>
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');

            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Mobile Informasi Dropdown Toggle
            const mobileInformasiBtn = document.getElementById('mobile-informasi-btn');
            const mobileInformasiMenu = document.getElementById('mobile-informasi-menu');
            const mobileInformasiIcon = document.getElementById('mobile-informasi-icon');

            if (mobileInformasiBtn) {
                mobileInformasiBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mobileInformasiMenu.classList.toggle('hidden');
                    mobileInformasiIcon.classList.toggle('rotate-180');
                });
            }

            // Mobile Layanan Dropdown Toggle
            const mobileLayananBtn = document.getElementById('mobile-layanan-btn');
            const mobileLayananMenu = document.getElementById('mobile-layanan-menu');
            const mobileLayananIcon = document.getElementById('mobile-layanan-icon');

            if (mobileLayananBtn) {
                mobileLayananBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mobileLayananMenu.classList.toggle('hidden');
                    mobileLayananIcon.classList.toggle('rotate-180');
                });
            }

            // Close menu when a link is clicked
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    if (mobileInformasiMenu) {
                        mobileInformasiMenu.classList.add('hidden');
                        mobileInformasiIcon.classList.remove('rotate-180');
                    }
                });
            });

        </script>
        @stack('scripts')
        
        @if(Auth::check() && Auth::user()->role === 'mahasiswa')
            @include('partials.mobile-bottom-nav')
        @endif
    </body>
</html>
