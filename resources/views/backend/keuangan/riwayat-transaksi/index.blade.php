@extends('layouts.keuangan')

@section('title', 'Buku Kas & Riwayat Transaksi')

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
    <div class="bg-[var(--primary-50)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-[var(--primary-500)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-xs font-semibold text-[var(--primary-700)]">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-[var(--danger)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-xs font-semibold text-[var(--danger)]">{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[var(--danger)] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-xs font-bold text-[var(--danger)] mb-1">Perhatian:</p>
            <ul class="list-disc list-inside text-xs text-[var(--danger)] space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Header Area: Title & Action Buttons --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Buku kas & riwayat transaksi</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Audit trail seluruh arus kas masuk (kredit) dan kas keluar (debit) kampus MATLA University.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Tombol Catat Transaksi Kas --}}
            <button type="button" 
                    @click="showCreateModal = true"
                    class="h-10 inline-flex items-center gap-2 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Catat transaksi kas</span>
            </button>

            {{-- Ekspor Excel --}}
            <a href="{{ route('backend.keuangan.riwayat-transaksi.export.excel', request()->query()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--primary-500)] hover:bg-[var(--primary-50)] text-[var(--primary-700)] dark:text-[var(--primary-300)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor Excel</span>
            </a>

            {{-- Ekspor PDF --}}
            <a href="{{ route('backend.keuangan.riwayat-transaksi.export.pdf', request()->query()) }}" 
               target="_blank"
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan 3 Kartu Mini (Border 1px, Tanpa Bayangan) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1: Uang Masuk --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Uang masuk (kredit)</p>
                <p class="text-2xl font-bold font-display text-[var(--primary-600)] tabular-nums mt-1.5">
                    + Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2 tabular-nums">
                {{ number_format($countMasuk, 0, ',', '.') }} transaksi tercatat
            </p>
        </div>

        {{-- Card 2: Uang Keluar --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--danger)]">Uang keluar (debit)</p>
                <p class="text-2xl font-bold font-display text-[var(--danger)] tabular-nums mt-1.5">
                    - Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2 tabular-nums">
                {{ number_format($countKeluar, 0, ',', '.') }} transaksi operasional
            </p>
        </div>

        {{-- Card 3: Arus Kas Bersih --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Arus kas bersih</p>
                <p class="text-2xl font-bold font-display {{ $netCashflow >= 0 ? 'text-[var(--primary-600)]' : 'text-[var(--danger)]' }} tabular-nums mt-1.5">
                    Rp {{ number_format($netCashflow, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2">
                {{ $netCashflow >= 0 ? 'Surplus operasional' : 'Defisit operasional' }}
            </p>
        </div>
    </div>

    {{-- Filter Pencarian (Tinggi 40px, Radius 4px, Border Line) --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4">
        <form action="{{ route('backend.keuangan.riwayat-transaksi.index') }}" method="GET" class="space-y-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                {{-- Pencarian Cepat --}}
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Kode TRX, nama, NIM, keterangan..." 
                               class="w-full h-10 pl-9 pr-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)]">
                        <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Filter Jenis Arus Kas --}}
                <div class="sm:col-span-1 lg:col-span-2">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Arus kas</label>
                    <select name="jenis" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <option value="">Semua jenis</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Uang masuk</option>
                        <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Uang keluar</option>
                    </select>
                </div>

                {{-- Filter Kategori --}}
                <div class="sm:col-span-1 lg:col-span-3">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-[var(--text-heading)]">Kategori</label>
                        <button type="button" 
                                @click="showKategoriModal = true" 
                                class="text-[11px] font-semibold text-[var(--primary-600)] hover:underline cursor-pointer">
                            Kelola kategori
                        </button>
                    </div>
                    <select name="kategori" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <option value="">Semua kategori</option>
                        <option value="spp" {{ request('kategori') === 'spp' ? 'selected' : '' }}>Tagihan SPP</option>
                        <option value="non-spp" {{ request('kategori') === 'non-spp' ? 'selected' : '' }}>Non-SPP</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rentang Tanggal --}}
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Rentang tanggal</label>
                    <div class="flex items-center gap-1.5">
                        <input type="date" 
                               name="start_date" 
                               value="{{ request('start_date') }}" 
                               class="flex-1 h-10 px-2.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <span class="text-[var(--text-muted)] text-xs">-</span>
                        <input type="date" 
                               name="end_date" 
                               value="{{ request('end_date') }}" 
                               class="flex-1 h-10 px-2.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-[var(--border)] text-xs">
                <span class="text-[var(--text-muted)] tabular-nums">
                    Menampilkan {{ $transaksis->total() }} baris data transaksi
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'jenis', 'kategori', 'start_date', 'end_date', 'bulan', 'tahun']))
                    <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" 
                       class="h-9 px-3 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] font-semibold transition flex items-center">
                        Reset filter
                    </a>
                    @endif
                    <button type="submit" 
                            class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] font-semibold transition cursor-pointer">
                        Terapkan filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat Transaksi --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-5 pr-3 w-12 text-center">No</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[160px]">Kode & waktu</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[130px]">Arus kas</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[180px]">Subjek / mahasiswa</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[200px]">Deskripsi</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[110px]">Metode</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[130px] text-right">Nominal</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[110px]">Petugas</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($transaksis as $index => $tx)
                        @php
                            $isMasuk = $tx->isMasuk();
                            $rowNum = $transaksis->firstItem() + $index;
                        @endphp
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                            {{-- No --}}
                            <td class="py-3.5 pl-5 pr-3 text-center text-[var(--text-muted)] tabular-nums" data-label="No">
                                {{ $rowNum }}
                            </td>

                            {{-- Kode & Waktu --}}
                            <td class="py-3.5 px-4 tabular-nums" data-label="Kode & waktu">
                                <span class="font-bold text-[var(--text-heading)]">{{ $tx->kode_transaksi }}</span>
                                <span class="block text-[11px] text-[var(--text-muted)] mt-0.5">{{ $tx->formatted_tanggal }}</span>
                            </td>

                            {{-- Arus Kas --}}
                            <td class="py-3.5 px-4" data-label="Arus kas">
                                @if($isMasuk)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-[var(--danger)] border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--danger)] mr-1.5"></span>
                                        Keluar
                                    </span>
                                @endif
                                <span class="block text-[11px] font-medium text-[var(--text-muted)] mt-1 truncate max-w-[130px]" title="{{ $tx->kategori }}">
                                    {{ $tx->kategori }}
                                </span>
                            </td>

                            {{-- Subjek --}}
                            <td class="py-3.5 px-4" data-label="Subjek">
                                <span class="font-semibold text-[var(--text-heading)] block truncate max-w-[180px]">{{ $tx->subjek ?: '-' }}</span>
                                @if($tx->user && !empty($tx->user->nim))
                                    <span class="text-[11px] text-[var(--text-muted)] tabular-nums block mt-0.5">
                                        NIM: {{ $tx->user->nim }}
                                        @if(!empty($tx->user->education['program_studi']))
                                            • {{ $tx->user->education['program_studi'] }}
                                        @endif
                                    </span>
                                @endif
                            </td>

                            {{-- Deskripsi --}}
                            <td class="py-3.5 px-4" data-label="Deskripsi">
                                <p class="text-[var(--text-body)] line-clamp-2 max-w-[220px]" title="{{ $tx->deskripsi }}">
                                    {{ $tx->deskripsi ?: '-' }}
                                </p>
                                @if(!empty($tx->no_referensi))
                                    <span class="text-[10px] text-[var(--text-muted)] block mt-0.5 font-mono">
                                        Ref: {{ $tx->no_referensi }}
                                    </span>
                                @endif
                            </td>

                            {{-- Metode --}}
                            <td class="py-3.5 px-4" data-label="Metode">
                                <span class="text-xs">{{ $tx->metode_pembayaran ?: 'Transfer Bank' }}</span>
                                @if($tx->bukti_url)
                                    <a href="{{ $tx->bukti_url }}" target="_blank" class="block text-[11px] text-[var(--primary-600)] hover:underline mt-0.5">
                                        Bukti struk
                                    </a>
                                @endif
                            </td>

                            {{-- Nominal --}}
                            <td class="py-3.5 px-4 text-right tabular-nums font-bold text-xs {{ $isMasuk ? 'text-[var(--primary-600)]' : 'text-[var(--danger)]' }}" data-label="Nominal">
                                {{ $tx->formatted_nominal }}
                            </td>

                            {{-- Petugas --}}
                            <td class="py-3.5 px-4 text-[var(--text-muted)] truncate max-w-[110px]" data-label="Petugas">
                                {{ $tx->admin_nama ?: 'Admin Keuangan' }}
                            </td>

                            {{-- Aksi (40x40 Buttons) --}}
                            <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Detail --}}
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
                                            title="Lihat detail"
                                            aria-label="Lihat detail transaksi {{ $tx->kode_transaksi }}"
                                            class="w-8 h-8 rounded-[var(--radius-xs)] border border-[var(--border)] text-[var(--primary-600)] hover:bg-[var(--primary-50)] flex items-center justify-center transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Cetak --}}
                                    <a href="{{ route('backend.keuangan.riwayat-transaksi.kwitansi', $tx->id) }}" 
                                       target="_blank"
                                       title="Cetak kwitansi"
                                       aria-label="Cetak kwitansi transaksi {{ $tx->kode_transaksi }}"
                                       class="w-8 h-8 rounded-[var(--radius-xs)] border border-[var(--border)] text-[var(--text-body)] hover:bg-[var(--bg-page)] flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('backend.keuangan.riwayat-transaksi.destroy', $tx->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus transaksi [{{ $tx->kode_transaksi }}] ini dari buku kas?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus transaksi"
                                                aria-label="Hapus transaksi {{ $tx->kode_transaksi }}"
                                                class="w-8 h-8 rounded-[var(--radius-xs)] border border-[var(--border)] text-[var(--danger)] hover:bg-rose-50 flex items-center justify-center transition cursor-pointer">
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
                            <td colspan="9" class="py-12 text-center text-xs text-[var(--text-muted)]">
                                <p class="font-semibold text-[var(--text-heading)]">Belum ada riwayat transaksi.</p>
                                <p class="mt-1">Gunakan tombol "Catat transaksi kas" untuk mencatat pemasukan atau pengeluaran baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transaksis->hasPages())
        <div class="px-5 py-3.5 border-t border-[var(--border)] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[var(--text-muted)]">
            <span class="tabular-nums">
                Menampilkan {{ $transaksis->firstItem() ?: 0 }}–{{ $transaksis->lastItem() ?: 0 }} dari total {{ $transaksis->total() }} transaksi
            </span>
            <div>
                {{ $transaksis->links('partials.pagination-keuangan') }}
            </div>
        </div>
        @endif
    </div>

    {{-- MODAL "+ CATAT TRANSAKSI KAS" --}}
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showCreateModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-lg w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Catat transaksi kas</h3>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Pemasukan lain yayasan atau beban operasional kampus</p>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)] p-1 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('backend.keuangan.riwayat-transaksi.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden min-h-0 m-0">
                @csrf

                <div class="p-5 overflow-y-auto flex-1 space-y-4">
                    {{-- Arus Kas --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Jenis arus kas</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-2.5 rounded-[var(--radius-xs)] border cursor-pointer select-none"
                                   :class="jenisPilihan === 'masuk' ? 'border-[var(--primary-500)] bg-[var(--primary-50)] text-[var(--primary-700)]' : 'border-[var(--border)] text-[var(--text-body)]'">
                                <input type="radio" name="jenis_transaksi" value="masuk" x-model="jenisPilihan" class="text-[var(--primary-500)] focus:ring-[var(--primary-500)]">
                                <div>
                                    <span class="font-bold text-xs block">Uang masuk</span>
                                    <span class="text-[10px] text-[var(--text-muted)]">Penerimaan / kredit</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 p-2.5 rounded-[var(--radius-xs)] border cursor-pointer select-none"
                                   :class="jenisPilihan === 'keluar' ? 'border-[var(--danger)] bg-rose-50 text-[var(--danger)]' : 'border-[var(--border)] text-[var(--text-body)]'">
                                <input type="radio" name="jenis_transaksi" value="keluar" x-model="jenisPilihan" class="text-[var(--danger)] focus:ring-[var(--danger)]">
                                <div>
                                    <span class="font-bold text-xs block">Uang keluar</span>
                                    <span class="text-[10px] text-[var(--text-muted)]">Pengeluaran / debit</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Tanggal & Jam --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Tanggal transaksi *</label>
                            <input type="date" 
                                   name="tanggal" 
                                   value="{{ date('Y-m-d') }}" 
                                   required 
                                   class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Waktu</label>
                            <input type="time" 
                                   name="jam" 
                                   value="{{ date('H:i') }}" 
                                   class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                    </div>

                    {{-- Kategori & Metode --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Kategori transaksi *</label>
                            <select name="kategori" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="">-- Pilih kategori --</option>
                                <template x-for="item in filteredKategori" :key="item.id">
                                    <option :value="item.nama_kategori" x-text="item.nama_kategori"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Metode pembayaran *</label>
                            <select name="metode_pembayaran" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Kas / Tunai">Kas / Tunai</option>
                                <option value="Virtual Account / QRIS">Virtual Account / QRIS</option>
                                <option value="Cek / Giro">Cek / Giro</option>
                            </select>
                        </div>
                    </div>

                    {{-- Subjek --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">
                            <span x-text="jenisPilihan === 'masuk' ? 'Pihak pembayar / sumber dana' : 'Penerima dana / vendor / keperluan'"></span>
                            <span class="text-[var(--danger)]">*</span>
                        </label>
                        <input type="text" 
                               name="subjek" 
                               placeholder="Contoh: PT PLN Persero, Donatur Yayasan..." 
                               required 
                               class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>

                    {{-- Nominal & Referensi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nominal (Rp) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-xs text-[var(--text-muted)] pointer-events-none">Rp</span>
                                <input type="number" 
                                       name="nominal" 
                                       min="1" 
                                       step="any" 
                                       placeholder="0" 
                                       required 
                                       class="w-full h-10 pl-9 pr-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] font-bold tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">No. referensi</label>
                            <input type="text" 
                                   name="no_referensi" 
                                   placeholder="Nomor struk/nota" 
                                   class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Rincian keterangan</label>
                        <textarea name="deskripsi" 
                                  rows="2" 
                                  placeholder="Catatan rincian keperluan transaksi..." 
                                  class="w-full p-2.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] resize-none"></textarea>
                    </div>

                    {{-- Upload Bukti --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Upload bukti nota/struk</label>
                        <input type="file" 
                               name="bukti" 
                               accept="image/*,application/pdf" 
                               class="w-full text-xs text-[var(--text-muted)] file:mr-3 file:h-8 file:px-3 file:rounded-[var(--radius-xs)] file:border-0 file:text-xs file:font-semibold file:bg-[var(--primary-50)] file:text-[var(--primary-700)] hover:file:bg-[var(--primary-100)] cursor-pointer">
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex items-center justify-end gap-2 shrink-0">
                    <button type="button" 
                            @click="showCreateModal = false" 
                            class="h-9 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] font-semibold text-xs rounded-[var(--radius-xs)]">
                        Batal
                    </button>
                    <button type="submit" 
                            class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white font-semibold text-xs rounded-[var(--radius-xs)] transition cursor-pointer">
                        Simpan ke buku kas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL TRANSAKSI --}}
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showDetailModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-md w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <template x-if="selectedTx">
                <div class="flex flex-col flex-1 overflow-hidden min-h-0">
                    <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                        <div>
                            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Rincian bukti transaksi</h3>
                            <p class="text-[11px] text-[var(--text-muted)] font-mono mt-0.5" x-text="selectedTx.kode"></p>
                        </div>
                        <button type="button" @click="showDetailModal = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-5 overflow-y-auto flex-1 space-y-4 text-xs">
                        <div class="p-4 rounded-[var(--radius-xs)] text-center border"
                             :class="selectedTx.is_masuk ? 'bg-[var(--primary-50)] border-[var(--primary-200)]' : 'bg-rose-50 border-rose-200'">
                            <span class="text-[11px] font-semibold uppercase tracking-wider block"
                                  :class="selectedTx.is_masuk ? 'text-[var(--primary-700)]' : 'text-[var(--danger)]'"
                                  x-text="selectedTx.is_masuk ? 'Pemasukan kas' : 'Pengeluaran kas'"></span>
                            <p class="text-2xl font-bold font-display mt-1 tabular-nums"
                               :class="selectedTx.is_masuk ? 'text-[var(--primary-700)]' : 'text-[var(--danger)]'"
                               x-text="selectedTx.nominal_formatted"></p>
                        </div>

                        <div class="space-y-2.5">
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Waktu</span>
                                <span class="font-semibold text-[var(--text-heading)] tabular-nums" x-text="selectedTx.waktu"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Kategori</span>
                                <span class="font-semibold text-[var(--text-heading)]" x-text="selectedTx.kategori"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Pihak / subjek</span>
                                <span class="font-semibold text-[var(--text-heading)]" x-text="selectedTx.subjek"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Metode</span>
                                <span class="font-medium text-[var(--text-heading)]" x-text="selectedTx.metode"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">No. referensi</span>
                                <span class="font-mono text-[var(--text-heading)]" x-text="selectedTx.no_ref"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Petugas</span>
                                <span class="font-medium text-[var(--text-heading)]" x-text="selectedTx.admin"></span>
                            </div>
                            <div class="pt-1">
                                <span class="text-[var(--text-muted)] block mb-1">Keterangan:</span>
                                <p class="text-[var(--text-body)] bg-[var(--bg-page)] p-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] leading-relaxed" x-text="selectedTx.deskripsi"></p>
                            </div>
                            
                            <template x-if="selectedTx.bukti_url">
                                <div class="pt-1">
                                    <a :href="selectedTx.bukti_url" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-[var(--primary-600)] font-semibold hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Buka lampiran bukti struk</span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex items-center justify-between gap-2 shrink-0">
                        <button type="button" @click="showDetailModal = false" class="h-9 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)]">
                            Tutup
                        </button>
                        <a :href="selectedTx.cetak_url" target="_blank" class="h-9 px-4 inline-flex items-center gap-1.5 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak kwitansi</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- MODAL MASTER KELOLA KATEGORI TRANSAKSI --}}
    <div x-show="showKategoriModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showKategoriModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-xl w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Master kategori transaksi</h3>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Klasifikasi pos arus kas masuk dan kas keluar buku kas</p>
                </div>
                <button type="button" @click="showKategoriModal = false; cancelEditKategori();" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto flex-1 space-y-4">
                {{-- Form Tambah/Edit Kategori --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-4">
                    <div class="flex items-center justify-between mb-2.5">
                        <h4 class="text-xs font-bold text-[var(--text-heading)]">
                            <span x-text="editKategoriId ? 'Edit kategori transaksi' : 'Tambah kategori baru'"></span>
                        </h4>
                        <template x-if="editKategoriId">
                            <button type="button" @click="cancelEditKategori()" class="text-xs text-[var(--primary-600)] hover:underline font-semibold cursor-pointer">
                                Batal edit
                            </button>
                        </template>
                    </div>

                    <form :action="editKategoriId ? '{{ url('/backend/keuangan/riwayat-transaksi/master-kategori') }}/' + editKategoriId : '{{ route('backend.keuangan.riwayat-transaksi.kategori.store') }}'" method="POST">
                        @csrf
                        <template x-if="editKategoriId">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <div class="sm:col-span-6">
                                <label class="block text-[11px] font-semibold text-[var(--text-heading)] mb-1">Nama kategori *</label>
                                <input type="text" 
                                       name="nama_kategori" 
                                       x-model="editNama" 
                                       placeholder="Misal: Operasional, Gaji..." 
                                       required 
                                       class="w-full h-9 px-2.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold text-[var(--text-heading)] mb-1">Tipe arus kas *</label>
                                <select name="tipe" x-model="editTipe" required class="w-full h-9 px-2 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                    <option value="umum">Umum</option>
                                    <option value="masuk">Uang masuk</option>
                                    <option value="keluar">Uang keluar</option>
                                </select>
                            </div>

                            <div class="sm:col-span-3 flex items-end">
                                <button type="submit" 
                                        class="w-full h-9 px-3 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white font-semibold text-xs rounded-[var(--radius-xs)] transition cursor-pointer">
                                    <span x-text="editKategoriId ? 'Simpan' : 'Tambah'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Kategori --}}
                <div class="border border-[var(--border)] rounded-[var(--radius-xs)] overflow-hidden max-h-56 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 border-b border-[var(--border)] font-semibold text-[var(--text-body)] sticky top-0 z-10">
                            <tr>
                                <th class="py-2 px-3 w-10 text-center">No</th>
                                <th class="py-2 px-3">Nama kategori</th>
                                <th class="py-2 px-3 w-28">Tipe</th>
                                <th class="py-2 px-3 w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                            @forelse($masterKategoris as $idx => $mk)
                            <tr class="hover:bg-[var(--primary-50)]/40 transition">
                                <td class="py-2 px-3 text-center text-[var(--text-muted)] tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-semibold text-[var(--text-heading)]">{{ $mk->nama_kategori }}</td>
                                <td class="py-2 px-3">
                                    <span class="text-[11px]">{{ ucfirst($mk->tipe) }}</span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" 
                                                @click="startEditKategori({ id: {{ $mk->id }}, nama_kategori: '{{ addslashes($mk->nama_kategori) }}', tipe: '{{ $mk->tipe }}' })" 
                                                title="Edit kategori" 
                                                class="p-1 text-[var(--text-muted)] hover:text-[var(--primary-600)] rounded cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('backend.keuangan.riwayat-transaksi.kategori.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Hapus kategori \'{{ addslashes($mk->nama_kategori) }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus kategori" class="p-1 text-[var(--danger)] rounded cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-[var(--text-muted)]">Belum ada kategori terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex justify-end shrink-0">
                <button type="button" 
                        @click="showKategoriModal = false; cancelEditKategori();" 
                        class="h-8 px-4 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)]">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
