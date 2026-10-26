@extends('layouts.keuangan')

@section('title', 'Rekapitulasi SPP per Semester')

@section('content')
<div class="space-y-6">

    {{-- ================================================================= --}}
    {{-- HEADER & TOMBOL AKSI UTAMA                                        --}}
    {{-- ================================================================= --}}
    <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-5 md:p-6 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base md:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Rekapitulasi SPP per Semester</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 uppercase">
                            {{ $tipeSemester === 'ganjil' ? 'Semester Ganjil' : 'Semester Genap' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Lembar kerja matriks pembayaran SPP 6 bulan, kalkulasi tunggakan, dan status kelunasan mahasiswa.
                    </p>
                </div>
            </div>
        </div>

        {{-- Action Buttons: Export Excel & PDF --}}
        <div class="flex items-center gap-2.5 shrink-0">
            {{-- Tombol Export Excel --}}
            <a href="{{ route('backend.keuangan.rekap-spp.export.excel', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors cursor-pointer"
               title="Unduh Lembar Kerja Rekapitulasi SPP ke format Microsoft Excel">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                    <path d="M8.5 12.5l1.5 2.5-1.5 2.5h1.5l.8-1.5.8 1.5h1.5l-1.5-2.5 1.5-2.5h-1.5l-.8 1.5-.8-1.5z"/>
                </svg>
                <span>Export Excel</span>
            </a>

            {{-- Tombol Export PDF --}}
            <a href="{{ route('backend.keuangan.rekap-spp.export.pdf', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold shadow-xs transition-colors cursor-pointer"
               title="Cetak atau Unduh Laporan Rekapitulasi SPP ke format PDF resmi MATLA">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- RINGKASAN METRIK KEUANGAN (5 KARTU ENTERPRISE)                    --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 md:gap-4">
        {{-- Card 1: Total Mahasiswa --}}
        <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Mahasiswa</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums tracking-tight">
                {{ $summary['total_mahasiswa'] }} <span class="text-xs font-semibold text-slate-500">Orang</span>
            </p>
            <p class="text-[11px] text-slate-500 mt-0.5">Sesuai filter kriteria</p>
        </div>

        {{-- Card 2: Total Potensi SPP --}}
        <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Potensi SPP</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-xl lg:text-[22px] font-extrabold text-slate-900 dark:text-white tabular-nums tracking-tight">
                Rp {{ number_format($summary['total_potensi'], 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-500 mt-0.5">Target 6 bulan semester</p>
        </div>

        {{-- Card 3: SPP Terkumpul (Lunas) --}}
        <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">Sudah Terkumpul</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <p class="text-xl lg:text-[22px] font-extrabold text-emerald-700 dark:text-emerald-400 tabular-nums tracking-tight">
                Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">{{ $summary['mahasiswa_lunas'] }} mahasiswa bebas tunggakan</p>
        </div>

        {{-- Card 4: Sisa Tunggakan SPP --}}
        <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-rose-900/40 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Sisa Tunggakan</span>
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
            </div>
            <p class="text-xl lg:text-[22px] font-extrabold text-rose-600 dark:text-rose-400 tabular-nums tracking-tight">
                Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-rose-700 font-medium mt-0.5">{{ $summary['mahasiswa_menunggak'] }} mahasiswa belum lunas</p>
        </div>

        {{-- Card 5: Persentase Kelunasan --}}
        <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">% Kelunasan</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums tracking-tight">
                {{ $summary['persentase_lunas'] }}%
            </p>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ min(100, $summary['persentase_lunas']) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- FILTER DINAMIS                                                    --}}
    {{-- ================================================================= --}}
    <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl p-5 shadow-xs">
        <form method="GET" action="{{ route('backend.keuangan.rekap-spp.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                {{-- 1. Filter Tahun Akademik --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tahun Akademik</label>
                    <select name="tahun_akademik" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        @foreach($daftarTahunAkademik as $thn)
                            <option value="{{ $thn }}" {{ $tahunAkademik === $thn ? 'selected' : '' }}>
                                TA {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Tipe Semester (Ganjil vs Genap) --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Periode Semester</label>
                    <select name="tipe_semester" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        <option value="ganjil" {{ $tipeSemester === 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Jul - Des)</option>
                        <option value="genap" {{ $tipeSemester === 'genap' ? 'selected' : '' }}>Semester Genap (Jan - Jun)</option>
                    </select>
                </div>

                {{-- 3. Program Studi --}}
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Program Studi</label>
                    <select name="prodi" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        <option value="">Semua Program Studi</option>
                        @foreach($daftarProdi as $p)
                            <option value="{{ $p }}" {{ $selectedProdi === $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Semester Mahasiswa --}}
                <div class="lg:col-span-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Semester</label>
                    <select name="semester" onchange="this.form.submit()" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        <option value="">Semua</option>
                        @foreach($daftarSemesterMhs as $sVal)
                            <option value="{{ $sVal }}" {{ (string)$selectedSemester === (string)$sVal ? 'selected' : '' }}>
                                Smst {{ $sVal }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Status Akademik (Default: AKTIF) --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status Mahasiswa</label>
                    <select name="status_akademik" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        <option value="AKTIF" {{ strtoupper($statusAkademik) === 'AKTIF' ? 'selected' : '' }}>Hanya Mahasiswa Aktif</option>
                        <option value="semua" {{ strtolower($statusAkademik) === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="CUTI" {{ strtoupper($statusAkademik) === 'CUTI' ? 'selected' : '' }}>Mahasiswa Cuti</option>
                        <option value="LULUS" {{ strtoupper($statusAkademik) === 'LULUS' ? 'selected' : '' }}>Mahasiswa Lulus</option>
                    </select>
                </div>

                {{-- 6. Pencarian Cepat Nama / NIM --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cari Mahasiswa</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Nama atau NIM..." 
                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-600 transition">
                        <span class="absolute left-2.5 top-2.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan rekapitulasi: <b class="text-slate-800">{{ count($rekapItems) }}</b> mahasiswa terdaftar.
                </p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('backend.keuangan.rekap-spp.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition-colors">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-4 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-lg shadow-xs transition-colors cursor-pointer">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ================================================================= --}}
    {{-- TABEL MATRIKS REKAP SPP (FORMAT LEMBAR KERJA KEUANGAN KAMPUS)     --}}
    {{-- ================================================================= --}}
    <div class="bg-white dark:bg-[#0E2217] border border-slate-200 dark:border-emerald-900/40 rounded-2xl shadow-xs overflow-hidden">
        
        <div class="p-4 md:p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-sm md:text-base font-bold text-slate-900 dark:text-white">Matriks Pembayaran SPP 6 Bulan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Ikon <span class="text-emerald-700 font-bold">✔ Lunas</span> menandakan tagihan bulan tersebut telah terverifikasi lunas.</p>
            </div>
            <span class="text-xs font-semibold text-slate-500">
                TA {{ $tahunAkademik }} &bull; {{ strtoupper($tipeSemester) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-700">
                        <th class="py-3 px-3 text-center border-r border-slate-200 dark:border-slate-700 w-10">No</th>
                        <th class="py-3 px-3 text-center border-r border-slate-200 dark:border-slate-700 w-24">NIM</th>
                        <th class="py-3 px-4 border-r border-slate-200 dark:border-slate-700 min-w-[200px]">Mahasiswa & Prodi</th>
                        
                        {{-- 6 Kolom Bulan Semester --}}
                        @foreach($daftarBulan as $b)
                            <th class="py-3 px-2 text-center border-r border-slate-200 dark:border-slate-700 w-16">
                                <span class="block uppercase font-extrabold text-[11px] text-emerald-800 dark:text-emerald-400">{{ substr($b['nama'], 0, 3) }}</span>
                                <span class="text-[9px] text-slate-500 font-normal">{{ $b['nama'] }}</span>
                            </th>
                        @endforeach

                        <th class="py-3 px-3 text-right border-r border-slate-200 dark:border-slate-700 w-24">Tarif SPP</th>
                        <th class="py-3 px-2 text-center border-r border-slate-200 dark:border-slate-700 w-14">Tgk</th>
                        <th class="py-3 px-3 text-right border-r border-slate-200 dark:border-slate-700 w-28">Total Tagihan</th>
                        <th class="py-3 px-3 text-right border-r border-slate-200 dark:border-slate-700 w-28">Sudah Bayar</th>
                        <th class="py-3 px-3 text-right border-r border-slate-200 dark:border-slate-700 w-28">Sisa Tunggakan</th>
                        <th class="py-3 px-3 text-center border-r border-slate-200 dark:border-slate-700 w-24">Status</th>
                        <th class="py-3 px-3 min-w-[130px]">Metode / Ket.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-800 dark:text-slate-200">
                    @forelse($rekapItems as $index => $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            {{-- 1. No --}}
                            <td class="py-3 px-3 text-center border-r border-slate-100 dark:border-slate-800 font-medium text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            {{-- 2. NIM --}}
                            <td class="py-3 px-3 text-center border-r border-slate-100 dark:border-slate-800 font-mono text-slate-600 font-semibold">
                                {{ $item['nim'] }}
                            </td>

                            {{-- 3. Nama & Prodi --}}
                            <td class="py-3 px-4 border-r border-slate-100 dark:border-slate-800">
                                <div class="font-bold text-slate-900 dark:text-white leading-tight">
                                    {{ $item['nama'] }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>{{ $item['prodi'] }}</span>
                                    <span>&bull;</span>
                                    <span>Smst {{ $item['semester_mhs'] }}</span>
                                </div>
                            </td>

                            {{-- 4. Matriks 6 Bulan --}}
                            @foreach($item['matriks_bulan'] as $m)
                                <td class="py-3 px-2 text-center border-r border-slate-100 dark:border-slate-800">
                                    @if($m['is_lunas'])
                                        <div class="inline-flex items-center justify-center gap-1 px-2 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold" 
                                             title="Lunas - Rp {{ number_format($m['nominal_bayar'], 0, ',', '.') }}">
                                            <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Lunas</span>
                                        </div>
                                    @elseif($m['ada_tagihan'] && $m['nominal_bayar'] > 0)
                                        <div class="inline-flex flex-col items-center justify-center px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 text-[9px] font-semibold"
                                             title="Sebagian: Rp {{ number_format($m['nominal_bayar'], 0, ',', '.') }}">
                                            <span>Sebagian</span>
                                        </div>
                                    @else
                                        <span class="inline-block w-5 h-5 leading-5 rounded-full text-center text-slate-300 font-bold" title="Belum Dibayar">-</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- 5. Nominal SPP (dari nominal_spp) --}}
                            <td class="py-3 px-3 text-right border-r border-slate-100 dark:border-slate-800 font-mono font-bold text-slate-700">
                                Rp {{ number_format($item['nominal_spp'], 0, ',', '.') }}
                            </td>

                            {{-- 6. Bulan Menunggak --}}
                            <td class="py-3 px-2 text-center border-r border-slate-100 dark:border-slate-800">
                                @if($item['bulan_menunggak'] > 0)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-extrabold text-xs">
                                        {{ $item['bulan_menunggak'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-xs">
                                        0
                                    </span>
                                @endif
                            </td>

                            {{-- 7. Total Tagihan Semester --}}
                            <td class="py-3 px-3 text-right border-r border-slate-100 dark:border-slate-800 font-mono font-semibold text-slate-800">
                                Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}
                            </td>

                            {{-- 8. Total Sudah Dibayar --}}
                            <td class="py-3 px-3 text-right border-r border-slate-100 dark:border-slate-800 font-mono font-bold text-emerald-700">
                                Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}
                            </td>

                            {{-- 9. Sisa Tunggakan --}}
                            <td class="py-3 px-3 text-right border-r border-slate-100 dark:border-slate-800 font-mono font-bold {{ $item['sisa_tunggakan'] > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                Rp {{ number_format($item['sisa_tunggakan'], 0, ',', '.') }}
                            </td>

                            {{-- 10. Status --}}
                            <td class="py-3 px-3 text-center border-r border-slate-100 dark:border-slate-800">
                                @if($item['status'] === 'Lunas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Menunggak
                                    </span>
                                @endif
                            </td>

                            {{-- 11. Metode / Keterangan --}}
                            <td class="py-3 px-3 text-[11px] text-slate-600">
                                {{ $item['metode'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-semibold text-slate-600">Tidak ada data mahasiswa ditemukan</span>
                                    <span class="text-xs text-slate-400 mt-0.5">Silakan sesuaikan filter Program Studi, Semester, atau kata kunci pencarian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapItems) > 0)
                    <tfoot>
                        <tr class="bg-slate-100/90 dark:bg-slate-800 font-bold border-t-2 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white">
                            <td colspan="3" class="py-3.5 px-4 text-right border-r border-slate-200 uppercase tracking-wider text-xs">
                                TOTAL KESELURUHAN ({{ count($rekapItems) }} Mahasiswa):
                            </td>
                            @foreach($daftarBulan as $b)
                                <td class="py-3 px-2 text-center border-r border-slate-200 text-slate-400">-</td>
                            @endforeach
                            <td class="py-3 px-3 text-right border-r border-slate-200 text-slate-400">-</td>
                            <td class="py-3 px-2 text-center border-r border-slate-200 text-rose-700">
                                {{ $rekapItems ? array_sum(array_column($rekapItems, 'bulan_menunggak')) : 0 }} bln
                            </td>
                            <td class="py-3 px-3 text-right border-r border-slate-200 font-mono text-xs text-slate-900">
                                Rp {{ number_format($summary['total_potensi'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-slate-200 font-mono text-xs text-emerald-700">
                                Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-slate-200 font-mono text-xs text-rose-600">
                                Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="py-3 px-3 text-center text-xs text-emerald-800">
                                {{ $summary['persentase_lunas'] }}% Terkumpul
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

    </div>

</div>
@endsection
