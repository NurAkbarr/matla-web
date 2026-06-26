@extends('layouts.backend')

@section('title', 'Upload KHS - Pilih Angkatan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('backend.admin.khs.index') }}" class="text-gray-400 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-gray-800">{{ $prodi->nama }}</h3>
            <p class="text-sm text-gray-500">Pilih Angkatan</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($angkatans as $angkatan)
                <a href="{{ route('backend.admin.khs.angkatan', [$prodi->id, $angkatan]) }}" class="p-4 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all group flex flex-col items-center justify-center text-center">
                    <span class="text-sm text-gray-500 mb-1 group-hover:text-primary">Angkatan</span>
                    <span class="text-2xl font-black text-gray-800 group-hover:text-primary">{{ $angkatan }}</span>
                </a>
            @endforeach
        </div>
        @if($angkatans->isEmpty())
            <div class="text-center py-8 text-gray-500 text-sm">Belum ada data mahasiswa dengan angkatan yang terdaftar di prodi ini.</div>
        @endif
    </div>
</div>
@endsection
