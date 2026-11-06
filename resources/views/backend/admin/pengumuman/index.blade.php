@extends('layouts.backend')

@section('title', 'Email Pengumuman')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">

    {{-- Top Heading & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--text-heading)] tracking-tight">
                Email Pengumuman
            </h1>
            <p class="text-sm text-[var(--text-muted)] mt-1">
                Kirim informasi, berita, dan pengumuman resmi via email secara massal atau terjadwal ke civitas MATLA.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('backend.admin.pengumuman.create') }}" 
               class="inline-flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Pengumuman Baru</span>
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('error'))
    <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
        {{-- Total --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Total Blast</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline">
                <span class="text-2xl sm:text-3xl font-extrabold text-[var(--text-heading)]">{{ $metrics['total'] }}</span>
            </div>
        </div>

        {{-- Selesai Terkirim --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Selesai</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $metrics['completed'] }}</span>
            </div>
        </div>

        {{-- Terjadwal --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Dijadwalkan</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $metrics['scheduled'] }}</span>
            </div>
        </div>

        {{-- Draft --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Draft</span>
                <span class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline">
                <span class="text-2xl sm:text-3xl font-extrabold text-[var(--text-heading)]">{{ $metrics['draft'] }}</span>
            </div>
        </div>

        {{-- Total Email Berhasil Terkirim --}}
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-emerald-600 to-green-700 text-white rounded-2xl p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-100 uppercase tracking-wider">Email Terkirim</span>
                <span class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline">
                <span class="text-2xl sm:text-3xl font-black text-white">{{ number_format($metrics['total_sent'], 0, ',', '.') }}</span>
                <span class="ml-1 text-xs text-emerald-100">pesan</span>
            </div>
        </div>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('backend.admin.pengumuman.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            {{-- Search Input --}}
            <div class="sm:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari judul pengumuman atau konten..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[var(--border)] bg-transparent text-sm text-[var(--text-heading)] focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
            </div>

            {{-- Filter Status --}}
            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-sm text-[var(--text-heading)] focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                    <option value="all">Semua Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai Terkirim</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="sending" {{ request('status') === 'sending' ? 'selected' : '' }}>Sedang Mengirim</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            {{-- Filter Target --}}
            <div class="sm:col-span-3">
                <select name="target" class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-sm text-[var(--text-heading)] focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                    <option value="all">Semua Target</option>
                    <option value="semua" {{ request('target') === 'semua' ? 'selected' : '' }}>Semua Pengguna</option>
                    <option value="mahasiswa" {{ request('target') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ request('target') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="admin" {{ request('target') === 'admin' ? 'selected' : '' }}>Admin & Staff</option>
                    <option value="custom_email" {{ request('target') === 'custom_email' ? 'selected' : '' }}>Email Kustom</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="sm:col-span-1 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center" title="Terapkan Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Main Data Table --}}
    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/70 dark:bg-slate-800/40 border-b border-[var(--border)]">
                    <tr>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Judul & Ringkasan</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Target Penerima</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Jadwal / Kirim</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider text-center">Statistik</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($pengumumans as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        {{-- Judul & Konten Snippet --}}
                        <td class="px-5 py-4 max-w-xs sm:max-w-md">
                            <a href="{{ route('backend.admin.pengumuman.show', $item) }}" class="font-bold text-[var(--text-heading)] hover:text-emerald-600 transition-colors line-clamp-1">
                                {{ $item->judul }}
                            </a>
                            <p class="text-xs text-[var(--text-muted)] line-clamp-1 mt-0.5">
                                {{ Str::limit(strip_tags($item->konten), 90) }}
                            </p>
                            <span class="inline-block text-[11px] text-[var(--text-muted)] mt-1">
                                Oleh: <span class="font-medium text-[var(--text-heading)]">{{ $item->creator->name ?? 'Admin' }}</span> &bull; {{ $item->created_at->diffForHumans() }}
                            </span>
                        </td>

                        {{-- Target Role --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->target_role === 'semua')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50">
                                    Semua Civitas
                                </span>
                            @elseif($item->target_role === 'mahasiswa')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                    Mahasiswa
                                </span>
                            @elseif($item->target_role === 'dosen')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                    Dosen
                                </span>
                            @elseif($item->target_role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                    Admin & Staff
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    Email Kustom
                                </span>
                            @endif
                        </td>

                        {{-- Tanggal Jadwal / Kirim --}}
                        <td class="px-5 py-4 whitespace-nowrap text-xs text-[var(--text-muted)]">
                            @if($item->sent_at)
                                <div class="font-semibold text-emerald-600 dark:text-emerald-400">
                                    {{ $item->sent_at->format('d M Y, H:i') }} WIB
                                </div>
                                <span class="text-[10px] text-[var(--text-muted)]">Selesai terkirim</span>
                            @elseif($item->scheduled_at)
                                <div class="font-semibold text-amber-600 dark:text-amber-400">
                                    {{ $item->scheduled_at->format('d M Y, H:i') }} WIB
                                </div>
                                <span class="text-[10px] text-[var(--text-muted)]">Jadwal pengiriman</span>
                            @else
                                <span class="text-slate-400 italic">Belum dikirim</span>
                            @endif
                        </td>

                        {{-- Statistik Pengiriman --}}
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <div class="inline-flex items-center gap-1.5 text-xs font-medium">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[var(--text-heading)]" title="Total Target">
                                    {{ $item->total_penerima }}
                                </span>
                                @if($item->total_berhasil > 0)
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-semibold" title="Berhasil">
                                        ✓ {{ $item->total_berhasil }}
                                    </span>
                                @endif
                                @if($item->total_gagal > 0)
                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 font-semibold" title="Gagal">
                                        ✕ {{ $item->total_gagal }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @if($item->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Selesai
                                </span>
                            @elseif($item->status === 'scheduled')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    Terjadwal
                                </span>
                            @elseif($item->status === 'sending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-ping"></span>
                                    Mengirim...
                                </span>
                            @elseif($item->status === 'failed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                    Gagal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                    Draft
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                            {{-- View / Detail --}}
                            <a href="{{ route('backend.admin.pengumuman.show', $item) }}" 
                               class="inline-flex items-center p-2 rounded-xl text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors"
                               title="Lihat Detail & Log Pengiriman">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            {{-- Kirim Sekarang (jika draft, scheduled, failed) --}}
                            @if(in_array($item->status, ['draft', 'scheduled', 'failed']))
                            <form action="{{ route('backend.admin.pengumuman.send-now', $item) }}" method="POST" class="inline" onsubmit="return confirm('Kirim email pengumuman ini sekarang juga ke seluruh target penerima?')">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center p-2 rounded-xl text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors"
                                        title="Kirim Sekarang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            {{-- Hapus --}}
                            @if($item->status !== 'sending')
                            <form action="{{ route('backend.admin.pengumuman.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center p-2 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                                        title="Hapus Pengumuman">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-[var(--text-heading)] text-base mb-1">Belum Ada Pengumuman</h3>
                                <p class="text-xs text-[var(--text-muted)] mb-5">
                                    Buat dan sebarkan email pengumuman baru untuk civitas akademika kampus MATLA.
                                </p>
                                <a href="{{ route('backend.admin.pengumuman.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                    + Buat Pengumuman Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pengumumans->hasPages())
        <div class="px-5 py-4 border-t border-[var(--border)] bg-slate-50/50 dark:bg-slate-800/20">
            {{ $pengumumans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
