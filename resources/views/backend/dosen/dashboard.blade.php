@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <!-- Welcome Banner -->
    <div class="bg-[#1e293b] rounded-[2.5rem] p-12 text-white shadow-2xl shadow-slate-200 relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-slate-300 text-lg md:text-xl font-medium">Semoga harimu menyenangkan. Berikut ringkasan aktivitas akademikmu.</p>
        </div>
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card Total Kelas -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center space-x-8 group hover:shadow-xl transition-all duration-500">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-3xl flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Kelas</p>
                <h3 class="text-5xl font-black text-gray-900 tracking-tighter">{{ $totalKelas }}</h3>
            </div>
        </div>

        <!-- Card Total Mahasiswa -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center space-x-8 group hover:shadow-xl transition-all duration-500">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Mahasiswa Ajar</p>
                <h3 class="text-5xl font-black text-gray-900 tracking-tighter">{{ $totalMahasiswa }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
