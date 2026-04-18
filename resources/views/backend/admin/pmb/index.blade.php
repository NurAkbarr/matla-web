@extends('layouts.backend')

@section('title', 'Daftar Pendaftar PMB')
@section('breadcrumb', 'PMB > Daftar Pendaftar')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pendaftar PMB</h1>
        <p class="text-gray-500 font-medium italic mt-1">Kelola dan verifikasi pendaftar baru</p>
    </div>

    <!-- Filter -->
    <form action="{{ route('backend.admin.pmb.registrations.index') }}" method="GET" class="flex items-center space-x-3">
        <select name="status" class="px-5 py-3 bg-white border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-bold text-xs text-gray-600">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="px-6 py-3 bg-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
            Filter
        </button>
    </form>
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
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Prodi</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tanggal Daftar</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
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
                        <div class="font-bold text-gray-900 text-sm mb-0.5 capitalize">{{ $reg->first_name }} {{ $reg->last_name }}</div>
                        <div class="text-[10px] text-gray-400 font-bold tracking-wide flex items-center space-x-1">
                            <span>{{ $reg->whatsapp_number }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">{{ $reg->study_program }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-xs text-gray-500 font-medium">
                        {{ $reg->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($reg->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-100 text-yellow-700">Pending</span>
                        @elseif($reg->status == 'verified')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">Terverifikasi</span>
                        @elseif($reg->status == 'accepted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">Diterima</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('backend.admin.pmb.registrations.show', $reg) }}" class="inline-flex p-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-xl transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <span class="text-sm font-medium">Belum ada data pendaftar.</span>
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
