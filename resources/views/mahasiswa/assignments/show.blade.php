@extends('layouts.app')
 
@section('title', $assignment->title . ' - Matla Islamic University')
 
@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
    </style>
 
    <div class="container mx-auto px-4 lg:px-12 pt-8">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('mahasiswa.assignments.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Tugas
            </a>
        </div>
 
        <div class="max-w-3xl mx-auto space-y-6">
            {{-- "Informasi Tugas" Card --}}
            <div class="bg-white rounded-[1.5rem] p-6 md:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900">Informasi Tugas</h2>
                    <span class="flex items-center px-3 py-1 bg-white border border-gray-200 rounded-full text-[11px] font-bold text-gray-700">
                        <span class="w-2 h-2 rounded-full bg-purple-600 mr-2"></span>
                        Tugas Individu
                    </span>
                </div>
                
                <h1 class="text-[22px] text-gray-900 mb-6" style="font-weight: 500;">{{ $assignment->title }}</h1>

                <div class="space-y-5">
                    <div>
                        <p class="text-[13px] text-gray-500 mb-0.5">Batas pengumpulan</p>
                        <p class="text-[15px] text-gray-900">{{ $assignment->due_date->translatedFormat('d F Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-[13px] text-gray-500 mb-0.5">Deskripsi</p>
                        <div class="text-gray-900 text-[15px] leading-relaxed whitespace-pre-line" x-data="{ expanded: false }">
                            <div :class="expanded ? '' : 'line-clamp-2'">
                                {{ $assignment->description ?: 'Tidak ada deskripsi.' }}
                            </div>
                            @if(strlen($assignment->description) > 100)
                                <button @click="expanded = !expanded" class="text-blue-600 hover:underline mt-1 text-[13px]" x-text="expanded ? 'Sembunyikan' : 'Lihat Selengkapnya'"></button>
                            @endif
                        </div>
                    </div>

                    @if($assignment->file_path || $assignment->link)
                    <div>
                        <p class="text-[13px] text-gray-500 mb-2">Lampiran</p>
                        <div class="flex flex-col gap-3">
                            @if($assignment->file_path)
                                <a href="{{ route('foto.bypass', ['path' => $assignment->file_path]) }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-red-600 text-white rounded-[10px] flex items-center justify-center font-black text-xs mr-4 shrink-0 shadow-sm">
                                        PDF
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-medium text-gray-900 truncate">{{ basename($assignment->file_path) }}</p>
                                        <p class="text-[12px] text-gray-500 mt-0.5">Berkas Referensi</p>
                                    </div>
                                </a>
                            @endif
                            @if($assignment->link)
                                <a href="{{ $assignment->link }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-[10px] flex items-center justify-center font-black text-xs mr-4 shrink-0 shadow-sm">
                                        LINK
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-medium text-gray-900 truncate">{{ $assignment->link }}</p>
                                        <p class="text-[12px] text-gray-500 mt-0.5">Tautan / Form Eksternal</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
 
                {{-- Grading Results / Feedback if graded --}}
                @if($submission && $submission->score !== null)
                    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 text-white rounded-[2rem] p-8 shadow-xl shadow-emerald-700/10 relative overflow-hidden">
                        <div class="absolute right-0 bottom-0 opacity-10 translate-x-4 translate-y-4">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="relative z-10 space-y-4">
                            <p class="text-emerald-100 font-bold uppercase tracking-widest text-[9px] leading-none">Hasil Penilaian</p>
                            <div class="flex items-baseline">
                                <span class="text-5xl font-black tracking-tight">{{ $submission->score }}</span>
                                <span class="text-emerald-200/60 font-bold text-sm ml-1.5">/100 Poin</span>
                            </div>
                            @if($submission->feedback)
                                <div class="pt-4 border-t border-white/20">
                                    <p class="text-[9px] font-bold text-emerald-200/80 uppercase tracking-widest leading-none">Catatan Dosen</p>
                                    <p class="text-sm font-semibold text-emerald-50/90 leading-relaxed mt-1.5">"{{ $submission->feedback }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
 
                {{-- Student Submission Info if exists --}}
                @if($submission)
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pengumpulan Saya</h3>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-black uppercase">DITERIMA</span>
                        </div>
 
                        <div class="space-y-3">
                            @if($submission->submitted_file_path)
                                <div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Berkas Terlampir</p>
                                    <a href="{{ route('foto.bypass', ['path' => $submission->submitted_file_path]) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-1">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ basename($submission->submitted_file_path) }}
                                    </a>
                                </div>
                            @endif
 
                            @if($submission->submitted_link)
                                <div class="pt-2">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Tautan Pengumpulan</p>
                                    <a href="{{ $submission->submitted_link }}" target="_blank" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-700 mt-1 truncate max-w-xs">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        {{ $submission->submitted_link }}
                                    </a>
                                </div>
                            @endif
 
                            @if($submission->notes)
                                <div class="pt-2">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Catatan Tambahan</p>
                                    <p class="text-xs font-semibold text-gray-600 mt-1 bg-slate-50 p-3 rounded-xl italic">"{{ $submission->notes }}"</p>
                                </div>
                            @endif
 
                            <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-[10px] text-gray-400 font-bold">
                                <span>Diserahkan pada:</span>
                                <span>{{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
 
                {{-- Form submission / resubmission --}}
                @if($submission && $submission->score !== null)
                    {{-- Finalized: Graded tugas can't be modified anymore --}}
                    <div class="bg-slate-100 rounded-[2rem] p-6 text-center border border-slate-200">
                        <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pengumpulan Terkunci</h4>
                        <p class="text-[10px] text-slate-400 mt-1">Tugas ini sudah dinilai oleh dosen pengampu dan tidak dapat direvisi lagi.</p>
                    </div>
                @elseif($isOverdue && !$submission)
                    {{-- Overdue locked --}}
                    <div class="bg-red-50 rounded-[2rem] p-6 text-center border border-red-100">
                        <svg class="w-10 h-10 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h4 class="text-xs font-bold text-red-700 uppercase tracking-wider">Batas Pengumpulan Berakhir</h4>
                        <p class="text-[10px] text-red-500 mt-1">Anda tidak mengumpulkan tugas ini tepat waktu dan pengumpulan telah ditutup.</p>
                    </div>
                @else
                    @if($assignment->link && !$assignment->file_path)
                        {{-- Auto Submit Only / External Link Task --}}
                        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4 text-center">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">Tugas Eksternal</h3>
                            
                            <div class="py-4">
                                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800">Kerjakan Melalui Tautan Eksternal</p>
                                <p class="text-xs font-medium text-gray-500 mt-2 px-4">Tugas ini dikerjakan melalui platform eksternal. Klik tombol di bawah ini, dan sistem akan otomatis merekam bahwa Anda telah mengerjakannya.</p>
                            </div>

                            <form action="{{ route('mahasiswa.assignments.auto-submit', $assignment) }}" method="POST" target="_blank" onsubmit="setTimeout(() => window.location.reload(), 1000)">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-emerald-600/20">
                                    Kerjakan Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Form active (Submit or Resubmit) --}}
                        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">
                                {{ $submission ? 'Perbarui Jawaban Tugas' : 'Kirim Jawaban Tugas' }}
                            </h3>
    
                            <form action="{{ route('mahasiswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                
                                {{-- File Attachment --}}
                                <div class="space-y-2">
                                    <label for="submitted_file" class="text-xs font-bold text-gray-700 block">Berkas Tugas (PDF / Word)</label>
                                    <div class="relative flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 hover:border-emerald-500 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <p class="text-[10px] text-gray-400 font-bold">Pilih berkas jawaban (Max 10MB)</p>
                                                <p id="sub-file-chosen" class="text-[10px] text-emerald-600 font-black mt-1 truncate max-w-[200px] hidden"></p>
                                            </div>
                                            <input type="file" name="submitted_file" id="submitted_file" class="hidden" onchange="document.getElementById('sub-file-chosen').textContent = this.files[0].name; document.getElementById('sub-file-chosen').classList.remove('hidden');" />
                                        </label>
                                    </div>
                                    @error('submitted_file')
                                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                {{-- Link Rujukan --}}
                                <div class="space-y-2">
                                    <label for="submitted_link" class="text-xs font-bold text-gray-700 block">Tautan / Link Eksternal (Contoh: Google Drive/GitHub)</label>
                                    <input type="url" name="submitted_link" id="submitted_link" placeholder="https://drive.google.com/..." value="{{ old('submitted_link', $submission ? $submission->submitted_link : '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs text-gray-700 font-semibold transition-all outline-none">
                                    @error('submitted_link')
                                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                {{-- Notes --}}
                                <div class="space-y-2">
                                    <label for="notes" class="text-xs font-bold text-gray-700 block">Catatan Tambahan Ke Dosen</label>
                                    <textarea name="notes" id="notes" rows="3" placeholder="Tuliskan catatan tambahan jika ada..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs text-gray-700 font-semibold transition-all outline-none resize-none">{{ old('notes', $submission ? $submission->notes : '') }}</textarea>
                                    @error('notes')
                                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                {{-- Submit --}}
                                <button type="submit" class="w-full py-4 border-2 border-emerald-600 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-bold rounded-2xl transition-all shadow-sm flex justify-center items-center">
                                    {{ $submission ? 'Perbarui Jawaban (Max 10 MB)' : 'Tambahkan Jawaban (Max 10 MB)' }}
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
