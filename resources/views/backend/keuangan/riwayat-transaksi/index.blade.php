@extends('layouts.keuangan')

@section('title', 'Buku Kas & Riwayat Transaksi - Sistem Keuangan')

@section('content')
<div class="space-y-6" x-data="{
    showCreateModal: false,
    showDetailModal: false,
    showKategoriModal: false,
    selectedTx: null,
    jenisPilihan: 'masuk',
    masterKategoris: {{ Js::from($masterKategoris) }},
    editKategoriId: null,
    editNama: '',
    editTipe: 'umum',
    get filteredKategori() {
        return this.masterKategoris.filter(k => k.tipe === this.jenisPilihan || k.tipe === 'umum');
    },
    openDetail(tx) {
        this.selectedTx = tx;
        this.showDetailModal = true;
    },
    startEditKategori(kat) {
        this.editKategoriId = kat.id;
        this.editNama = kat.nama_kategori;
        this.editTipe = kat.tipe;
    },
    cancelEditKategori() {
        this.editKategoriId = null;
        this.editNama = '';
        this.editTipe = 'umum';
    }
}">

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="text-sm text-rose-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-rose-700 font-bold mb-1">Perhatian:</p>
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Header Banner & Action Buttons --}}
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#00703C] flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Buku Kas & Riwayat Transaksi</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pusat audit trail seluruh arus kas masuk (kredit) dan kas keluar (debit) kampus MATLA University</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Tombol Catat Transaksi Kas --}}
            <button type="button" 
                    @click="showCreateModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00703C] hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-[0.98] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>+ Catat Transaksi Kas</span>
            </button>

            {{-- Export Excel --}}
            <a href="{{ route('backend.keuangan.riwayat-transaksi.export.excel', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 text-xs font-semibold rounded-xl transition shadow-xs">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                    <path d="M8.5 12.5 10 15l-1.5 2.5h1.5l.75-1.5.75 1.5H13L11.5 15l1.5-2.5h-1.5l-.75 1.5-.75-1.5z"/>
                </svg>
                <span>Export Excel</span>
            </a>

            {{-- Export PDF --}}
            <a href="{{ route('backend.keuangan.riwayat-transaksi.export.pdf', request()->query()) }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:border-rose-300 text-slate-700 hover:text-rose-700 text-xs font-semibold rounded-xl transition shadow-xs">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H8v-2h4v2zm4-4H8v-2h8v2zm0-4H8V7h8v2z"/>
                </svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- A. RINGKASAN MINI CARDS (SUMMARY BAR ATAS TABEL)                  --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1: Total Transaksi Masuk (Kredit) --}}
        <div class="bg-white border border-emerald-100 rounded-2xl p-5 shadow-xs flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Uang Masuk</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                        Pemasukan
                    </span>
                </div>
                <p class="text-2xl font-bold text-emerald-700 tabular-nums tracking-tight leading-tight">
                    + Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span class="font-semibold text-slate-700">{{ number_format($countMasuk, 0, ',', '.') }}</span> transaksi tercatat
                </p>
            </div>
        </div>

        {{-- Card 2: Total Transaksi Keluar (Debit) --}}
        <div class="bg-white border border-rose-100 rounded-2xl p-5 shadow-xs flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Uang Keluar</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                        Pengeluaran
                    </span>
                </div>
                <p class="text-2xl font-bold text-rose-600 tabular-nums tracking-tight leading-tight">
                    - Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span class="font-semibold text-slate-700">{{ number_format($countKeluar, 0, ',', '.') }}</span> transaksi operasional
                </p>
            </div>
        </div>

        {{-- Card 3: Arus Kas Bersih (Net Cashflow) --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl {{ $netCashflow >= 0 ? 'bg-[#00703C]/10 text-[#00703C] border border-[#00703C]/20' : 'bg-rose-50 text-rose-600 border border-rose-200' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 18h12l3-18H3zm3 4h12M9 14h6"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Arus Kas Bersih (Net)</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $netCashflow >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $netCashflow >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
                <p class="text-2xl font-bold {{ $netCashflow >= 0 ? 'text-[#00703C]' : 'text-rose-600' }} tabular-nums tracking-tight leading-tight">
                    Rp {{ number_format($netCashflow, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-500 mt-1">
                    Selisih kas masuk dikurangi kas keluar
                </p>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- B. FITUR PENCARIAN & FILTER LENGKAP                               --}}
    {{-- ================================================================= --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
        <form action="{{ route('backend.keuangan.riwayat-transaksi.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                {{-- Pencarian Cepat --}}
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Kode TRX, Nama, NIM, Ref, Keterangan..." 
                               class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#00703C] focus:bg-white focus:ring-2 focus:ring-[#00703C]/10 transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Filter Jenis Arus Kas --}}
                <div class="sm:col-span-1 lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Arus Kas</label>
                    <select name="jenis" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white focus:ring-2 focus:ring-[#00703C]/10 transition">
                        <option value="">Semua Jenis Arus Kas</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Uang Masuk</option>
                        <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Uang Keluar</option>
                    </select>
                </div>

                {{-- Filter Kategori --}}
                <div class="sm:col-span-1 lg:col-span-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kategori Transaksi</label>
                        <button type="button" 
                                @click="showKategoriModal = true" 
                                class="text-[10px] font-bold text-[#00703C] hover:text-emerald-700 hover:underline inline-flex items-center gap-1 cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Kelola Kategori</span>
                        </button>
                    </div>
                    <select name="kategori" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white focus:ring-2 focus:ring-[#00703C]/10 transition">
                        <option value="">Semua Kategori</option>
                        <option value="spp" {{ request('kategori') === 'spp' ? 'selected' : '' }}>Khusus Tagihan SPP</option>
                        <option value="non-spp" {{ request('kategori') === 'non-spp' ? 'selected' : '' }}>Tagihan Non-SPP (Lainnya)</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rentang Tanggal --}}
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Rentang Tanggal</label>
                    <div class="flex items-center gap-1.5 w-full min-w-0">
                        <input type="date" 
                               name="start_date" 
                               value="{{ request('start_date') }}" 
                               class="min-w-0 flex-1 w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition"
                               title="Dari Tanggal">
                        <span class="text-slate-400 text-xs shrink-0 font-bold">-</span>
                        <input type="date" 
                               name="end_date" 
                               value="{{ request('end_date') }}" 
                               class="min-w-0 flex-1 w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition"
                               title="Sampai Tanggal">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-500">
                    Menampilkan hasil: <strong class="text-slate-800">{{ $transaksis->total() }}</strong> total baris transaksi
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'jenis', 'kategori', 'start_date', 'end_date', 'bulan', 'tahun']))
                    <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" 
                       class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold transition">
                        Reset Filter
                    </a>
                    @endif
                    <button type="submit" 
                            class="px-5 py-2 bg-[#00703C] hover:bg-emerald-700 text-white rounded-xl font-bold shadow-xs transition cursor-pointer">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ================================================================= --}}
    {{-- C. TABEL RIWAYAT TRANSAKSI                                        --}}
    {{-- ================================================================= --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[170px]">Kode & Waktu Presisi</th>
                        <th class="py-3.5 px-4 min-w-[140px]">Jenis & Kategori</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Subjek / Pihak Terkait</th>
                        <th class="py-3.5 px-4 min-w-[220px]">Deskripsi / Rincian</th>
                        <th class="py-3.5 px-4 min-w-[120px]">Metode</th>
                        <th class="py-3.5 px-4 min-w-[150px] text-right">Nominal (Rp)</th>
                        <th class="py-3.5 px-4 min-w-[120px]">Petugas</th>
                        <th class="py-3.5 px-4 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($transaksis as $index => $tx)
                        @php
                            $isMasuk = $tx->isMasuk();
                            $rowNum = $transaksis->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- No --}}
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $rowNum }}
                            </td>

                            {{-- Kode & Waktu --}}
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 tracking-tight flex items-center gap-1.5">
                                    <span>{{ $tx->kode_transaksi }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $tx->formatted_tanggal }}</span>
                                </div>
                            </td>

                            {{-- Jenis & Kategori --}}
                            <td class="py-3.5 px-4">
                                <div class="mb-1">
                                    @if($isMasuk)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Uang Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Uang Keluar
                                        </span>
                                    @endif
                                </div>
                                <span class="font-semibold text-slate-700 block truncate max-w-[150px]" title="{{ $tx->kategori }}">
                                    {{ $tx->kategori }}
                                </span>
                            </td>

                            {{-- Subjek / Pihak Terkait --}}
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $tx->subjek ?: '-' }}
                                </div>
                                @if($tx->user && !empty($tx->user->nim))
                                    <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                        <span class="bg-slate-100 px-1.5 py-0.5 rounded font-mono font-bold text-slate-600">NIM: {{ $tx->user->nim }}</span>
                                        @if(!empty($tx->user->education['program_studi']))
                                            <span>• {{ $tx->user->education['program_studi'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Deskripsi / Rincian --}}
                            <td class="py-3.5 px-4">
                                <p class="text-slate-600 line-clamp-2 max-w-[260px]" title="{{ $tx->deskripsi }}">
                                    {{ $tx->deskripsi ?: '-' }}
                                </p>
                                @if(!empty($tx->no_referensi))
                                    <span class="inline-block mt-1 text-[10px] text-slate-400 font-mono">
                                        Ref: {{ $tx->no_referensi }}
                                    </span>
                                @endif
                            </td>

                            {{-- Metode & Bukti --}}
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $tx->metode_pembayaran ?: 'Transfer Bank' }}
                                </span>
                                @if($tx->bukti_url)
                                    <div class="mt-1">
                                        <a href="{{ $tx->bukti_url }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-1 text-[11px] text-emerald-600 hover:text-emerald-700 font-semibold hover:underline">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat Bukti</span>
                                        </a>
                                    </div>
                                @endif
                            </td>

                            {{-- Nominal --}}
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-bold tabular-nums text-sm {{ $isMasuk ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $tx->formatted_nominal }}
                                </span>
                            </td>

                            {{-- Petugas / Admin --}}
                            <td class="py-3.5 px-4 text-slate-600">
                                <span class="truncate block max-w-[110px]" title="{{ $tx->admin_nama ?: 'Admin Keuangan' }}">
                                    {{ $tx->admin_nama ?: 'Admin Keuangan' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    {{-- Tombol Detail Kwitansi --}}
                                    <button type="button" 
                                            @click="openDetail({{ json_encode([
                                                'id' => $tx->id,
                                                'kode' => $tx->kode_transaksi,
                                                'jenis' => $tx->jenis_transaksi,
                                                'kategori' => $tx->kategori,
                                                'nominal' => number_format($tx->nominal, 0, ',', '.'),
                                                'nominal_formatted' => $tx->formatted_nominal,
                                                'waktu' => $tx->formatted_tanggal,
                                                'metode' => $tx->metode_pembayaran ?: 'Transfer Bank',
                                                'subjek' => $tx->subjek ?: '-',
                                                'deskripsi' => $tx->deskripsi ?: '-',
                                                'no_ref' => $tx->no_referensi ?: '-',
                                                'admin' => $tx->admin_nama ?: 'Admin Keuangan',
                                                'bukti_url' => $tx->bukti_url,
                                                'cetak_url' => route('backend.keuangan.riwayat-transaksi.kwitansi', $tx->id),
                                                'is_masuk' => $isMasuk
                                            ]) }})"
                                            title="Lihat Detail & Bukti Kas"
                                            class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 flex items-center justify-center transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Cetak Kwitansi --}}
                                    <a href="{{ route('backend.keuangan.riwayat-transaksi.kwitansi', $tx->id) }}" 
                                       target="_blank"
                                       title="Cetak Bukti Kwitansi Kas"
                                       class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </a>

                                    {{-- Hapus Transaksi --}}
                                    <form action="{{ route('backend.keuangan.riwayat-transaksi.destroy', $tx->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi [{{ $tx->kode_transaksi }}] ini dari Buku Kas?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Transaksi Kas"
                                                class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <span class="font-medium text-slate-600 block">Belum ada riwayat transaksi yang cocok dengan filter.</span>
                                <span class="text-xs text-slate-400">Silakan gunakan tombol "+ Catat Transaksi Kas" untuk menambahkan transaksi pemasukan atau pengeluaran baru.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transaksis->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Menampilkan <strong>{{ $transaksis->firstItem() ?: 0 }}</strong> sampai <strong>{{ $transaksis->lastItem() ?: 0 }}</strong> dari total <strong>{{ $transaksis->total() }}</strong> transaksi
            </span>
            <div>
                {{ $transaksis->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- ================================================================= --}}
    {{-- D. MODAL "+ CATAT TRANSAKSI KAS (MASUK / KELUAR)"                 --}}
    {{-- ================================================================= --}}
    <div x-show="showCreateModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-slate-900/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showCreateModal = false" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
            
            {{-- Modal Header (Fixed at top) --}}
            <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#00703C] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Catat Transaksi Kas Internal</h3>
                        <p class="text-xs text-slate-500">Pemasukan lain yayasan atau pengeluaran operasional kampus</p>
                    </div>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('backend.keuangan.riwayat-transaksi.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden min-h-0 m-0">
                @csrf

                {{-- Scrollable Form Body with max-h and internal scrolling --}}
                <div class="p-6 overflow-y-auto flex-1 space-y-4">
                    {{-- Pilihan Jenis Arus Kas (Uang Masuk / Uang Keluar) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jenis Arus Kas</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                   :class="jenisPilihan === 'masuk' ? 'border-emerald-500 bg-emerald-50/70 text-emerald-800 ring-2 ring-emerald-500/10' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                <input type="radio" name="jenis_transaksi" value="masuk" x-model="jenisPilihan" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="font-bold text-xs block">Uang Masuk</span>
                                    <span class="text-[10px] text-slate-500">Pemasukan / Kredit</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition select-none"
                                   :class="jenisPilihan === 'keluar' ? 'border-rose-500 bg-rose-50/70 text-rose-800 ring-2 ring-rose-500/10' : 'border-slate-200 hover:border-slate-300 text-slate-700'">
                                <input type="radio" name="jenis_transaksi" value="keluar" x-model="jenisPilihan" class="text-rose-600 focus:ring-rose-500">
                                <div>
                                    <span class="font-bold text-xs block">Uang Keluar</span>
                                    <span class="text-[10px] text-slate-500">Pengeluaran / Debit</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Tanggal & Jam --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                            <input type="date" 
                                   name="tanggal" 
                                   value="{{ date('Y-m-d') }}" 
                                   required 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Waktu (Jam:Menit)</label>
                            <input type="time" 
                                   name="jam" 
                                   value="{{ date('H:i') }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Kategori & Metode --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-slate-600">Kategori Transaksi <span class="text-rose-500">*</span></label>
                                <button type="button" 
                                        @click="showKategoriModal = true" 
                                        class="text-xs font-bold text-[#00703C] hover:text-emerald-700 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span>Kelola Kategori</span>
                                </button>
                            </div>
                            <select name="kategori" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                                <option value="">-- Pilih Kategori --</option>
                                <template x-for="item in filteredKategori" :key="item.id">
                                    <option :value="item.nama_kategori" x-text="item.nama_kategori"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                            <select name="metode_pembayaran" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Kas / Tunai">Kas / Tunai</option>
                                <option value="Virtual Account / QRIS">Virtual Account / QRIS</option>
                                <option value="Cek / Giro">Cek / Giro</option>
                            </select>
                        </div>
                    </div>

                    {{-- Subjek / Pihak Terkait --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            <span x-text="jenisPilihan === 'masuk' ? 'Sumber Dana / Pihak Pembayar' : 'Penerima Dana / Vendor / Keperluan'"></span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="subjek" 
                               placeholder="Contoh: PT PLN Persero, Donatur Yayasan, Staf Administrasi..." 
                               required 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                    </div>

                    {{-- Nominal & Nomor Referensi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" 
                                       name="nominal" 
                                       min="1" 
                                       step="any" 
                                       placeholder="0" 
                                       required 
                                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">No. Referensi / No. Nota</label>
                            <input type="text" 
                                   name="no_referensi" 
                                   placeholder="Opsional — No. Invoice/Struk" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Keterangan / Deskripsi --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Keterangan / Rincian</label>
                        <textarea name="deskripsi" 
                                  rows="2" 
                                  placeholder="Catatan rincian keperluan transaksi..." 
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] focus:bg-white transition resize-none"></textarea>
                    </div>

                    {{-- Upload Bukti / Nota --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Upload Bukti Struk / Nota (Opsional)</label>
                        <input type="file" 
                               name="bukti" 
                               accept="image/*,application/pdf" 
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-[#00703C] hover:file:bg-emerald-100 transition cursor-pointer">
                        <span class="text-[10px] text-slate-400 block mt-1">Format gambar (JPG, PNG) atau PDF, maksimal 5MB.</span>
                    </div>
                </div>

                {{-- Fixed Bottom Modal Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" 
                            @click="showCreateModal = false" 
                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-[#00703C] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition active:scale-[0.98] cursor-pointer">
                        Simpan ke Buku Kas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- E. MODAL DETAIL TRANSAKSI & BUKTI KAS                             --}}
    {{-- ================================================================= --}}
    <div x-show="showDetailModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-slate-900/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showDetailModal = false" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
            
            <template x-if="selectedTx">
                <div class="flex flex-col flex-1 overflow-hidden min-h-0">
                    {{-- Modal Header --}}
                    <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold"
                                 :class="selectedTx.is_masuk ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Rincian Bukti Transaksi Kas</h3>
                                <p class="text-xs text-slate-500 font-mono" x-text="selectedTx.kode"></p>
                            </div>
                        </div>
                        <button type="button" @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Scrollable Detail Content --}}
                    <div class="p-6 overflow-y-auto flex-1 space-y-5">
                        {{-- Nominal Banner --}}
                        <div class="p-4 rounded-2xl text-center border"
                             :class="selectedTx.is_masuk ? 'bg-emerald-50/60 border-emerald-200' : 'bg-rose-50/60 border-rose-200'">
                            <span class="text-[11px] font-bold uppercase tracking-wider block"
                                  :class="selectedTx.is_masuk ? 'text-emerald-700' : 'text-rose-700'"
                                  x-text="selectedTx.is_masuk ? 'TOTAL UANG MASUK' : 'TOTAL UANG KELUAR'"></span>
                            <p class="text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight"
                               :class="selectedTx.is_masuk ? 'text-emerald-700' : 'text-rose-600'"
                               x-text="selectedTx.nominal_formatted"></p>
                        </div>

                        {{-- Data Rows --}}
                        <div class="space-y-3 text-xs">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">Waktu Presisi</span>
                                <span class="text-slate-800 font-bold text-right" x-text="selectedTx.waktu"></span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">Kategori Transaksi</span>
                                <span class="text-slate-800 font-bold text-right" x-text="selectedTx.kategori"></span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">Pihak / Subjek</span>
                                <span class="text-slate-800 font-bold text-right" x-text="selectedTx.subjek"></span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">Metode Pembayaran</span>
                                <span class="text-slate-800 font-bold text-right" x-text="selectedTx.metode"></span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">No. Referensi / Kwitansi</span>
                                <span class="text-slate-800 font-mono font-semibold text-right" x-text="selectedTx.no_ref"></span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 font-medium">Petugas Pencatat</span>
                                <span class="text-slate-800 font-semibold text-right" x-text="selectedTx.admin"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-100">
                                <span class="text-slate-500 font-medium block mb-1">Rincian Keterangan:</span>
                                <p class="text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed" x-text="selectedTx.deskripsi"></p>
                            </div>
                            
                            <template x-if="selectedTx.bukti_url">
                                <div class="pt-2">
                                    <span class="text-slate-500 font-medium block mb-1.5">Lampiran Bukti Nota:</span>
                                    <a :href="selectedTx.bukti_url" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-semibold hover:bg-emerald-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Buka Lampiran Bukti File</span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Fixed Footer Actions --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3 flex-shrink-0">
                        <button type="button" 
                                @click="showDetailModal = false" 
                                class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl transition cursor-pointer">
                            Tutup
                        </button>
                        <a :href="selectedTx.cetak_url" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00703C] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>Cetak Bukti Kwitansi (PDF/Print)</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- F. MODAL MASTER KELOLA KATEGORI TRANSAKSI                         --}}
    {{-- ================================================================= --}}
    <div x-show="showKategoriModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-slate-900/60 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showKategoriModal = false" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#00703C] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Master Kategori Transaksi</h3>
                        <p class="text-xs text-slate-500">Kelola klasifikasi pos kas masuk dan kas keluar buku kas</p>
                    </div>
                </div>
                <button type="button" @click="showKategoriModal = false; cancelEditKategori();" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                
                {{-- Form Tambah Kategori Baru (atau Edit) --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4.5">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#00703C]"></span>
                            <span x-text="editKategoriId ? 'Edit Kategori Transaksi' : 'Tambah Kategori Baru'"></span>
                        </h4>
                        <template x-if="editKategoriId">
                            <button type="button" @click="cancelEditKategori()" class="text-xs text-slate-500 hover:text-slate-700 underline font-semibold cursor-pointer">
                                Batal Edit
                            </button>
                        </template>
                    </div>

                    {{-- Dynamic Form: POST for create, PUT for update --}}
                    <form :action="editKategoriId ? '{{ url('/backend/keuangan/riwayat-transaksi/master-kategori') }}/' + editKategoriId : '{{ route('backend.keuangan.riwayat-transaksi.kategori.store') }}'" method="POST">
                        @csrf
                        <template x-if="editKategoriId">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <div class="sm:col-span-6">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                                <input type="text" 
                                       name="nama_kategori" 
                                       x-model="editNama" 
                                       placeholder="Misal: Operasional, Gaji, Yayasan..." 
                                       required 
                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] transition">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe Arus Kas <span class="text-rose-500">*</span></label>
                                <select name="tipe" x-model="editTipe" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#00703C] transition">
                                    <option value="umum">Umum (Masuk/Keluar)</option>
                                    <option value="masuk">Uang Masuk Saja</option>
                                    <option value="keluar">Uang Keluar Saja</option>
                                </select>
                            </div>

                            <div class="sm:col-span-3 flex items-end">
                                <button type="submit" 
                                        class="w-full py-2.5 px-4 bg-[#00703C] hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition active:scale-[0.98] cursor-pointer">
                                    <span x-text="editKategoriId ? 'Simpan Edit' : '+ Tambahkan'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- List Tabel Master Kategori --}}
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Daftar Kategori Terdaftar (<span x-text="masterKategoris.length"></span>)
                        </h4>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[11px] font-bold uppercase">
                                <tr>
                                    <th class="py-2.5 px-3.5 w-12 text-center">No</th>
                                    <th class="py-2.5 px-3.5">Nama Kategori</th>
                                    <th class="py-2.5 px-3.5 w-36">Tipe Arus Kas</th>
                                    <th class="py-2.5 px-3.5 w-24 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($masterKategoris as $idx => $mk)
                                <tr class="hover:bg-slate-50/70 transition-colors" :class="editKategoriId === {{ $mk->id }} ? 'bg-amber-50/50' : ''">
                                    <td class="py-3 px-3.5 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-3.5 font-bold text-slate-800">
                                        {{ $mk->nama_kategori }}
                                    </td>
                                    <td class="py-3 px-3.5">
                                        @if($mk->tipe === 'masuk')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Uang Masuk
                                            </span>
                                        @elseif($mk->tipe === 'keluar')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Uang Keluar
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                                Umum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            {{-- Tombol Edit --}}
                                            <button type="button" 
                                                    @click="startEditKategori({ id: {{ $mk->id }}, nama_kategori: '{{ addslashes($mk->nama_kategori) }}', tipe: '{{ $mk->tipe }}' })" 
                                                    title="Edit Kategori" 
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 transition cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('backend.keuangan.riwayat-transaksi.kategori.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($mk->nama_kategori) }}\'?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        title="Hapus Kategori" 
                                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">
                                        Belum ada kategori transaksi yang terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 flex-shrink-0">
                <button type="button" 
                        @click="showKategoriModal = false; cancelEditKategori();" 
                        class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl transition cursor-pointer">
                    Selesai / Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
