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
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Pendaftar</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Tier Aktif</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-right">Total Hak Komisi</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-right">Sudah Dicairkan</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-right">Sisa Belum Cair</th>
                    <th class="px-4 py-3 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($affiliates as $index => $aff)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4 border-r border-gray-200 text-center text-xs font-bold text-gray-500">{{ $affiliates->firstItem() + $index }}</td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $aff->display_name }}</div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Kode: {{ $aff->affiliate_code }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <div class="font-bold text-gray-900 text-lg">{{ $aff->registrations_count }}</div>
                        <div class="text-[9px] text-gray-400 uppercase tracking-widest">Mahasiswa</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <div class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-xl text-[10px] font-black uppercase tracking-widest border border-primary/20">
                            {{ $aff->tier_name }}
                        </div>
                        <div class="text-[9px] text-gray-400 font-bold mt-1">@ Rp {{ number_format($aff->tier_rate, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-right">
                        <div class="text-sm font-black text-gray-900">Rp {{ number_format($aff->total_earned, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-right">
                        <div class="text-sm font-black text-emerald-600">Rp {{ number_format($aff->total_paid, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-right">
                        <div class="text-sm font-black {{ $aff->unpaid_balance > 0 ? 'text-amber-600' : 'text-gray-400' }}">Rp {{ number_format($aff->unpaid_balance, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($aff->unpaid_balance > 0)
                            <form action="{{ route('backend.admin.affiliates.commissions.pay_balance', $aff) }}" method="POST" onsubmit="return confirm('Bayar sisa komisi sebesar Rp {{ number_format($aff->unpaid_balance, 0, ',', '.') }}?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-colors shadow-sm whitespace-nowrap">
                                    Cairkan Sisa
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                Lunas
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400 font-medium italic border-b border-gray-200">Belum ada afiliator yang mendapatkan komisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($affiliates->hasPages())
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        {{ $affiliates->links() }}
    </div>
    @endif
</div>
@endsection
