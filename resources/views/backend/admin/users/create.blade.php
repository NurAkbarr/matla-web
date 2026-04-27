@extends('layouts.backend')

@section('title', 'Tambah User Baru')
@section('breadcrumb', 'User Management / Create')

@section('content')
<div class="max-w-2xl bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50">
        <h3 class="text-xl font-extrabold text-gray-900">Registrasi User Baru</h3>
        <p class="text-sm text-gray-500">Daftarkan Admin, Dosen, atau Mahasiswa secara internal.</p>
    </div>

    <form action="{{ route('backend.admin.users.store') }}" method="POST" class="p-8 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Nama Lengkap</label>
                <input type="text" name="name" id="name" required 
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('name') border-red-500 @enderror" 
                    placeholder="Contoh: Dr. Ahmad" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Alamat Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('email') border-red-500 @enderror" 
                    placeholder="nama@matla.id" value="{{ old('email') }}">
                @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>
        </div>

        <div x-data="{ role: '{{ old('role', 'mahasiswa') }}' }" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="role" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Role Pengguna</label>
                    <select name="role" id="role" required x-model="role"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin Kampus</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Kata Sandi</label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('password') border-red-500 @enderror" 
                        placeholder="Minimal 8 karakter">
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Mahasiswa Specific Fields -->
            <div x-show="role === 'mahasiswa'" x-transition 
                 class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-primary/5 rounded-[1.5rem] border border-primary/10">
                 
                <div class="space-y-2">
                    <label for="nim" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">NIM (Nomor Induk)</label>
                    <input type="text" name="nim" id="nim" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('nim') border-red-500 @enderror" 
                        placeholder="Contoh: 25010241" value="{{ old('nim') }}">
                    @error('nim') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="program_studi" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Program Studi</label>
                    <div class="relative">
                        <select name="program_studi" id="program_studi" :required="role === 'mahasiswa'"
                            class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodis as $prodi)
                            <option value="{{ $prodi->nama }}" {{ old('program_studi') == $prodi->nama ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="angkatan" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" 
                        placeholder="Contoh: 2024" value="{{ old('angkatan') }}">
                </div>

                <div class="space-y-2">
                    <label for="semester" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Semester</label>
                    <input type="number" name="semester" id="semester" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" 
                        placeholder="Contoh: 1" value="{{ old('semester') }}">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="status" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Status Mahasiswa</label>
                    <select name="status" id="status" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                        <option value="AKTIF" {{ old('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="CUTI" {{ old('status') == 'CUTI' ? 'selected' : '' }}>Cuti</option>
                        <option value="KELUAR" {{ old('status') == 'KELUAR' ? 'selected' : '' }}>Keluar</option>
                        <option value="LULUS" {{ old('status') == 'LULUS' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('backend.admin.users.index') }}" class="px-8 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold hover:bg-gray-100 transition-all">Batal</a>
            <button type="submit" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                Simpan User
            </button>
        </div>
    </form>
</div>
@endsection
