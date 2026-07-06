@extends('layouts.finance')

@section('title', 'Manajemen Kategori')
@section('header', 'Kategori Transaksi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Form Tambah Kategori -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Kategori</h3>
            
            <form action="{{ route('backend.admin.finance.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Contoh: Belanja Bulanan" class="w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Arus Kas</label>
                        <select name="type" required class="w-full border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                            <option value="income">Pemasukan (Income)</option>
                            <option value="expense">Pengeluaran (Expense)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Ikon / Emoji <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="icon" placeholder="🛒" maxlength="5" class="w-20 text-center border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-lg">
                        <p class="text-xs text-slate-500 mt-1">Tekan 'Win + .' atau kontrol keyboard HP Anda untuk memasukkan emoji.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 transition-colors">
                            Simpan Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-center">Jml Transaksi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $category->icon }} <span class="ml-2">{{ $category->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->type === 'income')
                                <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">Pemasukan</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-rose-100 text-rose-800 text-xs font-bold uppercase tracking-wider">Pengeluaran</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center tabular-nums font-semibold">
                            {{ $category->transactions_count }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($category->transactions_count == 0)
                                <form action="{{ route('backend.admin.finance.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-xs uppercase tracking-wider">Hapus</button>
                                </form>
                            @else
                                <span class="text-slate-300 text-xs uppercase cursor-not-allowed" title="Kategori ini sudah dipakai di transaksi dan tidak bisa dihapus">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            Belum ada kategori. Silakan tambahkan kategori baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
