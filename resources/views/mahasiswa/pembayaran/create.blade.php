@extends('layouts.app')

@section('title', 'Submit Pembayaran - Mahasiswa')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20 pt-8">
    <div class="container mx-auto px-4 lg:px-12 max-w-4xl">
        {{-- Breadcrumb / Back --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('backend.mahasiswa.pembayaran.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Menu Pembayaran
            </a>
        </div>

        <div class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight" style="font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif;">Submit Pembayaran</h1>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name }}" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:border-emerald-500 transition-colors cursor-not-allowed">
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Program Studi</label>
                        <input type="text" value="{{ Auth::user()->education['program_studi'] ?? 'N/A' }}" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:border-emerald-500 transition-colors cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Semester -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Semester</label>
                        <div class="relative">
                            <select name="semester" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 appearance-none transition-colors">
                                <option value="" disabled selected>Pilih Semester...</option>
                                @php $currentSemester = Auth::user()->semester ?: 1; @endphp
                                @for($i = 1; $i <= $currentSemester; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Pembayaran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Jenis Pembayaran</label>
                        <div class="relative">
                            <select name="jenis_pembayaran" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 appearance-none transition-colors">
                                <option value="" disabled selected>Pilih Jenis Pembayaran...</option>
                                <option value="Pendaftaran">Pendaftaran</option>
                                <option value="Almamater">Almamater</option>
                                <option value="Biaya Pengembangan">Biaya Pengembangan</option>
                                <option value="Biaya Konversi">Biaya Konversi</option>
                                <option value="SPP">SPP</option>
                                <option value="Skripsi">Skripsi</option>
                                <option value="Wisuda">Wisuda</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nominal Transfer -->
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Nominal Transfer (Rp)</label>
                    <input type="number" name="nominal" required placeholder="Contoh: 1000000" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors placeholder:font-normal placeholder:text-gray-400">
                </div>

                <!-- Upload Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Bukti Transfer (Wajib) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Upload Bukti Transfer</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-emerald-200 rounded-2xl cursor-pointer bg-emerald-50/50 hover:bg-emerald-50 hover:border-emerald-300 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 text-emerald-400 mb-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path></svg>
                                <p class="text-sm font-semibold text-emerald-600">Klik untuk foto struk <span class="text-emerald-500">(Wajib)</span></p>
                            </div>
                            <input type="file" name="bukti_transfer" class="hidden" required accept="image/*,.pdf" />
                        </label>
                    </div>

                    <!-- Berkas Pendukung (Opsional) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Berkas Pendukung (Opsional)</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50/50 hover:bg-gray-50 hover:border-gray-300 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 text-gray-400 mb-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path><path d="M12 2v5h5"></path></svg>
                                <p class="text-sm font-semibold text-gray-500">Klik untuk file tambahan</p>
                            </div>
                            <input type="file" name="berkas_pendukung" class="hidden" accept="image/*,.pdf" />
                        </label>
                    </div>
                </div>

                <!-- Catatan (Opsional) -->
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Tuliskan keterangan tambahan jika diperlukan..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors placeholder:text-gray-400 resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" class="w-full py-4 px-6 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-lg flex items-center justify-center gap-2 transition-all shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                        SUBMIT PEMBAYARAN
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
