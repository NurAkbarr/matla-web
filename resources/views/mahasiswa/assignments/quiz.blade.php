@extends('layouts.app')

@section('title', 'Kuis: ' . $assignment->title . ' - Matla Islamic University')

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
            <a href="{{ route('mahasiswa.assignments.show', $assignment->id) }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail Tugas
            </a>
        </div>

        <div class="max-w-3xl mx-auto space-y-6">
            <div class="bg-white rounded-[1.5rem] p-6 md:p-8 shadow-sm border border-gray-100">
                <h1 class="text-[22px] text-gray-900 mb-2 font-medium">{{ $assignment->title }}</h1>
                <p class="text-sm text-gray-500 mb-6">Batas waktu: {{ $assignment->due_date->translatedFormat('d F Y, H:i') }}</p>

                @if($submission && $submission->score !== null)
                    <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl mb-6">
                        Kuis ini telah dinilai. Skor Anda: <strong>{{ $submission->score }}</strong>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.assignments.quiz.submit', $assignment->id) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-8">
                        @foreach($assignment->questions as $index => $question)
                            @php
                                $previousAnswer = null;
                                $previousSelectedOptions = [];
                                if ($submission) {
                                    $qa = $submission->quizAnswers->where('assignment_question_id', $question->id)->first();
                                    if ($qa) {
                                        $previousAnswer = $qa->answer_text;
                                        $previousSelectedOptions = $qa->selectedOptions->pluck('assignment_question_option_id')->toArray();
                                    }
                                }
                                $isLocked = $submission && $submission->score !== null;
                            @endphp

                            <div class="p-6 border border-gray-200 rounded-2xl bg-white space-y-4">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-gray-900">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">{{ $question->points }} Poin</span>
                                </div>

                                <div>
                                    @if($question->type === 'multiple_choice')
                                        <div class="space-y-2 mt-4">
                                            @foreach($question->options as $option)
                                                <label class="flex items-center space-x-3 cursor-pointer group">
                                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" {{ in_array($option->id, $previousSelectedOptions) ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }} required>
                                                    <span class="text-gray-700">{{ $option->option_text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'checkbox')
                                        <div class="space-y-2 mt-4">
                                            @foreach($question->options as $option)
                                                <label class="flex items-center space-x-3 cursor-pointer group">
                                                    <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" {{ in_array($option->id, $previousSelectedOptions) ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                                    <span class="text-gray-700">{{ $option->option_text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'short_answer')
                                        <div class="mt-4">
                                            <input type="text" name="answers[{{ $question->id }}]" value="{{ $previousAnswer }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none" placeholder="Jawaban Anda" {{ $isLocked ? 'disabled' : '' }} required>
                                        </div>
                                    @elseif($question->type === 'paragraph')
                                        <div class="mt-4">
                                            <textarea name="answers[{{ $question->id }}]" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none resize-none" placeholder="Jawaban Anda" {{ $isLocked ? 'disabled' : '' }} required>{{ $previousAnswer }}</textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(!$isLocked)
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-sm transition-all">
                                Kirim Jawaban Kuis
                            </button>
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
