@extends('layouts.backend')

@section('title', 'Quick Info Ticker')
@section('breadcrumb', 'Manajemen Website > Quick Info')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
    <div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Quick Info Ticker</h1>
        <p class="text-sm text-gray-500 font-bold italic mt-1">Kelola tombol informasi yang berjalan di halaman depan</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah -->
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-none border border-gray-100 shadow-sm sticky top-24">
            <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-widest border-b pb-4">Tambah Tombol</h2>
            
            <form action="{{ route('backend.admin.quick-infos.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Label Tombol</label>
                    <input type="text" name="label" required placeholder="Contoh: Kurikulum S1" 
                           class="w-full px-4 py-3 rounded-none border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-sm">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Link Tujuan (Opsional)</label>
                    <input type="text" name="link" placeholder="Contoh: https://... atau #" 
                           class="w-full px-4 py-3 rounded-none border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-sm">
                </div>

                <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-emerald-600/20 active:scale-95">
                    Simpan Tombol
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Tombol -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-none border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">Daftar Tombol Aktif</h3>
                <span class="px-3 py-1 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest">{{ $items->count() }} Tombol</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16">Urutan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-gray-400">#{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-900 text-sm uppercase tracking-wider">{{ $item->label }}</div>
                                <div class="text-[10px] text-emerald-600 font-bold truncate max-w-[200px] mt-0.5">{{ $item->link }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editItem({{ $item->id }}, '{{ $item->label }}', '{{ $item->link }}')" 
                                            class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all rounded-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <form action="{{ route('backend.admin.quick-infos.destroy', $item->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus tombol ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all rounded-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <p class="text-gray-400 font-bold italic text-xs">Belum ada tombol informasi.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit (Simple Hidden Form) -->
<div id="edit-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[999] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md p-8 rounded-none border border-white">
        <h3 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-widest border-b pb-4">Edit Tombol</h3>
        
        <form id="edit-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Label Tombol</label>
                <input type="text" name="label" id="edit-label" required 
                       class="w-full px-4 py-3 rounded-none border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-sm">
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Link Tujuan</label>
                <input type="text" name="link" id="edit-link" 
                       class="w-full px-4 py-3 rounded-none border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-sm">
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black uppercase tracking-widest text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-emerald-600/20">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editItem(id, label, link) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        const inputLabel = document.getElementById('edit-label');
        const inputLink = document.getElementById('edit-link');
        
        form.action = `/backend/admin/quick-infos/${id}`;
        inputLabel.value = label;
        inputLink.value = link;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
