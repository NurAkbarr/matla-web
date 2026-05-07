@extends('layouts.backend')

@section('title', 'Manajemen Afiliasi')
@section('breadcrumb', 'PMB > Afiliasi')

@section('content')
<div class="mb-6 md:mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Afiliasi</h1>
        <p class="text-gray-500 text-xs md:text-sm font-medium italic mt-1">Kelola program referral dan komisi pendaftaran</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('backend.admin.affiliates.commissions') }}" class="px-6 py-3 bg-white border border-gray-100 text-gray-600 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Data Komisi</span>
        </a>
        <button onclick="document.getElementById('addAffiliateModal').classList.remove('hidden')" class="px-6 py-3 bg-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Afiliator</span>
        </button>
    </div>
</div>

<!-- Stats Card (Optional) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-gray-50 shadow-sm flex items-center space-x-4">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Afiliator</p>
            <p class="text-2xl font-black text-gray-900">{{ $affiliates->total() }}</p>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white border border-gray-200 shadow-sm overflow-hidden rounded-none">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest w-16 text-center">No</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest">Afiliator</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest">Kode & Link</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Total Referral</th>
                    <th class="px-4 py-3 border-r border-gray-200 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Status</th>
                    <th class="px-4 py-3 text-[10px] font-black text-gray-600 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($affiliates as $index => $aff)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4 border-r border-gray-200 text-center text-xs font-bold text-gray-500">{{ $affiliates->firstItem() + $index }}</td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400 overflow-hidden shadow-sm border border-gray-200">
                                @if($aff->user)
                                    <img src="{{ $aff->user->avatar_url }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $aff->display_name }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                                    {{ $aff->user ? $aff->user->role : 'EKSTERNAL' }} • {{ $aff->display_whatsapp }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200">
                        <div class="flex flex-col space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-black text-primary bg-primary/5 px-2 py-0.5 rounded-none border border-primary/20">{{ $aff->affiliate_code }}</span>
                                <button onclick="copyLink('{{ $aff->link }}')" class="text-gray-400 hover:text-primary transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                </button>
                            </div>
                            <div class="text-[9px] text-gray-400 font-medium truncate max-w-[200px]">{{ $aff->link }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <div class="text-sm font-black text-gray-900">{{ $aff->registrations_count }}</div>
                        <div class="text-[9px] text-gray-400 font-bold uppercase">Pendaftar</div>
                    </td>
                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                        <form action="{{ route('backend.admin.affiliates.toggle', $aff) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-none border border-current text-[10px] font-black uppercase tracking-widest {{ $aff->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $aff->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('backend.admin.affiliates.destroy', $aff) }}" method="POST" onsubmit="return confirm('Hapus afiliator ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded transition-colors border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 font-medium italic">Belum ada data afiliator.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($affiliates->hasPages())
    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
        {{ $affiliates->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah Afiliator -->
<div id="addAffiliateModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('addAffiliateModal').classList.add('hidden')"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden" x-data="{ mode: 'internal', userId: '' }">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Tambah Afiliator</h3>
                <button onclick="document.getElementById('addAffiliateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('backend.admin.affiliates.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="flex p-1 bg-gray-100 rounded-2xl mb-2">
                    <button type="button" @click="mode = 'internal'" :class="mode == 'internal' ? 'bg-white shadow-sm' : ''" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">Internal (User)</button>
                    <button type="button" @click="mode = 'external'; userId = ''" :class="mode == 'external' ? 'bg-white shadow-sm' : ''" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">Eksternal</button>
                </div>

                <div class="space-y-2" x-show="mode == 'internal'">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Pilih User</label>
                    <select name="user_id" x-model="userId" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900 appearance-none">
                        <option value="">Pilih Mahasiswa/Dosen</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="mode == 'external'" class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" :required="mode == 'external'" placeholder="Masukkan nama afiliator" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                        <input type="tel" name="whatsapp_number" :required="mode == 'external'" placeholder="E.g. 0812..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Kode Referral</label>
                    <div class="flex gap-2">
                        <input type="text" id="affiliate_code_input" name="affiliate_code" required placeholder="E.g. MTA001" class="flex-1 px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900 uppercase">
                        <button type="button" onclick="generateRandomCode()" class="px-4 bg-gray-100 text-gray-600 rounded-2xl hover:bg-gray-200 transition-colors font-bold text-xs uppercase tracking-tight">Generate</button>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Nominal Komisi (Rp)</label>
                    <input type="number" name="commission_rate" required value="50000" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-black tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all transform active:scale-95">
                    Simpan Afiliator
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function copyLink(link) {
        navigator.clipboard.writeText(link).then(() => {
            alert('Link berhasil disalin!');
        });
    }

    function generateRandomCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = 'MTL'; // Prefix
        for (let i = 0; i < 4; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('affiliate_code_input').value = code;
    }
</script>
@endsection
