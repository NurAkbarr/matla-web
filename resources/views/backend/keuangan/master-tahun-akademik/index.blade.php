@extends('layouts.keuangan')

@section('title', 'Master Tahun Akademik & Semester')

@section('content')
<div class="space-y-6">

    {{-- Header Area Container --}}
    <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">Master tahun akademik & semester</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 shadow-2xs">
                    Keuangan & Rekap SPP
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-[var(--text-muted)] mt-1">
                Kelola daftar tahun akademik, periode semester ganjil/genap, serta tentukan tahun dan semester aktif default untuk rekap SPP.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <a href="{{ route('backend.keuangan.rekap-spp.index') }}" 
               class="h-9 sm:h-10 inline-flex items-center gap-2 px-3.5 bg-white dark:bg-[var(--surface)] border border-slate-300 dark:border-[var(--border)] hover:bg-slate-50 dark:hover:bg-[var(--bg-page)] text-slate-700 dark:text-[var(--text-body)] text-xs font-semibold rounded-lg transition shadow-2xs cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Lihat Rekap SPP</span>
            </a>

            <button type="button" 
                    onclick="openCreateTaModal()" 
                    class="h-9 sm:h-10 inline-flex items-center gap-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Tahun Akademik</span>
            </button>
        </div>
    </div>

    {{-- Daftar Tahun Akademik & Semesternya --}}
    <div class="space-y-5">
        @forelse($tahunAkademiks as $ta)
            <div class="bg-white dark:bg-[var(--surface)] border {{ $ta->is_active ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-[var(--border)]' }} rounded-xl shadow-sm overflow-hidden transition-all duration-200">
                
                {{-- Header Bar Tahun Akademik --}}
                <div class="px-5 py-4 sm:py-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $ta->is_active ? 'bg-emerald-50/40 dark:bg-emerald-950/20 border-b border-emerald-200/70 dark:border-emerald-900/40' : 'bg-slate-50/80 dark:bg-[var(--bg-page)]/50 border-b border-slate-200 dark:border-[var(--border)]' }}">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-lg {{ $ta->is_active ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[var(--primary-50)] text-slate-700 dark:text-[var(--primary-700)] border border-slate-200 dark:border-transparent' }} flex items-center justify-center font-bold text-xs tracking-wider shrink-0">
                            TA
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="text-base font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">
                                    Tahun Akademik {{ $ta->tahun }}
                                </h2>
                                @if($ta->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-2xs">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                                        </span>
                                        Tahun Aktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-[var(--text-muted)] mt-0.5">
                                Terdiri dari {{ $ta->semesters->count() }} semester terdaftar
                            </p>
                        </div>
                    </div>

                    {{-- Actions for TA --}}
                    <div class="flex items-center gap-2 self-start sm:self-center flex-wrap">
                        @if(!$ta->is_active)
                            <form action="{{ route('backend.keuangan.master-tahun-akademik.set-active', $ta->id) }}" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" 
                                        class="h-8 px-3 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-lg transition cursor-pointer shadow-2xs" 
                                        title="Jadikan Tahun Akademik Aktif">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Aktifkan TA</span>
                                </button>
                            </form>
                        @endif

                        <button type="button" 
                                onclick="openCreateSemesterModal({{ $ta->id }}, '{{ $ta->tahun }}')" 
                                class="h-8 px-3 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 border border-slate-300 rounded-lg transition cursor-pointer shadow-2xs"
                                title="Tambah Semester">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Semester</span>
                        </button>

                        <button type="button" 
                                onclick="openEditTaModal({{ $ta->id }}, '{{ $ta->tahun }}', {{ $ta->is_active ? 'true' : 'false' }})" 
                                class="h-8 px-2.5 inline-flex items-center gap-1 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 border border-slate-300 rounded-lg transition cursor-pointer shadow-2xs"
                                title="Ubah Tahun Akademik">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </button>

                        <form action="{{ route('backend.keuangan.master-tahun-akademik.destroy', $ta->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tahun Akademik {{ $ta->tahun }} beserta seluruh semesternya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="h-8 px-2.5 inline-flex items-center gap-1 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 hover:text-rose-800 border border-rose-200 hover:border-rose-300 rounded-lg transition cursor-pointer shadow-2xs"
                                    title="Hapus Tahun Akademik">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Tabel Semester di dalam Tahun Akademik Ini --}}
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-slate-700 dark:text-[var(--text-muted)] uppercase tracking-wider">
                            Daftar Semester
                        </h3>
                        <span class="text-[11px] text-slate-500 dark:text-[var(--text-muted)]">
                            Total {{ $ta->semesters->count() }} Semester
                        </span>
                    </div>
                    
                    <div class="border border-slate-200 dark:border-[var(--border)] rounded-lg overflow-hidden bg-white dark:bg-[var(--surface)] shadow-2xs">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-[var(--bg-page)] text-slate-700 dark:text-[var(--text-body)] font-bold border-b border-slate-200 dark:border-[var(--border)]">
                                        <th class="py-3 px-3.5 w-12 text-center border-r border-slate-200/70 dark:border-[var(--border)]">No</th>
                                        <th class="py-3 px-4 min-w-[160px] border-r border-slate-200/70 dark:border-[var(--border)]">Nama Semester</th>
                                        <th class="py-3 px-3.5 w-24 border-r border-slate-200/70 dark:border-[var(--border)]">Tipe</th>
                                        <th class="py-3 px-4 min-w-[200px] border-r border-slate-200/70 dark:border-[var(--border)]">Rentang Bulan</th>
                                        <th class="py-3 px-3 text-center w-36 border-r border-slate-200/70 dark:border-[var(--border)]">Status</th>
                                        <th class="py-3 px-4 text-center w-64">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-[var(--border)]">
                                    @forelse($ta->semesters as $sIndex => $sem)
                                    @php
                                        $monthNames = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        $rentang = ($monthNames[$sem->bulan_mulai] ?? $sem->bulan_mulai) . ' – ' . ($monthNames[$sem->bulan_selesai] ?? $sem->bulan_selesai);
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-[var(--primary-50)]/30 transition-colors">
                                        {{-- No --}}
                                        <td class="py-3 px-3.5 text-center text-slate-500 dark:text-[var(--text-muted)] tabular-nums border-r border-slate-200/70 dark:border-[var(--border)]">
                                            {{ $sIndex + 1 }}
                                        </td>

                                        {{-- Nama Semester --}}
                                        <td class="py-3 px-4 font-bold text-slate-900 dark:text-[var(--text-heading)] border-r border-slate-200/70 dark:border-[var(--border)]">
                                            {{ $sem->nama_semester }}
                                        </td>

                                        {{-- Tipe --}}
                                        <td class="py-3 px-3.5 border-r border-slate-200/70 dark:border-[var(--border)]">
                                            @if(strtolower($sem->tipe) === 'ganjil')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                    Ganjil
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800">
                                                    Genap
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Rentang Bulan --}}
                                        <td class="py-3 px-4 text-slate-600 dark:text-[var(--text-muted)] border-r border-slate-200/70 dark:border-[var(--border)]">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $rentang }}</span>
                                                <span class="text-[11px] text-slate-400">({{ $sem->bulan_mulai }} s/d {{ $sem->bulan_selesai }})</span>
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-3 px-3 text-center border-r border-slate-200/70 dark:border-[var(--border)]">
                                            @if($sem->is_active && $ta->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-2xs">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                                    Aktif Default
                                                </span>
                                            @elseif($sem->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-50 text-slate-700 border border-slate-300 dark:bg-slate-900/40 dark:text-slate-300 dark:border-slate-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Aktif di TA
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium text-slate-400 bg-slate-50/50 border border-slate-200 dark:border-slate-800">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                @if(!$sem->is_active || !$ta->is_active)
                                                    <form action="{{ route('backend.keuangan.master-semester.set-active', $sem->id) }}" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-emerald-700 hover:border-emerald-300 border border-slate-300 rounded-md transition shadow-2xs cursor-pointer"
                                                                title="Jadikan semester ini sebagai default rekap SPP">
                                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            <span>Pilih Aktif</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-emerald-700 bg-emerald-50/80 border border-emerald-200 rounded-md select-none"
                                                          title="Semester ini sedang aktif sebagai default">
                                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Aktif</span>
                                                    </span>
                                                @endif

                                                <button type="button" 
                                                        onclick="openEditSemesterModal({{ $sem->id }}, '{{ $sem->nama_semester }}', '{{ $sem->tipe }}', {{ $sem->bulan_mulai }}, {{ $sem->bulan_selesai }}, {{ $sem->is_active ? 'true' : 'false' }})" 
                                                        class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 border border-slate-300 rounded-md transition shadow-2xs cursor-pointer"
                                                        title="Ubah data semester">
                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    <span>Edit</span>
                                                </button>

                                                <form action="{{ route('backend.keuangan.master-semester.destroy', $sem->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semester {{ $sem->nama_semester }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 hover:text-rose-800 border border-rose-200 hover:border-rose-300 rounded-md transition shadow-2xs cursor-pointer"
                                                            title="Hapus semester">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-xs text-slate-500 dark:text-[var(--text-muted)]">
                                            Belum ada semester terdaftar pada Tahun Akademik ini. Silakan klik tombol <b class="text-slate-700 dark:text-slate-300">+ Semester</b> di kanan atas kartu.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl p-12 text-center shadow-sm">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-bold text-sm text-slate-900 dark:text-[var(--text-heading)]">Belum ada tahun akademik terdaftar.</p>
                <p class="text-xs text-slate-500 dark:text-[var(--text-muted)] mt-1">Klik tombol Tambah Tahun Akademik di atas untuk membuat data baru.</p>
            </div>
        @endforelse
    </div>

</div>

{{-- MODAL TAMBAH TAHUN AKADEMIK --}}
<div id="modal-create-ta" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-[var(--border)] flex items-center justify-between bg-slate-50/70 dark:bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">Tambah Tahun Akademik</h3>
            <button type="button" onclick="closeModal('modal-create-ta')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-[var(--text-heading)] rounded-md cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('backend.keuangan.master-tahun-akademik.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Tahun Akademik <span class="text-red-500">*</span></label>
                <input type="text" name="tahun" placeholder="Contoh: 2026/2027" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition">
                <p class="text-[11px] text-slate-500 dark:text-[var(--text-muted)] mt-1.5">Format: YYYY/YYYY (misal: 2026/2027). Dua semester default (Ganjil & Genap) akan otomatis dibuat.</p>
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="create_ta_is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                <label for="create_ta_is_active" class="text-xs font-medium text-slate-700 dark:text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Tahun Akademik Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-create-ta')" class="h-9 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition cursor-pointer shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shadow-sm">
                    Simpan Tahun Akademik
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT TAHUN AKADEMIK --}}
<div id="modal-edit-ta" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-[var(--border)] flex items-center justify-between bg-slate-50/70 dark:bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">Edit Tahun Akademik</h3>
            <button type="button" onclick="closeModal('modal-edit-ta')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-[var(--text-heading)] rounded-md cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-ta" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Tahun Akademik <span class="text-red-500">*</span></label>
                <input type="text" id="edit_ta_tahun" name="tahun" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition">
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="edit_ta_is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                <label for="edit_ta_is_active" class="text-xs font-medium text-slate-700 dark:text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Tahun Akademik Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-edit-ta')" class="h-9 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition cursor-pointer shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shadow-sm">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH SEMESTER --}}
<div id="modal-create-semester" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-[var(--border)] flex items-center justify-between bg-slate-50/70 dark:bg-[var(--bg-page)]">
            <div>
                <h3 class="text-sm font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">Tambah Semester</h3>
                <p id="create_sem_ta_label" class="text-[11px] text-slate-500 dark:text-[var(--text-muted)] mt-0.5">Tahun Akademik: -</p>
            </div>
            <button type="button" onclick="closeModal('modal-create-semester')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-[var(--text-heading)] rounded-md cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-create-semester" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Nama Semester <span class="text-red-500">*</span></label>
                <input type="text" name="nama_semester" placeholder="Misal: Ganjil (Jul-Des) atau Pendek" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Tipe Semester <span class="text-red-500">*</span></label>
                <select name="tipe" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Bulan Mulai <span class="text-red-500">*</span></label>
                    <select name="bulan_mulai" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                        <option value="7">7 - Juli</option>
                        <option value="1">1 - Januari</option>
                        <option value="2">2 - Februari</option>
                        <option value="3">3 - Maret</option>
                        <option value="4">4 - April</option>
                        <option value="5">5 - Mei</option>
                        <option value="6">6 - Juni</option>
                        <option value="8">8 - Agustus</option>
                        <option value="9">9 - September</option>
                        <option value="10">10 - Oktober</option>
                        <option value="11">11 - November</option>
                        <option value="12">12 - Desember</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Bulan Selesai <span class="text-red-500">*</span></label>
                    <select name="bulan_selesai" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                        <option value="12">12 - Desember</option>
                        <option value="6">6 - Juni</option>
                        <option value="1">1 - Januari</option>
                        <option value="2">2 - Februari</option>
                        <option value="3">3 - Maret</option>
                        <option value="4">4 - April</option>
                        <option value="5">5 - Mei</option>
                        <option value="7">7 - Juli</option>
                        <option value="8">8 - Agustus</option>
                        <option value="9">9 - September</option>
                        <option value="10">10 - Oktober</option>
                        <option value="11">11 - November</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="create_sem_is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                <label for="create_sem_is_active" class="text-xs font-medium text-slate-700 dark:text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Semester Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-create-semester')" class="h-9 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition cursor-pointer shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shadow-sm">
                    Simpan Semester
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT SEMESTER --}}
<div id="modal-edit-semester" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white dark:bg-[var(--surface)] border border-slate-200 dark:border-[var(--border)] rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-[var(--border)] flex items-center justify-between bg-slate-50/70 dark:bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-slate-900 dark:text-[var(--text-heading)]">Edit Semester</h3>
            <button type="button" onclick="closeModal('modal-edit-semester')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-[var(--text-heading)] rounded-md cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-semester" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Nama Semester <span class="text-red-500">*</span></label>
                <input type="text" id="edit_sem_nama" name="nama_semester" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Tipe Semester <span class="text-red-500">*</span></label>
                <select id="edit_sem_tipe" name="tipe" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Bulan Mulai <span class="text-red-500">*</span></label>
                    <select id="edit_sem_mulai" name="bulan_mulai" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                        <option value="1">1 - Januari</option>
                        <option value="2">2 - Februari</option>
                        <option value="3">3 - Maret</option>
                        <option value="4">4 - April</option>
                        <option value="5">5 - Mei</option>
                        <option value="6">6 - Juni</option>
                        <option value="7">7 - Juli</option>
                        <option value="8">8 - Agustus</option>
                        <option value="9">9 - September</option>
                        <option value="10">10 - Oktober</option>
                        <option value="11">11 - November</option>
                        <option value="12">12 - Desember</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-[var(--text-heading)] mb-1.5">Bulan Selesai <span class="text-red-500">*</span></label>
                    <select id="edit_sem_selesai" name="bulan_selesai" required class="w-full h-10 px-3.5 bg-white dark:bg-[var(--bg-page)] border border-slate-300 dark:border-[var(--border)] rounded-lg text-xs text-slate-800 dark:text-[var(--text-body)] focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/15 transition cursor-pointer">
                        <option value="1">1 - Januari</option>
                        <option value="2">2 - Februari</option>
                        <option value="3">3 - Maret</option>
                        <option value="4">4 - April</option>
                        <option value="5">5 - Mei</option>
                        <option value="6">6 - Juni</option>
                        <option value="7">7 - Juli</option>
                        <option value="8">8 - Agustus</option>
                        <option value="9">9 - September</option>
                        <option value="10">10 - Oktober</option>
                        <option value="11">11 - November</option>
                        <option value="12">12 - Desember</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="edit_sem_is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                <label for="edit_sem_is_active" class="text-xs font-medium text-slate-700 dark:text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Semester Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200 dark:border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-edit-semester')" class="h-9 px-3.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-semibold transition cursor-pointer shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shadow-sm">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function openCreateTaModal() {
        document.getElementById('modal-create-ta').style.display = 'flex';
    }

    function openEditTaModal(id, tahun, isActive) {
        document.getElementById('form-edit-ta').action = '/backend/keuangan/master-tahun-akademik/' + id;
        document.getElementById('edit_ta_tahun').value = tahun;
        document.getElementById('edit_ta_is_active').checked = isActive;
        document.getElementById('modal-edit-ta').style.display = 'flex';
    }

    function openCreateSemesterModal(taId, taTahun) {
        document.getElementById('form-create-semester').action = '/backend/keuangan/master-tahun-akademik/' + taId + '/semester';
        document.getElementById('create_sem_ta_label').innerText = 'Tahun Akademik: ' + taTahun;
        document.getElementById('modal-create-semester').style.display = 'flex';
    }

    function openEditSemesterModal(id, nama, tipe, mulai, selesai, isActive) {
        document.getElementById('form-edit-semester').action = '/backend/keuangan/master-semester/' + id;
        document.getElementById('edit_sem_nama').value = nama;
        document.getElementById('edit_sem_tipe').value = tipe;
        document.getElementById('edit_sem_mulai').value = mulai;
        document.getElementById('edit_sem_selesai').value = selesai;
        document.getElementById('edit_sem_is_active').checked = isActive;
        document.getElementById('modal-edit-semester').style.display = 'flex';
    }
</script>
@endsection
