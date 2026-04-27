@extends('layouts.backend')

@section('title', 'Edit User')
@section('breadcrumb', 'User Management / Edit')

@section('content')
<div class="max-w-2xl bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50">
        <h3 class="text-xl font-extrabold text-gray-900">Edit Data Pengguna</h3>
        <p class="text-sm text-gray-500">Perbarui informasi profil atau reset password user.</p>
    </div>

    <form action="{{ route('backend.admin.users.update', $user) }}" method="POST" class="p-8 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Nama Lengkap</label>
                <input type="text" name="name" id="name" required 
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('name') border-red-500 @enderror" 
                    placeholder="Contoh: Dr. Ahmad" value="{{ old('name', $user->name) }}">
                @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Alamat Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('email') border-red-500 @enderror" 
                    placeholder="nama@matla.id" value="{{ old('email', $user->email) }}">
                @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>
        </div>

        <div x-data="{ role: '{{ old('role', $user->role) }}' }" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="role" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Role Pengguna</label>
                    @if($user->role === 'super_admin')
                        {{-- Super Admin: role tidak bisa diubah --}}
                        <input type="hidden" name="role" value="super_admin">
                        <div class="w-full px-5 py-4 bg-gray-100 border border-gray-200 rounded-2xl flex items-center space-x-2 cursor-not-allowed">
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Super Admin</span>
                            <span class="text-xs text-gray-400 ml-2">— tidak dapat diubah</span>
                        </div>
                    @else
                        <div class="relative">
                            <select name="role" id="role" required x-model="role"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="admin">Admin Kampus</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-gray-700 uppercase tracking-widest leading-none">Kata Sandi Baru</label>
                    <input type="password" name="password" id="password" 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm @error('password') border-red-500 @enderror" 
                        placeholder="Kosongkan jika tidak ingin diubah">
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
                        placeholder="Contoh: 25010241" value="{{ old('nim', $user->nim) }}">
                    @error('nim') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="program_studi" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Program Studi</label>
                    <input type="text" name="program_studi" id="program_studi" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" 
                        placeholder="Contoh: PAI" value="{{ old('program_studi', $user->education['program_studi'] ?? '') }}">
                </div>

                <div class="space-y-2">
                    <label for="angkatan" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" 
                        placeholder="Contoh: 2024" value="{{ old('angkatan', $user->angkatan) }}">
                </div>

                <div class="space-y-2">
                    <label for="semester" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Semester</label>
                    <input type="number" name="semester" id="semester" :required="role === 'mahasiswa'"
                        class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" 
                        placeholder="Contoh: 1" value="{{ old('semester', $user->semester) }}">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="status" class="text-[10px] font-black text-primary uppercase tracking-widest leading-none">Status Mahasiswa</label>
                    <div class="relative">
                        <select name="status" id="status" :required="role === 'mahasiswa'"
                            class="w-full px-5 py-4 bg-white border border-primary/10 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm appearance-none cursor-pointer">
                            <option value="AKTIF" {{ old('status', $user->status) == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="CUTI" {{ old('status', $user->status) == 'CUTI' ? 'selected' : '' }}>Cuti</option>
                            <option value="KELUAR" {{ old('status', $user->status) == 'KELUAR' ? 'selected' : '' }}>Keluar</option>
                            <option value="LULUS" {{ old('status', $user->status) == 'LULUS' ? 'selected' : '' }}>Lulus</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('backend.admin.users.index') }}" class="px-8 py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold hover:bg-gray-100 transition-all">Batal</a>
            <button type="submit" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
