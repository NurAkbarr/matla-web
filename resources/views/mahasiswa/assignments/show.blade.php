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

                    @php $showLink = ($assignment->submission_type ?? 'file') !== 'external'; @endphp
                    @if($assignment->file_path || ($assignment->link && $showLink))
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
                            @if($assignment->link && $showLink)
                                <a href="{{ $assignment->link }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-[10px] flex items-center justify-center font-black text-xs mr-4 shrink-0 shadow-sm">
                                        LINK
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-medium text-gray-900 truncate">{{ $assignment->link }}</p>
                                        <p class="text-[12px] text-gray-500 mt-0.5">Tautan / Referensi</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
 
                {{-- Unified Graded / Submitted Info Card --}}
                @if($submission)
                    @php
                        $otherCount = $assignment->submissions()->where('student_id', '!=', auth()->id())->count();
                    @endphp
                    <div class="bg-emerald-50 text-emerald-900 border border-emerald-100 rounded-[1.5rem] p-4 text-center text-sm font-semibold mb-6">
                        @if($otherCount === 0)
                            Kamu adalah yang pertama mengumpulkan! 🎉
                        @else
                            Tugas mu sudah dikumpulkan bersama <span class="font-extrabold text-emerald-700">{{ $otherCount }}</span> mahasiswa lainnya! 🎉
                        @endif
                    </div>

                    <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm overflow-hidden mb-6">
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-bold text-gray-900">Jawaban Anda</h3>
                                <a href="#" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Riwayat Pengerjaan
                                </a>
                            </div>

                            <p class="text-xs text-gray-400 font-semibold">{{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}</p>

                            <div class="space-y-3 pt-2">
                                    {{-- Unified representation: Show both text/link and file if they exist --}}
                                    @if($submission->submitted_link)
                                        <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                            <div class="p-2 bg-slate-50 rounded-xl text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            </div>
                                            <a href="{{ $submission->submitted_link }}" target="_blank" class="text-indigo-600 hover:underline font-bold truncate max-w-[250px]">{{ $submission->submitted_link }}</a>
                                        </div>
                                    @endif

                                    @if($submission->submitted_file_path)
                                        <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                            <div class="p-2 bg-slate-50 rounded-xl text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <a href="{{ route('tugas.download', ['path' => $submission->submitted_file_path]) }}" target="_blank" class="text-emerald-600 hover:underline font-bold">{{ basename($submission->submitted_file_path) }}</a>
                                        </div>
                                    @endif

                                {{-- Feedback indicator --}}
                                <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                    <div class="p-2 bg-slate-50 rounded-xl text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <span>
                                        @if($submission->score !== null && $submission->feedback)
                                            1 Feedback Pengajar
                                        @else
                                            Belum Ada Feedback
                                        @endif
                                    </span>
                                </div>

                                @if($submission->notes)
                                <div class="text-xs text-gray-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 mt-2 prose prose-sm max-w-none">
                                    {!! $submission->notes !!}
                                </div>
                                @endif

                                @if($submission->feedback)
                                <div class="text-xs text-emerald-800 bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100 italic mt-2">
                                    <strong>Catatan Dosen:</strong> "{{ $submission->feedback }}"
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Bar --}}
                        @if($submission->score !== null)
                            <div class="bg-emerald-600 text-white px-6 py-4 flex items-center justify-between text-base font-bold">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sudah dinilai
                                </div>
                                <div class="text-2xl font-black">{{ $submission->score }}</div>
                            </div>
                        @else
                            <div class="bg-amber-500 text-white px-6 py-4 flex items-center justify-between text-base font-bold">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sudah dikumpulkan (Belum dinilai)
                                </div>
                                <div class="text-xl font-black">-</div>
                            </div>
                        @endif
                    </div>
                @endif
 
                {{-- Form submission / resubmission --}}
                @php
                    $isOverdue = now()->gt($assignment->due_date);
                    $submissionType = $assignment->submission_type ?? 'file';
                @endphp
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
                    {{-- Unified Submission Form --}}
                    <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-sm mt-6">
                        <form action="{{ route('mahasiswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- Header --}}
                            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
                                <h3 class="text-base font-bold text-gray-900">
                                    Jawaban:
                                </h3>
                                <div class="flex items-center gap-4">
                                    @if($submission)
                                    <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Riwayat Pengumpulan
                                    </a>
                                    @endif
                                    <a href="{{ route('mahasiswa.assignments.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Batal</a>
                                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        {{ $submission ? 'Perbarui Jawaban' : 'Kirim Jawaban' }}
                                    </button>
                                </div>
                            </div>

                            {{-- Textarea and Attachments --}}
                            <div class="relative rounded-2xl border border-gray-200 focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500 transition-all bg-gray-50/50">
                                <textarea name="notes" id="notes" class="w-full px-4 py-4 bg-transparent text-sm text-gray-700 border-none focus:ring-0 resize-none min-h-[150px] outline-none" placeholder="Ketik jawaban Anda di sini (opsional jika hanya mengirim berkas)...">{{ old('notes', $submission?->notes) }}</textarea>
                                
                                <div class="px-4 py-3 border-t border-gray-200 bg-white rounded-b-2xl flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <label class="cursor-pointer text-gray-500 hover:text-emerald-600 p-2 hover:bg-emerald-50 rounded-xl transition-colors relative group" title="Tambahkan Berkas">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <input type="file" name="submitted_file" id="submitted_file" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0].name;" />
                                        </label>
                                        <span id="file-name" class="text-xs font-bold text-emerald-600">
                                            @if($submission?->submitted_file_path)
                                                Berkas terunggah: {{ basename($submission->submitted_file_path) }}
                                            @else
                                                Belum ada berkas yang dipilih.
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @error('notes') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                            @error('submitted_file') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror

                            <p class="text-[11px] text-gray-500 mt-4">
                                <span class="font-bold text-gray-700">Tipe berkas yang dapat di unggah:</span> pdf, ppt, pptx, xls, xlsx, doc, docx, txt, jpeg, jpg, png, gif, rar, zip.
                            </p>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

