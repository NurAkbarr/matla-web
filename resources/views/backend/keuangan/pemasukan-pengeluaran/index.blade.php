@extends('layouts.keuangan')

@section('title', 'Pencatatan Kas (Pemasukan & Pengeluaran)')

@section('content')
<script>
function kasPageData() {
    return {
        jenisPilihan: 'masuk',
        nominalInput: '',
        masterKategoris: @json($masterKategoris),
        
        // Modal State
        showDetailModal: false,
        showEditModal: false,
        showKategoriModal: false,
        selectedTx: null,
        
        // Edit Form State
        editForm: {
            id: null,
            actionUrl: '',
            jenis_transaksi: 'masuk',
            tanggal: '',
            jam: '',
            kategori: '',
            subjek: '',
            nominal: '',
            metode_pembayaran: 'Transfer Bank',
            no_referensi: '',
            deskripsi: ''
        },

        // Category Management State
        editKategoriId: null,
        editKategoriNama: '',
        editKategoriTipe: 'umum',

        get filteredKategori() {
            return this.masterKategoris.filter(k => k.tipe === this.jenisPilihan || k.tipe === 'umum');
        },

        get formattedNominal() {
            if (!this.nominalInput || isNaN(this.nominalInput)) return 'Rp 0';
            return 'Rp ' + parseInt(this.nominalInput, 10).toLocaleString('id-ID');
        },

        setJenis(jenis) {
            this.jenisPilihan = jenis;
        },

        openDetail(tx) {
            this.selectedTx = tx;
            this.showDetailModal = true;
        },

        openEdit(tx) {
            this.editForm.id = tx.id;
            this.editForm.actionUrl = '{{ url('/backend/keuangan/pemasukan-pengeluaran') }}/' + tx.id;
            this.editForm.jenis_transaksi = tx.jenis_transaksi;
            this.editForm.tanggal = tx.raw_tanggal || '';
            this.editForm.jam = tx.raw_jam || '';
            this.editForm.kategori = tx.kategori || '';
            this.editForm.subjek = tx.subjek || '';
            this.editForm.nominal = tx.nominal || '';
            this.editForm.metode_pembayaran = tx.metode || 'Transfer Bank';
            this.editForm.no_referensi = tx.no_ref && tx.no_ref !== '-' ? tx.no_ref : '';
            this.editForm.deskripsi = tx.deskripsi && tx.deskripsi !== '-' ? tx.deskripsi : '';
            this.showEditModal = true;
        },

        startEditKategori(kat) {
            this.editKategoriId = kat.id;
            this.editKategoriNama = kat.nama_kategori;
            this.editKategoriTipe = kat.tipe;
        },

        cancelEditKategori() {
            this.editKategoriId = null;
            this.editKategoriNama = '';
            this.editKategoriTipe = 'umum';
        }
    };
}
</script>

