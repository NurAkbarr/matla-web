@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('backend.mahasiswa.pembayaran.tagihan') }}" class="inline-flex items-center text-sm font-medium text-[#00703C] hover:text-emerald-700">
            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Cek Tagihan
        </a>
    </div>

    <div class="bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] rounded-2xl p-6 sm:p-8 border border-slate-100 relative overflow-hidden">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-[#00703C]/5 rounded-full blur-3xl pointer-events-none"></div>

        <h1 class="text-2xl font-bold text-slate-800 mb-6">Konfirmasi Pembayaran</h1>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8 p-5 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#00703C]/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#00703C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">{{ $tagihan->nama_tagihan }}</h3>
                    <p class="text-slate-500 text-sm mt-0.5">Tagihan Personal/Massal</p>
                </div>
            </div>
            <div class="sm:text-right">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Sisa Pembayaran</p>
                <p class="text-xl font-bold text-rose-600">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
            </div>
        </div>

        <form action="{{ route('backend.mahasiswa.pembayaran.submitKonfirmasi', $tagihan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10" x-data="{ jenis: '{{ old('jenis_pembayaran') }}', sisaTagihan: {{ $tagihan->sisa_tagihan }}, nominal: {{ old('nominal', $tagihan->sisa_tagihan) }} }">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Pembayaran -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="jenis_pembayaran" x-model="jenis" @change="if(jenis === 'lunas') { nominal = sisaTagihan }" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#00703C]/20 focus:border-[#00703C] transition-colors text-slate-600 font-medium">
                        <option value="">Pilih Jenis</option>
                        <option value="lunas">Lunas</option>
                        @if($tagihan->jenis_tagihan == 'cicilan')
                        <option value="cicilan">Cicilan</option>
                        @endif
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nominal Transfer (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="nominal" x-model="nominal" min="1" max="{{ $tagihan->sisa_tagihan }}" :readonly="jenis === 'lunas'" :class="jenis === 'lunas' ? 'bg-slate-200/60 cursor-not-allowed border-transparent text-slate-500' : 'bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#00703C]/20 focus:border-[#00703C] text-slate-600'" required class="w-full px-4 py-3 rounded-xl border border-slate-200 transition-colors font-semibold font-mono" placeholder="Misal: 3000000">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Bukti Transfer -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Transfer <span class="text-rose-500">*</span></label>
                    <input type="file" name="bukti_tf" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#00703C]/20 focus:border-[#00703C] transition-colors text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-[#00703C]/10 file:text-[#00703C] hover:file:bg-[#00703C]/20 cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-500">Format: JPG, PNG, PDF (Maks: 2MB)</p>
                </div>

                <!-- Berkas Pendukung -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Berkas Pendukung <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="berkas_pendukung" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#00703C]/20 focus:border-[#00703C] transition-colors text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-500">Misal: Surat dispensasi atau dokumen lain (Maks: 2MB)</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#00703C]/20 focus:border-[#00703C] transition-colors text-slate-600" placeholder="Tuliskan pesan untuk admin jika ada...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('backend.mahasiswa.pembayaran.tagihan') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-[#00703C] text-white font-bold hover:bg-[#00703C]/90 focus:ring-4 focus:ring-[#00703C]/20 transition-all shadow-lg shadow-[#00703C]/20 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Bukti
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
