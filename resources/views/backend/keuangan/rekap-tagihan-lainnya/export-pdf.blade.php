@php
    $logoBase64 = 'data:image/png;base64,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';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Tagihan Lainnya (Non-SPP) - MATLA</title>
    <style>
        @page {
            margin: 1.2cm 1cm 1.2cm 1cm;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #1e293b;
            line-height: 1.3;
        }

        /* HEADER / KOP */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 65px;
            text-align: left;
        }
        .kop-logo img {
            max-width: 60px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            padding-right: 65px;
        }
        .kop-text h1 {
            font-size: 13.5pt;
            margin: 0 0 2px 0;
            color: #00703C;
            font-family: 'Times New Roman', serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 8pt;
            margin: 1px 0;
            color: #334155;
            font-family: 'Times New Roman', serif;
        }

        .divider {
            border-top: 2px solid #00703C;
            border-bottom: 1px solid #00703C;
            height: 1px;
            margin: 6px 0 10px 0;
        }

        /* TITLE */
        .title-block {
            text-align: center;
            margin-bottom: 10px;
        }
        .title-block h2 {
            font-size: 11pt;
            margin: 0;
            color: #00703C;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .title-block .meta {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 3px;
        }

        /* SUMMARY BAR */
        .summary-box {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-collapse: collapse;
        }
        .summary-box td {
            padding: 6px 10px;
            font-size: 8pt;
            vertical-align: middle;
            border-right: 1px solid #e2e8f0;
        }
        .summary-box td:last-child {
            border-right: none;
        }
        .summary-label {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
            font-weight: bold;
        }
        .summary-val {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
        }
        .text-success { color: #166534; }
        .text-danger { color: #991b1b; }

        /* DATA TABLE */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #94a3b8;
            padding: 4px 6px;
        }
        table.data-table th {
            background-color: #00703C;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 7.5pt;
            height: 24px;
        }
        table.data-table td {
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* STATUS BADGE */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 2px;
            font-size: 6.5pt;
            font-weight: bold;
            text-align: center;
        }
        .badge-lunas {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .badge-sebagian {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-belum {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* SIGNATURE */
        .signature-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            font-size: 8pt;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI MATLA --}}
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

    {{-- TITLE & METADATA --}}
    <div class="title-block">
        <h2>LAPORAN REKAPITULASI TAGIHAN LAINNYA (NON-SPP)</h2>
        <div class="meta">
            <span>Kriteria Filter: <strong>{{ $filterLabel }}</strong></span>
            &nbsp;|&nbsp;
            <span>Dicetak pada: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</strong></span>
        </div>
    </div>

    {{-- RINGKASAN DATA (KPI) --}}
    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-label">Total Tagihan</div>
                <div class="summary-val">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total Terkumpul</div>
                <div class="summary-val text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Sisa Tunggakan</div>
                <div class="summary-val text-danger">Rp {{ number_format($totalSisa, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Status Tagihan</div>
                <div style="font-size: 8pt; font-weight: bold; margin-top: 3px;">
                    <span class="text-success">{{ $countLunas }} Lunas</span> | 
                    <span style="color: #b45309;">{{ $countSebagian }} Sebagian</span> | 
                    <span class="text-danger">{{ $countBelumLunas }} Belum</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">NIM</th>
                <th style="width: 140px;">Nama Mahasiswa</th>
                <th style="width: 120px;">Program Studi</th>
                <th style="width: 140px;">Item Tagihan</th>
                <th style="width: 110px;">Keterangan / Tempo</th>
                <th style="width: 85px;">Nominal (Rp)</th>
                <th style="width: 85px;">Dibayar (Rp)</th>
                <th style="width: 85px;">Sisa (Rp)</th>
                <th style="width: 75px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i => $t)
                @php
                    $nom = (float) $t->nominal_total;
                    $sisa = (float) $t->sisa_tagihan;
                    $dibayar = max(0, $nom - $sisa);

                    $isLunas = ($sisa <= 0 || strtolower($t->status) === 'lunas');
                    $isSebagian = (!$isLunas && $dibayar > 0);

                    $tempoStr = $t->jatuh_tempo ? \Carbon\Carbon::parse($t->jatuh_tempo)->format('d/m/Y') : '-';
                    $ket = trim($t->keterangan ?? '');
                    $ketFull = $tempoStr !== '-' ? "Jatuh Tempo: {$tempoStr}" . ($ket ? " ({$ket})" : '') : ($ket ?: '-');
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $t->user->nim ?? '-' }}</td>
                    <td><strong>{{ $t->user->name ?? '-' }}</strong></td>
                    <td>{{ $t->user->education['program_studi'] ?? '-' }}</td>
                    <td><strong>{{ $t->nama_tagihan }}</strong></td>
                    <td style="font-size: 7pt; color: #475569;">{{ $ketFull }}</td>
                    <td class="text-right font-bold">{{ number_format($nom, 0, ',', '.') }}</td>
                    <td class="text-right text-success font-bold">{{ number_format($dibayar, 0, ',', '.') }}</td>
                    <td class="text-right {{ $sisa > 0 ? 'text-danger font-bold' : 'text-success' }}">
                        {{ number_format($sisa, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($isLunas)
                            <span class="badge badge-lunas">LUNAS</span>
                        @elseif($isSebagian)
                            <span class="badge badge-sebagian">SEBAGIAN</span>
                        @else
                            <span class="badge badge-belum">BELUM LUNAS</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #64748b;">
                        Tidak ada data tagihan non-SPP yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="6" class="text-center" style="padding: 6px;">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
                <td class="text-right text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                <td class="text-right text-danger">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                <td class="text-center">{{ count($items) }} Data</td>
            </tr>
        </tfoot>
    </table>

    {{-- TANDA TANGAN / PENGESAHAN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 65%; vertical-align: bottom; font-size: 7.5pt; color: #64748b; line-height: 1.5;">
                <p style="margin: 0;">
                    <i>* Dokumen laporan ini dicetak secara otomatis dari Sistem Keuangan MATLA.</i><br>
                    <i>* Seluruh data tagihan dan riwayat pembayaran non-SPP yang tercatat merupakan data sah dan terverifikasi.</i>
                </p>
            </td>
            <td style="width: 35%; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 4px 0; font-size: 8pt; color: #334155;">
                    Tangerang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <p style="margin: 0 0 50px 0; font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                    Bagian Administrasi Keuangan
                </p>
                <p style="margin: 0; font-size: 8.5pt; font-weight: bold; text-decoration: underline; color: #00703C;">
                    {{ Auth::user()->name ?? 'Admin Keuangan' }}
                </p>
                <p style="margin: 2px 0 0 0; font-size: 7.5pt; color: #64748b;">
                    Petugas Keuangan Kampus
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
