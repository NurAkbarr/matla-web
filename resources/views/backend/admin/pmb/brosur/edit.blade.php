@extends('layouts.backend')

@section('title', 'Edit Brosur PMB')
@section('breadcrumb', 'Manajemen Konten > Brosur PMB > Edit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Brosur</h1>
        <p class="text-gray-500 font-medium italic">Perbarui informasi atau file brosur Anda</p>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
        <form action="{{ route('backend.admin.pmb.brosur.update', $brosur->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Status & Order -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap:6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Status Tampilan</h3>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $brosur->is_active ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 focus:ring-primary rounded-lg transition-all">
                            <span class="text-gray-700 font-medium">Aktifkan Brosur</span>
                        </label>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Urutan Tampilan</h3>
                        <input type="number" name="order" value="{{ old('order', $brosur->order) }}" required
                            class="w-full px-5 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary text-gray-700 font-medium">
                        <p class="text-[10px] text-gray-400 mt-1">Angka lebih kecil tampil lebih dulu.</p>
                    </div>
                </div>

                <!-- Judul & Deskripsi -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Judul Brosur</label>
                        <input type="text" name="title" value="{{ old('title', $brosur->title) }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium"
                            placeholder="Contoh: Brosur Utama PMB 2024">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" rows="3"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium"
                            placeholder="Berikan ringkasan isi brosur...">{{ old('description', $brosur->description) }}</textarea>
                        @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Gambar Sampul (Thumbnail)</label>
                        <div class="mb-4 aspect-[3/4] w-24 overflow-hidden rounded-xl border border-gray-100">
                             <img src="{{ asset('storage/' . $brosur->image) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-primary transition-colors bg-gray-50/50">
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">Kosongkan jika tidak ingin mengubah. Max 2MB.</p>
                        </div>
                        @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">File Brosur (PDF)</label>
                        <div class="mb-4 flex items-center space-x-2 text-xs font-bold text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span>PDF Saat Ini Tersimpan</span>
                        </div>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-primary transition-colors bg-gray-50/50">
                            <input type="file" name="file" accept="application/pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">Kosongkan jika tidak ingin mengubah. Max 10MB.</p>
                        </div>
                        @error('file') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100 flex items-center space-x-4">
                <button type="submit" class="px-10 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold uppercase tracking-widest text-[10px] shadow-xl shadow-primary/20 transition-all transform hover:scale-[1.02]">
                    Simpan Perubahan
                </button>
                <a href="{{ route('backend.admin.pmb.brosur.index') }}" class="px-10 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl font-bold uppercase tracking-widest text-[10px] transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
