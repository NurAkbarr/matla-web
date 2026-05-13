@extends('layouts.backend')

@section('title', 'Manajemen Silabus & Pertemuan')

@section('breadcrumb', 'Akademik > Jadwal > Silabus')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $jadwal->mata_kuliah }}</h2>
            <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">
                Dosen: {{ $jadwal->dosen->name ?? 'Belum Ditentukan' }} &bull; Semester {{ $jadwal->semester }}
            </p>
        </div>
        <a href="{{ route('backend.admin.jadwal.pertemuan.create', $jadwal->id) }}" class="px-6 py-3 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
            + Tambah Pertemuan
        </a>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Daftar Pertemuan (Silabus)</h3>
        <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full">{{ $pertemuans->count() }} Pertemuan</span>
    </div>

    @if($pertemuans->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($pertemuans as $p)
                <div class="p-6 hover:bg-gray-50 transition-colors flex items-start justify-between group">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shadow-inner flex-shrink-0
                            {{ $p->tipe_pertemuan == 'video' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }}">
                            {{ $p->pertemuan_ke }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">
                                {{ $p->judul_materi }}
                            </h4>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                    {{ $p->tipe_pertemuan == 'video' ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700' }}">
                                    @if($p->tipe_pertemuan == 'video')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                                        <span>Video E-Learning</span>
                                    @else
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                                        <span>Live Zoom</span>
                                    @endif
                                </span>
                                @if($p->link_url)
                                    <a href="{{ $p->link_url }}" target="_blank" class="text-[10px] font-bold text-gray-400 hover:text-primary transition-colors flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        <span>Lihat Tautan</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('backend.admin.jadwal.pertemuan.edit', [$jadwal->id, $p->id]) }}" class="p-2 text-gray-400 hover:text-primary bg-gray-50 hover:bg-primary/10 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('backend.admin.jadwal.pertemuan.destroy', [$jadwal->id, $p->id]) }}" method="POST" onsubmit="return confirm('Hapus pertemuan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pertemuan</h4>
            <p class="text-sm font-medium text-gray-500">Mulai buat silabus dan materi pertemuan untuk mata kuliah ini.</p>
        </div>
    @endif
</div>
@endsection