<div class="space-y-6" x-data="kasPageData()">

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

    @if(isset($errors) && $errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-[var(--radius-xs)] p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[var(--danger)] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-xs font-bold text-[var(--danger)] mb-1">Periksa kembali input formulir:</p>
            <ul class="list-disc list-inside text-xs text-[var(--danger)] space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Header Area: Title & Action Buttons --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Pencatatan kas (Pemasukan & Pengeluaran)</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Formulir resmi pencatatan transaksi arus kas masuk (kredit) dan kas keluar (debit) operasional kampus.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Tombol Kelola Master Kategori --}}
            <button type="button" 
                    @click="showKategoriModal = true"
                    class="h-10 inline-flex items-center gap-2 px-3.5 bg-[var(--surface)] border border-[var(--border)] hover:bg-[var(--primary-50)] text-[var(--text-body)] hover:text-[var(--primary-700)] text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Kelola kategori transaksi</span>
            </button>

            {{-- Link ke Riwayat Transaksi Lengkap --}}
            <a href="{{ route('backend.keuangan.riwayat-transaksi.index') }}" 
               class="h-10 inline-flex items-center gap-2 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Buku kas & laporan lengkap</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan Kas Hari Ini (3 Kartu Enterprise) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1: Pemasukan Hari Ini --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-[var(--text-muted)]">Pemasukan kas hari ini</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                        Masuk
                    </span>
                </div>
                <p class="text-2xl font-bold font-display text-[var(--primary-600)] tabular-nums mt-2">
                    + Rp {{ number_format($todayMasuk, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2">
                {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Card 2: Pengeluaran Hari Ini --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-[var(--danger)]">Pengeluaran kas hari ini</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                        Keluar
                    </span>
                </div>
                <p class="text-2xl font-bold font-display text-[var(--danger)] tabular-nums mt-2">
                    - Rp {{ number_format($todayKeluar, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2">
                Operasional & pengeluaran hari ini
            </p>
        </div>

        {{-- Card 3: Arus Kas Bersih Hari Ini --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-[var(--text-muted)]">Saldo bersih hari ini</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $todayNet >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                        {{ $todayNet >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
                <p class="text-2xl font-bold font-display {{ $todayNet >= 0 ? 'text-[var(--primary-600)]' : 'text-[var(--danger)]' }} tabular-nums mt-2">
                    Rp {{ number_format($todayNet, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-2 tabular-nums">
                {{ $countToday }} transaksi tercatat hari ini
            </p>
        </div>
    </div>

    {{-- FORM UTAMA PENCATATAN KAS BARU --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden shadow-xs">
        <div class="px-5 py-4 border-b border-[var(--border)] bg-[var(--bg-page)] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-bold font-display text-[var(--text-heading)]">Formulir transaksi kas baru</h2>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">Input rincian arus kas masuk atau arus kas keluar kampus secara presisi.</p>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-xs)] bg-[var(--primary-50)] text-[var(--primary-700)] text-[11px] font-semibold border border-[var(--primary-200)] shrink-0">
                <svg class="w-3.5 h-3.5 text-[var(--primary-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Status otomatis: Terverifikasi (Verified)</span>
            </div>
        </div>

        <form action="{{ route('backend.keuangan.pemasukan-pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
            @csrf

            {{-- 1. PILIHAN JENIS ARUS KAS (Segmented Two Large Cards) --}}
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-2">
                    Jenis arus kas <span class="text-[var(--danger)]">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    {{-- Opsi Uang Masuk --}}
                    <label @click="setJenis('masuk')"
                           :class="jenisPilihan === 'masuk' ? 'border-[var(--primary-500)] bg-[var(--primary-50)]/50 ring-1 ring-[var(--primary-500)]' : 'border-[var(--border)] bg-[var(--bg-page)] hover:bg-[var(--surface)]'"
                           class="flex items-start gap-3.5 p-4 rounded-[var(--radius-xs)] border transition cursor-pointer select-none">
                        <input type="radio" name="jenis_transaksi" value="masuk" x-model="jenisPilihan" class="mt-1 text-[var(--primary-600)] focus:ring-[var(--primary-500)]">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 font-bold text-xs">
                                    +
                                </span>
                                <span class="text-sm font-bold text-[var(--text-heading)]">Uang Masuk (Kredit / Penerimaan)</span>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                Pemasukan non-SPP, hibah, donasi, pendaftaran PMB, penjualan formulir, sewa fasilitas, dll.
                            </p>
                        </div>
                    </label>

                    {{-- Opsi Uang Keluar --}}
                    <label @click="setJenis('keluar')"
                           :class="jenisPilihan === 'keluar' ? 'border-rose-500 bg-rose-50/50 ring-1 ring-rose-500' : 'border-[var(--border)] bg-[var(--bg-page)] hover:bg-[var(--surface)]'"
                           class="flex items-start gap-3.5 p-4 rounded-[var(--radius-xs)] border transition cursor-pointer select-none">
                        <input type="radio" name="jenis_transaksi" value="keluar" x-model="jenisPilihan" class="mt-1 text-rose-600 focus:ring-rose-500">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 font-bold text-xs">
                                    -
                                </span>
                                <span class="text-sm font-bold text-[var(--text-heading)]">Uang Keluar (Debit / Pengeluaran)</span>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-1.5 leading-relaxed">
                                Biaya operasional, gaji dosen & staf, listrik, air, internet, sarana prasarana, konsumsi, dll.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 2. GRID FORMULIR DETAIL TRANSAKSI --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pt-1">
                {{-- Tanggal Transaksi --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Tanggal transaksi <span class="text-[var(--danger)]">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" 
                           required 
                           class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                </div>

                {{-- Waktu Presisi --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Waktu presisi
                    </label>
                    <input type="time" 
                           name="jam" 
                           value="{{ old('jam', date('H:i')) }}" 
                           class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                </div>

                {{-- Metode Pembayaran --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Metode pembayaran <span class="text-[var(--danger)]">*</span>
                    </label>
                    <select name="metode_pembayaran" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold text-[var(--text-heading)] focus:outline-none focus:border-[var(--primary-700)]">
                        <option value="Transfer Bank" {{ old('metode_pembayaran') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Kas / Tunai" {{ old('metode_pembayaran') === 'Kas / Tunai' ? 'selected' : '' }}>Kas / Tunai</option>
                        <option value="Virtual Account / QRIS" {{ old('metode_pembayaran') === 'Virtual Account / QRIS' ? 'selected' : '' }}>Virtual Account / QRIS</option>
                        <option value="Cek / Giro" {{ old('metode_pembayaran') === 'Cek / Giro' ? 'selected' : '' }}>Cek / Giro</option>
                    </select>
                </div>

                {{-- Kategori Transaksi --}}
                <div class="md:col-span-6">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-[var(--text-heading)]">
                            Kategori transaksi <span class="text-[var(--danger)]">*</span>
                        </label>
                        <button type="button" @click="showKategoriModal = true" class="text-[11px] font-semibold text-[var(--primary-600)] hover:underline cursor-pointer">
                            + Kelola kategori
                        </button>
                    </div>
                    <select name="kategori" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        <option value="">-- Pilih kategori transaksi --</option>
                        <template x-for="item in filteredKategori" :key="item.id">
                            <option :value="item.nama_kategori" x-text="item.nama_kategori + ' (' + item.tipe + ')'"></option>
                        </template>
                    </select>
                </div>

                {{-- Pihak Terkait / Subjek --}}
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        <span x-text="jenisPilihan === 'masuk' ? 'Pihak pembayar / sumber dana' : 'Penerima dana / vendor / keperluan'"></span>
                        <span class="text-[var(--danger)]">*</span>
                    </label>
                    <input type="text" 
                           name="subjek" 
                           value="{{ old('subjek') }}" 
                           :placeholder="jenisPilihan === 'masuk' ? 'Contoh: Donatur H. Ahmad, Yayasan, Pendaftar PMB...' : 'Contoh: PT PLN Persero, Toko ATK Berkah, Pengajar Tamu...'" 
                           required 
                           class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)]">
                </div>

                {{-- Nominal Transaksi --}}
                <div class="md:col-span-6">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-[var(--text-heading)]">
                            Nominal (Rp) <span class="text-[var(--danger)]">*</span>
                        </label>
                        <span class="text-xs font-bold text-[var(--primary-600)] tabular-nums" x-text="formattedNominal"></span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs text-[var(--text-muted)] font-semibold pointer-events-none">Rp</span>
                        <input type="number" 
                               name="nominal" 
                               x-model="nominalInput"
                               value="{{ old('nominal') }}"
                               min="1" 
                               step="any" 
                               placeholder="1000000" 
                               required 
                               class="w-full h-10 pl-9 pr-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] font-bold tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                    </div>
                </div>

                {{-- Nomor Referensi / Struk --}}
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        No. referensi / No. nota (opsional)
                    </label>
                    <input type="text" 
                           name="no_referensi" 
                           value="{{ old('no_referensi') }}" 
                           placeholder="Contoh: INV-2026-001, No. Resi, dsb." 
                           class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)]">
                </div>

                {{-- Rincian Keterangan --}}
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Rincian keterangan / catatan
                    </label>
                    <textarea name="deskripsi" 
                              rows="3" 
                              placeholder="Catatan detail mengenai peruntukan atau keperluan transaksi ini..." 
                              class="w-full p-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] resize-none">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Upload Bukti Struk/Nota --}}
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1.5">
                        Upload bukti nota / struk / slip transfer
                    </label>
                    <div class="p-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)]">
                        <input type="file" 
                               name="bukti" 
                               accept="image/*,application/pdf" 
                               class="w-full text-xs text-[var(--text-muted)] file:mr-3 file:h-8 file:px-3 file:rounded-[var(--radius-xs)] file:border-0 file:text-xs file:font-semibold file:bg-[var(--primary-50)] file:text-[var(--primary-700)] hover:file:bg-[var(--primary-100)] cursor-pointer">
                        <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Format: JPG, PNG, atau PDF (Maksimal 5MB)</p>
                    </div>
                </div>
            </div>

            {{-- Submit Area --}}
            <div class="pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-[var(--border)]">
                <p class="text-xs text-[var(--text-muted)]">
                    Petugas pencatat: <span class="font-semibold text-[var(--text-heading)]">{{ auth()->user()->name ?? 'Admin Keuangan' }}</span>
                </p>
                <button type="submit" 
                        :class="jenisPilihan === 'masuk' ? 'bg-[var(--primary-500)] hover:bg-[var(--primary-600)]' : 'bg-rose-600 hover:bg-rose-700'"
                        class="h-10 px-6 text-white text-xs font-semibold rounded-[var(--radius-xs)] transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="jenisPilihan === 'masuk' ? 'Simpan Transaksi Kas Masuk' : 'Simpan Transaksi Kas Keluar'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT TRANSAKSI KAS TERBARU DI HALAMAN INI --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[var(--border)] bg-[var(--bg-page)] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-xs font-bold font-display text-[var(--text-heading)]">Riwayat transaksi kas terbaru</h3>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Daftar transaksi kas yang baru diinput. Dapat langsung diedit atau dihapus jika terdapat kesalahan.</p>
            </div>

            {{-- Filter & Search Form --}}
            <form action="{{ route('backend.keuangan.pemasukan-pengeluaran.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="jenis" onchange="this.form.submit()" class="h-9 px-2.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    <option value="">Semua jenis</option>
                    <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Uang Masuk</option>
                    <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Uang Keluar</option>
                </select>

                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari transaksi..." 
                           class="h-9 pl-8 pr-3 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)] w-48">
                    <svg class="w-3.5 h-3.5 text-[var(--text-muted)] absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-5 pr-2 w-12 text-center">No</th>
                        <th scope="col" class="py-3.5 px-3">Kode TRX</th>
                        <th scope="col" class="py-3.5 px-3">Waktu</th>
                        <th scope="col" class="py-3.5 px-3 text-center">Jenis</th>
                        <th scope="col" class="py-3.5 px-3">Kategori</th>
                        <th scope="col" class="py-3.5 px-3">Pihak terkait / subjek</th>
                        <th scope="col" class="py-3.5 px-3 text-right">Nominal (Rp)</th>
                        <th scope="col" class="py-3.5 px-3 text-center">Bukti</th>
                        <th scope="col" class="py-3.5 px-3">Petugas</th>
                        <th scope="col" class="py-3.5 pl-2 pr-5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($recentTransaksis as $idx => $tx)
                    @php
                        $isMasuk = $tx->isMasuk();
                        $txDataJson = json_encode([
                            'id' => $tx->id,
                            'kode' => $tx->kode_transaksi,
                            'jenis_transaksi' => $tx->jenis_transaksi,
                            'is_masuk' => $isMasuk,
                            'kategori' => $tx->kategori,
                            'subjek' => $tx->subjek ?: ($tx->user ? $tx->user->name : '-'),
                            'nominal' => $tx->nominal,
                            'nominal_formatted' => ($isMasuk ? '+ Rp ' : '- Rp ') . number_format($tx->nominal, 0, ',', '.'),
                            'raw_tanggal' => $tx->tanggal_transaksi ? $tx->tanggal_transaksi->format('Y-m-d') : '',
                            'raw_jam' => $tx->tanggal_transaksi ? $tx->tanggal_transaksi->format('H:i') : '',
                            'waktu' => $tx->tanggal_transaksi ? $tx->tanggal_transaksi->translatedFormat('d M Y, H:i') : '-',
                            'metode' => $tx->metode_pembayaran ?: 'Transfer Bank',
                            'no_ref' => $tx->no_referensi ?: '-',
                            'deskripsi' => $tx->deskripsi ?: '-',
                            'admin' => $tx->admin_nama ?: 'Admin Keuangan',
                            'bukti_url' => $tx->bukti ? asset('storage/' . $tx->bukti) : null,
                            'cetak_url' => route('backend.keuangan.pemasukan-pengeluaran.kwitansi', $tx->id),
                        ]);
                    @endphp
                    <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                        <td class="py-3 pl-5 pr-2 text-center text-[var(--text-muted)] tabular-nums" data-label="No">
                            {{ $recentTransaksis->firstItem() + $idx }}
                        </td>
                        <td class="py-3 px-3 font-mono font-semibold text-[var(--text-heading)]" data-label="Kode TRX">
                            {{ $tx->kode_transaksi }}
                        </td>
                        <td class="py-3 px-3 text-[var(--text-body)] tabular-nums whitespace-nowrap" data-label="Waktu">
                            {{ $tx->tanggal_transaksi ? $tx->tanggal_transaksi->translatedFormat('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-3 px-3 text-center" data-label="Jenis">
                            @if($isMasuk)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                    Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                                    Keluar
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3 font-medium text-[var(--text-heading)]" data-label="Kategori">
                            {{ $tx->kategori }}
                        </td>
                        <td class="py-3 px-3 text-[var(--text-body)] font-medium" data-label="Subjek">
                            {{ $tx->subjek ?: ($tx->user ? $tx->user->name : '-') }}
                        </td>
                        <td class="py-3 px-3 text-right font-bold tabular-nums {{ $isMasuk ? 'text-[var(--primary-600)]' : 'text-[var(--danger)]' }}" data-label="Nominal">
                            {{ $isMasuk ? '+ ' : '- ' }}Rp {{ number_format($tx->nominal, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-center" data-label="Bukti">
                            @if($tx->bukti)
                                <a href="{{ asset('storage/' . $tx->bukti) }}" target="_blank" class="inline-flex items-center text-[var(--primary-600)] hover:underline text-[11px] font-semibold">
                                    <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span>Nota</span>
                                </a>
                            @else
                                <span class="text-[var(--text-muted)]">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-[var(--text-muted)] text-[11px]" data-label="Petugas">
                            {{ $tx->admin_nama ?: 'Admin' }}
                        </td>
                        <td class="py-3 pl-2 pr-5 text-center" data-label="Aksi">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Tombol Detail --}}
                                <button type="button" 
                                        @click='openDetail({{ $txDataJson }})' 
                                        title="Rincian Transaksi" 
                                        class="w-7 h-7 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>

                                {{-- Tombol Cetak Kwitansi --}}
                                <a href="{{ route('backend.keuangan.pemasukan-pengeluaran.kwitansi', $tx->id) }}" 
                                   target="_blank" 
                                   title="Cetak Kwitansi" 
                                   class="w-7 h-7 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--text-muted)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>

                                {{-- Tombol Edit --}}
                                <button type="button" 
                                        @click='openEdit({{ $txDataJson }})' 
                                        title="Koreksi / Edit Transaksi" 
                                        class="w-7 h-7 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('backend.keuangan.pemasukan-pengeluaran.destroy', $tx->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi [{{ $tx->kode_transaksi }}]? Tindakan ini tidak dapat dibatalkan.');" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Hapus Transaksi" 
                                            class="w-7 h-7 flex items-center justify-center rounded-[var(--radius-xs)] text-[var(--danger)] hover:bg-rose-50 border border-[var(--border)] transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-xs text-[var(--text-muted)]">
                            Belum ada riwayat transaksi kas yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if($recentTransaksis->hasPages())
        <div class="px-5 py-3 border-t border-[var(--border)] bg-[var(--bg-page)]">
            {{ $recentTransaksis->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL EDIT TRANSAKSI KAS --}}
    <div x-show="showEditModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showEditModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-xl w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Edit transaksi kas</h3>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Koreksi kesalahan input data transaksi kas</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="editForm.actionUrl" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden min-h-0 m-0">
                @csrf
                @method('PUT')

                <div class="p-5 overflow-y-auto flex-1 space-y-3.5">
                    {{-- Jenis Arus Kas --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Jenis arus kas *</label>
                        <select name="jenis_transaksi" x-model="editForm.jenis_transaksi" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            <option value="masuk">Uang Masuk (Kredit)</option>
                            <option value="keluar">Uang Keluar (Debit)</option>
                        </select>
                    </div>

                    {{-- Tanggal & Jam --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Tanggal *</label>
                            <input type="date" name="tanggal" x-model="editForm.tanggal" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Waktu</label>
                            <input type="time" name="jam" x-model="editForm.jam" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                    </div>

                    {{-- Kategori & Metode --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Kategori *</label>
                            <select name="kategori" x-model="editForm.kategori" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="">-- Pilih kategori --</option>
                                <template x-for="item in masterKategoris" :key="item.id">
                                    <option :value="item.nama_kategori" x-text="item.nama_kategori + ' (' + item.tipe + ')'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Metode pembayaran *</label>
                            <select name="metode_pembayaran" x-model="editForm.metode_pembayaran" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Kas / Tunai">Kas / Tunai</option>
                                <option value="Virtual Account / QRIS">Virtual Account / QRIS</option>
                                <option value="Cek / Giro">Cek / Giro</option>
                            </select>
                        </div>
                    </div>

                    {{-- Subjek --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Pihak terkait / vendor / sumber *</label>
                        <input type="text" name="subjek" x-model="editForm.subjek" required class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                    </div>

                    {{-- Nominal & No Referensi --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" x-model="editForm.nominal" required min="1" step="any" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] font-bold tabular-nums focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">No. referensi</label>
                            <input type="text" name="no_referensi" x-model="editForm.no_referensi" class="w-full h-10 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Rincian keterangan</label>
                        <textarea name="deskripsi" x-model="editForm.deskripsi" rows="2" class="w-full p-2.5 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)] resize-none"></textarea>
                    </div>

                    {{-- Upload Bukti Baru --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Ganti file bukti struk/nota (opsional)</label>
                        <input type="file" name="bukti" accept="image/*,application/pdf" class="w-full text-xs text-[var(--text-muted)] file:mr-3 file:h-8 file:px-3 file:rounded-[var(--radius-xs)] file:border-0 file:text-xs file:font-semibold file:bg-[var(--primary-50)] file:text-[var(--primary-700)] cursor-pointer">
                    </div>
                </div>

                <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex items-center justify-end gap-2 shrink-0">
                    <button type="button" @click="showEditModal = false" class="h-9 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)]">
                        Batal
                    </button>
                    <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)] transition cursor-pointer">
                        Simpan perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL TRANSAKSI --}}
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showDetailModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-md w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <template x-if="selectedTx">
                <div class="flex flex-col flex-1 overflow-hidden min-h-0">
                    <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                        <div>
                            <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Rincian bukti transaksi</h3>
                            <p class="text-[11px] text-[var(--text-muted)] font-mono mt-0.5" x-text="selectedTx.kode"></p>
                        </div>
                        <button type="button" @click="showDetailModal = false" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-5 overflow-y-auto flex-1 space-y-4 text-xs">
                        <div class="p-4 rounded-[var(--radius-xs)] text-center border"
                             :class="selectedTx.is_masuk ? 'bg-[var(--primary-50)] border-[var(--primary-200)]' : 'bg-rose-50 border-rose-200'">
                            <span class="text-[11px] font-semibold uppercase tracking-wider block"
                                  :class="selectedTx.is_masuk ? 'text-[var(--primary-700)]' : 'text-[var(--danger)]'"
                                  x-text="selectedTx.is_masuk ? 'Pemasukan kas' : 'Pengeluaran kas'"></span>
                            <p class="text-2xl font-bold font-display mt-1 tabular-nums"
                               :class="selectedTx.is_masuk ? 'text-[var(--primary-700)]' : 'text-[var(--danger)]'"
                               x-text="selectedTx.nominal_formatted"></p>
                        </div>

                        <div class="space-y-2.5">
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Waktu</span>
                                <span class="font-semibold text-[var(--text-heading)] tabular-nums" x-text="selectedTx.waktu"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Kategori</span>
                                <span class="font-semibold text-[var(--text-heading)]" x-text="selectedTx.kategori"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Pihak / subjek</span>
                                <span class="font-semibold text-[var(--text-heading)]" x-text="selectedTx.subjek"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Metode</span>
                                <span class="font-medium text-[var(--text-heading)]" x-text="selectedTx.metode"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">No. referensi</span>
                                <span class="font-mono text-[var(--text-heading)]" x-text="selectedTx.no_ref"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-[var(--border)]">
                                <span class="text-[var(--text-muted)]">Petugas</span>
                                <span class="font-medium text-[var(--text-heading)]" x-text="selectedTx.admin"></span>
                            </div>
                            <div class="pt-1">
                                <span class="text-[var(--text-muted)] block mb-1">Keterangan:</span>
                                <p class="text-[var(--text-body)] bg-[var(--bg-page)] p-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] leading-relaxed" x-text="selectedTx.deskripsi"></p>
                            </div>
                            
                            <template x-if="selectedTx.bukti_url">
                                <div class="pt-1">
                                    <a :href="selectedTx.bukti_url" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-[var(--primary-600)] font-semibold hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Buka lampiran bukti struk / nota</span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex items-center justify-between gap-2 shrink-0">
                        <button type="button" @click="showDetailModal = false" class="h-9 px-3.5 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)]">
                            Tutup
                        </button>
                        <a :href="selectedTx.cetak_url" target="_blank" class="h-9 px-4 inline-flex items-center gap-1.5 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white text-xs font-semibold rounded-[var(--radius-xs)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak kwitansi</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- MODAL MASTER KATEGORI TRANSAKSI --}}
    <div x-show="showKategoriModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showKategoriModal = false" 
             class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] max-w-xl w-full max-h-[90vh] flex flex-col shadow-[var(--shadow-modal)] overflow-hidden">
            
            <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Master kategori transaksi</h3>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Klasifikasi pos arus kas masuk dan kas keluar buku kas</p>
                </div>
                <button type="button" @click="showKategoriModal = false; cancelEditKategori();" class="text-[var(--text-muted)] hover:text-[var(--text-heading)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto flex-1 space-y-4">
                {{-- Form Tambah/Edit Kategori --}}
                <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-4">
                    <div class="flex items-center justify-between mb-2.5">
                        <h4 class="text-xs font-bold text-[var(--text-heading)]">
                            <span x-text="editKategoriId ? 'Edit kategori transaksi' : 'Tambah kategori baru'"></span>
                        </h4>
                        <template x-if="editKategoriId">
                            <button type="button" @click="cancelEditKategori()" class="text-xs text-[var(--primary-600)] hover:underline font-semibold cursor-pointer">
                                Batal edit
                            </button>
                        </template>
                    </div>

                    <form :action="editKategoriId ? '{{ url('/backend/keuangan/pemasukan-pengeluaran/master-kategori') }}/' + editKategoriId : '{{ route('backend.keuangan.pemasukan-pengeluaran.kategori.store') }}'" method="POST">
                        @csrf
                        <template x-if="editKategoriId">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <div class="sm:col-span-6">
                                <label class="block text-[11px] font-semibold text-[var(--text-heading)] mb-1">Nama kategori *</label>
                                <input type="text" 
                                       name="nama_kategori" 
                                       x-model="editKategoriNama" 
                                       placeholder="Misal: Operasional, Gaji..." 
                                       required 
                                       class="w-full h-9 px-2.5 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold text-[var(--text-heading)] mb-1">Tipe arus kas *</label>
                                <select name="tipe" x-model="editKategoriTipe" required class="w-full h-9 px-2 bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
                                    <option value="umum">Umum</option>
                                    <option value="masuk">Uang masuk</option>
                                    <option value="keluar">Uang keluar</option>
                                </select>
                            </div>

                            <div class="sm:col-span-3 flex items-end">
                                <button type="submit" 
                                        class="w-full h-9 px-3 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white font-semibold text-xs rounded-[var(--radius-xs)] transition cursor-pointer">
                                    <span x-text="editKategoriId ? 'Simpan' : 'Tambah'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Kategori --}}
                <div class="border border-[var(--border)] rounded-[var(--radius-xs)] overflow-hidden max-h-56 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 border-b border-[var(--border)] font-semibold text-[var(--text-body)] sticky top-0 z-10">
                            <tr>
                                <th class="py-2 px-3 w-10 text-center">No</th>
                                <th class="py-2 px-3">Nama kategori</th>
                                <th class="py-2 px-3 w-28">Tipe</th>
                                <th class="py-2 px-3 w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                            @forelse($masterKategoris as $idx => $mk)
                            <tr class="hover:bg-[var(--primary-50)]/40 transition">
                                <td class="py-2 px-3 text-center text-[var(--text-muted)] tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-semibold text-[var(--text-heading)]">{{ $mk->nama_kategori }}</td>
                                <td class="py-2 px-3">
                                    <span class="text-[11px]">{{ ucfirst($mk->tipe) }}</span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" 
                                                @click="startEditKategori({ id: {{ $mk->id }}, nama_kategori: '{{ addslashes($mk->nama_kategori) }}', tipe: '{{ $mk->tipe }}' })" 
                                                title="Edit kategori" 
                                                class="p-1 text-[var(--text-muted)] hover:text-[var(--primary-600)] rounded cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('backend.keuangan.pemasukan-pengeluaran.kategori.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Hapus kategori \'{{ addslashes($mk->nama_kategori) }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus kategori" class="p-1 text-[var(--danger)] rounded cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-[var(--text-muted)]">Belum ada kategori terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-5 py-3 bg-[var(--bg-page)] border-t border-[var(--border)] flex justify-end shrink-0">
                <button type="button" 
                        @click="showKategoriModal = false; cancelEditKategori();" 
                        class="h-8 px-4 bg-[var(--surface)] border border-[var(--border)] text-[var(--text-body)] text-xs font-semibold rounded-[var(--radius-xs)]">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
