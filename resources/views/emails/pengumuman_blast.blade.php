<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengumuman->judul }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 24px 12px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #005a2b 0%, #006D35 50%, #0b7e42 100%);
            padding: 36px 28px 28px;
            text-align: center;
            color: #ffffff;
            border-bottom: 4px solid #f59e0b;
        }
        .institution-sub {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #bbf7d0;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .institution-title {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.3px;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .institution-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 8px;
            color: #fef08a;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 32px 28px;
            background-color: #ffffff;
        }
        .announcement-box {
            background-color: #f8fafc;
            border-left: 4px solid #006D35;
            border-radius: 0 12px 12px 0;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .announcement-meta {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .announcement-headline {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            line-height: 1.35;
        }
        .salutation {
            font-size: 15px;
            color: #334155;
            margin-bottom: 18px;
            line-height: 1.6;
        }
        .salutation strong {
            color: #006D35;
        }
        .body-content {
            font-size: 15px;
            line-height: 1.7;
            color: #334155;
            background-color: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 26px;
        }
        .body-content p {
            margin: 0 0 14px 0;
        }
        .body-content p:last-child {
            margin-bottom: 0;
        }
        .body-content ul, .body-content ol {
            padding-left: 20px;
            margin: 10px 0;
        }
        .body-content li {
            margin-bottom: 6px;
        }
        .body-content blockquote {
            border-left: 3px solid #006D35;
            background-color: #f0fdf4;
            padding: 10px 16px;
            margin: 14px 0;
            border-radius: 0 8px 8px 0;
            color: #166534;
            font-style: italic;
        }
        .cta-container {
            text-align: center;
            margin: 32px 0 16px;
        }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #006D35 0%, #005a2b 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0, 109, 53, 0.25);
            letter-spacing: 0.3px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 28px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            line-height: 1.6;
            color: #64748b;
        }
        .footer-logo {
            font-weight: 800;
            color: #006D35;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .footer-address {
            margin: 6px 0;
            color: #64748b;
        }
        .footer-contact {
            margin: 6px 0;
            color: #475569;
        }
        .footer-divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 16px auto;
            max-width: 200px;
        }
        .footer-disclaimer {
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="institution-sub">Sistem Informasi Akademik & Portal Kampus</div>
            <h1 class="institution-title">MAHAD TA'LIM LUGHOH AROBIYYAH</h1>
            <div class="institution-badge">KAMPUS MATLA</div>
        </div>

        <!-- Content Area -->
        <div class="content">
            <!-- Announcement Header Box -->
            <div class="announcement-box">
                <div class="announcement-meta">Pemberitahuan Resmi Kampus</div>
                <h2 class="announcement-headline">{{ $pengumuman->judul }}</h2>
            </div>

            <!-- Greeting -->
            <p class="salutation">
                Yth. <strong>{{ $recipientName }}</strong>,
            </p>

            <!-- Body Message -->
            <div class="body-content">
                {!! nl2br($pengumuman->konten) !!}
            </div>

            <!-- Call to Action -->
            <div class="cta-container">
                <a href="{{ url('/') }}" class="cta-btn" target="_blank">Kunjungi Portal MATLA &rarr;</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)</div>
            <p class="footer-address">
                Jl. H. Basir Pondok Kacang Barat, Gg. Mushola, Pondok Kacang Barat, Kec. Pondok Aren, Kota Tangerang Selatan, Banten
            </p>
            <p class="footer-contact">
                WhatsApp: <strong>081273510994</strong> &bull; Email: <strong>matlaislamicuniversity@gmail.com</strong>
            </p>
            <div class="footer-divider"></div>
            <p class="footer-disclaimer">
                Email ini dikirimkan secara otomatis dari portal resmi MATLA University.<br>
                &copy; {{ date('Y') }} MATLA University Portal. Seluruh hak cipta dilindungi undang-undang.
            </p>
        </div>
    </div>
</body>
</html>
