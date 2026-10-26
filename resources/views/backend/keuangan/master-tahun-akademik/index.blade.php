@extends('layouts.keuangan')

@section('title', 'Master Tahun Akademik & Semester')

@section('content')
<div class="space-y-6">

    {{-- Header Area Container (Clean Enterprise, High Contrast MATLA Design Tokens) --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Master Tahun Akademik & Semester</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-bold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)]">
                    Keuangan & Rekap SPP
                </span>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                Kelola daftar tahun akademik, periode semester ganjil/genap, serta tentukan tahun dan semester aktif default untuk rekap SPP.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <a href="{{ route('backend.keuangan.rekap-spp.index') }}" 
               class="h-9 sm:h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Lihat Rekap SPP</span>
            </a>

            <button type="button" 
                    onclick="openCreateTaModal()" 
                    class="h-9 sm:h-10 inline-flex items-center gap-2 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Tahun Akademik</span>
            </button>
        </div>
    </div>

    {{-- Daftar Tahun Akademik & Semesternya --}}
    <div class="space-y-5">
        @forelse($tahunAkademiks as $ta)
            <div class="bg-[var(--surface)] border {{ $ta->is_active ? 'border-2 border-[var(--primary-500)]' : 'border border-[var(--border)]' }} rounded-[var(--radius-sm)] overflow-hidden transition-all duration-150">
                
                {{-- Header Bar Tahun Akademik --}}
                <div class="px-5 py-4 sm:py-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $ta->is_active ? 'bg-[var(--primary-50)]/50 border-b border-[var(--border)]' : 'bg-[var(--surface)] border-b border-[var(--border)]' }}">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-[var(--radius-xs)] {{ $ta->is_active ? 'bg-[var(--primary-500)] text-white' : 'bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)]' }} flex items-center justify-center font-bold text-xs tracking-wider shrink-0">
                            TA
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="text-base font-bold font-display text-[var(--text-heading)]">
                                    Tahun Akademik {{ $ta->tahun }}
                                </h2>
                                @if($ta->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)]"></span>
                                        Tahun Aktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">
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
                                        class="h-8 px-3 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--primary-700)] bg-[var(--primary-50)] hover:bg-[var(--primary-100)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] transition cursor-pointer" 
                                        title="Jadikan Tahun Akademik Aktif">
                                    <svg class="w-3.5 h-3.5 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Aktifkan TA</span>
                                </button>
                            </form>
                        @endif

                        <button type="button" 
                                onclick="openCreateSemesterModal({{ $ta->id }}, '{{ $ta->tahun }}')" 
                                class="h-8 px-3 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--text-body)] bg-[var(--surface)] hover:bg-[var(--primary-50)] border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer"
                                title="Tambah Semester">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Semester</span>
                        </button>

                        <button type="button" 
                                onclick="openEditTaModal({{ $ta->id }}, '{{ $ta->tahun }}', {{ $ta->is_active ? 'true' : 'false' }})" 
                                class="h-8 px-2.5 inline-flex items-center gap-1 text-xs font-semibold text-[var(--text-body)] bg-[var(--surface)] hover:bg-[var(--primary-50)] border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer"
                                title="Ubah Tahun Akademik">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </button>

                        <form action="{{ route('backend.keuangan.master-tahun-akademik.destroy', $ta->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tahun Akademik {{ $ta->tahun }} beserta seluruh semesternya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="h-8 px-2.5 inline-flex items-center gap-1 text-xs font-semibold text-rose-700 bg-[var(--surface)] hover:bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] transition cursor-pointer"
                                    title="Hapus Tahun Akademik">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Tabel Semester di dalam Tahun Akademik Ini --}}
                <div class="p-5 bg-[var(--surface)]">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-[var(--text-heading)] uppercase tracking-wider">
                            Daftar Semester
                        </h3>
                        <span class="text-[11px] text-[var(--text-muted)]">
                            Total {{ $ta->semesters->count() }} Semester
                        </span>
                    </div>
                    
                    <div class="border border-[var(--border)] rounded-[var(--radius-xs)] overflow-hidden bg-[var(--surface)]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-[var(--bg-page)] text-[var(--text-heading)] font-bold border-b border-[var(--border)]">
                                        <th class="py-3 px-3.5 w-12 text-center border-r border-[var(--border)]">No</th>
                                        <th class="py-3 px-4 min-w-[160px] border-r border-[var(--border)]">Nama Semester</th>
                                        <th class="py-3 px-3.5 w-24 text-center border-r border-[var(--border)]">Tipe</th>
                                        <th class="py-3 px-4 min-w-[200px] border-r border-[var(--border)]">Rentang Bulan</th>
                                        <th class="py-3 px-3 text-center w-36 border-r border-[var(--border)]">Status</th>
                                        <th class="py-3 px-4 text-center w-64">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    @forelse($ta->semesters as $sIndex => $sem)
                                    @php
                                        $monthNames = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        $rentang = ($monthNames[$sem->bulan_mulai] ?? $sem->bulan_mulai) . ' – ' . ($monthNames[$sem->bulan_selesai] ?? $sem->bulan_selesai);
                                    @endphp
                                    <tr class="hover:bg-[var(--primary-50)]/40 transition-colors bg-[var(--surface)]">
                                        {{-- No --}}
                                        <td class="py-3 px-3.5 text-center text-[var(--text-muted)] tabular-nums border-r border-[var(--border)]">
                                            {{ $sIndex + 1 }}
                                        </td>

                                        {{-- Nama Semester --}}
                                        <td class="py-3 px-4 font-bold text-[var(--text-heading)] border-r border-[var(--border)]">
                                            {{ $sem->nama_semester }}
                                        </td>

                                        {{-- Tipe --}}
                                        <td class="py-3 px-3.5 text-center border-r border-[var(--border)]">
                                            @if(strtolower($sem->tipe) === 'ganjil')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800">
                                                    Ganjil
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800">
                                                    Genap
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Rentang Bulan --}}
                                        <td class="py-3 px-4 text-[var(--text-body)] border-r border-[var(--border)]">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-[var(--text-muted)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="font-medium text-[var(--text-heading)]">{{ $rentang }}</span>
                                                <span class="text-[11px] text-[var(--text-muted)]">({{ $sem->bulan_mulai }} s/d {{ $sem->bulan_selesai }})</span>
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-3 px-3 text-center border-r border-[var(--border)]">
                                            @if($sem->is_active && $ta->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)]"></span>
                                                    Aktif Default
                                                </span>
                                            @elseif($sem->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--text-muted)]"></span>
                                                    Aktif di TA
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium text-[var(--text-muted)] bg-[var(--bg-page)] border border-[var(--border)]">
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
                                                                class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-[var(--text-body)] bg-[var(--surface)] hover:bg-[var(--primary-50)] hover:text-[var(--primary-700)] border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer"
                                                                title="Jadikan semester ini sebagai default rekap SPP">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            <span>Pilih Aktif</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-[var(--primary-700)] bg-[var(--primary-50)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] select-none"
                                                          title="Semester ini sedang aktif sebagai default">
                                                        <svg class="w-3.5 h-3.5 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Aktif</span>
                                                    </span>
                                                @endif

                                                <button type="button" 
                                                        onclick="openEditSemesterModal({{ $sem->id }}, '{{ $sem->nama_semester }}', '{{ $sem->tipe }}', {{ $sem->bulan_mulai }}, {{ $sem->bulan_selesai }}, {{ $sem->is_active ? 'true' : 'false' }})" 
                                                        class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-[var(--text-body)] bg-[var(--surface)] hover:bg-[var(--primary-50)] hover:text-[var(--text-heading)] border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer"
                                                        title="Ubah data semester">
                                                    <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    <span>Edit</span>
                                                </button>

                                                <form action="{{ route('backend.keuangan.master-semester.destroy', $sem->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semester {{ $sem->nama_semester }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="h-7 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-rose-700 bg-[var(--surface)] hover:bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] transition cursor-pointer"
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
                                        <td colspan="6" class="py-8 text-center text-xs text-[var(--text-muted)]">
                                            Belum ada semester terdaftar pada Tahun Akademik ini. Silakan klik tombol <b class="text-[var(--text-heading)]">+ Semester</b> di kanan atas kartu.
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
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[var(--bg-page)] flex items-center justify-center mx-auto mb-3 text-[var(--text-muted)] border border-[var(--border)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-bold text-sm text-[var(--text-heading)]">Belum ada tahun akademik terdaftar.</p>
                <p class="text-xs text-[var(--text-muted)] mt-1">Klik tombol Tambah Tahun Akademik di atas untuk membuat data baru.</p>
            </div>
        @endforelse
    </div>

</div>

{{-- MODAL TAMBAH TAHUN AKADEMIK --}}
<div id="modal-create-ta" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-[var(--shadow-modal)] w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Tambah Tahun Akademik</h3>
            <button type="button" onclick="closeModal('modal-create-ta')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('backend.keuangan.master-tahun-akademik.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                <input type="text" name="tahun" placeholder="Contoh: 2026/2027" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition">
                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Format: YYYY/YYYY (misal: 2026/2027). Dua semester default (Ganjil & Genap) akan otomatis dibuat.</p>
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="create_ta_is_active" value="1" class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-500)] focus:ring-[var(--primary-500)] cursor-pointer">
                <label for="create_ta_is_active" class="text-xs font-medium text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Tahun Akademik Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-create-ta')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer shadow-xs">
                    Simpan Tahun Akademik
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT TAHUN AKADEMIK --}}
<div id="modal-edit-ta" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-[var(--shadow-modal)] w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit Tahun Akademik</h3>
            <button type="button" onclick="closeModal('modal-edit-ta')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-ta" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Tahun Akademik <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_ta_tahun" name="tahun" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition">
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="edit_ta_is_active" value="1" class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-500)] focus:ring-[var(--primary-500)] cursor-pointer">
                <label for="edit_ta_is_active" class="text-xs font-medium text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Tahun Akademik Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-edit-ta')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer shadow-xs">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH SEMESTER --}}
