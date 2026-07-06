<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance System') | Matla</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Override border radii globally for this layout to ensure sharp edges */
        .sharp-edges, .sharp-edges * {
            border-radius: 0 !important;
        }
    </style>
</head>
<body class="bg-[#f8fcfb] text-slate-800 h-screen overflow-hidden flex selection:bg-emerald-600 selection:text-white sharp-edges">
    
    <!-- Sidebar (Desktop Only) -->
    <aside class="hidden md:flex w-64 bg-[#022c22] text-emerald-100/70 flex-col h-full shrink-0 border-r border-emerald-900 relative z-50">
        <!-- Logo Area -->
        <div class="h-20 flex items-center justify-between px-6 bg-emerald-950/50 border-b border-emerald-900/80">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white flex items-center justify-center mr-3 shadow-md">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <span class="font-black text-xl text-white tracking-widest drop-shadow-sm">MATLA</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-8 px-4 space-y-1">
            <p class="px-3 text-[10px] font-bold text-emerald-400/60 uppercase tracking-widest mb-4">Analytics</p>
            
            <a href="{{ route('backend.admin.finance.dashboard') }}" 
               class="flex items-center px-3 py-3 text-sm font-bold transition-colors border-l-4 {{ request()->routeIs('backend.admin.finance.dashboard') ? 'bg-emerald-900/60 text-white border-emerald-400' : 'border-transparent text-emerald-100/70 hover:bg-emerald-900/30 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('backend.admin.finance.dashboard') ? 'text-emerald-400' : 'text-emerald-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <p class="px-3 text-[10px] font-bold text-emerald-400/60 uppercase tracking-widest mt-8 mb-4">Management</p>
            
            <a href="{{ route('backend.admin.finance.categories') }}" 
               class="flex items-center px-3 py-3 text-sm font-bold transition-colors border-l-4 {{ request()->routeIs('backend.admin.finance.categories') ? 'bg-emerald-900/60 text-white border-emerald-400' : 'border-transparent text-emerald-100/70 hover:bg-emerald-900/30 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('backend.admin.finance.categories') ? 'text-emerald-400' : 'text-emerald-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori Transaksi
            </a>

            <a href="{{ route('backend.admin.finance.wallets') }}" 
               class="flex items-center px-3 py-3 text-sm font-bold transition-colors border-l-4 {{ request()->routeIs('backend.admin.finance.wallets') ? 'bg-emerald-900/60 text-white border-emerald-400' : 'border-transparent text-emerald-100/70 hover:bg-emerald-900/30 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('backend.admin.finance.wallets') ? 'text-emerald-400' : 'text-emerald-500/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Dompet / Akun
            </a>
        </nav>

        <!-- Footer / Lock Module -->
        <div class="p-4 border-t border-emerald-900/50">
            <form action="{{ route('backend.admin.finance.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-emerald-950 hover:bg-rose-950/80 text-emerald-400/80 hover:text-rose-400 px-4 py-3 text-xs tracking-wider uppercase font-bold transition-colors border border-emerald-800/60 hover:border-rose-900/60 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Kunci Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-[#f8fcfb] w-full">
        
        <!-- Header (Mobile: Top Header like in screenshot) -->
        <header class="h-20 bg-white border-b border-emerald-50 flex items-center justify-between px-4 lg:px-8 shrink-0 relative z-40 shadow-[0_4px_20px_-10px_rgba(16,185,129,0.1)]">
            
            <!-- Left Side Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white border-emerald-100 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <span class="font-black text-xl text-slate-800 tracking-widest drop-shadow-sm">MATLA</span>
            </div>

            <!-- Right Side Profile -->
            <div class="flex items-center space-x-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-slate-800">Super Admin</p>
                    <p class="text-[10px] text-emerald-600 font-bold flex items-center justify-end">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></span>
                        Module Unlocked
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 pb-28 md:pb-8">
            <div class="max-w-6xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 font-bold text-sm rounded-r-xl shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 font-bold text-sm rounded-r-xl shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 font-bold text-sm rounded-r-xl shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50 rounded-t-3xl px-6 py-4 flex justify-between items-center">
        
        <!-- Tab: Dashboard -->
        <a href="{{ route('backend.admin.finance.dashboard') }}" class="flex flex-col items-center justify-center px-4 py-2 rounded-2xl transition-all {{ request()->routeIs('backend.admin.finance.dashboard') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('backend.admin.finance.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('backend.admin.finance.dashboard') ? '0' : '2' }}" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[10px] font-bold">Dashboard</span>
        </a>

        <!-- Tab: Kategori -->
        <a href="{{ route('backend.admin.finance.categories') }}" class="flex flex-col items-center justify-center px-4 py-2 rounded-2xl transition-all {{ request()->routeIs('backend.admin.finance.categories') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('backend.admin.finance.categories') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('backend.admin.finance.categories') ? '0' : '2' }}" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span class="text-[10px] font-bold">Kategori</span>
        </a>

        <!-- Tab: Dompet -->
        <a href="{{ route('backend.admin.finance.wallets') }}" class="flex flex-col items-center justify-center px-4 py-2 rounded-2xl transition-all {{ request()->routeIs('backend.admin.finance.wallets') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('backend.admin.finance.wallets') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('backend.admin.finance.wallets') ? '0' : '2' }}" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <span class="text-[10px] font-bold">Dompet</span>
        </a>

        <!-- Tab: Akun / Lock -->
        <form action="{{ route('backend.admin.finance.logout') }}" method="POST" class="inline-block m-0 p-0">
            @csrf
            <button type="submit" class="flex flex-col items-center justify-center px-4 py-2 rounded-2xl transition-all text-slate-400 hover:text-rose-500 hover:bg-rose-50">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="text-[10px] font-bold">Keluar</span>
            </button>
        </form>

    </div>

</body>
</html>
