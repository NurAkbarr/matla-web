<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Matla Backend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-active { @apply bg-primary/10 text-primary border-r-4 border-primary; }
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
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-100 flex-shrink-0 hidden lg:flex flex-col sticky top-0 h-screen">
            <div class="p-8 flex items-center space-x-3 mb-6">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <span class="text-xl font-extrabold text-primary-dark tracking-wider">MATLA</span>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <!-- SISTEM UTAMA -->
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sistem Utama</p>
                <a href="{{ route('backend.admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                <a href="{{ route('backend.admin.users.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('backend.admin.users.*') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm">Manajemen User</span>
                </a>
                @endif
                
                <!-- PMB -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl transition-all">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm">PMB</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-12 space-y-1 mt-1">
                        <a href="#" class="block py-2 text-xs font-medium text-gray-400 hover:text-primary transition-colors">Pengaturan PMB</a>
                    </div>
                </div>

                <!-- AKADEMIK -->
                <div x-data="{ open: {{ (request()->routeIs('backend.admin.mahasiswa') || request()->routeIs('backend.admin.dosen') || request()->routeIs('backend.admin.jadwal.*') || request()->routeIs('backend.admin.program-studi.*')) ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ (request()->routeIs('backend.admin.mahasiswa') || request()->routeIs('backend.admin.dosen') || request()->routeIs('backend.admin.jadwal.*') || request()->routeIs('backend.admin.program-studi.*')) ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm">Akademik</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-12 space-y-1 mt-1">
                        <a href="{{ route('backend.admin.mahasiswa') }}" 
                           class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.mahasiswa') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Mahasiswa Aktif</a>
                        <a href="{{ route('backend.admin.dosen') }}" 
                           class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.dosen') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Data Dosen</a>
                        <a href="{{ route('backend.admin.jadwal.index') }}" 
                           class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.jadwal.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Jadwal Perkuliahan</a>
                        <a href="{{ route('backend.admin.program-studi.index') }}" 
                           class="block py-2 text-xs font-medium {{ request()->routeIs('backend.admin.program-studi.*') ? 'text-primary font-bold' : 'text-gray-400 hover:text-primary transition-colors' }}">Program Studi</a>
                    </div>
                </div>

                <!-- MANAJEMEN KONTEN -->
                <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Manajemen Konten</p>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v4a2 2 0 002 2h4" />
                    </svg>
                    <span class="text-sm">Berita & Artikel</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Header -->
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-8 py-4 sticky top-0 z-20">
                <div class="lg:hidden flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-primary-dark">MATLA</span>
                </div>
                
                <h2 class="text-sm font-bold text-gray-500 hidden lg:block uppercase tracking-wider">
                    @yield('breadcrumb', 'Backend Portal')
                </h2>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-900 capitalize">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 bg-primary/20 text-primary rounded-full flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8 flex-1">
                @if(session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Mini Footer -->
            <footer class="p-8 pb-4 border-t border-gray-50">
                <div class="flex flex-col md:flex-row justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest uppercase">
                    <p>&copy; {{ date('Y') }} Matla University. All Rights Reserved.</p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <a href="#" class="hover:text-primary transition-colors">Support</a>
                        <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
