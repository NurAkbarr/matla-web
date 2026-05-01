@extends('layouts.dosen')

@section('title', 'Absen Mengajar (Grid) - Portal Dosen')

@section('content')
<div class="space-y-6" x-data="presensiGrid({{ json_encode($rows) }}, {{ $honorPerPertemuan }}, '{{ $bulan }}')">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Absen Mengajar</h1>
            <p class="mt-1 text-base text-gray-500">Rekap kehadiran per pertemuan selama satu bulan.</p>
        </div>
        <div>
            <form action="{{ route('backend.dosen.presensi.index') }}" method="GET" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ $bulan }}" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary focus:border-primary">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('backend.dosen.presensi.store') }}" method="POST" id="presensiForm">
        @csrf
        <input type="hidden" name="bulan" value="{{ $bulan }}">

        <!-- Top Header Card (Mirip Coretan) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-wrap gap-6 items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-primary/20">
                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-500 text-sm">Periode Bulan: <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</span></p>
                </div>
            </div>
        </div>

        <template x-if="rows.length === 0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <h3 class="text-lg font-bold text-gray-900">Belum ada Jadwal</h3>
                <p class="text-gray-500 text-sm mt-1 mb-4">Anda belum memiliki jadwal mengajar yang diplot oleh Admin.</p>
            </div>
        </template>

        <div class="space-y-6">
            <template x-for="(row, index) in rows" :key="row.id || index">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Row Header -->
                    <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-700">Mata Kuliah:</span>
                                <input type="text" x-model="row.mata_kuliah" :name="`rows[${index}][mata_kuliah]`" readonly class="border-none bg-transparent font-semibold text-gray-900 px-0 focus:ring-0 w-32 md:w-48">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-700">Semester:</span>
                                <input type="text" x-model="row.semester" :name="`rows[${index}][semester]`" readonly class="border-none bg-transparent font-semibold text-gray-900 px-0 focus:ring-0 w-12 text-center">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-700">Angkatan:</span>
                                <input type="text" x-model="row.angkatan" :name="`rows[${index}][angkatan]`" readonly class="border-none bg-transparent font-semibold text-gray-900 px-0 focus:ring-0 w-16 text-center">
                            </div>
                        </div>
                    </div>

                    <!-- Row Grid -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center">
                            <thead class="text-xs text-gray-500 uppercase bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 1</th>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 2</th>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 3</th>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Pertemuan 4</th>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Fee/Pertemuan</th>
                                    <th class="px-4 py-3 font-bold border-r border-gray-100">Jml Pertemuan</th>
                                    <th class="px-4 py-3 font-bold text-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- Pekan 1 -->
                                    <td class="px-4 py-4 border-r border-gray-100 align-top">
                                        <select x-model="row.pekan_1" :name="`rows[${index}][pekan_1]`" class="w-full border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-primary focus:border-primary" :class="getColorClass(row.pekan_1)">
                                            <option value="">- Kosong -</option>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alfa">Tidak Hadir (Alfa)</option>
                                        </select>
                                    </td>
                                    <!-- Pekan 2 -->
                                    <td class="px-4 py-4 border-r border-gray-100 align-top">
                                        <select x-model="row.pekan_2" :name="`rows[${index}][pekan_2]`" class="w-full border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-primary focus:border-primary" :class="getColorClass(row.pekan_2)">
                                            <option value="">- Kosong -</option>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alfa">Tidak Hadir (Alfa)</option>
                                        </select>
                                    </td>
                                    <!-- Pekan 3 -->
                                    <td class="px-4 py-4 border-r border-gray-100 align-top">
                                        <select x-model="row.pekan_3" :name="`rows[${index}][pekan_3]`" class="w-full border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-primary focus:border-primary" :class="getColorClass(row.pekan_3)">
                                            <option value="">- Kosong -</option>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alfa">Tidak Hadir (Alfa)</option>
                                        </select>
                                    </td>
                                    <!-- Pekan 4 -->
                                    <td class="px-4 py-4 border-r border-gray-100 align-top">
                                        <select x-model="row.pekan_4" :name="`rows[${index}][pekan_4]`" class="w-full border border-gray-200 rounded px-2 py-1.5 text-xs focus:ring-primary focus:border-primary" :class="getColorClass(row.pekan_4)">
                                            <option value="">- Kosong -</option>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alfa">Tidak Hadir (Alfa)</option>
                                        </select>
                                    </td>
                                    
                                    <td class="px-4 py-4 border-r border-gray-100 font-medium text-gray-700">
                                        <span x-text="formatRupiah(feePerPertemuan)"></span>
                                    </td>
                                    <td class="px-4 py-4 border-r border-gray-100 font-bold text-gray-900">
                                        <span x-text="countHadir(row)"></span> x <span x-text="formatRupiah(feePerPertemuan)"></span>
                                    </td>
                                    <td class="px-4 py-4 font-black text-primary text-base">
                                        <span x-text="formatRupiah(countHadir(row) * feePerPertemuan)"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>

        <!-- Submit & Grand Total -->
        <div class="mt-8 flex flex-col md:flex-row justify-between items-end md:items-center gap-6" x-show="rows.length > 0">
            <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-xl font-black text-lg transition-all shadow-xl shadow-primary/20 hover:scale-[1.02]">
                SIMPAN ABSENSI BULANAN
            </button>
            
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl shadow-xl p-6 text-white w-full md:w-auto min-w-[300px]">
                <p class="text-emerald-100 text-sm font-bold uppercase tracking-widest mb-1">Grand Total Honor</p>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black tracking-tighter" x-text="'Rp ' + formatRupiah(grandTotal())"></span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('presensiGrid', (initialRows, fee, bulan) => ({
            rows: initialRows.length > 0 ? initialRows : [],
            feePerPertemuan: fee,
            bulan: bulan,

            countHadir(row) {
                let count = 0;
                if (row.pekan_1 === 'Hadir') count++;
                if (row.pekan_2 === 'Hadir') count++;
                if (row.pekan_3 === 'Hadir') count++;
                if (row.pekan_4 === 'Hadir') count++;
                return count;
            },

            grandTotal() {
                let total = 0;
                this.rows.forEach(row => {
                    total += this.countHadir(row) * this.feePerPertemuan;
                });
                return total;
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },

            getColorClass(status) {
                if (status === 'Hadir') return 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                if (status === 'Izin') return 'bg-blue-50 text-blue-700 border-blue-200';
                if (status === 'Sakit') return 'bg-yellow-50 text-yellow-700 border-yellow-200';
                if (status === 'Alfa') return 'bg-red-50 text-red-700 border-red-200';
                return 'bg-gray-50 text-gray-500';
            }
        }));
    });
</script>
@endsection
