@extends('layouts.backend')

@section('title', 'Kotak Sampah PMB')
@section('breadcrumb', 'PMB > Daftar Pendaftar > Kotak Sampah')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-primary mb-3 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Utama
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kotak Sampah PMB</h1>
        <p class="text-gray-500 font-medium italic mt-1">Data pendaftar yang dihapus sementara (Soft Delete)</p>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16 text-center">No</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Registrasi</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Dihapus Pada</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($registrations as $index => $reg)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-center text-xs font-bold text-gray-400">{{ $registrations->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-black text-primary">{{ $reg->registration_code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 text-sm mb-0.5 capitalize">{{ $reg->full_name }}</div>
                        <div class="text-[10px] text-gray-400 font-bold tracking-wide flex items-center space-x-1">
                            <span>{{ $reg->study_program }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500 font-medium">
                        {{ $reg->deleted_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Restore -->
                            <form action="{{ route('backend.admin.pmb.registrations.restore', $reg->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" title="Pulihkan Data">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Pulihkan
                                </button>
                            </form>

                            <!-- Permanent Delete -->
                            <form action="{{ route('backend.admin.pmb.registrations.forceDelete', $reg->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Data ini akan dihapus secara PERMANEN dari sistem dan tidak bisa dikembalikan lagi. Anda yakin?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" title="Hapus Permanen">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-4 text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="text-sm font-medium">Kotak sampah kosong.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registrations->hasPages())
    <div class="p-6 border-t border-gray-100 bg-gray-50/50">
        {{ $registrations->links() }}
    </div>
    @endif
</div>
@endsection
