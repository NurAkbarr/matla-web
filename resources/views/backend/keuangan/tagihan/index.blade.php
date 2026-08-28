@extends('layouts.keuangan')

@section('title', 'Manajemen Tagihan')

@section('content')
<div class="space-y-6" x-data="{ tab: 'massal' }">

    {{-- Error Alert --}}
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="text-sm text-rose-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Header Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800 uppercase tracking-wider">Manajemen Tagihan</p>
                <p class="text-xs text-slate-500 mt-0.5">Buat dan kelola tagihan mahasiswa secara massal atau personal.</p>
            </div>
        </div>
        <a href="{{ route('backend.keuangan.tagihan.spreadsheet') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00703C] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Google Sheets
        </a>
    </div>

    {{-- Tab Switcher --}}
    <div class="flex gap-3 overflow-x-auto pb-2">
        <button @click="tab = 'massal'"
                :class="tab === 'massal' ? 'bg-[#00703C] text-white shadow-lg shadow-[#00703C]/20' : 'bg-white text-slate-500 border border-slate-200 hover:border-[#00703C] hover:text-[#00703C]'"
                class="flex-1 py-3.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Tagihan Massal
        </button>
        <button @click="tab = 'personal'"
                :class="tab === 'personal' ? 'bg-slate-800 text-white shadow-lg shadow-slate-800/20' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-800 hover:text-slate-800'"
                class="flex-1 py-3.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Tagihan Personal
        </button>
        <button @click="tab = 'kelola_massal'"
                :class="tab === 'kelola_massal' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'bg-white text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600'"
                class="flex-1 py-3.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Kelola Massal
        </button>
    </div>

    {{-- Form Massal --}}
    <div x-show="tab === 'massal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            {{-- Form Header --}}
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#00703C] flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Buat Tagihan Massal</p>
                        <p class="text-[11px] text-slate-400">Kirim tagihan ke seluruh mahasiswa sekaligus berdasarkan filter prodi dan angkatan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('backend.keuangan.tagihan.storeMass') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                {{-- Row 1: Filter Target --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Program Studi
                        </label>
                        <select name="program_studi" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 transition">
                            <option value="">Semua Program Studi</option>
                            @foreach($programStudis as $prodi)
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Angkatan
                        </label>
                        <select name="angkatan" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 transition">
                            <option value="">Semua Angkatan</option>
                            @foreach($angkatans as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Nama Tagihan --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Nama Tagihan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_tagihan" required placeholder="Contoh: SPP Semester Ganjil 2026" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 placeholder:text-slate-300 transition">
                </div>

                {{-- Row 3: Nominal + Jenis + Jatuh Tempo --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Nominal (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" name="nominal_total" required min="0" placeholder="3500000" class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 placeholder:text-slate-300 transition">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Jenis Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis_tagihan" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 transition">
                            <option value="lunas">Wajib Lunas (Sekali Bayar)</option>
                            <option value="cicilan">Bisa Dicicil</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Jatuh Tempo
                        </label>
                        <input type="date" name="jatuh_tempo" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/10 transition">
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[#00703C] text-white text-sm font-bold rounded-xl shadow-lg shadow-[#00703C]/20 hover:bg-emerald-700 hover:shadow-xl hover:shadow-[#00703C]/30 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate Tagihan Massal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Personal --}}
    <div x-show="tab === 'personal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            {{-- Form Header --}}
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Buat Tagihan Personal</p>
                        <p class="text-[11px] text-slate-400">Kirim tagihan ke satu mahasiswa tertentu.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('backend.keuangan.tagihan.storePersonal') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                {{-- Pilih Mahasiswa --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pilih Mahasiswa <span class="text-rose-500">*</span>
                    </label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}">{{ $mhs->nim }} — {{ $mhs->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Tagihan --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Nama Tagihan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_tagihan" required placeholder="Contoh: Denda Keterlambatan" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 placeholder:text-slate-300 transition">
                </div>

                {{-- Nominal + Jenis + Jatuh Tempo --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Nominal (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" name="nominal_total" required min="0" placeholder="50000" class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 placeholder:text-slate-300 transition">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Jenis Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis_tagihan" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                            <option value="lunas">Wajib Lunas (Sekali Bayar)</option>
                            <option value="cicilan">Bisa Dicicil</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Jatuh Tempo
                        </label>
                        <input type="date" name="jatuh_tempo" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Keterangan Khusus
                    </label>
                    <textarea name="keterangan" rows="2" placeholder="Opsional — catatan tambahan untuk mahasiswa." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 placeholder:text-slate-300 transition resize-none"></textarea>
                </div>

                {{-- Submit --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-800/20 hover:bg-slate-900 hover:shadow-xl hover:shadow-slate-800/30 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Buat Tagihan Personal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kelola Massal --}}
    <div x-show="tab === 'kelola_massal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-data="{ 
        editModalOpen: false, 
        deleteModalOpen: false, 
        editForm: { old_nama_tagihan: '', nama_tagihan: '', nominal_total: '', jenis_tagihan: 'lunas', jatuh_tempo: '' },
        deleteForm: { nama_tagihan: '' }
    }">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Kelola Tagihan Massal</p>
                        <p class="text-[11px] text-slate-400">Grup tagihan berdasarkan nama tagihan.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold tracking-wider text-[10px] uppercase bg-slate-50/50">
                            <th class="py-4 px-5">Nama Tagihan</th>
                            <th class="py-4 px-5 text-center">Jml Mahasiswa</th>
                            <th class="py-4 px-5 text-right">Total Nominal</th>
                            <th class="py-4 px-5">Jatuh Tempo</th>
                            <th class="py-4 px-5 text-center">Jenis</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($massalGroups as $group)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-5 font-bold text-slate-800">{{ $group->nama_tagihan }}</td>
                            <td class="py-4 px-5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-bold text-xs border border-blue-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    {{ $group->total_mhs }} Mhs
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right font-mono font-bold text-slate-700">Rp {{ number_format($group->total_nominal, 0, ',', '.') }}</td>
                            <td class="py-4 px-5">
                                @if($group->jatuh_tempo)
                                    <span class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($group->jatuh_tempo)->format('d M Y') }}</span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($group->jenis_tagihan == 'cicilan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">Cicilan</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">Lunas</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="editForm.old_nama_tagihan = '{{ addslashes($group->nama_tagihan) }}'; editForm.nama_tagihan = '{{ addslashes($group->nama_tagihan) }}'; editForm.nominal_total = '{{ $group->total_nominal / $group->total_mhs }}'; editForm.jenis_tagihan = '{{ $group->jenis_tagihan }}'; editForm.jatuh_tempo = '{{ $group->jatuh_tempo ? \Carbon\Carbon::parse($group->jatuh_tempo)->format('Y-m-d') : '' }}'; editModalOpen = true;" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-100 hover:border-emerald-600" title="Edit Massal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button @click="deleteForm.nama_tagihan = '{{ addslashes($group->nama_tagihan) }}'; deleteModalOpen = true;" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors border border-rose-100 hover:border-rose-600" title="Hapus Massal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="font-medium text-slate-600">Belum ada grup tagihan massal</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Edit Massal --}}
        <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">Edit Tagihan Massal</h3>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('backend.keuangan.tagihan.updateMassal') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="old_nama_tagihan" x-model="editForm.old_nama_tagihan">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Tagihan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_tagihan" x-model="editForm.nama_tagihan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nominal Per Mhs <span class="text-rose-500">*</span></label>
                                <input type="number" name="nominal_total" x-model="editForm.nominal_total" required min="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis <span class="text-rose-500">*</span></label>
                                <select name="jenis_tagihan" x-model="editForm.jenis_tagihan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition">
                                    <option value="lunas">Lunas</option>
                                    <option value="cicilan">Cicilan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" x-model="editForm.jatuh_tempo" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition">
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Hapus Massal --}}
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-sm p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-rose-100 rounded-full">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-center text-slate-900" id="modal-title">Hapus Tagihan Massal</h3>
                    <p class="text-sm text-center text-slate-500 mb-6">Anda yakin ingin menghapus seluruh tagihan <b><span x-text="deleteForm.nama_tagihan"></span></b>? Aksi ini tidak dapat dibatalkan.</p>
                    
                    <form action="{{ route('backend.keuangan.tagihan.destroyMassal') }}" method="POST" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="nama_tagihan" x-model="deleteForm.nama_tagihan">
                        <button type="button" @click="deleteModalOpen = false" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 rounded-xl shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-colors">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Histori Tagihan Terakhir --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden" x-data="{ 
        searchQuery: '',
        editPersonalOpen: false,
        editPersonalForm: { id: '', action_url: '', nama_tagihan: '', nominal_total: '', jenis_tagihan: 'lunas', jatuh_tempo: '', keterangan: '' }
    }">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Riwayat Tagihan Terakhir</p>
                    <p class="text-[11px] text-slate-400">10 tagihan terakhir yang berhasil dibuat.</p>
                </div>
            </div>

            {{-- Filter Pencarian --}}
            <div class="relative w-full sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari mhs atau tagihan..." class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#00703C] focus:ring-2 focus:ring-[#00703C]/20 transition-all">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-bold tracking-wider text-[10px] uppercase">
                        <th class="py-4 px-5">Tgl Dibuat</th>
                        <th class="py-4 px-5">Mahasiswa</th>
                        <th class="py-4 px-5">Angkatan</th>
                        <th class="py-4 px-5">Nama Tagihan</th>
                        <th class="py-4 px-5 text-right">Total</th>
                        <th class="py-4 px-5 text-right">Sisa</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @php
                        $historyColors = ['bg-emerald-100 text-emerald-700', 'bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700', 'bg-cyan-100 text-cyan-700'];
                    @endphp
                    @forelse($recentTagihans as $i => $tagihan)
                    <tr class="hover:bg-slate-50/50 transition-colors" x-show="searchQuery === '' || '{{ strtolower(str_replace("'", "\'", $tagihan->user->name ?? '')) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(str_replace("'", "\'", $tagihan->nama_tagihan)) }}'.includes(searchQuery.toLowerCase())">
                        <td class="py-4 px-5">
                            <span class="block text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($tagihan->created_at)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($tagihan->created_at)->format('H:i') }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $historyColors[$i % count($historyColors)] }} flex items-center justify-center text-[11px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($tagihan->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-bold text-slate-800 text-sm">{{ $tagihan->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $tagihan->user->angkatan ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-5 text-slate-500">{{ $tagihan->nama_tagihan }}</td>
                        <td class="py-4 px-5 text-right font-semibold text-slate-700">Rp {{ number_format($tagihan->nominal_total, 0, ',', '.') }}</td>
                        <td class="py-4 px-5 text-right font-bold text-rose-600">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="py-4 px-5 text-center">
                            @if($tagihan->status === 'lunas')
                                <span class="font-bold text-xs uppercase tracking-wider text-[#00703C]">Lunas</span>
                            @else
                                <span class="font-bold text-xs uppercase tracking-wider text-amber-600">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="editPersonalForm.id = '{{ $tagihan->id }}'; editPersonalForm.action_url = '{{ route('backend.keuangan.tagihan.updatePersonal', $tagihan->id) }}'; editPersonalForm.nama_tagihan = '{{ addslashes($tagihan->nama_tagihan) }}'; editPersonalForm.nominal_total = '{{ $tagihan->nominal_total }}'; editPersonalForm.jenis_tagihan = '{{ $tagihan->jenis_tagihan }}'; editPersonalForm.jatuh_tempo = '{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('Y-m-d') : '' }}'; editPersonalForm.keterangan = '{{ addslashes($tagihan->keterangan ?? '') }}'; editPersonalOpen = true;" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Tagihan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('backend.keuangan.tagihan.destroy', $tagihan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Tagihan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 text-sm" x-show="searchQuery === ''">Belum ada tagihan yang dibuat.</td>
                        <td colspan="8" class="py-12 text-center text-slate-400 text-sm" x-show="searchQuery !== ''" style="display: none;">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Edit Personal --}}
        <div x-show="editPersonalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editPersonalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editPersonalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">Edit Tagihan Personal</h3>
                        <button type="button" @click="editPersonalOpen = false" class="text-slate-400 hover:text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <form :action="editPersonalForm.action_url" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Tagihan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_tagihan" x-model="editPersonalForm.nama_tagihan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nominal (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" name="nominal_total" x-model="editPersonalForm.nominal_total" required min="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis <span class="text-rose-500">*</span></label>
                                <select name="jenis_tagihan" x-model="editPersonalForm.jenis_tagihan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                                    <option value="lunas">Lunas</option>
                                    <option value="cicilan">Cicilan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" x-model="editPersonalForm.jatuh_tempo" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan</label>
                            <textarea name="keterangan" x-model="editPersonalForm.keterangan" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-slate-800 focus:ring-2 focus:ring-slate-800/10 transition resize-none"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="editPersonalOpen = false" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-xl shadow-lg shadow-slate-800/20 hover:bg-slate-900 transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400 font-medium">Menampilkan <span class="font-bold text-slate-600">{{ count($recentTagihans) }}</span> tagihan terakhir</p>
            <div class="flex items-center gap-2">
                <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition text-xs">&lt;</button>
                <button class="w-8 h-8 rounded-lg bg-[#00703C] text-white flex items-center justify-center text-xs font-bold shadow-sm">1</button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-slate-50 transition text-xs">&gt;</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
