@extends('layouts.app')

@section('title', 'Login - Matla Islamic University')

@section('content')
<style>
    /* Fix page height to prevent scrolling, offset for navbar */
    body {
        overflow: hidden;
    }
    .login-container {
        height: calc(100vh - 57px); /* Mobile nav height is ~57px */
        font-family: 'Montserrat', sans-serif;
    }
    @media (min-width: 1024px) {
        .login-container {
            height: calc(100vh - 73px); /* Desktop nav height is ~73px */
        }
    }
    .motif-bg {
        background-image: url('{{ asset('assets/motifbatik.jpg') }}');
        background-size: 300px;
        background-repeat: repeat;
    }
    
    /* Responsive Background */
    .main-bg {
        background-image: url('{{ asset('assets/bg-web.png') }}');
        background-size: cover;
        background-position: bottom center;
        background-repeat: no-repeat;
    }
    @media (min-width: 768px) {
        .main-bg {
            background-image: none;
            background-color: #f9fafb; /* bg-gray-50 */
        }
    }
</style>

<div class="login-container w-full flex flex-col items-center justify-center relative main-bg">
    <!-- Motif background pattern (Desktop only) -->
    <div class="absolute inset-0 z-0 motif-bg opacity-5 hidden md:block"></div>
    
    <!-- Semi-transparent overlay for mobile readability -->
    <div class="absolute inset-0 bg-white/30 md:hidden z-0"></div>
    
    <!-- Main Card Wrapper -->
    <div class="w-11/12 max-w-[900px] relative z-10 flex flex-col md:flex-row md:bg-white md:rounded-2xl md:shadow-xl md:border border-gray-100 md:h-[500px] md:overflow-hidden">
        
        <!-- Left Side (Info part - Desktop Only) -->
        <div class="hidden md:flex w-1/2 relative bg-cover bg-center flex-col p-10 justify-between" style="background-image: url('{{ asset('assets/bg-web.png') }}');">
            <div class="absolute inset-0 bg-white/20"></div>
            <div class="relative z-10 h-full w-full flex flex-col justify-between">
                <div class="flex flex-col items-center justify-center mt-4">
                    <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-20 w-auto mb-2">
                    <h1 class="text-2xl font-extrabold text-green-700 tracking-wide text-center uppercase">MATLA</h1>
                    <p class="text-[10px] font-bold text-gray-700 tracking-[0.2em] text-center uppercase">Islamic Academy</p>
                </div>
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Sistem Informasi</h2>
                    <h2 class="text-2xl font-bold text-green-700 mb-4">E-Campus</h2>
                    <p class="text-xs text-gray-600 leading-relaxed border-t border-gray-300 pt-4 w-4/5 font-medium">
                        Akses informasi akademik, layanan, dan berbagai fitur kampus dalam satu portal terintegrasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side (Form) -->
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-8 bg-white rounded-2xl md:rounded-none shadow-xl md:shadow-none mb-4 md:mb-0">
            <div class="w-full max-w-[320px] relative z-10">
                <!-- Header -->
                <div class="text-center mb-4 md:mb-6">
                    <div class="mx-auto w-10 h-10 md:w-12 md:h-12 bg-green-50 rounded-full flex items-center justify-center mb-2 md:mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-1">Selamat Datang</h2>
                    <p class="text-[11px] md:text-xs text-gray-500 font-medium">Silakan masuk ke akun civitas akademika Anda</p>
                </div>

                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-3 md:space-y-4">
                    @csrf
                    
                    <!-- Email / Phone -->
                    <div class="space-y-1.5">
                        <label for="login" class="text-xs font-bold text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email / No. Telepon
                        </label>
                        <input id="login" name="login" type="text" autocomplete="username" required 
                            class="w-full px-4 py-2 md:py-2.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-600 transition-all text-sm text-gray-700 @error('login') border-red-500 @enderror" 
                            placeholder="Alamat email atau no. telepon" value="{{ old('login') }}">
                        @error('login')
                            <p class="text-red-500 text-[10px] mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-xs font-bold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Kata Sandi
                            </label>
                            <a href="#" class="text-[10px] font-bold text-green-700 hover:text-green-800 transition-colors">Lupa sandi?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                class="w-full px-4 py-2 md:py-2.5 pr-10 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600/20 focus:border-green-600 transition-all text-sm text-gray-700" 
                                placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-700 transition-colors focus:outline-none">
                                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center pt-1 pb-1">
                        <input id="remember_me" name="remember" type="checkbox" class="h-3.5 w-3.5 text-green-700 focus:ring-green-700 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-xs font-medium text-gray-600">Ingat saya seterusnya</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2.5 md:py-3 bg-[#0d8744] hover:bg-[#0a6b35] text-white rounded-xl font-bold text-sm shadow-md transition-all flex justify-between items-center px-4 mt-1">
                        <span class="mx-auto pl-6">Masuk Sekarang</span>
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </button>
                </form>

                <div class="mt-5 flex items-center justify-between">
                    <span class="border-b border-gray-200 w-1/5 lg:w-1/4"></span>
                    <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest">Atau masuk dengan</span>
                    <span class="border-b border-gray-200 w-1/5 lg:w-1/4"></span>
                </div>

                <div class="mt-5">
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-2.5 md:py-3 border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all">
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Bottom Text (Hidden on desktop) -->
    <div class="md:hidden w-11/12 max-w-[320px] relative z-10 mt-4">
        <div class="flex items-center mb-1">
            <svg class="w-4 h-4 text-green-700 mr-2 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"></path>
            </svg>
            <h2 class="text-sm font-bold text-gray-800">Sistem Informasi <span class="text-green-700">E-Campus</span></h2>
        </div>
        <p class="text-[10px] text-gray-600 font-medium leading-relaxed pl-6">
            Akses informasi akademik, layanan, dan berbagai fitur kampus dalam satu portal terintegrasi.
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (togglePassword && password && eyeIcon) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                if (type === 'text') {
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
                } else {
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
                }
            });
        }
    });
</script>
@endsection
