<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Data Mahasiswa & Tarif SPP - Sistem Keuangan MATLA</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN for print formatting -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .paper {
            width: 297mm; /* Landscape A4 for wide table */
            min-height: 210mm;
            background: white;
            padding: 1.5cm;
            margin: 2rem auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            box-sizing: border-box;
            border-radius: 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
            body {
                background: white;
            }
            .paper {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
                min-height: auto;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak / Download PDF (Hanya di Layar) -->
    <div class="fixed top-6 right-6 no-print z-50 flex items-center gap-3">
        <a href="{{ route('backend.keuangan.mahasiswa.index') }}" class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2.5 rounded-xl font-semibold text-xs shadow-sm flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <button onclick="window.print()" class="bg-[#006837] hover:bg-[#005a2f] text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Lembar Dokumen A4 Landscape -->
    <div class="paper">
        
        <!-- Header / Kop Surat -->
        <div class="flex items-center justify-between border-b-2 border-[#006837] pb-4 mb-5">
            <div class="flex items-center gap-4">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo Matla" class="h-14 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black text-[#006837] tracking-tight uppercase">MATLA ISLAMIC UNIVERSITY</h1>
                    <p class="text-[11px] text-gray-700 font-bold uppercase tracking-wider mt-0.5">Sistem Keuangan & Administrasi Biaya Pendidikan</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Website: www.matla.id | Email: finance@matla.id | Portal Keuangan Terpadu</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Cetak</p>
                <p class="text-xs font-bold text-gray-800">{{ date('d F Y, H:i') }} WIB</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Dicetak oleh: Staff Keuangan</p>
            </div>
        </div>

        <!-- Judul Rekap -->
        <div class="text-center mb-5">
            <h2 class="text-base font-extrabold text-gray-900 uppercase tracking-wide">Rekapitulasi Data Mahasiswa & Tarif SPP Bulanan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Daftar mahasiswa terdaftar beserta penetapan tarif SPP per bulan dan status keaktifan akademik</p>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-3 gap-4 mb-5">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Mahasiswa</p>
                <p class="text-lg font-bold text-gray-800 mt-0.5">{{ $totalMahasiswa }} <span class="text-xs font-normal text-gray-500">Orang</span></p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Mahasiswa Aktif</p>
                <p class="text-lg font-bold text-emerald-700 mt-0.5">{{ $totalAktif }} <span class="text-xs font-normal text-emerald-600">Orang</span></p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider">Potensi SPP Aktif / Bulan</p>
                <p class="text-lg font-bold text-blue-700 mt-0.5">Rp {{ number_format($totalPotensiSpp, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
            <table class="text-xs">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 font-bold border-b border-gray-300 text-left">
                        <th class="py-2.5 px-3 text-center w-10">No</th>
                        <th class="py-2.5 px-3">NIM</th>
                        <th class="py-2.5 px-3">Nama Mahasiswa</th>
                        <th class="py-2.5 px-3">Program Studi</th>
                        <th class="py-2.5 px-3 text-center">Angkatan</th>
                        <th class="py-2.5 px-3 text-center">Semester</th>
                        <th class="py-2.5 px-3 text-right">SPP per Bulan</th>
                        <th class="py-2.5 px-3">No HP / WhatsApp</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @forelse($users as $index => $u)
                        @php
                            $statusLower = strtolower($u->status ?? 'aktif');
                        @endphp
                        <tr class="{{ $index % 2 === 1 ? 'bg-gray-50/60' : 'bg-white' }}">
                            <td class="py-2 px-3 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 font-semibold text-gray-700">{{ $u->nim ?? '-' }}</td>
                            <td class="py-2 px-3 font-bold text-gray-900">{{ $u->name }}</td>
                            <td class="py-2 px-3 text-gray-600">{{ $u->education['program_studi'] ?? '-' }}</td>
                            <td class="py-2 px-3 text-center text-gray-600">{{ $u->angkatan ?? '-' }}</td>
                            <td class="py-2 px-3 text-center text-gray-600">{{ $u->semester ?? '-' }}</td>
                            <td class="py-2 px-3 text-right font-bold text-emerald-700">
                                Rp {{ number_format($u->nominal_spp ?? 300000, 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-3 text-gray-600">{{ $u->phone ?? '-' }}</td>
                            <td class="py-2 px-3 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    @if($statusLower === 'aktif') bg-emerald-100 text-emerald-800 border border-emerald-300
                                    @elseif($statusLower === 'cuti') bg-amber-100 text-amber-800 border border-amber-300
                                    @elseif($statusLower === 'nonaktif') bg-rose-100 text-rose-800 border border-rose-300
                                    @else bg-gray-100 text-gray-700 border border-gray-300 @endif">
                                    {{ strtoupper($u->status ?? 'AKTIF') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-400">Tidak ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan / Footer Pengesahan -->
        <div class="flex justify-between items-end pt-4 mt-8 border-t border-gray-200 text-xs">
            <div class="text-gray-500">
                <p class="font-bold text-gray-700">Catatan Keuangan:</p>
                <p class="text-[11px] mt-0.5">• Nominal SPP di atas berlaku sebagai tarif dasar penagihan berkala.</p>
                <p class="text-[11px]">• Tagihan hanya dibuat dan ditagihkan kepada mahasiswa dengan status akademik AKTIF.</p>
            </div>
            <div class="text-center w-56">
                <p class="text-gray-600">Kota Santri, {{ date('d F Y') }}</p>
                <p class="text-gray-500 font-medium mt-0.5">Bagian Keuangan & Administrasi,</p>
                <div class="h-16"></div>
                <p class="font-bold text-gray-900 border-b border-gray-400 pb-1">Bendahara Kampus MATLA</p>
                <p class="text-[10px] text-gray-400 mt-0.5">NIP: 19840212 201201 1 002</p>
            </div>
        </div>

    </div>

</body>
</html>
