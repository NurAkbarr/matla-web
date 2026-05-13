@extends('layouts.backend')

@section('title', 'Tambah Pertemuan')

@section('breadcrumb', 'Akademik > Jadwal > Silabus > Tambah')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Tambah Pertemuan</h2>
        <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">{{ $jadwal->mata_kuliah }}</p>
    </div>
    <a href="{{ route('backend.admin.jadwal.pertemuan.index', $jadwal->id) }}" class="text-xs font-bold text-gray-400 hover:text-primary transition-colors flex items-center space-x-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Kembali</span>
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
    <form action="{{ route('backend.admin.jadwal.pertemuan.store', $jadwal->id) }}" method="POST" class="p-6 sm:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pertemuan Ke-</label>
                <input type="number" name="pertemuan_ke" min="1" max="16" value="{{ old('pertemuan_ke', $jadwal->pertemuans->count() + 1) }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe Pertemuan</label>
                <select name="tipe_pertemuan" id="tipe_pertemuan" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
                    <option value="video" {{ old('tipe_pertemuan') == 'video' ? 'selected' : '' }}>Video E-Learning (Asinkronus)</option>
                    <option value="zoom" {{ old('tipe_pertemuan') == 'zoom' ? 'selected' : '' }}>Live Zoom (Sinkronus)</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Judul Materi / Topik</label>
            <input type="text" name="judul_materi" value="{{ old('judul_materi') }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Contoh: Pengantar Ilmu Komunikasi" required>
        </div>

        <div class="mb-6">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Link Tautan (YouTube / Zoom)</label>
            <input type="url" name="link_url" value="{{ old('link_url') }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="https://...">
            <p class="text-[10px] text-gray-400 mt-2 font-medium">*Untuk tipe Video, gunakan link YouTube. Untuk tipe Zoom, gunakan link invitation Zoom.</p>
        </div>

        <div class="mb-6">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi / Instruksi</label>
            <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Penjelasan singkat mengenai pertemuan ini...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-8" id="soal_evaluasi_container">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Soal Evaluasi / Kuis (Wajib untuk Video)</label>
            <textarea name="soal_evaluasi" rows="3" class="w-full bg-indigo-50/50 border border-indigo-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Tuliskan pertanyaan yang harus dijawab mahasiswa setelah menonton video...">{{ old('soal_evaluasi') }}</textarea>
            <p class="text-[10px] text-indigo-400 mt-2 font-bold">*Mahasiswa baru bisa menjawab soal ini setelah menonton 50% durasi video.</p>
        </div>

        <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-6">
            <a href="{{ route('backend.admin.jadwal.pertemuan.index', $jadwal->id) }}" class="px-6 py-3 text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-widest">Batal</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                Simpan Pertemuan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.getElementById('tipe_pertemuan');
        const soalContainer = document.getElementById('soal_evaluasi_container');

        function toggleSoal() {
            if (tipeSelect.value === 'video') {
                soalContainer.style.display = 'block';
            } else {
                soalContainer.style.display = 'none';
            }
        }

        tipeSelect.addEventListener('change', toggleSoal);
        toggleSoal(); // initial check
    });
</script>
@endsection
