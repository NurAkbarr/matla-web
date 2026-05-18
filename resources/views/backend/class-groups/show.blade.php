@extends('layouts.backend')

@section('title', 'Detail Kelompok Kelas')
@section('breadcrumb', 'Akademik / Kelompok Kelas / Detail')

@section('content')
<div class="space-y-6 animate-[fadeIn_0.4s_ease-out]" x-data="{ addStudentModal: false, moveStudentModal: false, selectedStudentId: '', selectedStudentName: '' }">
    <!-- Back Navigation and Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <a href="{{ route('backend.admin.kelompok-kelas.index') }}" class="w-10 h-10 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-primary rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">{{ $group->name }}</h1>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Daftar mahasiswa yang terdaftar di kelompok kelas ini</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 self-start sm:self-auto">
            <span class="inline-flex items-center px-3.5 py-1.5 bg-emerald-50 text-primary border border-primary/10 rounded-xl text-xs font-extrabold shadow-sm">
                {{ count($students) }} Mahasiswa Terdaftar
            </span>
            @if(Auth::user()->role === 'super_admin')
                <button @click="addStudentModal = true" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary/20 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Mahasiswa
                </button>
            @endif
        </div>
    </div>

    <!-- Class Details Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-[radial-gradient(circle_at_top_right,rgba(5,150,105,0.05),transparent_60%)] pointer-events-none"></div>
        
        <div class="space-y-1">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Program Studi</span>
            <span class="text-base font-bold text-slate-800 block">{{ $group->prodi ? $group->prodi->nama : 'Tidak Diketahui' }}</span>
            <span class="text-xs text-slate-400 font-semibold block">Jenjang {{ $group->prodi ? $group->prodi->jenjang : '-' }} – Akreditasi {{ $group->prodi ? $group->prodi->akreditasi : '-' }}</span>
        </div>

        <div class="space-y-1 md:border-l md:border-slate-100 md:pl-6">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Tahun Angkatan</span>
            <span class="text-base font-bold text-slate-800 block">Angkatan {{ $group->angkatan }}</span>
            <span class="text-xs text-slate-400 font-semibold block">Tahun Masuk / Kurikulum</span>
        </div>

        <div class="space-y-1 md:border-l md:border-slate-100 md:pl-6">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Metode Pembagian</span>
            <span class="text-base font-bold text-emerald-600 block">Sistem Otomatis Terintegrasi</span>
            <span class="text-xs text-slate-400 font-semibold block">Terstruktur dan bebas kesalahan manual</span>
        </div>
    </div>

    <!-- Students Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Daftar Mahasiswa Kelas</h3>
            <p class="text-xs text-slate-400 mt-0.5">Menampilkan seluruh nama, NIM, kontak, dan status keaktifan mahasiswa</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-16">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">No. Telepon / WA</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-32">Status Keaktifan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4.5 text-center text-sm font-semibold text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4.5">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary font-bold shadow-sm border border-primary/5">
                                    @if($student->avatar_url && !str_contains($student->avatar_url, 'ui-avatars'))
                                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-black">{{ substr($student->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block group-hover:text-primary transition-colors">
                                        {{ $student->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold block mt-0.5">
                                        Semester {{ $student->semester ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4.5">
                            <span class="text-sm font-bold text-slate-700 tracking-wider">
                                {{ $student->nim ?? 'Belum Diisi' }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5">
                            <span class="text-sm font-semibold text-slate-500">
                                {{ strtolower($student->email) }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5">
                            <span class="text-sm font-semibold text-slate-600">
                                {{ $student->phone ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-center">
                            @php
                                $status = strtoupper($student->status ?? 'AKTIF');
                                $colors = [
                                    'AKTIF' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'CUTI' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'KELUAR' => 'bg-red-50 text-red-700 border-red-100',
                                    'LULUS' => 'bg-blue-50 text-blue-700 border-blue-100',
                                ];
                                $badgeColor = $colors[$status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeColor }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('backend.admin.users.show', $student) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all" title="Detail Mahasiswa">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('backend.admin.users.ktm', $student) }}" target="_blank" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all" title="Cetak KTM">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </a>
                                @if(Auth::user()->role === 'super_admin')
                                    <button @click="moveStudentModal = true; selectedStudentId = '{{ $student->id }}'; selectedStudentName = '{{ $student->name }}'" class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-xl transition-all shadow-sm active:scale-95" title="Pindahkan Kelas">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </button>
                                @endif
                                <a href="{{ route('backend.admin.users.edit', $student) }}" class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-all" title="Edit Data">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="max-w-md mx-auto flex flex-col items-center justify-center space-y-3.5">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-700">Belum Ada Mahasiswa</h4>
                                    <p class="text-sm text-slate-400">Tidak ada mahasiswa dari angkatan {{ $group->angkatan }} yang terdaftar di Program Studi {{ $group->prodi ? $group->prodi->nama : '-' }}.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(Auth::user()->role === 'super_admin')
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Modal Tambah Mahasiswa -->
    <div x-show="addStudentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addStudentModal = false"></div>
        <div class="relative bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Tambah Mahasiswa ke Kelas</h3>
                <button @click="addStudentModal = false" class="p-1 text-slate-400 hover:text-rose-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('backend.admin.kelompok-kelas.add-student', $group) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="student_id" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Mahasiswa</label>
                    <select name="student_id" id="student_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-xl text-sm text-slate-700 font-medium transition-all outline-none">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($availableStudents as $availStudent)
                            <option value="{{ $availStudent->id }}">
                                {{ $availStudent->name }} (Angkatan: {{ $availStudent->angkatan ?? '-' }}, Prodi: {{ $availStudent->education['program_studi'] ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="addStudentModal = false" class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary/10">
                        Masukkan ke Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pindahkan Mahasiswa -->
    <div x-show="moveStudentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="moveStudentModal = false"></div>
        <div class="relative bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Pindahkan Mahasiswa</h3>
                <button @click="moveStudentModal = false" class="p-1 text-slate-400 hover:text-rose-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('backend.admin.kelompok-kelas.move-student', $group) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" :value="selectedStudentId">
                
                <div class="space-y-1 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Nama Mahasiswa</span>
                    <span class="text-sm font-bold text-slate-800" x-text="selectedStudentName"></span>
                </div>

                <div class="space-y-1.5">
                    <label for="target_class_group_id" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pilih Kelas Baru</label>
                    <select name="target_class_group_id" id="target_class_group_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-primary focus:bg-white rounded-xl text-sm text-slate-700 font-medium transition-all outline-none">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($allGroups as $g)
                            <option value="{{ $g->id }}">
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="moveStudentModal = false" class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/10">
                        Pindahkan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
