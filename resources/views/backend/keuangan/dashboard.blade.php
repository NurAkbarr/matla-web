@extends('layouts.keuangan')

@section('title', 'Dashboard Keuangan')

@section('content')
<div class="space-y-6 md:space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/20 border border-[var(--primary-200)]/60 dark:border-[var(--primary-800)]/40 rounded-2xl px-6 py-5 flex items-center gap-4 shadow-xs">
        <div class="w-12 h-12 rounded-full bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/50 border border-[var(--primary-200)]/80 dark:border-[var(--primary-700)]/40 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold text-[var(--text-heading)]">Selamat datang di Sistem Keuangan MATLA University Portal.</p>
            <p class="text-xs text-[var(--text-muted)] mt-0.5">Kelola keuangan kampus dengan mudah, aman, dan terintegrasi.</p>
        </div>
    </div>

    {{-- Stat Cards (4 Columns) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        {{-- Saldo Akun --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card shadow-card-hover flex items-start gap-4 transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-500)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Saldo Akun</p>
                <p class="text-2xl md:text-[26px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">Rp {{ number_format($saldoAkun, 0, ',', '.') }}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5">Total saldo tersedia</p>
            </div>
        </div>

        {{-- Total Tagihan --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card shadow-card-hover flex items-start gap-4 transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-[var(--secondary-100)] dark:bg-[var(--secondary-900)]/40 text-[var(--secondary-600)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Tagihan</p>
                <p class="text-2xl md:text-[26px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5">{{ $jumlahTagihan }} tagihan belum dibayar</p>
            </div>
        </div>

        {{-- Pembayaran Bulan Ini --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card shadow-card-hover flex items-start gap-4 transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-400)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Pembayaran Bulan Ini</p>
                <p class="text-2xl md:text-[26px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5">Terakhir: {{ $tanggalTerakhir }}</p>
            </div>
        </div>

        {{-- Riwayat Transaksi --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 md:p-6 shadow-card shadow-card-hover flex items-start gap-4 transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 text-[var(--primary-500)] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Riwayat Transaksi</p>
                <p class="text-2xl md:text-[26px] font-bold text-[var(--text-heading)] tabular-nums tracking-tight leading-tight">{{ $totalTransaksi }}</p>
                <p class="text-xs text-[var(--text-muted)] mt-1.5">Transaksi tercatat</p>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Ringkasan + Riwayat | Kalender --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        {{-- Left Column: Ringkasan Keuangan + Riwayat --}}
        <div class="lg:col-span-2 space-y-6 md:space-y-8">

            {{-- Ringkasan Keuangan --}}
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-semibold text-[var(--text-heading)]">Ringkasan Keuangan</h3>
                    <select class="text-xs font-medium text-[var(--text-body)] border border-[var(--border)] bg-[var(--surface)] rounded-xl px-3.5 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary-500)]/20 focus:border-[var(--primary-500)] transition-colors cursor-pointer">
                        <option>6 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Total Pemasukan --}}
                    <div class="border border-[var(--primary-100)] dark:border-[var(--primary-900)]/50 bg-[var(--primary-50)]/60 dark:bg-[var(--primary-900)]/20 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/50 text-[var(--primary-500)] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Pemasukan</p>
                            <p class="text-lg font-bold text-[var(--primary-600)] dark:text-[var(--primary-400)] tabular-nums">Rp {{ number_format($saldoAkun, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    {{-- Total Pengeluaran --}}
                    <div class="border border-rose-100 dark:border-rose-900/30 bg-rose-50/50 dark:bg-rose-950/20 rounded-xl p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-rose-100 dark:bg-rose-900/40 text-[var(--danger)] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Pengeluaran</p>
                            <p class="text-lg font-bold text-[var(--danger)] tabular-nums">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Transaksi Terkini --}}
            <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[var(--text-heading)] mb-6">Riwayat Transaksi Terkini</h3>

                    <div class="divide-y divide-[var(--border)]">
                        @forelse($riwayatTerkini as $riwayat)
                            @php
                                $initial = substr($riwayat->user->name ?? '?', 0, 1);
                                $isAmber = (crc32($riwayat->user->name ?? 'unknown') % 2) === 1;
                            @endphp
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                                <div class="flex items-center gap-3 min-w-0 pr-4">
                                    <div class="w-10 h-10 rounded-full {{ $isAmber ? 'bg-[var(--secondary-100)] dark:bg-[var(--secondary-900)]/40 text-[var(--secondary-700)] dark:text-[var(--secondary-400)]' : 'bg-[var(--primary-100)] dark:bg-[var(--primary-900)]/40 text-[var(--primary-600)] dark:text-[var(--primary-300)]' }} flex items-center justify-center text-sm font-bold uppercase shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[var(--text-heading)] truncate">{{ $riwayat->user->name ?? 'Mahasiswa' }}</p>
                                        <p class="text-xs text-[var(--text-muted)] mt-0.5 truncate">{{ $riwayat->tagihan->nama_tagihan ?? 'Pembayaran' }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-bold text-[var(--primary-500)] tabular-nums">+Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</p>
                                    <p class="text-xs text-[var(--text-muted)] mt-0.5">
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
                            <div class="py-8 text-center text-[var(--text-muted)] text-sm">Belum ada riwayat transaksi terkini.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Lihat Semua --}}
                <div class="mt-6 pt-5 border-t border-[var(--border)] flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 border border-[var(--border)] rounded-xl text-xs font-semibold text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/30 hover:text-[var(--primary-500)] transition-colors">
                        Lihat Semua Transaksi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column: Kalender --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 md:p-8 shadow-card flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-[var(--border)]">
                    <svg class="w-5 h-5 text-[var(--primary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-base font-semibold text-[var(--text-heading)]">Kalender</h3>
                </div>

                <div class="text-center mb-6">
                    <p class="text-sm font-bold text-[var(--text-heading)]" id="hijri-date">Memuat tanggal Hijriah...</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1" id="gregorian-date">Memuat tanggal Masehi...</p>
                </div>

                <div>
                    <div class="grid grid-cols-7 gap-1 text-center mb-3">
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sen</div>
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sel</div>
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Rab</div>
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Kam</div>
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Jum</div>
                        <div class="text-[10px] md:text-xs font-semibold text-[var(--text-muted)] uppercase py-1">Sab</div>
                        <div class="text-[10px] md:text-xs font-bold text-[var(--danger)] uppercase py-1">Min</div>
                    </div>
                    <div id="mini-calendar" class="grid grid-cols-7 gap-y-2 text-center text-xs font-medium text-[var(--text-body)]">
                        <!-- JS will fill this -->
                    </div>
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
        html += '<div class="py-1.5 text-[#B5C0BA] dark:text-neutral-600 font-normal">' + (prevMonthDays - i) + '</div>';
    }

    // Current month days
    for (var d = 1; d <= daysInMonth; d++) {
        var dayIndex = (firstDay + d - 1) % 7;
        var isSunday = (dayIndex === 6);

        if (d === today) {
            html += '<div class="py-1.5"><div class="bg-[var(--primary-500)] text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto text-xs font-bold shadow-sm">' + d + '</div></div>';
        } else if (isSunday) {
            html += '<div class="py-1.5 text-[var(--danger)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--surface)] rounded-full w-7 h-7 flex items-center justify-center mx-auto cursor-default transition-colors">' + d + '</div>';
        } else {
            html += '<div class="py-1.5 text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 rounded-full w-7 h-7 flex items-center justify-center mx-auto cursor-default transition-colors">' + d + '</div>';
        }
    }

    // Next month leading days
    var totalCells = firstDay + daysInMonth;
    var remaining = (7 - (totalCells % 7)) % 7;
    for (var n = 1; n <= remaining; n++) {
        html += '<div class="py-1.5 text-[#B5C0BA] dark:text-neutral-600 font-normal">' + n + '</div>';
    }

    calEl.innerHTML = html;
})();
</script>
@endpush
