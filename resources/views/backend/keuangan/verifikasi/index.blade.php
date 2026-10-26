@extends('layouts.keuangan')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">

    {{-- Header Area --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Verifikasi pembayaran & alokasi SPP</h1>
                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                    Audit Kas & Alokasi Lintas Bulan
                </span>
            </div>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">
                Periksa bukti transfer, alokasikan bulan SPP yang dibayarkan, dan verifikasi kwitansi tanpa mengubah tanggal mutasi buku kas.
            </p>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-[var(--radius-xs)] text-xs font-semibold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)] self-start sm:self-auto tabular-nums">
            <span>{{ count($pembayarans) }} total pengajuan</span>
        </div>
    </div>

    {{-- Filter Bar (Tinggi 40px, Radius 4px, Border Line) --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[220px]">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama, NIM, atau kwitansi..." class="w-full h-10 pl-9 pr-3.5 border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] bg-[var(--bg-page)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)] focus:ring-1 focus:ring-[var(--primary-700)]">
            <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select id="statusFilter" onchange="filterTable()" class="h-10 border border-[var(--border)] rounded-[var(--radius-xs)] px-3 text-xs text-[var(--text-body)] bg-[var(--bg-page)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua status</option>
            <option value="pending">Menunggu verifikasi</option>
            <option value="verified">Terverifikasi</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    {{-- Table Container --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse" id="verifikasiTable">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-5 pr-3 w-[115px]">Waktu Kas</th>
                        <th scope="col" class="py-3.5 px-3 w-[95px]">NIM</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[170px]">Nama mahasiswa</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[140px]">Program studi</th>
                        <th scope="col" class="py-3.5 px-3 w-[110px]">Rincian</th>
                        <th scope="col" class="py-3.5 px-4 text-right w-[115px]">Nominal</th>
                        <th scope="col" class="py-3.5 px-3 min-w-[150px]">Bulan Alokasi SPP</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[75px]">Bukti</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[110px]">Kwitansi</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[110px]">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-[130px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($pembayarans as $bayar)
                    @php
                        $subjek = $bayar->user->name ?? '-';
                        $initial = strtoupper(substr($subjek, 0, 1));
                        $isSpp = ($bayar->tagihan && (
                            strtoupper(trim($bayar->tagihan->nama_tagihan)) === 'SPP' ||
                            stripos($bayar->tagihan->nama_tagihan, 'spp') !== false
                        ));
                        $monthLabels = [
                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                        ];
                    @endphp
                    <tr class="hover:bg-[var(--primary-50)]/40 transition-colors" data-status="{{ $bayar->status }}" data-search="{{ strtolower($subjek . ' ' . ($bayar->user->nim ?? '') . ' ' . ($bayar->kwitansi ?? '')) }}">
                        {{-- Waktu Kas / Aktual Pembayaran (Audit Kas Bank) --}}
                        <td class="py-3.5 pl-5 pr-3 tabular-nums">
                            <span class="block text-[var(--text-heading)] font-semibold">{{ \Carbon\Carbon::parse($bayar->created_at)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-[var(--text-muted)]">{{ \Carbon\Carbon::parse($bayar->created_at)->format('H:i') }} WIB</span>
                        </td>

                        {{-- NIM --}}
                        <td class="py-3.5 px-3 font-semibold text-[var(--text-heading)] tabular-nums">
                            {{ $bayar->user->nim ?? '-' }}
                        </td>

                        {{-- Nama Mahasiswa --}}
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ $initial }}
                                </div>
                                <div class="truncate max-w-[160px]">
                                    <span class="font-semibold text-[var(--text-heading)] block truncate">{{ $subjek }}</span>
                                    <span class="text-[11px] text-[var(--text-muted)] block">Tarif SPP: Rp {{ number_format($bayar->user->nominal_spp ?? 300000, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Program Studi --}}
                        <td class="py-3.5 px-4 text-[var(--text-muted)] truncate max-w-[140px]">
                            {{ $bayar->user->education['program_studi'] ?? ($bayar->user->prodi ?? '-') }}
                        </td>

                        {{-- Rincian Tagihan --}}
                        <td class="py-3.5 px-3 text-[var(--text-muted)] truncate max-w-[120px]" title="{{ $bayar->tagihan->nama_tagihan ?? '-' }}">
                            <span class="font-medium text-[var(--text-heading)] block truncate">{{ $bayar->tagihan->nama_tagihan ?? '-' }}</span>
                            <span class="text-[11px] text-[var(--text-muted)] block">{{ ucfirst($bayar->jenis_pembayaran) }}</span>
                        </td>

                        {{-- Nominal Utuh --}}
                        <td class="py-3.5 px-4 text-right font-bold text-[var(--text-heading)] tabular-nums">
                            Rp {{ number_format($bayar->nominal, 0, ',', '.') }}
                        </td>

                        {{-- Bulan Alokasi SPP (Cross-Month Allocation) --}}
                        <td class="py-3.5 px-3">
                            @if(!empty($bayar->bulan_alokasi) && is_array($bayar->bulan_alokasi))
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @foreach($bayar->bulan_alokasi as $bKey)
                                        @php
                                            $bText = $bKey;
                                            if (preg_match('/^(\d{4})-(\d{2})$/', $bKey, $mPart)) {
                                                $mNum = (int)$mPart[2];
                                                $bText = ($monthLabels[$mNum] ?? "Bln {$mNum}") . " '" . substr($mPart[1], 2);
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-[var(--radius-xs)] text-[10px] font-bold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)]" title="Dialokasikan untuk {{ $bKey }}">
                                            ✔ {{ $bText }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-[var(--text-muted)] text-[11px] italic">Belum dialokasikan</span>
                            @endif
                        </td>

                        {{-- Bukti TF --}}
                        <td class="py-3.5 px-3 text-center">
                            @if($bayar->bukti_tf)
                                <a href="{{ route('backend.keuangan.verifikasi.bukti', $bayar->id) }}" target="_blank" class="w-8 h-8 inline-flex items-center justify-center text-[var(--primary-600)] hover:bg-[var(--primary-50)] rounded-[var(--radius-xs)] border border-[var(--border)] transition" title="Lihat bukti transfer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            @else
                                <span class="text-[var(--text-muted)]">-</span>
                            @endif
                        </td>

                        {{-- Kwitansi --}}
                        <td class="py-3.5 px-3 text-center">
                            @if($bayar->kwitansi)
                                <a href="{{ route('backend.keuangan.verifikasi.kwitansi-drive', $bayar->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-mono font-semibold text-[var(--primary-600)] hover:underline truncate max-w-[100px]" title="Buka kwitansi Google Drive">
                                    {{ $bayar->kwitansi }}
                                </a>
                            @else
                                <span class="text-[var(--text-muted)] text-[11px]">Belum terbit</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="py-3.5 px-3 text-center">
                            @if($bayar->status === 'verified')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--primary-50)] text-[var(--primary-600)] border border-[var(--primary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--primary-500)] mr-1.5"></span>
                                    Terverifikasi
                                </span>
                            @elseif($bayar->status === 'rejected')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-rose-50 text-[var(--danger)] border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--danger)] mr-1.5"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[var(--radius-xs)] text-[11px] font-semibold bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:text-[var(--secondary-400)] border border-[var(--secondary-200)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--secondary-500)] mr-1.5"></span>
                                    Menunggu
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="py-3.5 pl-3 pr-5 text-center">
                            @php
                                $mhsTarif = $bayar->user->nominal_spp ?? 300000;
                                $payData = [
                                    'id' => $bayar->id,
                                    'nama' => $bayar->user->name ?? 'Mahasiswa',
                                    'nim' => $bayar->user->nim ?? '-',
                                    'prodi' => $bayar->user->education['program_studi'] ?? ($bayar->user->prodi ?? '-'),
                                    'tagihan' => $bayar->tagihan->nama_tagihan ?? 'Tagihan Mahasiswa',
                                    'nominal' => $bayar->nominal,
                                    'tarif_spp' => $mhsTarif,
                                    'created_at' => \Carbon\Carbon::parse($bayar->created_at)->format('d M Y H:i'),
                                    'bulan_alokasi' => $bayar->bulan_alokasi ?: [],
                                    'catatan_alokasi' => $bayar->catatan_alokasi ?: '',
                                    'tahun_akademik_id' => $bayar->tahun_akademik_id,
                                ];
                            @endphp

                            @if($bayar->status === 'pending')
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" 
                                            onclick='openModalVerifikasi(@json($payData))' 
                                            class="h-8 px-2.5 inline-flex items-center gap-1 text-[11px] font-semibold text-white bg-[var(--primary-500)] hover:bg-[var(--primary-600)] rounded-[var(--radius-xs)] transition cursor-pointer shadow-xs" 
                                            title="Verifikasi Pembayaran & Pilih Bulan Alokasi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Verifikasi</span>
                                    </button>

                                    <form action="{{ route('backend.keuangan.verifikasi.reject', $bayar->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pembayaran ini?');">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-[var(--danger)] hover:bg-rose-50 border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer" title="Tolak pembayaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @elseif($bayar->status === 'verified')
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" 
                                            onclick='openModalEditAlokasi(@json($payData))' 
                                            class="h-8 px-2 text-[11px] font-semibold text-[var(--primary-700)] bg-[var(--primary-50)] hover:bg-[var(--primary-100)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] transition cursor-pointer" 
                                            title="Atur atau ubah bulan alokasi SPP">
                                        Ubah Alokasi
                                    </button>

                                    <form action="{{ route('backend.keuangan.verifikasi.reject', $bayar->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan verifikasi pembayaran ini? Transaksi di Buku Kas akan otomatis dibatalkan.');">
                                        @csrf
                                        <button type="submit" class="h-8 px-2 text-[11px] font-semibold text-[var(--danger)] bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-[var(--radius-xs)] transition cursor-pointer" title="Batalkan verifikasi">
                                            Batal
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button type="button" 
                                        onclick='openModalVerifikasi(@json($payData))' 
                                        class="h-8 px-2.5 text-[11px] font-semibold text-[var(--primary-700)] bg-[var(--primary-50)] hover:bg-[var(--primary-100)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] transition cursor-pointer">
                                    Verifikasi Ulang
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-12 text-center text-xs text-[var(--text-muted)]">
                            <p class="font-semibold text-[var(--text-heading)]">Belum ada data verifikasi pembayaran.</p>
                            <p class="mt-1">Seluruh pengajuan bukti transfer pembayaran akan tercantum di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3.5 border-t border-[var(--border)] flex items-center justify-between">
            <p class="text-xs text-[var(--text-muted)] tabular-nums">Total {{ count($pembayarans) }} data pembayaran</p>
        </div>
    </div>
</div>

{{-- MODAL VERIFIKASI & ALOKASI BULAN SPP (PENDING PAYMENTS) --}}
<div id="modal-verifikasi" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        
        {{-- Modal Header --}}
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)] shrink-0">
            <div>
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Verifikasi Pembayaran & Alokasi SPP</h3>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Alokasikan bulan SPP yang dibayarkan tanpa mengubah tanggal kas bank.</p>
            </div>
            <button type="button" onclick="closeModal('modal-verifikasi')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form id="form-verifikasi" method="POST" class="p-5 overflow-y-auto space-y-4">
            @csrf

            {{-- Ringkasan Transfer & Mahasiswa --}}
            <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-3.5 space-y-2 text-xs">
                <div class="flex items-center justify-between border-b border-[var(--border)] pb-2">
                    <div>
                        <span class="font-bold text-[var(--text-heading)]" id="verif_nama">-</span>
                        <span class="text-[var(--text-muted)] ml-1" id="verif_nim">(-)</span>
                    </div>
                    <span class="text-[var(--text-muted)]" id="verif_prodi">-</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 text-[11px]">
                    <div>
                        <span class="text-[var(--text-muted)] block">Nominal Transfer:</span>
                        <span class="font-bold text-sm text-[var(--primary-600)] tabular-nums" id="verif_nominal">Rp 0</span>
                    </div>
                    <div>
                        <span class="text-[var(--text-muted)] block">Tarif SPP Mahasiswa:</span>
                        <span class="font-semibold text-[var(--text-heading)] tabular-nums" id="verif_tarif">Rp 0 / bln</span>
                    </div>
                    <div>
                        <span class="text-[var(--text-muted)] block">Tanggal Aktual Kas:</span>
                        <span class="font-semibold text-[var(--text-heading)]" id="verif_tanggal">-</span>
                    </div>
                </div>
            </div>

            {{-- Callout Audit Trail Kas --}}
            <div class="p-3 bg-[var(--primary-50)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] text-[11px] text-[var(--primary-800)] flex items-start gap-2">
                <svg class="w-4 h-4 text-[var(--primary-600)] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <b>Pencatatan Arus Kas Utuh:</b> Uang masuk dicatat utuh sebesar nominal transfer pada tanggal aktual transfer di atas. Mutasi buku kas bank tidak diubah bulannya agar audit trail valid.
                </div>
            </div>

            {{-- Pilihan Bulan Alokasi SPP --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-[var(--text-heading)]">
                        Pilih Bulan Alokasi SPP (Checklist) <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[11px] text-[var(--text-muted)]">Centang bulan-bulan yang dilunasi</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="verif_checkbox_container">
                    {{-- Dinamis via Javascript berdasarkan Tahun Akademik Aktif --}}
                </div>
                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">
                    Matriks <b>Rekap SPP per Semester</b> akan otomatis memperbarui status centang (✔ Lunas) pada kolom bulan yang Anda centang di atas.
                </p>
            </div>

            {{-- Catatan Tambahan --}}
            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Catatan Verifikasi / Alokasi (Opsional)</label>
                <input type="text" name="catatan_alokasi" placeholder="Misal: Pelunasan SPP Juli-Agustus via transfer Oktober" class="w-full h-9 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
            </div>

            {{-- Modal Actions --}}
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)] shrink-0">
                <button type="button" onclick="closeModal('modal-verifikasi')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition shadow-xs cursor-pointer">
                    Setujui & Simpan Alokasi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT ALOKASI SPP (VERIFIED PAYMENTS) --}}
