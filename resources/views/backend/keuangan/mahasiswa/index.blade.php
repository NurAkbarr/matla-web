@extends('layouts.keuangan')

@section('title', 'Data Mahasiswa & SPP')

@section('content')
<div class="space-y-6">

    {{-- Header Area: Title & Export Buttons --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Data mahasiswa & tarif SPP</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Penetapan besaran SPP per mahasiswa, kontak, dan status keaktifan akademik.</p>
        </div>

        {{-- Tombol Aksi Ekspor (Excel & PDF: Tinggi 40px, Radius 4px, Gaya Konsisten) --}}
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            {{-- Ekspor Excel --}}
            <a href="{{ route('backend.keuangan.mahasiswa.export.excel', request()->all()) }}" 
               class="h-10 inline-flex items-center gap-2 px-3.5 rounded-[var(--radius-xs)] text-xs font-semibold text-[var(--primary-700)] dark:text-[var(--primary-300)] bg-[var(--surface)] hover:bg-[var(--primary-50)] border border-[var(--primary-500)] transition-colors cursor-pointer"
               title="Unduh data dalam format Excel">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor Excel</span>
            </a>

            {{-- Ekspor PDF --}}
            <a href="{{ route('backend.keuangan.mahasiswa.export.pdf', request()->all()) }}" 
               target="_blank"
               class="h-10 inline-flex items-center gap-2 px-3.5 rounded-[var(--radius-xs)] text-xs font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] transition-colors cursor-pointer"
               title="Cetak atau simpan format PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    {{-- Filter Bar (Satu Baris Panel, Input 40px, Radius 4px, Border Line) --}}
    <form id="filter-form" action="{{ route('backend.keuangan.mahasiswa.index') }}" method="GET" class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-wrap items-center gap-3">
        {{-- Pencarian --}}
        <div class="relative flex-1 min-w-[220px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, atau nomor HP..." class="w-full h-10 pl-9 pr-3.5 border border-[var(--border)] bg-[var(--bg-page)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)] focus:ring-1 focus:ring-[var(--primary-700)] transition-all">
            <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        {{-- Filter Program Studi --}}
        <select name="prodi" onchange="this.form.submit()" class="h-10 border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-[var(--radius-xs)] px-3 text-xs focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua program studi</option>
            @foreach($programStudis as $prodi)
                <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
            @endforeach
        </select>

        {{-- Filter Angkatan --}}
        <select name="angkatan" onchange="this.form.submit()" class="h-10 border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-[var(--radius-xs)] px-3 text-xs focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua angkatan</option>
            @foreach($angkatans as $angkatan)
                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>Angkatan {{ $angkatan }}</option>
            @endforeach
        </select>

        {{-- Filter Semester --}}
        <select name="semester" onchange="this.form.submit()" class="h-10 border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-[var(--radius-xs)] px-3 text-xs focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua semester</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>Semester {{ $semester }}</option>
            @endforeach
        </select>

        {{-- Filter Status Akademik --}}
        <select name="status" onchange="this.form.submit()" class="h-10 border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-[var(--radius-xs)] px-3 text-xs font-medium focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ strtolower((string) request('status')) === strtolower((string) $status) ? 'selected' : '' }}>
                    {{ ucfirst(strtolower((string) $status)) }}
                </option>
            @endforeach
        </select>

        {{-- Reset Filter Button --}}
        @if(request()->anyFilled(['search', 'prodi', 'angkatan', 'semester', 'status']))
            <a href="{{ route('backend.keuangan.mahasiswa.index') }}" class="h-10 px-3 text-xs font-semibold text-[var(--danger)] hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-[var(--radius-xs)] border border-rose-200 dark:border-rose-900/50 transition-colors flex items-center gap-1.5" title="Reset filter">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Reset filter</span>
            </a>
        @endif
    </form>

    {{-- Table Container --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards" id="mhs-table">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-5 pr-3 w-[110px]">NIM</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[200px]">Nama mahasiswa</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[80px]">Angkatan</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[180px]">Program studi</th>
                        <th scope="col" class="py-3.5 px-4 text-right w-[130px]">SPP per bulan</th>
                        <th scope="col" class="py-3.5 px-4 w-[140px]">WhatsApp</th>
                        <th scope="col" class="py-3.5 px-4 text-center w-[120px]">Status akademik</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]" id="mhs-tbody">
                    @forelse($mahasiswas as $mhs)
                    @php
                        $statusLower = strtolower($mhs->status ?? 'aktif');
                        $nominalSpp = (float) ($mhs->nominal_spp ?? 300000);
                        $initial = strtoupper(substr($mhs->name, 0, 1));
                    @endphp
                    <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                        {{-- NIM --}}
                        <td class="py-3.5 pl-5 pr-3 font-semibold text-[var(--text-heading)] tabular-nums" data-label="NIM">
                            {{ $mhs->nim ?? '-' }}
                        </td>
                        
                        {{-- Nama Mahasiswa & Email --}}
                        <td class="py-3.5 px-4" data-label="Mahasiswa">
                            <div class="flex items-center gap-3">
                                {{-- Seragam satu skema warna (Bukan warna-warni acak) --}}
                                <div class="w-8 h-8 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ $initial }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-[var(--text-heading)] truncate max-w-[170px] xl:max-w-[220px]">{{ $mhs->name }}</p>
                                    <p class="text-[11px] text-[var(--text-muted)] truncate max-w-[170px] xl:max-w-[220px]">{{ $mhs->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Angkatan --}}
                        <td class="py-3.5 px-3 text-center text-[var(--text-muted)] tabular-nums" data-label="Angkatan">
                            {{ $mhs->angkatan ?? '-' }}
                        </td>
                        
                        {{-- Program Studi & Semester --}}
                        <td class="py-3.5 px-4" data-label="Program studi">
                            <p class="font-medium text-[var(--text-heading)] truncate max-w-[180px]">{{ $mhs->education['program_studi'] ?? 'Belum ditentukan' }}</p>
                            <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                                {{ !empty($mhs->semester) ? 'Semester ' . $mhs->semester : 'Semester -' }}
                            </p>
                        </td>

                        {{-- SPP per Bulan (Teks Angka Biasa dengan Tabular-nums, BUKAN Badge) --}}
                        <td class="py-3.5 px-4 text-right font-semibold tabular-nums text-[var(--text-heading)]" data-label="Tarif SPP">
                            Rp {{ number_format($nominalSpp, 0, ',', '.') }}
                        </td>

                        {{-- WhatsApp --}}
                        <td class="py-3.5 px-4" data-label="WhatsApp">
                            @if(!empty($mhs->phone))
                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $mhs->phone);
                                    if (str_starts_with($waNumber, '0')) {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="inline-flex items-center gap-1.5 font-medium text-[var(--primary-600)] hover:underline tabular-nums" title="Hubungi via WhatsApp">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>{{ $mhs->phone }}</span>
                                </a>
                            @else
                                <span class="text-[var(--text-muted)]">-</span>
                            @endif
                        </td>

                        {{-- Status Akademik (Badge Persegi Bergaris Radius 4px dengan Titik Penanda per design.md) --}}
                        <td class="py-3.5 px-4 text-center" data-label="Status">
                            @if($statusLower === 'aktif')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>
                                    Aktif
                                </span>
                            @elseif($statusLower === 'cuti')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary-500)] mr-1.5"></span>
                                    Cuti
                                </span>
                            @elseif($statusLower === 'nonaktif')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-[var(--danger)] dark:bg-rose-950/40 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--danger)] mr-1.5"></span>
                                    Nonaktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--bg-page)] text-[var(--text-muted)] border border-[var(--border)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--text-muted)] mr-1.5"></span>
                                    {{ ucfirst(strtolower($mhs->status ?? 'Tidak aktif')) }}
                                </span>
                            @endif
                        </td>

                        {{-- Aksi (Tombol Ikon 40x40 Aksesibel) --}}
                        <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Edit Tarif SPP --}}
                                <button type="button" 
                                        onclick="openSppModal(this)"
                                        data-id="{{ $mhs->id }}"
                                        data-name="{{ $mhs->name }}"
                                        data-nim="{{ $mhs->nim ?? '-' }}"
                                        data-prodi="{{ $mhs->education['program_studi'] ?? '-' }}"
                                        data-nominal="{{ $nominalSpp }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--primary-700)] dark:text-[var(--primary-300)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer"
                                        aria-label="Atur tarif SPP {{ $mhs->name }}"
                                        title="Atur tarif SPP">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Detail Mahasiswa --}}
                                <button type="button" 
                                        onclick="openDetailModal(this)"
                                        data-name="{{ $mhs->name }}"
                                        data-nim="{{ $mhs->nim ?? '-' }}"
                                        data-email="{{ $mhs->email ?? '-' }}"
                                        data-phone="{{ $mhs->phone ?? '-' }}"
                                        data-prodi="{{ $mhs->education['program_studi'] ?? '-' }}"
                                        data-angkatan="{{ $mhs->angkatan ?? '-' }}"
                                        data-semester="{{ $mhs->semester ?? '-' }}"
                                        data-status="{{ ucfirst(strtolower($mhs->status ?? 'Aktif')) }}"
                                        data-spp="{{ number_format($nominalSpp, 0, ',', '.') }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--bg-page)] border border-transparent hover:border-[var(--border)] transition cursor-pointer" 
                                        aria-label="Lihat detail {{ $mhs->name }}"
                                        title="Lihat detail profil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-xs text-[var(--text-muted)]">
                            <p class="font-semibold text-[var(--text-heading)]">Belum ada data mahasiswa.</p>
                            <p class="mt-1">Coba sesuaikan kata kunci pencarian atau bersihkan filter yang aktif.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Count + Pagination --}}
        <div class="px-5 py-3.5 border-t border-[var(--border)] flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-[var(--text-muted)] text-center sm:text-left tabular-nums">
                @if($mahasiswas->total() > 0)
                    Menampilkan {{ $mahasiswas->firstItem() }}–{{ $mahasiswas->lastItem() }} dari total {{ $mahasiswas->total() }} mahasiswa
                @else
                    Menampilkan 0 mahasiswa
                @endif
            </p>
            
            <div class="flex items-center">
                {{ $mahasiswas->links('partials.pagination-keuangan') }}
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- MODAL ATUR TARIF SPP MAHASISWA                                        --}}
{{-- ===================================================================== --}}
<div id="modal-spp" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs hidden" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-md shadow-[var(--shadow-modal)] overflow-hidden">
        
        {{-- Modal Header --}}
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Atur tarif SPP mahasiswa</h3>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Penetapan besaran tarif tagihan SPP bulanan</p>
            </div>
            <button type="button" onclick="closeSppModal()" class="w-8 h-8 flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] hover:bg-[var(--bg-page)] transition cursor-pointer" aria-label="Tutup modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Edit SPP --}}
        <form id="form-update-spp" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-4">
                {{-- Preview Mahasiswa --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-3">
                    <p class="text-[10px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">Mahasiswa</p>
                    <p id="modal-mhs-name" class="text-xs font-bold text-[var(--text-heading)] mt-0.5">-</p>
                    <p id="modal-mhs-nim-prodi" class="text-[11px] text-[var(--text-muted)] mt-0.5">-</p>
                </div>

                {{-- Input Nominal SPP --}}
                <div>
                    <label for="nominal_spp" class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Besaran SPP per bulan (Rp) <span class="text-[var(--danger)]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs font-semibold text-[var(--text-muted)] pointer-events-none">Rp</span>
                        <input type="number" 
                               id="nominal_spp" 
                               name="nominal_spp" 
                               min="0" 
                               step="1000"
                               required
                               placeholder="Contoh: 300000"
                               class="w-full h-10 pl-10 pr-3 text-xs font-bold text-[var(--text-heading)] tabular-nums bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] focus:outline-none focus:border-[var(--primary-700)] focus:ring-1 focus:ring-[var(--primary-700)] transition-all">
                    </div>
                    
                    {{-- Quick Presets --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                        <span class="text-[10px] text-[var(--text-muted)] mr-1">Preset:</span>
                        <button type="button" onclick="setNominalPreset(200000)" class="px-2 py-1 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition cursor-pointer">200.000</button>
                        <button type="button" onclick="setNominalPreset(250000)" class="px-2 py-1 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition cursor-pointer">250.000</button>
                        <button type="button" onclick="setNominalPreset(300000)" class="px-2 py-1 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)] transition cursor-pointer">300.000</button>
                        <button type="button" onclick="setNominalPreset(350000)" class="px-2 py-1 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition cursor-pointer">350.000</button>
                        <button type="button" onclick="setNominalPreset(400000)" class="px-2 py-1 rounded-[var(--radius-xs)] text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition cursor-pointer">400.000</button>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="px-5 py-3 border-t border-[var(--border)] bg-[var(--bg-page)] flex items-center justify-end gap-2">
                <button type="button" onclick="closeSppModal()" class="h-9 px-3.5 text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--border)] rounded-[var(--radius-xs)] border border-[var(--border)] transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 text-xs font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] rounded-[var(--radius-xs)] transition cursor-pointer">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- MODAL DETAIL MAHASISWA                                                --}}
{{-- ===================================================================== --}}
<div id="modal-detail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs hidden" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-md shadow-[var(--shadow-modal)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Detail profil mahasiswa</h3>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)]" aria-label="Tutup modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-5 space-y-3 text-xs">
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">Nama lengkap</span>
                <span id="detail-name" class="font-semibold text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">NIM</span>
                <span id="detail-nim" class="font-semibold text-[var(--text-heading)] tabular-nums text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">Program studi</span>
                <span id="detail-prodi" class="font-medium text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">Angkatan / semester</span>
                <span id="detail-angkatan-semester" class="font-medium text-[var(--text-heading)] tabular-nums text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">Status akademik</span>
                <span id="detail-status" class="font-semibold text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">Tarif SPP per bulan</span>
                <span id="detail-spp" class="font-bold text-[var(--primary-600)] tabular-nums text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)]">No HP / WhatsApp</span>
                <span id="detail-phone" class="font-medium text-[var(--text-heading)] tabular-nums text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5">
                <span class="text-[var(--text-muted)]">Email</span>
                <span id="detail-email" class="font-medium text-[var(--text-heading)] text-right truncate max-w-[200px]">-</span>
            </div>
        </div>
        <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="h-9 px-4 text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--border)] rounded-[var(--radius-xs)] border border-[var(--border)] transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openSppModal(target, name, nim, prodi, currentNominal) {
    var id;
    if (target instanceof HTMLElement || (typeof target === 'object' && target !== null && target.dataset)) {
        var ds = target.dataset;
        id = ds.id;
        name = ds.name;
        nim = ds.nim;
        prodi = ds.prodi;
        currentNominal = ds.nominal;
    } else {
        id = target;
    }

    var modal = document.getElementById('modal-spp');
    var form = document.getElementById('form-update-spp');
    
    form.action = "{{ url('/backend/keuangan/mahasiswa') }}/" + id + "/nominal-spp";

    document.getElementById('modal-mhs-name').innerText = name || '-';
    document.getElementById('modal-mhs-nim-prodi').innerText = "NIM: " + (nim || '-') + " • " + (prodi || '-');
    document.getElementById('nominal_spp').value = currentNominal || '0';

    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    document.getElementById('nominal_spp').focus();
}

