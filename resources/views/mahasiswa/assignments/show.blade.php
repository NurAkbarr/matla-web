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
                                @php $subType = $assignment->submission_type ?? 'file'; @endphp

                                @if($subType === 'external')
                                    {{-- External: no file/link to show, just a status badge --}}
                                    <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        </div>
                                        <span class="text-indigo-600 font-bold text-sm">Dikerjakan melalui tautan eksternal</span>
                                    </div>
                                    <p class="text-[11px] text-gray-400 italic pl-1">— Jawaban dikerjakan di luar sistem (Google Form / Quizizz / dll)</p>

                                @elseif($subType === 'link')
                                    {{-- Link type: show submitted link --}}
                                    @if($submission->submitted_link)
                                        <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                            <div class="p-2 bg-slate-50 rounded-xl text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            </div>
                                            <a href="{{ $submission->submitted_link }}" target="_blank" class="text-indigo-600 hover:underline font-bold truncate max-w-[250px]">{{ $submission->submitted_link }}</a>
                                        </div>
                                    @endif

                                @else
                                    {{-- File type: show file if uploaded --}}
                                    @if($submission->submitted_file_path)
                                        <div class="flex items-center space-x-3 text-sm text-gray-700 font-medium">
                                            <div class="p-2 bg-slate-50 rounded-xl text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <a href="{{ route('foto.bypass', ['path' => $submission->submitted_file_path]) }}" target="_blank" class="text-emerald-600 hover:underline font-bold">{{ basename($submission->submitted_file_path) }}</a>
                                        </div>
                                    @endif
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

                                @if($submission->notes && $subType !== 'external')
                                <div class="text-xs text-gray-400 bg-slate-50 p-3 rounded-2xl border border-slate-100 italic mt-2">
                                    "{{ $submission->notes }}"
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
                @elseif($submissionType === 'external')
                    {{-- External link: show auto-submit button only if not yet submitted --}}
                    @if(!$submission)
                        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4 text-center">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">Tugas Eksternal</h3>
                            <div class="py-4">
                                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800">Kerjakan Melalui Tautan Eksternal</p>
                                <p class="text-xs font-medium text-gray-500 mt-2 px-4">Tugas ini dikerjakan melalui platform eksternal (Google Form, Quizizz, dll). Klik tombol di bawah, sistem akan otomatis merekam bahwa Anda telah mengerjakannya.</p>
                            </div>
                            <button
                                id="btn-kerjakan"
                                onclick="kerjakanEksternal(this)"
                                data-url="{{ $assignment->link }}"
                                data-action="{{ route('mahasiswa.assignments.auto-submit', $assignment) }}"
                                data-token="{{ csrf_token() }}"
                                class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-emerald-600/20"
                            >
                                Kerjakan Sekarang →
                            </button>
                        </div>
                    @endif
                @elseif($submissionType === 'link')
                    {{-- Link submission --}}
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">
                            {{ $submission ? 'Perbarui Tautan Jawaban' : 'Kumpulkan Tautan Jawaban' }}
                        </h3>
                        <form action="{{ route('mahasiswa.assignments.submit', $assignment) }}" method="POST" class="space-y-5">
                            @csrf
                            {{-- Link input --}}
                            <div class="space-y-2">
                                <label for="submitted_link" class="text-xs font-bold text-gray-700 block">Tautan Jawaban (Google Drive, YouTube, dll) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="url" name="submitted_link" id="submitted_link" required placeholder="https://drive.google.com/..." value="{{ old('submitted_link', $submission?->submitted_link) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs text-gray-700 font-semibold transition-all outline-none">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    </div>
                                </div>
                                @error('submitted_link')
                                    <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Notes --}}
                            <div class="space-y-2">
                                <label for="notes" class="text-xs font-bold text-gray-700 block">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3" placeholder="Tuliskan catatan tambahan jika ada..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs text-gray-700 font-semibold transition-all outline-none resize-none">{{ old('notes', $submission?->notes) }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-4 border-2 border-emerald-600 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-bold rounded-2xl transition-all shadow-sm flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                {{ $submission ? 'Perbarui Tautan Jawaban' : 'Kumpulkan Tautan Jawaban' }}
                            </button>
                        </form>
                    </div>
                @else
                    {{-- File submission (default) --}}
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-4">
                            {{ $submission ? 'Perbarui Berkas Jawaban' : 'Kumpulkan Berkas Jawaban' }}
                        </h3>
                        <form action="{{ route('mahasiswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            {{-- File upload --}}
                            <div class="space-y-2">
                                <label for="submitted_file" class="text-xs font-bold text-gray-700 block">
                                    Berkas Jawaban (PDF / Word / Excel) <span class="text-rose-500">*</span>
                                    @if($submission?->submitted_file_path)
                                        <span class="font-normal text-gray-400 ml-1">— Sudah ada berkas sebelumnya</span>
                                    @endif
                                </label>
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
                            {{-- Notes --}}
                            <div class="space-y-2">
                                <label for="notes" class="text-xs font-bold text-gray-700 block">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3" placeholder="Tuliskan catatan tambahan jika ada..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-2xl text-xs text-gray-700 font-semibold transition-all outline-none resize-none">{{ old('notes', $submission?->notes) }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-4 border-2 border-emerald-600 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-bold rounded-2xl transition-all shadow-sm flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                {{ $submission ? 'Perbarui Berkas (Max 10 MB)' : 'Kumpulkan Berkas (Max 10 MB)' }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function kerjakanEksternal(btn) {
    const url    = btn.dataset.url;
    const action = btn.dataset.action;
    const token  = btn.dataset.token;

    if (!url) {
        alert('Tautan eksternal belum diatur oleh pengajar. Silakan hubungi dosen atau admin.');
        return;
    }

    // Immediately open the external link in a new tab
    window.open(url, '_blank');

    // Disable button and show loading state
    btn.disabled = true;
    btn.textContent = 'Merekam kehadiran...';

    // Record submission in background via fetch
    fetch(action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
    })
    .finally(() => {
        // Reload page after a short delay to reflect submitted status
        setTimeout(() => window.location.reload(), 800);
    });
}
</script>
@endpush
