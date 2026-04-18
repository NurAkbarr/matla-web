<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - {{ $registration->registration_code }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    
    <!-- Tailwind -> using CDN for print specific fast rendering -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .paper {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 2.5cm;
            margin: 2rem 0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }

        .serif {
            font-family: 'Playfair Display', serif;
        }

        /* Print Specific Styling */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                background: white;
                display: block;
            }
            .paper {
                margin: 0;
                padding: 2.5cm;
                box-shadow: none;
                width: 100%;
                min-height: 100vh;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak Manual (Hanya Tampil di Layar) -->
    <div class="fixed top-6 right-6 no-print">
        <button onclick="window.print()" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl font-bold shadow-lg flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Tanda Bukti / Simpan PDF</span>
        </button>
    </div>

    <!-- Dokumen Kertas A4 -->
    <div class="paper text-gray-900 border-x border-b border-gray-100">
        
        <!-- Header / Kop Surat -->
        <div class="flex items-center justify-between border-b-[3px] border-double border-gray-900 pb-6 mb-8">
            <div class="flex items-center space-x-5">
                <img src="{{ asset('assets/logo-bulat.png') }}" alt="Logo Matla" class="w-24 h-24 object-contain">
                <div>
                    <h1 class="text-3xl font-black text-[#10B981] tracking-tight uppercase">MATLA ISLAMIC UNIVERSITY</h1>
                    <p class="text-[11px] text-gray-600 font-bold uppercase tracking-widest mt-1">Panitia Penerimaan Mahasiswa Baru</p>
                    <p class="text-xs text-gray-500 mt-0.5">Jl. Conspet No. 123, Kota Santri, Indonesia. Website: www.matla.id</p>
                </div>
            </div>
        </div>

        <!-- Judul Surat -->
        <div class="text-center mb-12">
            <h2 class="text-2xl serif font-bold relative inline-block border-b border-gray-900 pb-1">
                SURAT KETERANGAN LULUS
            </h2>
            <p class="text-sm mt-2 font-bold tracking-widest text-gray-500">Letter of Acceptance (LoA)</p>
            <p class="text-sm mt-1 text-gray-600">No: {{ date('Y') }}/PMB-MATLA/SKL/{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Isi Surat -->
        <div class="space-y-6 text-sm leading-relaxed text-justify">
            <p>Panitia Penerimaan Mahasiswa Baru MATLA Islamic University dengan ini menerangkan bahwa:</p>

            <div class="mx-8 bg-gray-50/50 p-6 rounded-lg border border-gray-200">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="py-2 w-1/3 font-bold text-gray-600">Nomor Registrasi</td>
                            <td class="py-2 w-4 text-center">:</td>
                            <td class="py-2 font-black text-[#10B981] text-lg">{{ $registration->registration_code }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 w-1/3 font-bold text-gray-600">Nama Lengkap</td>
                            <td class="py-2 w-4 text-center">:</td>
                            <td class="py-2 font-bold uppercase">{{ $registration->first_name }} {{ $registration->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 w-1/3 font-bold text-gray-600">Nomor Induk Kependudukan</td>
                            <td class="py-2 w-4 text-center">:</td>
                            <td class="py-2 font-bold">{{ $registration->nik }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 w-1/3 font-bold text-gray-600">Tempat, Tanggal Lahir</td>
                            <td class="py-2 w-4 text-center">:</td>
                            <td class="py-2 font-bold capitalize">{{ $registration->birth_place }}, {{ \Carbon\Carbon::parse($registration->birth_date)->format('d F Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p>Berdasarkan hasil verifikasi administrasi dan keputusan Panitia Penerimaan Mahasiswa Baru, yang bersangkutan dinyatakan <strong>LULUS</strong> dan <strong>DITERIMA</strong> sebagai Mahasiswa Baru MATLA Islamic University pada:</p>

            <div class="mx-8 p-4 bg-emerald-50 text-emerald-900 rounded-lg border border-emerald-200 font-bold text-center text-lg uppercase tracking-wide">
                PROGRAM STUDI: {{ $registration->study_program }}
            </div>

            <p>Mahasiswa yang bersangkutan diwajibkan untuk segera melengkapi berkas Daftar Ulang dan mengikuti rangkaian kegiatan Pengenalan Kehidupan Kampus Mahasiswa Baru (PKKMB) sesuai dengan jadwal yang akan diinformasikan kemudian.</p>
            
            <p>Demikian surat keterangan ini kami sampaikan untuk dapat digunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya kami ucapkan jazakumullah khairan.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="mt-16 text-right">
            <p class="text-sm mb-1">Ditetapkan di: Kota Santri</p>
            <p class="text-sm mb-8">Pada Tanggal: {{ date('d F Y') }}</p>
            
            <div class="inline-block text-center mr-8">
                <p class="text-sm font-bold text-gray-800 mb-20 relative z-10 w-48">
                    Ketua PMB MATLA
                </p>
                <div class="border-b border-gray-900 pb-1 px-4">
                    <p class="font-bold text-sm uppercase">Ust. Syafiq Basalamah, M.A.</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">NIP: 19800512 201001 1 003</p>
            </div>
            <!-- Cap Stempel Placeholder (Optional aesthetic) -->
            <div class="absolute right-40 bottom-32 opacity-20 pointer-events-none -rotate-12 select-none grayscale w-24 h-24">
                <img src="{{ asset('assets/logo-bulat.png') }}" alt="Stempel">
            </div>
        </div>

        <!-- Footer / Watermark Text -->
        <div class="absolute bottom-6 left-0 right-0 text-center border-t border-gray-200 pt-4 mx-[2.5cm]">
            <p class="text-[10px] text-gray-400 font-medium">
                Surat ini digenerasi secara elektronik oleh Sistem Informasi Akademik Terpadu (SIAKAD) MATLA University.<br>
                Validitas Data dapat diverifikasi langsung melalui portal pmb.matla.id.
            </p>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500); // Wait 500ms for tailwind to process and fonts to load
        }
    </script>
</body>
</html>
