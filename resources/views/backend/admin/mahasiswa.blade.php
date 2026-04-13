@extends('layouts.backend')

@section('title', 'Manajemen Mahasiswa')
@section('breadcrumb', 'Akademik / Manajemen Mahasiswa')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Manajemen Mahasiswa</h1>
                <p class="text-gray-500 font-medium">Kelola status, filter angkatan, dan data akademik mahasiswa secara terpusat.</p>
            </div>
            <a href="{{ route('backend.admin.users.create', ['role' => 'mahasiswa']) }}" class="mt-6 md:mt-0 px-8 py-3 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Tambah Mahasiswa</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Area -->
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
        <form action="{{ route('backend.admin.mahasiswa') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Bar -->
            <div class="md:col-span-1 relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." 
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all">
            </div>

            <!-- Dropdown Angkatan -->
            <div>
                <select name="angkatan" onchange="this.form.submit()" 
                        class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $year)
                        <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>Angkatan {{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Semester -->
            <div>
                <select name="semester" onchange="this.form.submit()"
                        class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $smt)
                        <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>Semester {{ $smt }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Status -->
            <div>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl text-xs font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all">
                    <option value="">Semua Status</option>
                    <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                    <option value="CUTI" {{ request('status') == 'CUTI' ? 'selected' : '' }}>Cuti</option>
                    <option value="KELUAR" {{ request('status') == 'KELUAR' ? 'selected' : '' }}>Keluar</option>
                    <option value="LULUS" {{ request('status') == 'LULUS' ? 'selected' : '' }}>Lulus</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-primary px-8 py-5 border-b border-primary-dark flex justify-between items-center">
            <h3 class="text-xs font-black text-white uppercase tracking-widest">Daftar Mahasiswa Universitas</h3>
            <span class="px-3 py-1 bg-white/10 text-white rounded-lg text-[10px] font-black">{{ $users->count() }} Total Data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-primary/95 text-white">
                        <th class="border border-white/10 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-center w-12 text-white">No</th>
                        <th class="border border-white/10 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white">Informasi Mahasiswa</th>
                        <th class="border border-white/10 px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest w-40 text-white">Angkatan & Smt</th>
                        <th class="border border-white/10 px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest w-32 text-white">Status</th>
                        <th class="border border-white/10 px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest w-32 bg-primary-dark text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="border border-gray-100 px-6 py-4 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="border border-gray-100 px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-gray-900 leading-none mb-1 capitalize">{{ $user->name }}</span>
                                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="border border-gray-100 px-6 py-4 text-center">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-gray-700">Angkatan {{ $user->angkatan ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-gray-400">Semester {{ $user->semester ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="border border-gray-100 px-6 py-4 text-center">
                            <span class="px-3 py-1.5 {{ $user->status_badge }} rounded-lg text-[9px] font-black uppercase tracking-widest border border-current/20">
                                {{ $user->status ?? 'AKTIF' }}
                            </span>
                        </td>
                        <td class="border border-gray-100 px-6 py-4 text-right bg-gray-50/20">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('backend.admin.users.show', $user) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('backend.admin.users.edit', $user) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form action="{{ route('backend.admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400 italic">Data mahasiswa tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
