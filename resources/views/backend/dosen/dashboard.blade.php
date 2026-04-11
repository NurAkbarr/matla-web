@extends('layouts.app')

@section('title', 'Dosen Dashboard - Matla Islamic University')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.827a1 1 0 00-.788 0l-7 3a1 1 0 000 1.846l7 3a1 1 0 00.788 0l7-3a1 1 0 000-1.846l-7-3z" />
                    <path d="M3 10.827v3.647a1 1 0 00.553.894l6 3a1 1 0 00.894 0l6-3a1 1 0 00.553-.894v-3.647l-7 3a1 1 0 00-.788 0l-7-3z" />
                </svg>
            </div>
            
            <div class="relative z-10">
                <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Role: Dosen</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Selamat Datang di Portal Dosen</h1>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl">Halo, {{ Auth::user()->name }}. Ini adalah halaman manajemen akademik Anda. Silakan kelola kelas dan nilai mahasiswa Anda di sini.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary font-bold text-xl">4</div>
                        <div>
                            <span class="block font-bold text-gray-900 leading-tight">Mata Kuliah</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Semester Aktif</span>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary font-bold text-xl">128</div>
                        <div>
                            <span class="block font-bold text-gray-900 leading-tight">Total Mahasiswa</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Dibimbing</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-12">
                    @csrf
                    <button type="submit" class="px-8 py-3 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-all inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
