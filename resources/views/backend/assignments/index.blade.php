@extends(Auth::user()->role === 'dosen' ? 'layouts.dosen' : 'layouts.backend')
 
@section('title', 'Manajemen Tugas MHS')
 
@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tugas Mahasiswa</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola publikasi tugas, referensi, lampiran, dan berikan penilaian langsung.</p>
        </div>
        <div>
            <a href="{{ route('backend.admin.assignments.create') }}" class="inline-flex items-center px-6 py-3.5 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold transition-all shadow-lg shadow-primary/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tugas
            </a>
        </div>
    </div>
 
    {{-- Main Cards/Table list --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-lg">Daftar Tugas Aktif</h3>
            <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold">{{ $assignments->total() }} Tugas</span>
        </div>
        
        @if($assignments->isEmpty())
            <div class="p-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-1">Belum ada tugas dipublikasikan</h4>
                <p class="text-slate-400 text-sm max-w-md">Klik tombol 'Tambah Tugas' untuk mempublikasikan tugas baru untuk kelompok kelas tertentu.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Judul Tugas</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Target Kelas</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Batas Waktu</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Dibuat Oleh</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($assignments as $assignment)
                            <tr class="hover:bg-slate-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-800 group-hover:text-primary transition-colors text-base">{{ $assignment->title }}</div>
                                    <div class="flex items-center space-x-3 mt-1.5">
                                        @if($assignment->file_path)
                                            <span class="inline-flex items-center text-[10px] font-black text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded-md">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Berkas Lampiran
                                            </span>
                                        @endif
                                        @if($assignment->link)
                                            <span class="inline-flex items-center text-[10px] font-black text-indigo-600 uppercase bg-indigo-50 px-2 py-0.5 rounded-md">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                </svg>
                                                Link
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">
                                        {{ $assignment->classGroup->name }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $isOverdue = now()->gt($assignment->due_date);
                                    @endphp
                                    <div class="font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-700' }} text-sm">
                                        {{ $assignment->due_date->translatedFormat('d F Y, H:i') }} WIB
                                    </div>
                                    <span class="text-[10px] font-black uppercase {{ $isOverdue ? 'text-rose-400' : 'text-emerald-500' }}">
                                        {{ $isOverdue ? 'Batas Waktu Habis' : 'Aktif' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-semibold text-slate-700">{{ $assignment->creator->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $assignment->creator->role }}</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('backend.admin.assignments.show', $assignment) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl text-xs transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Nilai & Detail
                                        </a>
                                        <form action="{{ route('backend.admin.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini beserta seluruh jawaban mahasiswa?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-8 border-t border-slate-100">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
