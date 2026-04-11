@extends('layouts.backend')

@section('title', 'Admin Dashboard')
@section('breadcrumb', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Pendaftar Baru</p>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ $pendaftarBaru }}</h3>
        </div>
        <div class="w-14 h-14 bg-emerald-50 text-primary rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($totalMahasiswa) }}</h3>
        </div>
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Dosen</p>
            <h3 class="text-3xl font-extrabold text-gray-900">{{ number_format($totalDosen) }}</h3>
        </div>
        <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <h4 class="text-xl font-extrabold text-gray-900 mb-6">Aktivitas Terakhir</h4>
        <div class="space-y-6">
            <div class="flex items-center space-x-4">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-900 leading-none mb-1">Mahasiswa Baru Terdaftar</p>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-tighter">10 Menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-900 leading-none mb-1">Update Kurikulum Prodi TI</p>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-tighter">1 Jam yang lalu</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
