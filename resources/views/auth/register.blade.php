@extends('layouts.app')

@section('title', 'Daftar Mahasiswa - Matla Islamic University')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-400/10 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>

    <div class="max-w-md w-full relative z-10">
        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-white p-8 md:p-10 transition-all duration-300">
            <!-- Header -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-2 mb-6">
                    <img src="{{ asset('assets/logo.png') }}" alt="MATLA Logo" class="h-12 w-auto">
                    <span class="text-2xl font-bold text-primary-dark tracking-wide">MATLA</span>
                </a>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Registrasi Mahasiswa</h2>
                <p class="text-gray-500">Silakan lengkapi data untuk mengaktifkan akun Mahasiswa Anda</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Nama Lengkap
                    </label>
                    <input id="name" name="name" type="text" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all text-gray-700 @error('name') border-red-500 @enderror" 
                        placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="password" class="text-sm font-bold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Kata Sandi
                    </label>
                    <input id="password" name="password" type="password" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all text-gray-700 @error('password') border-red-500 @enderror" 
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Konfirmasi Sandi
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all text-gray-700" 
                        placeholder="Ulangi kata sandi Anda">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Daftar Sekarang
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-8 text-center pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
