@extends(Auth::user()->role === 'dosen' ? 'layouts.dosen' : 'layouts.backend')

@section('title', 'Jawaban Kuis Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-20">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.admin.assignments.show', $assignment->id) }}" class="p-3 bg-slate-50 text-slate-500 hover:text-primary hover:bg-emerald-50 rounded-2xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">Kuis</span>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight mt-1.5">{{ $submission->student->name }}</h1>
                <p class="text-slate-500 font-medium mt-1">
                    Kuis: <strong class="text-slate-700">{{ $assignment->title }}</strong>
                </p>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-sm font-bold text-slate-500 mb-1">Skor Saat Ini</div>
            <div class="text-4xl font-black {{ $submission->score !== null ? 'text-emerald-600' : 'text-amber-500' }}">
                {{ $submission->score ?? 'Belum Dinilai' }}
            </div>
        </div>
    </div>

    {{-- Answers --}}
    <div class="space-y-6">
        @foreach($assignment->questions as $index => $question)
            @php
                $qa = $submission->quizAnswers->where('assignment_question_id', $question->id)->first();
                $isCorrect = $qa ? $qa->is_correct : false;
                $isAutoGraded = in_array($question->type, ['multiple_choice', 'checkbox']);
            @endphp
            <div class="bg-white p-6 rounded-[2rem] border {{ $isAutoGraded ? ($isCorrect ? 'border-emerald-200 bg-emerald-50/30' : 'border-rose-200 bg-rose-50/30') : 'border-gray-200' }}">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-slate-800">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-slate-500">{{ $question->points }} Poin</span>
                        @if($isAutoGraded)
                            @if($isCorrect)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded uppercase">Benar</span>
                            @else
                                <span class="px-2 py-1 bg-rose-100 text-rose-700 text-[10px] font-black rounded uppercase">Salah</span>
                            @endif
                        @else
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded uppercase">Perlu Dinilai Manual</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    @if(in_array($question->type, ['multiple_choice', 'checkbox']))
                        <div class="space-y-2">
                            @php
                                $selectedIds = $qa ? $qa->selectedOptions->pluck('assignment_question_option_id')->toArray() : [];
                            @endphp
                            @foreach($question->options as $option)
                                @php
                                    $isSelected = in_array($option->id, $selectedIds);
                                    $isOptionCorrect = $option->is_correct;
                                    
                                    $bgClass = 'bg-slate-50 border-slate-200 text-slate-600';
                                    if ($isSelected && $isOptionCorrect) {
                                        $bgClass = 'bg-emerald-100 border-emerald-300 text-emerald-800 font-bold';
                                    } elseif ($isSelected && !$isOptionCorrect) {
                                        $bgClass = 'bg-rose-100 border-rose-300 text-rose-800 font-bold';
                                    } elseif (!$isSelected && $isOptionCorrect) {
                                        $bgClass = 'bg-emerald-50 border-emerald-200 text-emerald-600 font-bold border-dashed';
                                    }
                                @endphp
                                <div class="px-4 py-3 rounded-xl border {{ $bgClass }} flex items-center">
                                    <div class="w-5 h-5 rounded{{ $question->type === 'multiple_choice' ? '-full' : '' }} border flex items-center justify-center mr-3 {{ $isSelected ? 'bg-current border-current text-white' : 'border-slate-300 bg-white' }}">
                                        @if($isSelected)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>
                                    {{ $option->option_text }}
                                    
                                    @if($isOptionCorrect)
                                        <span class="ml-auto text-emerald-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <h4 class="text-xs font-bold text-slate-500 uppercase mb-2">Jawaban Mahasiswa:</h4>
                            <p class="text-slate-800 whitespace-pre-line">{{ $qa ? $qa->answer_text : '-' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
