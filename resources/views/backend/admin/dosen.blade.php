@extends('layouts.backend')

@section('title', 'Direktori Dosen')
@section('breadcrumb', 'Akademik / Data Dosen')

@section('content')
<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Direktori Dosen</h1>
            <p class="text-gray-500 font-medium">Daftar staf pengajar dan dosen aktif universitas</p>
        </div>
        <a href="{{ route('backend.admin.users.index') }}" class="mt-4 md:mt-0 px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all flex items-center space-x-2">
            <span>Kelola via Manajemen User</span>
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-8 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Cari nama atau email dosen..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-gray-50">
                    <tr class="text-left border-b border-gray-50">
                    <th class="pb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">Profil Dosen</th>
                    <th class="pb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest px-4 text-center">Status Role</th>
                    <th class="pb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="group hover:bg-gray-50 transition-all">
                    <td class="py-6 px-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm group-hover:scale-110 transition-transform">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 leading-none mb-1 capitalize">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-6 px-4 text-center">
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-orange-100">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-6 px-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('backend.admin.users.show', $user) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Detail Profile">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('backend.admin.users.edit', $user) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
