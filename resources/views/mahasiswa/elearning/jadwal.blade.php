@extends('layouts.app')

@section('title', 'Silabus Kelas - ' . $jadwal->mata_kuliah)

@section('content')
<div class="bg-gray-50 min-h-screen pb-24 pt-8 lg:pt-12">
    <div class="container mx-auto px-4 lg:px-12 max-w-5xl">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $jadwal->mata_kuliah }}</h1>
        <p class="text-gray-500 mt-1 font-medium">{{ $jadwal->dosen->name ?? 'Dosen Belum Ditentukan' }} &bull; SKS: {{ $jadwal->sks }} &bull; SMT: {{ $jadwal->semester }}</p>
    </div>
    <a href="{{ route('mahasiswa.elearning.index') }}" class="px-6 py-3 bg-white text-gray-500 text-xs font-bold uppercase tracking-widest rounded-xl hover:text-gray-900 border border-gray-100 transition-all shadow-sm">
        Kembali ke Daftar
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Silabus & Materi Pertemuan</h3>
        <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">{{ $pertemuans->count() }} Pertemuan</span>
    </div>

    @if($pertemuans->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($pertemuans as $p)
                @php
                    $log = $p->logTontonans->first();
                    $eval = $p->jawabanEvaluasis->first();
                    
                    $isLocked = false;
                    $statusColor = 'bg-gray-50 text-gray-400';
                    $statusText = 'Belum Dimulai';
                    
                    if ($p->tipe_pertemuan == 'video') {
                        if ($log && $log->is_lulus_nonton) {
                            if ($eval) {
                                $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                $statusText = 'Selesai & Evaluasi Dikirim';
                            } else {
                                $statusColor = 'bg-amber-50 text-amber-600 border-amber-100';
                                $statusText = 'Video Selesai (Evaluasi Belum)';
                            }
                        } elseif ($log && $log->detik_ditonton > 0) {
                            $statusColor = 'bg-blue-50 text-blue-600 border-blue-100';
                            $statusText = 'Sedang Menonton';
                        } else {
                            $statusColor = 'bg-indigo-50 text-indigo-600 border-indigo-100';
                            $statusText = 'Belum Menonton';
                        }
                    } else {
                        // Zoom
                        $statusColor = 'bg-blue-50 text-blue-600 border-blue-100';
                        $statusText = 'Sesi Live Zoom';
                    }
                @endphp
                <a href="{{ route('mahasiswa.elearning.pertemuan', $p->id) }}" class="block p-6 hover:bg-gray-50 transition-colors group">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl shadow-inner flex-shrink-0
                                {{ $p->tipe_pertemuan == 'video' ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700' }} group-hover:scale-105 transition-transform">
                                {{ $p->pertemuan_ke }}
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">
                                    {{ $p->judul_materi }}
                                </h4>
                                <div class="flex items-center space-x-3 mt-2">
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border
                                        {{ $statusColor }}">
                                        @if($p->tipe_pertemuan == 'video')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                                        @else
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                                        @endif
                                        <span>{{ $statusText }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right md:w-32">
                            <span class="inline-flex items-center text-xs font-bold text-primary group-hover:translate-x-1 transition-transform">
                                Akses Kelas <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-1">Silabus Belum Tersedia</h4>
            <p class="text-sm font-medium text-gray-500">Dosen belum menambahkan jadwal pertemuan untuk mata kuliah ini.</p>
        </div>
    @endif
        </div>
    </div>
</div>
@endsection