<div id="modal-create-semester" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-[var(--shadow-modal)] w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <div>
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Tambah Semester</h3>
                <p id="create_sem_ta_label" class="text-xs text-[var(--text-muted)] mt-0.5">Tahun Akademik: -</p>
            </div>
            <button type="button" onclick="closeModal('modal-create-semester')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-create-semester" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_semester" placeholder="Misal: Ganjil (Jul-Des) atau Pendek" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Tipe Semester <span class="text-rose-500">*</span></label>
                <select name="tipe" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Bulan Mulai <span class="text-rose-500">*</span></label>
                    <select name="bulan_mulai" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
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
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Bulan Selesai <span class="text-rose-500">*</span></label>
                    <select name="bulan_selesai" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
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
                <input type="checkbox" name="is_active" id="create_sem_is_active" value="1" class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-500)] focus:ring-[var(--primary-500)] cursor-pointer">
                <label for="create_sem_is_active" class="text-xs font-medium text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Semester Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-create-semester')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer shadow-xs">
                    Simpan Semester
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT SEMESTER --}}
<div id="modal-edit-semester" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-[var(--shadow-modal)] w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)]">
            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit Semester</h3>
            <button type="button" onclick="closeModal('modal-edit-semester')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-semester" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Nama Semester <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_sem_nama" name="nama_semester" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Tipe Semester <span class="text-rose-500">*</span></label>
                <select id="edit_sem_tipe" name="tipe" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Bulan Mulai <span class="text-rose-500">*</span></label>
                    <select id="edit_sem_mulai" name="bulan_mulai" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
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
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Bulan Selesai <span class="text-rose-500">*</span></label>
                    <select id="edit_sem_selesai" name="bulan_selesai" required class="w-full h-10 px-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-500)] focus:ring-1 focus:ring-[var(--primary-500)] transition cursor-pointer">
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
                <input type="checkbox" name="is_active" id="edit_sem_is_active" value="1" class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-500)] focus:ring-[var(--primary-500)] cursor-pointer">
                <label for="edit_sem_is_active" class="text-xs font-medium text-[var(--text-body)] cursor-pointer">
                    Jadikan sebagai Semester Aktif Default
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)]">
                <button type="button" onclick="closeModal('modal-edit-semester')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer shadow-xs">
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
