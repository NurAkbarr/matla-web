@extends('layouts.backend')

@section('title', 'Detail Pengumuman - ' . $pengumuman->judul)

@section('content')
<div class="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-500" x-data="{ activeTab: 'preview' }">

    {{-- Breadcrumb & Top Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-[var(--text-muted)] mb-1">
                <a href="{{ route('backend.admin.pengumuman.index') }}" class="hover:text-emerald-600 transition-colors">Email Pengumuman</a>
                <span>/</span>
                <span class="text-[var(--text-heading)] font-medium">Detail & Log #{{ $pengumuman->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--text-heading)] tracking-tight">
                {{ $pengumuman->judul }}
            </h1>
            <p class="text-xs text-[var(--text-muted)] mt-1">
                Dibuat oleh <strong class="text-[var(--text-heading)]">{{ $pengumuman->creator->name ?? 'Admin' }}</strong> pada {{ $pengumuman->created_at->format('d M Y, H:i') }} WIB
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('backend.admin.pengumuman.index') }}" 
               class="px-4 py-2 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-xs font-semibold text-[var(--text-heading)] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                &larr; Kembali
            </a>

            @if(in_array($pengumuman->status, ['draft', 'scheduled', 'failed', 'sending']))
            <form action="{{ route('backend.admin.pengumuman.send-now', $pengumuman) }}" method="POST" class="inline" onsubmit="return confirm('Proses pengiriman email pengumuman ini sekarang juga ke seluruh target?')">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>{{ $pengumuman->status === 'sending' ? 'Proses Antrean Sekarang' : 'Kirim Sekarang' }}</span>
                </button>
            </form>
            @endif

            @if($pengumuman->status !== 'sending')
            <form action="{{ route('backend.admin.pengumuman.destroy', $pengumuman) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengumuman ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors"
                        title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Status Banner & Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card Status --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider block mb-1">Status Pengiriman</span>
            <div class="flex items-center gap-2 mt-2">
                @if($pengumuman->status === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                        Terkirim Selesai
                    </span>
                @elseif($pengumuman->status === 'scheduled')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                        Dijadwalkan
                    </span>
                @elseif($pengumuman->status === 'sending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-ping"></span>
                        Sedang Mengirim...
                    </span>
                @elseif($pengumuman->status === 'failed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                        <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                        Gagal Mengirim
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span>
                        Draft
                    </span>
                @endif
            </div>
            <div class="text-[11px] text-[var(--text-muted)] mt-2">
                @if($pengumuman->sent_at)
                    Selesai: {{ $pengumuman->sent_at->format('d M Y, H:i') }} WIB
                @elseif($pengumuman->scheduled_at)
                    Jadwal: {{ $pengumuman->scheduled_at->format('d M Y, H:i') }} WIB
                @elseif($pengumuman->status === 'sending')
                    <span class="text-blue-500 font-semibold animate-pulse">Sedang diproses dalam antrean...</span>
                @else
                    Belum diproses
                @endif
            </div>
        </div>

        {{-- Card Target --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider block mb-1">Target Sasaran</span>
            <div class="text-base font-bold text-[var(--text-heading)] mt-1">
                {{ $pengumuman->target_role_label }}
            </div>
            <div class="text-[11px] text-[var(--text-muted)] mt-1">
                Total Estimasi: <strong class="text-[var(--text-heading)]">{{ $pengumuman->total_penerima }}</strong> penerima
            </div>
        </div>

        {{-- Card Berhasil --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block mb-1">Berhasil Terkirim</span>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                {{ $pengumuman->total_berhasil }}
                <span class="text-xs font-medium text-[var(--text-muted)]">/ {{ $pengumuman->total_penerima }}</span>
            </div>
            @php
                $pct = $pengumuman->total_penerima > 0 ? round(($pengumuman->total_berhasil / $pengumuman->total_penerima) * 100) : 0;
            @endphp
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-2.5 overflow-hidden">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>

        {{-- Card Gagal --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider block mb-1">Gagal Terkirim</span>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">
                {{ $pengumuman->total_gagal }}
            </div>
            <div class="text-[11px] text-[var(--text-muted)] mt-1">
                @if($pengumuman->total_gagal > 0)
                    Periksa tab Log untuk detail kesalahan
                @else
                    Tidak ada kendala pengiriman
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs Container --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl shadow-xs overflow-hidden">
        {{-- Tab Headers --}}
        <div class="flex items-center border-b border-[var(--border)] px-6 pt-4 gap-4 bg-slate-50/50 dark:bg-slate-800/20">
            <button type="button" 
                    @click="activeTab = 'preview'" 
                    class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                    :class="activeTab === 'preview' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-[var(--text-muted)] hover:text-[var(--text-heading)]'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Pratinjau Email Resmi</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'logs'" 
                    class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                    :class="activeTab === 'logs' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-[var(--text-muted)] hover:text-[var(--text-heading)]'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>Log Riwayat Pengiriman</span>
                @if(is_array($pengumuman->log_pengiriman) && count($pengumuman->log_pengiriman) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 dark:bg-slate-700 text-[var(--text-heading)]">
                        {{ count($pengumuman->log_pengiriman) }}
                    </span>
                @endif
            </button>
        </div>

        {{-- TAB 1: EMAIL PREVIEW --}}
        <div x-show="activeTab === 'preview'" class="p-6 sm:p-10 bg-slate-100/70 dark:bg-slate-900/60 flex justify-center">
            <div class="max-w-2xl w-full bg-white text-slate-800 rounded-2xl overflow-hidden shadow-lg border border-slate-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-green-800 text-white p-7 text-center border-b-4 border-amber-500">
                    <div class="text-[10px] tracking-widest uppercase font-bold text-emerald-200 mb-1">
                        Sistem Informasi Akademik & Portal Kampus
                    </div>
                    <h3 class="text-xl font-black text-white m-0">MAHAD TA'LIM LUGHOH AROBIYYAH</h3>
                    <div class="inline-block px-3.5 py-0.5 rounded-full bg-white/20 text-yellow-200 text-[10px] font-bold mt-2">
                        KAMPUS MATLA
                    </div>
                </div>

                <!-- Content Area -->
                <div class="p-7 space-y-5">
                    <div class="bg-slate-50 border-l-4 border-emerald-600 p-4 rounded-r-xl">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pemberitahuan Resmi Kampus</span>
                        <h4 class="text-lg font-extrabold text-slate-900 mt-1 leading-snug">{{ $pengumuman->judul }}</h4>
                    </div>

                    <p class="text-sm text-slate-700">
                        Yth. <strong>{{ $pengumuman->greeting_label }}</strong>,
                    </p>

                    <div class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed bg-white border border-slate-100 p-5 rounded-xl shadow-xs">
                        {!! nl2br(e($pengumuman->konten)) !!}
                    </div>

                    <div class="text-center pt-2">
                        <a href="https://matla.id/" target="_blank" class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-700 to-green-700 hover:from-emerald-800 hover:to-green-800 text-white font-bold text-xs shadow-md transition-all">
                            Kunjungi Portal MATLA &rarr;
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 border-t border-slate-100 p-5 text-center text-xs text-slate-500 space-y-1">
                    <div class="font-bold text-emerald-800">KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)</div>
                    <div class="text-[11px]">Jl. H. Basir Pondok Kacang Barat, Tangerang Selatan, Banten</div>
                    <div class="text-[11px] text-slate-400">WhatsApp: 081273510994 &bull; Email: matlaislamicuniversity@gmail.com</div>
                    <div class="text-[10px] text-slate-400 pt-2 border-t border-slate-200 mt-3">&copy; {{ date('Y') }} MATLA University Portal. All rights reserved.</div>
                </div>
            </div>
        </div>

        {{-- TAB 2: DELIVERY LOGS --}}
        <div x-show="activeTab === 'logs'" x-cloak class="p-6">
            @if(is_array($pengumuman->log_pengiriman) && count($pengumuman->log_pengiriman) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-800/40 border-b border-[var(--border)]">
                            <tr>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider">Email Penerima</th>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider">Nama / Akun</th>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider text-center">Status</th>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider">Waktu Eksekusi</th>
                                <th class="px-4 py-3 font-bold text-[var(--text-muted)] uppercase tracking-wider">Keterangan / Pesan Error</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach($pengumuman->log_pengiriman as $index => $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-mono font-medium text-[var(--text-heading)]">
                                    {{ $log['email'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-[var(--text-muted)]">
                                    {{ $log['name'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if(($log['status'] ?? '') === 'success')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            ✓ Berhasil
                                        </span>
                                    @elseif(($log['status'] ?? '') === 'failed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                            ✕ Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ ucfirst($log['status'] ?? 'Info') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                                    {{ $log['sent_at'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-[var(--text-muted)]">
                                    @if(!empty($log['error']))
                                        <span class="text-rose-600 font-mono text-[11px]">{{ $log['error'] }}</span>
                                    @elseif(!empty($log['message']))
                                        <span>{{ $log['message'] }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center max-w-sm mx-auto">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-sm text-[var(--text-heading)] mb-1">Belum Ada Catatan Log</h4>
                    <p class="text-xs text-[var(--text-muted)]">
                        Log pengiriman email akan terisi otomatis saat pengumuman mulai diproses oleh queue pengiriman.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($pengumuman->status === 'sending')
<script>
    // Auto refresh halaman setiap 4 detik selama status sedang mengirim
    setTimeout(function() {
        window.location.reload();
    }, 4000);
</script>
@endif
@endsection

