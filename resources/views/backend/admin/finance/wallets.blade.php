@extends('layouts.finance')

@section('title', 'Manajemen Dompet & Akun')
@section('header', 'Dompet / Akun (Wallets)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Form Tambah Wallet -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 uppercase tracking-wider">Tambah Dompet Baru</h3>
            
            <form action="{{ route('backend.admin.finance.wallets.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Nama Akun/Dompet</label>
                        <input type="text" name="name" required placeholder="Contoh: Rekening Mandiri" class="w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm font-medium">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Tipe</label>
                        <select name="type" required class="w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm font-medium">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="bank">Rekening Bank</option>
                            <option value="ewallet">E-Wallet (OVO, GoPay, dll)</option>
                            <option value="credit">Kartu Kredit / Paylater</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Saldo Awal (Rp)</label>
                        <input type="number" name="balance" required value="0" min="0" step="1" class="w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Ikon / Emoji <span class="text-slate-400 font-normal normal-case">(Opsional)</span></label>
                        <input type="text" name="icon" placeholder="🏦" maxlength="5" class="w-20 text-center border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-lg">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 transition-colors uppercase tracking-widest text-xs">
                            Simpan Dompet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Wallet -->
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            @forelse($wallets as $wallet)
            <div class="bg-white border border-slate-200 shadow-sm p-5 relative overflow-hidden group hover:border-emerald-500 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">{{ $wallet->icon }}</span>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ $wallet->name }}</h4>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">{{ $wallet->type }}</p>
                        </div>
                    </div>
                    
                    @if($wallet->transactions_count == 0)
                        <form action="{{ route('backend.admin.finance.wallets.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('Hapus dompet ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-500 hover:text-rose-700 p-1" title="Hapus Dompet">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="mt-4">
                    <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Saldo Saat Ini</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-2 bg-white border border-slate-200 shadow-sm p-8 text-center text-slate-500">
                Belum ada dompet/akun. Silakan tambahkan di sebelah kiri.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
