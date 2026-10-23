@php
    // Logo Matla (Embed Base64)
    $logoBase64 = 'data:image/png;base64,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';

    // Cap Stempel
    $candidateCapPaths = [
        public_path('assets/Cap_Kampus.png'),
        public_path('assets/Cap_kampus.png'),
        base_path('public/assets/Cap_Kampus.png'),
        base_path('public/assets/Cap_kampus.png'),
    ];
    $capBase64 = '';
    foreach ($candidateCapPaths as $p) {
        if ($p && file_exists($p)) {
            $capBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($p));
            break;
        }
    }

    $isMasuk = $transaksi->isMasuk();

    // Helper Fungsi Terbilang Rupiah
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = penyebut($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
    $terbilangText = trim(penyebut((float)$transaksi->nominal)) . " Rupiah";
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi {{ $transaksi->kode_transaksi }} - MATLA</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background-color: #f8fafc;
        }

        .paper-container {
            max-width: 780px;
            margin: 0 auto;
            background: #ffffff;
            padding: 45px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 90px;
            font-weight: 800;
            color: rgba(0, 112, 60, 0.05);
            z-index: 0;
            white-space: nowrap;
            letter-spacing: 12px;
            pointer-events: none;
            user-select: none;
        }

        /* KOP SURAT */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 1;
        }
        .header-logo {
            width: 80px;
            vertical-align: middle;
            text-align: left;
        }
        .header-logo img {
            max-width: 75px;
            height: auto;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 40px;
        }
        .header-text h1 {
            margin: 0 0 4px 0;
            font-size: 19px;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .header-text p {
            margin: 2px 0;
            font-size: 11px;
            color: #475569;
            font-family: 'Times New Roman', Times, serif;
        }

        /* DIVIDER */
        .divider {
            border-top: 2.5px solid #00703C;
            border-bottom: 1px solid #00703C;
            height: 2px;
            margin: 15px 0 25px 0;
            position: relative;
            z-index: 1;
        }

        /* TITLE */
        .title-section {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }
        .title-section h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: {{ $isMasuk ? '#00703C' : '#dc2626' }};
            font-family: 'Times New Roman', Times, serif;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .title-section .subtitle {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* DETAIL TABLE */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .detail-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .col-label {
            width: 25%;
            color: #64748b;
            font-weight: 500;
        }
        .col-colon {
            width: 3%;
            text-align: center;
            color: #64748b;
        }
        .col-value {
            width: 72%;
            font-weight: 600;
            color: #0f172a;
        }

        /* BOX NOMINAL */
        .nominal-box {
            background-color: {{ $isMasuk ? '#f0fdf4' : '#fef2f2' }};
            border: 1.5px solid {{ $isMasuk ? '#86efac' : '#fecaca' }};
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0 25px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }
        .terbilang-text {
            font-size: 11.5px;
            color: #475569;
            font-style: italic;
            margin-top: 4px;
        }
        .nominal-amount {
            font-size: 24px;
            font-weight: 800;
            color: {{ $isMasuk ? '#00703C' : '#dc2626' }};
            text-align: right;
            letter-spacing: 0.5px;
        }

        /* FOOTER SIGNATURE */
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            position: relative;
            z-index: 1;
        }
        .footer-table td {
            vertical-align: top;
        }
        .signature-box {
            text-align: center;
            position: relative;
            width: 260px;
            margin-left: auto;
        }
        .signature-cap {
            position: absolute;
            left: 20px;
            top: 25px;
            width: 110px;
            opacity: 0.85;
            z-index: 2;
        }

        /* PRINT BUTTON */
        .print-toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        .btn-print {
            background-color: #00703C;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 112, 60, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover {
            background-color: #005a30;
        }
        .btn-close {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .paper-container {
                box-shadow: none;
                border: none;
                padding: 20px 25px;
                max-width: 100%;
            }
            .print-toolbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    {{-- Toolbar Print (Disembunyikan saat dicetak) --}}
    <div class="print-toolbar">
        <a href="javascript:window.close()" class="btn-close">Tutup</a>
        <button onclick="window.print()" class="btn-print">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Kwitansi / PDF</span>
        </button>
    </div>

    <div class="paper-container">
        {{-- Watermark --}}
        <div class="watermark">
            {{ $isMasuk ? 'LUNAS' : 'PENGELUARAN' }}
        </div>

        {{-- KOP KAMPUS --}}
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <img src="{{ $logoBase64 }}" alt="Logo MATLA">
                </td>
                <td class="header-text">
                    <h1>MA'HAD TIBYAN LIL QUR'AN WAL LUGHAH AL-ARABIYAH</h1>
                    <p>Kampus Pusat: Jl. Raya Matla University Portal, Indonesia</p>
                    <p>Website: <span style="color: #00703C; font-weight: bold;">matla-university.ac.id</span> | Email: info@matla-university.ac.id</p>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        {{-- JUDUL KWITANSI --}}
        <div class="title-section">
            <h2>{{ $isMasuk ? 'BUKTI PENERIMAAN KAS (KWITANSI)' : 'BUKTI PENGELUARAN KAS (VOUCHER)' }}</h2>
            <p class="subtitle">NOMOR: <strong>{{ $transaksi->kode_transaksi }}</strong></p>
        </div>

        {{-- DATA DETAIL TRANSAKSI --}}
        <table class="detail-table">
            <tr>
                <td class="col-label">Tanggal & Waktu</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $transaksi->formatted_tanggal }}</td>
            </tr>
            <tr>
                <td class="col-label">{{ $isMasuk ? 'Telah Diterima Dari' : 'Dibayarkan Kepada' }}</td>
                <td class="col-colon">:</td>
                <td class="col-value">
                    {{ $transaksi->subjek ?: '-' }}
                    @if($transaksi->user && !empty($transaksi->user->nim))
                        <span style="font-size: 11px; color: #475569; font-weight: normal;">(NIM: {{ $transaksi->user->nim }} &bull; {{ $transaksi->user->education['program_studi'] ?? 'Mahasiswa' }})</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="col-label">Kategori Transaksi</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $transaksi->kategori }}</td>
            </tr>
            <tr>
                <td class="col-label">Untuk Keperluan</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $transaksi->deskripsi ?: ($isMasuk ? 'Pembayaran Tagihan' : 'Pengeluaran Operasional') }}</td>
            </tr>
            <tr>
                <td class="col-label">Metode Pembayaran</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $transaksi->metode_pembayaran ?: 'Transfer Bank' }}</td>
            </tr>
            @if(!empty($transaksi->no_referensi))
            <tr>
                <td class="col-label">No. Referensi / Ref Bank</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $transaksi->no_referensi }}</td>
            </tr>
            @endif
        </table>

        {{-- BOX JUMLAH & TERBILANG --}}
        <div class="nominal-box">
            <div>
                <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b;">Jumlah Uang</span>
                <div class="terbilang-text"># {{ $terbilangText }} #</div>
            </div>
            <div class="nominal-amount">
                {{ ($isMasuk ? 'Rp ' : '- Rp ') . number_format($transaksi->nominal, 0, ',', '.') }}
            </div>
        </div>

        {{-- FOOTER / TANDA TANGAN --}}
        <table class="footer-table">
            <tr>
                <td style="width: 55%; font-size: 11px; color: #64748b; line-height: 1.6;">
                    <strong style="color: #0f172a;">Catatan Penting:</strong><br>
                    &bull; Bukti kas ini merupakan dokumen resmi yang diterbitkan secara elektronik oleh Sistem Keuangan MATLA University Portal.<br>
                    &bull; Transaksi yang telah diverifikasi bersifat sah dan tercatat secara permanen di buku kas pusat.
                </td>
                <td style="width: 45%;">
                    <div class="signature-box">
                        <p style="font-size: 12px; color: #475569; margin: 0 0 5px 0;">
                            {{ $transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->translatedFormat('d F Y') : date('d F Y') }}
                        </p>
                        <p style="font-size: 12px; font-weight: bold; color: #0f172a; margin: 0;">
                            Bagian Keuangan MATLA
                        </p>

                        @if(!empty($capBase64))
                            <img src="{{ $capBase64 }}" class="signature-cap" alt="Cap Kampus">
                        @endif

                        <div style="height: 65px;"></div>

                        <p style="font-size: 12px; font-weight: bold; color: #00703C; margin: 0; text-decoration: underline;">
                            {{ $transaksi->admin_nama ?: 'Admin Keuangan' }}
                        </p>
                        <p style="font-size: 10px; color: #64748b; margin: 2px 0 0 0;">
                            ID Petugas: {{ $transaksi->admin_id ? 'ADM-' . str_pad($transaksi->admin_id, 3, '0', STR_PAD_LEFT) : 'KEUANGAN-MATLA' }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
