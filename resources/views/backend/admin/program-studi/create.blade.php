@extends('layouts.backend')

@section('title', 'Tambah Program Studi')
@section('breadcrumb', 'Akademik / Program Studi / Tambah')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50">
            <h3 class="text-xl font-extrabold text-gray-900">Tambah Program Studi Baru</h3>
            <p class="text-sm text-gray-500 mt-1">Isi informasi lengkap program akademik yang akan ditambahkan.</p>
        </div>

        <form action="{{ route('backend.admin.program-studi.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 md:col-span-2">
                    <label for="nama" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Nama Program Studi</label>
                    <input type="text" name="nama" id="nama" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('nama') border-red-400 @enderror"
                        placeholder="Contoh: Teknik Informatika" value="{{ old('nama') }}">
                    @error('nama') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="singkatan" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Singkatan</label>
                    <input type="text" name="singkatan" id="singkatan" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('singkatan') border-red-400 @enderror"
                        placeholder="Contoh: TI" value="{{ old('singkatan') }}">
                    @error('singkatan') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="jenjang" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Jenjang</label>
                    <div class="relative">
                        <select name="jenjang" id="jenjang" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                            @foreach(['D3','D4','S1','S2','S3','Profesi'] as $j)
                                <option value="{{ $j }}" {{ old('jenjang','S1') == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="icon" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Icon (Emoji)</label>
                    <input type="text" name="icon" id="icon"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm text-2xl"
                        placeholder="🎓" value="{{ old('icon', '🎓') }}" maxlength="5">
                    <p class="text-[10px] text-gray-400">Masukkan satu emoji sebagai ikon program studi</p>
                </div>

                <div class="space-y-2">
                    <label for="akreditasi" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Akreditasi</label>
                    <div class="relative">
                        <select name="akreditasi" id="akreditasi"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                            @foreach(['Unggul','Baik Sekali','Baik','B','C'] as $akr)
                                <option value="{{ $akr }}" {{ old('akreditasi','Baik') == $akr ? 'selected' : '' }}>{{ $akr }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="deskripsi" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm resize-none"
                        placeholder="Deskripsi singkat tentang program studi ini...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="space-y-2">
                    <label for="urutan" class="text-xs font-bold text-gray-700 uppercase tracking-widest">Urutan Tampil</label>
                    <input type="number" name="urutan" id="urutan" min="0"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm"
                        placeholder="0" value="{{ old('urutan', 0) }}">
                </div>

                <div class="space-y-2 flex flex-col justify-end">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-widest">Status</label>
                    <label for="is_active" class="flex items-center space-x-3 px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl cursor-pointer hover:border-primary/30 transition-all">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-5 h-5 text-primary rounded-lg focus:ring-primary/20 border-gray-300">
                        <span class="text-sm font-medium text-gray-700">Aktifkan Program Studi</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('backend.admin.program-studi.index') }}" class="px-8 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold hover:bg-gray-100 transition-all">Batal</a>
                <button type="submit" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Simpan Program Studi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
