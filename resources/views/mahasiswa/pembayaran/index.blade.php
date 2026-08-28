@extends('layouts.app')

@section('title', 'Pembayaran - Mahasiswa')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20 pt-8">
    <div class="container mx-auto px-4 lg:px-12">
        {{-- Breadcrumb / Back --}}
        <div class="max-w-5xl mx-auto">
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('backend.mahasiswa.dashboard') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight mb-2" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Pembayaran</h1>
                <p class="text-sm font-medium text-gray-500">Kelola administrasi keuangan dan pembayaran Anda di sini.</p>
            </div>

            <!-- Grid Menu Pembayaran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        

        <!-- 2. Cek Tagihan -->
        <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-10px_rgba(0,0,0,0.1)] transition-all duration-300 flex flex-col group">
            <div class="flex items-start gap-6 mb-6">
                <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="pt-1">
                    <h3 class="text-xl font-bold text-slate-800 mb-2" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Cek Tagihan</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Lihat dan cek rincian tagihan yang belum dibayar.</p>
                </div>
            </div>
            <div class="mt-auto border-t border-gray-100 pt-5">
                <a href="{{ route('backend.mahasiswa.pembayaran.tagihan') }}" class="flex items-center justify-between text-emerald-500 hover:text-emerald-600 font-bold text-sm">
                    <span>Lihat Tagihan</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- 3. Riwayat Transaksi -->
        <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-10px_rgba(0,0,0,0.1)] transition-all duration-300 flex flex-col group">
            <div class="flex items-start gap-6 mb-6">
                <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="pt-1">
                    <h3 class="text-xl font-bold text-slate-800 mb-2" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Riwayat Transaksi</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Lihat riwayat pembayaran yang sudah dilakukan.</p>
                </div>
            </div>
            <div class="mt-auto border-t border-gray-100 pt-5">
                <a href="{{ route('backend.mahasiswa.pembayaran.riwayat') }}" class="flex items-center justify-between text-emerald-500 hover:text-emerald-600 font-bold text-sm">
                    <span>Lihat Riwayat</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>


            </div>
        </div>
    </div>
</div>
@endsection
