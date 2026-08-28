@extends('layouts.app')

@section('title', 'Tagihan Kuliah - Mahasiswa')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20 pt-8">
    <div class="container mx-auto px-4 lg:px-12 max-w-4xl">
        {{-- Breadcrumb / Back --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('backend.mahasiswa.pembayaran.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Menu Pembayaran
            </a>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Tagihan Kuliah</h1>
        </div>

        @forelse($tagihans as $tagihan)
        <div class="bg-white rounded-xl border border-gray-200 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] mb-6 overflow-hidden">
            <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-1" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">{{ $tagihan->nama_tagihan }}</h3>
                    <p class="text-slate-900">Total Tagihan: <span class="font-bold text-slate-900">Rp {{ number_format($tagihan->nominal_total, 0, ',', '.') }}</span></p>
                    @if($tagihan->keterangan)
                    <p class="text-sm text-gray-500 mt-1">{{ $tagihan->keterangan }}</p>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2">
                    <p class="text-slate-900 font-bold text-lg">Sisa: Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                    @if($tagihan->status == 'lunas')
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-400 text-emerald-500 rounded-full text-xs font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        LUNAS
                    </div>
                    @else
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-amber-400 text-amber-500 rounded-full text-xs font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        BELUM LUNAS
                    </div>
                    @endif
                    @if($tagihan->jenis_tagihan == 'cicilan')
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-400 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bisa Dicicil
                    </div>
                    @else
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-400 text-rose-600 rounded-full text-xs font-bold uppercase tracking-wider mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Wajib Lunas
                    </div>
                    @endif
                </div>
            </div>

            @if($tagihan->status != 'lunas' && $tagihan->pembayarans->isNotEmpty())
            <div class="border-t border-gray-100 p-6 md:p-8 bg-slate-50/50">
                <div class="flex items-center gap-2 mb-4 text-slate-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h4 class="text-xs font-bold uppercase tracking-widest">Riwayat Pembayaran/Cicilan:</h4>
                </div>
                <div class="space-y-3">
                    @foreach($tagihan->pembayarans as $index => $bayar)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white rounded-xl border border-gray-100 shadow-sm gap-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Pembayaran {{ $index + 1 }}</p>
                                <p class="text-xs text-slate-500">{{ $bayar->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                <span class="text-sm font-bold text-slate-900">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</span>
                                @if($bayar->status == 'verified')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold uppercase tracking-wide">Sukses</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md text-[10px] font-bold uppercase tracking-wide">Diproses</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="border-t border-gray-100 p-6 md:p-8">
                <div class="flex items-center gap-2 mb-4 text-slate-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <h4 class="text-xs font-bold uppercase tracking-widest">INFO JATUH TEMPO:</h4>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 text-slate-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            @if($tagihan->jatuh_tempo)
                                <p class="font-bold text-slate-900 text-sm">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') }}</p>
                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">Batas akhir pembayaran tagihan ini.</p>
                            @else
                                <p class="font-bold text-slate-900 text-sm">Tidak ada batas waktu</p>
                            @endif
                        </div>
                    </div>
                    @if($tagihan->status != 'lunas')
                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-2 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                        <a href="{{ route('backend.mahasiswa.pembayaran.konfirmasi', $tagihan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold shadow-md hover:bg-slate-900 transition-colors">
                            Konfirmasi Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] p-12 flex flex-col items-center justify-center text-center mb-6">
            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Hore! Tidak Ada Tagihan</h3>
            <p class="text-gray-500">Anda tidak memiliki tagihan kuliah saat ini. Seluruh administrasi telah lunas.</p>
        </div>
        @endforelse

    </div>
</div>
@endsection
