@extends('layouts.keuangan')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-800 uppercase tracking-wider">Verifikasi Pembayaran Masuk</p>
            <p class="text-xs text-slate-500 mt-0.5">Periksa dan verifikasi seluruh bukti pembayaran mahasiswa.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-3 flex flex-wrap items-center gap-2">
        <div class="relative flex-1 min-w-[220px]">
            <input type="text" placeholder="Cari nama, NIM, atau catatan..." class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 bg-slate-50 focus:outline-none focus:border-[#00703C] focus:ring-1 focus:ring-[#00703C]/20 focus:bg-white">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Jenis Pembayaran</option>
            <option value="spp">SPP</option>
            <option value="pangkal">Uang Pangkal</option>
            <option value="skripsi">Biaya Skripsi</option>
        </select>

        <select class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 bg-slate-50 focus:outline-none focus:border-[#00703C]">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Verifikasi</option>
            <option value="verified">Terverifikasi</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold tracking-wider text-[10px] uppercase">
                        <th class="py-4 px-5">Waktu (Timestep)</th>
                        <th class="py-4 px-5">NIM</th>
                        <th class="py-4 px-5">Nama Lengkap</th>
                        <th class="py-4 px-5">Prodi</th>
                        <th class="py-4 px-5">Jenis Pembayaran</th>
                        <th class="py-4 px-5">Detail/Bulan SPP</th>
                        <th class="py-4 px-5 text-right">Nominal</th>
                        <th class="py-4 px-5">Catatan</th>
                        <th class="py-4 px-5 text-center">Bukti TF</th>
                        <th class="py-4 px-5 text-center">Kwitansi</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($pembayarans as $bayar)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-5">
                            <span class="block text-slate-800 font-medium text-sm">{{ \Carbon\Carbon::parse($bayar->created_at)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($bayar->created_at)->format('H:i') }} WIB</span>
                        </td>
                        <td class="py-4 px-5 font-bold text-slate-800">{{ $bayar->user->nim ?? '-' }}</td>
                        <td class="py-4 px-5 text-slate-700">{{ $bayar->user->name ?? '-' }}</td>
                        <td class="py-4 px-5 text-slate-500">{{ $bayar->user->education['program_studi'] ?? '-' }}</td>
                        <td class="py-4 px-5">
                            <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                {{ $bayar->jenis_pembayaran }}
                            </span>
                        </td>
                        <td class="py-4 px-5 text-slate-500">{{ $bayar->tagihan->nama_tagihan ?? '-' }}</td>
                        <td class="py-4 px-5 text-right font-bold text-slate-800">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</td>
                        <td class="py-4 px-5 max-w-[150px] truncate text-slate-500" title="{{ $bayar->catatan }}">
                            {{ $bayar->catatan ?: '-' }}
                        </td>
                        <td class="py-4 px-5 text-center">
                            @if($bayar->bukti_tf)
                                <a href="{{ str_starts_with($bayar->bukti_tf, 'http') ? $bayar->bukti_tf : asset('storage/' . $bayar->bukti_tf) }}" target="_blank" class="p-1.5 inline-block text-slate-400 hover:text-[#00703C] hover:bg-emerald-50 rounded-lg transition" title="Lihat Bukti">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            @if($bayar->kwitansi)
                                <a href="{{ route('backend.keuangan.verifikasi.kwitansi-drive', $bayar->id) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-2.5 py-1 rounded-md transition-colors group" title="Buka di Google Drive">
                                    <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 3.8l7.2 14.2H4.8L12 5.8zM11 10v5h2v-5h-2zm0 6v2h2v-2h-2z"/></svg>
                                    {{ $bayar->kwitansi }}
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">Belum terbit</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            @if($bayar->status === 'verified')
                                <span class="font-bold text-xs uppercase tracking-wider text-[#00703C]">Terverifikasi</span>
                            @elseif($bayar->status === 'rejected')
                                <span class="font-bold text-xs uppercase tracking-wider text-rose-600">Ditolak</span>
                            @else
                                <span class="font-bold text-xs uppercase tracking-wider text-amber-600">Pending</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            @if($bayar->status === 'pending')
                                <div class="flex items-center justify-center gap-1">
                                    <form action="{{ route('backend.keuangan.verifikasi.approve', $bayar->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition" title="Verifikasi (Terima)">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('backend.keuangan.verifikasi.reject', $bayar->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Tolak">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Detail">
                                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="py-12 text-center text-slate-400 text-sm">Belum ada data verifikasi pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400 font-medium">Total <span class="font-bold text-slate-600">{{ count($pembayarans) }}</span> data pembayaran</p>
            
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
@endsection
