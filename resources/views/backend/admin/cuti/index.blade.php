@extends('layouts.backend')
 
@section('title', 'Daftar Pengajuan Cuti - Admin')
 
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Daftar Pengajuan Cuti</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola permohonan cuti akademik mahasiswa.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
 
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Prodi / Smt</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Semester Cuti</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cutiRequests as $cuti)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                {{ $cuti->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <p class="text-sm font-bold text-slate-800">{{ $cuti->user->name ?? 'User Dihapus' }}</p>
                                <p class="text-xs text-slate-500">{{ $cuti->user->nim ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <p class="text-sm text-slate-700 font-medium">{{ $cuti->user->education['program_studi'] ?? '-' }}</p>
                                <p class="text-xs text-slate-500">Smt: {{ $cuti->user->semester ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-slate-700 font-bold">
                                Semester {{ $cuti->semester_diajukan }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($cuti->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px] font-bold">Disetujui</span>
                                @elseif($cuti->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-700 border border-rose-100 text-[11px] font-bold">Ditolak</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-100 text-[11px] font-bold">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('backend.admin.cuti.show', $cuti->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 text-slate-600 rounded-lg text-xs font-bold transition-colors">
                                        Lihat Detail
                                    </a>
                                    @if(Auth::user()->role === 'super_admin')
                                        <form action="{{ route('backend.admin.cuti.destroy', $cuti->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data cuti ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-rose-200 hover:bg-rose-50 hover:text-rose-700 text-rose-600 rounded-lg text-xs font-bold transition-colors" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-slate-500 text-sm font-medium">Belum ada data pengajuan cuti.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $cutiRequests->links() }}
        </div>
    </div>
</div>
@endsection
