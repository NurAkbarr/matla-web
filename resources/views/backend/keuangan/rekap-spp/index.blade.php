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
            <p class="text-[11px] text-[var(--primary-600)] mt-1.5 tabular-nums">{{ $summary['mahasiswa_lunas'] }} mahasiswa lunas</p>
        </div>

        {{-- Card 4: Sisa Tunggakan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--danger)]">Sisa tunggakan</p>
                <p class="text-xl font-bold font-display text-[var(--danger)] tabular-nums mt-1" id="sumTunggakan">
                    Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--danger)] mt-1.5 tabular-nums">{{ $summary['mahasiswa_menunggak'] }} belum lunas</p>
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
                <div class="bg-[var(--primary-500)] h-1.5 rounded-full" style="width: {{ min(100, $summary['persentase_lunas']) }}%"></div>
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
                        <th class="py-3 px-3 min-w-[110px]">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($rekapItems as $index => $item)
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors" id="row-mhs-{{ $item['id'] }}">
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

                            {{-- Total Tagihan --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--text-heading)]">
                                Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}
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

                            {{-- Keterangan --}}
                            <td class="py-3 px-3 text-[11px] text-[var(--text-muted)]">
                                {{ $item['metode'] }}
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
                            <td class="py-3 px-2 text-center border-r border-[var(--border)] text-[var(--danger)] tabular-nums">
                                {{ $rekapItems ? array_sum(array_column($rekapItems, 'bulan_menunggak')) : 0 }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums">
                                Rp {{ number_format($summary['total_potensi'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--primary-600)]">
                                Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--danger)]">
                                Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="py-3 px-3 text-center text-[var(--primary-600)] tabular-nums">
                                {{ $summary['persentase_lunas'] }}% lunas
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
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
                action: targetAction
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
</script>
@endsection
