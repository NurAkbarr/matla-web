@extends('layouts.backend')

@section('title', 'Manajemen Mahasiswa')
@section('breadcrumb', 'Akademik / Mahasiswa Aktif')

@section('content')
<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Manajemen Mahasiswa Aktif</h1>
            <p class="text-gray-500 font-medium">Kelola data profil, IPK, dan SKS mahasiswa</p>
        </div>
        <button class="mt-4 md:mt-0 px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Mahasiswa</span>
        </button>
    </div>

    <!-- Search & Filter Area -->
    <div class="mb-8 p-6 bg-gray-50 rounded-3xl flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Cari nama atau NIM..." class="w-full pl-12 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-primary/20 transition-all font-medium text-sm">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-50">
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Mahasiswa</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM & Prodi</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Semester</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Angkatan</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">IPK</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">SKS</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 uppercase tracking-tighter font-bold">
                @forelse($users as $user)
                <tr class="group hover:bg-gray-50 transition-all">
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="text-gray-900 mb-1">{{ $user->name }}</span>
                            <span class="text-[10px] text-gray-400 font-medium lowercase italic">{{ $user->email }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="text-gray-900 mb-1">1234567890</span>
                            <span class="text-[8px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md inline-block w-fit">Pendidikan Agama Islam</span>
                        </div>
                    </td>
                    <td class="py-6 px-4 text-center">{{ $user->semester ?? '-' }}</td>
                    <td class="py-6 px-4 text-center">{{ $user->angkatan ?? '-' }}</td>
                    <td class="py-6 px-4 text-center text-gray-400">-</td>
                    <td class="py-6 px-4 text-center">0/144</td>
                    <td class="py-6 px-4">
                        <div class="flex items-center justify-end space-x-2">
                            <button class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400 italic">Belum ada data mahasiswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
