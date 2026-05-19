@extends('layouts.app')
 
@section('title', 'Tugas Mahasiswa - Matla Islamic University')
 
@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
    </style>
 
    <div class="container mx-auto px-4 lg:px-12 pt-8">
        {{-- Breadcrumb / Back --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('backend.mahasiswa.dashboard') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
            
            @if($classGroup)
                <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase tracking-wider">
                    Kelas: {{ $classGroup->name }}
                </span>
            @endif
        </div>
 
        {{-- Welcome Header --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Tugas Kuliah Saya</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Daftar penugasan akademik aktif khusus untuk kelompok kelas Anda.</p>
            </div>
            
            <div class="flex-shrink-0 bg-emerald-50 px-6 py-4 rounded-2xl flex items-center space-x-3">
                <div class="p-2.5 bg-emerald-500 text-white rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total Tugas</p>
                    <p class="text-xl font-black text-gray-900 mt-1">{{ $assignments->count() }} Tugas</p>
                </div>
            </div>
        </div>
 
        @if(!$classGroup)
            <div class="bg-white rounded-[2rem] p-16 shadow-sm border border-gray-100 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Kelompok Kelas Belum Terdaftar!</h3>
                <p class="text-gray-500 text-sm max-w-md">Profil Anda belum terintegrasi dengan kelompok kelas manapun. Silakan lengkapi Program Studi dan Angkatan Anda di Biodata diri Anda.</p>
                <a href="{{ route('mahasiswa.profil') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-emerald-600/20">
                    Lengkapi Biodata Sekarang
                </a>
            </div>
        @elseif($assignments->isEmpty())
            <div class="bg-white rounded-[2rem] p-16 shadow-sm border border-gray-100 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Belum ada tugas kuliah!</h3>
                <p class="text-gray-400 text-sm max-w-sm">Dosen atau Admin belum membagikan tugas untuk kelompok kelas Anda.</p>
            </div>
        @else
            {{-- Tugas Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignments as $assignment)
                    @php
                        $sub = $assignment->submission;
                        $isOverdue = now()->gt($assignment->due_date);
                        
                        // Status badge colors & label
                        if ($sub) {
                            if ($sub->score !== null) {
                                $badgeColor = 'bg-emerald-50 border-emerald-100 text-emerald-700';
                                $statusLabel = "Sudah Dinilai: {$sub->score}/100";
                            } else {
                                $badgeColor = 'bg-amber-50 border-amber-100 text-amber-700';
                                $statusLabel = 'Sudah Dikumpulkan';
                            }
                        } else {
                            if ($isOverdue) {
                                $badgeColor = 'bg-rose-50 border-rose-100 text-rose-700';
                                $statusLabel = 'Batas Waktu Habis';
                            } else {
                                $badgeColor = 'bg-blue-50 border-blue-100 text-blue-700';
                                $statusLabel = 'Belum Dikerjakan';
                            }
                        }
                    @endphp
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:border-emerald-200 transition-all group">
                        <div class="space-y-4">
                            {{-- Header (Status & Creator) --}}
                            <div class="flex items-center justify-between">
                                <span class="px-3.5 py-1.5 rounded-xl border text-[11px] font-bold {{ $badgeColor }}">
                                    {{ $statusLabel }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    {{ $assignment->creator->name }}
                                </span>
                            </div>
 
                            {{-- Title --}}
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-tight">
                                    {{ $assignment->title }}
                                </h3>
                            </div>
 
                            {{-- Due Date Info --}}
                            <div class="flex items-start space-x-2 text-xs font-semibold text-gray-500">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-gray-400 uppercase tracking-widest text-[9px] font-bold">Batas Waktu</p>
                                    <p class="mt-0.5 text-gray-700 font-bold {{ $isOverdue && !$sub ? 'text-rose-500' : '' }}">
                                        {{ $assignment->due_date->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                            </div>
                        </div>
 
                        {{-- Action button --}}
                        <div class="mt-6 pt-4 border-t border-gray-50 flex flex-col gap-2">
                            @if(!$sub && !$isOverdue && $assignment->link)
                                <form action="{{ route('mahasiswa.assignments.auto-submit', $assignment) }}" method="POST" target="_blank" onsubmit="setTimeout(() => window.location.reload(), 1000)">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm group-hover:shadow-indigo-500/20">
                                        <span>Kerjakan Sekarang</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('mahasiswa.assignments.show', $assignment) }}" class="w-full flex items-center justify-center py-3 {{ ($sub || $isOverdue) ? 'bg-slate-50 hover:bg-emerald-600 text-gray-600' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/20' }} hover:text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                <span>{{ $sub ? 'Lihat Hasil Pengerjaan' : ($isOverdue ? 'Lihat Rincian Tugas' : 'Buka Rincian & Kumpulkan') }}</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
