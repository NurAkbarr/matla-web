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
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white font-sans text-text-dark">
        <!-- Navigation Bar -->
        <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
            <div class="container mx-auto px-4 lg:px-12 py-3 lg:py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="#beranda" class="flex items-center space-x-2">
                        <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-8 lg:h-10 w-auto">
                        <span class="text-lg lg:text-xl font-bold text-primary-dark tracking-wide">MATLA</span>
                    </a>

                    <!-- Desktop Navigation Items -->
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
                                    <a href="https://ktm.matla.id/ktm/login" target="_blank" class="flex items-center space-x-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-primary rounded-lg transition-colors">
                                        <svg class="w-4 h-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                                        <span>KTM Digital</span>
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
                            <div class="hidden sm:flex items-center space-x-3">
                                <a href="{{ $dashboardRoute }}" class="px-6 py-2.5 bg-emerald-50 text-primary rounded-lg font-semibold hover:bg-emerald-100 transition-all">
                                    Dashboard
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:inline-block px-6 lg:px-8 py-2 lg:py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold transition-all">
                                Login
                            </a>
                        @endauth

                        
                        <!-- Mobile Hamburger -->
                        <button id="mobile-menu-button" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="hamburger-icon" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 space-y-2 border-t border-gray-100 pt-4 max-h-[80vh] overflow-y-auto">
                    <a href="{{ url('/') }}#beranda" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Beranda</a>
                    <a href="{{ url('/') }}#tentang" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Tentang</a>
                    <a href="{{ url('/') }}#keunggulan" class="mobile-nav-link block px-4 py-2 text-gray-600 font-medium rounded-md hover:bg-gray-50 hover:text-primary transition-all">Keunggulan</a>
                    
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
                            <a href="https://ktm.matla.id/ktm/login" target="_blank" class="block py-2 text-sm font-medium text-gray-500 hover:text-primary">KTM Digital</a>
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
        <footer class="bg-gray-50 border-t border-gray-100 pt-16 pb-8">
            <div class="container mx-auto px-4 lg:px-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
                    <!-- Column 1: Brand & Contact -->
                    <div class="lg:col-span-2">
                        <a href="#beranda" class="flex items-center space-x-2 mb-6">
                            <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-8 w-auto">
                            <span class="text-xl font-bold text-primary-dark tracking-wide uppercase">MATLA</span>
                        </a>
                        <p class="text-lg font-bold text-[#1F2937] mb-2">
                            Matla Islamic University - Kampus Islam Online
                        </p>
                        <div class="space-y-3 text-gray-500">
                            <p class="flex items-start">
                                <span class="max-w-xs">Jl. Conspet No. 123, Kota Santri, Indonesia.</span>
                            </p>
                            <p>
                            <a href="mailto:matlaislamicuniversity@gmail.com" class="hover:text-primary transition-colors">matlaislamicuniversity@gmail.com</a>
                            </p>
                            <p>
                                <a href="tel:+6287784538820" class="hover:text-primary transition-colors">+62 877-8453-8820</a>
                            </p>
                        </div>
                    </div>

                    <!-- Column 2: Menu -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1F2937] mb-6">Menu</h4>
                        <ul class="space-y-4">
                            <li><a href="#tentang" class="text-gray-500 hover:text-primary transition-colors">Tentang</a></li>
                            <li><a href="#keunggulan" class="text-gray-500 hover:text-primary transition-colors">Program</a></li>
                            <li><a href="#pmb" class="text-gray-500 hover:text-primary transition-colors">Informasi</a></li>
                            <li><a href="#kontak" class="text-gray-500 hover:text-primary transition-colors">Kontak</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Social Media -->
                    <div>
                        <h4 class="text-lg font-bold text-[#1F2937] mb-6">Ikuti Kami</h4>
                        <ul class="space-y-4">
                            <li>
                                <a href="#" class="flex items-center space-x-3 text-gray-500 hover:text-primary transition-colors group">
                                    <div class="p-2 border border-gray-200 rounded-lg group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Facebook</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 text-gray-500 hover:text-primary transition-colors group">
                                    <div class="p-2 border border-gray-200 rounded-lg group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Twitter</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center space-x-3 text-gray-500 hover:text-primary transition-colors group">
                                    <div class="p-2 border border-gray-200 rounded-lg group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"></rect>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"></line>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Instagram</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-center md:text-left">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Matla. All rights reserved.
                    </p>
                    <div class="flex space-x-6 text-sm text-gray-400">
                        <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
                    </div>
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
    </body>
</html>
