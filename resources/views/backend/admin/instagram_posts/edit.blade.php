@extends('layouts.backend')

@section('title', 'Edit Postingan Instagram')
@section('breadcrumb', 'Manajemen Konten > Informasi Instagram > Edit')

@section('content')
<div class="mb-6 md:mb-10">
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('backend.admin.instagram-posts.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Edit Postingan</h1>
    </div>
    <p class="text-xs md:text-sm text-gray-500 font-medium italic ml-12">Perbarui gambar atau tautan Instagram</p>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-10 max-w-3xl">
    <form action="{{ route('backend.admin.instagram-posts.update', $instagram_post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Judul -->
            <div>
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul (Opsional)</label>
                <input type="text" name="title" id="title" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('title', $instagram_post->title) }}" placeholder="Contoh: Info Pendaftaran Gelombang 2">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Media -->
            <div>
                <label for="type" class="block text-sm font-bold text-gray-700 mb-2">Tipe Postingan <span class="text-red-500">*</span></label>
                <select name="type" id="type" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors">
                    <option value="image" {{ old('type', $instagram_post->type) == 'image' ? 'selected' : '' }}>Foto / Gambar</option>
                    <option value="video" {{ old('type', $instagram_post->type) == 'video' ? 'selected' : '' }}>Video (MP4 / MOV)</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Instagram -->
            <div>
                <label for="instagram_link" class="block text-sm font-bold text-gray-700 mb-2">Link Postingan Instagram <span class="text-red-500">*</span></label>
                <input type="url" name="instagram_link" id="instagram_link" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('instagram_link', $instagram_post->instagram_link) }}" placeholder="https://www.instagram.com/p/..." required>
                @error('instagram_link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gambar / Video -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">File Saat Ini</label>
                @if($instagram_post->image)
                    <div class="mb-4 bg-gray-50 p-2 rounded-xl border border-gray-200 inline-block">
                        @if($instagram_post->type == 'video')
                        <video src="{{ asset('instagram-posts/' . $instagram_post->image) }}" class="h-32 rounded-lg" controls></video>
                        @else
                        <img src="{{ asset('instagram-posts/' . $instagram_post->image) }}" class="h-32 object-contain rounded-lg">
                        @endif
                    </div>
                @endif
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Ganti File Foto/Video (Opsional)</label>
                <input type="file" name="image" id="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors" accept="image/*,video/mp4,video/quicktime">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-2 italic">Format Foto: JPG, PNG. Format Video: MP4, MOV. Maks 20MB. Kosongkan jika tidak ingin mengganti file.</p>
            </div>

            <!-- Caption -->
            <div>
                <label for="caption" class="block text-sm font-bold text-gray-700 mb-2">Caption / Deskripsi (Opsional)</label>
                <textarea name="caption" id="caption" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" placeholder="Ketik caption lengkap di sini...">{{ old('caption', $instagram_post->caption) }}</textarea>
                @error('caption')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <!-- Urutan -->
                <div>
                    <label for="order" class="block text-sm font-bold text-gray-700 mb-2">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('order', $instagram_post->order) }}">
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Penayangan</label>
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $instagram_post->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Tampilkan di Halaman Utama</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/30 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>
@endsection
