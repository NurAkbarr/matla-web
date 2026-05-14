@extends('layouts.backend')

@section('title', 'Edit Berita & Pengumuman')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('backend.admin.announcements.index') }}" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Berita</h1>
    </div>

    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 max-w-4xl">
        <form action="{{ route('backend.admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Judul Berita</label>
                    <input type="text" name="title" value="{{ $announcement->title }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Kategori</label>
                    <select name="category" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        <option value="Pengumuman" {{ $announcement->category == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Akademik" {{ $announcement->category == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Prestasi" {{ $announcement->category == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Event" {{ $announcement->category == 'Event' ? 'selected' : '' }}>Event</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Pilih Ikon</label>
                    <div class="flex flex-wrap gap-3 mb-2" x-data="{ selected: '{{ $announcement->icon }}' }">
                        <input type="hidden" name="icon" :value="selected">
                        @foreach(['📢', '🎓', '🏆', '📅', '💻', '💡'] as $emoji)
                            <button type="button" @click="selected = '{{ $emoji }}'" 
                                    :class="selected === '{{ $emoji }}' ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 bg-gray-50 text-gray-400 hover:border-gray-200'"
                                    class="w-12 h-12 flex items-center justify-center rounded-xl border-2 transition-all text-xl">
                                {{ $emoji }}
                            </button>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold">Klik salah satu ikon di atas</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Ganti File PDF</label>
                    <input type="file" name="pdf_file" accept=".pdf" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl outline-none">
                    @if($announcement->pdf_file)
                        <div class="mt-2 flex items-center p-2 bg-blue-50 rounded-lg border border-blue-100">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <a href="{{ asset('storage/'.$announcement->pdf_file) }}" target="_blank" class="text-[10px] font-bold text-blue-600 truncate max-w-[200px]">File saat ini: {{ basename($announcement->pdf_file) }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Tanggal Rilis</label>
                    <input type="date" name="published_at" value="{{ $announcement->published_at->format('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Isi Berita (Opsional)</label>
                <textarea name="content" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Tuliskan isi pengumuman jika tidak ada file PDF...">{{ $announcement->content }}</textarea>
            </div>

            <div class="flex items-center space-x-2 mb-8">
                <input type="checkbox" name="is_active" value="1" {{ $announcement->is_active ? 'checked' : '' }} id="is_active" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                <label for="is_active" class="text-sm font-bold text-gray-700">Terbitkan Sekarang</label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                    Perbarui Berita
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
