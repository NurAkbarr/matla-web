@php
    // Logo Matla (Embed Base64 langsung agar 100% pasti muncul di server manapun/cPanel tanpa terpengaruh public_html/docroot)
    $logoBase64 = 'data:image/png;base64,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';

    // Cap Kampus (Cek berbagai path kemungkinan di server/cPanel)
    $candidateCapPaths = [
        public_path('assets/Cap_Kampus.png'),
        public_path('assets/Cap_kampus.png'),
        base_path('public/assets/Cap_Kampus.png'),
        base_path('public/assets/Cap_kampus.png'),
        base_path('../public_html/assets/Cap_Kampus.png'),
        base_path('../public_html/assets/Cap_kampus.png'),
        base_path('public_html/assets/Cap_Kampus.png'),
        base_path('public_html/assets/Cap_kampus.png'),
        isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/assets/Cap_Kampus.png' : null,
        isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/assets/Cap_kampus.png' : null,
    ];
    $capBase64 = '';
    foreach ($candidateCapPaths as $p) {
        if ($p && file_exists($p)) {
            $capBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($p));
            break;
        }
    }
@endphp
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
            height: auto;
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
            width: 48%;
            font-size: 11px;
            font-style: italic;
            color: #555;
            padding-right: 20px;
            line-height: 1.4;
        }
        .footer-sign {
            width: 52%;
            text-align: center;
            font-size: 12px;
            color: #333;
        }
        .sign-container {
            position: relative;
            margin: 15px auto 0 auto;
            width: 240px;
            text-align: center;
        }
        .cap-stamp {
            position: absolute;
            left: 50%;
            top: 5px;
            margin-left: -55px; /* Tepat di tengah horizontal (setengah dari 110px) */
            width: 110px;
            height: auto;
            z-index: 10;
        }
        .sign-line {
            width: 100%;
            margin: 60px 0 5px 0;
            border-bottom: 1px solid #333;
            position: relative;
            z-index: 5;
        }
        .sign-title {
            font-size: 11px;
            color: #666;
            position: relative;
            z-index: 5;
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
                    <img src="{{ $logoBase64 }}" alt="Logo Matla">
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
                <div class="sign-container">
                    @if($capBase64)
                        <img src="{{ $capBase64 }}" class="cap-stamp" alt="Cap Kampus">
                    @endif
                    <div class="sign-line"></div>
                    <div class="sign-title">Bendahara / Admin Kampus Matla</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="bottom-border"></div>

</body>
</html>
