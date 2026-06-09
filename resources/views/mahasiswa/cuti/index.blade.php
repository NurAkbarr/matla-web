@extends('layouts.app')
 
@section('title', 'Pengajuan Cuti - Matla Islamic University')
 
@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body { font-family: 'Montserrat', sans-serif !important; }
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
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-start space-x-3">
                <svg class="w-6 h-6 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="font-medium text-sm pt-0.5">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-start space-x-3">
                <svg class="w-6 h-6 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="font-medium text-sm pt-0.5">{{ session('error') }}</div>
            </div>
        @endif
 
        {{-- Header --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Pengajuan Cuti</h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Daftar riwayat pengajuan cuti akademik Anda.</p>
            </div>
            
            <div class="flex-shrink-0">
                @php
                    $hasPending = $cutiRequests->where('status', 'pending')->count() > 0;
                @endphp
                
                @if(!$hasPending)
                    <a href="{{ route('backend.mahasiswa.cuti.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-emerald-600/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Pengajuan Baru
                    </a>
                @else
                    <span class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-500 font-bold rounded-xl text-sm cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Menunggu Proses
                    </span>
                @endif
            </div>
        </div>
 
        @if($cutiRequests->isEmpty())
            <div class="bg-white rounded-[2rem] p-16 shadow-sm border border-gray-100 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-3xl flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Belum Ada Pengajuan</h3>
                <p class="text-gray-400 text-sm max-w-sm">Anda belum pernah membuat pengajuan cuti akademik.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cutiRequests as $cuti)
                    @php
                        if ($cuti->status === 'approved') {
                            $badgeColor = 'bg-emerald-50 border-emerald-100 text-emerald-700';
                            $statusLabel = 'Disetujui';
                        } elseif ($cuti->status === 'rejected') {
                            $badgeColor = 'bg-rose-50 border-rose-100 text-rose-700';
                            $statusLabel = 'Ditolak';
                        } else {
                            $badgeColor = 'bg-amber-50 border-amber-100 text-amber-700';
                            $statusLabel = 'Menunggu Persetujuan';
                        }
                    @endphp
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all group">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="px-3.5 py-1.5 rounded-xl border text-[11px] font-bold {{ $badgeColor }}">
                                    {{ $statusLabel }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    {{ $cuti->created_at->format('d M Y') }}
                                </span>
                            </div>
 
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Semester Diajukan</p>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">
                                    Semester {{ $cuti->semester_diajukan }}
                                </h3>
                            </div>
 
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Alasan Cuti</p>
                                <p class="text-sm font-medium text-gray-700 line-clamp-3">
                                    {{ $cuti->alasan }}
                                </p>
                            </div>

                            @if($cuti->status === 'rejected' && $cuti->admin_notes)
                                <div class="p-3 bg-rose-50 rounded-xl mt-4">
                                    <p class="text-xs text-rose-700 font-bold mb-1 uppercase tracking-wider">Catatan Admin</p>
                                    <p class="text-xs text-rose-600 font-medium">{{ $cuti->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
