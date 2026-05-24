@extends('layouts.backend')

@section('title', 'Pengaturan Tier Komisi')
@section('breadcrumb', 'PMB > Afiliasi > Tier Komisi')

@section('content')
<div class="mb-6 md:mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Tier Komisi</h1>
        <p class="text-gray-500 text-xs md:text-sm font-medium italic mt-1">Atur jenjang komisi afiliasi berdasarkan total pendaftar</p>
    </div>
    <a href="{{ route('backend.admin.affiliates.index') }}" class="px-6 py-3 bg-white border border-gray-100 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Kembali</span>
    </a>
</div>

<div class="bg-white border border-gray-200 shadow-sm overflow-hidden rounded-3xl p-6 md:p-10">
    <form action="{{ route('backend.admin.affiliates.tiers.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($tiers as $index => $tier)
            <div class="border border-gray-200 rounded-2xl p-6 bg-gray-50">
                <input type="hidden" name="tiers[{{ $index }}][id]" value="{{ $tier->id }}">
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Tier</label>
                        <input type="text" name="tiers[{{ $index }}][name]" required value="{{ $tier->name }}" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-gray-900">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Minimal Mahasiswa</label>
                        <input type="number" name="tiers[{{ $index }}][min_referrals]" required value="{{ $tier->min_referrals }}" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-gray-900">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nominal Komisi (Rp) / Mahasiswa</label>
                        <input type="number" name="tiers[{{ $index }}][commission_amount]" required value="{{ intval($tier->commission_amount) }}" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-gray-900">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="px-8 py-4 bg-primary text-white rounded-2xl font-black tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all transform active:scale-95">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
