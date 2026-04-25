<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Letter of Acceptance - {{ $registration->registration_code }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1a1a1a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .paper {
            padding: 2.5cm;
            position: relative;
        }
        .header {
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .logo-box { float: left; width: 100px; }
        .logo { width: 80px; height: auto; }
        .header-text { float: left; width: 500px; padding-left: 20px; }
        .university-name {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
            margin: 0;
            text-transform: uppercase;
        }
        .header-subtitle { font-size: 11px; font-weight: bold; margin: 5px 0; text-transform: uppercase; color: #444; }
        .header-contact { font-size: 10px; color: #666; }

        .document-title-box { text-align: center; margin-bottom: 40px; }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }
        .document-subtitle { font-size: 12px; font-weight: bold; color: #666; margin-top: 5px; }
        .document-number { font-size: 11px; margin-top: 5px; }

        .content { font-size: 13px; text-align: justify; }
        .data-table {
            width: 90%;
            margin: 20px auto;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
        }
        .data-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; color: #4b5563; width: 150px; }
        .value { font-weight: bold; }
        .highlight { color: #065f46; font-size: 16px; }

        .prodi-box {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin: 20px 0;
            text-transform: uppercase;
            color: #064e3b;
        }

        .signature-box { margin-top: 50px; float: right; width: 250px; text-align: left; }
        .signature-date { font-size: 12px; margin-bottom: 5px; }
        .signature-title { font-size: 13px; font-weight: bold; margin-bottom: 60px; }
        .signature-name { font-size: 13px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .signature-nip { font-size: 11px; color: #666; }

        .footer {
            position: absolute;
            bottom: 30px;
            left: 2.5cm;
            right: 2.5cm;
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="paper">
        <div class="header">
            <div class="logo-box">
                <img src="{{ public_path('assets/logo-bulat.png') }}" class="logo">
            </div>
            <div class="header-text">
                <h1 class="university-name">MATLA ISLAMIC UNIVERSITY</h1>
                <p class="header-subtitle">Panitia Penerimaan Mahasiswa Baru</p>
                <p class="header-contact">Jl. Raya Matla No. 12, Kota Bekasi, Jawa Barat. Website: www.matla.id</p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="document-title-box">
            <h2 class="document-title">SURAT KETERANGAN LULUS</h2>
            <p class="document-subtitle">Letter of Acceptance (LoA)</p>
            <p class="document-number">No: {{ date('Y') }}/PMB-MATLA/SKL/{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="content">
            <p>Panitia Penerimaan Mahasiswa Baru MATLA Islamic University dengan ini menerangkan bahwa:</p>

            <table class="data-table">
                <tr>
                    <td class="label">Nomor Registrasi</td>
                    <td>:</td>
                    <td class="value highlight">{{ $registration->registration_code }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td>:</td>
                    <td class="value" style="text-transform: uppercase;">{{ $registration->full_name }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td class="value">{{ $registration->birth_place }}, {{ \Carbon\Carbon::parse($registration->birth_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td>:</td>
                    <td class="value">{{ $registration->email }}</td>
                </tr>
            </table>

            <p>Berdasarkan hasil seleksi administrasi dan akademik, yang bersangkutan dinyatakan <strong>LULUS</strong> dan <strong>DITERIMA</strong> sebagai Mahasiswa Baru MATLA Islamic University pada:</p>

            <div class="prodi-box">
                PROGRAM STUDI: {{ $registration->study_program }}
            </div>

            <p>Mahasiswa diwajibkan melakukan Daftar Ulang sesuai dengan ketentuan yang berlaku. Informasi teknis mengenai perkuliahan akan disampaikan melalui email atau WhatsApp resmi kampus.</p>
            
            <p>Demikian surat keterangan ini disampaikan untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya, kami ucapkan Jazakumullah Khairan.</p>
        </div>

        <div class="signature-box">
            <p class="signature-date">Bekasi, {{ date('d F Y') }}</p>
            <p class="signature-title">Ketua PMB MATLA,</p>
            <p class="signature-name">Ust. Syafiq Basalamah, M.A.</p>
            <p class="signature-nip">NIP: 19800512 201001 1 003</p>
        </div>
        <div class="clear"></div>

        <div class="footer">
            Surat ini diterbitkan secara elektronik oleh Sistem PMB MATLA University.<br>
            Keaslian data dapat diverifikasi melalui kode registrasi di portal pmb.matla.id
        </div>
    </div>
</body>
</html>
