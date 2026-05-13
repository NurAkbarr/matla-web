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
                                    <h4 class="text-xs font-black text-blue-800 uppercase tracking-widest mb-2">Instruksi Format CSV:</h4>
                                    <p class="text-sm text-blue-700 leading-relaxed">
                                        Pastikan file berformat <strong>.csv</strong> dan memiliki kolom <em>(header tidak wajib tapi disarankan)</em> pada urutan berikut:<br>
                                        <strong class="text-xs leading-relaxed">1: NIM, 2: Nama, 3: Email, 4: Prodi, 5: Jenis Kelamin, 6: Status</strong><br><br>
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..." 
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

    @if(Auth::user()->role === 'super_admin')
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
                        @if(Auth::user()->role === 'super_admin')
                        <th class="px-4 py-3 text-center w-12">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-slate-800 focus:ring-slate-800 cursor-pointer">
                        </th>
                        @endif
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">#</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-32">Angkatan</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-center w-24">Status</th>
                        <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        @if(Auth::user()->role === 'super_admin')
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-slate-300 text-slate-800 focus:ring-slate-800 cursor-pointer" onclick="updateBulkDeleteUI()">
                        </td>
                        @endif
                        <td class="border-b border-slate-100 px-4 py-3 text-center text-sm text-slate-500">{{ $index + 1 }}</td>
                        <td class="border-b border-slate-100 px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-primary flex items-center justify-center flex-shrink-0 text-white shadow-sm">
                                    @if($user->avatar_url && !str_contains($user->avatar_url, 'ui-avatars'))
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold text-slate-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-sm text-slate-500">
                            {{ strtolower($user->email) }}
                        </td>
                        <td class="border-b border-slate-100 px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $user->angkatan ?? '-' }}
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
                                @if($user->qr_token)
                                <a href="{{ route('backend.admin.users.ktm', $user) }}" target="_blank" class="p-1 text-slate-400 hover:text-slate-600 transition-colors" title="Lihat & Cetak KTM">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                </a>
                                @endif
                                <a href="{{ route('backend.admin.users.edit', $user) }}" class="p-1 text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('backend.admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
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
        @if($users->hasPages())
        <div class="px-6 py-4 bg-white border-t border-gray-100 rounded-b-3xl">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@if(Auth::user()->role === 'super_admin')
<script>
    const selectAllBtn = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const actionContainer = document.getElementById('bulkActionContainer');
    const selectedCountText = document.getElementById('selectedCount');
    const form = document.getElementById('bulkDeleteForm');

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
</script>
@endif
@endsection
