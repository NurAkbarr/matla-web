@php
    $logoBase64 = 'data:image/png;base64,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';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Pembayaran SPP per Semester - MATLA</title>
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
            margin-top: 2px;
        }

        /* SUMMARY BAR */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .summary-box {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            background-color: #f8fafc;
            border-radius: 4px;
        }
        .summary-title {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .summary-val {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0f172a;
        }

        /* DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 4px 5px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #00703C;
            color: #ffffff;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
            text-align: center;
        }
        .data-table td {
            font-size: 7.5pt;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* BADGES */
        .badge-lunas {
            color: #047857;
            font-weight: bold;
            text-align: center;
            background-color: #d1fae5;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7pt;
        }
        .badge-tunggak {
            color: #b91c1c;
            font-weight: bold;
            text-align: center;
            background-color: #fee2e2;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7pt;
        }
        .check-lunas {
            color: #047857;
            font-weight: bold;
            font-size: 9pt;
        }
        .check-empty {
            color: #cbd5e1;
            font-size: 9pt;
        }

        /* FOOTER / SIGNATURE */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .signature-table td {
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
                <p>No. Hp. 081273510994 | Email: matlaislamicuniversity@gmail.com</p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="title-block">
        <h2>LEMBAR KERJA REKAPITULASI PEMBAYARAN SPP PER SEMESTER</h2>
        <div class="meta">
            {{ $periodeLabel }} &bull; Program Studi: <b>{{ $selectedProdi ?: 'Semua Program Studi' }}</b> &bull; Status Mahasiswa: <b>{{ $statusAkademik }}</b> &bull; Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WIB
        </div>
    </div>

    {{-- RINGKASAN METRIK --}}
    <table class="summary-table">
        <tr>
            <td style="width: 20%; padding-right: 4px;">
                <div class="summary-box">
                    <div class="summary-title">Total Mahasiswa</div>
                    <div class="summary-val">{{ $summary['total_mahasiswa'] }} Orang</div>
                </div>
            </td>
            <td style="width: 20%; padding-right: 4px;">
                <div class="summary-box">
                    <div class="summary-title">Potensi Tagihan SPP</div>
                    <div class="summary-val">Rp {{ number_format($summary['total_potensi'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 20%; padding-right: 4px;">
                <div class="summary-box" style="border-left: 3px solid #00703C;">
                    <div class="summary-title" style="color: #00703C;">Total Sudah Dibayar</div>
                    <div class="summary-val" style="color: #00703C;">Rp {{ number_format($summary['total_terkumpul'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 20%; padding-right: 4px;">
                <div class="summary-box" style="border-left: 3px solid #dc2626;">
                    <div class="summary-title" style="color: #dc2626;">Sisa Tunggakan SPP</div>
                    <div class="summary-val" style="color: #dc2626;">Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 20%;">
                <div class="summary-box">
                    <div class="summary-title">% Kelunasan Semester</div>
                    <div class="summary-val">{{ $summary['persentase_lunas'] }}% ({{ $summary['mahasiswa_lunas'] }} Lunas)</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL MATRIKS REKAP SPP --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 22px;">No</th>
                <th style="width: 55px;">NIM</th>
                <th style="text-align: left;">Nama Mahasiswa & Prodi</th>
                @foreach($daftarBulan as $b)
                    <th style="width: 38px;">{{ substr($b['nama'], 0, 3) }}</th>
                @endforeach
                <th style="width: 62px; text-align: right;">Tarif SPP</th>
                <th style="width: 32px;">Tgk</th>
                <th style="width: 68px; text-align: right;">Total Tagihan</th>
                <th style="width: 68px; text-align: right;">Telah Bayar</th>
                <th style="width: 68px; text-align: right;">Sisa Tunggakan</th>
                <th style="width: 55px;">Status</th>
                <th style="width: 75px; text-align: left;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapItems as $idx => $item)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center; font-family: monospace;">{{ $item['nim'] }}</td>
                    <td>
                        <b>{{ $item['nama'] }}</b><br>
                        <span style="font-size: 6.5pt; color: #64748b;">{{ $item['prodi'] }} (Smst {{ $item['semester_mhs'] }})</span>
                    </td>
                    @foreach($item['matriks_bulan'] as $m)
                        <td style="text-align: center;">
                            @if($m['is_lunas'])
                                <span class="check-lunas">✔</span>
                            @else
                                <span class="check-empty">-</span>
                            @endif
                        </td>
                    @endforeach
                    <td style="text-align: right;">{{ number_format($item['nominal_spp'], 0, ',', '.') }}</td>
                    <td style="text-align: center; font-weight: bold; {{ $item['bulan_menunggak'] > 0 ? 'color: #dc2626;' : 'color: #047857;' }}">
                        {{ $item['bulan_menunggak'] }}
                    </td>
                    <td style="text-align: right;">{{ number_format($item['total_tagihan'], 0, ',', '.') }}</td>
                    <td style="text-align: right; color: #047857; font-weight: bold;">{{ number_format($item['total_dibayar'], 0, ',', '.') }}</td>
                    <td style="text-align: right; {{ $item['sisa_tunggakan'] > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                        {{ number_format($item['sisa_tunggakan'], 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        @if($item['status'] === 'Lunas')
                            <span class="badge-lunas">Lunas</span>
                        @else
                            <span class="badge-tunggak">Menunggak</span>
                        @endif
                    </td>
                    <td>{{ $item['metode'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" style="text-align: center; padding: 15px; color: #64748b;">
                        Tidak ada data mahasiswa yang cocok dengan kriteria filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="9" style="text-align: right;">TOTAL KESELURUHAN ({{ count($rekapItems) }} Mahasiswa):</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: right;">{{ number_format($summary['total_potensi'], 0, ',', '.') }}</td>
                <td style="text-align: right; color: #047857;">{{ number_format($summary['total_terkumpul'], 0, ',', '.') }}</td>
                <td style="text-align: right; color: #dc2626;">{{ number_format($summary['total_tunggakan'], 0, ',', '.') }}</td>
                <td colspan="2" style="text-align: center;">{{ $summary['persentase_lunas'] }}% Terkumpul</td>
            </tr>
        </tfoot>
    </table>

    {{-- TANDA TANGAN PEJABAT KEUANGAN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 65%;">
                <p style="margin: 0; font-size: 7.5pt; color: #64748b;">
                    <i>* Keterangan Simbol: (✔) Tagihan SPP terverifikasi lunas, (-) Belum dibayar / dalam penagihan.</i><br>
                    <i>* Rekapitulasi ini dihasilkan otomatis dari Sistem Keuangan MATLA University Portal.</i>
                </p>
            </td>
            <td style="width: 35%; text-align: center;">
                <p style="margin: 0 0 45px 0;">
                    Tangerang Selatan, {{ now()->translatedFormat('d F Y') }}<br>
                    <b>Bagian Keuangan & Kasir Kampus</b>
                </p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ auth()->user()->name ?? 'Admin Keuangan' }}
                </p>
                <p style="margin: 2px 0 0 0; font-size: 7.5pt; color: #64748b;">
                    NIP / NIDN: {{ auth()->user()->nidn ?? '-' }}
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
