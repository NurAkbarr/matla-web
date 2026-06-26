@extends('layouts.app')

@section('title', 'KHS Saya - Matla Islamic University')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
    </style>

    <div class="container mx-auto px-4 lg:px-12 pt-8">
        {{-- Breadcrumb / Back --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('backend.mahasiswa.dashboard') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        {{-- Welcome Header --}}
        <div class="bg-white rounded-none p-8 shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Kartu Hasil Studi (KHS)</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Daftar KHS yang telah diterbitkan oleh bagian Akademik.</p>
            </div>
        </div>

        @if($khsList->isEmpty())
            <div class="bg-white rounded-none p-16 shadow-sm border border-gray-100 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-none flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Belum ada KHS!</h3>
                <p class="text-gray-400 text-sm max-w-sm">Admin Akademik belum mengunggah Kartu Hasil Studi Anda. Silakan cek secara berkala.</p>
            </div>
        @else
            {{-- KHS Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($khsList as $khs)
                    <div class="bg-white rounded-none p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:border-emerald-200 transition-all group">
                        <div class="space-y-4">
                            {{-- Header (Status) --}}
                            <div class="flex items-center justify-between">
                                <span class="px-3.5 py-1.5 rounded-none border text-[11px] font-bold bg-emerald-50 border-emerald-100 text-emerald-700">
                                    Tersedia
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($khs->created_at)->translatedFormat('M Y') }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-tight">
                                    Semester {{ $khs->semester }}
                                </h3>
                                <p class="text-xs font-bold text-emerald-600 mt-2 bg-emerald-50 inline-block px-2 py-1 rounded-md">
                                    Tahun Akademik {{ Auth::user()->angkatan ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Action button --}}
                        <div class="mt-6 pt-4 border-t border-gray-50 flex flex-col gap-2">
                            <a href="{{ route('mahasiswa.khs.download', $khs) }}" target="_blank" class="w-full flex items-center justify-center py-3 bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/20 hover:text-white text-xs font-bold rounded-none transition-all">
                                <span>Lihat KHS (PDF)</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
