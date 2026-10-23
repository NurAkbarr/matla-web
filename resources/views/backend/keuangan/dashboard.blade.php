@extends('layouts.keuangan')

@section('title', 'Dashboard Keuangan')

@section('content')
<div class="space-y-6 md:space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/20 border border-[var(--primary-200)]/60 dark:border-[var(--primary-800)]/40 rounded-2xl px-6 py-5 flex items-center gap-4 shadow-xs">
        <div class="w-12 h-12 rounded-full bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/50 border border-[var(--primary-200)]/80 dark:border-[var(--primary-700)]/40 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-[var(--text-heading)]">Selamat datang di Sistem Keuangan MATLA University Portal.</p>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Kelola keuangan kampus, verifikasi tagihan, serta pantau arus kas secara menyeluruh.</p>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 1. TOP CARDS (BARIS PERTAMA - 3 KOLOM SEJAJAR)                    --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        {{-- Card 1: Saldo Akun / Saldo Utama --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card hover:shadow-hover transition-all duration-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-400)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Saldo Utama</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/50 text-[var(--primary-600)] dark:text-[var(--primary-300)]">Aktif</span>
                </div>
                <p class="text-2xl md:text-[28px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">
                    Rp {{ number_format($saldoAkun, 0, ',', '.') }}
                </p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5 flex items-center gap-1">
                    <span>Total saldo bersih kampus saat ini</span>
                </p>
            </div>
        </div>

        {{-- Card 2: Total Tagihan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card hover:shadow-hover transition-all duration-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-[var(--secondary-100)] dark:bg-[var(--secondary-900)]/40 text-[var(--secondary-700)] dark:text-[var(--secondary-400)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Total Tagihan</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300">Belum Lunas</span>
                </div>
                <p class="text-2xl md:text-[28px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5">
                    {{ $jumlahTagihan }} item tagihan belum dibayar (bulanan & semesteran)
                </p>
            </div>
        </div>

        {{-- Card 3: Riwayat Transaksi (Clickable to Riwayat Transaksi) --}}
        <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" 
           class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200 flex items-start gap-4 cursor-pointer group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Riwayat Transaksi</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300">
                        <span>Tercatat</span>
                        <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <p class="text-2xl md:text-[28px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5 flex items-center justify-between">
                    <span>Total transaksi masuk & keluar</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium text-[11px] group-hover:underline">Buka Buku Kas &rarr;</span>
                </p>
            </div>
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- 2. SECTION BARU: LAPORAN KEUANGAN BULANAN                         --}}
    {{-- ================================================================= --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card space-y-6 md:space-y-7">
        
        {{-- Section Header with Dynamic Month Dropdown Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[var(--border)]">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-400)] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base md:text-lg font-bold text-[var(--text-heading)] tracking-tight">Laporan Keuangan Bulanan</h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">
                        Ringkasan arus kas, SPP, dan status mahasiswa periode <span class="font-semibold text-[var(--primary-600)] dark:text-[var(--primary-400)]">{{ $selectedLabel }}</span>
                    </p>
                </div>
            </div>

            {{-- Dropdown Filter Bulan Dinamis --}}
            <form method="GET" action="{{ route('backend.keuangan.dashboard') }}" class="flex items-center gap-2">
                <div class="relative inline-flex items-center w-full sm:w-auto">
                    <span class="absolute left-3.5 pointer-events-none text-[var(--primary-500)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <select name="periode" onchange="this.form.submit()" class="w-full sm:w-auto appearance-none pl-10 pr-9 py-2.5 text-xs md:text-sm font-semibold text-[var(--text-heading)] bg-[var(--bg-page)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/30 border border-[var(--border)] focus:border-[var(--primary-500)] focus:ring-2 focus:ring-[var(--primary-500)]/20 rounded-xl transition-all cursor-pointer shadow-xs">
                        @foreach($daftarBulan as $bulanOption)
                            <option value="{{ $bulanOption['value'] }}" {{ $selectedPeriode === $bulanOption['value'] ? 'selected' : '' }}>
                                {{ $bulanOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute right-3.5 pointer-events-none text-[var(--text-muted)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </form>
        </div>

        {{-- Baris 1: Grid 4 Kolom (Metrik Keuangan Bulanan) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            {{-- 1. TOTAL PEMASUKAN SPP --}}
            <div class="border border-[var(--primary-200)]/70 dark:border-[var(--primary-800)]/40 bg-[var(--primary-50)]/40 dark:bg-[var(--primary-900)]/15 rounded-2xl p-5 flex flex-col justify-between transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] md:text-xs font-bold text-[var(--primary-700)] dark:text-[var(--primary-300)] uppercase tracking-wider">Total Pemasukan SPP</span>
                    <div class="w-8 h-8 rounded-lg bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/50 text-[var(--primary-600)] dark:text-[var(--primary-300)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-[var(--text-heading)] tabular-nums tracking-tight">
                        Rp {{ number_format($pemasukanSpp, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-[var(--text-muted)] mt-1">Pembayaran SPP terverifikasi</p>
                </div>
            </div>

            {{-- 2. TOTAL PEMASUKAN SALDO LAIN (termasuk saldo bulan sebelumnya) --}}
            <div class="border border-emerald-200/70 dark:border-emerald-800/40 bg-emerald-50/40 dark:bg-emerald-950/15 rounded-2xl p-5 flex flex-col justify-between transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] md:text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">Pemasukan Lain & Saldo Lalu</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-[var(--text-heading)] tabular-nums tracking-tight">
                        Rp {{ number_format($pemasukanLain, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-[var(--text-muted)] mt-1">Non-SPP & sisa saldo bulan lalu</p>
                </div>
            </div>

            {{-- 3. TOTAL PENGELUARAN --}}
            <div class="border border-amber-200/70 dark:border-amber-800/40 bg-amber-50/40 dark:bg-amber-950/15 rounded-2xl p-5 flex flex-col justify-between transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] md:text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Total Pengeluaran</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-[var(--text-heading)] tabular-nums tracking-tight">
                        Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-[var(--text-muted)] mt-1">Pengeluaran operasional kampus</p>
                </div>
            </div>

            {{-- 4. TOTAL TUNGGAKAN SPP (SALDO MINUS) --}}
            <div class="border border-rose-200 dark:border-rose-900/50 bg-rose-50/50 dark:bg-rose-950/20 rounded-2xl p-5 flex flex-col justify-between transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] md:text-xs font-bold text-rose-800 dark:text-rose-300 uppercase tracking-wider">Tunggakan SPP (Minus)</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/50 text-[var(--danger)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-xl md:text-2xl font-bold text-rose-600 dark:text-rose-400 tabular-nums tracking-tight">
                            -Rp {{ number_format($tunggakanSpp, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-xs text-rose-700/80 dark:text-rose-400/80 mt-1">Tagihan SPP belum dibayar</p>
                </div>
            </div>
        </div>

        {{-- Baris 2: Full Width / 1 Kolom Penuh (TOTAL KESELURUHAN) --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[var(--primary-800)] via-[var(--primary-700)] to-[var(--primary-600)] p-6 md:p-7 text-white shadow-card">
            <div class="absolute -right-8 -bottom-8 w-44 h-44 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-white/15 text-white backdrop-blur-xs tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full {{ $totalKeseluruhan >= 0 ? 'bg-emerald-300' : 'bg-rose-300' }} animate-pulse"></span>
                        Kalkulasi Bersih Periode {{ $selectedLabel }}
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-white tracking-tight">TOTAL KESELURUHAN</h3>
                    <p class="text-xs text-white/80 font-mono tracking-tight">
                        (Total Pemasukan SPP + Total Pemasukan Saldo Lain) - Total Pengeluaran
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-2xl md:text-4xl font-extrabold tracking-tight tabular-nums text-white">
                        Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                    </p>
                    <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-lg {{ $totalKeseluruhan >= 0 ? 'bg-white/20 text-white' : 'bg-rose-500/30 text-rose-100' }} text-xs font-semibold">
                        @if($totalKeseluruhan >= 0)
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Surplus Kas Operasional Periode Ini</span>
                        @else
                            <svg class="w-4 h-4 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Defisit Kas Operasional Periode Ini</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris 3: Statistik Mahasiswa (Grid 3 Kolom) --}}
        <div>
            <div class="flex items-center gap-2 mb-3.5">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xs md:text-sm font-bold text-[var(--text-heading)] uppercase tracking-wider">Statistik Mahasiswa (Periode {{ $selectedLabel }})</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
                {{-- 1. Jumlah Mahasiswa --}}
                <div class="border border-[var(--border)] bg-[var(--bg-page)]/60 rounded-xl p-4 md:p-5 flex items-center justify-between transition-all duration-200">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Jumlah Mahasiswa</p>
                            <p class="text-xl md:text-2xl font-bold text-[var(--text-heading)] tabular-nums tracking-tight mt-0.5">
                                {{ $totalMahasiswaAktif }} <span class="text-xs font-normal text-[var(--text-muted)]">Orang</span>
                            </p>
                            <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Seluruh mahasiswa aktif</p>
                        </div>
                    </div>
                </div>

                {{-- 2. Mahasiswa Menunggak --}}
                <div class="border border-rose-100 dark:border-rose-900/30 bg-rose-50/40 dark:bg-rose-950/15 rounded-xl p-4 md:p-5 flex items-center justify-between transition-all duration-200">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/50 text-[var(--danger)] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-xs font-semibold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Mahasiswa Menunggak</p>
                            <p class="text-xl md:text-2xl font-bold text-rose-600 dark:text-rose-400 tabular-nums tracking-tight mt-0.5">
                                {{ $mahasiswaMenunggak }} <span class="text-xs font-normal text-rose-500/80">Orang</span>
                            </p>
                            <p class="text-[11px] text-rose-600/70 dark:text-rose-400/70 mt-0.5">
                                @if(($totalMahasiswaBertagihan ?? 0) > 0)
                                    {{ round(($mahasiswaMenunggak / $totalMahasiswaBertagihan) * 100) }}% memiliki tunggakan
                                @else
                                    Tidak ada tagihan aktif periode ini
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 3. Mahasiswa Lunas --}}
                <div class="border border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/40 dark:bg-emerald-950/15 rounded-xl p-4 md:p-5 flex items-center justify-between transition-all duration-200">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Mahasiswa Lunas</p>
                            <p class="text-xl md:text-2xl font-bold text-emerald-600 dark:text-emerald-400 tabular-nums tracking-tight mt-0.5">
                                {{ $mahasiswaLunas }} <span class="text-xs font-normal text-emerald-600/80">Orang</span>
                            </p>
                            <p class="text-[11px] text-emerald-600/70 dark:text-emerald-400/70 mt-0.5">
                                @if(($totalMahasiswaBertagihan ?? 0) > 0)
                                    {{ round(($mahasiswaLunas / $totalMahasiswaBertagihan) * 100) }}% telah lunas periode ini
                                @else
                                    Tidak ada tagihan periode ini
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 3. BOTTOM SECTION: RIWAYAT TRANSAKSI TERKINI + KALENDER           --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        {{-- Left 2 Columns: Riwayat Transaksi Terkini --}}
        <div class="lg:col-span-2">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-400)] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-[var(--text-heading)]">Riwayat Transaksi Terkini</h3>
                                <p class="text-xs text-[var(--text-muted)]">Aktivitas pembayaran mahasiswa terbaru yang telah diverifikasi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                                <span>Buku Kas Lengkap</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="divide-y divide-[var(--border)]">
                        @forelse($riwayatTerkini as $riwayat)
                            @php
                                $isTxKeuangan = ($riwayat instanceof \App\Models\TransaksiKeuangan);
                                $isKeluar = $isTxKeuangan ? $riwayat->isKeluar() : false;
                                $subjek = $riwayat->user->name ?? ($riwayat->subjek ?? 'Transaksi Kas');
                                $initial = substr($subjek, 0, 1);
                                $isAmber = (crc32($subjek) % 2) === 1;
                                $keterangan = $isTxKeuangan 
                                    ? ($riwayat->kategori . ($riwayat->deskripsi ? ' • ' . \Illuminate\Support\Str::limit($riwayat->deskripsi, 35) : ''))
                                    : ($riwayat->tagihan->nama_tagihan ?? 'Pembayaran');
                                $timeObj = $isTxKeuangan ? $riwayat->tanggal_transaksi : $riwayat->updated_at;
                            @endphp
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3.5 min-w-0 pr-4">
                                    <div class="w-10 h-10 rounded-full {{ $isKeluar ? 'bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400' : ($isAmber ? 'bg-[var(--secondary-100)] dark:bg-[var(--secondary-900)]/40 text-[var(--secondary-700)] dark:text-[var(--secondary-400)]' : 'bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-300)]') }} flex items-center justify-center text-sm font-bold uppercase shrink-0 shadow-xs">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[var(--text-heading)] truncate">{{ $subjek }}</p>
                                        <p class="text-xs text-[var(--text-muted)] mt-0.5 truncate">
                                            {{ $keterangan }}
                                            @if(!$isTxKeuangan && !empty($riwayat->user->education['program_studi']))
                                                <span class="opacity-80">• {{ $riwayat->user->education['program_studi'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold {{ $isKeluar ? 'text-rose-600 dark:text-rose-400' : 'text-[var(--primary-600)] dark:text-[var(--primary-400)]' }} tabular-nums">
                                        {{ ($isKeluar ? '- Rp ' : '+ Rp ') . number_format($riwayat->nominal, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                                        @if($timeObj && $timeObj->isToday())
                                            Hari ini, {{ $timeObj->format('H:i') }}
                                        @elseif($timeObj && $timeObj->isYesterday())
                                            Kemarin, {{ $timeObj->format('H:i') }}
                                        @elseif($timeObj)
                                            {{ $timeObj->format('d M, H:i') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-[var(--text-muted)] text-sm flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-[var(--bg-page)] flex items-center justify-center text-[var(--text-muted)] mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <span>Belum ada riwayat transaksi pembayaran terverifikasi.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Lihat Semua --}}
                <div class="mt-6 pt-5 border-t border-[var(--border)] flex justify-center">
                    <a href="{{ route('backend.keuangan.verifikasi.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-[var(--border)] rounded-xl text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/30 hover:text-[var(--primary-500)] transition-colors shadow-xs">
                        Lihat Semua Transaksi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right 1 Column: Kalender --}}
        <div class="lg:col-span-1">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-[var(--border)]">
                        <div class="w-8 h-8 rounded-lg bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-400)] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[var(--text-heading)]">Kalender</h3>
                    </div>

                    <div class="text-center mb-6">
                        <p class="text-sm font-bold text-[var(--text-heading)]" id="hijri-date">Memuat tanggal Hijriah...</p>
                        <p class="text-xs text-[var(--text-muted)] mt-1" id="gregorian-date">Memuat tanggal Masehi...</p>
                    </div>

                    <div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-3">
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sen</div>
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sel</div>
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Rab</div>
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Kam</div>
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Jum</div>
                            <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sab</div>
                            <div class="text-[10px] md:text-xs font-bold text-[var(--danger)] uppercase py-1">Min</div>
                        </div>
                        <div id="mini-calendar" class="grid grid-cols-7 gap-y-2 text-center text-xs font-medium text-[var(--text-body)]">
                            <!-- JS will fill this -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var calEl = document.getElementById('mini-calendar');
    if (!calEl) return;

    var now = new Date();
    
    // Update headers dynamically
    var gregorianEl = document.getElementById('gregorian-date');
    if (gregorianEl) {
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        gregorianEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    
    var hijriEl = document.getElementById('hijri-date');
    if (hijriEl) {
        try {
            // Koreksi kalender Hijriah (mundur 1 hari)
            var hijriDate = new Date(now);
            hijriDate.setDate(hijriDate.getDate() - 1);

            var hijriOptions = { day: 'numeric', month: 'long', year: 'numeric' };
            var hijriFormatter = new Intl.DateTimeFormat('id-ID-u-ca-islamic', hijriOptions);
            var formattedHijri = hijriFormatter.format(hijriDate);
            
            // Hapus akhiran 'H' atau 'AH' bawaan jika ada agar tidak double
            formattedHijri = formattedHijri.replace(/\s*AH/i, '').replace(/\s*H$/i, '');
            hijriEl.innerText = formattedHijri + ' H';
        } catch (e) {
            hijriEl.innerText = '12 Rabiulawal 1448 H';
        }
    }

    var month = now.getMonth();
    var year = now.getFullYear();
    var today = now.getDate();
    var firstDay = new Date(year, month, 1).getDay();
    // Adjust: JS Sunday=0, we want Monday=0
    firstDay = (firstDay + 6) % 7;
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var prevMonthDays = new Date(year, month, 0).getDate();

    var html = '';

    // Previous month trailing days
    for (var i = firstDay - 1; i >= 0; i--) {
        html += '<div class="py-1.5 text-[#B5C0BA] dark:text-neutral-600 font-normal">' + (prevMonthDays - i) + '</div>';
    }

    // Current month days
    for (var d = 1; d <= daysInMonth; d++) {
        var dayIndex = (firstDay + d - 1) % 7;
        var isSunday = (dayIndex === 6);

        if (d === today) {
            html += '<div class="py-1.5"><div class="bg-[var(--primary-500)] text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto text-xs font-bold shadow-sm">' + d + '</div></div>';
        } else if (isSunday) {
            html += '<div class="py-1.5 text-[var(--danger)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--surface)] rounded-full w-7 h-7 flex items-center justify-center mx-auto cursor-default transition-colors">' + d + '</div>';
        } else {
            html += '<div class="py-1.5 text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 rounded-full w-7 h-7 flex items-center justify-center mx-auto cursor-default transition-colors">' + d + '</div>';
        }
    }

    // Next month leading days
    var totalCells = firstDay + daysInMonth;
    var remaining = (7 - (totalCells % 7)) % 7;
    for (var n = 1; n <= remaining; n++) {
        html += '<div class="py-1.5 text-[#B5C0BA] dark:text-neutral-600 font-normal">' + n + '</div>';
    }

    calEl.innerHTML = html;
})();
</script>
@endpush
