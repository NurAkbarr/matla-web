@extends('layouts.keuangan')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-800 uppercase tracking-wider">Master Data Mahasiswa</p>
            <p class="text-xs text-slate-500 mt-0.5">Kelola dan lihat seluruh data mahasiswa terdaftar.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-3 flex flex-wrap items-center gap-2">
        <div class="relative flex-1 min-w-[180px]">
            <input type="text" id="search-input" placeholder="Cari nama atau NIM..." class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 bg-slate-50 focus:outline-none focus:border-[#00703C] focus:ring-1 focus:ring-[#00703C]/20 focus:bg-white">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select id="filter-prodi" class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Program Studi</option>
            @foreach($programStudis as $prodi)
                <option value="{{ $prodi }}">{{ $prodi }}</option>
            @endforeach
        </select>

        <select id="filter-angkatan" class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Angkatan</option>
            @foreach($angkatans as $angkatan)
                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
            @endforeach
        </select>

        <select id="filter-semester" class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Semester</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester }}">Semester {{ $semester }}</option>
            @endforeach
        </select>

        <select id="filter-status" class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}">{{ ucwords(strtolower($status)) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap" id="mhs-table">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold tracking-wider text-[10px] uppercase">
                        <th class="py-4 px-5">NIM <span class="inline-block ml-0.5 opacity-40">↕</span></th>
                        <th class="py-4 px-5">Nama Lengkap</th>
                        <th class="py-4 px-5">Prodi <span class="inline-block ml-0.5 opacity-40">↕</span></th>
                        <th class="py-4 px-5">Semester <span class="inline-block ml-0.5 opacity-40">↕</span></th>
                        <th class="py-4 px-5">Jenis Kelamin <span class="inline-block ml-0.5 opacity-40">↕</span></th>
                        <th class="py-4 px-5">Email</th>
                        <th class="py-4 px-5">Status Akademik <span class="inline-block ml-0.5 opacity-40">↕</span></th>
                        <th class="py-4 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600" id="mhs-tbody">
                    @php
                        $avatarColors = ['bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-rose-100 text-rose-700', 'bg-cyan-100 text-cyan-700'];
                    @endphp
                    @forelse($mahasiswas as $index => $mhs)
                    <tr class="hover:bg-slate-50/50 transition-colors mhs-row" 
                        data-nim="{{ strtolower($mhs->nim ?? '') }}" 
                        data-name="{{ strtolower($mhs->name) }}" 
                        data-prodi="{{ $mhs->education['program_studi'] ?? '' }}" 
                        data-angkatan="{{ $mhs->angkatan ?? '' }}" 
                        data-semester="{{ $mhs->semester ?? '' }}" 
                        data-status="{{ $mhs->status ?? 'Aktif' }}">
                        <td class="py-4 px-5 font-semibold text-slate-500">{{ $mhs->nim ?? '-' }}</td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $avatarColors[$index % count($avatarColors)] }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-slate-800">{{ $mhs->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-slate-500">{{ $mhs->education['program_studi'] ?? '-' }}</td>
                        <td class="py-4 px-5 text-center text-slate-500">{{ $mhs->semester ?? '-' }}</td>
                        <td class="py-4 px-5 text-slate-500">{{ $mhs->jenis_kelamin ?? '-' }}</td>
                        <td class="py-4 px-5 text-slate-500">{{ $mhs->email ?? '-' }}</td>
                        <td class="py-4 px-5">
                            @php $statusLower = strtolower($mhs->status ?? 'aktif'); @endphp
                            <span class="font-bold text-xs uppercase tracking-wider
                                @if($statusLower === 'aktif') text-[#00703C]
                                @elseif($statusLower === 'cuti') text-amber-600
                                @elseif($statusLower === 'nonaktif') text-rose-600
                                @else text-slate-500 @endif">
                                {{ strtoupper($mhs->status ?? 'AKTIF') }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center justify-center gap-1">
                                <button class="p-1.5 text-slate-400 hover:text-[#00703C] hover:bg-emerald-50 rounded-lg transition" title="Lihat Detail">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Menu">
                                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 text-sm">Belum ada data mahasiswa yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Count + Pagination --}}
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400 font-medium">Total <span class="font-bold text-slate-600" id="mhs-count">{{ count($mahasiswas) }}</span> data mahasiswa</p>
            
            <div class="flex items-center gap-2">
                <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition text-xs">&lt;</button>
                <button class="w-8 h-8 rounded-lg bg-[#00703C] text-white flex items-center justify-center text-xs font-bold shadow-sm">1</button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition text-xs">&gt;</button>
                <select class="border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-slate-500 ml-2">
                    <option>10 / halaman</option>
                    <option>25 / halaman</option>
                    <option>50 / halaman</option>
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('search-input');
    var filterProdi = document.getElementById('filter-prodi');
    var filterAngkatan = document.getElementById('filter-angkatan');
    var filterSemester = document.getElementById('filter-semester');
    var filterStatus = document.getElementById('filter-status');
    var rows = document.querySelectorAll('.mhs-row');
    var countEl = document.getElementById('mhs-count');

    function applyFilters() {
        var search = searchInput.value.toLowerCase();
        var prodi = filterProdi.value;
        var angkatan = filterAngkatan.value;
        var semester = filterSemester.value;
        var status = filterStatus.value;
        var visible = 0;

        rows.forEach(function(row) {
            var matchSearch = !search || row.dataset.nim.includes(search) || row.dataset.name.includes(search);
            var matchProdi = !prodi || row.dataset.prodi === prodi;
            var matchAngkatan = !angkatan || row.dataset.angkatan === angkatan;
            var matchSemester = !semester || row.dataset.semester === semester;
            var matchStatus = !status || row.dataset.status === status;

            if (matchSearch && matchProdi && matchAngkatan && matchSemester && matchStatus) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        countEl.textContent = visible;
    }

    searchInput.addEventListener('input', applyFilters);
    filterProdi.addEventListener('change', applyFilters);
    filterAngkatan.addEventListener('change', applyFilters);
    filterSemester.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);
})();
</script>
@endpush
@endsection
