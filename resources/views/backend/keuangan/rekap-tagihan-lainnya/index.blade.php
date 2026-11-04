@extends('layouts.keuangan')

@section('title', 'Rekap Tagihan Lainnya')

@section('content')
<style>
.tagihan-pagination nav > div:last-child > div:first-child {
    display: none;
}
.tagihan-pagination nav {
    box-shadow: none !important;
}
.tagihan-pagination nav span[aria-current="page"] span {
    background-color: var(--primary-500) !important;
    border-color: var(--primary-500) !important;
    color: #ffffff !important;
    font-weight: 700;
}
.tagihan-pagination nav a, 
.tagihan-pagination nav span {
    border-radius: var(--radius-xs);
    font-size: 11px;
}
</style>

<div class="space-y-6">

    {{-- Header Area: Title & Export Actions --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)] dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                    Non-SPP
                </span>
                <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Rekap tagihan lainnya</h1>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                Pantau status pembayaran mahasiswa untuk tagihan insidentil (Biaya Wisuda, Bimbingan Skripsi, Pengindukan, Jas Almamater, dll.) terpisah dari matriks SPP.
            </p>
        </div>
        <div class="flex items-center gap-2.5 w-full sm:w-auto flex-wrap sm:flex-nowrap">
            {{-- Tombol Export Excel --}}
            <a href="{{ route('backend.keuangan.rekap-tagihan-lainnya.export.excel', request()->query()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Ekspor Excel</span>
            </a>

            {{-- Tombol Export PDF --}}
            <a href="{{ route('backend.keuangan.rekap-tagihan-lainnya.export.pdf', request()->query()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] hover:text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer shadow-2xs">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Ekspor PDF</span>
            </a>

            {{-- Tombol Buat Tagihan --}}
            <a href="{{ route('backend.keuangan.tagihan.index') }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--primary-500)] text-white text-xs font-semibold rounded-[var(--radius-xs)] hover:bg-[var(--primary-600)] transition cursor-pointer shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Kelola Tagihan</span>
            </a>
        </div>
    </div>

    {{-- Summary KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. Total Tagihan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-[var(--radius-xs)] bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-900 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wider">Total Tagihan</p>
                <p class="text-base font-bold font-display text-[var(--text-heading)] tabular-nums truncate">
                    Rp {{ number_format($totalNominal, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $totalTagihan }} tagihan terdata</p>
            </div>
        </div>

        {{-- 2. Total Terkumpul (Dibayar) --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-[var(--radius-xs)] bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wider">Total Terkumpul</p>
                <p class="text-base font-bold font-display text-emerald-600 dark:text-emerald-400 tabular-nums truncate">
                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $countLunas }} tagihan lunas</p>
            </div>
        </div>

        {{-- 3. Sisa Kekurangan (Tunggakan) --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-[var(--radius-xs)] bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wider">Sisa Tunggakan</p>
                <p class="text-base font-bold font-display text-rose-600 dark:text-rose-400 tabular-nums truncate">
                    Rp {{ number_format($totalSisa, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $countBelumLunas }} belum lunas, {{ $countSebagian }} cicilan</p>
            </div>
        </div>

        {{-- 4. Persentase Kelunasan --}}
        @php
            $persenLunas = $totalNominal > 0 ? round(($totalDibayar / $totalNominal) * 100, 1) : 0;
        @endphp
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex items-center gap-3.5 shadow-2xs">
            <div class="w-11 h-11 rounded-[var(--radius-xs)] bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200 dark:border-purple-900 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wider">Rasio Pelunasan</p>
                <p class="text-base font-bold font-display text-[var(--text-heading)] tabular-nums">
                    {{ $persenLunas }}%
                </p>
                <div class="w-full bg-[var(--border)] rounded-full h-1.5 mt-1 overflow-hidden">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $persenLunas) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 shadow-2xs">
        <form method="GET" action="{{ route('backend.keuangan.rekap-tagihan-lainnya.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            
            {{-- Filter Jenis Tagihan --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)] mb-1">Jenis Tagihan</label>
                <select name="jenis" class="w-full h-9 px-3 text-xs bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    <option value="semua">Semua Jenis Tagihan</option>
                    @foreach($daftarJenisTagihan as $j)
                        <option value="{{ $j }}" {{ request('jenis') === $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                    @if(empty($daftarJenisTagihan))
                        <option value="Wisuda" {{ request('jenis') === 'Wisuda' ? 'selected' : '' }}>Wisuda</option>
                        <option value="Skripsi" {{ request('jenis') === 'Skripsi' ? 'selected' : '' }}>Skripsi / Bimbingan</option>
                        <option value="Pengindukan" {{ request('jenis') === 'Pengindukan' ? 'selected' : '' }}>Pengindukan</option>
                    @endif
                </select>
            </div>

            {{-- Filter Program Studi --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)] mb-1">Program Studi</label>
                <select name="prodi" class="w-full h-9 px-3 text-xs bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    <option value="semua">Semua Program Studi</option>
                    @foreach($daftarProdi as $p)
                        <option value="{{ $p }}" {{ request('prodi') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)] mb-1">Status Pembayaran</label>
                <select name="status" class="w-full h-9 px-3 text-xs bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    <option value="semua">Semua Status</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="sebagian" {{ in_array(request('status'), ['sebagian', 'cicilan']) ? 'selected' : '' }}>Cicilan Sebagian</option>
                    <option value="belum_lunas" {{ request('status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>

            {{-- Search Bar --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)] mb-1">Cari Mahasiswa</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nama, NIM, atau Tagihan..." 
                           class="w-full h-9 pl-8 pr-3 text-xs bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    <svg class="w-3.5 h-3.5 text-[var(--text-muted)] absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 h-9 px-4 bg-[var(--primary-500)] text-white text-xs font-semibold rounded-[var(--radius-xs)] hover:bg-[var(--primary-600)] transition cursor-pointer shadow-2xs flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['jenis', 'prodi', 'status', 'search']))
                <a href="{{ route('backend.keuangan.rekap-tagihan-lainnya.index') }}" class="h-9 px-3 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-muted)] hover:text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer flex items-center justify-center" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden shadow-2xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-4 pr-2 w-10 text-center">No</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[200px]">Mahasiswa</th>
                        <th scope="col" class="py-3.5 px-3 min-w-[140px]">Program Studi</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[180px]">Item Tagihan</th>
                        <th scope="col" class="py-3.5 px-4 text-right min-w-[120px]">Total Tagihan</th>
                        <th scope="col" class="py-3.5 px-4 text-right min-w-[120px]">Dibayar</th>
                        <th scope="col" class="py-3.5 px-4 text-right min-w-[120px]">Sisa Kekurangan</th>
                        <th scope="col" class="py-3.5 px-3 text-center min-w-[120px]">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($tagihans as $index => $t)
                        @php
                            $nomTotal = (float) $t->nominal_total;
                            $sisa = (float) $t->sisa_tagihan;
                            $dibayar = max(0, $nomTotal - $sisa);

                            $isLunas = ($sisa <= 0 || strtolower($t->status) === 'lunas');
                            $isSebagian = (!$isLunas && $dibayar > 0);
                            $isBelumLunas = (!$isLunas && $dibayar <= 0);

                            $prodi = $t->user->education['program_studi'] ?? '-';
                            $angkatan = $t->user->angkatan ?? '-';

                            $lastVerifiedPay = $t->pembayarans->where('status', 'verified')->last();
                        @endphp
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                            {{-- No --}}
                            <td class="py-3.5 pl-4 pr-2 text-center tabular-nums text-[var(--text-muted)]" data-label="No">
                                {{ ($tagihans->currentPage() - 1) * $tagihans->perPage() + $index + 1 }}
                            </td>

                            {{-- Mahasiswa --}}
                            <td class="py-3.5 px-4" data-label="Mahasiswa">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($t->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-[var(--text-heading)] truncate max-w-[180px]">{{ $t->user->name ?? 'Unknown' }}</p>
                                        <p class="text-[11px] text-[var(--text-muted)] tabular-nums">{{ $t->user->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Program Studi --}}
                            <td class="py-3.5 px-3 text-[var(--text-muted)]" data-label="Program Studi">
                                <span class="block truncate max-w-[140px] font-medium text-[var(--text-heading)]">{{ $prodi }}</span>
                                <span class="block text-[11px] text-[var(--text-muted)] tabular-nums">Angkatan {{ $angkatan }}</span>
                            </td>

                            {{-- Item Tagihan --}}
                            <td class="py-3.5 px-4" data-label="Item Tagihan">
                                <p class="font-semibold text-[var(--text-heading)]">{{ $t->nama_tagihan }}</p>
                                @if(!empty($t->keterangan) || !empty($t->jatuh_tempo))
                                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                                        @if($t->jatuh_tempo)
                                            <span class="tabular-nums">Tempo: {{ \Carbon\Carbon::parse($t->jatuh_tempo)->format('d M Y') }}</span>
                                        @endif
                                        @if(!empty($t->keterangan))
                                            <span>&bull; {{ Str::limit($t->keterangan, 35) }}</span>
                                        @endif
                                    </p>
                                @endif
                            </td>

                            {{-- Nominal Total --}}
                            <td class="py-3.5 px-4 text-right font-semibold text-[var(--text-heading)] tabular-nums" data-label="Total">
                                Rp {{ number_format($nomTotal, 0, ',', '.') }}
                            </td>

                            {{-- Dibayar --}}
                            <td class="py-3.5 px-4 text-right font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums" data-label="Dibayar">
                                Rp {{ number_format($dibayar, 0, ',', '.') }}
                            </td>

                            {{-- Sisa Kekurangan --}}
                            <td class="py-3.5 px-4 text-right font-bold tabular-nums {{ $sisa > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" data-label="Sisa Kekurangan">
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="py-3.5 px-3 text-center" data-label="Status">
                                @if($isLunas)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Lunas
                                    </span>
                                @elseif($isSebagian)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Cicilan Sebagian
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Kwitansi jika sudah ada pembayaran --}}
                                    @if($lastVerifiedPay && !empty($lastVerifiedPay->kwitansi))
                                        <a href="{{ asset('storage/' . $lastVerifiedPay->kwitansi) }}" 
                                           target="_blank" 
                                           class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-emerald-600 hover:bg-emerald-50 border border-[var(--border)] transition cursor-pointer" 
                                           title="Unduh Kwitansi Resmi" 
                                           aria-label="Unduh Kwitansi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    @endif

                                    {{-- Kirim Pengingat jika belum lunas & mahasiswa punya email --}}
                                    @if(!$isLunas && !empty($t->user->email))
                                        <form action="{{ route('backend.keuangan.tagihan.resendReminder', $t->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer" 
                                            title="Kirim pengingat pembayaran ke {{ $t->user->email }}" 
                                                    aria-label="Kirim pengingat tagihan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-xs text-[var(--text-muted)]">
                                <div class="w-12 h-12 rounded-full bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)] flex items-center justify-center mx-auto mb-2.5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="font-bold text-[var(--text-heading)]">Tidak Ada Data Tagihan Non-SPP</p>
                                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Belum ada tagihan insidentil yang sesuai dengan kriteria filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="px-5 py-3.5 border-t border-[var(--border)] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[var(--surface)]">
            <p class="text-xs text-[var(--text-muted)] tabular-nums">
                @if($tagihans->total() > 0)
                    Menampilkan <span class="font-semibold text-[var(--text-heading)]">{{ $tagihans->firstItem() }}</span> sampai <span class="font-semibold text-[var(--text-heading)]">{{ $tagihans->lastItem() }}</span> dari <span class="font-semibold text-[var(--text-heading)]">{{ $tagihans->total() }}</span> tagihan lainnya
                @else
                    Menampilkan 0 tagihan
                @endif
            </p>
            @if($tagihans->hasPages())
                <div class="tagihan-pagination">
                    {{ $tagihans->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
