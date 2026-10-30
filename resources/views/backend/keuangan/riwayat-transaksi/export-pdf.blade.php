@php
    $logoBase64 = 'data:image/png;base64,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';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Kas & Riwayat Transaksi - MATLA</title>
    <style>
        @page {
            margin: 1.5cm 1.2cm 1.5cm 1.2cm;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.35;
        }

        /* HEADER */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 70px;
            text-align: left;
        }
        .kop-logo img {
            max-width: 65px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            padding-right: 70px;
        }
        .kop-text h1 {
            font-size: 14.5pt;
            margin: 0 0 3px 0;
            color: #00703C;
            font-family: 'Times New Roman', serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 8.5pt;
            margin: 2px 0;
            color: #334155;
            font-family: 'Times New Roman', serif;
        }

        .divider {
            border-top: 2.5px solid #00703C;
            border-bottom: 1px solid #00703C;
            height: 2px;
            margin: 8px 0 14px 0;
        }

        /* TITLE */
        .title-block {
            text-align: center;
            margin-bottom: 12px;
        }
        .title-block h2 {
            font-size: 13pt;
            margin: 0;
            color: #00703C;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .title-block .meta {
            font-size: 8pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* SUMMARY BAR */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .summary-box {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            background-color: #f8fafc;
        }
        .summary-title {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .summary-val {
            font-size: 12pt;
            font-weight: bold;
        }

        /* MAIN TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #00703C;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000000;
            padding: 6px 4px;
            font-size: 8pt;
            text-transform: uppercase;
        }
        .data-table td {
            border: 1px solid #000000;
            vertical-align: middle;
            padding: 5px 6px;
            color: #000000;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .bg-masuk {
            background-color: #f0fdf4 !important;
            color: #166534 !important;
            font-weight: bold;
        }
        .bg-keluar {
            background-color: #fef2f2 !important;
            color: #991b1b !important;
            font-weight: bold;
        }

        /* SIGNATURE */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
        }
    </style>
</head>
<body>

    {{-- KOP KAMPUS --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ $logoBase64 }}" alt="Logo MATLA">
            </td>
            <td class="kop-text">
                <h1>KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)</h1>
                <p>Jl. H. Basir Pondok Kacang Barat, Gg. Mushola, Pondok Kacang Barat, Kec. Pondok Aren, Kota Tangerang Selatan, Banten</p>
                <p>No. Hp. 081273510994, Email: matlaislamicuniversity@gmail.com</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="title-block">
        <h2>Laporan Mutasi Kas & Riwayat Transaksi Keuangan</h2>
        <div class="meta" style="font-weight: bold; color: #1e293b; margin-bottom: 2px;">
            Periode: {{ $periodeLabel ?? 'Semua Periode' }}
        </div>
        <div class="meta">
            Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i:s') }} WIB &bull; Total Baris: {{ $transaksis->count() }} Transaksi
        </div>
    </div>

    {{-- RINGKASAN ARUS KAS --}}
    <table class="summary-table">
        <tr>
            <td style="width: 32%; padding-right: 8px;">
                <div class="summary-box" style="border-left: 4px solid #16a34a; background-color: #f0fdf4;">
                    <div class="summary-title" style="color: #166534;">Total Uang Masuk</div>
                    <div class="summary-val" style="color: #166534;">+ Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 32%; padding: 0 4px;">
                <div class="summary-box" style="border-left: 4px solid #dc2626; background-color: #fef2f2;">
                    <div class="summary-title" style="color: #991b1b;">Total Uang Keluar</div>
                    <div class="summary-val" style="color: #991b1b;">- Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 36%; padding-left: 8px;">
                <div class="summary-box" style="border-left: 4px solid #00703C; background-color: #f8fafc;">
                    <div class="summary-title" style="color: #00703C;">Arus Kas Bersih (Net Cashflow)</div>
                    <div class="summary-val" style="color: {{ $netCashflow >= 0 ? '#00703C' : '#dc2626' }};">
                        Rp {{ number_format($netCashflow, 0, ',', '.') }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA TRANSAKSI --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th style="width: 125px;">Kode Transaksi</th>
                <th style="width: 110px;">Waktu Presisi (Tanggal & Jam)</th>
                <th style="width: 85px;">Jenis Arus Kas</th>
                <th style="width: 100px;">Kategori</th>
                <th>Keterangan / Subjek</th>
                <th style="width: 80px;">Metode</th>
                <th style="width: 115px; text-align: right;">Nominal (Rp)</th>
                <th style="width: 85px;">No. Referensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $tx)
                @php
                    $isMasuk = $tx->isMasuk();
                    $timeStr = $tx->tanggal_transaksi ? $tx->tanggal_transaksi->translatedFormat('d/m/Y H:i:s') : '-';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; text-align: center;">{{ $tx->kode_transaksi }}</td>
                    <td style="text-align: center; font-size: 7.5pt;">{{ $timeStr }} WIB</td>
                    <td style="text-align: center; font-weight: bold; font-size: 7.5pt; color: {{ $isMasuk ? '#166534' : '#991b1b' }};">
                        {{ $isMasuk ? 'Uang Masuk' : 'Uang Keluar' }}
                    </td>
                    <td>{{ $tx->kategori }}</td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">{{ $tx->subjek ?: '-' }}</div>
                        @if($tx->deskripsi && $tx->deskripsi !== $tx->subjek)
                            <div style="font-size: 7pt; color: #475569; margin-top: 1px;">{{ $tx->deskripsi }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $tx->metode_pembayaran ?: 'Transfer Bank' }}</td>
                    <td class="{{ $isMasuk ? 'bg-masuk' : 'bg-keluar' }}" style="text-align: right; white-space: nowrap;">
                        {{ ($isMasuk ? '+ ' : '- ') . number_format($tx->nominal, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center; font-size: 7pt;">{{ $tx->no_referensi ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #64748b;">
                        Tidak ada catatan transaksi pada filter ini.
                    </td>
                </tr>
            @endforelse

            {{-- Summary Rows --}}
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="7" style="text-align: right; padding-right: 10px;">TOTAL TRANSAKSI MASUK:</td>
                <td class="bg-masuk" style="text-align: right;">+ Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="7" style="text-align: right; padding-right: 10px;">TOTAL TRANSAKSI KELUAR:</td>
                <td class="bg-keluar" style="text-align: right;">- Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="7" style="text-align: right; padding-right: 10px;">ARUS KAS BERSIH (NET CASHFLOW):</td>
                <td style="text-align: right; color: {{ $netCashflow >= 0 ? '#166534' : '#991b1b' }};">Rp {{ number_format($netCashflow, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- TANDA TANGAN / PENGESAHAN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 60%; font-size: 8pt; color: #64748b; line-height: 1.6;">
                <strong>Keterangan Audit Mutasi Kas:</strong><br>
                &bull; Laporan ini dicetak secara otomatis dari Buku Kas Modul Sistem Keuangan MATLA University Portal.<br>
                &bull; Data transaksi yang tertera telah terekonsiliasi dan diverifikasi sah oleh Bagian Keuangan Kampus.<br>
                &bull; Dokumen cetakan ini merupakan arsip pembukuan resmi dan sah.
            </td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 4px 0; font-size: 8.5pt; color: #475569;">
                    Tangerang Selatan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <p style="margin: 0; font-size: 9pt; font-weight: bold; color: #0f172a;">
                    Mengetahui / Mengesahkan,<br>
                    <strong>Bendahara / Bagian Keuangan MATLA</strong>
                </p>
                <div style="height: 55px;"></div>
                <p style="margin: 0; font-size: 9.5pt; font-weight: bold; text-decoration: underline; color: #00703C;">
                    {{ auth()->user()->name ?? 'Bendahara Kampus MATLA' }}
                </p>
                <p style="margin: 2px 0 0 0; font-size: 7.5pt; color: #64748b;">
                    NIP / ID: {{ auth()->id() ? 'BDH-' . str_pad(auth()->id(), 3, '0', STR_PAD_LEFT) : 'BENDAHARA-MATLA' }}
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
