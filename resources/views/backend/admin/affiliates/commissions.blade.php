@extends('layouts.backend')

@section('title', 'Data Komisi Afiliasi')
@section('breadcrumb', 'PMB > Afiliasi > Komisi')

@section('content')
<div class="mb-6 md:mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Data Komisi Afiliasi</h1>
        <p class="text-gray-500 text-xs md:text-sm font-medium italic mt-1">Daftar komisi dari pendaftar yang direferensikan</p>
    </div>
    <a href="{{ route('backend.admin.affiliates.index') }}" class="px-6 py-3 bg-white border border-gray-100 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali</span>
    </a>
</div>

<!-- Table -->
<div class="bg-white border border-gray-200 shadow-sm overflow-hidden rounded-none">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest w-16 text-center">No</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest">Afiliator</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest">Pendaftar & WA</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Nominal</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Status</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Tgl Cair</th>
                    <th class="px-4 py-3 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($commissions as $index => $com)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4 border-r border-gray-200 text-center text-xs font-bold text-gray-500">{{ $commissions->firstItem() + $index }}</td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $com->affiliate->display_name }}</div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Kode: {{ $com->affiliate->affiliate_code }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="font-bold text-gray-700 text-xs mb-0.5">{{ $com->registration->full_name }}</div>
                        <div class="text-[9px] text-primary font-black uppercase tracking-widest mb-1">{{ $com->registration->registration_code }}</div>
                        <div class="flex items-center text-[10px] text-emerald-600 font-bold">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>{{ $com->registration->whatsapp_number }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <div class="text-sm font-black text-gray-900">Rp {{ number_format($com->amount, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        @if($com->status == 'paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-none border border-emerald-500 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest">Lunas</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-none border border-amber-500 bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-widest">Siap Cair</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <div class="text-xs font-bold text-gray-500">{{ $com->paid_at ? $com->paid_at->format('d/m/Y') : '-' }}</div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($com->status != 'paid')
                            <form action="{{ route('backend.admin.affiliates.commissions.pay', $com) }}" method="POST" onsubmit="return confirm('Tandai sebagai sudah dibayar?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded hover:bg-emerald-700 transition-colors shadow-sm">
                                    Bayar
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-400 font-medium italic border-b border-gray-200">Belum ada data komisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commissions->hasPages())
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        {{ $commissions->links() }}
    </div>
    @endif
</div>
@endsection
