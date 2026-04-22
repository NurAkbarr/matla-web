@extends('layouts.backend')

@section('title', 'Program Studi')
@section('breadcrumb', 'Akademik / Program Studi')

@section('content')
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-gray-900">Daftar Program Studi</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola semua program akademik universitas di sini.</p>
        </div>
        <a href="{{ route('backend.admin.program-studi.create') }}" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold transition-all flex items-center space-x-2 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah Program Studi</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Program Studi</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenjang</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Akreditasi</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($programStudis as $prodi)
                <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-8 py-5 text-sm text-gray-400 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-8 py-5">
                            <div>
                                <p class="font-bold text-gray-900 text-sm">{{ $prodi->nama }}</p>
                                <p class="text-xs text-gray-400 font-medium">{{ $prodi->singkatan }}</p>
                            </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                            {{ $prodi->jenjang }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                            @if($prodi->akreditasi == 'A' || $prodi->akreditasi == 'Unggul') bg-emerald-50 text-primary
                            @elseif($prodi->akreditasi == 'B' || $prodi->akreditasi == 'Baik Sekali') bg-blue-50 text-blue-600
                            @else bg-amber-50 text-amber-600
                            @endif">
                            {{ $prodi->akreditasi }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        @if($prodi->is_active)
                            <span class="px-3 py-1 bg-emerald-50 text-primary rounded-lg text-[10px] font-black uppercase tracking-widest">Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-black uppercase tracking-widest">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center space-x-2">
                            <a href="{{ route('backend.admin.program-studi.edit', $prodi) }}" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('backend.admin.program-studi.destroy', $prodi) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Program Studi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center space-y-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-3xl">📚</div>
                            <p class="text-gray-400 font-medium text-sm">Belum ada program studi. <a href="{{ route('backend.admin.program-studi.create') }}" class="text-primary font-bold hover:underline">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
