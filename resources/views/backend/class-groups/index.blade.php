@extends('layouts.backend')

@section('title', 'Kelompok Kelas')
@section('breadcrumb', 'Akademik / Kelompok Kelas')

@section('content')
<div class="space-y-6 animate-[fadeIn_0.4s_ease-out]">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-primary to-emerald-800 rounded-2xl p-6 md:p-8 text-white shadow-xl shadow-primary/10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_50%)]"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Kelompok Kelas</h1>
                <p class="text-emerald-100 text-sm font-medium max-w-xl">
                    Sistem otomatisasi pembagian kelas berdasarkan kombinasi Program Studi dan Angkatan Mahasiswa secara real-time dan terintegrasi.
                </p>
            </div>
            
            <!-- Sync Action Form -->
            <form action="{{ route('backend.admin.kelompok-kelas.sync') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" class="w-full md:w-auto px-6 py-3.5 bg-white text-primary hover:bg-emerald-50 active:scale-95 text-sm font-bold uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center gap-2 group">
                    <svg class="w-5 h-5 text-primary group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.25" />
                    </svg>
                    <span>Sinkronkan Sekarang</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Alert / Toast Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center space-x-3 shadow-sm animate-[slideIn_0.3s_ease-out]">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Kelompok Kelas</h3>
                <p class="text-xs text-slate-400 mt-0.5">Menampilkan seluruh kelompok kelas yang terdaftar aktif</p>
            </div>
            <span class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-bold border border-slate-100">
                Total: {{ $groups->total() }} Grup
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-16">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Kelompok Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Program Studi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-40">Angkatan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-40">Jumlah Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($groups as $index => $group)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4.5 text-center text-sm font-semibold text-slate-400">
                            {{ ($groups->currentPage() - 1) * $groups->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4.5">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary/10 to-emerald-100 border border-primary/10 flex items-center justify-center text-primary font-bold shadow-sm group-hover:scale-105 transition-transform duration-250">
                                    <span class="text-sm tracking-tight">{{ $group->prodi ? $group->prodi->singkatan : 'K' }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block group-hover:text-primary transition-colors">
                                        {{ $group->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium block uppercase mt-0.5 tracking-wider">
                                        ID: CLG-{{ str_pad($group->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4.5">
                            <span class="text-sm font-semibold text-slate-600 block">
                                {{ $group->prodi ? $group->prodi->nama : 'Tidak Diketahui' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">
                                Jenjang {{ $group->prodi ? $group->prodi->jenjang : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-center">
                            <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">
                                {{ $group->angkatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-center">
                            <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-primary border border-primary/10 rounded-lg text-xs font-extrabold">
                                {{ $group->students_count }} Mahasiswa
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-right">
                            <a href="{{ route('backend.admin.kelompok-kelas.show', $group) }}" class="inline-flex items-center px-3 py-2 bg-white text-slate-700 hover:text-primary border border-slate-200 hover:border-primary rounded-xl text-xs font-bold transition-all shadow-sm gap-1.5 hover:shadow-md hover:shadow-primary/5 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Lihat Mahasiswa</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="max-w-md mx-auto flex flex-col items-center justify-center space-y-4">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-700">Belum Ada Kelompok Kelas</h4>
                                    <p class="text-sm text-slate-400 mt-1">Silakan sinkronisasikan kelompok kelas untuk memproses data dari database mahasiswa.</p>
                                </div>
                                <form action="{{ route('backend.admin.kelompok-kelas.sync') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-primary/20">
                                        Mulai Sinkronisasi
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($groups->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl">
            {{ $groups->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-15px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>
@endsection
