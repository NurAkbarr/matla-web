@extends('layouts.keuangan')

@section('title', 'Data Mahasiswa & SPP')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-card">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 border border-[var(--primary-200)]/80 dark:border-[var(--primary-800)]/50 flex items-center justify-center flex-shrink-0 text-[var(--primary-600)] dark:text-[var(--primary-400)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-base md:text-lg font-bold text-[var(--text-heading)]">Master Data Mahasiswa & Tarif SPP</h1>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">Kelola tarif SPP per mahasiswa, kontak mahasiswa, dan status keaktifan akademik untuk penagihan.</p>
            </div>
        </div>

        {{-- Tombol Aksi Export (Excel & PDF) --}}
        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            {{-- Export Excel --}}
            <a href="{{ route('backend.keuangan.mahasiswa.export.excel', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-200 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 border border-emerald-300 dark:border-emerald-800 shadow-xs transition-colors"
               title="Unduh Data dalam Format Excel Rapi & Berwarna">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Excel</span>
            </a>

            {{-- Export PDF --}}
            <a href="{{ route('backend.keuangan.mahasiswa.export.pdf', request()->all()) }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[var(--primary-600)] hover:bg-[var(--primary-700)] shadow-xs transition-colors"
               title="Cetak atau Simpan Dokumen Rekap PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form id="filter-form" action="{{ route('backend.keuangan.mahasiswa.index') }}" method="GET" class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 flex flex-wrap items-center gap-3 shadow-card">
        <input type="hidden" name="per_page" id="filter-per-page" value="{{ request('per_page', $perPage ?? 10) }}">
        
        {{-- Search Input --}}
        <div class="relative flex-1 min-w-[220px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, email, atau no HP..." class="w-full pl-9 pr-4 py-2.5 border border-[var(--border)] bg-[var(--bg-page)] rounded-xl text-xs md:text-sm text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-2 focus:ring-[var(--primary-500)]/20 transition-all">
            <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        {{-- Filter Program Studi --}}
        <select name="prodi" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-xl px-3 py-2.5 text-xs md:text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Program Studi</option>
            @foreach($programStudis as $prodi)
                <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
            @endforeach
        </select>

        {{-- Filter Angkatan --}}
        <select name="angkatan" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-xl px-3 py-2.5 text-xs md:text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Angkatan</option>
            @foreach($angkatans as $angkatan)
                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>Angkatan {{ $angkatan }}</option>
            @endforeach
        </select>

        {{-- Filter Semester --}}
        <select name="semester" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-xl px-3 py-2.5 text-xs md:text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Semester</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>Semester {{ $semester }}</option>
            @endforeach
        </select>

        {{-- Filter Status Akademik --}}
        <select name="status" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] rounded-xl px-3 py-2.5 text-xs md:text-sm font-semibold focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Status Akademik</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ strtolower((string) request('status')) === strtolower((string) $status) ? 'selected' : '' }}>
                    {{ ucwords(strtolower((string) $status)) }}
                </option>
            @endforeach
        </select>

        {{-- Reset Filter Button --}}
        @if(request()->anyFilled(['search', 'prodi', 'angkatan', 'semester', 'status']))
            <a href="{{ route('backend.keuangan.mahasiswa.index') }}" class="px-3 py-2.5 text-xs font-semibold text-[var(--danger)] hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-900/50 transition-colors flex items-center gap-1.5" title="Reset Semua Filter">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Reset</span>
            </a>
        @endif
    </form>

    {{-- Table Container --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl overflow-hidden shadow-card">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs md:text-sm whitespace-nowrap" id="mhs-table">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-muted)] font-bold tracking-wider text-[10px] md:text-[11px] uppercase bg-[var(--bg-page)]/50">
                        <th class="py-4 pl-6 pr-4 font-bold">NIM</th>
                        <th class="py-4 px-5 font-bold">Nama Mahasiswa</th>
                        <th class="py-4 px-4 text-center font-bold">Angkatan</th>
                        <th class="py-4 px-5 font-bold">Program Studi & Semester</th>
                        <th class="py-4 px-5 text-center font-bold">SPP per Bulan</th>
                        <th class="py-4 px-5 font-bold">No HP / WhatsApp</th>
                        <th class="py-4 px-5 text-center font-bold">Status Akademik</th>
                        <th class="py-4 pl-3 pr-6 text-center font-bold min-w-[130px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]" id="mhs-tbody">
                    @php
                        $avatarColors = [
                            'bg-[var(--primary-100)] text-[var(--primary-700)] dark:bg-[var(--primary-900)]/40 dark:text-[var(--primary-300)]',
                            'bg-[var(--secondary-100)] text-[var(--secondary-700)] dark:bg-[var(--secondary-900)]/40 dark:text-[var(--secondary-300)]',
                            'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300',
                            'bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300',
                            'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300',
                            'bg-cyan-100 text-cyan-700 dark:bg-cyan-950/40 dark:text-cyan-300'
                        ];
                    @endphp
                    @forelse($mahasiswas as $index => $mhs)
                    @php
                        $statusLower = strtolower($mhs->status ?? 'aktif');
                        $nominalSpp = (float) ($mhs->nominal_spp ?? 300000);
                    @endphp
                    <tr class="hover:bg-[var(--primary-50)]/30 dark:hover:bg-[var(--primary-900)]/10 transition-colors">
                        {{-- NIM (pl-6 untuk padding lega sisi kiri) --}}
                        <td class="py-4 pl-6 pr-4 font-semibold text-[var(--text-heading)] tabular-nums">{{ $mhs->nim ?? '-' }}</td>
                        
                        {{-- Nama Mahasiswa & Email --}}
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $avatarColors[$index % count($avatarColors)] }} flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-[var(--text-heading)] truncate max-w-[200px]">{{ $mhs->name }}</p>
                                    <p class="text-[11px] text-[var(--text-muted)] truncate max-w-[200px]">{{ $mhs->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Angkatan --}}
                        <td class="py-4 px-4 text-center text-[var(--text-muted)] font-medium">{{ $mhs->angkatan ?? '-' }}</td>
                        
                        {{-- Program / Kelas & Semester --}}
                        <td class="py-4 px-5">
                            <p class="font-semibold text-[var(--text-heading)]">{{ $mhs->education['program_studi'] ?? 'Belum diset' }}</p>
                            <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                                @if(!empty($mhs->semester))
                                    Semester {{ $mhs->semester }}
                                @else
                                    Semester -
                                @endif
                            </p>
                        </td>

                        {{-- SPP per Bulan (Rp) (Badge Bersih Tanpa Catatan) --}}
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-100/90 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800/60 tabular-nums shadow-2xs">
                                Rp {{ number_format($nominalSpp, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- No HP / WA --}}
                        <td class="py-4 px-5">
                            @if(!empty($mhs->phone))
                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $mhs->phone);
                                    if (str_starts_with($waNumber, '0')) {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="inline-flex items-center gap-1.5 font-medium text-[var(--text-body)] hover:text-emerald-600 transition-colors" title="Hubungi via WhatsApp">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span class="tabular-nums">{{ $mhs->phone }}</span>
                                </a>
                            @else
                                <span class="text-[var(--text-muted)]">-</span>
                            @endif
                        </td>

                        {{-- Status Akademik (Kontras Tinggi & Jelas) --}}
                        <td class="py-4 px-5 text-center">
                            @if($statusLower === 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400 mr-1.5"></span>
                                    AKTIF
                                </span>
                            @elseif($statusLower === 'cuti')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300 border border-amber-300 dark:border-amber-700 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 dark:bg-amber-400 mr-1.5"></span>
                                    CUTI
                                </span>
                            @elseif($statusLower === 'nonaktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800 dark:bg-rose-950/70 dark:text-rose-300 border border-rose-300 dark:border-rose-700 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 dark:bg-rose-400 mr-1.5"></span>
                                    NONAKTIF
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-600 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span>
                                    {{ strtoupper($mhs->status ?? 'TIDAK AKTIF') }}
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Aksi (pr-6 untuk padding sisi kanan) --}}
                        <td class="py-4 pl-3 pr-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Tombol Edit Nominal SPP --}}
                                <button type="button" 
                                        onclick="openSppModal('{{ $mhs->id }}', '{{ addslashes($mhs->name) }}', '{{ $mhs->nim ?? '-' }}', '{{ $mhs->education['program_studi'] ?? '-' }}', '{{ $nominalSpp }}')"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-[var(--primary-700)] dark:text-[var(--primary-300)] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/40 hover:bg-[var(--primary-100)] dark:hover:bg-[var(--primary-900)]/70 border border-[var(--primary-200)] dark:border-[var(--primary-800)] rounded-lg transition-all shadow-xs"
                                        title="Atur / Edit Nominal SPP Mahasiswa">
                                    <svg class="w-3.5 h-3.5 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Tarif SPP</span>
                                </button>

                                {{-- Tombol Detail Mahasiswa --}}
                                <button type="button" 
                                        onclick="openDetailModal('{{ addslashes($mhs->name) }}', '{{ $mhs->nim ?? '-' }}', '{{ $mhs->email ?? '-' }}', '{{ $mhs->phone ?? '-' }}', '{{ $mhs->education['program_studi'] ?? '-' }}', '{{ $mhs->angkatan ?? '-' }}', '{{ $mhs->semester ?? '-' }}', '{{ strtoupper($mhs->status ?? 'AKTIF') }}', '{{ number_format($nominalSpp, 0, ',', '.') }}')"
                                        class="p-1.5 text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--bg-page)] border border-transparent hover:border-[var(--border)] rounded-lg transition" 
                                        title="Lihat Detail Profil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-[var(--text-muted)] text-sm">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-[var(--bg-page)] flex items-center justify-center text-[var(--text-muted)] mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="font-medium text-[var(--text-heading)]">Data mahasiswa tidak ditemukan</span>
                                <span class="text-xs text-[var(--text-muted)] mt-0.5">Coba ubah kata kunci pencarian atau sesuaikan opsi filter status.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Count + Pagination --}}
        <div class="px-6 py-4 border-t border-[var(--border)] flex flex-wrap items-center justify-between gap-4">
            <p class="text-xs text-[var(--text-muted)] font-medium">
                Total <span class="font-bold text-[var(--text-heading)]">{{ $mahasiswas->total() }}</span> data mahasiswa
                @if($mahasiswas->total() > 0)
                    <span class="opacity-75">(Menampilkan baris {{ $mahasiswas->firstItem() }}-{{ $mahasiswas->lastItem() }})</span>
                @endif
            </p>
            
            <div class="flex items-center gap-2">
                {{ $mahasiswas->links('partials.pagination-keuangan') }}

                <select id="per-page-select" onchange="document.getElementById('filter-per-page').value = this.value; document.getElementById('filter-form').submit();" class="border border-[var(--border)] bg-[var(--surface)] text-[var(--text-body)] rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-[var(--primary-500)] ml-2 cursor-pointer transition-colors shadow-xs">
                    <option value="10" {{ request('per_page', $perPage ?? 10) == 10 ? 'selected' : '' }}>10 / halaman</option>
                    <option value="25" {{ request('per_page', $perPage ?? 10) == 25 ? 'selected' : '' }}>25 / halaman</option>
                    <option value="50" {{ request('per_page', $perPage ?? 10) == 50 ? 'selected' : '' }}>50 / halaman</option>
                    <option value="100" {{ request('per_page', $perPage ?? 10) == 100 ? 'selected' : '' }}>100 / halaman</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- MODAL EDIT NOMINAL SPP MAHASISWA                                      --}}
{{-- ===================================================================== --}}
<div id="modal-spp" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity duration-200 hidden" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all">
        
        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-[var(--border)] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[var(--text-heading)]">Atur Tarif SPP Mahasiswa</h3>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Penetapan besaran tarif tagihan SPP bulanan</p>
                </div>
            </div>
            <button type="button" onclick="closeSppModal()" class="text-[var(--text-muted)] hover:text-[var(--text-heading)] p-1.5 rounded-lg hover:bg-[var(--bg-page)] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Edit SPP --}}
        <form id="form-update-spp" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-4">
                {{-- Preview Info Mahasiswa (Read-only) --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-xl p-3.5 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Mahasiswa</p>
                        <p id="modal-mhs-name" class="text-sm font-bold text-[var(--text-heading)] mt-0.5">-</p>
                        <p id="modal-mhs-nim-prodi" class="text-xs text-[var(--text-muted)] mt-0.5">-</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            Sinkron Admin
                        </span>
                    </div>
                </div>

                {{-- Input Nominal SPP --}}
                <div>
                    <label for="nominal_spp" class="block text-xs font-bold text-[var(--text-heading)] uppercase tracking-wider mb-1.5">
                        Nominal SPP per Bulan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-[var(--text-muted)]">Rp</span>
                        <input type="number" 
                               id="nominal_spp" 
                               name="nominal_spp" 
                               min="0" 
                               step="1000"
                               required
                               placeholder="Contoh: 300000"
                               class="w-full pl-11 pr-4 py-2.5 text-sm font-bold text-[var(--text-heading)] tabular-nums bg-[var(--surface)] border border-[var(--border)] rounded-xl focus:outline-none focus:border-[var(--primary-500)] focus:ring-2 focus:ring-[var(--primary-500)]/20 transition-all">
                    </div>
                    
                    {{-- Quick Preset Buttons --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                        <span class="text-[10px] text-[var(--text-muted)] font-medium mr-1">Pilihan Cepat:</span>
                        <button type="button" onclick="setNominalPreset(200000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition">200rb</button>
                        <button type="button" onclick="setNominalPreset(250000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition">250rb</button>
                        <button type="button" onclick="setNominalPreset(300000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-300)] transition">300rb (Std)</button>
                        <button type="button" onclick="setNominalPreset(350000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition">350rb</button>
                        <button type="button" onclick="setNominalPreset(400000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition">400rb</button>
                        <button type="button" onclick="setNominalPreset(500000)" class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[var(--bg-page)] hover:bg-[var(--primary-50)] text-[var(--text-body)] border border-[var(--border)] transition">500rb</button>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-[var(--bg-page)]/50 border-t border-[var(--border)] flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeSppModal()" class="px-4 py-2.5 text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--border)] rounded-xl border border-[var(--border)] transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-[var(--primary-600)] hover:bg-[var(--primary-700)] rounded-xl shadow-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Tarif SPP</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- MODAL DETAIL MAHASISWA                                                --}}
{{-- ===================================================================== --}}
<div id="modal-detail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity duration-200 hidden" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[var(--border)] flex items-center justify-between">
            <h3 class="text-base font-bold text-[var(--text-heading)]">Detail Profil Mahasiswa</h3>
            <button type="button" onclick="closeDetailModal()" class="text-[var(--text-muted)] hover:text-[var(--text-heading)] p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3.5 text-xs">
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">Nama Lengkap</span>
                <span id="detail-name" class="font-bold text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">NIM</span>
                <span id="detail-nim" class="font-bold text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">Program Studi</span>
                <span id="detail-prodi" class="font-semibold text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">Angkatan / Semester</span>
                <span id="detail-angkatan-semester" class="font-semibold text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">Status Akademik</span>
                <span id="detail-status" class="font-bold text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">Tarif SPP per Bulan</span>
                <span id="detail-spp" class="font-bold text-emerald-600 dark:text-emerald-400 text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-[var(--border)]">
                <span class="text-[var(--text-muted)] font-medium">No HP / WhatsApp</span>
                <span id="detail-phone" class="font-medium text-[var(--text-heading)] text-right">-</span>
            </div>
            <div class="flex justify-between py-1.5">
                <span class="text-[var(--text-muted)] font-medium">Email</span>
                <span id="detail-email" class="font-medium text-[var(--text-heading)] text-right">-</span>
            </div>
        </div>
        <div class="px-6 py-3 bg-[var(--bg-page)]/50 border-t border-[var(--border)] flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--border)] rounded-xl border border-[var(--border)] transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openSppModal(id, name, nim, prodi, currentNominal) {
    var modal = document.getElementById('modal-spp');
    var form = document.getElementById('form-update-spp');
    
    // Set Action Form URL
    form.action = "{{ url('/backend/keuangan/mahasiswa') }}/" + id + "/nominal-spp";

    // Set Preview Content
    document.getElementById('modal-mhs-name').innerText = name;
    document.getElementById('modal-mhs-nim-prodi').innerText = "NIM: " + nim + " • " + prodi;
    document.getElementById('nominal_spp').value = currentNominal;

    // Show modal
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

function openDetailModal(name, nim, email, phone, prodi, angkatan, semester, status, spp) {
    document.getElementById('detail-name').innerText = name;
    document.getElementById('detail-nim').innerText = nim;
    document.getElementById('detail-prodi').innerText = prodi;
    document.getElementById('detail-angkatan-semester').innerText = "Angkatan " + angkatan + " • Semester " + semester;
    document.getElementById('detail-status').innerText = status;
    document.getElementById('detail-spp').innerText = "Rp " + spp;
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

// Close modals on Escape key or clicking outside backdrop
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
