@extends('layouts.keuangan')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Laporan Keuangan</h3>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-white border border-slate-200 rounded-3xl p-12 flex flex-col items-center justify-center text-center min-h-[60vh] shadow-sm relative overflow-hidden">
        
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-emerald-300 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-blue-200 blur-3xl"></div>
        </div>

        <!-- Illustration / Icon -->
        <div class="relative z-10 mb-8 bg-emerald-50 text-[#00703C] w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-xl shadow-emerald-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
        </div>

        <!-- Text Content -->
        <h2 class="relative z-10 text-3xl font-black text-slate-800 tracking-tight mb-4">Coming Soon!</h2>
        <p class="relative z-10 text-slate-500 max-w-lg mx-auto leading-relaxed mb-8">
            Fitur laporan keuangan sedang dalam tahap pengembangan dan akan segera hadir.
        </p>

        <!-- Status Badge -->
        <div class="relative z-10 inline-flex items-center gap-2 px-4 py-2 bg-slate-100 rounded-full border border-slate-200">
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            <span class="text-xs font-bold text-slate-600 tracking-wider uppercase">Coming Soon</span>
        </div>
    </div>
</div>
@endsection
