@extends('layouts.backend')

@section('title', 'Buat Email Pengumuman')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-in fade-in duration-500" x-data="pengumumanForm()">

    {{-- Breadcrumb & Heading --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs text-[var(--text-muted)] mb-1">
                <a href="{{ route('backend.admin.pengumuman.index') }}" class="hover:text-emerald-600 transition-colors">Email Pengumuman</a>
                <span>/</span>
                <span class="text-[var(--text-heading)] font-medium">Buat Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--text-heading)] tracking-tight">
                Buat Email Pengumuman
            </h1>
        </div>
        <a href="{{ route('backend.admin.pengumuman.index') }}" 
           class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-sm font-semibold text-[var(--text-heading)] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 rounded-2xl">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Terdapat beberapa kesalahan pengisian form:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('backend.admin.pengumuman.store') }}" method="POST" class="space-y-6" id="pengumuman-form">
        @csrf

        {{-- CARD 1: INFORMASI UTAMA & SASARAN --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 shadow-xs space-y-6">
            <h2 class="text-base font-bold text-[var(--text-heading)] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center text-xs font-black">1</span>
                <span>Subjek & Sasaran Penerima</span>
            </h2>

            {{-- Judul / Subjek --}}
            <div>
                <label for="judul" class="block text-xs font-bold text-[var(--text-heading)] uppercase tracking-wider mb-2">
                    Judul Pengumuman (Subjek Email) <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="judul" 
                       name="judul" 
                       x-model="judul"
                       required
                       value="{{ old('judul') }}"
                       placeholder="Contoh: Pemberitahuan Pelaksanaan Ujian Akhir Semester (UAS) Gasal 2026/2027" 
                       class="w-full px-4 py-3 rounded-xl border border-[var(--border)] bg-transparent text-sm text-[var(--text-heading)] focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all font-medium">
                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">
                    Judul ini akan menjadi subjek resmi email yang diterima oleh para civitas akademika.
                </p>
            </div>

            {{-- Target Penerima --}}
            <div>
                <label class="block text-xs font-bold text-[var(--text-heading)] uppercase tracking-wider mb-2">
                    Target Sasaran Penerima <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    
                    {{-- Option: Semua --}}
                    <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                           :class="targetRole === 'semua' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                        <input type="radio" name="target_role" value="semua" class="sr-only" x-model="targetRole">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-[var(--text-heading)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Semua Civitas
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                                {{ $roleCounts['semua'] }} email
                            </span>
                        </div>
                        <p class="text-[11px] text-[var(--text-muted)]">Kirim ke seluruh civitas (Mahasiswa, Dosen, & Admin).</p>
                    </label>

                    {{-- Option: Mahasiswa --}}
                    <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                           :class="targetRole === 'mahasiswa' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                        <input type="radio" name="target_role" value="mahasiswa" class="sr-only" x-model="targetRole">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-[var(--text-heading)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                                Mahasiswa
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                                {{ $roleCounts['mahasiswa'] }} email
                            </span>
                        </div>
                        <p class="text-[11px] text-[var(--text-muted)]">Khusus ditujukan kepada seluruh mahasiswa terdaftar.</p>
                    </label>

                    {{-- Option: Dosen --}}
                    <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                           :class="targetRole === 'dosen' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                        <input type="radio" name="target_role" value="dosen" class="sr-only" x-model="targetRole">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-[var(--text-heading)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Dosen & Pengajar
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300">
                                {{ $roleCounts['dosen'] }} email
                            </span>
                        </div>
                        <p class="text-[11px] text-[var(--text-muted)]">Pengumuman internal khusus para dosen pengajar.</p>
                    </label>

                    {{-- Option: Admin --}}
                    <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                           :class="targetRole === 'admin' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                        <input type="radio" name="target_role" value="admin" class="sr-only" x-model="targetRole">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-[var(--text-heading)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Admin & Staff
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">
                                {{ $roleCounts['admin'] }} email
                            </span>
                        </div>
                        <p class="text-[11px] text-[var(--text-muted)]">Ditujukan ke pengelola sistem dan staff admin.</p>
                    </label>

                    {{-- Option: Custom Email --}}
                    <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all sm:col-span-2 lg:col-span-2"
                           :class="targetRole === 'custom_email' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                        <input type="radio" name="target_role" value="custom_email" class="sr-only" x-model="targetRole">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-[var(--text-heading)] flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email Kustom (Manual)
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300" x-text="customEmailCount + ' email valid'"></span>
                        </div>
                        <p class="text-[11px] text-[var(--text-muted)]">Kirim ke daftar alamat email spesifik yang dimasukkan secara manual.</p>
                    </label>
                </div>

                {{-- Input Khusus jika Memilih Custom Email --}}
                <div x-show="targetRole === 'custom_email'" x-cloak class="mt-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-[var(--border)] space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="custom_emails" class="text-xs font-bold text-[var(--text-heading)]">
                            Daftar Alamat Email Penerima:
                        </label>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            Terdeteksi: <strong x-text="customEmailCount">0</strong> email valid
                        </span>
                    </div>
                    <textarea id="custom_emails" 
                              name="custom_emails" 
                              rows="3" 
                              x-model="customEmails"
                              @input="updateCustomEmailCount()"
                              placeholder="Ketik alamat email di sini, pisahkan dengan koma atau baris baru:&#10;mahasiswa1@gmail.com, dosen@matla.id, info@kampus.ac.id" 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] text-xs text-[var(--text-heading)] font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all"></textarea>
                    <p class="text-[11px] text-[var(--text-muted)]">
                        Format didukung: Pisahkan setiap email dengan koma (,), titik koma (;), atau baris baru (Enter). Sistem otomatis memvalidasi dan membuang duplikat.
                    </p>
                </div>
            </div>
        </div>

        {{-- CARD 2: KONTEN PENGUMUMAN & LIVE PREVIEW --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-[var(--border)] pb-4">
                <h2 class="text-base font-bold text-[var(--text-heading)] flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center text-xs font-black">2</span>
                    <span>Isi Konten Pesan Pengumuman</span>
                </h2>

                {{-- Tabs Switcher: Editor vs Live Preview --}}
                <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-[var(--border)] text-xs font-bold">
                    <button type="button" 
                            @click="activeTab = 'editor'" 
                            class="px-3.5 py-1.5 rounded-lg transition-all"
                            :class="activeTab === 'editor' ? 'bg-[var(--surface)] text-emerald-600 shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-heading)]'">
                        Tulis Konten
                    </button>
                    <button type="button" 
                            @click="activeTab = 'preview'" 
                            class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5"
                            :class="activeTab === 'preview' ? 'bg-[var(--surface)] text-emerald-600 shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-heading)]'">
                        <span>Pratinjau Email</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </button>
                </div>
            </div>

            {{-- TAB 1: EDITOR --}}
            <div x-show="activeTab === 'editor'" class="space-y-3">
                {{-- Quick Formatting Toolbar --}}
                <div class="flex flex-wrap items-center gap-1.5 p-2 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-[var(--border)] text-xs">
                    <button type="button" @click="insertText('**Teks Tebal**')" class="px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-[var(--text-heading)] font-bold" title="Tebal (Bold)">
                        B
                    </button>
                    <button type="button" @click="insertText('*Teks Miring*')" class="px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-[var(--text-heading)] italic font-serif" title="Miring (Italic)">
                        I
                    </button>
                    <button type="button" @click="insertText('### Sub-Judul\n')" class="px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-[var(--text-heading)] font-bold" title="Heading">
                        H3
                    </button>
                    <span class="w-px h-4 bg-slate-300 dark:bg-slate-700 mx-1"></span>
                    <button type="button" @click="insertText('• Poin pengumuman\n• Poin kedua\n')" class="px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-[var(--text-heading)]" title="Daftar Poin">
                        • Bullet List
                    </button>
                    <button type="button" @click="insertText('> Catatan Penting: ')" class="px-2.5 py-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-[var(--text-heading)]" title="Kutipan / Catatan">
                        ❝ Quote
                    </button>
                    <span class="w-px h-4 bg-slate-300 dark:bg-slate-700 mx-1"></span>
                    <button type="button" @click="loadTemplate()" class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-semibold hover:bg-emerald-100" title="Isi dengan contoh draft pengumuman">
                        ✨ Muat Contoh Template
                    </button>
                </div>

                {{-- Textarea Konten --}}
                <textarea id="konten" 
                          name="konten" 
                          rows="12" 
                          x-model="konten"
                          required
                          placeholder="Tuliskan isi pengumuman secara rinci di sini. Anda dapat menggunakan baris baru untuk paragraf, membuat poin-poin penjelasan, dan menyertakan instruksi penting bagi penerima..."
                          class="w-full px-4 py-3.5 rounded-xl border border-[var(--border)] bg-transparent text-sm text-[var(--text-heading)] leading-relaxed focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all font-sans"></textarea>
                
                <div class="flex items-center justify-between text-xs text-[var(--text-muted)]">
                    <span>* Konten pesan akan diformat rapi ke dalam template email resmi MATLA University.</span>
                    <span x-text="konten.length + ' karakter'">0 karakter</span>
                </div>
            </div>

            {{-- TAB 2: LIVE EMAIL PREVIEW --}}
            <div x-show="activeTab === 'preview'" x-cloak class="p-4 sm:p-6 bg-slate-100 dark:bg-slate-900 rounded-2xl border border-[var(--border)]">
                <div class="max-w-xl mx-auto bg-white text-slate-800 rounded-2xl overflow-hidden shadow-lg border border-slate-200">
                    {{-- Preview Email Header --}}
                    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-green-800 text-white p-6 text-center border-b-4 border-amber-500">
                        <div class="text-[10px] tracking-widest uppercase font-bold text-emerald-200">
                            Sistem Informasi Akademik & Portal Kampus
                        </div>
                        <h3 class="text-lg font-black mt-1 text-white">MAHAD TA'LIM LUGHOH AROBIYYAH</h3>
                        <div class="inline-block px-3 py-0.5 rounded-full bg-white/20 text-yellow-200 text-[10px] font-bold mt-1">
                            KAMPUS MATLA
                        </div>
                    </div>

                    {{-- Preview Email Content --}}
                    <div class="p-6 space-y-4">
                        <div class="bg-slate-50 border-l-4 border-emerald-600 p-3.5 rounded-r-xl">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pemberitahuan Resmi Kampus</span>
                            <h4 class="text-base font-extrabold text-slate-900 mt-0.5" x-text="judul || '[Judul Pengumuman]' "></h4>
                        </div>

                        <p class="text-sm text-slate-600">
                            Yth. <strong>Civitas Akademika MATLA</strong>,
                        </p>

                        <div class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed bg-white border border-slate-100 p-4 rounded-xl min-h-[120px]"
                             x-text="konten || '[Isi pesan pengumuman akan ditampilkan di sini saat Anda mengetik pada tab Tulis Konten]' ">
                        </div>

                        <div class="text-center pt-2">
                            <span class="inline-block px-5 py-2.5 rounded-lg bg-emerald-700 text-white font-bold text-xs shadow-md">
                                Kunjungi Portal MATLA &rarr;
                            </span>
                        </div>
                    </div>

                    {{-- Preview Email Footer --}}
                    <div class="bg-slate-50 border-t border-slate-100 p-4 text-center text-[11px] text-slate-500 space-y-1">
                        <div class="font-bold text-emerald-800">KAMPUS MAHAD TA'LIM LUGHOH AROBIYYAH (MATLA)</div>
                        <div>Jl. H. Basir Pondok Kacang Barat, Tangerang Selatan, Banten</div>
                        <div class="text-[10px] text-slate-400 mt-2">&copy; {{ date('Y') }} MATLA University Portal. All rights reserved.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: PILIHAN WAKTU PENGIRIMAN & SUBMIT --}}
        <div class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 shadow-xs space-y-6">
            <h2 class="text-base font-bold text-[var(--text-heading)] flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center text-xs font-black">3</span>
                <span>Pilihan Waktu & Metode Pengiriman</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {{-- Kirim Sekarang --}}
                <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                       :class="actionType === 'kirim_sekarang' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 ring-2 ring-emerald-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                    <input type="radio" name="action_type" value="kirim_sekarang" class="sr-only" x-model="actionType">
                    <div class="flex items-center gap-2 font-bold text-xs text-[var(--text-heading)] mb-1">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span>Kirim Sekarang</span>
                    </div>
                    <p class="text-[11px] text-[var(--text-muted)]">Langsung jalankan antrean email blast secara bertahap saat tombol ditekan.</p>
                </label>

                {{-- Jadwalkan Pengiriman --}}
                <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                       :class="actionType === 'jadwalkan' ? 'border-amber-500 bg-amber-50/50 dark:bg-amber-950/30 ring-2 ring-amber-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                    <input type="radio" name="action_type" value="jadwalkan" class="sr-only" x-model="actionType">
                    <div class="flex items-center gap-2 font-bold text-xs text-[var(--text-heading)] mb-1">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Jadwalkan Nanti</span>
                    </div>
                    <p class="text-[11px] text-[var(--text-muted)]">Tentukan tanggal dan jam spesifik kapan email akan dikirim secara otomatis.</p>
                </label>

                {{-- Simpan Sebagai Draft --}}
                <label class="relative flex flex-col p-4 rounded-xl border cursor-pointer transition-all"
                       :class="actionType === 'draft' ? 'border-slate-500 bg-slate-50 dark:bg-slate-800/60 ring-2 ring-slate-500/20' : 'border-[var(--border)] hover:bg-slate-50 dark:hover:bg-slate-800/40'">
                    <input type="radio" name="action_type" value="draft" class="sr-only" x-model="actionType">
                    <div class="flex items-center gap-2 font-bold text-xs text-[var(--text-heading)] mb-1">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        <span>Simpan Draft</span>
                    </div>
                    <p class="text-[11px] text-[var(--text-muted)]">Simpan tulisan ke sistem tanpa memicu pengiriman email apa pun.</p>
                </label>
            </div>

            {{-- Datetime Picker jika Memilih Jadwalkan --}}
            <div x-show="actionType === 'jadwalkan'" x-cloak class="p-4 rounded-xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/40 space-y-2">
                <label for="scheduled_at" class="block text-xs font-bold text-amber-900 dark:text-amber-300">
                    Pilih Waktu Pengiriman (WIB): <span class="text-rose-500">*</span>
                </label>
                <input type="datetime-local" 
                       id="scheduled_at" 
                       name="scheduled_at" 
                       value="{{ old('scheduled_at') }}"
                       class="px-4 py-2.5 rounded-xl border border-amber-300 dark:border-amber-700 bg-[var(--surface)] text-sm text-[var(--text-heading)] font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/30">
                <p class="text-[11px] text-amber-700 dark:text-amber-400">
                    Sistem Background Cron MATLA akan memeriksa dan mengeksekusi pengiriman otomatis pada menit yang ditentukan.
                </p>
            </div>

            {{-- Submit Bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-[var(--border)]">
                <div class="text-xs text-[var(--text-muted)] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Dilengkapi pembatasan batching (15 email per batch) untuk proteksi anti-spam.</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('backend.admin.pengumuman.index') }}" 
                       class="w-1/2 sm:w-auto px-5 py-2.5 rounded-xl border border-[var(--border)] text-center text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-heading)] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-1/2 sm:w-auto px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-white shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2"
                            :class="actionType === 'kirim_sekarang' ? 'bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800' : (actionType === 'jadwalkan' ? 'bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800' : 'bg-slate-700 hover:bg-slate-800')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="actionType === 'kirim_sekarang'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span x-text="actionType === 'kirim_sekarang' ? 'Kirim Pengumuman Sekarang' : (actionType === 'jadwalkan' ? 'Simpan & Jadwalkan' : 'Simpan Sebagai Draft')">
                            Kirim Pengumuman
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function pengumumanForm() {
    return {
        judul: '{{ old('judul', '') }}',
        konten: `{{ old('konten', '') }}`,
        targetRole: '{{ old('target_role', 'semua') }}',
        customEmails: `{{ old('custom_emails', '') }}`,
        actionType: '{{ old('action_type', 'kirim_sekarang') }}',
        activeTab: 'editor',
        customEmailCount: 0,

        init() {
            this.updateCustomEmailCount();
        },

        insertText(snippet) {
            const el = document.getElementById('konten');
            if (!el) return;
            const start = el.selectionStart || 0;
            const end = el.selectionEnd || 0;
            const val = this.konten;
            this.konten = val.substring(0, start) + snippet + val.substring(end);
            this.$nextTick(() => {
                el.focus();
                el.setSelectionRange(start + snippet.length, start + snippet.length);
            });
        },

        updateCustomEmailCount() {
            if (!this.customEmails) {
                this.customEmailCount = 0;
                return;
            }
            const raw = this.customEmails.split(/[\r\n,;]+/);
            const valid = new Set();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            raw.forEach(e => {
                const trimmed = e.trim().toLowerCase();
                if (trimmed && emailRegex.test(trimmed)) {
                    valid.add(trimmed);
                }
            });
            this.customEmailCount = valid.size;
        },

        loadTemplate() {
            if (this.konten.trim() !== '' && !confirm('Ganti isi pesan saat ini dengan template contoh?')) {
                return;
            }
            this.judul = 'Pemberitahuan: Pelaksanaan Ujian Akhir Semester Gasal 2026/2027';
            this.konten = `Assalamu'alaikum Warahmatullahi Wabarakatuh,

Diberitahukan kepada seluruh Civitas Akademika Mahad Ta'lim Lughoh Arobiyyah (MATLA), sehubungan dengan berakhirnya perkuliahan Semester Gasal Tahun Akademik 2026/2027, berikut kami sampaikan informasi penting terkait agenda evaluasi:

1. Pelaksanaan Ujian Akhir Semester (UAS) akan dimulai pada tanggal 15 Desember 2026.
2. Setiap mahasiswa diwajibkan telah menyelesaikan seluruh kewajiban administrasi akademik dan keuangan sebelum mencetak Kartu Ujian.
3. Jadwal detail mata kuliah dan ruang ujian dapat diakses melalui menu LMS Portal MATLA.

Demikian pemberitahuan ini disampaikan agar dapat dipedomani sebagaimana mestinya. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.

Wassalamu'alaikum Warahmatullahi Wabarakatuh.
Bagian Akademik & Administrasi Kampus MATLA`;
            this.updateCustomEmailCount();
        }
    }
}
</script>
@endsection
