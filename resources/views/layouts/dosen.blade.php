<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matla Portal Dosen</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-bulat.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
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

                <a href="{{ route('backend.dosen.presensi.index') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.presensi.*') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Absen Mengajar</span>
                </a>

                <a href="{{ route('backend.dosen.nilai.index') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.dosen.nilai.*') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span class="text-sm">Input Nilai</span>
                </a>

                <a href="{{ route('backend.admin.assignments.index') }}" 
                   class="flex items-center space-x-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('backend.admin.assignments.*') ? 'bg-primary/10 text-primary font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-sm">Tugas MHS</span>
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



        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Mobile Header / Top Bar -->
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-6 py-4 sticky top-0 z-30 lg:hidden">
                <div class="flex items-center space-x-3">
                    <span class="font-extrabold text-primary-dark tracking-tight">MATLA</span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg overflow-hidden shadow-sm border border-primary/20">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-10 flex-1 pb-24 lg:pb-10">
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.dosen-mobile-bottom-nav')
</body>
</html>
