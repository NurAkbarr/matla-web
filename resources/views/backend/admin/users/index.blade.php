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
    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-extrabold text-gray-900">Daftar Pengguna</h3>
            <p class="text-sm text-gray-500">Kelola semua akun sivitas akademika di sini.</p>
        </div>
        <a href="{{ route('backend.admin.users.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah User</span>
        </a>
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
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs capitalize">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-gray-900 text-sm capitalize">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-sm text-gray-500 font-medium">{{ $user->email }}</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-{{ $user->role == 'admin' ? 'blue' : ($user->role == 'dosen' ? 'orange' : 'emerald') }}-50 text-{{ $user->role == 'admin' ? 'blue' : ($user->role == 'dosen' ? 'orange' : 'primary') }}-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center space-x-2">
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