<div id="modal-edit-alokasi" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        
        <div class="px-5 py-4 border-b border-[var(--border)] flex items-center justify-between bg-[var(--bg-page)] shrink-0">
            <div>
                <h3 class="text-sm font-bold font-display text-[var(--text-heading)]">Ubah Bulan Alokasi SPP</h3>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Sesuaikan kembali centang bulan alokasi tanpa mengubah mutasi kas.</p>
            </div>
            <button type="button" onclick="closeModal('modal-edit-alokasi')" class="p-1 text-[var(--text-muted)] hover:text-[var(--text-heading)] rounded-[var(--radius-xs)] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-edit-alokasi" method="POST" class="p-5 overflow-y-auto space-y-4">
            @csrf

            <div class="bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] p-3 text-xs flex items-center justify-between">
                <div>
                    <span class="font-bold text-[var(--text-heading)]" id="edit_alok_nama">-</span>
                    <span class="text-[var(--text-muted)] ml-1" id="edit_alok_nim">(-)</span>
                </div>
                <span class="font-bold text-[var(--primary-600)] tabular-nums" id="edit_alok_nominal">Rp 0</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-[var(--text-heading)] mb-2">
                    Centang Bulan Alokasi SPP yang Dilunasi:
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="edit_alok_checkbox_container">
                    {{-- Dinamis via Javascript --}}
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--text-heading)] mb-1">Catatan Alokasi (Opsional)</label>
                <input type="text" id="edit_alok_catatan" name="catatan_alokasi" class="w-full h-9 px-3 bg-[var(--bg-page)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] focus:outline-none focus:border-[var(--primary-700)]">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border)] shrink-0">
                <button type="button" onclick="closeModal('modal-edit-alokasi')" class="h-9 px-3.5 bg-[var(--surface)] hover:bg-[var(--bg-page)] text-[var(--text-body)] border border-[var(--border)] rounded-[var(--radius-xs)] text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[var(--primary-500)] hover:bg-[var(--primary-600)] text-white rounded-[var(--radius-xs)] text-xs font-semibold transition shadow-xs cursor-pointer">
                    Simpan Perubahan Alokasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Data Konfigurasi Bulan Semester Aktif
    @php
        $tahunStr = $activeTa->tahun ?? date('Y') . '/' . (date('Y') + 1);
        $taParts = explode('/', $tahunStr);
        $sYear = isset($taParts[0]) && is_numeric($taParts[0]) ? (int)$taParts[0] : (int)date('Y');
        $eYear = isset($taParts[1]) && is_numeric($taParts[1]) ? (int)$taParts[1] : ($sYear + 1);

        // Siapkan 12 bulan kalender lengkap agar leluasa alokasi lintas semester
        $allMonthsConfig = [
            ['nama' => 'Juli', 'short' => 'Jul', 'num' => 7, 'year' => $sYear, 'key' => sprintf('%04d-07', $sYear)],
            ['nama' => 'Agustus', 'short' => 'Ags', 'num' => 8, 'year' => $sYear, 'key' => sprintf('%04d-08', $sYear)],
            ['nama' => 'September', 'short' => 'Sep', 'num' => 9, 'year' => $sYear, 'key' => sprintf('%04d-09', $sYear)],
            ['nama' => 'Oktober', 'short' => 'Okt', 'num' => 10, 'year' => $sYear, 'key' => sprintf('%04d-10', $sYear)],
            ['nama' => 'November', 'short' => 'Nov', 'num' => 11, 'year' => $sYear, 'key' => sprintf('%04d-11', $sYear)],
            ['nama' => 'Desember', 'short' => 'Des', 'num' => 12, 'year' => $sYear, 'key' => sprintf('%04d-12', $sYear)],
            ['nama' => 'Januari', 'short' => 'Jan', 'num' => 1, 'year' => $eYear, 'key' => sprintf('%04d-01', $eYear)],
            ['nama' => 'Februari', 'short' => 'Feb', 'num' => 2, 'year' => $eYear, 'key' => sprintf('%04d-02', $eYear)],
            ['nama' => 'Maret', 'short' => 'Mar', 'num' => 3, 'year' => $eYear, 'key' => sprintf('%04d-03', $eYear)],
            ['nama' => 'April', 'short' => 'Apr', 'num' => 4, 'year' => $eYear, 'key' => sprintf('%04d-04', $eYear)],
            ['nama' => 'Mei', 'short' => 'Mei', 'num' => 5, 'year' => $eYear, 'key' => sprintf('%04d-05', $eYear)],
            ['nama' => 'Juni', 'short' => 'Jun', 'num' => 6, 'year' => $eYear, 'key' => sprintf('%04d-06', $eYear)],
        ];
    @endphp

    var availableMonths = @json($allMonthsConfig);

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function renderMonthCheckboxes(containerId, selectedKeys, prefix) {
        var container = document.getElementById(containerId);
        container.innerHTML = '';

        availableMonths.forEach(function(item) {
            var isChecked = selectedKeys && (selectedKeys.indexOf(item.key) !== -1 || selectedKeys.indexOf(item.num) !== -1 || selectedKeys.indexOf(String(item.num)) !== -1);
            var cbId = prefix + '_' + item.key;

            var div = document.createElement('label');
            div.htmlFor = cbId;
            div.className = 'flex items-center gap-2 p-2 rounded-[var(--radius-xs)] border ' + 
                            (isChecked ? 'border-[var(--primary-500)] bg-[var(--primary-50)] text-[var(--primary-800)]' : 'border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)]') + 
                            ' cursor-pointer transition hover:border-[var(--primary-400)]';

            div.innerHTML = '<input type="checkbox" name="bulan_alokasi[]" value="' + item.key + '" id="' + cbId + '" ' + 
                            (isChecked ? 'checked' : '') + 
                            ' class="w-4 h-4 rounded-[var(--radius-xs)] text-[var(--primary-600)] focus:ring-[var(--primary-500)] cursor-pointer" onchange="toggleCheckboxStyle(this)">' +
                            '<div class="min-w-0 text-xs">' +
                                '<span class="font-bold block truncate">' + item.nama + ' ' + item.year + '</span>' +
                            '</div>';

            container.appendChild(div);
        });
    }

    function toggleCheckboxStyle(cb) {
        var label = cb.closest('label');
        if (cb.checked) {
            label.className = 'flex items-center gap-2 p-2 rounded-[var(--radius-xs)] border border-[var(--primary-500)] bg-[var(--primary-50)] text-[var(--primary-800)] cursor-pointer transition hover:border-[var(--primary-400)]';
        } else {
            label.className = 'flex items-center gap-2 p-2 rounded-[var(--radius-xs)] border border-[var(--border)] bg-[var(--bg-page)] text-[var(--text-body)] cursor-pointer transition hover:border-[var(--primary-400)]';
        }
    }

    function openModalVerifikasi(data) {
        document.getElementById('form-verifikasi').action = '/backend/keuangan/verifikasi/' + data.id + '/approve';
        document.getElementById('verif_nama').innerText = data.nama;
        document.getElementById('verif_nim').innerText = '(NIM: ' + data.nim + ')';
        document.getElementById('verif_prodi').innerText = data.prodi;
        document.getElementById('verif_nominal').innerText = formatRupiah(data.nominal);
        document.getElementById('verif_tarif').innerText = formatRupiah(data.tarif_spp) + ' / bln';
        document.getElementById('verif_tanggal').innerText = data.created_at;

        // Auto pre-check jika ada bulan alokasi, atau default ke bulan tagihan jika ada nama bulan di tagihan
        var preSelected = data.bulan_alokasi || [];
        if (preSelected.length === 0 && data.tagihan) {
            var tagihanLower = data.tagihan.toLowerCase();
            availableMonths.forEach(function(m) {
                if (tagihanLower.indexOf(m.nama.toLowerCase()) !== -1 || tagihanLower.indexOf(m.short.toLowerCase()) !== -1) {
                    preSelected.push(m.key);
                }
            });
        }

        renderMonthCheckboxes('verif_checkbox_container', preSelected, 'verif_cb');
        document.getElementById('modal-verifikasi').style.display = 'flex';
    }

    function openModalEditAlokasi(data) {
        document.getElementById('form-edit-alokasi').action = '/backend/keuangan/verifikasi/' + data.id + '/alokasi';
        document.getElementById('edit_alok_nama').innerText = data.nama;
        document.getElementById('edit_alok_nim').innerText = '(NIM: ' + data.nim + ')';
        document.getElementById('edit_alok_nominal').innerText = formatRupiah(data.nominal);
        document.getElementById('edit_alok_catatan').value = data.catatan_alokasi || '';

        renderMonthCheckboxes('edit_alok_checkbox_container', data.bulan_alokasi || [], 'edit_alok_cb');
        document.getElementById('modal-edit-alokasi').style.display = 'flex';
    }

    function filterTable() {
        var query = document.getElementById('searchInput').value.toLowerCase();
        var status = document.getElementById('statusFilter').value.toLowerCase();
        var rows = document.querySelectorAll('#verifikasiTable tbody tr');

        rows.forEach(function(row) {
            var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
            var searchData = (row.getAttribute('data-search') || '').toLowerCase();

            var matchSearch = !query || searchData.indexOf(query) !== -1;
            var matchStatus = !status || rowStatus === status;

            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }
</script>
@endsection
