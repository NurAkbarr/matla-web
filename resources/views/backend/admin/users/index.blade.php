@extends('layouts.backend')

@section('title', 'Manajemen User')
@section('breadcrumb', 'User Management / List')

@section('content')
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-primary rounded-2xl flex items-center space-x-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-center space-x-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
        <div>
            <h3 class="text-xl font-extrabold text-gray-900">Daftar Pengguna</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola semua akun sivitas akademika di sini.</p>
        </div>
        <a href="{{ route('backend.admin.users.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah User</span>
        </a>
    </div>

    <!-- Search & Filter Area -->
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('backend.admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, NIM, atau email..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
            </div>
            
            <div class="w-full md:w-72">
                <select name="role" onchange="this.form.submit()" 
                        class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm cursor-pointer appearance-none">
                    <option value="">Semua Kelompok (Role)</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>
                            Kelompok {{ ucwords(str_replace('_', ' ', $r)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Nama Pengguna</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Email</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Role</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl overflow-hidden shadow-sm">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                            <span class="font-bold text-gray-900 text-sm capitalize">{{ strtolower($user->name) }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-sm text-gray-500 font-medium">{{ strtolower($user->email) }}</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-{{ $user->role == 'admin' || $user->role == 'super_admin' ? 'blue' : ($user->role == 'dosen' ? 'orange' : 'emerald') }}-50 text-{{ $user->role == 'admin' || $user->role == 'super_admin' ? 'blue' : ($user->role == 'dosen' ? 'orange' : 'primary') }}-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-current/20">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center space-x-2">
                            <a href="{{ route('backend.admin.users.show', $user) }}" class="p-2 text-gray-400 hover:text-emerald-500 transition-colors" title="Detail User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('backend.admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Edit User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @if(auth()->id() !== $user->id && $user->role !== 'super_admin')
                            <form action="{{ route('backend.admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Hapus User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-gray-400 font-medium">Data user tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-6 bg-white border-t border-gray-100 rounded-b-[2rem]">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
