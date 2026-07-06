@extends('layouts.finance')

@section('title', 'Dashboard Keuangan')
@section('header', 'Financial Overview')

@section('content')
<div x-data="{ showModal: false }">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">Ringkasan Arus Kas</h1>
            <p class="text-slate-500 mt-1 text-sm font-medium">Pantau dan kelola target keuangan pribadi Anda.</p>
        </div>
        <button onclick="openModal()" class="bg-emerald-600 text-white px-5 py-2.5 font-bold hover:bg-emerald-700 transition-colors shadow-sm flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Catat Transaksi</span>
        </button>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-white p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-500 transition-colors">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <h3 class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-widest">Total Saldo Aktif</h3>
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-black text-slate-900 tabular-nums">Rp {{ number_format($activeBalance, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-indigo-500 transition-colors">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <h3 class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-widest">Pemasukan Bulan Ini</h3>
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-black text-slate-900 tabular-nums">Rp {{ number_format($monthIncome, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-rose-500 transition-colors">
            <div class="absolute right-0 top-0 w-24 h-24 bg-rose-50 -z-10 group-hover:scale-110 transition-transform"></div>
            <h3 class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-widest">Pengeluaran Bulan Ini</h3>
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-black text-slate-900 tabular-nums">Rp {{ number_format($monthExpense, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    <h2 class="text-lg font-bold text-slate-800 mb-4 uppercase tracking-wider">Histori Transaksi Terakhir</h2>
    
    @if($transactions->isEmpty())
        <div class="bg-white border border-slate-200 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Transaksi</h3>
            <p class="text-slate-500 max-w-md mx-auto text-sm">Anda belum mencatat transaksi keuangan apapun. Mulai catat sekarang menggunakan tombol di atas.</p>
        </div>
    @else
        <div class="bg-white border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-[#022c22] text-white uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center space-x-2">
                                <span>{{ $trx->category->icon }}</span>
                                <span class="font-bold text-slate-700">{{ $trx->category->name }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $trx->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-black tracking-tight text-base {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx->type == 'income' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modal Catat Transaksi -->
    <div id="transactionModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal()"></div>
        
        <div class="relative bg-white shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 transition-all transform scale-95 opacity-0 duration-300" id="transactionModalContent">
            <div class="px-6 py-5 border-b border-emerald-900/50 flex justify-between items-center bg-[#022c22] text-white">
                <h3 class="text-lg font-bold uppercase tracking-wider">Catat Transaksi</h3>
                <button onclick="closeModal()" type="button" class="text-slate-400 hover:text-white p-1 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('backend.admin.finance.store') }}" method="POST" class="p-6 bg-slate-50">
                @csrf
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Tanggal</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-sm font-medium">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Pilih Dompet / Akun</label>
                            <select name="finance_wallet_id" required class="w-full border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-sm font-medium">
                                <option value="">-- Pilih --</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->icon }} {{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Kategori</label>
                            <select name="finance_category_id" required class="w-full border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-sm font-medium">
                                <option value="">-- Pilih --</option>
                                <optgroup label="Pemasukan (Income)">
                                    @foreach($categories->where('type', 'income') as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Pengeluaran (Expense)">
                                    @foreach($categories->where('type', 'expense') as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Jumlah (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="amount" required min="1" step="1" placeholder="0" class="w-full pl-12 py-3 border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm font-black text-xl text-slate-900 tracking-tight">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Deskripsi / Catatan <span class="font-normal normal-case">(Opsional)</span></label>
                        <input type="text" name="description" placeholder="Makan siang sate padang..." class="w-full border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-sm">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 transition-colors uppercase tracking-widest text-sm">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- VanillaJS Modal Logic -->
<script>
    const modal = document.getElementById('transactionModal');
    const modalContent = document.getElementById('transactionModalContent');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
