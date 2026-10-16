@extends('layouts.keuangan')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl px-6 py-5 flex items-center gap-4 shadow-card">
        <div class="w-12 h-12 rounded-full bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 border border-[var(--primary-200)]/80 dark:border-[var(--primary-800)]/50 flex items-center justify-center flex-shrink-0 text-[var(--primary-500)]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-[var(--text-heading)] uppercase tracking-wider">Master Data Mahasiswa</p>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Kelola dan lihat seluruh data mahasiswa terdaftar.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form id="filter-form" action="{{ route('backend.keuangan.mahasiswa.index') }}" method="GET" class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-3.5 flex flex-wrap items-center gap-2.5 shadow-card">
        <input type="hidden" name="per_page" id="filter-per-page" value="{{ request('per_page', $perPage ?? 10) }}">
        
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, atau email..." class="w-full pl-9 pr-4 py-2 border border-[var(--border)] bg-[var(--background)] rounded-xl text-sm text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-2 focus:ring-[var(--primary-500)]/20 transition-all">
            <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select name="prodi" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--background)] text-[var(--text-body)] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Program Studi</option>
            @foreach($programStudis as $prodi)
                <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
            @endforeach
        </select>

        <select name="angkatan" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--background)] text-[var(--text-body)] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Angkatan</option>
            @foreach($angkatans as $angkatan)
                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>{{ $angkatan }}</option>
            @endforeach
        </select>

        <select name="semester" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--background)] text-[var(--text-body)] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Semester</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>Semester {{ $semester }}</option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()" class="border border-[var(--border)] bg-[var(--background)] text-[var(--text-body)] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[var(--primary-500)] cursor-pointer transition-colors">
            <option value="">Semua Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucwords(strtolower($status)) }}</option>
            @endforeach
        </select>

        @if(request()->anyFilled(['search', 'prodi', 'angkatan', 'semester', 'status']))
            <a href="{{ route('backend.keuangan.mahasiswa.index') }}" class="px-3 py-2 text-xs font-semibold text-[var(--danger)] hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-900/50 transition-colors flex items-center gap-1.5" title="Reset Filter">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Reset</span>
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl overflow-hidden shadow-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap" id="mhs-table">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-muted)] font-bold tracking-wider text-[10px] uppercase bg-[var(--surface)]">
                        <th class="py-4 px-5">NIM</th>
                        <th class="py-4 px-5">Nama Lengkap</th>
                        <th class="py-4 px-5">Prodi</th>
                        <th class="py-4 px-5 text-center">Semester</th>
                        <th class="py-4 px-5">Jenis Kelamin</th>
                        <th class="py-4 px-5">Email</th>
                        <th class="py-4 px-5 text-center">Status Akademik</th>
                        <th class="py-4 px-5 text-center">Aksi</th>
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
                    <tr class="hover:bg-[var(--primary-50)]/40 dark:hover:bg-[var(--primary-900)]/10 transition-colors">
                        <td class="py-4 px-5 font-semibold text-[var(--text-muted)]">{{ $mhs->nim ?? '-' }}</td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $avatarColors[$index % count($avatarColors)] }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-[var(--text-heading)]">{{ $mhs->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-[var(--text-muted)]">{{ $mhs->education['program_studi'] ?? '-' }}</td>
                        <td class="py-4 px-5 text-center text-[var(--text-muted)]">{{ $mhs->semester ?? '-' }}</td>
                        <td class="py-4 px-5 text-[var(--text-muted)]">{{ $mhs->jenis_kelamin ?? '-' }}</td>
                        <td class="py-4 px-5 text-[var(--text-muted)]">{{ $mhs->email ?? '-' }}</td>
                        <td class="py-4 px-5 text-center">
                            @php $statusLower = strtolower($mhs->status ?? 'aktif'); @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                @if($statusLower === 'aktif') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/40
                                @elseif($statusLower === 'cuti') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/80 dark:border-amber-800/40
                                @elseif($statusLower === 'nonaktif') bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200/80 dark:border-rose-800/40
                                @else bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700 @endif">
                                {{ strtoupper($mhs->status ?? 'AKTIF') }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center justify-center gap-1">
                                <button class="p-1.5 text-[var(--text-muted)] hover:text-[var(--primary-500)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 rounded-lg transition" title="Lihat Detail">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button class="p-1.5 text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 rounded-lg transition" title="Menu">
                                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-[var(--text-muted)] text-sm">
                            @if(request()->anyFilled(['search', 'prodi', 'angkatan', 'semester', 'status']))
                                Data mahasiswa tidak ditemukan dengan filter yang dipilih.
                            @else
                                Belum ada data mahasiswa yang terdaftar.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Count + Pagination --}}
        <div class="px-5 py-4 border-t border-[var(--border)] flex flex-wrap items-center justify-between gap-4">
            <p class="text-xs text-[var(--text-muted)] font-medium">
                Total <span class="font-bold text-[var(--text-heading)]">{{ $mahasiswas->total() }}</span> data mahasiswa
                @if($mahasiswas->total() > 0)
                    <span class="opacity-75">(Menampilkan {{ $mahasiswas->firstItem() }}-{{ $mahasiswas->lastItem() }})</span>
                @endif
            </p>
            
            <div class="flex items-center gap-2">
                {{ $mahasiswas->links('partials.pagination-keuangan') }}

                <select id="per-page-select" onchange="document.getElementById('filter-per-page').value = this.value; document.getElementById('filter-form').submit();" class="border border-[var(--border)] bg-[var(--surface)] text-[var(--text-body)] rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-[var(--primary-500)] ml-2 cursor-pointer transition-colors">
                    <option value="10" {{ request('per_page', $perPage ?? 10) == 10 ? 'selected' : '' }}>10 / halaman</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / halaman</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / halaman</option>
                </select>
            </div>
        </div>
    </div>
</div>
@endsection
