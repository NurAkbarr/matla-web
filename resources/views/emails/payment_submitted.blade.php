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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Baru Masuk!</h1>
        </div>
        <div class="content">
            <p>Halo Admin Keuangan,</p>
            <p>Terdapat satu pembayaran baru yang masuk dari mahasiswa dan membutuhkan verifikasi Anda. Berikut adalah detail pembayarannya:</p>
            
            <div class="details-box">
                <p><strong>Nama Mahasiswa:</strong> {{ $user->name }}</p>
                <p><strong>NIM:</strong> {{ $user->nim ?? '-' }}</p>
                <p><strong>Program Studi:</strong> {{ $user->education['program_studi'] ?? '-' }}</p>
                <p><strong>Tagihan:</strong> {{ $pembayaran->tagihan->nama_tagihan }}</p>
                <p><strong>Nominal Bayar:</strong> Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                <p><strong>Tanggal Bayar:</strong> {{ $pembayaran->created_at->format('d M Y H:i') }}</p>
            </div>

            <p style="margin-top: 20px;">Silakan login ke Dashboard Keuangan untuk melihat bukti transfer dan memverifikasi pembayaran ini.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/backend/keuangan/dashboard') }}" class="btn">Buka Dashboard Keuangan</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MATLA University Portal. All rights reserved.
        </div>
    </div>
</body>
</html>
