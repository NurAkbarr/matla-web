@extends('layouts.keuangan')

@section('title', 'Dashboard Keuangan')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-800">Selamat datang di Sistem Keuangan MATLA University Portal.</p>
            <p class="text-xs text-slate-500 mt-0.5">Kelola keuangan kampus dengan mudah, aman, dan terintegrasi.</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Saldo Akun --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Saldo Akun</p>
                <p class="text-xl font-extrabold text-slate-900 tracking-tight">Rp {{ number_format($saldoAkun, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Total saldo tersedia</p>
            </div>
        </div>

        {{-- Total Tagihan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                <p class="text-xl font-extrabold text-slate-900 tracking-tight">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 mt-1">{{ $jumlahTagihan }} tagihan belum dibayar</p>
            </div>
        </div>

        {{-- Pembayaran Bulan Ini --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pembayaran Bulan Ini</p>
                <p class="text-xl font-extrabold text-slate-900 tracking-tight">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Terakhir: {{ $tanggalTerakhir }}</p>
            </div>
        </div>

        {{-- Riwayat Transaksi --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Riwayat Transaksi</p>
                <p class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $totalTransaksi }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Transaksi tercatat</p>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Ringkasan + Riwayat | Kalender --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Ringkasan Keuangan + Riwayat --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Ringkasan Keuangan --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Ringkasan Keuangan</h3>
                    <select class="text-xs text-slate-500 border border-slate-200 bg-slate-50 rounded-lg px-3 py-1.5 focus:outline-none focus:border-slate-400">
                        <option>6 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border border-slate-100 bg-slate-50/50 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
                            <p class="text-lg font-extrabold text-slate-900">Rp {{ number_format($saldoAkun, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="border border-slate-100 bg-slate-50/50 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                            <p class="text-lg font-extrabold text-slate-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Transaksi Terkini --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5">Riwayat Transaksi Terkini</h3>

                <div class="divide-y divide-slate-100">
                    @forelse($riwayatTerkini as $riwayat)
                        @php
                            $initial = substr($riwayat->user->name ?? '?', 0, 1);
                            $colors = ['emerald', 'amber', 'blue', 'purple', 'rose'];
                            $colorIndex = crc32($riwayat->user->name ?? 'unknown') % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-{{ $color }}-100 flex items-center justify-center text-sm font-bold text-{{ $color }}-700 uppercase">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $riwayat->user->name ?? 'Mahasiswa' }}</p>
                                    <p class="text-xs text-slate-400">{{ $riwayat->tagihan->nama_tagihan ?? 'Pembayaran' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-[#00703C]">+Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400">
                                    @if($riwayat->updated_at->isToday())
                                        Hari ini, {{ $riwayat->updated_at->format('H:i') }}
                                    @elseif($riwayat->updated_at->isYesterday())
                                        Kemarin, {{ $riwayat->updated_at->format('H:i') }}
                                    @else
                                        {{ $riwayat->updated_at->format('d M, H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-sm">Belum ada riwayat transaksi terkini.</div>
                    @endforelse
                </div>

                {{-- Lihat Semua --}}
                <div class="mt-5 pt-4 border-t border-slate-100 flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition">
                        Lihat Semua Transaksi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column: Kalender --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col">
            <div class="flex items-center gap-2 mb-5">
                <svg class="w-5 h-5 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Kalender</h3>
            </div>

            <div class="text-center mb-6">
                <p class="text-sm font-bold text-slate-800" id="hijri-date">13 Rabilawal 1448 H H</p>
                <p class="text-xs text-slate-400 mt-0.5" id="gregorian-date">Selasa, 25 Agustus 2026</p>
            </div>

            <div class="flex-1">
                <div class="grid grid-cols-7 gap-1 text-center mb-3">
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Sen</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Sel</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Rab</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Kam</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Jum</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Sab</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase py-1">Min</div>
                </div>
                <div id="mini-calendar" class="grid grid-cols-7 gap-y-2 text-center text-xs font-medium text-slate-700">
                    <!-- JS will fill this -->
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var calEl = document.getElementById('mini-calendar');
    if (!calEl) return;

    var now = new Date();
    
    // Update headers dynamically
    var gregorianEl = document.getElementById('gregorian-date');
    if (gregorianEl) {
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        gregorianEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    
    var hijriEl = document.getElementById('hijri-date');
    if (hijriEl) {
        try {
            // Koreksi kalender Hijriah (mundur 1 hari)
            var hijriDate = new Date(now);
            hijriDate.setDate(hijriDate.getDate() - 1);

            var hijriOptions = { day: 'numeric', month: 'long', year: 'numeric' };
            var hijriFormatter = new Intl.DateTimeFormat('id-ID-u-ca-islamic', hijriOptions);
            var formattedHijri = hijriFormatter.format(hijriDate);
            
            // Hapus akhiran 'H' atau 'AH' bawaan jika ada agar tidak double
            formattedHijri = formattedHijri.replace(/\s*AH/i, '').replace(/\s*H$/i, '');
            hijriEl.innerText = formattedHijri + ' H';
        } catch (e) {
            hijriEl.innerText = '12 Rabiulawal 1448 H';
        }
    }

    var month = now.getMonth();
    var year = now.getFullYear();
    var today = now.getDate();
    var firstDay = new Date(year, month, 1).getDay();
    // Adjust: JS Sunday=0, we want Monday=0
    firstDay = (firstDay + 6) % 7;
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var prevMonthDays = new Date(year, month, 0).getDate();

    var html = '';

    // Previous month trailing days
    for (var i = firstDay - 1; i >= 0; i--) {
        html += '<div class="py-1.5 text-slate-300">' + (prevMonthDays - i) + '</div>';
    }

    // Current month days
    for (var d = 1; d <= daysInMonth; d++) {
        if (d === today) {
            html += '<div class="py-1.5"><div class="bg-[#00703C] text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto text-xs font-bold shadow-md shadow-emerald-200">' + d + '</div></div>';
        } else {
            html += '<div class="py-1.5 text-slate-700 hover:text-[#00703C] cursor-default transition">' + d + '</div>';
        }
    }

    // Next month leading days
    var totalCells = firstDay + daysInMonth;
    var remaining = (7 - (totalCells % 7)) % 7;
    for (var n = 1; n <= remaining; n++) {
        html += '<div class="py-1.5 text-slate-300">' + n + '</div>';
    }

    calEl.innerHTML = html;
})();
</script>
@endpush
