@extends('layouts.backend')

@section('title', 'Manajemen Mahasiswa')
@section('breadcrumb', 'Akademik / Manajemen Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight mb-1">Manajemen Mahasiswa</h1>
                <p class="text-sm font-medium text-slate-500">Kelola data mahasiswa dalam sistem</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto xl:justify-end">
                
                <!-- Action Dropdown untuk Import / Export -->
                <div class="relative flex-1 sm:flex-none">
                    <button onclick="toggleImportDropdown()" class="w-full sm:w-auto px-4 py-2.5 bg-white text-slate-700 text-sm font-medium border border-slate-200 rounded-lg hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Export / Import</span>
                    </button>
                    
                    <div id="import-export-dropdown" style="display: none;" class="absolute left-0 sm:left-auto sm:right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <button onclick="document.getElementById('import-modal').style.display='flex'; document.getElementById('import-export-dropdown').style.display='none';" class="w-full text-left px-4 py-3 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-primary transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span>Import CSV (Excel)</span>
                        </button>
                        <a href="{{ route('backend.admin.mahasiswa.export.excel', request()->query()) }}" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Download CSV</span>
                        </a>
                        <a href="{{ route('backend.admin.mahasiswa.export.pdf', request()->query()) }}" target="_blank" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center space-x-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak PDF / Print</span>
                        </a>
                    </div>

                    <!-- Modal Import -->
                    <div id="import-modal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                        <div onclick="document.getElementById('import-modal').style.display='none'" class="absolute inset-0"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-200">
                            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="text-lg font-black text-gray-900 tracking-tight">Import Data Mahasiswa</h3>
                                <button type="button" onclick="document.getElementById('import-modal').style.display='none'" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="{{ route('backend.admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                                @csrf
                                <div class="mb-6 bg-blue-50 border border-blue-100 p-5 rounded-2xl">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-xs font-black text-blue-800 uppercase tracking-widest">Instruksi Format CSV:</h4>
                                        <a href="data:text/csv;charset=utf-8,%EF%BB%BFNIM%2CNama%2CEmail%2CNo%20Telepon%2CProgram%20Studi%2CAngkatan%2CSemester%2CStatus%2CJenis%20Kelamin%0A202601001%2CBudi%20Santoso%2Cbudi%40example.com%2C08123456789%2CPendidikan%20Agama%20Islam%2C2026%2C1%2CAKTIF%2CL" download="template_mahasiswa.csv" class="text-xs font-bold text-blue-600 hover:text-blue-800 underline flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Unduh Template
                                        </a>
                                    </div>
                                    <p class="text-sm text-blue-700 leading-relaxed">
                                        Pastikan file berformat <strong>.csv</strong> dengan susunan kolom:<br>
                                        <strong class="text-xs leading-relaxed">1: NIM, 2: Nama, 3: Email, 4: No Telepon, 5: Program Studi, 6: Angkatan, 7: Semester, 8: Status, 9: Jenis Kelamin</strong><br><br>
                                        <span class="text-xs italic text-blue-800">*Password mahasiswa baru otomatis diset: <strong>password123</strong>. Mahasiswa terdaftar akan diperbarui datanya tanpa mereset password.</span>
                                    </p>
                                </div>
                                <div class="mb-8 relative group">
                                    <input type="file" name="csv_file" accept=".csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 flex flex-col items-center justify-center text-center group-hover:border-primary group-hover:bg-primary/5 transition-all">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 group-hover:text-primary transition-colors">
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="font-bold text-gray-700 text-sm">Klik atau Seret file CSV ke sini</p>
                                        <p class="text-xs text-gray-400 mt-1">Maksimal 4MB</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-primary text-white font-bold text-sm uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/30">
                                    Mulai Proses Import
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ route('backend.admin.users.create', ['role' => 'mahasiswa']) }}" class="flex-1 sm:flex-none px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-all shadow-sm shadow-primary/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Tambah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filter Area -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <form action="{{ route('backend.admin.mahasiswa') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Search Bar -->
            <div class="md:col-span-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, atau nomor telepon..." 
                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-slate-400 focus:ring-0 transition-all">
            </div>

            <!-- Dropdown Program Studi -->
            <div>
                <select name="program_studi" onchange="this.form.submit()" 
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:border-slate-400 focus:ring-0 transition-all appearance-none">
                    <option value="">Semua Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->nama }}" {{ request('program_studi') == $prodi->nama ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Angkatan -->
            <div>
                <select name="angkatan" onchange="this.form.submit()" 
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:border-slate-400 focus:ring-0 transition-all appearance-none">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $year)
                        <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>Angkatan {{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Semester -->
            <div>
                <select name="semester" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:border-slate-400 focus:ring-0 transition-all appearance-none">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $smt)
                        <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>Semester {{ $smt }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Status -->
            <div>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:border-slate-400 focus:ring-0 transition-all appearance-none">
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
    <!-- Hidden Bulk Delete Form -->
    <form action="{{ route('backend.admin.users.bulk_delete') }}" method="POST" id="bulkDeleteForm" class="hidden">
        @csrf
        <div id="hiddenInputsContainer"></div>
    </form>

    @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
    <div class="p-4 border-b border-emerald-100 bg-red-50/50 hidden rounded-t-[2.5rem]" id="bulkActionContainer">
        <div class="flex items-center justify-between px-4">
            <span class="text-sm font-bold text-red-600" id="selectedCount">0 item terpilih</span>
            <button type="button" onclick="confirmBulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition-all shadow-sm">
                Hapus Terpilih
            </button>
        </div>
    </div>
    @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500">
                        @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                        <th class="px-4 py-3 text-center w-12">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-slate-800 focus:ring-slate-800 cursor-pointer">
                        </th>
                        @endif
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">#</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">NIM</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">No. Telepon / WA</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-28">Angkatan</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-20">Semester</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-24">Status</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-slate-300 text-slate-800 focus:ring-slate-800 cursor-pointer" onclick="updateBulkDeleteUI()">
                        </td>
                        @endif
                        <td class="border-b border-slate-100 px-4 py-3 text-center text-sm text-slate-500">{{ $users->firstItem() ? $users->firstItem() + $index : $index + 1 }}</td>
                        <td class="border-b border-slate-100 px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-primary flex items-center justify-center flex-shrink-0 text-white shadow-sm">
                                    @if($user->avatar_url && !str_contains($user->avatar_url, 'ui-avatars'))
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-slate-800 block">{{ $user->name }}</span>
                                    @if(!empty($user->education['program_studi']))
                                        <span class="text-[11px] text-slate-400 block">{{ $user->education['program_studi'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-sm">
                            @if($user->nim)
                                <span class="font-mono text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200/60">{{ $user->nim }}</span>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-sm text-slate-500">
                            {{ strtolower($user->email) }}
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-sm">
                            @if($user->phone)
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 px-2 py-1 rounded-md transition-colors" title="Hubungi via WhatsApp">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>{{ $user->phone }}</span>
                                </a>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $user->angkatan ?? '-' }}
                            </span>
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $user->semester ?? '-' }}
                            </span>
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $user->status === 'AKTIF' || !$user->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->status ?? 'AKTIF' }}
                            </span>
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('backend.admin.users.show', $user) }}" class="p-1 text-slate-400 hover:text-slate-600 transition-colors" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('backend.admin.users.ktm', $user) }}" target="_blank" class="p-1 text-slate-400 hover:text-slate-600 transition-colors" title="Lihat & Cetak KTM">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                </a>
                                <a href="{{ route('backend.admin.users.edit', $user) }}" class="p-1 text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                                <form action="{{ route('backend.admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-gray-400 italic">Data mahasiswa tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 bg-white border-t border-gray-100 rounded-b-3xl">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function toggleImportDropdown() {
        const dropdown = document.getElementById('import-export-dropdown');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('import-export-dropdown');
        const button = event.target.closest('button[onclick="toggleImportDropdown()"]');
        if (dropdown && !button && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
    const selectAllBtn = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const actionContainer = document.getElementById('bulkActionContainer');
    const selectedCountText = document.getElementById('selectedCount');
    const form = document.getElementById('bulkDeleteForm');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteUI();
        });
    }

    function updateBulkDeleteUI() {
        const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
        if (checkedCount > 0) {
            actionContainer.classList.remove('hidden');
            selectedCountText.innerText = checkedCount + ' mahasiswa terpilih';
        } else {
            actionContainer.classList.add('hidden');
        }
        
        if (selectAllBtn) {
            selectAllBtn.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        }
    }

    function confirmBulkDelete() {
        if (confirm('PERINGATAN! Anda yakin ingin menghapus massal semua mahasiswa yang dipilih? Tindakan ini tidak dapat dibatalkan.')) {
            const container = document.getElementById('hiddenInputsContainer');
            container.innerHTML = ''; // Clear previous
            
            document.querySelectorAll('.user-checkbox:checked').forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            
            form.submit();
        }
    }
    @endif
</script>
@endsection
