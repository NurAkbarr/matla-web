    <!-- Mobile Bottom Navigation -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 z-50 flex justify-around items-center px-2 py-2 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] pb-[calc(env(safe-area-inset-bottom)+0.5rem)]">
        <a href="{{ route('backend.mahasiswa.dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('backend.mahasiswa.dashboard') ? 'text-emerald-700' : 'text-slate-400 hover:text-emerald-700' }} transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-9 9h6m-6 0v-6"></path></svg>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        <a href="{{ route('mahasiswa.elearning.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('mahasiswa.elearning.*') ? 'text-emerald-700' : 'text-slate-400 hover:text-emerald-700' }} transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-[10px] font-medium">Kuliah</span>
        </a>
        <a href="{{ route('mahasiswa.assignments.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('mahasiswa.assignments.*') ? 'text-emerald-700' : 'text-slate-400 hover:text-emerald-700' }} transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span class="text-[10px] font-medium">Tugas</span>
        </a>
        <button @click="mobileMenuOpen = true" class="flex flex-col items-center p-2 text-slate-400 hover:text-emerald-700 transition-colors">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <span class="text-[10px] font-medium">Lainnya</span>
        </button>
    </div>

    <!-- Mobile Bottom Sheet ("Lainnya") -->
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[60] lg:hidden flex flex-col justify-end" style="display: none;">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        
        <!-- Bottom Sheet Content -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="relative bg-white rounded-t-3xl shadow-2xl p-6 pb-8 w-full max-h-[85vh] overflow-y-auto rounded-t-[32px]">
            
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Menu Lainnya</h3>
                <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-600 bg-slate-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                <!-- Biodata -->
                <a href="{{ route('mahasiswa.profil') }}" class="flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-2 active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-600">Biodata</span>
                </a>
                
                <!-- KTM Digital -->
                <a href="{{ route('mahasiswa.ktm') }}" class="flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-2 active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-600">KTM</span>
                </a>

                <!-- Placeholder for visual fullness -->
                <a href="#" class="flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-2 active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-600">Presensi</span>
                </a>
                
                <a href="#" class="flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-2 active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-600">KHS</span>
                </a>
            </div>

            <!-- Profile Info & Logout in Bottom Sheet -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ Auth::user()->foto_profil ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-10 h-10 rounded-full object-cover" alt="Profile">
                    <div>
                        <p class="text-[13px] font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 font-medium">{{ Auth::user()->education['program_studi'] ?? 'Teknik Informatika' }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
