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
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden p-10" x-data="quizBuilder()">
        <form action="{{ route('backend.admin.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="assignmentForm">
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
                    <input type="datetime-local" name="due_date" id="due_date" required value="{{ old('due_date', $assignment->due_date->format('Y-m-d\TH:i')) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none">
                    @error('due_date')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Tugas --}}
                <div class="space-y-2">
                    <label for="type" class="text-sm font-bold text-slate-700">Tipe Tugas <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select name="type" id="type" x-model="assignmentType" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-2xl text-slate-700 font-medium transition-all outline-none appearance-none">
                            <option value="upload">Upload File & Link</option>
                            <option value="quiz">Kuis / Form Online</option>
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
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
 
            <!-- Form Upload/Link -->
            <div x-show="assignmentType === 'upload'" class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                        <span id="link-label">Tautan Eksternal (Referensi materi)</span>
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

            <!-- Form Quiz Builder -->
            <div x-cloak x-show="assignmentType === 'quiz'" class="space-y-6 border-t border-slate-100 pt-8 mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-800">Builder Soal (Kuis)</h3>
                    <div class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl">
                        Total Poin: <span x-text="getTotalPoints()"></span>
                    </div>
                </div>
                
                <template x-for="(question, index) in questions" :key="question.id">
                    <div class="p-5 bg-white border-2 border-slate-800 rounded-xl relative space-y-4 transition-all">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-slate-800" x-text="'Soal ' + (index + 1)"></span>
                            <button type="button" @click="removeQuestion(index)" class="text-rose-500 hover:text-rose-700 p-1.5 transition-colors" title="Hapus Soal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Question Text -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Pertanyaan</label>
                            <input type="text" x-model="question.text" :name="'questions['+index+'][text]'" placeholder="Ketik pertanyaan di sini..." class="w-full px-4 py-2.5 border-2 border-slate-800 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-lg outline-none transition-all font-medium text-sm" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Type -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Tipe Soal</label>
                                <select x-model="question.type" :name="'questions['+index+'][type]'" class="w-full px-4 py-2.5 border-2 border-slate-800 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-lg outline-none transition-all font-medium appearance-none bg-white text-sm">
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="checkbox">Kotak Centang (Checkboxes)</option>
                                    <option value="short_answer">Jawaban Singkat</option>
                                    <option value="paragraph">Paragraf / Essay</option>
                                </select>
                            </div>
                            <!-- Points -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Poin Soal</label>
                                <input type="number" min="0" x-model.number="question.points" :name="'questions['+index+'][points]'" placeholder="Misal: 10" class="w-full px-4 py-2.5 border-2 border-slate-800 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-lg outline-none transition-all font-medium bg-white text-sm">
                            </div>
                        </div>

                        <!-- Options for multiple choice / checkbox -->
                        <template x-if="['multiple_choice', 'checkbox'].includes(question.type)">
                            <div class="space-y-2.5 mt-4 pt-4 border-t-2 border-slate-100">
                                <label class="text-[11px] font-bold text-slate-800 uppercase tracking-wider block mb-2">Opsi Jawaban (Tandai yang benar)</label>
                                <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                    <div class="flex items-center space-x-3 group">
                                        <div class="relative flex items-center justify-center w-6 h-6 border-2 border-slate-800 bg-white group-hover:border-emerald-500 transition-colors cursor-pointer" :class="question.type === 'multiple_choice' ? 'rounded-full' : 'rounded-md'" @click="toggleCorrectOption(index, optIndex)">
                                            <!-- Hidden input to store value -->
                                            <input type="hidden" :name="'questions['+index+'][options]['+optIndex+'][is_correct]'" :value="option.is_correct ? '1' : '0'">
                                            
                                            <!-- Custom UI for Radio/Checkbox -->
                                            <div x-show="option.is_correct" class="w-3 h-3 bg-emerald-600 absolute" :class="question.type === 'multiple_choice' ? 'rounded-full' : 'rounded-[2px]'"></div>
                                        </div>
                                        <input type="text" x-model="option.text" :name="'questions['+index+'][options]['+optIndex+'][text]'" placeholder="Opsi jawaban" class="flex-1 px-3 py-2 border-2 border-slate-800 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-lg outline-none transition-all font-medium bg-white text-sm" required>
                                        <button type="button" @click="removeOption(index, optIndex)" class="text-slate-400 hover:text-rose-500 p-1.5 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="addOption(index)" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 mt-2 inline-flex items-center px-3 py-1.5 border-2 border-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Opsi
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                <button type="button" @click="addQuestion()" class="w-full py-4 border-2 border-dashed border-slate-800 text-slate-800 hover:bg-slate-50 rounded-xl font-bold transition-all flex items-center justify-center text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Soal Baru
                </button>
            </div>
 
            {{-- Submit --}}
            <div class="pt-6 border-t border-slate-100 flex justify-end space-x-3">
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
<script>
    function quizBuilder() {
        return {
            assignmentType: '{{ $assignment->type ?? "upload" }}',
            questions: {!! json_encode($assignment->questions()->with('options')->get()->map(function($q) {
                return [
                    'id' => $q->id,
                    'type' => $q->type,
                    'text' => $q->question_text,
                    'points' => $q->points,
                    'options' => $q->options->map(function($o) {
                        return [
                            'id' => $o->id,
                            'text' => $o->option_text,
                            'is_correct' => (bool) $o->is_correct
                        ];
                    })->toArray()
                ];
            })) !!},
            init() {
                // Add initial question if empty
                if (this.questions.length === 0 && this.assignmentType === 'quiz') {
                    this.addQuestion();
                }
            },
            generateId() {
                return Math.random().toString(36).substr(2, 9);
            },
            addQuestion() {
                this.questions.push({
                    id: this.generateId(),
                    type: 'multiple_choice',
                    text: '',
                    points: 10,
                    options: [
                        { text: 'Opsi 1', is_correct: false },
                        { text: 'Opsi 2', is_correct: false }
                    ]
                });
            },
            removeQuestion(index) {
                if (this.questions.length > 1) {
                    this.questions.splice(index, 1);
                } else {
                    alert('Minimal harus ada 1 soal.');
                }
            },
            addOption(questionIndex) {
                this.questions[questionIndex].options.push({
                    text: 'Opsi ' + (this.questions[questionIndex].options.length + 1),
                    is_correct: false
                });
            },
            removeOption(questionIndex, optionIndex) {
                if (this.questions[questionIndex].options.length > 2) {
                    this.questions[questionIndex].options.splice(optionIndex, 1);
                } else {
                    alert('Soal pilihan ganda minimal harus memiliki 2 opsi.');
                }
            },
            toggleCorrectOption(questionIndex, optionIndex) {
                const question = this.questions[questionIndex];
                if (question.type === 'multiple_choice') {
                    const currentState = question.options[optionIndex].is_correct;
                    question.options.forEach((opt) => {
                        opt.is_correct = false;
                    });
                    question.options[optionIndex].is_correct = !currentState;
                } else {
                    question.options[optionIndex].is_correct = !question.options[optionIndex].is_correct;
                }
            },
            getTotalPoints() {
                return this.questions.reduce((total, q) => total + (parseInt(q.points) || 0), 0);
            }
        }
    }
</script>
@endpush
@endsection
