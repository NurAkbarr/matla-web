@extends('layouts.app')
 
@section('title', 'Buat Pengajuan Cuti - Matla Islamic University')
 
@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <style>
        body { font-family: 'Montserrat', sans-serif !important; }
    </style>
 
    <div class="container mx-auto px-4 lg:px-12 pt-8 max-w-4xl">
        {{-- Breadcrumb / Back --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('backend.mahasiswa.cuti.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Cuti
            </a>
        </div>
 
        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-gray-100 mb-8">
            <div class="mb-8 border-b border-gray-100 pb-8">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Form Pengajuan Cuti</h1>
                <p class="text-sm font-medium text-gray-500 mt-2 max-w-2xl leading-relaxed">
                    Silakan isi form di bawah ini dengan lengkap dan benar untuk mengajukan cuti akademik. Data diri Anda akan otomatis disertakan.
                </p>
            </div>

            @if($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-rose-800 text-sm">Terdapat kesalahan pada input Anda:</span>
                    </div>
                    <ul class="list-disc pl-5 text-sm text-rose-600 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
 
            <form action="{{ route('backend.mahasiswa.cuti.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Auto-filled Profile Data (Readonly) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-8">
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest border-b border-gray-200 pb-3 mb-4">Informasi Pemohon</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Mahasiswa</label>
                        <input type="text" value="{{ $user->name }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 font-medium text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIM</label>
                        <input type="text" value="{{ $user->nim ?? 'Belum Diatur' }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 font-medium text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Program Studi</label>
                        <input type="text" value="{{ $user->education['program_studi'] ?? 'Belum Diatur' }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 font-medium text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tahun Akademik</label>
                        <input type="text" value="{{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 font-medium text-sm cursor-not-allowed" readonly>
                    </div>
                </div>
 
                <div>
                    <label for="semester_diajukan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Semester yang Diajukan <span class="text-red-500">*</span></label>
                    <input type="number" id="semester_diajukan" name="semester_diajukan" value="{{ old('semester_diajukan') }}" min="{{ $user->semester ?? 1 }}" max="14" placeholder="Contoh: 3" class="w-full bg-white border border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl px-4 py-3 text-sm font-medium transition-all" required>
                </div>
 
                <div>
                    <label for="alasan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alasan Cuti <span class="text-red-500">*</span></label>
                    <textarea id="alasan" name="alasan" rows="4" placeholder="Jelaskan alasan pengajuan cuti secara detail..." class="w-full bg-white border border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl px-4 py-3 text-sm font-medium transition-all resize-y" required minlength="10">{{ old('alasan') }}</textarea>
                    <p class="text-[11px] text-gray-400 font-medium mt-2">Minimal 10 karakter.</p>
                </div>

                <div class="mt-8 p-6 bg-gray-50 border border-gray-200 rounded-2xl">
                    <label class="flex items-start space-x-4 cursor-pointer">
                        <div class="flex-shrink-0 pt-0.5">
                            <input type="checkbox" name="pernyataan_persetujuan" id="pernyataan_persetujuan" class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mt-1 cursor-pointer" required>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Pernyataan Persetujuan</p>
                            <p class="text-xs font-medium text-gray-600 leading-relaxed">
                                Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. Saya memahami bahwa selama masa cuti akademik, saya tidak diperkenankan mengikuti kegiatan akademik apapun di Matla Islamic University. Status mahasiswa saya akan dinonaktifkan sementara apabila pengajuan ini disetujui.
                            </p>
                        </div>
                    </label>
                </div>
 
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-4">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-emerald-600/20 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Pengajuan
                    </button>
                    <a href="{{ route('backend.mahasiswa.cuti.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-gray-200 hover:bg-gray-50 hover:text-emerald-700 text-gray-600 font-bold rounded-xl text-sm transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
