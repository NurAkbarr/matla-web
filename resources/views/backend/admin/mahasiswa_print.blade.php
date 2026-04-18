<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Mahasiswa MATLA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
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
            padding: 2cm;
            margin: 2rem 0;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }

        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
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
                padding: 1.5cm;
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
    <div class="fixed top-6 right-6 no-print z-50">
        <button onclick="window.print()" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-3 rounded-xl font-bold shadow-lg flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Dokumen Rekap</span>
        </button>
    </div>

    <!-- Dokumen Kertas A4 -->
    <div class="paper text-gray-900">
        
        <!-- Header / Kop Surat -->
        <div class="flex items-center justify-between border-b-[3px] border-double border-gray-900 pb-4 mb-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('assets/logo-bulat.png') }}" alt="Logo Matla" class="w-16 h-16 object-contain">
                <div>
                    <h1 class="text-2xl font-black text-[#10B981] tracking-tight uppercase">MATLA ISLAMIC UNIVERSITY</h1>
                    <p class="text-[10px] text-gray-600 font-bold uppercase tracking-widest mt-0.5">Sistem Informasi Akademik Terpadu (SIAKAD)</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Jl. Conspet No. 123, Kota Santri. Website: www.matla.id | Telp: 0821-xxxx-xxxx</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal Cetak</p>
                <p class="text-xs font-bold text-gray-900">{{ date('d M Y') }}</p>
            </div>
        </div>

        <!-- Judul Rekap -->
        <div class="text-center mb-8">
            <h2 class="text-lg font-bold uppercase border-b border-gray-900 inline-block pb-0.5 mb-1">
                Data Rekapitulasi Mahasiswa terdaftar
            </h2>
            <p class="text-[10px] text-gray-500 font-medium">Total Mahasiswa: {{ $users->count() }} Data</p>
        </div>

        <!-- Tabel Data -->
        <table class="w-full text-left text-xs border border-gray-900 mb-8">
            <thead>
                <tr class="bg-gray-100 uppercase tracking-wider text-[10px] font-bold border-b border-gray-900">
                    <th class="py-2 px-3 border-r border-gray-900 text-center w-10">No</th>
                    <th class="py-2 px-3 border-r border-gray-900">Nama Mahasiswa</th>
                    <th class="py-2 px-3 border-r border-gray-900">Alamat Email</th>
                    <th class="py-2 px-3 border-r border-gray-900 text-center w-24">Angkatan</th>
                    <th class="py-2 px-3 border-r border-gray-900 text-center w-24">Semester</th>
                    <th class="py-2 px-3 text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-2 px-3 border-r border-gray-900 text-center text-[10px] font-medium">{{ $index + 1 }}</td>
                    <td class="py-2 px-3 border-r border-gray-900 font-bold uppercase">{{ $user->name }}</td>
                    <td class="py-2 px-3 border-r border-gray-900">{{ $user->email }}</td>
                    <td class="py-2 px-3 border-r border-gray-900 text-center">{{ $user->angkatan ?? '-' }}</td>
                    <td class="py-2 px-3 border-r border-gray-900 text-center">{{ $user->semester ?? '-' }}</td>
                    <td class="py-2 px-3 text-center font-bold text-[10px]">{{ $user->status ?? 'AKTIF' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-[10px] text-gray-500 italic mt-8">
            *Dokumen ini merupakan hasil generasi valid dari portal SIAKAD MATLA University.
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
