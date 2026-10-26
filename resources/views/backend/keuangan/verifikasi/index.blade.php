@extends('layouts.keuangan')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">

    {{-- Header Area --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold font-display text-[var(--text-heading)]">Verifikasi pembayaran</h1>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Periksa bukti transfer dan verifikasi pembayaran mahasiswa untuk penerbitan kwitansi.</p>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-[var(--radius-xs)] text-xs font-semibold bg-[var(--primary-50)] text-[var(--primary-700)] border border-[var(--primary-200)] self-start sm:self-auto tabular-nums">
            <span>{{ count($pembayarans) }} total pengajuan</span>
        </div>
    </div>

    {{-- Filter Bar (Tinggi 40px, Radius 4px, Border Line) --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] p-4 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[220px]">
            <input type="text" placeholder="Cari nama, NIM, atau catatan..." class="w-full h-10 pl-9 pr-3.5 border border-[var(--border)] rounded-[var(--radius-xs)] text-xs text-[var(--text-body)] bg-[var(--bg-page)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--primary-700)] focus:ring-1 focus:ring-[var(--primary-700)]">
            <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select class="h-10 border border-[var(--border)] rounded-[var(--radius-xs)] px-3 text-xs text-[var(--text-body)] bg-[var(--bg-page)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua jenis pembayaran</option>
            <option value="spp">SPP bulanan</option>
            <option value="pangkal">Uang pangkal</option>
            <option value="skripsi">Biaya skripsi</option>
        </select>

        <select class="h-10 border border-[var(--border)] rounded-[var(--radius-xs)] px-3 text-xs text-[var(--text-body)] bg-[var(--bg-page)] focus:outline-none focus:border-[var(--primary-700)] cursor-pointer">
            <option value="">Semua status</option>
            <option value="pending">Menunggu verifikasi</option>
            <option value="verified">Terverifikasi</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    {{-- Table Container --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[var(--radius-sm)] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-mobile-cards">
                <thead>
                    <tr class="border-b border-[var(--border)] text-[var(--text-body)] font-semibold text-[12.5px] bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30">
                        <th scope="col" class="py-3.5 pl-5 pr-3 w-[120px]">Waktu</th>
                        <th scope="col" class="py-3.5 px-3 w-[100px]">NIM</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[170px]">Nama mahasiswa</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[150px]">Program studi</th>
                        <th scope="col" class="py-3.5 px-3 w-[120px]">Jenis</th>
                        <th scope="col" class="py-3.5 px-4 min-w-[140px]">Rincian tagihan</th>
                        <th scope="col" class="py-3.5 px-4 text-right w-[120px]">Nominal</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[90px]">Bukti</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[110px]">Kwitansi</th>
                        <th scope="col" class="py-3.5 px-3 text-center w-[120px]">Status</th>
                        <th scope="col" class="py-3.5 pl-3 pr-5 text-center w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)] text-[var(--text-body)]">
                    @forelse($pembayarans as $bayar)
                    @php
                        $subjek = $bayar->user->name ?? '-';
                        $initial = strtoupper(substr($subjek, 0, 1));
                    @endphp
                    <tr class="hover:bg-[var(--primary-50)]/40 transition-colors">
                        {{-- Waktu --}}
                        <td class="py-3.5 pl-5 pr-3 tabular-nums" data-label="Waktu">
                            <span class="block text-[var(--text-heading)] font-semibold">{{ \Carbon\Carbon::parse($bayar->created_at)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-[var(--text-muted)]">{{ \Carbon\Carbon::parse($bayar->created_at)->format('H:i') }} WIB</span>
                        </td>

                        {{-- NIM --}}
                        <td class="py-3.5 px-3 font-semibold text-[var(--text-heading)] tabular-nums" data-label="NIM">
                            {{ $bayar->user->nim ?? '-' }}
                        </td>

                        {{-- Nama Mahasiswa --}}
                        <td class="py-3.5 px-4" data-label="Mahasiswa">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-[var(--primary-100)] text-[var(--primary-700)] border border-[var(--primary-200)] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ $initial }}
                                </div>
                                <span class="font-semibold text-[var(--text-heading)] truncate max-w-[160px]">{{ $subjek }}</span>
                            </div>
                        </td>

                        {{-- Program Studi --}}
                        <td class="py-3.5 px-4 text-[var(--text-muted)] truncate max-w-[160px]" data-label="Program studi">
                            {{ $bayar->user->education['program_studi'] ?? '-' }}
                        </td>

                        {{-- Jenis Pembayaran --}}
                        <td class="py-3.5 px-3" data-label="Jenis">
                            <span class="text-xs font-medium text-[var(--text-heading)]">
                                {{ ucfirst($bayar->jenis_pembayaran) }}
                            </span>
                        </td>

                        {{-- Rincian Tagihan --}}
                        <td class="py-3.5 px-4 text-[var(--text-muted)] truncate max-w-[160px]" data-label="Rincian" title="{{ $bayar->tagihan->nama_tagihan ?? '-' }}">
                            {{ $bayar->tagihan->nama_tagihan ?? '-' }}
                        </td>

                        {{-- Nominal --}}
                        <td class="py-3.5 px-4 text-right font-bold text-[var(--text-heading)] tabular-nums" data-label="Nominal">
                            Rp {{ number_format($bayar->nominal, 0, ',', '.') }}
                        </td>

                        {{-- Bukti TF --}}
                        <td class="py-3.5 px-3 text-center" data-label="Bukti transfer">
                            @if($bayar->bukti_tf)
                                <a href="{{ route('backend.keuangan.verifikasi.bukti', $bayar->id) }}" target="_blank" class="w-8 h-8 inline-flex items-center justify-center text-[var(--primary-600)] hover:bg-[var(--primary-50)] rounded-[var(--radius-xs)] border border-[var(--border)] transition" title="Lihat bukti transfer" aria-label="Lihat bukti transfer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            @else
                                <span class="text-[var(--text-muted)]">-</span>
                            @endif
                        </td>

                        {{-- Kwitansi --}}
                        <td class="py-3.5 px-3 text-center" data-label="Kwitansi">
                            @if($bayar->kwitansi)
                                <a href="{{ route('backend.keuangan.verifikasi.kwitansi-drive', $bayar->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-mono font-semibold text-[var(--primary-600)] hover:underline truncate max-w-[100px]" title="Buka kwitansi Google Drive">
                                    {{ $bayar->kwitansi }}
                                </a>
                            @else
                                <span class="text-[var(--text-muted)] text-[11px]">Belum terbit</span>
                            @endif
                        </td>

                        {{-- Status (Badge Persegi Radius 4px dengan Titik Penanda) --}}
                        <td class="py-3.5 px-3 text-center" data-label="Status">
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

                        {{-- Aksi (40x40 Icon Buttons) --}}
                        <td class="py-3.5 pl-3 pr-5 text-center" data-label="Aksi">
                            @if($bayar->status === 'pending')
                                <div class="flex items-center justify-center gap-1">
                                    <form action="{{ route('backend.keuangan.verifikasi.approve', $bayar->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-[var(--primary-600)] hover:bg-[var(--primary-50)] border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer" title="Verifikasi pembayaran" aria-label="Verifikasi pembayaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('backend.keuangan.verifikasi.reject', $bayar->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-[var(--danger)] hover:bg-rose-50 border border-[var(--border)] rounded-[var(--radius-xs)] transition cursor-pointer" title="Tolak pembayaran" aria-label="Tolak pembayaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @elseif($bayar->status === 'verified')
                                <form action="{{ route('backend.keuangan.verifikasi.reject', $bayar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan verifikasi pembayaran ini? Transaksi di Buku Kas akan otomatis dihapus.');">
                                    @csrf
                                    <button type="submit" class="h-8 px-2.5 text-[11px] font-semibold text-[var(--danger)] bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-[var(--radius-xs)] transition cursor-pointer" title="Batalkan verifikasi">
                                        Batalkan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('backend.keuangan.verifikasi.approve', $bayar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Verifikasi ulang pembayaran ini?');">
                                    @csrf
                                    <button type="submit" class="h-8 px-2.5 text-[11px] font-semibold text-[var(--primary-700)] bg-[var(--primary-50)] hover:bg-[var(--primary-100)] border border-[var(--primary-200)] rounded-[var(--radius-xs)] transition cursor-pointer" title="Verifikasi ulang">
                                        Setujui
                                    </button>
                                </form>
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
@endsection
