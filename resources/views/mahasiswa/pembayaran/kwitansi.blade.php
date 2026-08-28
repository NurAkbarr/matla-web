<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
            position: relative;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 140px;
            font-weight: bold;
            color: rgba(220, 235, 225, 0.4);
            z-index: -1;
            white-space: nowrap;
            letter-spacing: 20px;
        }

        /* HEADER */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 15%;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo img {
            max-width: 90px;
        }
        .header-text {
            width: 85%;
            text-align: center;
            vertical-align: middle;
        }
        .header-text h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0;
            font-size: 11px;
            font-family: 'Times New Roman', Times, serif;
        }
        .header-text a {
            color: #3b82f6;
            text-decoration: none;
        }

        /* DIVIDER */
        .divider {
            border-top: 3px solid #1a5632;
            margin: 15px 0 25px 0;
        }

        /* TITLE */
        .title-section {
            text-align: center;
            margin-bottom: 35px;
        }
        .title-section h2 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            color: #d97706; /* Emas/Orange */
            font-family: 'Times New Roman', Times, serif;
            letter-spacing: 2px;
        }
        .title-section p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }

        /* DETAIL MHS */
        .detail-table {
            width: 100%;
            border-top: 2px solid #1a5632;
            padding-top: 20px;
            margin-bottom: 25px;
            font-size: 13px;
        }
        .detail-table td {
            padding: 5px 0;
        }
        .col-label {
            width: 25%;
            color: #555;
        }
        .col-colon {
            width: 3%;
            text-align: center;
        }
        .col-value {
            width: 72%;
            font-weight: 600;
            color: #1a5632;
        }

        /* BOX PEMBAYARAN */
        .payment-box {
            width: 100%;
            background-color: #eaf3ed;
            border-top: 1px solid #1a5632;
            border-bottom: 1px solid #1a5632;
            border-left: 1px solid #1a5632;
            border-right: 1px solid #1a5632;
            padding: 15px 20px;
            box-sizing: border-box;
            margin-bottom: 35px;
        }
        .payment-table {
            width: 100%;
        }
        .payment-table td {
            vertical-align: bottom;
        }
        .label-jenis {
            font-size: 10px;
            font-weight: bold;
            color: #1a5632;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .val-jenis {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .label-nominal {
            font-size: 10px;
            font-weight: bold;
            color: #d97706;
            text-transform: uppercase;
            text-align: right;
            margin-bottom: 3px;
        }
        .val-nominal {
            font-size: 22px;
            font-weight: bold;
            color: #1a5632;
            text-align: right;
        }

        /* FOOTER */
        .footer-table {
            width: 100%;
            margin-top: 20px;
        }
        .footer-table td {
            vertical-align: top;
        }
        .footer-note {
            width: 50%;
            font-size: 11px;
            font-style: italic;
            color: #555;
            padding-right: 20px;
            line-height: 1.4;
        }
        .footer-sign {
            width: 50%;
            text-align: center;
            font-size: 12px;
            color: #333;
        }
        .sign-line {
            width: 80%;
            margin: 60px auto 5px auto;
            border-bottom: 1px solid #333;
        }
        .sign-title {
            font-size: 11px;
            color: #666;
        }

        /* BOTTOM BORDER */
        .bottom-border {
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
            height: 12px;
            background-color: #f59e0b;
        }
    </style>
</head>
<body>

    <div class="watermark">MATLA</div>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <!-- Base64 Logo (Atau URL absolute) -->
                    <!-- Jika logo tidak muncul, pastikan path public_path terjangkau oleh DomPDF -->
                    <img src="{{ public_path('assets/logo.png') }}" alt="Logo Matla" onerror="this.style.display='none'">
                </td>
                <td class="header-text">
                    <h1>KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH<br>(MATLA)</h1>
                    <p>Jl. H. Basir Pondok Kacang Barat, Gg. Mushola, Pondok Kacang Barat,<br>
                    Kec. Pondok Aren, Kota Tangerang Selatan, Banten No. Hp.<br>
                    081273510994, Email: <a href="mailto:matlaislamicuniversity@gmail.com">matlaislamicuniversity@gmail.com</a></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="title-section">
        <h2>KWITANSI</h2>
        <p>No. {{ $pembayaran->kwitansi }}</p>
    </div>

    <table class="detail-table">
        <tr>
            <td class="col-label">Telah terima dari</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $pembayaran->user->name ?? 'Mahasiswa' }}</td>
        </tr>
        <tr>
            <td class="col-label">NIM</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $pembayaran->user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Program Studi</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $pembayaran->user->prodi->nama_prodi ?? 'S1 PAI' }}</td>
        </tr>
    </table>

    <div class="payment-box">
        <table class="payment-table">
            <tr>
                <td>
                    <div class="label-jenis">JENIS PEMBAYARAN</div>
                    <div class="val-jenis">
                        {{ $pembayaran->tagihan->nama_tagihan ?? 'Pembayaran' }} 
                        @if(strtolower($pembayaran->jenis_pembayaran) === 'cicilan')
                            (Cicilan)
                        @elseif(strtolower($pembayaran->jenis_pembayaran) === 'lunas')
                            (Lunas)
                        @endif
                    </div>
                </td>
                <td style="text-align: right;">
                    <div class="label-nominal">NOMINAL</div>
                    <div class="val-nominal">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="footer-table">
        <tr>
            <td class="footer-note">
                Kwitansi ini merupakan bukti pembayaran resmi yang sah tanpa perlu tanda tangan basah, dan dicetak otomatis oleh sistem administrasi Kampus Matla.
            </td>
            <td class="footer-sign">
                Tangerang Selatan, {{ $pembayaran->updated_at ? $pembayaran->updated_at->format('d F Y') : date('d F Y') }}
                <div class="sign-line"></div>
                <div class="sign-title">Bendahara / Admin Kampus Matla</div>
            </td>
        </tr>
    </table>

    <div class="bottom-border"></div>

</body>
</html>
