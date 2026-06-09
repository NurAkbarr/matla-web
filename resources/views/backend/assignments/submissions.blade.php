@extends(Auth::user()->role === 'dosen' ? 'layouts.dosen' : 'layouts.backend')
 
@section('title', 'Pusat Penilaian Tugas')
 
@section('content')
<div class="max-w-7xl mx-auto space-y-8" x-data="{ gradingModal: false, activeStudent: '', activeSubmissionId: '', activeScore: '', activeFeedback: '', activeAction: '' }">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.admin.assignments.index') }}" class="p-3 bg-slate-50 text-slate-500 hover:text-primary hover:bg-emerald-50 rounded-2xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">{{ $classGroup->name }}</span>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight mt-1.5">{{ $assignment->title }}</h1>
                <p class="text-slate-500 font-medium mt-1">
                    Batas Waktu: <strong class="text-slate-700">{{ $assignment->due_date->translatedFormat('d F Y, H:i') }} WIB</strong>
                </p>
            </div>
        </div>
 
        <div class="flex flex-wrap items-center gap-3">
            @if($assignment->file_path)
                <a href="{{ route('assignment.download', ['path' => str_replace('#', '%23', $assignment->file_path)]) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl text-xs transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Lihat Berkas Petunjuk
                </a>
            @endif
            @if($assignment->link)
                <a href="{{ $assignment->link }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Buka Link Referensi
                </a>
            @endif
        </div>
    </div>
 
    {{-- Assignment Details if any --}}
    @if($assignment->description)
        <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-2">Instruksi & Deskripsi Tugas</h3>
            <p class="text-slate-600 leading-relaxed font-medium whitespace-pre-line text-sm">{{ $assignment->description }}</p>
        </div>
    @endif
 
    {{-- Submissions Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-lg">Pekerjaan Mahasiswa</h3>
            <div class="flex items-center space-x-4 text-xs font-bold">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full">{{ $submissions->count() }} Mengumpulkan</span>
                <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-full">{{ $students->count() - $submissions->count() }} Belum Mengumpulkan</span>
            </div>
        </div>
 
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Status & Waktu</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Jawaban Tugas</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider">Nilai / Umpan Balik</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $student)
                        @php
                            $sub = $submissions->get($student->id);
                        @endphp
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 text-base">{{ $student->name }}</div>
                                <div class="text-xs text-slate-400 font-bold tracking-widest uppercase mt-0.5">NIM: {{ $student->nim ?? '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                @if($sub)
                                    @php
                                        $isLate = $sub->submitted_at->gt($assignment->due_date);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold">
                                        Sudah Mengumpulkan
                                    </span>
                                    <div class="text-[11px] text-slate-400 font-bold mt-1">
                                        {{ $sub->submitted_at->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                    @if($isLate)
                                        <span class="inline-flex text-[9px] font-black text-rose-500 uppercase tracking-tighter mt-0.5">
                                            Terlambat
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold">
                                        Belum Mengumpulkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                @if($sub)
                                    <div class="space-y-1.5">
                                        @if($assignment->submission_type === 'external')
                                            {{-- External: no file/link collected, just show badge --}}
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                                Dikerjakan via Tautan Eksternal
                                            </span>
                                            <div class="text-[10px] text-slate-400 font-medium italic">— Jawaban dikerjakan di luar sistem</div>
                                        @else
                                            @if($assignment->type === 'quiz')
                                                <a href="{{ route('backend.admin.assignments.quiz-answers', $sub) }}" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Jawaban Kuis
                                                </a>
                                            @else
                                                @if($sub->submitted_file_path)
                                                    <a href="{{ route('tugas.download', ['path' => str_replace('#', '%23', $sub->submitted_file_path)]) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Unduh Jawaban Berkas
                                                    </a>
                                                @endif
                                                @if($sub->submitted_link)
                                                    <a href="{{ $sub->submitted_link }}" target="_blank" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-700">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                        </svg>
                                                        Buka Tautan Jawaban
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                        @if($sub->notes)
                                            <div class="text-xs text-slate-500 font-medium italic mt-1 max-w-xs truncate" title="{{ $sub->notes }}">
                                                "{{ $sub->notes }}"
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-300 font-bold text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                @if($sub && $sub->score !== null)
                                    <div class="flex items-baseline space-x-1.5">
                                        <span class="text-xl font-black text-slate-800">{{ $sub->score }}</span>
                                        <span class="text-xs font-semibold text-slate-400">/100</span>
                                    </div>
                                    @if($sub->feedback)
                                        <div class="text-xs text-slate-400 font-medium mt-1 max-w-xs truncate" title="{{ $sub->feedback }}">
                                            Catatan: {{ $sub->feedback }}
                                        </div>
                                    @endif
                                @elseif($sub)
                                    <span class="inline-flex px-2 py-0.5 bg-amber-50 text-amber-600 rounded-md text-[10px] font-black uppercase">
                                        Belum Dinilai
                                    </span>
                                @else
                                    <span class="text-slate-300 font-bold text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($sub)
                                    <button 
                                        @click="gradingModal = true; activeStudent = '{{ $student->name }}'; activeSubmissionId = '{{ $sub->id }}'; activeScore = '{{ $sub->score ?? '' }}'; activeFeedback = '{{ $sub->feedback ?? '' }}'; activeAction = '{{ route('backend.admin.assignments.grade', $sub) }}'"
                                        class="inline-flex items-center px-4 py-2 {{ $sub->score !== null ? 'bg-slate-100 hover:bg-slate-200 text-slate-600' : 'bg-primary hover:bg-primary-dark text-white shadow-md shadow-primary/10' }} font-bold rounded-xl text-xs transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        {{ $sub->score !== null ? 'Koreksi Nilai' : 'Beri Nilai' }}
                                    </button>
                                @else
                                    <button disabled class="inline-flex items-center px-4 py-2 bg-slate-50 text-slate-300 font-bold rounded-xl text-xs cursor-not-allowed">
                                        Beri Nilai
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- Alpine.js Grading Modal --}}
    <div x-show="gradingModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="gradingModal = false"></div>
        
        {{-- Modal Content --}}
        <div class="relative bg-white w-full max-w-lg rounded-[2rem] p-8 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Form Penilaian Tugas</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1" x-text="activeStudent"></p>
                </div>
                <button @click="gradingModal = false" class="p-2 text-slate-400 hover:text-rose-500 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form :action="activeAction" method="POST" class="space-y-6">
                @csrf
                
                {{-- Score Input --}}
                <div class="space-y-2">
                    <label for="modal_score" class="text-sm font-bold text-slate-700">Nilai Anggota (0-100) <span class="text-rose-500">*</span></label>
                    <input type="number" name="score" id="modal_score" required min="0" max="100" placeholder="Masukkan nilai 0-100" x-model="activeScore" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-bold transition-all outline-none">
                </div>
 
                {{-- Feedback Input --}}
                <div class="space-y-2">
                    <label for="modal_feedback" class="text-sm font-bold text-slate-700">Catatan / Umpan Balik</label>
                    <textarea name="feedback" id="modal_feedback" rows="4" placeholder="Tuliskan masukan atau kritik membangun bagi mahasiswa..." x-model="activeFeedback" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-semibold transition-all outline-none resize-none"></textarea>
                </div>
 
                {{-- Submit --}}
                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                    <button type="button" @click="gradingModal = false" class="px-6 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold rounded-2xl text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl text-xs transition-all shadow-lg shadow-primary/20">
                        Simpan Nilai & Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
