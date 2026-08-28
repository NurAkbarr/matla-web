@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Mahasiswa')

@section('content')
<div class="bg-slate-50 min-h-screen pb-20 pt-8">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            {{-- Header & Back Button --}}
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('backend.mahasiswa.pembayaran.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Pembayaran
                </a>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 mb-8 flex items-center gap-5">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-2" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Riwayat Transaksi</h1>
                    <p class="text-sm font-medium text-slate-500">Histori pembayaran yang pernah Anda lakukan untuk setiap tagihan.</p>
                </div>
            </div>

            {{-- Daftar Riwayat Berdasarkan Tagihan --}}
            @if($tagihans->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Riwayat</h3>
                    <p class="text-slate-500 text-sm">Anda belum melakukan transaksi pembayaran apa pun.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($tagihans as $tagihan)
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm" x-data="{ open: false }">
                            {{-- Accordion Header --}}
                            <button @click="open = !open" class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 hover:bg-slate-50 transition-colors text-left focus:outline-none">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $tagihan->nama_tagihan }}</h3>
                                    <div class="flex flex-wrap items-center gap-3 text-sm">
                                        <span class="text-slate-500 font-medium">Total: Rp {{ number_format($tagihan->nominal_total, 0, ',', '.') }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-slate-500 font-medium">Sisa: <span class="{{ $tagihan->sisa_tagihan == 0 ? 'text-emerald-600' : 'text-rose-500' }}">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</span></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($tagihan->status === 'lunas')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider">Lunas</span>
                                    @elseif($tagihan->pembayarans->where('status', 'pending')->count() > 0)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Verifikasi</span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider">Belum Lunas</span>
                                    @endif
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 transition-transform duration-300" :class="{ 'rotate-180': open }">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </button>

                            {{-- Accordion Body (List of Pembayarans) --}}
                            <div x-show="open" x-collapse style="display: none;">
                                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Rincian Transaksi ({{ $tagihan->pembayarans->count() }})</h4>
                                    
                                    <div class="space-y-3">
                                        @foreach($tagihan->pembayarans as $bayar)
                                            <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</span>
                                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">{{ $bayar->jenis_pembayaran }}</span>
                                                    </div>
                                                    <div class="text-xs text-slate-500 font-medium">
                                                        {{ $bayar->created_at->format('d M Y, H:i') }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-3">
                                                    {{-- Status --}}
                                                    @if($bayar->status === 'verified')
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            Diverifikasi
                                                        </span>
                                                        @if($bayar->kwitansi)
                                                            <a href="{{ route('backend.mahasiswa.pembayaran.kwitansi', $bayar->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-100 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                                Kwitansi
                                                            </a>
                                                        @endif
                                                    @elseif($bayar->status === 'rejected')
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1.5 rounded-lg border border-rose-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            Ditolak
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1.5 rounded-lg border border-amber-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Proses
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
