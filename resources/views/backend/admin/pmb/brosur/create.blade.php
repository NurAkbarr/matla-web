@extends('layouts.backend')

@section('title', 'Tambah Brosur PMB')
@section('breadcrumb', 'Manajemen Konten > Brosur PMB > Tambah')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Brosur Baru</h1>
        <p class="text-gray-500 font-medium italic">Upload sampul dan file PDF brosur Anda</p>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
        <form action="{{ route('backend.admin.pmb.brosur.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Status & Order -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Status Tampilan</h3>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-primary border-gray-300 focus:ring-primary rounded-lg transition-all">
                            <span class="text-gray-700 font-medium">Aktifkan Brosur</span>
                        </label>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Urutan Tampilan</h3>
                        <input type="number" name="order" value="0" required
                            class="w-full px-5 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary text-gray-700 font-medium">
                        <p class="text-[10px] text-gray-400 mt-1">Angka lebih kecil tampil lebih dulu.</p>
                    </div>
                </div>

                <!-- Judul & Deskripsi -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Judul Brosur</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium"
                            placeholder="Contoh: Brosur Utama PMB 2024">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="3"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium"
                            placeholder="Berikan ringkasan isi brosur..."></textarea>
                        @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- File Upload -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 block">File Gambar Brosur</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition-colors bg-gray-50/50">
                        <input type="file" name="image" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-4 font-medium italic">Format: JPEG, PNG, WEBP (Max 5MB). File ini akan digunakan sebagai tampilan galeri dan juga file yang dapat diunduh.</p>
                    </div>
                    @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100 flex items-center space-x-4">
                <button type="submit" class="px-10 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold uppercase tracking-widest text-[10px] shadow-xl shadow-primary/20 transition-all transform hover:scale-[1.02]">
                    Tambah Brosur
                </button>
                <a href="{{ route('backend.admin.pmb.brosur.index') }}" class="px-10 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl font-bold uppercase tracking-widest text-[10px] transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
