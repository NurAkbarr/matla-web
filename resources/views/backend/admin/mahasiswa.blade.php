@extends('layouts.backend')

@section('title', 'Manajemen Mahasiswa')
@section('breadcrumb', 'Akademik / Manajemen Mahasiswa')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full -mr-32 -mt-32 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Manajemen Mahasiswa</h1>
                <p class="text-gray-500 font-medium">Kelola status, filter angkatan, dan data akademik mahasiswa secara terpusat.</p>
            </div>
            <div class="mt-6 md:mt-0 flex flex-wrap items-center gap-3">
                
                <!-- Action Dropdown untuk Import / Export -->
                <div x-data="{ open: false, showImportModal: false }" class="relative">
                    <button @click="open = !open" class="px-5 py-3 bg-white text-gray-700 font-black text-xs uppercase tracking-widest border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <span>Aksi Lanjutan</span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <button @click="showImportModal = true; open = false" class="w-full text-left px-4 py-3 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-primary transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span>Import CSV (Excel)</span>
                        </button>
                        <a href="{{ route('backend.admin.mahasiswa.export.excel') }}" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Download CSV</span>
                        </a>
                        <a href="{{ route('backend.admin.mahasiswa.export.pdf') }}" target="_blank" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak PDF / Print</span>
                        </a>
                    </div>

                    <!-- Modal Import -->
                    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                        <div @click.away="showImportModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-200">
                            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="text-lg font-black text-gray-900 tracking-tight">Import Data Mahasiswa</h3>
                                <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="{{ route('backend.admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                                @csrf
                                <div class="mb-6 bg-blue-50 border border-blue-100 p-5 rounded-2xl">
                                    <h4 class="text-xs font-black text-blue-800 uppercase tracking-widest mb-2">Instruksi Format CSV:</h4>
                                    <p class="text-sm text-blue-700 leading-relaxed">
                                        Pastikan file berformat <strong>.csv</strong> dan memiliki kolom <em>(header tidak wajib tapi disarankan)</em> pada urutan berikut:<br>
                                        <strong class="text-xs">1: Nama, 2: Email, 3: Angkatan, 4: Semester</strong><br><br>
                                        <span class="text-xs italic">*Password otomatis diset: <strong>password123</strong></span>
                                    </p>
                                </div>
                                <div class="mb-8 relative group">
                                    <input type="file" name="csv_file" accept=".csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 flex flex-col items-center justify-center text-center group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 group-hover:text-primary transition-colors">
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="font-bold text-gray-700 text-sm">Klik atau Seret file CSV ke sini</p>
                                        <p class="text-xs text-gray-400 mt-1">Maksimal 2MB</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-primary text-white font-bold text-sm uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/30">
                                    Mulai Proses Import
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ route('backend.admin.users.create', ['role' => 'mahasiswa']) }}" class="px-6 py-3 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Tambah</span>
                </a>
            </div>
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
