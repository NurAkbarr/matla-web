@extends('layouts.keuangan')

@section('title', 'Dashboard Keuangan')

@section('content')
<div class="space-y-6">

    {{-- 1. Status Ringkas (Sentence case, no selling banner) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-1 gap-2">
        <div>
            <h1 class="text-xl md:text-2xl font-bold font-display text-[var(--text-heading)]">Dashboard keuangan</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Ringkasan arus kas, pemantauan tagihan, dan rekonsiliasi pembayaran kampus.</p>
        </div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-xs)] text-xs font-medium text-[var(--primary-600)] bg-[var(--primary-50)] border border-[var(--primary-200)] self-start sm:self-auto">
            <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)]"></span>
            <span>Sistem aktif</span>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 2. TIGA KARTU RINGKASAN (Border 1px, Tanpa Bayangan, 32px Font)   --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Kartu 1: Saldo Utama (Tenang) --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Saldo utama</p>
                <p class="text-[32px] font-bold font-display text-[var(--text-heading)] tabular-nums leading-tight mt-2">
                    Rp {{ number_format($saldoAkun, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-3">Kas aktif universitas</p>
        </div>

        {{-- Kartu 2: Total Tagihan (MENONJOL - Paling butuh tindakan) --}}
        <div class="bg-[var(--surface)] border-2 border-[var(--secondary-500)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between relative">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-[var(--secondary-700)] dark:text-[var(--secondary-400)]">Tagihan belum lunas</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-bold bg-[var(--secondary-50)] dark:bg-[var(--secondary-100)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)] tabular-nums">
                        {{ $jumlahTagihan }} tertunda
                    </span>
                </div>
                <p class="text-[32px] font-bold font-display text-[var(--text-heading)] tabular-nums leading-tight mt-2">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[var(--border)]">
                <span class="text-xs text-[var(--text-muted)]">Memerlukan penagihan aktif</span>
                <a href="{{ route('backend.keuangan.tagihan.index') }}" class="text-xs font-bold text-[var(--secondary-700)] dark:text-[var(--secondary-400)] hover:underline focus:outline-none">
                    Kelola tagihan
                </a>
            </div>
        </div>

        {{-- Kartu 3: Riwayat Transaksi (Tenang) --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-[var(--text-muted)]">Riwayat transaksi</p>
                <p class="text-[32px] font-bold font-display text-[var(--text-heading)] tabular-nums leading-tight mt-2">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[var(--border)]">
                <span class="text-xs text-[var(--text-muted)]">Arus masuk dan keluar</span>
                <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" class="text-xs font-semibold text-[var(--primary-500)] hover:underline">
                    Buka buku kas
                </a>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 3. LAPORAN KEUANGAN BULANAN (Satu Panel dengan 4 Kolom Pembagi)   --}}
    {{-- ================================================================= --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 md:p-6 space-y-6">
        
        {{-- Header Panel Laporan & Pemilih Bulan --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[var(--border)]">
            <div>
                <h2 class="text-base font-bold font-display text-[var(--text-heading)]">Laporan keuangan bulanan</h2>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">
                    Ringkasan penerimaan, beban operasional, dan sisa kewajiban periode <span class="font-semibold text-[var(--primary-500)]">{{ $selectedLabel }}</span>
                </p>
            </div>

            {{-- Pemilih Bulan --}}
            <form method="GET" action="{{ route('backend.keuangan.dashboard') }}" class="flex items-center gap-2">
                <div class="relative inline-flex items-center w-full sm:w-auto">
                    <select name="periode" onchange="this.form.submit()" class="w-full sm:w-auto appearance-none h-10 pl-3.5 pr-8 text-xs font-semibold text-[var(--text-heading)] bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] focus:outline-none focus:border-[var(--primary-500)] cursor-pointer">
                        @foreach($daftarBulan as $bulanOption)
                            <option value="{{ $bulanOption['value'] }}" {{ $selectedPeriode === $bulanOption['value'] ? 'selected' : '' }}>
                                {{ $bulanOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute right-2.5 pointer-events-none text-[var(--text-muted)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </form>
        </div>

        {{-- SATU PANEL DENGAN 4 KOLOM DIPISAHKAN PEMBAGI VERTIKAL 1PX (Tanpa Ikon Pastel) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-[var(--border)] border border-[var(--border)] rounded-[var(--radius-xs)] overflow-hidden bg-[var(--surface)]">
            {{-- Kolom 1: Total Pemasukan SPP --}}
            <div class="p-5">
                <p class="text-xs font-medium text-[var(--text-muted)]">Total pemasukan SPP</p>
                <p class="text-xl lg:text-2xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1.5">
                    Rp {{ number_format($pemasukanSpp, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[var(--text-muted)] mt-1">Pembayaran terverifikasi</p>
            </div>

            {{-- Kolom 2: Pemasukan Lain & Saldo Lalu --}}
            <div class="p-5">
                <p class="text-xs font-medium text-[var(--text-muted)]">Pemasukan lain & saldo lalu</p>
                <p class="text-xl lg:text-2xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1.5">
                    Rp {{ number_format($pemasukanLain, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[var(--text-muted)] mt-1">Non-SPP dan saldo lampau</p>
            </div>

            {{-- Kolom 3: Total Pengeluaran --}}
            <div class="p-5">
                <p class="text-xs font-medium text-[var(--text-muted)]">Total pengeluaran</p>
                <p class="text-xl lg:text-2xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1.5">
                    Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[var(--text-muted)] mt-1">Beban operasional</p>
            </div>

            {{-- Kolom 4: Tunggakan SPP (Minus & Danger) --}}
            <div class="p-5">
                <p class="text-xs font-medium text-[var(--danger)]">Tunggakan SPP (minus)</p>
                <p class="text-xl lg:text-2xl font-bold font-display text-[var(--danger)] tabular-nums mt-1.5">
                    -Rp {{ number_format($tunggakanSpp, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[var(--text-muted)] mt-1">Belum dilunasi mahasiswa</p>
            </div>
        </div>

        {{-- Baris Total Keseluruhan --}}
        <div class="rounded-[var(--radius-xs)] border border-[var(--border)] p-5 md:p-6 bg-[var(--primary-900)] text-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-medium text-[var(--primary-200)]">Kalkulasi bersih periode {{ $selectedLabel }}</span>
                    <h3 class="text-base font-bold font-display text-white mt-0.5">Total keseluruhan</h3>
                    <p class="text-xs text-white/70 font-mono mt-1">
                        (Pemasukan SPP + Pemasukan lain) - Pengeluaran
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-3xl lg:text-4xl font-extrabold font-display tabular-nums text-white">
                        Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                    </p>
                    <div class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-[var(--radius-xs)] text-xs font-semibold {{ $totalKeseluruhan >= 0 ? 'bg-[var(--primary-800)] text-emerald-200 border border-[var(--primary-600)]' : 'bg-rose-950/80 text-rose-200 border border-rose-800' }}">
                        <span>{{ $totalKeseluruhan >= 0 ? 'Surplus kas operasional' : 'Defisit kas operasional' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Statistik Mahasiswa (3 Kolom Tenang, Tanpa Ikon Pastel) --}}
        <div class="pt-2">
            <h3 class="text-xs font-semibold text-[var(--text-muted)] mb-3">Statistik mahasiswa periode {{ $selectedLabel }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- 1. Jumlah Mahasiswa --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-4">
                    <p class="text-xs font-medium text-[var(--text-muted)]">Jumlah mahasiswa aktif</p>
                    <p class="text-xl font-bold font-display text-[var(--text-heading)] tabular-nums mt-1">
                        {{ $totalMahasiswaAktif }} <span class="text-xs font-normal text-[var(--text-muted)]">orang</span>
                    </p>
                    <p class="text-[11px] text-[var(--text-muted)] mt-1">Total basis data</p>
                </div>

                {{-- 2. Mahasiswa Menunggak --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-4">
                    <p class="text-xs font-medium text-[var(--secondary-700)] dark:text-[var(--secondary-400)]">Mahasiswa menunggak</p>
                    <p class="text-xl font-bold font-display text-[var(--secondary-700)] dark:text-[var(--secondary-400)] tabular-nums mt-1">
                        {{ $mahasiswaMenunggak }} <span class="text-xs font-normal text-[var(--text-muted)]">orang</span>
                    </p>
                    <p class="text-[11px] text-[var(--text-muted)] mt-1">
                        @if(($totalMahasiswaBertagihan ?? 0) > 0)
                            {{ round(($mahasiswaMenunggak / $totalMahasiswaBertagihan) * 100) }}% memiliki tagihan aktif
                        @else
                            Tidak ada tagihan aktif
                        @endif
                    </p>
                </div>

                {{-- 3. Mahasiswa Lunas --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-4">
                    <p class="text-xs font-medium text-[var(--primary-600)]">Mahasiswa lunas</p>
                    <p class="text-xl font-bold font-display text-[var(--primary-600)] tabular-nums mt-1">
                        {{ $mahasiswaLunas }} <span class="text-xs font-normal text-[var(--text-muted)]">orang</span>
                    </p>
                    <p class="text-[11px] text-[var(--text-muted)] mt-1">
                        @if(($totalMahasiswaBertagihan ?? 0) > 0)
                            {{ round(($mahasiswaLunas / $totalMahasiswaBertagihan) * 100) }}% bebas tanggungan periode ini
                        @else
                            Tidak ada tagihan
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- 4. SECTION: RIWAYAT TRANSAKSI TERKINI + KALENDER                  --}}
    {{-- ================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Riwayat Transaksi Terkini (2 Kolom) --}}
        <div class="lg:col-span-2">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 md:p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-[var(--border)] mb-4">
                        <div>
                            <h3 class="text-base font-bold font-display text-[var(--text-heading)]">Riwayat transaksi terkini</h3>
                            <p class="text-xs text-[var(--text-muted)]">Aktivitas arus kas dan pembayaran terverifikasi terbaru</p>
                        </div>
                        <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" class="text-xs font-semibold text-[var(--primary-500)] hover:underline">
                            Lihat semua
                        </a>
                    </div>

                    <div class="divide-y divide-[var(--border)]">
                        @forelse($riwayatTerkini as $riwayat)
                            @php
                                $isTxKeuangan = ($riwayat instanceof \App\Models\TransaksiKeuangan);
                                $isKeluar = $isTxKeuangan ? $riwayat->isKeluar() : false;
                                $subjek = $riwayat->user->name ?? ($riwayat->subjek ?? 'Transaksi kas');
                                $initial = strtoupper(substr($subjek, 0, 1));
                                $keterangan = $isTxKeuangan 
                                    ? ($riwayat->kategori . ($riwayat->deskripsi ? ' • ' . \Illuminate\Support\Str::limit($riwayat->deskripsi, 35) : ''))
                                    : ($riwayat->tagihan->nama_tagihan ?? 'Pembayaran');
                                $timeObj = $isTxKeuangan ? $riwayat->tanggal_transaksi : $riwayat->updated_at;
                            @endphp
                            <div class="flex items-center justify-between py-3.5 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3 min-w-0 pr-4">
                                    {{-- Uniform neutral-green initial avatar (No random pastel colors) --}}
                                    <div class="w-9 h-9 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[var(--text-heading)] truncate">{{ $subjek }}</p>
                                        <p class="text-[11px] text-[var(--text-muted)] truncate mt-0.5">
                                            {{ $keterangan }}
                                            @if(!$isTxKeuangan && !empty($riwayat->user->education['program_studi']))
                                                <span>• {{ $riwayat->user->education['program_studi'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-bold {{ $isKeluar ? 'text-[var(--danger)]' : 'text-[var(--primary-600)]' }} tabular-nums">
                                        {{ ($isKeluar ? '- Rp ' : '+ Rp ') . number_format($riwayat->nominal, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5 tabular-nums">
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
                            <div class="py-8 text-center text-xs text-[var(--text-muted)]">
                                Belum ada riwayat transaksi pembayaran.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[var(--border)] text-center">
                    <a href="{{ route('backend.keuangan.verifikasi.index') }}" class="inline-flex items-center justify-center h-10 px-4 rounded-[var(--radius-xs)] border border-[var(--border)] text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--primary-50)] hover:text-[var(--primary-600)] transition-colors">
                        Buka verifikasi pembayaran
                    </a>
                </div>
            </div>
        </div>

        {{-- Kalender Akademik & Keuangan (1 Kolom) --}}
        <div class="lg:col-span-1">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 md:p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="pb-4 border-b border-[var(--border)] mb-4">
                        <h3 class="text-base font-bold font-display text-[var(--text-heading)]">Kalender akademik</h3>
                        <p class="text-xs text-[var(--text-muted)]">Jadwal tanggal masehi dan hijriah</p>
                    </div>

                    <div class="text-center mb-5 p-3 bg-[var(--bg-page)] rounded-[var(--radius-xs)] border border-[var(--border)]">
                        <p class="text-xs font-bold text-[var(--primary-600)]" id="hijri-date">Memuat kalender hijriah...</p>
                        <p class="text-[11px] text-[var(--text-muted)] mt-0.5" id="gregorian-date">Memuat tanggal...</p>
                    </div>

                    <div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-2">
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Sen</div>
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Sel</div>
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Rab</div>
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Kam</div>
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Jum</div>
                            <div class="text-[11px] font-semibold text-[var(--text-muted)] py-1">Sab</div>
                            <div class="text-[11px] font-semibold text-[var(--danger)] py-1">Min</div>
                        </div>
                        <div id="mini-calendar" class="grid grid-cols-7 gap-y-1.5 text-center text-xs text-[var(--text-body)]">
                            <!-- JS fills calendar -->
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
    
    var gregorianEl = document.getElementById('gregorian-date');
    if (gregorianEl) {
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        gregorianEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    
    var hijriEl = document.getElementById('hijri-date');
    if (hijriEl) {
        try {
            var hijriDate = new Date(now);
            hijriDate.setDate(hijriDate.getDate() - 1);
            var hijriOptions = { day: 'numeric', month: 'long', year: 'numeric' };
            var hijriFormatter = new Intl.DateTimeFormat('id-ID-u-ca-islamic', hijriOptions);
            var formattedHijri = hijriFormatter.format(hijriDate);
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
    firstDay = (firstDay + 6) % 7;
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var prevMonthDays = new Date(year, month, 0).getDate();

    var html = '';

    for (var i = firstDay - 1; i >= 0; i--) {
        html += '<div class="py-1 text-[var(--text-muted)]/40 font-normal">' + (prevMonthDays - i) + '</div>';
    }

    for (var d = 1; d <= daysInMonth; d++) {
        var dayIndex = (firstDay + d - 1) % 7;
        var isSunday = (dayIndex === 6);

        if (d === today) {
            html += '<div class="py-0.5"><div class="bg-[var(--primary-500)] text-white rounded-[var(--radius-xs)] w-7 h-7 flex items-center justify-center mx-auto text-xs font-bold">' + d + '</div></div>';
        } else if (isSunday) {
            html += '<div class="py-0.5 text-[var(--danger)] font-medium rounded-[var(--radius-xs)] w-7 h-7 flex items-center justify-center mx-auto">' + d + '</div>';
        } else {
            html += '<div class="py-0.5 text-[var(--text-body)] font-medium hover:bg-[var(--primary-50)] rounded-[var(--radius-xs)] w-7 h-7 flex items-center justify-center mx-auto transition-colors">' + d + '</div>';
        }
    }

    var totalCells = firstDay + daysInMonth;
    var remaining = (7 - (totalCells % 7)) % 7;
    for (var n = 1; n <= remaining; n++) {
        html += '<div class="py-1 text-[var(--text-muted)]/40 font-normal">' + n + '</div>';
    }

    calEl.innerHTML = html;
})();
</script>
@endpush
