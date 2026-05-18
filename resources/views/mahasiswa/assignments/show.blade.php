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
 
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Column Left: Assignment detail & Instructions (2 Cols) --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-6 mb-6">
                        <div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">TUGAS KULIAH</span>
                            <h1 class="text-xl md:text-2xl font-black text-gray-900 mt-2 leading-snug">{{ $assignment->title }}</h1>
                        </div>
                        <div class="text-right hidden md:block">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Dibuat Oleh</p>
                            <p class="text-sm font-black text-gray-800 mt-1">{{ $assignment->creator->name }}</p>
                        </div>
                    </div>
 
                    {{-- Instructions Box --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Deskripsi / Petunjuk Pengerjaan</h3>
                        @if($assignment->description)
                            <div class="text-gray-600 font-medium text-sm leading-relaxed whitespace-pre-line bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                                {{ $assignment->description }}
                            </div>
                        @else
                            <p class="text-gray-400 text-sm italic">Tidak ada deskripsi tertulis tambahan.</p>
                        @endif
                    </div>
 
                    {{-- Attachments provided by lecturer --}}
                    @if($assignment->file_path || $assignment->link)
                        <div class="mt-8 pt-6 border-t border-gray-50 space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Lampiran & Referensi Dosen</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($assignment->file_path)
                                    <a href="{{ route('foto.bypass', ['path' => $assignment->file_path]) }}" target="_blank" class="flex items-center p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl hover:bg-emerald-50 transition-colors group">
                                        <div class="p-3 bg-emerald-500 text-white rounded-xl mr-4 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider leading-none">Unduh Panduan</p>
                                            <p class="text-xs font-black text-gray-700 mt-1 truncate">{{ basename($assignment->file_path) }}</p>
                                        </div>
                                    </a>
                                @endif
                                @if($assignment->link)
                                    <a href="{{ $assignment->link }}" target="_blank" class="flex items-center p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl hover:bg-indigo-50 transition-colors group">
                                        <div class="p-3 bg-indigo-500 text-white rounded-xl mr-4 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-indigo-800 uppercase tracking-wider leading-none">Buka Referensi</p>
                                            <p class="text-xs font-black text-gray-700 mt-1 truncate">{{ $assignment->link }}</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
 
            {{-- Column Right: Submission card, grades & forms (1 Col) --}}
            <div class="space-y-8">
                {{-- Deadlines summary card --}}
                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Batas Waktu</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-slate-50 rounded-xl text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">Batas Waktu Pengumpulan</p>
                                <p class="text-xs font-bold text-gray-700 mt-1">{{ $assignment->due_date->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        
                        @php
                            $isOverdue = now()->gt($assignment->due_date);
                        @endphp
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-{{ $isOverdue ? 'rose' : 'emerald' }}-50 rounded-xl text-{{ $isOverdue ? 'rose' : 'emerald' }}-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">Status Tenggat</p>
                                <p class="text-xs font-black text-{{ $isOverdue ? 'rose-500' : 'emerald-600' }} uppercase mt-1">
                                    {{ $isOverdue ? 'Batas Waktu Habis' : 'Sedang Berlangsung' }}
                                </p>
                            </div>
                        </div>
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
                            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl transition-all shadow-lg shadow-emerald-600/20">
                                {{ $submission ? 'Perbarui Pengumpulan Tugas' : 'Kirim Pengumpulan Tugas' }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
