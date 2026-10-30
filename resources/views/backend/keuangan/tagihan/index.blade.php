@extends('layouts.keuangan')

@section('title', 'Manajemen Tagihan')

@section('content')
<script>
function tagihanPageData() {
    return {
        tab: 'massal',
        masterModalOpen: false,
        masterEditOpen: false,
        masterEditForm: { id: '', action_url: '', nama_tagihan: '', nominal_default: '', tipe_nominal: 'manual' },

        // Data dari Controller
        activePeriods: @json($activePeriods),
        mhsSummaryList: @json($mhsSummaryList),

        // Massal Form State (5 Kolom Berurutan)
        selectedProdi: '',
        selectedAngkatan: '',
        selectedPeriodeId: '',
        selectedTagihan: '',
        keterangan: '',
        
        // Dependent state
        sppMonthList: [],
        massalIsSpp: true,
        massalNominal: '',
        jatuhTempo: '',

        // Personal Form State
        personalUserId: '',
        personalUserSpp: 0,
        personalJenis: @json($masterJenisTagihans->first()->nama_tagihan ?? 'SPP'),
        personalPeriode: '',
        personalNominal: '',
        personalIsSpp: true,

        init() {
            this.$nextTick(() => {
                // Jika ada periode aktif, pilih yang pertama secara default agar siap dipakai
                if (this.activePeriods && this.activePeriods.length > 0) {
                    this.selectedPeriodeId = String(this.activePeriods[0].id);
                    this.onPeriodeChange();
                }
                this.checkPersonalSpp();
            });
        },

        onPeriodeChange() {
            this.selectedTagihan = '';
            this.massalNominal = '';
            this.jatuhTempo = '';
            
            if (!this.selectedPeriodeId) {
                this.sppMonthList = [];
                return;
            }

            const period = this.activePeriods.find(p => String(p.id) === String(this.selectedPeriodeId));
            if (period && period.months) {
                this.sppMonthList = period.months.map(m => ({
                    label: 'SPP ' + (m.nama || '').toUpperCase(),
                    bulan: m.nama,
                    num: m.num,
                    year: m.year
                }));
                // Auto-pilih bulan pertama jika tersedia
                if (this.sppMonthList.length > 0) {
                    this.selectedTagihan = this.sppMonthList[0].label;
                    this.onTagihanChange();
                }
            } else {
                this.sppMonthList = [];
            }
        },

        onTagihanChange() {
            if (!this.selectedTagihan) {
                this.massalIsSpp = true;
                this.jatuhTempo = '';
                return;
            }

            const isSpp = this.selectedTagihan.toUpperCase().indexOf('SPP') !== -1;
            this.massalIsSpp = isSpp;

            if (isSpp) {
                this.massalNominal = '';
                // Cari bulan dari sppMonthList untuk auto set tanggal 10
                const foundMonth = this.sppMonthList.find(m => m.label.toUpperCase() === this.selectedTagihan.toUpperCase());
                if (foundMonth) {
                    const mm = String(foundMonth.num).padStart(2, '0');
                    this.jatuhTempo = String(foundMonth.year) + '-' + mm + '-10';
                }
            } else {
                // Non-SPP, cari default nominal jika ada di master data
                const selectEl = document.getElementById('select-massal-tagihan');
                if (selectEl) {
                    const opt = selectEl.options[selectEl.selectedIndex];
                    const defNominal = opt ? opt.getAttribute('data-nominal') : '';
                    this.massalNominal = defNominal || '';
                }
            }
        },

        get filteredMhsCount() {
            if (!this.mhsSummaryList) return 0;
            return this.mhsSummaryList.filter(m => {
                const matchProdi = !this.selectedProdi || m.prodi === this.selectedProdi;
                const matchAngkatan = !this.selectedAngkatan || String(m.angkatan) === String(this.selectedAngkatan);
                return matchProdi && matchAngkatan;
            }).length;
        },

        get selectedPeriodeLabel() {
            if (!this.selectedPeriodeId) return '';
            const period = this.activePeriods.find(p => String(p.id) === String(this.selectedPeriodeId));
            return period ? period.label : '';
        },

        onPersonalUserChange() {
            const select = document.getElementById('select-personal-user');
            if (!select) return;
            const opt = select.options[select.selectedIndex];
            this.personalUserSpp = opt ? parseFloat(opt.getAttribute('data-spp') || 0) : 0;
            this.updatePersonalNominal();
        },

        onPersonalJenisChange() {
            const select = document.getElementById('select-personal-jenis');
            if (!select) return;
            const opt = select.options[select.selectedIndex];
            const tipe = opt ? opt.getAttribute('data-tipe') : '';
            const defNominal = opt ? opt.getAttribute('data-nominal') : '';
            this.personalIsSpp = (tipe === 'otomatis_spp' || this.personalJenis.toUpperCase() === 'SPP');
            this.updatePersonalNominal(defNominal);
        },

        checkPersonalSpp() {
            this.onPersonalJenisChange();
        },

        updatePersonalNominal(defNominal = null) {
            if (this.personalIsSpp) {
                this.personalNominal = this.personalUserSpp > 0 ? this.personalUserSpp : 300000;
            } else {
                if (defNominal !== null) {
                    this.personalNominal = defNominal || '';
                }
            }
        },

        openMasterEdit(target, nama, nominal, tipe) {
            var id;
            if (target instanceof HTMLElement || (typeof target === 'object' && target !== null && target.dataset)) {
                var ds = target.dataset;
                id = ds.id;
                nama = ds.nama;
                nominal = ds.nominal;
                tipe = ds.tipe;
            } else {
                id = target;
            }
            this.masterEditForm.id = id;
            this.masterEditForm.action_url = '{{ url('/backend/keuangan/tagihan/master-jenis') }}/' + id;
            this.masterEditForm.nama_tagihan = nama || '';
            this.masterEditForm.nominal_default = nominal || '';
            this.masterEditForm.tipe_nominal = tipe || 'manual';
            this.masterEditOpen = true;
        }
    };
}
</script>

