@extends('layouts.backend')

@section('title', 'Rekap Honor Dosen - Admin Portal')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rekap Honor Dosen</h1>
            <p class="mt-1 text-base text-gray-500">Rekapitulasi absensi dan perhitungan honor mengajar dosen.</p>
        </div>
        <div>
            <form action="{{ route('backend.admin.rekap-honor.index') }}" method="GET" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ $bulan }}" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary focus:border-primary">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    Filter
                </button>
                <a href="{{ route('backend.admin.rekap-honor.export', ['bulan' => $bulan]) }}" 
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                    Export Excel
                </a>
                <button type="button" onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 md:p-6 flex items-start space-x-4">
        <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-blue-900">Rincian Kehadiran Bulan: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</h3>
            <p class="text-blue-800 mt-1">Tarif honor per pertemuan (kehadiran) saat ini disetel pada: <strong>Rp {{ number_format($honorPerPertemuan, 0, ',', '.') }}</strong>.</p>
        </div>
    </div>

    @if(count($dosens) == 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <p class="text-gray-500">Belum ada data dosen di sistem.</p>
    </div>
    @endif

    <div class="space-y-8">
        @foreach($dosens as $dosen)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:border-gray-300 print:shadow-none break-inside-avoid">
            <!-- Dosen Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200">
                        <img src="{{ $dosen->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $dosen->name }}</h3>
                        <p class="text-sm text-gray-500">Dosen Pengampu</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 uppercase tracking-widest font-bold">Total Honor</p>
                    <p class="text-2xl font-black text-primary">Rp {{ number_format($dosen->total_honor, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Tabel Grid Detail -->
            @if(count($dosen->presensiDosens) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center">
                    <thead class="text-xs text-gray-500 uppercase bg-white border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 font-bold border-r border-gray-100 text-left w-64">Kelas (Mata Kuliah)</th>
                            <th class="px-3 py-3 font-bold border-r border-gray-100">Sem</th>
                            <th class="px-3 py-3 font-bold border-r border-gray-100">Angkatan</th>
                            <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 1</th>
                            <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 2</th>
                            <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 3</th>
                            <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 4</th>
                            <th class="px-4 py-3 font-bold border-r border-gray-100">Jml Pertemuan</th>
                            <th class="px-4 py-3 font-bold text-primary">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($dosen->presensiDosens as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 border-r border-gray-100 text-left">
                                <span class="font-bold text-gray-900">{{ $p->mata_kuliah }}</span>
                            </td>
                            <td class="px-3 py-3 border-r border-gray-100 font-medium text-gray-700">
                                {{ $p->semester }}
                            </td>
                            <td class="px-3 py-3 border-r border-gray-100 font-medium text-gray-700">
                                {{ $p->angkatan }}
                            </td>
                            <!-- P1 -->
                            <td class="px-4 py-3 border-r border-gray-100">
                                @if($p->pekan_1 == 'Hadir') <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-bold text-xs">Hadir</span>
                                @elseif($p->pekan_1) <span class="text-gray-400 text-xs">{{ $p->pekan_1 }}</span>
                                @else <span class="text-gray-300">-</span> @endif
                            </td>
                            <!-- P2 -->
                            <td class="px-4 py-3 border-r border-gray-100">
                                @if($p->pekan_2 == 'Hadir') <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-bold text-xs">Hadir</span>
                                @elseif($p->pekan_2) <span class="text-gray-400 text-xs">{{ $p->pekan_2 }}</span>
                                @else <span class="text-gray-300">-</span> @endif
                            </td>
                            <!-- P3 -->
                            <td class="px-4 py-3 border-r border-gray-100">
                                @if($p->pekan_3 == 'Hadir') <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-bold text-xs">Hadir</span>
                                @elseif($p->pekan_3) <span class="text-gray-400 text-xs">{{ $p->pekan_3 }}</span>
                                @else <span class="text-gray-300">-</span> @endif
                            </td>
                            <!-- P4 -->
                            <td class="px-4 py-3 border-r border-gray-100">
                                @if($p->pekan_4 == 'Hadir') <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-bold text-xs">Hadir</span>
                                @elseif($p->pekan_4) <span class="text-gray-400 text-xs">{{ $p->pekan_4 }}</span>
                                @else <span class="text-gray-300">-</span> @endif
                            </td>

                            <td class="px-4 py-3 border-r border-gray-100 font-bold text-gray-900">
                                {{ $p->totalHadirBaris }} x {{ number_format($honorPerPertemuan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 font-black text-primary">
                                {{ number_format($p->totalHonorBaris, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-8 text-center text-gray-400 text-sm">
                Tidak ada data absensi untuk dosen ini di bulan yang dipilih.
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Super Grand Total -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl shadow-xl p-6 md:p-8 text-white flex flex-col md:flex-row items-center justify-between mt-8 print:hidden">
        <div>
            <h2 class="text-emerald-100 text-lg font-bold uppercase tracking-widest mb-1">Total Keseluruhan Honor Kampus</h2>
            <p class="text-emerald-200 text-sm">Total tagihan honor dosen untuk bulan {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="text-5xl font-black tracking-tighter">Rp {{ number_format($grandTotalSeluruhHonor, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
