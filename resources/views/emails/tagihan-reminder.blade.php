<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Tagihan Mahasiswa</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 24px 12px;
            -webkit-text-size-adjust: 100%;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #065F46 0%, #047857 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 4px 14px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.3;
        }
        .content {
            padding: 28px 24px;
        }
        .greeting {
            font-size: 15px;
            color: #334155;
            margin-top: 0;
            margin-bottom: 12px;
            line-height: 1.6;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 13px;
        }
        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .detail-label {
            color: #64748b;
            font-weight: 500;
            min-width: 140px;
        }
        .detail-value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
            flex: 1;
        }
        .nominal-highlight {
            color: #047857;
            font-size: 18px;
            font-weight: 800;
        }
        .badge-type {
            display: inline-block;
            padding: 3px 9px;
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-cicilan {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }
        .info-box {
            background-color: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 18px;
            font-size: 12px;
            line-height: 1.5;
            color: #854d0e;
        }
        .footer {
            padding: 20px 24px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            line-height: 1.5;
        }
        @media only screen and (max-width: 480px) {
            body { padding: 12px 6px; }
            .content { padding: 20px 16px; }
            .detail-row { flex-direction: column; gap: 4px; }
            .detail-value { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header Banner --}}
        <div class="header">
            <div class="header-badge">Sistem Keuangan Kampus</div>
            <h1>MATLA UNIVERSITY PORTAL</h1>
        </div>

        {{-- Main Body --}}
        <div class="content">
            <p class="greeting">
                <strong>Assalamu'alaikum Wr. Wb.</strong><br>
                Halo <strong>{{ $user->name }}</strong> (NIM: {{ $user->nim ?? '-' }}),
            </p>
            <p class="greeting">
                Kami informasikan bahwa tagihan biaya perkuliahan Anda telah diterbitkan oleh Bagian Keuangan Kampus. Berikut rincian tagihan Anda:
            </p>

            {{-- Detail Tagihan Card --}}
            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Nama Tagihan</span>
                    <span class="detail-value">{{ $tagihan->nama_tagihan }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Program Studi / Kelas</span>
                    <span class="detail-value">{{ $user->education['program_studi'] ?? 'Reguler' }} @if(!empty($user->semester)) &bull; Semester {{ $user->semester }} @endif</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total Tagihan</span>
                    <span class="detail-value nominal-highlight">
                        Rp {{ number_format($tagihan->nominal_total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Jenis Pembayaran</span>
                    <span class="detail-value">
                        @if($tagihan->jenis_tagihan === 'cicilan')
                            <span class="badge-type badge-cicilan">Dapat Dicicil</span>
                        @else
                            <span class="badge-type">Wajib Lunas</span>
                        @endif
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Batas Jatuh Tempo</span>
                    <span class="detail-value" style="color: {{ $tagihan->jatuh_tempo ? '#b91c1c' : '#0f172a' }};">
                        @if($tagihan->jatuh_tempo)
                            {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                @if(!empty($tagihan->keterangan))
                <div class="detail-row">
                    <span class="detail-label">Keterangan</span>
                    <span class="detail-value" style="font-weight: 500; color: #475569;">
                        {{ $tagihan->keterangan }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Important Notice --}}
            <div class="info-box">
                <strong>Catatan Penting:</strong>
                <ul style="margin: 6px 0 0 0; padding-left: 18px;">
                    <li>Silakan login ke portal dan upload bukti pembayaran (struk transfer / mutasi) pada menu <strong>Pembayaran &gt; Tagihan</strong>.</li>
                    <li>Jika Anda sudah melakukan transfer atau terdapat kendala perihal tagihan ini, silakan hubungi Admin Keuangan Kampus.</li>
                </ul>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            Email ini dikirim secara otomatis oleh Sistem Keuangan <strong>MATLA University</strong>.<br>
            Mohon tidak membalas langsung ke alamat email ini.<br>
            &copy; {{ date('Y') }} MATLA University. All Rights Reserved.
        </div>
    </div>
</body>
</html>
