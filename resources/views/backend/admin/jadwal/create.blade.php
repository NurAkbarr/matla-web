@extends('layouts.backend')

@section('title', 'Tambah Jadwal Baru')
@section('breadcrumb', 'Akademik / Jadwal / Tambah')

@section('content')
<div class="max-w-4xl bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 bg-gray-50/50">
        <h3 class="text-xl font-extrabold text-gray-900">Tambah Jadwal Perkuliahan</h3>
        <p class="text-sm text-gray-500 font-medium">Lengkapi formulir di bawah untuk mendaftarkan jadwal baru.</p>
    </div>

    <form action="{{ route('backend.admin.jadwal.store') }}" method="POST" class="p-8 space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Mata Kuliah -->
            <div class="space-y-3">
                <label for="mata_kuliah" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Mata Kuliah</label>
                <input type="text" name="mata_kuliah" id="mata_kuliah" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold @error('mata_kuliah') border-red-500 @enderror" 
                    placeholder="Contoh: Algoritma & Pemrograman" value="{{ old('mata_kuliah') }}">
                @error('mata_kuliah') <p class="text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <!-- Dosen -->
            <div class="space-y-3">
                <label for="dosen_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Dosen Pengampu</label>
                <select name="dosen_id" id="dosen_id" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold appearance-none cursor-pointer">
                    <option value="" disabled selected>Pilih Dosen...</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Program Studi -->
            <div class="space-y-3">
                <label for="program_studi_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Program Studi</label>
                <select name="program_studi_id" id="program_studi_id" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold appearance-none cursor-pointer">
                    <option value="" disabled selected>Pilih Program Studi...</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Semester -->
            <div class="space-y-3">
                <label for="semester" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Semester</label>
                <input type="number" name="semester" id="semester" required min="1" max="10"
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold" 
                    placeholder="Contoh: 1" value="{{ old('semester') }}">
            </div>

            <!-- SKS -->
            <div class="space-y-3">
                <label for="sks" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Jumlah SKS</label>
                <input type="number" name="sks" id="sks" required min="1" max="6"
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold" 
                    placeholder="Contoh: 3" value="{{ old('sks', 2) }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Hari -->
            <div class="space-y-3">
                <label for="hari" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Hari</label>
                <select name="hari" id="hari" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold appearance-none cursor-pointer">
                    @foreach($haris as $hari)
                        <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Waktu Mulai -->
            <div class="space-y-3">
                <label for="jam_mulai" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold" 
                    value="{{ old('jam_mulai') }}">
            </div>

            <!-- Waktu Selesai -->
            <div class="space-y-3">
                <label for="jam_selesai" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" required 
                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold" 
                    value="{{ old('jam_selesai') }}">
            </div>
        </div>

        <div class="space-y-3 border-t border-gray-50 pt-8">
            <label for="ruang" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Ruangan</label>
            <input type="text" name="ruang" id="ruang" required 
                class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-bold" 
                placeholder="Contoh: Ruang Galon atau Ruang A-101" value="{{ old('ruang') }}">
        </div>

        <div class="pt-6 flex justify-end space-x-4 border-t border-gray-50">
            <a href="{{ route('backend.admin.jadwal.index') }}" class="px-10 py-4 bg-white border border-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</a>
            <button type="submit" class="px-12 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 transition-all">
                Simpan Jadwal
            </button>
        </div>
    </form>
</div>
@endsection