<div class="space-y-6" x-data="tagihanPageData()">

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="bg-[var(--primary-50)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-[var(--primary-500)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-xs font-semibold text-[var(--primary-700)]">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-[var(--danger)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-xs font-semibold text-[var(--danger)]">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-[var(--secondary-50)] border border-[var(--secondary-200)] rounded-[var(--radius-xs)] p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-[var(--secondary-700)] dark:text-[var(--secondary-400)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-xs font-semibold text-[var(--secondary-700)] dark:text-[var(--secondary-400)]">{{ session('warning') }}</p>
    </div>
    @endif

    {{-- Header Area: Title & Action Buttons --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Manajemen tagihan</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Buat dan kelola tagihan mahasiswa secara massal atau personal dengan master kategori.</p>
        </div>
        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <button type="button" @click="masterModalOpen = true" class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span>Kelola jenis tagihan</span>
            </button>
            <a href="{{ route('backend.keuangan.tagihan.spreadsheet') }}" class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--primary-500)] text-white text-xs font-semibold rounded-[var(--radius-xs)] hover:bg-[var(--primary-600)] transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Google Sheets</span>
            </a>
        </div>
    </div>

    {{-- Segmented Tab Switcher --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] p-1 flex gap-1">
        <button @click="tab = 'massal'"
                :class="tab === 'massal' ? 'bg-[var(--primary-500)] text-white font-semibold' : 'text-[var(--text-body)] hover:bg-[var(--primary-50)] font-medium'"
                class="flex-1 h-9 px-4 rounded-[var(--radius-xs)] text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Tagihan massal</span>
        </button>
        <button @click="tab = 'personal'"
                :class="tab === 'personal' ? 'bg-[var(--primary-500)] text-white font-semibold' : 'text-[var(--text-body)] hover:bg-[var(--primary-50)] font-medium'"
                class="flex-1 h-9 px-4 rounded-[var(--radius-xs)] text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Tagihan personal</span>
        </button>
        <button @click="tab = 'kelola_massal'"
                :class="tab === 'kelola_massal' ? 'bg-[var(--primary-500)] text-white font-semibold' : 'text-[var(--text-body)] hover:bg-[var(--primary-50)] font-medium'"
                class="flex-1 h-9 px-4 rounded-[var(--radius-xs)] text-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            <span>Kelola massal</span>
        </button>
    </div>

    {{-- Form Tagihan Massal (5 Kolom Berurutan) --}}
    <div x-show="tab === 'massal'" style="display: none;">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden shadow-xs">
            <div class="px-5 py-3.5 border-b border-[var(--border)] bg-[var(--bg-page)] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <p class="text-xs font-bold font-display text-[var(--text-heading)]">Buat Tagihan Massal (Alur 5 Kolom Berurutan)</p>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Pilih sasaran prodi, angkatan, periode aktif, dan bulan SPP secara berurutan.</p>
                </div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-700)] text-[11px] font-semibold border border-[var(--primary-200)] shrink-0">
                    <svg class="w-3.5 h-3.5 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Anti-double billing aktif</span>
                </div>
            </div>

            <form action="{{ route('backend.keuangan.tagihan.storeMass') }}" method="POST" class="p-5 space-y-4">
                @csrf
                
                {{-- 5-Kolom Berurutan (Step-by-step Dependency) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    {{-- Kolom 1: Program Studi --}}
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] text-[11px] font-bold">1</span>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] truncate">Program Studi</label>
                        </div>
                        <select name="program_studi" 
                                x-model="selectedProdi" 
                                class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="">Semua program studi</option>
                            @foreach($programStudis as $prodi)
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10.5px] text-[var(--text-muted)] mt-1 truncate">Filter target program studi</p>
                    </div>
                    
                    {{-- Kolom 2: Angkatan --}}
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] text-[11px] font-bold">2</span>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] truncate">Angkatan</label>
                        </div>
                        <select name="angkatan" 
                                x-model="selectedAngkatan" 
                                class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="">Semua angkatan</option>
                            @foreach($angkatans as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10.5px] text-[var(--text-muted)] mt-1 truncate">Filter tahun angkatan</p>
                    </div>

                    {{-- Kolom 3: Periode (Tahun Akademik & Semester Aktif) --}}
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[var(--primary-600)] text-white text-[11px] font-bold">3</span>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] truncate">Periode Aktif <span class="text-rose-500">*</span></label>
                        </div>
                        <select name="periode_id" 
                                x-model="selectedPeriodeId" 
                                @change="onPeriodeChange()" 
                                required 
                                class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="">-- Pilih Periode Aktif --</option>
                            @forelse($activePeriods as $p)
                                <option value="{{ $p['id'] }}">
                                    {{ $p['label'] }} ({{ $p['nama_semester'] }})
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada periode aktif</option>
                            @endforelse
                        </select>
                        <p class="text-[10.5px] text-[var(--text-muted)] mt-1 truncate">Wajib sebelum memilih Kolom 4</p>
                    </div>

                    {{-- Kolom 4: Jenis Tagihan (Otomatis Khusus SPP Berdasarkan Bulan Periode) --}}
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[11px] font-bold" 
                                  :class="selectedPeriodeId ? 'bg-[var(--primary-600)] text-white' : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-400'">4</span>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] truncate">Jenis Tagihan (SPP) <span class="text-rose-500">*</span></label>
                        </div>
                        <div class="relative">
                            <select id="select-massal-tagihan"
                                    name="nama_tagihan" 
                                    x-model="selectedTagihan" 
                                    @change="onTagihanChange()" 
                                    :disabled="!selectedPeriodeId"
                                    required 
                                    :class="!selectedPeriodeId ? 'opacity-60 cursor-not-allowed bg-slate-100 dark:bg-slate-800' : 'bg-[var(--bg-page)] border-[var(--border)]'"
                                    class="w-full h-10 px-3 border rounded-[var(--radius-xs)] text-xs font-semibold text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="" x-show="!selectedPeriodeId">-- Terkunci (Pilih Kolom 3 Dulu) --</option>
                                <option value="" x-show="selectedPeriodeId">-- Pilih Bulan SPP / Tagihan --</option>
                                <optgroup label="Bulan SPP Periode Terpilih">
                                    <template x-for="item in sppMonthList" :key="item.label">
                                        <option :value="item.label" x-text="item.label"></option>
                                    </template>
                                </optgroup>
                                @if($masterJenisTagihans->where('tipe_nominal', '!=', 'otomatis_spp')->isNotEmpty())
                                <optgroup label="Tagihan Non-SPP Lainnya">
                                    @foreach($masterJenisTagihans->where('tipe_nominal', '!=', 'otomatis_spp') as $nonSpp)
                                        <option value="{{ $nonSpp->nama_tagihan }}" data-tipe="{{ $nonSpp->tipe_nominal }}" data-nominal="{{ $nonSpp->nominal_default ?? '' }}">
                                            {{ $nonSpp->nama_tagihan }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                @endif
                            </select>
                        </div>
                        <p class="text-[10.5px] mt-1 truncate" :class="!selectedPeriodeId ? 'text-amber-600 dark:text-amber-400 font-medium' : 'text-[var(--primary-600)] dark:text-[var(--primary-400)]'">
                            <span x-show="!selectedPeriodeId">⚠️ Kunci: Pilih Kolom 3 dahulu</span>
                            <span x-show="selectedPeriodeId">✓ Bulan otomatis dari periode aktif</span>
                        </p>
                    </div>

                    {{-- Kolom 5: Keterangan Tambahan / Catatan (Opsional) --}}
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[11px] font-bold">5</span>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] truncate">Keterangan / Catatan</label>
                        </div>
                        <input type="text" 
                               name="keterangan" 
                               x-model="keterangan" 
                               placeholder="Opsional: Catatan tagihan..." 
                               class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)]">
                        <p class="text-[10.5px] text-[var(--text-muted)] mt-1 truncate">Catatan bebas pada tagihan</p>
                    </div>
                </div>

                {{-- Status & Summary Bar --}}
                <div class="p-3.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                        <div>
                            <span class="text-[var(--text-muted)]">Sasaran Target:</span>
                            <span class="font-bold text-[var(--text-heading)] ml-1" x-text="filteredMhsCount + ' mahasiswa aktif'"></span>
                        </div>
                        <div class="h-3 w-px bg-[var(--border)] hidden sm:block"></div>
                        <div>
                            <span class="text-[var(--text-muted)]">Tagihan Terpilih:</span>
                            <span class="font-bold text-[var(--primary-700)] dark:text-[var(--primary-300)] ml-1" x-text="selectedTagihan || 'Belum dipilih'"></span>
                        </div>
                        <div class="h-3 w-px bg-[var(--border)] hidden sm:block"></div>
                        <div>
                            <span class="text-[var(--text-muted)]">Periode Aktif:</span>
                            <span class="font-medium text-[var(--text-body)] ml-1" x-text="selectedPeriodeLabel || '-'"></span>
                        </div>
                    </div>
                    <div>
                        <div x-show="massalIsSpp" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[var(--primary-50)] text-[var(--primary-700)] font-semibold border border-[var(--primary-200)] text-[11px]">
                            <svg class="w-3.5 h-3.5 text-[var(--primary-600)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Tarif SPP otomatis per mahasiswa (Anti-duplikasi aktif)</span>
                        </div>
                        <div x-show="!massalIsSpp" style="display: none;" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200 text-[11px]">
                            <span>Tagihan non-SPP (Nominal manual)</span>
                        </div>
                    </div>
                </div>

                {{-- Additional Config Row (Nominal jika non-SPP, Jenis Pembayaran, Jatuh Tempo) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    {{-- Input Nominal (Hanya jika Non-SPP) --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                            Nominal (Rp) <span x-show="!massalIsSpp" class="text-[var(--danger)]">*</span>
                        </label>
                        <div x-show="massalIsSpp" class="h-10 px-3 bg-[var(--primary-50)]/60 border border-[var(--primary-200)] rounded-[var(--radius-xs)] flex items-center justify-between">
                            <span class="text-xs text-[var(--primary-700)] font-semibold">Tarif SPP masing-masing</span>
                            <span class="text-[11px] text-[var(--primary-600)]">Tarik dari data master</span>
                        </div>
                        <div x-show="!massalIsSpp" style="display: none;">
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-xs text-[var(--text-muted)]">Rp</span>
                                <input type="number" name="nominal_total" x-model="massalNominal" :required="!massalIsSpp" min="0" placeholder="150000" class="w-full h-10 pl-9 pr-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                            </div>
                        </div>
                    </div>

                    {{-- Jenis Pembayaran --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Jenis pembayaran <span class="text-[var(--danger)]">*</span></label>
                        <select name="jenis_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="lunas">Wajib lunas (sekali bayar)</option>
                            <option value="cicilan">Bisa dicicil</option>
                        </select>
                    </div>

                    {{-- Jatuh Tempo --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Jatuh tempo</label>
                        <input type="date" name="jatuh_tempo" x-model="jatuhTempo" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                </div>

                {{-- Submit Area --}}
                <div class="pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-[var(--border)]">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="send_email" value="1" checked class="w-4 h-4 rounded text-[var(--primary-500)] focus:ring-[var(--primary-500)] border-[var(--border)]">
                        <span class="text-xs font-medium text-[var(--text-body)]">Kirim notifikasi pengingat ke email mahasiswa</span>
                    </label>
                    <button type="submit" 
                            :disabled="!selectedPeriodeId || !selectedTagihan || filteredMhsCount === 0"
                            :class="(!selectedPeriodeId || !selectedTagihan || filteredMhsCount === 0) ? 'opacity-60 cursor-not-allowed bg-slate-400' : 'bg-[var(--primary-500)] hover:bg-[var(--primary-600)] cursor-pointer'"
                            class="h-10 px-5 text-white text-xs font-semibold rounded-[var(--radius-xs)] transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Terbitkan Tagihan Massal</span>
                        <span x-show="filteredMhsCount > 0" class="px-1.5 py-0.5 rounded-full bg-white/20 text-[10px]" x-text="filteredMhsCount"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Tagihan Personal --}}
    <div x-show="tab === 'personal'" style="display: none;">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[var(--border)] bg-[var(--bg-page)]">
                <p class="text-xs font-bold font-display text-[var(--text-heading)]">Buat tagihan personal</p>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Kirim tagihan khusus ke satu mahasiswa tertentu.</p>
            </div>

            <form action="{{ route('backend.keuangan.tagihan.storePersonal') }}" method="POST" class="p-5 space-y-4">
                @csrf
                
                {{-- Pilih Mahasiswa --}}
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Pilih mahasiswa <span class="text-[var(--danger)]">*</span></label>
                    <select id="select-personal-user" name="user_id" x-model="personalUserId" @change="onPersonalUserChange()" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <option value="">-- Pilih mahasiswa --</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}" data-spp="{{ (float)($mhs->nominal_spp ?? 300000) }}">
                                {{ $mhs->nim ?? '-' }} — {{ $mhs->name }} (Tarif SPP: Rp {{ number_format($mhs->nominal_spp ?? 300000, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Tagihan & Periode --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-[var(--text-heading)]">Jenis tagihan <span class="text-[var(--danger)]">*</span></label>
                            <button type="button" @click="masterModalOpen = true" class="text-[11px] font-semibold text-[var(--primary-600)] hover:underline cursor-pointer">
                                Kelola jenis
                            </button>
                        </div>
                        <select id="select-personal-jenis" x-model="personalJenis" @change="onPersonalJenisChange()" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-700)]">
                            @foreach($masterJenisTagihans as $mjt)
                                <option value="{{ $mjt->nama_tagihan }}" data-tipe="{{ $mjt->tipe_nominal }}" data-nominal="{{ $mjt->nominal_default ?? '' }}">
                                    {{ $mjt->nama_tagihan }} {{ $mjt->tipe_nominal === 'otomatis_spp' ? '(Otomatis tarif SPP mahasiswa)' : ($mjt->nominal_default ? '(Default: Rp ' . number_format($mjt->nominal_default, 0, ',', '.') . ')' : '') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Periode / keterangan tambahan</label>
                        <input type="text" x-model="personalPeriode" placeholder="Contoh: Oktober 2026 atau Denda Keterlambatan" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                </div>

                {{-- Preview Nama Tagihan --}}
                <div class="px-3 py-2 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-[var(--text-muted)]">Nama tagihan:</span>
                    <span class="font-bold text-[var(--primary-700)] dark:text-[var(--primary-300)]" x-text="personalPeriode.trim() ? (personalJenis + ' - ' + personalPeriode.trim()) : personalJenis"></span>
                </div>
                <input type="hidden" name="nama_tagihan" :value="personalPeriode.trim() ? (personalJenis + ' - ' + personalPeriode.trim()) : personalJenis">

                {{-- Nominal + Jenis + Jatuh Tempo --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Nominal (Rp) <span class="text-[var(--danger)]">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs text-[var(--text-muted)]">Rp</span>
                            <input type="number" name="nominal_total" x-model="personalNominal" :readonly="personalIsSpp" required min="0" placeholder="50000" :class="personalIsSpp ? 'bg-[var(--bg-page)] opacity-80 cursor-not-allowed' : 'bg-[var(--surface)]'" class="w-full h-10 pl-9 pr-3 border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Jenis pembayaran <span class="text-[var(--danger)]">*</span></label>
                        <select name="jenis_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="lunas">Wajib lunas (sekali bayar)</option>
                            <option value="cicilan">Bisa dicicil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Jatuh tempo</label>
                        <input type="date" name="jatuh_tempo" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">Catatan tambahan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan opsional untuk mahasiswa..." class="w-full p-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] resize-none"></textarea>
                </div>

                {{-- Submit Area --}}
                <div class="pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-[var(--border)]">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="send_email" value="1" checked class="w-4 h-4 rounded text-[var(--primary-500)] focus:ring-[var(--primary-500)] border-[var(--border)]">
                        <span class="text-xs font-medium text-[var(--text-body)]">Kirim notifikasi pengingat ke email mahasiswa</span>
                    </label>
                    <button type="submit" class="h-10 px-5 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                        Terbitkan tagihan personal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kelola Massal --}}
    <div x-show="tab === 'kelola_massal'" style="display: none;" x-data="{ 
        editModalOpen: false, 
        deleteModalOpen: false, 
        editForm: { old_nama_tagihan: '', nama_tagihan: '', nominal_total: '', jenis_tagihan: 'lunas', jatuh_tempo: '' },
        deleteForm: { nama_tagihan: '' },
        openMassalEdit(el) {
            this.editForm.old_nama_tagihan = el.dataset.nama;
            this.editForm.nama_tagihan = el.dataset.nama;
            this.editForm.nominal_total = el.dataset.nominal;
            this.editForm.jenis_tagihan = el.dataset.jenis;
            this.editForm.jatuh_tempo = el.dataset.tempo || '';
            this.editModalOpen = true;
        },
        openMassalDelete(el) {
            this.deleteForm.nama_tagihan = el.dataset.nama;
            this.deleteModalOpen = true;
        }
    }">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[var(--border)] bg-[var(--bg-page)]">
                <p class="text-xs font-bold font-display text-[var(--text-heading)]">Daftar grup tagihan massal</p>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Kelola tagihan yang dibuat secara massal berdasarkan nama tagihan.</p>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left text-xs table-mobile-cards">
                    <thead>
                        <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                            <th scope="col" class="py-3.5 pl-5 pr-3">Nama tagihan</th>
                            <th scope="col" class="py-3.5 px-4 text-center">Jumlah mahasiswa</th>
                            <th scope="col" class="py-3.5 px-4 text-right">Total nominal</th>
                            <th scope="col" class="py-3.5 px-4">Jatuh tempo</th>
                            <th scope="col" class="py-3.5 px-4 text-center">Jenis</th>
                            <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                        @forelse($massalGroups as $group)
                        <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                            <td class="py-3.5 pl-5 pr-3 font-semibold text-[var(--text-heading)]" data-label="Tagihan">{{ $group->nama_tagihan }}</td>
                            <td class="py-3.5 px-4 text-center tabular-nums" data-label="Mahasiswa">{{ $group->total_mhs }} orang</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-[var(--text-heading)] tabular-nums" data-label="Total">Rp {{ number_format($group->total_nominal, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 tabular-nums" data-label="Jatuh tempo">
                                {{ $group->jatuh_tempo ? \Carbon\Carbon::parse($group->jatuh_tempo)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center" data-label="Jenis">
                                <span class="text-xs font-medium">{{ ucfirst($group->jenis_tagihan) }}</span>
                            </td>
                            <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button"
                                            @click="openMassalEdit($el)"
                                            data-nama="{{ $group->nama_tagihan }}"
                                            data-nominal="{{ $group->total_nominal / $group->total_mhs }}"
                                            data-jenis="{{ $group->jenis_tagihan }}"
                                            data-tempo="{{ $group->jatuh_tempo ? \Carbon\Carbon::parse($group->jatuh_tempo)->format('Y-m-d') : '' }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer" 
                                            title="Edit massal" aria-label="Edit massal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button"
                                            @click="openMassalDelete($el)"
                                            data-nama="{{ $group->nama_tagihan }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--danger)] hover:bg-rose-50 border border-[var(--border)] transition cursor-pointer" 
                                            title="Hapus massal" aria-label="Hapus massal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-[var(--text-muted)]">Belum ada grup tagihan massal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Edit Massal --}}
        <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display: none;">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-md shadow-[var(--shadow-modal)] overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit tagihan massal</h3>
                    <button type="button" @click="editModalOpen = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form action="{{ route('backend.keuangan.tagihan.updateMassal') }}" method="POST" class="p-5 space-y-3.5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="old_nama_tagihan" x-model="editForm.old_nama_tagihan">
                    
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nama tagihan *</label>
                        <input type="text" name="nama_tagihan" x-model="editForm.nama_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nominal per mhs *</label>
                            <input type="number" name="nominal_total" x-model="editForm.nominal_total" required min="0" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Jenis *</label>
                            <select name="jenis_tagihan" x-model="editForm.jenis_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="lunas">Lunas</option>
                                <option value="cicilan">Cicilan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Jatuh tempo</label>
                        <input type="date" name="jatuh_tempo" x-model="editForm.jatuh_tempo" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-[var(--border)]">
                        <button type="button" @click="editModalOpen = false" class="h-9 px-3.5 text-xs font-semibold text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)]">Batal</button>
                        <button type="submit" class="h-9 px-4 text-xs font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] rounded-[var(--radius-xs)]">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus Massal --}}
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display: none;">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-sm p-5 text-center shadow-[var(--shadow-modal)]">
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)] mb-2">Hapus tagihan massal</h3>
                <p class="text-xs text-[var(--text-muted)] mb-5">Apakah Anda yakin ingin menghapus seluruh tagihan <b><span x-text="deleteForm.nama_tagihan"></span></b>?</p>
                
                <form action="{{ route('backend.keuangan.tagihan.destroyMassal') }}" method="POST" class="flex justify-center gap-2">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="nama_tagihan" x-model="deleteForm.nama_tagihan">
                    <button type="button" @click="deleteModalOpen = false" class="h-9 px-4 text-xs font-semibold text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)]">Batal</button>
                    <button type="submit" class="h-9 px-4 text-xs font-semibold text-white bg-[var(--danger)] rounded-[var(--radius-xs)]">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Histori Tagihan Terakhir --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden" x-data="{ 
        searchQuery: '',
        selectedIds: [],
        selectAll: false,
        allIds: {{ json_encode($recentTagihans->pluck('id')) }},
        
        toggleSelectAll() {
            if (this.selectAll) {
                let ids = [];
                document.querySelectorAll('.tagihan-row').forEach(tr => {
                    if (tr.style.display !== 'none' && tr.offsetParent !== null) {
                        const id = parseInt(tr.dataset.tagihanId);
                        if (id) ids.push(id);
                    }
                });
                this.selectedIds = ids.length > 0 ? ids : [...this.allIds];
            } else {
                this.selectedIds = [];
            }
        },

        updateSelectAllState() {
            let visibleRows = Array.from(document.querySelectorAll('.tagihan-row')).filter(tr => tr.style.display !== 'none' && tr.offsetParent !== null);
            if (visibleRows.length === 0) {
                this.selectAll = false;
                return;
            }
            let visibleIds = visibleRows.map(tr => parseInt(tr.dataset.tagihanId));
            this.selectAll = visibleIds.every(id => this.selectedIds.includes(id));
        },

        confirmBulkDelete() {
            if (this.selectedIds.length === 0) return;
            const count = this.selectedIds.length;
            const msg = 'Apakah Anda yakin ingin menghapus ' + count + ' data tagihan yang dipilih? Tindakan ini tidak dapat dibatalkan.';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Hapus Data',
                    text: msg,
                    icon: 'warning',
                    iconColor: '#D93025',
                    showCancelButton: true,
                    confirmButtonColor: '#D93025',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus Sekarang',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900',
                        title: 'text-base font-bold text-slate-900 dark:text-slate-100 font-display',
                        htmlContainer: 'text-xs text-slate-600 dark:text-slate-400',
                        confirmButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-sm cursor-pointer',
                        cancelButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-2xs cursor-pointer'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-bulk-delete').submit();
                    }
                });
            } else {
                if (confirm(msg)) {
                    document.getElementById('form-bulk-delete').submit();
                }
            }
        },

        editPersonalOpen: false,
        editPersonalForm: { id: '', action_url: '', nama_tagihan: '', nominal_total: '', jenis_tagihan: 'lunas', jatuh_tempo: '', keterangan: '' },
        openPersonalEdit(el) {
            this.editPersonalForm.id = el.dataset.id;
            this.editPersonalForm.action_url = el.dataset.action;
            this.editPersonalForm.nama_tagihan = el.dataset.nama;
            this.editPersonalForm.nominal_total = el.dataset.nominal;
            this.editPersonalForm.jenis_tagihan = el.dataset.jenis;
            this.editPersonalForm.jatuh_tempo = el.dataset.tempo || '';
            this.editPersonalForm.keterangan = el.dataset.keterangan || '';
            this.editPersonalOpen = true;
        }
    }">
        <div class="px-5 py-3.5 border-b border-[var(--border)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2.5">
                    <p class="text-xs font-bold font-display text-[var(--text-heading)]">Riwayat tagihan terakhir</p>
                    <span x-show="selectedIds.length > 0" x-cloak class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800">
                        <span x-text="selectedIds.length"></span> dipilih
                    </span>
                </div>
                <p class="text-[11px] text-[var(--text-muted)]">Daftar tagihan yang baru diterbitkan ke mahasiswa.</p>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end flex-wrap sm:flex-nowrap">
                {{-- Tombol Hapus Terpilih (Bulk Delete) --}}
                <button type="button" 
                        x-show="selectedIds.length > 0" 
                        x-cloak
                        @click="confirmBulkDelete()"
                        class="h-9 px-3.5 inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-[var(--radius-xs)] shadow-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Hapus Terpilih (<span x-text="selectedIds.length"></span>)</span>
                </button>

                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Cari mahasiswa atau tagihan..." class="w-full h-9 pl-8 pr-3 text-xs bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] focus:outline-none focus:border-[var(--primary-700)]">
                    <svg class="w-3.5 h-3.5 text-[var(--text-muted)] absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Form Hapus Massal Terpilih --}}
        <form id="form-bulk-delete" action="{{ route('backend.keuangan.tagihan.destroyBulk') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
        </form>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-4 pr-2 w-10 text-center">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleSelectAll()" 
                                   class="w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer"
                                   title="Pilih Semua (Select All)">
                        </th>
                        <th scope="col" class="py-3.5 px-3 w-[110px]">Waktu</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[170px]">Mahasiswa</th>
                        <th scope="col" class="py-3.5 px-3 min-w-[130px]">Program studi</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[80px]">Angkatan</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[150px]">Nama tagihan</th>
                        <th scope="col" class="py-3.5 px-4 text-right w-[110px]">Total</th>
                        <th scope="col" class="py-3.5 px-4 text-right w-[110px]">Sisa</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[110px]">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($recentTagihans as $tagihan)
                    <tr class="tagihan-row hover:bg-[var(--primary-50)]/40 transition-colors" 
                        data-tagihan-id="{{ $tagihan->id }}"
                        :class="selectedIds.includes({{ $tagihan->id }}) ? 'bg-emerald-50/50 dark:bg-emerald-950/20' : ''"
                        x-show="searchQuery === '' || '{{ strtolower(str_replace("'", "\'", $tagihan->user->name ?? '')) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(str_replace("'", "\'", $tagihan->nama_tagihan)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(str_replace("'", "\'", $tagihan->user->education['program_studi'] ?? '')) }}'.includes(searchQuery.toLowerCase())">
                        <td class="py-3.5 pl-4 pr-2 text-center" data-label="Pilih">
                            <input type="checkbox" 
                                   value="{{ $tagihan->id }}"
                                   :value="{{ $tagihan->id }}"
                                   name="ids[]"
                                   x-model="selectedIds"
                                   @change="updateSelectAllState()"
                                   class="tagihan-checkbox w-4 h-4 rounded-[var(--radius-xs)] border-[var(--border)] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer">
                        </td>
                        <td class="py-3.5 px-3 tabular-nums" data-label="Waktu">
                            <span class="block font-semibold text-[var(--text-heading)]">{{ \Carbon\Carbon::parse($tagihan->created_at)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-[var(--text-muted)]">{{ \Carbon\Carbon::parse($tagihan->created_at)->format('H:i') }}</span>
                        </td>
                        <td class="py-3.5 px-4" data-label="Mahasiswa">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($tagihan->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-[var(--text-heading)] truncate max-w-[160px]">{{ $tagihan->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-3 text-[var(--text-muted)] truncate max-w-[140px]" data-label="Program studi">
                            {{ $tagihan->user->education['program_studi'] ?? '-' }}
                        </td>
                        <td class="py-3.5 px-3 text-center tabular-nums" data-label="Angkatan">
                            {{ $tagihan->user->angkatan ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-[var(--text-heading)] font-medium truncate max-w-[160px]" data-label="Tagihan">{{ $tagihan->nama_tagihan }}</td>
                        <td class="py-3.5 px-4 text-right font-semibold text-[var(--text-heading)] tabular-nums" data-label="Total">Rp {{ number_format($tagihan->nominal_total, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-[var(--danger)] tabular-nums" data-label="Sisa">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-3 text-center" data-label="Status">
                            @if($tagihan->status === 'lunas')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary-500)] mr-1.5"></span>
                                    Belum lunas
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                            <div class="flex items-center justify-center gap-1">
                                @if(!empty($tagihan->user->email))
                                <form action="{{ route('backend.keuangan.tagihan.resendReminder', $tagihan->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" 
                                            onclick="confirmResendReminder(this, '{{ addslashes($tagihan->user->name ?? 'Mahasiswa') }}', '{{ $tagihan->user->email }}')" 
                                            class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer" 
                                            title="Kirim reminder" 
                                            aria-label="Kirim reminder">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </button>
                                </form>
                                @endif

                                <button type="button"
                                        @click="openPersonalEdit($el)"
                                        data-id="{{ $tagihan->id }}"
                                        data-action="{{ route('backend.keuangan.tagihan.updatePersonal', $tagihan->id) }}"
                                        data-nama="{{ $tagihan->nama_tagihan }}"
                                        data-nominal="{{ $tagihan->nominal_total }}"
                                        data-jenis="{{ $tagihan->jenis_tagihan }}"
                                        data-tempo="{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('Y-m-d') : '' }}"
                                        data-keterangan="{{ $tagihan->keterangan ?? '' }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-body)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer" 
                                        title="Edit tagihan" aria-label="Edit tagihan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <form action="{{ route('backend.keuangan.tagihan.destroy', $tagihan->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDeleteTagihan(this, '{{ addslashes($tagihan->nama_tagihan) }}', '{{ addslashes($tagihan->user->name ?? 'Mahasiswa') }}')" 
                                            class="w-8 h-8 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--danger)] hover:bg-rose-50 border border-[var(--border)] transition cursor-pointer" 
                                            title="Hapus tagihan" 
                                            aria-label="Hapus tagihan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-xs text-[var(--text-muted)]">Belum ada tagihan yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Edit Personal --}}
        <div x-show="editPersonalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display: none;">
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-md shadow-[var(--shadow-modal)] overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit tagihan personal</h3>
                    <button type="button" @click="editPersonalOpen = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form :action="editPersonalForm.action_url" method="POST" class="p-5 space-y-3.5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nama tagihan *</label>
                        <input type="text" name="nama_tagihan" x-model="editPersonalForm.nama_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal_total" x-model="editPersonalForm.nominal_total" required min="0" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Jenis *</label>
                            <select name="jenis_tagihan" x-model="editPersonalForm.jenis_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="lunas">Lunas</option>
                                <option value="cicilan">Cicilan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Jatuh tempo</label>
                        <input type="date" name="jatuh_tempo" x-model="editPersonalForm.jatuh_tempo" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Keterangan</label>
                        <textarea name="keterangan" x-model="editPersonalForm.keterangan" rows="2" class="w-full p-2.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] resize-none"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-[var(--border)]">
                        <button type="button" @click="editPersonalOpen = false" class="h-9 px-3.5 text-xs font-semibold text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)]">Batal</button>
                        <button type="submit" class="h-9 px-4 text-xs font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] rounded-[var(--radius-xs)]">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3.5 border-t border-[var(--border)] flex items-center justify-between">
            <p class="text-xs text-[var(--text-muted)] tabular-nums">Menampilkan {{ count($recentTagihans) }} tagihan terakhir</p>
        </div>
    </div>

    {{-- MODAL MASTER: KELOLA JENIS TAGIHAN --}}
    <div x-show="masterModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display: none;">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-xl shadow-[var(--shadow-modal)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Kelola master jenis tagihan</h3>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Kategori dan tipe tagihan yang muncul pada formulir pembuatan tagihan.</p>
                </div>
                <button type="button" @click="masterModalOpen = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 space-y-4">
                {{-- Form Tambah Jenis Baru --}}
                <div class="p-3.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)]" x-data="{ addTipe: 'manual' }">
                    <p class="text-xs font-bold text-[var(--text-heading)] mb-2.5">Tambah jenis tagihan baru</p>
                    <form action="{{ route('backend.keuangan.tagihan.jenis.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-muted)] mb-1">Nama tagihan *</label>
                                <input type="text" name="nama_tagihan" required placeholder="Contoh: Praktikum" class="w-full h-9 px-2.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-muted)] mb-1">Tipe nominal *</label>
                                <select name="tipe_nominal" x-model="addTipe" required class="w-full h-9 px-2 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                    <option value="manual">Manual (input bebas)</option>
                                    <option value="otomatis_spp">Otomatis SPP mahasiswa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-muted)] mb-1">Nominal default (Rp)</label>
                                <input type="number" name="nominal_default" :disabled="addTipe === 'otomatis_spp'" placeholder="150000" class="w-full h-9 px-2.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)] disabled:opacity-50">
                            </div>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit" class="h-8 px-3.5 bg-[var(--primary-500)] text-white text-xs font-semibold rounded-[var(--radius-xs)] hover:bg-[var(--primary-600)] transition cursor-pointer">
                                Simpan jenis tagihan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Master Jenis Tagihan --}}
                <div class="border border-[var(--border)] rounded-[var(--radius-xs)] overflow-hidden max-h-56 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 border-b border-[var(--border)] font-semibold text-[var(--text-body)] sticky top-0 z-10">
                            <tr>
                                <th class="py-2.5 px-3">Nama tagihan</th>
                                <th class="py-2.5 px-3">Tipe nominal</th>
                                <th class="py-2.5 px-3 text-right">Nominal default</th>
                                <th class="py-2.5 px-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                            @forelse($masterJenisTagihans as $jenis)
                            <tr class="hover:bg-[var(--primary-50)]/40 transition">
                                <td class="py-2.5 px-3 font-semibold text-[var(--text-heading)]">
                                    {{ $jenis->nama_tagihan }}
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[11px]">{{ $jenis->tipe_nominal === 'otomatis_spp' ? 'Otomatis SPP' : 'Manual input' }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-right tabular-nums">
                                    {{ $jenis->nominal_default ? 'Rp ' . number_format($jenis->nominal_default, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" 
                                                @click="openMasterEdit($el)"
                                                data-id="{{ $jenis->id }}"
                                                data-nama="{{ $jenis->nama_tagihan }}"
                                                data-nominal="{{ $jenis->nominal_default }}"
                                                data-tipe="{{ $jenis->tipe_nominal }}"
                                                class="p-1 text-[var(--text-muted)] hover:text-[var(--primary-600)] rounded cursor-pointer" 
                                                title="Edit jenis">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if(strtoupper(trim($jenis->nama_tagihan)) !== 'SPP' && $jenis->tipe_nominal !== 'otomatis_spp')
                                            <form action="{{ route('backend.keuangan.tagihan.jenis.destroy', $jenis->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="confirmDeleteJenis(this, '{{ addslashes($jenis->nama_tagihan) }}')" 
                                                        class="p-1 text-[var(--danger)] rounded cursor-pointer" 
                                                        title="Hapus jenis">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-[var(--text-muted)]">Belum ada jenis tagihan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-5 py-3 border-t border-[var(--border)] bg-[var(--bg-page)] flex justify-end">
                <button type="button" @click="masterModalOpen = false" class="h-8 px-4 text-xs font-semibold text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)]">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL SUB: EDIT MASTER JENIS TAGIHAN --}}
    <div x-show="masterEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display: none;">
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] w-full max-w-sm shadow-[var(--shadow-modal)] overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between">
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit jenis tagihan</h3>
                <button type="button" @click="masterEditOpen = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="masterEditForm.action_url" method="POST" class="p-5 space-y-3.5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nama tagihan *</label>
                    <input type="text" name="nama_tagihan" x-model="masterEditForm.nama_tagihan" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Tipe nominal *</label>
                    <select name="tipe_nominal" x-model="masterEditForm.tipe_nominal" :disabled="masterEditForm.nama_tagihan.toUpperCase() === 'SPP' || masterEditForm.tipe_nominal === 'otomatis_spp'" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] disabled:opacity-50">
                        <option value="manual">Manual (input bebas)</option>
                        <option value="otomatis_spp">Otomatis SPP mahasiswa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nominal default (Rp)</label>
                    <input type="number" name="nominal_default" x-model="masterEditForm.nominal_default" :disabled="masterEditForm.tipe_nominal === 'otomatis_spp'" placeholder="150000" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] tabular-nums focus:outline-none focus:border-[var(--primary-700)] disabled:opacity-50">
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-[var(--border)]">
                    <button type="button" @click="masterEditOpen = false" class="h-9 px-3.5 text-xs font-semibold text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)]">Batal</button>
                    <button type="submit" class="h-9 px-4 text-xs font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] rounded-[var(--radius-xs)]">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteTagihan(button, namaTagihan, namaMhs) {
        const form = button.closest('form');
        const text = 'Apakah Anda yakin ingin menghapus data tagihan "' + namaTagihan + '" untuk ' + namaMhs + '? Tindakan ini tidak dapat dibatalkan.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                text: text,
                icon: 'warning',
                iconColor: '#D93025',
                showCancelButton: true,
                confirmButtonColor: '#D93025',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900',
                    title: 'text-base font-bold text-slate-900 dark:text-slate-100 font-display',
                    htmlContainer: 'text-xs text-slate-600 dark:text-slate-400',
                    confirmButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-sm cursor-pointer',
                    cancelButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-2xs cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                form.submit();
            }
        }
    }

    function confirmResendReminder(button, namaMhs, email) {
        const form = button.closest('form');
        const text = 'Kirim ulang email reminder tagihan ke ' + namaMhs + ' (' + email + ')?';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Kirim Ulang Reminder',
                text: text,
                icon: 'question',
                iconColor: '#006837',
                showCancelButton: true,
                confirmButtonColor: '#006837',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Kirim Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900',
                    title: 'text-base font-bold text-slate-900 dark:text-slate-100 font-display',
                    htmlContainer: 'text-xs text-slate-600 dark:text-slate-400',
                    confirmButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-sm cursor-pointer',
                    cancelButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-2xs cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                form.submit();
            }
        }
    }

    function confirmDeleteJenis(button, namaJenis) {
        const form = button.closest('form');
        const text = 'Apakah Anda yakin ingin menghapus jenis tagihan "' + namaJenis + '"? Tindakan ini tidak dapat dibatalkan.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                text: text,
                icon: 'warning',
                iconColor: '#D93025',
                showCancelButton: true,
                confirmButtonColor: '#D93025',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900',
                    title: 'text-base font-bold text-slate-900 dark:text-slate-100 font-display',
                    htmlContainer: 'text-xs text-slate-600 dark:text-slate-400',
                    confirmButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-sm cursor-pointer',
                    cancelButton: 'h-9 px-4 text-xs font-semibold rounded-lg shadow-2xs cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                form.submit();
            }
        }
    }
</script>
@endpush
@endsection