function closeSppModal() {
    var modal = document.getElementById('modal-spp');
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

function setNominalPreset(amount) {
    document.getElementById('nominal_spp').value = amount;
}

function openDetailModal(target, nim, email, phone, prodi, angkatan, semester, status, spp) {
    var name;
    if (target instanceof HTMLElement || (typeof target === 'object' && target !== null && target.dataset)) {
        var ds = target.dataset;
        name = ds.name;
        nim = ds.nim;
        email = ds.email;
        phone = ds.phone;
        prodi = ds.prodi;
        angkatan = ds.angkatan;
        semester = ds.semester;
        status = ds.status;
        spp = ds.spp;
    } else {
        name = target;
    }

    document.getElementById('detail-name').innerText = name || '-';
    document.getElementById('detail-nim').innerText = nim || '-';
    document.getElementById('detail-prodi').innerText = prodi || '-';
    document.getElementById('detail-angkatan-semester').innerText = "Angkatan " + (angkatan || '-') + " • Semester " + (semester || '-');
    document.getElementById('detail-status').innerText = status || '-';
    document.getElementById('detail-spp').innerText = "Rp " + (spp || '0');
    document.getElementById('detail-phone').innerText = phone || '-';
    document.getElementById('detail-email').innerText = email || '-';

    var modal = document.getElementById('modal-detail');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeDetailModal() {
    var modal = document.getElementById('modal-detail');
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSppModal();
        closeDetailModal();
    }
});

document.getElementById('modal-spp').addEventListener('click', function(e) {
    if (e.target === this) closeSppModal();
});

document.getElementById('modal-detail').addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});
</script>
@endpush
