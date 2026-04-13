@extends('layouts.dosen')

@section('title', 'Penilaian Mata Kuliah')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Penilaian Mata Kuliah</h1>
        <p class="text-gray-500 font-medium italic">Pilih mata kuliah untuk menginput nilai mahasiswa</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($jadwals as $jadwal)
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 flex flex-col group hover:shadow-xl transition-all duration-500">
            <div class="p-8">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="px-2 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg">
                        {{ $jadwal->programStudi->nama }}
                    </span>
                    <span class="text-xs font-bold text-gray-300">Semester {{ $jadwal->semester }}</span>
                </div>
                
                <h3 class="text-xl font-black text-gray-900 mb-4 group-hover:text-primary transition-colors leading-tight">
                    {{ $jadwal->mata_kuliah }}
                </h3>

                <div class="space-y-3 text-sm font-bold text-gray-400 mb-8">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                    </div>
                </div>

                <a href="{{ route('backend.dosen.nilai.input', $jadwal) }}" 
                   class="w-full flex items-center justify-center space-x-2 py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span>Input Nilai</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-gray-400 font-bold italic">Anda belum memiliki jadwal mengajar.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
