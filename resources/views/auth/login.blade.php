@extends('layouts.app')

@section('title', 'Login - Matla Islamic University')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400/10 rounded-full -translate-x-1/2 translate-y-1/2 blur-3xl"></div>

    <div class="max-w-md w-full relative z-10">
        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-white p-8 md:p-10 transition-all duration-300">
            <!-- Header -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-2 mb-6">
                    <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-12 w-auto">
                    <span class="text-2xl font-bold text-primary-dark tracking-wide">MATLA</span>
                </a>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Selamat Datang</h2>
                <p id="login-subtitle" class="text-gray-500 transition-all duration-300">Silakan masuk ke akun sivitas akademika Anda</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                        </svg>
                        Alamat Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all text-gray-700 @error('email') border-red-500 @enderror" 
                        placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-sm font-bold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Kata Sandi
                        </label>
                        <a href="#" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Lupa sandi?</a>
                    </div>
                    <input id="password" name="password" type="password" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all text-gray-700" 
                        placeholder="••••••••">
                </div>

                <!-- Remember Me & Custom Action -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded-lg">
                    <label for="remember_me" class="ml-2 block text-sm font-medium text-gray-600">Ingat saya untuk 30 hari</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-8 text-center pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    Bukan Sivitas Akademika? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar sebagai Mahasiswa</a>
                </p>
                <p class="text-xs text-gray-400 mt-2 italic">
                    Staf & Dosen silakan hubungi Bagian IT untuk akses akun.
                </p>
            </div>

        </div>

        <!-- Role Indicators -->
        <div class="mt-8 pt-6 border-t border-gray-100/50">
            <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Akses Portalin Sivitas</p>
            <div class="flex justify-center space-x-6">
                <div id="portal-admin" class="flex flex-col items-center group cursor-pointer" title="Akses Admin">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:border-primary/30 group-hover:shadow-md transition-all mb-2">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">Admin</span>
                </div>
                <div id="portal-dosen" class="flex flex-col items-center group cursor-pointer" title="Akses Dosen">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:border-primary/30 group-hover:shadow-md transition-all mb-2">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">Dosen</span>
                </div>
                <div id="portal-mhs" class="flex flex-col items-center group cursor-pointer" title="Akses Mahasiswa">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:border-primary/30 group-hover:shadow-md transition-all mb-2">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">Mhs</span>
                </div>
            </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subtitle = document.getElementById('login-subtitle');
        const portals = {
            'portal-admin': 'Admin',
            'portal-dosen': 'Dosen',
            'portal-mhs': 'Mahasiswa'
        };

        Object.keys(portals).forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function() {
                    const role = portals[id];
                    
                    // Add fade out effect
                    subtitle.classList.add('opacity-0', 'scale-95');
                    
                    setTimeout(() => {
                        subtitle.innerText = `Silahkan login sebagai ${role}`;
                        // Remove fade out and add fade in effect
                        subtitle.classList.remove('opacity-0', 'scale-95');
                    }, 200);

                    // Add active class or visual cue to selected portal if needed
                    Object.keys(portals).forEach(pid => {
                        document.getElementById(pid).querySelector('div').classList.remove('border-primary', 'shadow-md', 'bg-emerald-50');
                    });
                    el.querySelector('div').classList.add('border-primary', 'shadow-md', 'bg-emerald-50');
                });
            }
        });
    });
</script>
@endsection
