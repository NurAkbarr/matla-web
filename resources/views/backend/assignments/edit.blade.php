@extends(Auth::user()->role === 'dosen' ? 'layouts.dosen' : 'layouts.backend')
 
@section('title', 'Edit Tugas')
 
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center space-x-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <a href="{{ route('backend.admin.assignments.index') }}" class="p-3 bg-slate-50 text-slate-500 hover:text-primary hover:bg-emerald-50 rounded-2xl transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Edit Tugas MHS</h1>
            <p class="text-slate-500 font-medium mt-1">Perbarui informasi penugasan, berkas lampiran, dan batas waktu pengumpulan.</p>
        </div>
    </div>
 
    {{-- Form --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden p-10">
        <form action="{{ route('backend.admin.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kelompok Kelas --}}
                <div class="space-y-2">
                    <label for="class_group_id" class="text-sm font-bold text-slate-700">Target Kelompok Kelas <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select name="class_group_id" id="class_group_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none appearance-none">
                            <option value="">-- Pilih Kelompok Kelas --</option>
                            @foreach($classGroups as $classGroup)
                                <option value="{{ $classGroup->id }}" {{ old('class_group_id', $assignment->class_group_id) == $classGroup->id ? 'selected' : '' }}>
                                    {{ $classGroup->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('class_group_id')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mata Kuliah --}}
                <div class="space-y-2">
                    <label for="mata_kuliah_id" class="text-sm font-bold text-slate-700">Mata Kuliah <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select name="mata_kuliah_id" id="mata_kuliah_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none appearance-none">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $assignment->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->nama }} ({{ $mk->kode }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('mata_kuliah_id')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Batas Waktu --}}
                <div class="space-y-2">
                    <label for="due_date" class="text-sm font-bold text-slate-700">Batas Waktu (Due Date) <span class="text-rose-500">*</span></label>
                    <input type="text" name="due_date" id="due_date" required value="{{ old('due_date', $assignment->due_date->format('Y-m-d H:i')) }}" placeholder="Pilih tanggal dan waktu" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none">
                    @error('due_date')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
 
            {{-- Judul Tugas --}}
            <div class="space-y-2">
                <label for="title" class="text-sm font-bold text-slate-700">Judul Tugas <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Makalah Analisis Pendidikan Kontemporer" value="{{ old('title', $assignment->title) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none">
                @error('title')
                    <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            {{-- Deskripsi Tugas --}}
            <div class="space-y-2">
                <label for="description" class="text-sm font-bold text-slate-700">Deskripsi & Instruksi Tugas</label>
                <textarea name="description" id="description" rows="6" placeholder="Masukkan detail penugasan, sistematika penulisan, atau kriteria penilaian..." class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none resize-none">{{ old('description', $assignment->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Upload Berkas Lampiran --}}
                <div class="space-y-2">
                    <label for="file_attachment" class="text-sm font-bold text-slate-700">Lampiran Berkas (PDF, Word, zip, rar)</label>
                    <div class="relative flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 hover:border-primary rounded-2xl cursor-pointer hover:bg-slate-50 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-xs text-slate-400 font-bold">Klik untuk unggah berkas baru (Max 10MB)</p>
                                <p id="file-chosen" class="text-xs text-primary font-bold mt-1 {{ $assignment->file_path ? '' : 'hidden' }}">
                                    {{ $assignment->file_path ? basename($assignment->file_path) : '' }}
                                </p>
                            </div>
                            <input type="file" name="file_attachment" id="file_attachment" class="hidden" onchange="document.getElementById('file-chosen').textContent = this.files[0].name; document.getElementById('file-chosen').classList.remove('hidden');" />
                        </label>
                    </div>
                    @error('file_attachment')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Link Rujukan --}}
                <div class="space-y-2">
                    <label for="link" class="text-sm font-bold text-slate-700">
                        <span id="link-label">Tautan Eksternal (Google Form, Quiz, Drive, dll)</span>
                    </label>
                    <div class="relative">
                        <input type="url" name="link" id="link" placeholder="https://example.com/panduan" value="{{ old('link', $assignment->link) }}" class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                    </div>
                    @error('link')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
 
            {{-- Submit --}}
            <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('backend.admin.assignments.index') }}" class="px-6 py-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-bold rounded-2xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl text-sm transition-all shadow-lg shadow-primary/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#due_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true
    });
</script>
@endpush
@endsection
