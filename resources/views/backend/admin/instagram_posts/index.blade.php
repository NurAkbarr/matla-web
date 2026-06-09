@extends('layouts.backend')

@section('title', 'Manajemen Informasi Instagram')
@section('breadcrumb', 'Manajemen Konten > Informasi Instagram')

@section('content')
<div class="mb-6 md:mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Informasi Instagram</h1>
        <p class="text-xs md:text-sm text-gray-500 font-medium italic">Kelola postingan Instagram yang tampil di Halaman Utama</p>
    </div>
    <a href="{{ route('backend.admin.instagram-posts.create') }}" 
       class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-5 md:px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl md:rounded-2xl font-bold text-xs md:text-sm shadow-xl shadow-primary/20 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Tambah Postingan</span>
    </a>
</div>

@if(session('success'))
<div class="mb-6 md:mb-8 p-3 md:p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl md:rounded-2xl font-medium text-sm">
    {{ session('success') }}
</div>
@endif

@if($posts->where('is_active', true)->count() > 9)
<div class="mb-6 md:mb-8 p-3 md:p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl md:rounded-2xl font-medium text-sm flex items-center gap-3">
    <svg class="w-6 h-6 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        <strong>Perhatian:</strong> Jumlah postingan aktif melebihi batas maksimal (9 postingan). Postingan ke-10 dan seterusnya tidak akan ditampilkan di Halaman Utama. Harap non-aktifkan atau hapus postingan yang sudah lama.
    </div>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
    @forelse($posts as $post)
    <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
        <!-- Thumbnail -->
        <div class="relative h-48 sm:h-auto sm:aspect-square overflow-hidden bg-gray-50 flex items-center justify-center">
            @if($post->type == 'video')
                <video src="{{ asset('instagram-posts/' . $post->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" muted preload="metadata"></video>
                <!-- Play Icon Indicator -->
                <div class="absolute inset-0 flex items-center justify-center bg-black/10 z-10 pointer-events-none">
                    <div class="w-12 h-12 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 384 512"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg>
                    </div>
                </div>
            @else
                <img src="{{ asset('instagram-posts/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity z-20"></div>
            
            <!-- Badge Status -->
            <div class="absolute top-3 right-3 md:top-4 md:right-4">
                @if($post->is_active)
                <span class="px-2.5 md:px-3 py-1 bg-emerald-500 text-white text-[8px] md:text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Aktif</span>
                @else
                <span class="px-2.5 md:px-3 py-1 bg-red-500 text-white text-[8px] md:text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Non-Aktif</span>
                @endif
            </div>
            
            <a href="{{ $post->instagram_link }}" target="_blank" class="absolute bottom-4 left-4 bg-white/20 backdrop-blur-md p-2 rounded-full text-white hover:bg-white hover:text-pink-600 transition-colors opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
            </a>
        </div>

        <!-- Content -->
        <div class="p-4 md:p-6">
            <div class="mb-3 md:mb-4">
                <span class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase tracking-widest">Urutan: #{{ $post->order }}</span>
                <h3 class="text-base md:text-lg font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug truncate">{{ $post->title ?? 'Tanpa Judul' }}</h3>
            </div>

            <div class="flex items-center justify-between pt-3 md:pt-4 border-t border-gray-50">
                <div class="flex space-x-1.5 md:space-x-2">
                    <a href="{{ route('backend.admin.instagram-posts.edit', $post->id) }}" class="p-1.5 md:p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg md:rounded-xl transition-all">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('backend.admin.instagram-posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 md:p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg md:rounded-xl transition-all">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 md:py-20 text-center bg-white rounded-[2rem] border border-dashed border-gray-200">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-300" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
        </div>
        <p class="text-gray-400 font-medium italic text-sm">Belum ada foto yang ditambahkan.</p>
    </div>
    @endforelse
</div>
@endsection
