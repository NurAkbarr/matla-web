@extends('layouts.keuangan')

@section('title', 'Rekapitulasi SPP per Semester')

@section('content')
<div class="space-y-6">

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
                Lembar kerja matriks pembayaran SPP 6 bulan, kalkulasi tunggakan, dan status kelunasan mahasiswa.
            </p>
        </div>

        {{-- Action Buttons: Ekspor Excel & PDF (Tinggi 40px, Radius 4px) --}}
        <div class="flex items-center gap-2.5 shrink-0">
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
                <p class="text-xl font-bold font-display text-[var(--primary-600)] tabular-nums mt-1">
                    Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--primary-600)] mt-1.5 tabular-nums">{{ $summary['mahasiswa_lunas'] }} mahasiswa lunas</p>
        </div>

        {{-- Card 4: Sisa Tunggakan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--danger)]">Sisa tunggakan</p>
                <p class="text-xl font-bold font-display text-[var(--danger)] tabular-nums mt-1">
                    Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}
                </p>
            </div>
            <p class="text-[11px] text-[var(--danger)] mt-1.5 tabular-nums">{{ $summary['mahasiswa_menunggak'] }} belum lunas</p>
        </div>

        {{-- Card 5: Persentase Kelunasan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Tingkat kelunasan</p>
                <p class="text-xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1">
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

    {{-- Matriks Tabel Rekap SPP --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        
        <div class="px-5 py-3.5 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <div>
                <h2 class="text-xs font-bold font-display text-[var(--text-heading)]">Matriks pembayaran SPP 6 bulan</h2>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Status verifikasi pelunasan SPP bulanan per mahasiswa</p>
            </div>
            <span class="text-xs font-semibold text-[var(--text-muted)] tabular-nums">
                TA {{ $tahunAkademik }} • {{ ucfirst($tipeSemester) }}
            </span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 text-[var(--text-body)] font-semibold text-[12.5px] border-b border-[var(--border)]">
                        <th class="py-3 px-3 text-center border-r border-[var(--border)] w-10">No</th>
                        <th class="py-3 px-3 text-center border-r border-[var(--border)] w-24">NIM</th>
                        <th class="py-3 px-4 border-r border-[var(--border)] min-w-[190px]">Mahasiswa & prodi</th>
                        
                        {{-- 6 Kolom Bulan --}}
                        @foreach($daftarBulan as $b)
                            <th class="py-3 px-2 text-center border-r border-[var(--border)] w-16">
                                <span class="block font-bold text-xs text-[var(--text-heading)]">{{ substr($b['nama'], 0, 3) }}</span>
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
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
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

                            {{-- 6 Bulan Semester --}}
                            @foreach($item['matriks_bulan'] as $m)
                                <td class="py-3 px-2 text-center border-r border-[var(--border)]">
                                    @if($m['is_lunas'])
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)] text-[10px] font-semibold" title="Lunas">
                                            Lunas
                                        </span>
                                    @elseif($m['ada_tagihan'] && $m['nominal_bayar'] > 0)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)] text-[10px] font-semibold" title="Sebagian">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="text-[var(--text-muted)]/50 font-medium">-</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Tarif SPP --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] font-semibold text-[var(--text-heading)] tabular-nums">
                                Rp {{ number_format($item['nominal_spp'], 0, ',', '.') }}
                            </td>

                            {{-- Bulan Menunggak --}}
                            <td class="py-3 px-2 text-center border-r border-[var(--border)] tabular-nums font-semibold {{ $item['bulan_menunggak'] > 0 ? 'text-[var(--danger)]' : 'text-[var(--primary-600)]' }}">
                                {{ $item['bulan_menunggak'] }}
                            </td>

                            {{-- Total Tagihan --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums text-[var(--text-heading)]">
                                Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}
                            </td>

                            {{-- Total Sudah Dibayar --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums font-semibold text-[var(--primary-600)]">
                                Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}
                            </td>

                            {{-- Sisa Tunggakan --}}
                            <td class="py-3 px-3 text-right border-r border-[var(--border)] tabular-nums font-bold {{ $item['sisa_tunggakan'] > 0 ? 'text-[var(--danger)]' : 'text-[var(--text-muted)]' }}">
                                Rp {{ number_format($item['sisa_tunggakan'], 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="py-3 px-3 text-center border-r border-[var(--border)]">
                                @if($item['status'] === 'Lunas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-rose-50 text-[var(--danger)] border border-rose-200">
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
@endsection
