<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8fafc; color: #0f172a; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .header { background-color: #006D35; padding: 30px 20px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; }
        .content { padding: 30px; }
        .content p { font-size: 16px; line-height: 1.5; color: #475569; margin-top: 0; }
        .details-box { background-color: #f1f5f9; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .details-box p { margin: 10px 0; font-size: 14px; }
        .details-box strong { color: #1e293b; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #f8fafc; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; background-color: #006D35; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .badge { display: inline-block; padding: 4px 10px; background-color: #d1fae5; color: #047857; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Disetujui <span style="font-size: 24px;">🎉</span></h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Kabar baik! Pembayaran kamu telah berhasil diverifikasi dan disetujui oleh Admin Keuangan. Berikut adalah detail pembayarannya:</p>
            
            <div class="details-box">
                <p><strong>Tagihan:</strong> {{ $pembayaran->tagihan->nama_tagihan }}</p>
                <p><strong>Nominal Bayar:</strong> Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                <p><strong>Tanggal Bayar:</strong> {{ $pembayaran->created_at->format('d M Y H:i') }}</p>
                <p><strong>Status:</strong> <span class="badge">Sukses</span></p>
            </div>

            <p style="margin-top: 20px;">Silakan cek di sistem informasi akademik (LMS) pada menu <strong>Riwayat Pembayaran</strong> untuk mengunduh kwitansi digital kamu.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/backend/mahasiswa/pembayaran/riwayat') }}" class="btn">Lihat Riwayat Pembayaran</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MATLA University Portal. All rights reserved.
        </div>
    </div>
</body>
</html>
