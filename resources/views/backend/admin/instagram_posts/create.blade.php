@extends('layouts.backend')

@section('title', 'Tambah Postingan Instagram')
@section('breadcrumb', 'Manajemen Konten > Informasi Instagram > Tambah')

@section('content')
<div class="mb-6 md:mb-10">
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('backend.admin.instagram-posts.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Postingan</h1>
    </div>
    <p class="text-xs md:text-sm text-gray-500 font-medium italic ml-12">Upload foto thumbnail & masukkan link Instagram postingannya</p>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-10 max-w-3xl">
    <form action="{{ route('backend.admin.instagram-posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <!-- Petunjuk -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <div class="text-sm text-blue-700">
                    <p class="font-bold mb-1">Cara mengisi:</p>
                    <ol class="list-decimal list-inside space-y-1 text-blue-600">
                        <li>Buka postingan Instagram yang ingin ditampilkan</li>
                        <li>Screenshot atau simpan gambar thumbnail-nya</li>
                        <li>Copy link postingan tersebut (klik ••• > Salin tautan)</li>
                        <li>Upload thumbnail & paste linknya di sini</li>
                    </ol>
                    <p class="mt-2 font-semibold text-blue-700">💡 Saat pengunjung klik foto di website, mereka akan melihat postingan Instagram asli lengkap dengan caption & videonya!</p>
                </div>
            </div>

            <!-- Judul -->
            <div>
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul (Opsional)</label>
                <input type="text" name="title" id="title" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('title') }}" placeholder="Contoh: Info Pendaftaran Gelombang 2">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Instagram -->
            <div>
                <label for="instagram_link" class="block text-sm font-bold text-gray-700 mb-2">Link Postingan Instagram <span class="text-red-500">*</span></label>
                <input type="url" name="instagram_link" id="instagram_link" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('instagram_link') }}" placeholder="https://www.instagram.com/p/..." required>
                @error('instagram_link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-2 italic">Bisa link foto, video, maupun Reels Instagram.</p>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Foto Thumbnail <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition-colors cursor-pointer" onclick="document.getElementById('image').click()">
                    <div id="preview-container" class="hidden mb-4">
                        <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-xl object-cover">
                    </div>
                    <div id="upload-placeholder">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-gray-500">Klik untuk pilih foto thumbnail</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — Maks. 10MB</p>
                    </div>
                </div>
                <input type="file" name="image" id="image" class="hidden" accept="image/*" required onchange="previewImage(this)">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                <!-- Urutan -->
                <div>
                    <label for="order" class="block text-sm font-bold text-gray-700 mb-2">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors" value="{{ old('order', 0) }}">
                    <p class="text-xs text-gray-400 mt-2 italic">Angka terkecil tampil paling depan.</p>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Penayangan</label>
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Tampilkan di Halaman Utama</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/30 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Postingan</span>
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
