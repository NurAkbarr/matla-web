@extends('layouts.keuangan')

@section('title', 'Rekapitulasi SPP per Semester')

@section('content')
<div class="space-y-6">

    {{-- Toast Notification Container --}}
    <div id="toastContainer" class="fixed top-5 right-5 z-50 space-y-2 pointer-events-none"></div>

    {{-- Header Area: Title & Export Buttons --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Rekapitulasi SPP per semester</h1>
                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                    {{ $tipeSemester === 'ganjil' ? 'Semester ganjil' : 'Semester genap' }}
                </span>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">
                Lembar kerja matriks pembayaran SPP 6 bulan, pencatatan historis, kalkulasi tunggakan, dan penerbitan kuitansi.
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <form action="{{ route('backend.keuangan.rekap-spp.sync-tagihan') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="tahun_akademik" value="{{ $tahunAkademik }}">
                <input type="hidden" name="tipe_semester" value="{{ $tipeSemester }}">
                <button type="submit" 
                        class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--primary-700)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer"
                        title="Terbitkan / Sinkronkan tagihan tunggakan semester aktif ke portal mahasiswa">
                    <svg class="w-4 h-4 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Sinkron Tagihan Portal</span>
                </button>
            </form>

            <a href="{{ route('backend.keuangan.master-tahun-akademik.index') }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer"
               title="Kelola Master Tahun Akademik & Semester">
                <svg class="w-4 h-4 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Kelola Tahun & Semester</span>
            </a>

            <a href="{{ route('backend.keuangan.rekap-spp.export.excel', request()->all()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--primary-500)] hover:bg-[var(--primary-50)] text-[var(--primary-700)] dark:text-[var(--primary-300)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer"
               title="Unduh format Excel">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor Excel</span>
            </a>

            <a href="{{ route('backend.keuangan.rekap-spp.export.pdf', request()->all()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer"
               title="Cetak format PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan 5 Kartu (Border 1px, Tanpa Bayangan) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
        {{-- Card 1: Mahasiswa --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Mahasiswa</p>
                <p class="text-xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1">
                    {{ $summary['total_mahasiswa'] }} <span class="text-xs font-normal text-[var(--text-muted)]">orang</span>
                </p>
            </div>
            <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Sesuai filter kriteria</p>
        </div>

        {{-- Card 2: Potensi SPP --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Potensi SPP</p>
                <p class="text-xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1">
                    Rp {{ number_format($summary['total_potensi'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Target 6 bulan semester</p>
        </div>

        {{-- Card 3: Sudah Terkumpul --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--primary-600)]">Sudah terkumpul</p>
                <p class="text-xl font-bold font-display text-[var(--primary-600)] tabular-nums mt-1" id="sumTerkumpul">
                    Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--primary-600)] mt-1.5 tabular-nums" id="sumLunasSub">{{ $summary['mahasiswa_lunas'] }} mahasiswa lunas</p>
        </div>

        {{-- Card 4: Sisa Tunggakan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--danger)]">Sisa tunggakan</p>
                <p class="text-xl font-bold font-display text-[var(--danger)] tabular-nums mt-1" id="sumTunggakan">
                    Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--danger)] mt-1.5 tabular-nums" id="sumMenunggakSub">{{ $summary['mahasiswa_menunggak'] }} belum lunas</p>
        </div>

        {{-- Card 5: Persentase Kelunasan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Tingkat kelunasan</p>
                <p class="text-xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1" id="sumPersen">
                    {{ $summary['persentase_lunas'] }}%
                </p>
            </div>
            <div class="w-full bg-[var(--bg-page)] rounded-full h-1.5 mt-2 overflow-hidden border border-[var(--border)]">
                <div id="sumPersenBar" class="bg-[var(--primary-500)] h-1.5 rounded-full" style="width: {{ min(100, $summary['persentase_lunas']) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Filter Dinamis (Tinggi 40px, Radius 4px, Border Line) --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4">
        <form method="GET" action="{{ route('backend.keuangan.rekap-spp.index') }}" class="space-y-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                
                {{-- 1. Tahun Akademik --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Tahun akademik</label>
                    <select name="tahun_akademik" onchange="this.form.submit()" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
                        @foreach($daftarTahunAkademik as $thn)
                            <option value="{{ $thn }}" {{ $tahunAkademik === $thn ? 'selected' : '' }}>
                                TA {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Tipe Semester --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Semester</label>
                    <select name="tipe_semester" onchange="this.form.submit()" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
                        <option value="ganjil" {{ $tipeSemester === 'ganjil' ? 'selected' : '' }}>Ganjil (Jul–Des)</option>
                        <option value="genap" {{ $tipeSemester === 'genap' ? 'selected' : '' }}>Genap (Jan–Jun)</option>
                    </select>
                </div>

                {{-- 3. Program Studi --}}
                <div class="lg:col-span-3">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Program studi</label>
                    <select name="prodi" onchange="this.form.submit()" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
                        <option value="">Semua program studi</option>
                        @foreach($daftarProdi as $p)
                            <option value="{{ $p }}" {{ $selectedProdi === $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Semester Mahasiswa --}}
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Smst</label>
                    <select name="semester" onchange="this.form.submit()" class="w-full h-10 px-2 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
                        <option value="">Semua</option>
                        @foreach($daftarSemesterMhs as $sVal)
                            <option value="{{ $sVal }}" {{ (string)$selectedSemester === (string)$sVal ? 'selected' : '' }}>
                                {{ $sVal }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Status Akademik --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Status</label>
                    <select name="status_akademik" onchange="this.form.submit()" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
                        <option value="AKTIF" {{ strtoupper($statusAkademik) === 'AKTIF' ? 'selected' : '' }}>Mahasiswa aktif</option>
                        <option value="semua" {{ strtolower($statusAkademik) === 'semua' ? 'selected' : '' }}>Semua status</option>
                        <option value="CUTI" {{ strtoupper($statusAkademik) === 'CUTI' ? 'selected' : '' }}>Mahasiswa cuti</option>
                        <option value="LULUS" {{ strtoupper($statusAkademik) === 'LULUS' ? 'selected' : '' }}>Mahasiswa lulus</option>
                    </select>
                </div>

                {{-- 6. Pencarian Cepat Nama / NIM --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Cari</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Nama atau NIM..." 
                               class="w-full h-10 pl-8 pr-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <svg class="w-3.5 h-3.5 text-[var(--text-muted)] absolute left-2.5 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between pt-2 border-t border-[var(--border)] text-xs">
                <p class="text-[var(--text-muted)] tabular-nums">
                    Menampilkan rekapitulasi: <b class="text-[var(--text-heading)]">{{ count($rekapItems) }}</b> mahasiswa terdaftar
                </p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('backend.keuangan.rekap-spp.index') }}" class="h-9 px-3 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] font-semibold transition flex items-center">
                        Reset filter
                    </a>
                    <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                        Terapkan filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Panduan Alur Centang Historis & Tunggakan --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-3.5 text-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex items-start gap-2.5">
            <div class="w-6 h-6 rounded-full bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[var(--text-heading)] font-semibold">
                    Panduan Pencatatan Historis (Drive/Rekening Masa Lalu) & Alur Verifikasi:
                </p>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                    • <b>Centang Langsung:</b> Klik kotak centang pada kolom bulan untuk menandai pelunasan historis masa lalu. Kuitansi resmi otomatis terbit tanpa menduplikasi transaksi buku kas berjalan.<br>
                    • <b>Tunggakan Aktif:</b> Bulan yang belum dicentang otomatis terbit sebagai tagihan di portal mahasiswa.<br>
                    • <b>Alur Mahasiswa Bayar:</b> Saat mahasiswa bayar via portal, status matriks menampilkan <i>⏳ Menunggu</i>. Centang Lunas baru aktif otomatis setelah Admin klik <b>Setuju / Approved</b> di menu Verifikasi Pembayaran.
                </p>
            </div>
        </div>
    </div>

    {{-- Matriks Tabel Rekap SPP --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        
        <div class="px-5 py-3.5 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <div>
                <h2 class="text-xs font-bold font-display text-[var(--text-heading)]">Matriks pembayaran SPP 6 bulan</h2>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Klik langsung kotak centang untuk mencatat pelunasan historis</p>
            </div>
            <span class="text-xs font-semibold text-[var(--text-muted)] tabular-nums">
                TA {{ $tahunAkademik }} • {{ ucfirst($tipeSemester) }}
            </span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 text-[var(--text-body)] font-semibold text-[12px] border-b border-[var(--border)]">
                        <th class="py-3 px-3 text-center border-r border-[var(--border)] w-10">No</th>
                        <th class="py-3 px-3 text-center border-r border-[var(--border)] w-24">NIM</th>
                        <th class="py-3 px-4 border-r border-[var(--border)] min-w-[190px]">Mahasiswa & prodi</th>
                        
                        {{-- 6 Kolom Bulan --}}
                        @foreach($daftarBulan as $b)
                            <th class="py-3 px-2 text-center border-r border-[var(--border)] min-w-[90px]">
                                <span class="block font-bold text-xs text-[var(--text-heading)]">{{ $b['nama'] }}</span>
                                <span class="block text-[10px] text-[var(--text-muted)] font-normal">{{ $b['year'] }}</span>
                            </th>
                        @endforeach

                        <th class="py-3 px-3 text-right border-r border-[var(--border)] w-24">Tarif</th>
                        <th class="py-3 px-2 text-center border-r border-[var(--border)] w-12">Tgk</th>
                        <th class="py-3 px-3 text-right border-r border-[var(--border)] w-24">Tagihan</th>
                        <th class="py-3 px-3 text-right border-r border-[var(--border)] w-24">Dibayar</th>
                        <th class="py-3 px-3 text-right border-r border-[var(--border)] w-24">Sisa</th>
                        <th class="py-3 px-3 text-center border-r border-[var(--border)] w-20">Status</th>
                        <th class="py-3 px-3 min-w-[140px]">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($rekapItems as $index => $item)
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors mhs-row" 
                            id="row-mhs-{{ $item['id'] }}"
                            data-mhs-id="{{ $item['id'] }}"
                            data-nominal-spp="{{ $item['nominal_spp'] }}"
                            data-bulan-menunggak="{{ $item['bulan_menunggak'] }}"
                            data-tagihan="{{ $item['tagihan'] }}"
                            data-dibayar="{{ $item['total_dibayar'] }}"
                            data-sisa="{{ $item['sisa_tunggakan'] }}"
                            data-status="{{ $item['status'] }}"
                            data-catatan="{{ htmlspecialchars($item['catatan'] ?? '', ENT_QUOTES) }}"
                            data-metode-asli="{{ htmlspecialchars($item['metode_asli'] ?? 'Belum Ada Pembayaran', ENT_QUOTES) }}">
                            {{-- No --}}
                            <td class="py-3 px-3 text-center border-r border-[var(--border)] text-[var(--text-muted)] tabular-nums">
                                {{ $index + 1 }}
                            </td>

                            {{-- NIM --}}
                            <td class="py-3 px-3 text-center border-r border-[var(--border)] font-semibold text-[var(--text-heading)] tabular-nums">
                                {{ $item['nim'] }}
                            </td>

                            {{-- Nama & Prodi --}}
                            <td class="py-3 px-4 border-r border-[var(--border)]">
                                <p class="font-semibold text-[var(--text-heading)] truncate max-w-[180px]">{{ $item['nama'] }}</p>
                                <p class="text-[11px] text-[var(--text-muted)] mt-0.5 truncate max-w-[180px]">
                                    {{ $item['prodi'] }} • Smst {{ $item['semester_mhs'] }}
                                </p>
                            </td>

                            {{-- 6 Bulan Semester Interaktif --}}
                            @foreach($item['matriks_bulan'] as $m)
                                <td class="py-3 px-2 text-center border-r border-[var(--border)] align-middle" id="cell-{{ $item['id'] }}-{{ $m['key'] }}">
                                    @if($m['is_lunas'])
                                        {{-- 1. Status LUNAS (Checked) --}}
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <label class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-300)] text-[10.5px] font-bold cursor-pointer hover:bg-[var(--primary-100)] transition"
                                                   title="{{ $m['is_historis'] ? 'Status: Lunas Historis. Klik untuk membatalkan.' : 'Status: Lunas Terverifikasi.' }}">
                                                <input type="checkbox" 
                                                       checked 
                                                       onchange="handleCheckboxToggle({{ $item['id'] }}, '{{ $m['key'] }}', {{ $m['is_historis'] ? 'true' : 'false' }}, this)" 
                                                       class="w-3.5 h-3.5 rounded-[2px] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer">
                                                <span>✔ Lunas</span>
                                                @if(!empty($m['is_historis']))
                                                    <span class="text-[9px] px-1 bg-[var(--secondary-100)] text-[var(--secondary-800)] rounded-[2px] font-semibold">Hist</span>
                                                @elseif(!empty($m['is_alokasi_lintas']))
                                                    <span class="text-[9px] px-1 bg-[var(--primary-200)]/60 text-[var(--primary-800)] rounded-[2px] font-semibold">Alok</span>
                                                @endif
                                            </label>
                                            
                                            @if(!empty($m['payment_id']))
                                                <a href="{{ route('backend.keuangan.verifikasi.kwitansi-drive', $m['payment_id']) }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center gap-0.5 text-[9.5px] font-mono font-semibold text-[var(--primary-600)] hover:underline" 
                                                   title="Buka / Unduh Kuitansi Resmi">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span>Kuitansi</span>
                                                </a>
                                            @endif
                                        </div>
                                    @elseif(!empty($m['is_pending']))
                                        {{-- 2. Status MENUNGGU VERIFIKASI (Unchecked, Amber) --}}
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)] text-[10px] font-semibold cursor-help"
                                                  title="Mahasiswa telah mengunggah bukti transfer. Menunggu verifikasi Admin di menu Verifikasi Pembayaran.">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary-500)] animate-pulse"></span>
                                                <span>Menunggu</span>
                                            </span>
                                            <a href="{{ route('backend.keuangan.verifikasi.index') }}" class="text-[9.5px] text-[var(--primary-600)] hover:underline font-semibold">
                                                Verifikasi
                                            </a>
                                        </div>
                                    @else
                                        {{-- 3. Status BELUM LUNAS / TUNGGAKAN AKTIF (Unchecked, Interaktif) --}}
                                        <div class="flex flex-col items-center justify-center">
                                            <label class="inline-flex items-center gap-1.5 p-1 rounded-[var(--radius-xs)] hover:bg-[var(--primary-50)] border border-transparent hover:border-[var(--primary-200)] cursor-pointer transition text-[var(--text-muted)] hover:text-[var(--primary-700)]"
                                                   title="Centang untuk menandai Lunas Historis (Menerbitkan Kuitansi otomatis tanpa menduplikasi kas)">
                                                <input type="checkbox" 
                                                       onchange="handleCheckboxToggle({{ $item['id'] }}, '{{ $m['key'] }}', false, this)" 
                                                       class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer">
                                                <span class="text-[10px] font-medium">-</span>
                                            </label>
                                        </div>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Tarif SPP --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] font-semibold text-[var(--text-heading)] tabular-nums">
                                Rp {{ number_format($item['nominal_spp'], 0, ',', '.') }}
                            </td>

                            {{-- Bulan Menunggak --}}
                            <td class="py-3 px-2 text-center border-r border-[var(--border)] tabular-nums font-semibold {{ $item['bulan_menunggak'] > 0 ? 'text-[var(--danger)]' : 'text-[var(--primary-600)]' }}" id="mhs-tgk-{{ $item['id'] }}">
                                {{ $item['bulan_menunggak'] }}
                            </td>

                            {{-- Tagihan (Rp) --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--text-heading)] font-semibold" id="mhs-tagihan-{{ $item['id'] }}">
                                Rp {{ number_format($item['tagihan'], 0, ',', '.') }}
                            </td>

                            {{-- Total Sudah Dibayar --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--primary-600)] font-semibold" id="mhs-dibayar-{{ $item['id'] }}">
                                Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}
                            </td>

                            {{-- Sisa Tunggakan --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums {{ $item['sisa_tunggakan'] > 0 ? 'text-[var(--danger)] font-semibold' : 'text-[var(--text-muted)]' }}" id="mhs-sisa-{{ $item['id'] }}">
                                Rp {{ number_format($item['sisa_tunggakan'], 0, ',', '.') }}
                            </td>

                            {{-- Status Kelunasan --}}
                            <td class="py-3 px-3 text-center border-r border-[var(--border)]" id="mhs-status-{{ $item['id'] }}">
                                @if($item['status'] === 'Lunas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-[var(--danger)] border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--danger)] mr-1.5"></span>
                                        Menunggak
                                    </span>
                                @endif
                            </td>

                            {{-- Keterangan / Catatan Admin --}}
                            <td class="py-3 px-3 text-[11px] align-middle" id="cell-catatan-{{ $item['id'] }}">
                                <div class="flex items-start justify-between gap-1.5 group">
                                    <div class="flex-1 min-w-0">
                                        @if(!empty($item['catatan']))
                                            <span id="text-catatan-{{ $item['id'] }}" class="text-[var(--text-heading)] font-medium break-words leading-tight block">
                                                {{ $item['catatan'] }}
                                            </span>
                                            <span id="sub-catatan-{{ $item['id'] }}" class="text-[9.5px] text-[var(--primary-600)] font-semibold block mt-0.5">Catatan Khusus</span>
                                        @else
                                            <span id="text-catatan-{{ $item['id'] }}" class="text-[var(--text-muted)] italic break-words leading-tight block">
                                                {{ $item['metode_asli'] ?? 'Belum Ada Pembayaran' }}
                                            </span>
                                            <span id="sub-catatan-{{ $item['id'] }}" class="text-[9.5px] text-[var(--primary-600)] font-semibold hidden mt-0.5">Catatan Khusus</span>
                                        @endif
                                    </div>
                                    <button type="button" 
                                            onclick="openCatatanModal({{ $item['id'] }}, '{{ addslashes($item['nama']) }}', '{{ $item['nim'] }}')" 
                                            class="shrink-0 p-1.5 rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--primary-700)] hover:bg-[var(--primary-50)] border border-transparent hover:border-[var(--primary-200)] transition cursor-pointer"
                                            title="Kelola Catatan Admin">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="py-12 text-center text-xs text-[var(--text-muted)]">
                                <p class="font-semibold text-[var(--text-heading)]">Tidak ada data mahasiswa ditemukan.</p>
                                <p class="mt-1">Silakan sesuaikan filter program studi, semester, atau kata kunci pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapItems) > 0)
                    <tfoot>
                        <tr class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/40 font-bold border-t border-[var(--border)] text-xs text-[var(--text-heading)]">
                            <td colspan="3" class="py-3.5 px-4 text-right border-r border-[var(--border)] font-semibold">
                                Total ({{ count($rekapItems) }} mahasiswa):
                            </td>
                            @foreach($daftarBulan as $b)
                                <td class="py-3 px-2 text-center border-r border-[var(--border)] text-[var(--text-muted)]">-</td>
                            @endforeach
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] text-[var(--text-muted)]">-</td>
                            <td class="py-3 px-2 text-center border-r border-[var(--border)] text-[var(--danger)] tabular-nums" id="foot-tgk">
                                {{ $rekapItems ? array_sum(array_column($rekapItems, 'bulan_menunggak')) : 0 }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums" id="foot-tagihan">
                                Rp {{ number_format($rekapItems ? array_sum(array_column($rekapItems, 'tagihan')) : 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--primary-600)]" id="foot-dibayar">
                                Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--danger)]" id="foot-sisa">
                                Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="py-3 px-3 text-center text-[var(--primary-600)] tabular-nums" id="foot-persen">
                                {{ $summary['persentase_lunas'] }}% lunas
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>

{{-- Modal Catatan Khusus Rekap SPP --}}
<div id="modalCatatan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeCatatanModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[var(--radius-sm)] bg-[var(--surface)] border border-[var(--border)] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <form id="formCatatan" onsubmit="submitCatatan(event)">
                <input type="hidden" id="catatan_user_id" name="user_id">
                
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[var(--primary-50)] text-[var(--primary-600)] flex items-center justify-center border border-[var(--primary-200)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Catatan Khusus Mahasiswa</h3>
                            <p class="text-[11px] text-[var(--text-muted)]" id="catatan_mhs_info">Mahasiswa</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeCatatanModal()" class="text-[var(--text-muted)] hover:text-[var(--text-heading)] p-1 rounded transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-4">
                    <div class="p-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-muted)] space-y-1">
                        <p><span class="font-semibold text-[var(--text-heading)]">Periode:</span> TA {{ $tahunAkademik }} • Semester {{ ucfirst($tipeSemester) }}</p>
                        <p class="text-[11px]">Catatan ini akan tampil di tabel matriks rekapitulasi serta ikut tercetak saat ekspor PDF & Excel.</p>
                    </div>

                    <div>
                        <label for="catatan_text" class="block text-xs font-semibold text-[var(--text-heading)] mb-1">
                            Tuliskan Catatan / Keterangan Khusus
                        </label>
                        <textarea id="catatan_text" 
                                  name="catatan" 
                                  rows="4" 
                                  maxlength="500"
                                  placeholder="Contoh: Lunas sampai Februari untuk pembuatan yayasan, atau Kurang 150rb transfer menyusul..." 
                                  class="w-full px-3 py-2 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] placeholder:text-[var(--text-muted)] leading-relaxed"></textarea>
                        <p class="text-[10px] text-[var(--text-muted)] mt-1 flex justify-between">
                            <span>Kosongkan catatan lalu simpan (atau klik tombol Hapus) untuk mengembalikan ke keterangan default.</span>
                            <span id="charCount">0/500</span>
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-[var(--border)] bg-[var(--bg-page)] flex items-center justify-between">
                    <button type="button" 
                            id="btnHapusCatatan"
                            onclick="hapusCatatan()" 
                            class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-[var(--danger)] border border-rose-200 rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus Catatan</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeCatatanModal()" class="px-3.5 py-2 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--bg-page)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" id="btnSimpanCatatan" class="px-4 py-2 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Catatan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showToast(message, isSuccess = true) {
        var container = document.getElementById('toastContainer');
        var toast = document.createElement('div');
        toast.className = 'pointer-events-auto p-3.5 rounded-[var(--radius-xs)] border shadow-lg text-xs font-semibold flex items-center gap-2 max-w-sm transition-all duration-300 ' +
            (isSuccess 
                ? 'bg-[var(--surface)] text-[var(--primary-700)] border-[var(--primary-300)]' 
                : 'bg-rose-50 text-[var(--danger)] border-rose-200');

        var icon = isSuccess
            ? '<svg class="w-4 h-4 text-[var(--primary-600)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
            : '<svg class="w-4 h-4 text-[var(--danger)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

        toast.innerHTML = icon + '<span>' + message + '</span>';
        container.appendChild(toast);

        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(function() { toast.remove(); }, 300);
        }, 4000);
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function recalculateSummaryTotals() {
        var rows = document.querySelectorAll('.mhs-row');
        if (!rows.length) return;

        var totalTgk = 0;
        var totalTagihan = 0;
        var totalDibayar = 0;
        var totalSisa = 0;
        var totalPotensi = 0;
        var countLunas = 0;
        var countMenunggak = 0;

        rows.forEach(function(row) {
            var nominalSpp = parseInt(row.dataset.nominalSpp) || 0;
            var tgk = parseInt(row.dataset.bulanMenunggak) || 0;
            var tagihan = parseInt(row.dataset.tagihan) || 0;
            var dibayar = parseInt(row.dataset.dibayar) || 0;
            var sisa = parseInt(row.dataset.sisa) || 0;
            var status = row.dataset.status;

            totalTgk += tgk;
            totalTagihan += tagihan;
            totalDibayar += dibayar;
            totalSisa += sisa;
            totalPotensi += (6 * nominalSpp);

            if (status === 'Lunas') {
                countLunas++;
            } else {
                countMenunggak++;
            }
        });

        var persen = totalPotensi > 0 ? (Math.round((totalDibayar / totalPotensi) * 1000) / 10) : 0;

        // Update Top Summary Cards
        var sumTerkumpul = document.getElementById('sumTerkumpul');
        if (sumTerkumpul) sumTerkumpul.textContent = formatRupiah(totalDibayar);

        var sumLunasSub = document.getElementById('sumLunasSub');
        if (sumLunasSub) sumLunasSub.textContent = countLunas + ' mahasiswa lunas';

        var sumTunggakan = document.getElementById('sumTunggakan');
        if (sumTunggakan) sumTunggakan.textContent = formatRupiah(totalSisa);

        var sumMenunggakSub = document.getElementById('sumMenunggakSub');
        if (sumMenunggakSub) sumMenunggakSub.textContent = countMenunggak + ' belum lunas';

        var sumPersen = document.getElementById('sumPersen');
        if (sumPersen) sumPersen.textContent = persen + '%';

        var sumPersenBar = document.getElementById('sumPersenBar');
        if (sumPersenBar) sumPersenBar.style.width = Math.min(100, persen) + '%';

        // Update Footer Table
        var footTgk = document.getElementById('foot-tgk');
        if (footTgk) footTgk.textContent = totalTgk;

        var footTagihan = document.getElementById('foot-tagihan');
        if (footTagihan) footTagihan.textContent = formatRupiah(totalTagihan);

        var footDibayar = document.getElementById('foot-dibayar');
        if (footDibayar) footDibayar.textContent = formatRupiah(totalDibayar);

        var footSisa = document.getElementById('foot-sisa');
        if (footSisa) footSisa.textContent = formatRupiah(totalSisa);

        var footPersen = document.getElementById('foot-persen');
        if (footPersen) footPersen.textContent = persen + '% lunas';
    }

    function handleCheckboxToggle(userId, monthKey, isHistorisAlready, cb) {
        var targetAction = cb.checked ? 'check' : 'uncheck';

        if (!cb.checked && !isHistorisAlready) {
            alert('Pembayaran ini merupakan pembayaran via verifikasi transfer bank. Pembatalan harus dilakukan melalui menu Verifikasi Pembayaran.');
            cb.checked = true;
            return;
        }

        if (!cb.checked && isHistorisAlready) {
            if (!confirm('Apakah Anda yakin ingin membatalkan status LUNAS historis pada bulan ini dan mengembalikannya ke TUNGGAKAN?')) {
                cb.checked = true;
                return;
            }
        }

        var cell = document.getElementById('cell-' + userId + '-' + monthKey);
        var originalContent = cell.innerHTML;
        cell.innerHTML = '<span class="inline-block animate-spin text-[var(--primary-600)]">⟳</span>';

        fetch('{{ route("backend.keuangan.rekap-spp.toggle-historis") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                month_key: monthKey,
                action: targetAction,
                tahun_akademik: '{{ $tahunAkademik }}',
                tipe_semester: '{{ $tipeSemester }}'
            })
        })
        .then(function(res) {
            return res.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast(data.message, true);

                if (data.is_lunas) {
                    var kwitansiBtn = '';
                    if (data.kwitansi_url) {
                        kwitansiBtn = '<a href="' + data.kwitansi_url + '" target="_blank" class="inline-flex items-center gap-0.5 text-[9.5px] font-mono font-semibold text-[var(--primary-600)] hover:underline" title="Buka / Unduh Kuitansi">' +
                            '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                            '<span>Kuitansi</span></a>';
                    }

                    cell.innerHTML = '<div class="flex flex-col items-center justify-center gap-1">' +
                        '<label class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-300)] text-[10.5px] font-bold cursor-pointer hover:bg-[var(--primary-100)] transition" title="Status: Lunas Historis. Klik untuk membatalkan.">' +
                            '<input type="checkbox" checked onchange="handleCheckboxToggle(' + userId + ', \'' + monthKey + '\', true, this)" class="w-3.5 h-3.5 rounded-[2px] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer">' +
                            '<span>✔ Lunas</span>' +
                            '<span class="text-[9px] px-1 bg-[var(--secondary-100)] text-[var(--secondary-800)] rounded-[2px] font-semibold">Hist</span>' +
                        '</label>' + kwitansiBtn +
                    '</div>';
                } else {
                    cell.innerHTML = '<div class="flex flex-col items-center justify-center">' +
                        '<label class="inline-flex items-center gap-1.5 p-1 rounded-[var(--radius-xs)] hover:bg-[var(--primary-50)] border border-transparent hover:border-[var(--primary-200)] cursor-pointer transition text-[var(--text-muted)] hover:text-[var(--primary-700)]" title="Centang untuk menandai Lunas Historis">' +
                            '<input type="checkbox" onchange="handleCheckboxToggle(' + userId + ', \'' + monthKey + '\', false, this)" class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer">' +
                            '<span class="text-[10px] font-medium">-</span>' +
                        '</label>' +
                    '</div>';
                }

                // Update Row Statistics in DOM
                if (data.row) {
                    var rowEl = document.getElementById('row-mhs-' + userId);
                    if (rowEl) {
                        rowEl.dataset.bulanMenunggak = data.row.bulan_menunggak;
                        rowEl.dataset.tagihan = data.row.tagihan;
                        rowEl.dataset.dibayar = data.row.total_dibayar;
                        rowEl.dataset.sisa = data.row.sisa_tunggakan;
                        rowEl.dataset.status = data.row.status;
                    }

                    // 1. Tgk
                    var tgkEl = document.getElementById('mhs-tgk-' + userId);
                    if (tgkEl) {
                        tgkEl.textContent = data.row.bulan_menunggak;
                        if (data.row.bulan_menunggak > 0) {
                            tgkEl.className = 'py-3 px-2 text-center border-r border-[var(--border)] tabular-nums font-semibold text-[var(--danger)]';
                        } else {
                            tgkEl.className = 'py-3 px-2 text-center border-r border-[var(--border)] tabular-nums font-semibold text-[var(--primary-600)]';
                        }
                    }

                    // 2. Tagihan
                    var tagihanEl = document.getElementById('mhs-tagihan-' + userId);
                    if (tagihanEl) {
                        tagihanEl.textContent = data.row.tagihan_formatted;
                    }

                    // 3. Dibayar
                    var dibayarEl = document.getElementById('mhs-dibayar-' + userId);
                    if (dibayarEl) {
                        dibayarEl.textContent = data.row.dibayar_formatted;
                    }

                    // 4. Sisa
                    var sisaEl = document.getElementById('mhs-sisa-' + userId);
                    if (sisaEl) {
                        sisaEl.textContent = data.row.sisa_formatted;
                        if (data.row.sisa_tunggakan > 0) {
                            sisaEl.className = 'py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--danger)] font-semibold';
                        } else {
                            sisaEl.className = 'py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--text-muted)]';
                        }
                    }

                    // 5. Status
                    var statusEl = document.getElementById('mhs-status-' + userId);
                    if (statusEl) {
                        if (data.row.status === 'Lunas') {
                            statusEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">' +
                                '<span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>' +
                                'Lunas</span>';
                        } else {
                            statusEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-[var(--danger)] border border-rose-200">' +
                                '<span class="w-1.5 h-1.5 rounded-full bg-[var(--danger)] mr-1.5"></span>' +
                                'Menunggak</span>';
                        }
                    }

                    // Recalculate Totals
                    recalculateSummaryTotals();
                }
            } else {
                showToast(data.message || 'Terjadi kesalahan.', false);
                cell.innerHTML = originalContent;
            }
        })
        .catch(function(err) {
            showToast('Gagal memproses permintaan: ' + err.message, false);
            cell.innerHTML = originalContent;
        });
    }

    // --- Catatan Khusus Modal & AJAX ---
    function openCatatanModal(userId, nama, nim) {
        var row = document.getElementById('row-mhs-' + userId);
        var catatan = row ? (row.dataset.catatan || '') : '';
        
        document.getElementById('catatan_user_id').value = userId;
        document.getElementById('catatan_mhs_info').textContent = nama + ' (' + nim + ')';
        var textarea = document.getElementById('catatan_text');
        textarea.value = catatan;
        document.getElementById('charCount').textContent = catatan.length + '/500';
        
        var btnHapus = document.getElementById('btnHapusCatatan');
        if (catatan.trim() === '') {
            btnHapus.style.display = 'none';
        } else {
            btnHapus.style.display = 'inline-flex';
        }
        
        document.getElementById('modalCatatan').classList.remove('hidden');
        setTimeout(function() {
            textarea.focus();
        }, 100);
    }

    function closeCatatanModal() {
        document.getElementById('modalCatatan').classList.add('hidden');
    }

    function submitCatatan(event) {
        event.preventDefault();
        var userId = document.getElementById('catatan_user_id').value;
        var catatanText = document.getElementById('catatan_text').value;
        var btnSimpan = document.getElementById('btnSimpanCatatan');
        var originalBtnText = btnSimpan.innerHTML;
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="inline-block animate-spin mr-1">⟳</span> Menyimpan...';
        
        fetch('{{ route("backend.keuangan.rekap-spp.catatan.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                catatan: catatanText,
                tahun_akademik: '{{ $tahunAkademik }}',
                tipe_semester: '{{ $tipeSemester }}'
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = originalBtnText;
            if (data.success) {
                showToast(data.message, true);
                closeCatatanModal();
                updateCatatanCell(userId, data.catatan);
            } else {
                showToast(data.message || 'Gagal menyimpan catatan.', false);
            }
        })
        .catch(function(err) {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = originalBtnText;
            showToast('Terjadi kesalahan: ' + err.message, false);
        });
    }

    function hapusCatatan() {
        if (!confirm('Apakah Anda yakin ingin menghapus catatan khusus untuk mahasiswa ini?')) {
            return;
        }
        var userId = document.getElementById('catatan_user_id').value;
        var btnHapus = document.getElementById('btnHapusCatatan');
        btnHapus.disabled = true;
        
        fetch('{{ route("backend.keuangan.rekap-spp.catatan.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                catatan: '',
                tahun_akademik: '{{ $tahunAkademik }}',
                tipe_semester: '{{ $tipeSemester }}'
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btnHapus.disabled = false;
            if (data.success) {
                showToast('Catatan berhasil dihapus.', true);
                closeCatatanModal();
                updateCatatanCell(userId, '');
            } else {
                showToast(data.message || 'Gagal menghapus catatan.', false);
            }
        })
        .catch(function(err) {
            btnHapus.disabled = false;
            showToast('Terjadi kesalahan: ' + err.message, false);
        });
    }

    function updateCatatanCell(userId, newCatatan) {
        var row = document.getElementById('row-mhs-' + userId);
        if (!row) return;
        row.dataset.catatan = newCatatan || '';
        var textEl = document.getElementById('text-catatan-' + userId);
        var subEl = document.getElementById('sub-catatan-' + userId);
        var metodeAsli = row.dataset.metodeAsli || 'Belum Ada Pembayaran';
        
        if (newCatatan && newCatatan.trim() !== '') {
            textEl.textContent = newCatatan;
            textEl.className = 'text-[var(--text-heading)] font-medium break-words leading-tight block';
            if (subEl) {
                subEl.textContent = 'Catatan Khusus';
                subEl.className = 'text-[9.5px] text-[var(--primary-600)] font-semibold block mt-0.5';
                subEl.classList.remove('hidden');
            }
        } else {
            textEl.textContent = metodeAsli;
            textEl.className = 'text-[var(--text-muted)] italic break-words leading-tight block';
            if (subEl) {
                subEl.classList.add('hidden');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var catatanInput = document.getElementById('catatan_text');
        if (catatanInput) {
            catatanInput.addEventListener('input', function() {
                var charCount = document.getElementById('charCount');
                if (charCount) charCount.textContent = this.value.length + '/500';
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('modalCatatan');
                if (modal && !modal.classList.contains('hidden')) {
                    closeCatatanModal();
                }
            }
        });
    });
</script>
@endsection
