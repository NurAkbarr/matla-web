@extends('layouts.finance')

@section('title', 'Dashboard Keuangan')
@section('header', 'Financial Overview')

@section('content')
<div x-data="{ showModal: false }">
    <!-- Header & Illustration Section -->
    <div class="flex flex-col-reverse md:flex-row md:items-center justify-between mb-8 relative">
        <div class="mt-6 md:mt-0 z-10 md:w-1/2">
            <h2 class="text-slate-800 font-bold text-lg mb-1">Selamat Datang,</h2>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 mb-3 tracking-tight">Super Admin 👋</h1>
            <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-sm mb-6">
                Pantau dan kelola target keuangan pribadi Anda dengan mudah.
            </p>
            <button onclick="openModal()" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Transaksi</span>
            </button>
        </div>
        
        <!-- Wallet Illustration -->
        <div class="w-full md:w-1/2 flex justify-center md:justify-end relative -mx-4 md:mx-0">
            <!-- Subtle background glow -->
            <div class="absolute inset-0 bg-emerald-500/10 blur-[60px] rounded-full"></div>
            <img src="{{ asset('assets/wallet-illustration.png') }}" alt="Finance Illustration" class="w-64 md:w-80 h-auto object-contain relative z-10 drop-shadow-xl">
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        
        <!-- Total Saldo -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
            <div class="flex justify-between items-center mb-4 z-10">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Saldo Aktif</h3>
                </div>
                <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                </div>
            </div>
            <div class="z-10">
                <span class="text-2xl md:text-3xl font-black text-emerald-600 tabular-nums tracking-tight">Rp {{ number_format($activeBalance, 0, ',', '.') }}</span>
            </div>
            <!-- Sparkline background placeholder (SVG wave) -->
            <svg class="absolute bottom-0 left-0 w-full h-16 text-emerald-50 opacity-50 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"/>
                <path d="M0 100 C 30 50 60 40 100 80 L 100 100 Z" fill="none" stroke="#10b981" stroke-width="2" class="opacity-30"/>
            </svg>
        </div>

        <!-- Pemasukan -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
            <div class="flex justify-between items-center mb-4 z-10">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Pemasukan Bulan Ini</h3>
                </div>
                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <div class="z-10">
                <span class="text-2xl md:text-3xl font-black text-indigo-600 tabular-nums tracking-tight">Rp {{ number_format($monthIncome, 0, ',', '.') }}</span>
            </div>
            <!-- Sparkline background placeholder (SVG wave) -->
            <svg class="absolute bottom-0 left-0 w-full h-16 text-indigo-50 opacity-50 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100">
                <path d="M0 100 C 40 80 60 20 100 90 L 100 100 Z" fill="none" stroke="#6366f1" stroke-width="2" class="opacity-30"/>
                <path d="M0 100 C 40 80 60 20 100 90 L 100 100 Z" fill="currentColor"/>
            </svg>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
            <div class="flex justify-between items-center mb-4 z-10">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 border border-orange-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Pengeluaran Bulan Ini</h3>
                </div>
                <div class="w-6 h-6 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                </div>
            </div>
            <div class="z-10">
                <span class="text-2xl md:text-3xl font-black text-orange-600 tabular-nums tracking-tight">Rp {{ number_format($monthExpense, 0, ',', '.') }}</span>
            </div>
            <!-- Sparkline background placeholder (SVG wave) -->
            <svg class="absolute bottom-0 left-0 w-full h-16 text-orange-50 opacity-50 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100">
                <path d="M0 100 C 20 70 70 40 100 70 L 100 100 Z" fill="none" stroke="#f97316" stroke-width="2" class="opacity-30"/>
                <path d="M0 100 C 20 70 70 40 100 70 L 100 100 Z" fill="currentColor"/>
            </svg>
        </div>

    </div>

    <!-- Histori Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Histori Transaksi Terakhir</h2>
        <a href="#" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 flex items-center space-x-1 bg-slate-100 px-3 py-1.5 rounded-lg transition-colors">
            <span>Lihat Semua</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    
    @if($transactions->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] relative overflow-hidden">
            <div class="relative w-24 h-24 mx-auto mb-6">
                <div class="absolute inset-0 bg-emerald-50 rounded-3xl transform rotate-3"></div>
                <div class="absolute inset-0 bg-white rounded-3xl transform -rotate-3 border border-emerald-100 shadow-sm flex items-center justify-center">
                    <svg class="w-10 h-10 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <!-- Sparkles -->
                <svg class="absolute -top-2 -left-4 w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5z"/></svg>
                <svg class="absolute -bottom-2 -right-4 w-5 h-5 text-emerald-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5z"/></svg>
                <svg class="absolute top-8 -right-6 w-3 h-3 text-emerald-100" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0l2.5 9.5L24 12l-9.5 2.5L12 24l-2.5-9.5L0 12l9.5-2.5z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-2">Belum Ada Transaksi</h3>
            <p class="text-slate-500 max-w-sm mx-auto text-xs md:text-sm leading-relaxed">Anda belum mencatat transaksi keuangan apapun. Mulai catat sekarang menggunakan tombol di atas.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/50 text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-500">{{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center space-x-3 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 group-hover:border-slate-200 transition-colors">
                                <span class="text-lg">{{ $trx->category->icon }}</span>
                                <span class="font-bold text-slate-700 text-xs">{{ $trx->category->name }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $trx->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-black tracking-tight text-sm {{ $trx->type == 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
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
