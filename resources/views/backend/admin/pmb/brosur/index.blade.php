@extends('layouts.backend')

@section('title', 'Manajemen Brosur PMB')
@section('breadcrumb', 'Manajemen Konten > Brosur PMB')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Brosur PMB</h1>
        <p class="text-gray-500 font-medium italic">Kelola brosur yang tampil di halaman PMB</p>
    </div>
    <a href="{{ route('backend.admin.pmb.brosur.create') }}" 
       class="inline-flex items-center space-x-2 px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold text-sm shadow-xl shadow-primary/20 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Tambah Brosur</span>
    </a>
</div>

@if(session('success'))
<div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-medium">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($brosurs as $brosur)
    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
        <!-- Thumbnail -->
        <div class="relative aspect-[3/4] overflow-hidden">
            <img src="{{ asset('storage/' . $brosur->image) }}" alt="{{ $brosur->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <!-- Badge Status -->
            <div class="absolute top-4 right-4">
                @if($brosur->is_active)
                <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Aktif</span>
                @else
                <span class="px-3 py-1 bg-red-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Non-Aktif</span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="mb-4">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Order: #{{ $brosur->order }}</span>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $brosur->title }}</h3>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $brosur->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <div class="flex space-x-2">
                    <a href="{{ route('backend.admin.pmb.brosur.edit', $brosur->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('backend.admin.pmb.brosur.destroy', $brosur->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brosur ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <a href="{{ asset('storage/' . $brosur->file) }}" target="_blank" class="text-xs font-bold text-primary hover:underline flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>View PDF</span>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <p class="text-gray-400 font-medium italic">Belum ada brosur yang ditambahkan.</p>
    </div>
    @endforelse
</div>
@endsection
