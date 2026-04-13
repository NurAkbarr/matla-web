<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matla Portal Dosen</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        'primary-dark': '#065f46',
                        'dosen-dark': '#1e293b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ mobileMenuOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar for Desktop -->
        <aside class="w-72 bg-white border-r border-gray-100 flex-shrink-0 hidden lg:flex flex-col sticky top-0 h-screen">
            <div class="p-8 flex items-center space-x-3 mb-6">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold text-primary-dark tracking-wider leading-none">MATLA</span>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Portal Dosen</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('backend.dosen.dashboard') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.dashboard') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Beranda</span>
                </a>

                <a href="{{ route('backend.dosen.jadwal') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.jadwal') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">Jadwal</span>
                </a>

                <a href="{{ route('backend.dosen.nilai.index') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.nilai.*') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span class="text-sm">Input Nilai</span>
                </a>

                <a href="{{ route('backend.dosen.profile.index') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.profile.*') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm">Profil Saya</span>
                </a>
            </nav>

            <div class="p-6 border-t border-gray-50 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-6 py-4 text-red-500 hover:bg-red-50 rounded-2xl transition-all font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for Mobile Sidebar -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Sidebar for Mobile -->
        <aside x-show="mobileMenuOpen"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 w-80 bg-white z-50 lg:hidden flex flex-col shadow-2xl">
            <div class="p-8 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-lg font-extrabold text-primary-dark tracking-wider">MATLA DOSEN</span>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('backend.dosen.dashboard') }}" 
                   class="flex items-center space-x-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.dashboard') ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('backend.dosen.jadwal') }}" 
                   class="flex items-center space-x-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.jadwal') ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Jadwal</span>
                </a>
                <a href="#" class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-400 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    <span>Input Nilai</span>
                </a>
                <a href="#" class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-400 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Profil Saya</span>
                </a>
            </nav>

            <div class="p-8 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-3 px-6 py-4 bg-red-50 text-red-500 rounded-2xl font-bold">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Mobile Header / Top Bar -->
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-6 py-4 sticky top-0 z-30 lg:hidden">
                <div class="flex items-center space-x-3">
                    <button @click="mobileMenuOpen = true" class="p-2 -ml-2 text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="font-extrabold text-primary-dark tracking-tight">MATLA</span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg overflow-hidden shadow-sm border border-primary/20">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-10 flex-1">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
