<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendaftaran PMB</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #065f46; padding-bottom: 10px; }
        .logo { width: 60px; margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; color: #065f46; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 12px; color: #666; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #065f46; color: white; padding: 10px 5px; text-align: left; }
        td { padding: 8px 5px; border-bottom: 1px solid #eee; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        
        .summary-box { margin-top: 20px; padding: 10px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; }
        .summary-title { font-weight: bold; margin-bottom: 5px; color: #065f46; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Matla Islamic University</h1>
        <p class="subtitle">Laporan Pendaftaran Penerimaan Mahasiswa Baru (PMB) TA {{ date('Y') }}</p>
        <p style="margin-top: 5px;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-title">Ringkasan Laporan:</div>
        Total Pendaftar: {{ $registrations->count() }} orang
        @if($status) | Status: {{ ucfirst($status) }} @endif
        @if($prodi) | Prodi: {{ $prodi }} @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Reg</th>
                <th width="25%">Nama Lengkap</th>
                <th width="20%">Program Studi</th>
                <th width="15%">WhatsApp</th>
                <th width="10%">Status</th>
                <th width="10%">Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $index => $reg)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><code>{{ $reg->registration_code }}</code></td>
                <td>{{ $reg->full_name }}</td>
                <td>{{ $reg->study_program }}</td>
                <td>{{ $reg->whatsapp_number }}</td>
                <td>
                    <span class="status" style="color: {{ 
                        $reg->status == 'accepted' ? '#059669' : 
                        ($reg->status == 'rejected' ? '#dc2626' : '#d97706') 
                    }}">
                        {{ $reg->status }}
                    </span>
                </td>
                <td>{{ $reg->created_at->format('d/m/y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem Informasi Akademik MATLA - Halaman 1 dari 1
    </div>
</body>
</html>
