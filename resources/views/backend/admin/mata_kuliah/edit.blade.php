@extends('layouts.backend')

@section('title', 'Edit Mata Kuliah')
@section('breadcrumb', 'Akademik / Mata Kuliah / Edit')

@section('content')
<div class="max-w-4xl bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 bg-gray-50/50">
        <h3 class="text-xl font-extrabold text-gray-900">Edit Mata Kuliah</h3>
        <p class="text-sm text-gray-500 font-medium">Perbarui informasi mata kuliah {{ $mataKuliah->nama }}.</p>
    </div>

    <form action="{{ route('backend.admin.mata-kuliah.update', $mataKuliah->id) }}" method="POST" class="p-8 space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Program Studi -->
            <div class="space-y-3">
                <label for="program_studi_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Program Studi</label>
                <select name="program_studi_id" id="program_studi_id" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold appearance-none cursor-pointer">
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ (old('program_studi_id') ?? $mataKuliah->program_studi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
                @error('program_studi_id') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <!-- Kode MK -->
            <div class="space-y-3">
                <label for="kode" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Kode Mata Kuliah</label>
                <input type="text" name="kode" id="kode" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold @error('kode') border-red-500 @enderror" 
                    placeholder="Contoh: TI101" value="{{ old('kode') ?? $mataKuliah->kode }}">
                @error('kode') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Nama MK -->
        <div class="space-y-3">
            <label for="nama" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Nama Mata Kuliah</label>
            <input type="text" name="nama" id="nama" required 
                class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold @error('nama') border-red-500 @enderror" 
                placeholder="Contoh: Algoritma & Pemrograman I" value="{{ old('nama') ?? $mataKuliah->nama }}">
            @error('nama') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- SKS -->
            <div class="space-y-3">
                <label for="sks" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Jumlah SKS</label>
                <input type="number" name="sks" id="sks" required min="1" max="8"
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold @error('sks') border-red-500 @enderror" 
                    value="{{ old('sks') ?? $mataKuliah->sks }}">
                @error('sks') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <!-- Semester -->
            <div class="space-y-3">
                <label for="semester" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Semester</label>
                <input type="number" name="semester" id="semester" required min="1" max="14"
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold @error('semester') border-red-500 @enderror" 
                    value="{{ old('semester') ?? $mataKuliah->semester }}">
                @error('semester') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-6 flex justify-end space-x-4 border-t border-gray-50">
            <a href="{{ route('backend.admin.mata-kuliah.index') }}" class="px-10 py-4 bg-white border border-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</a>
            <button type="submit" class="px-12 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
