@extends('layouts.dosen')

@section('title', 'Penilaian Mata Kuliah')

@section('content')
<div class="max-w-7xl mx-auto" x-data="gradingSystem()">
    <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                {{ $jadwal->mata_kuliah }}
            </h1>
            <div class="flex items-center gap-4">
                <p class="text-sm font-bold text-gray-400 italic">Input & Kelola Nilai Mahasiswa</p>
                <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">{{ $jadwal->sks }} SKS</span>
            </div>
        </div>
        
        <a href="{{ route('backend.dosen.nilai.index') }}" class="group px-6 py-3 bg-white border border-gray-100 rounded-2xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary hover:border-primary transition-all flex items-center space-x-2">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="mb-8 p-5 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-3xl flex items-center space-x-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="w-8 h-8 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Control Center (Compact Action Row) -->
    <div class="flex flex-wrap items-center gap-2 mb-8 animate-in fade-in slide-in-from-left-4 duration-700">
        <button @click="showSettings = true" class="flex items-center space-x-2 px-5 py-2.5 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-primary hover:text-primary transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span>Pengaturan</span>
        </button>

        <a href="{{ route('backend.dosen.nilai.history', $jadwal) }}" class="flex items-center space-x-2 px-5 py-2.5 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-primary hover:text-primary transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Riwayat</span>
        </a>

        <a href="{{ route('backend.dosen.nilai.export', $jadwal) }}" class="flex items-center space-x-2 px-5 py-2.5 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-primary hover:text-primary transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
            <span>Export CSV</span>
        </a>

        <div class="h-8 w-px bg-gray-100 mx-2"></div>

        <button @click="confirmLock()" class="flex items-center space-x-2 px-5 py-2.5 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-amber-500 hover:text-amber-500 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            <span>Publikasikan</span>
        </button>

        <button @click="submitGrades()" class="flex items-center space-x-2 px-6 py-2.5 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
            <span>Simpan Draf</span>
        </button>
    </div>

    <!-- Grading Table -->
    @if($komponens->count() > 0)
    <form id="gradingForm" action="{{ route('backend.dosen.nilai.store', $jadwal) }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-primary px-8 py-4 border-b border-primary-dark">
                <h3 class="text-xs font-black text-white uppercase tracking-widest">Daftar Penilaian Mahasiswa</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary/95 text-white">
                            <th class="border border-white/10 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-center w-12">No</th>
                            <th class="border border-white/10 px-6 py-4 text-[10px] font-black uppercase tracking-widest">Nama Mahasiswa</th>
                            <th class="border border-white/10 px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest w-24">SMT</th>
                            
                            @foreach($komponens as $comp)
                            <th class="border border-white/10 px-6 py-4 text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest block">{{ $comp->nama_komponen }}</span>
                                <span class="text-[8px] font-bold opacity-70 italic">Bobot {{ $comp->bobot }}%</span>
                            </th>
                            @endforeach

                            <th class="border border-white/10 px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest w-24 bg-primary-dark">Total</th>
                            <th class="border border-white/10 px-6 py-4 text-center text-[10px] font-black uppercase tracking-widest w-20 bg-primary-dark">Mutu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($participants as $index => $mhs)
                        @php 
                            $nilai = $nilais->get($mhs->id);
                            $isLocked = $nilai?->is_locked ?? false;
                            $dataSkor = $nilai?->data_skor ?? [];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors" x-init="initRow({{ $mhs->id }}, {{ json_encode($dataSkor) }})">
                            <td class="border border-gray-100 px-6 py-3 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="border border-gray-100 px-6 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 leading-none mb-1 capitalize">{{ $mhs->name }}</span>
                                    <span class="text-[9px] font-medium text-gray-400 lowercase italic">{{ $mhs->email }}</span>
                                </div>
                            </td>
                            <td class="border border-gray-100 px-6 py-3 text-center">
                                <span class="text-xs font-bold text-gray-500">{{ $mhs->semester ?? '-' }}</span>
                            </td>

                            @foreach($komponens as $comp)
                            <td class="border border-gray-100 px-6 py-3 text-center">
                                <input type="number" step="0.01" max="100" min="0" 
                                       name="scores[{{ $mhs->id }}][{{ $comp->id }}]" 
                                       x-model.number="rows[{{ $mhs->id }}].scores[{{ $comp->id }}]"
                                       @input="calculateRow({{ $mhs->id }})"
                                       {{ $isLocked ? 'disabled' : '' }}
                                       class="w-16 h-10 bg-gray-50/50 border border-gray-100 rounded-lg text-center text-sm font-bold focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all disabled:bg-gray-100 disabled:text-gray-300"
                                       placeholder="0">
                            </td>
                            @endforeach

                            <td class="border border-gray-100 px-6 py-3 text-center bg-gray-50/30">
                                <span class="text-sm font-black text-gray-900" x-text="rows[{{ $mhs->id }}].total"></span>
                            </td>
                            <td class="border border-gray-100 px-6 py-3 text-center bg-gray-50/30">
                                <span class="text-sm font-black text-primary" x-text="rows[{{ $mhs->id }}].huruf"></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white rounded-[3rem] p-24 text-center border-2 border-dashed border-gray-100">
        <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Konfigurasi Penilaian Diperlukan</h3>
        <p class="text-sm font-bold text-gray-400 mb-10 max-w-sm mx-auto italic">Tentukan komponen nilai (Tugas, UTS, UAS, dll) untuk mulai melakukan penilaian.</p>
        <button @click="showSettings = true" class="px-10 py-4 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all">
            Mulai Konfigurasi
        </button>
    </div>
    @endif

    <!-- Settings Modal -->
    <div x-show="showSettings" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-white/80 backdrop-blur-xl">
        
        <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-12">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Pengaturan Bobot</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Konfigurasi Komponen Nilai</p>
                    </div>
                    <button @click="showSettings = false" class="p-4 hover:bg-gray-50 rounded-2xl transition-colors">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <form action="{{ route('backend.dosen.nilai.settings', $jadwal) }}" method="POST">
                    @csrf
                    <div class="space-y-4 mb-10 max-h-[40vh] overflow-y-auto custom-scrollbar">
                        <template x-for="(comp, index) in settings" :key="index">
                            <div class="flex items-center gap-3 animate-in fade-in slide-in-from-right-2 duration-300">
                                <div class="flex-1">
                                    <input type="text" :name="'komponens['+index+'][nama]'" x-model="comp.nama" 
                                           placeholder="Kategori Nama"
                                           class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl font-bold text-sm focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all">
                                </div>
                                <div class="w-24">
                                    <div class="relative">
                                        <input type="number" :name="'komponens['+index+'][bobot]'" x-model.number="comp.bobot"
                                               class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl font-bold text-sm focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-center pr-8">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300">%</span>
                                    </div>
                                </div>
                                <button type="button" @click="removeComponent(index)" class="p-4 text-gray-200 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="flex items-center justify-between mb-10 py-6 border-y border-gray-50">
                        <button type="button" @click="addComponent()" class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center space-x-2 px-4 py-2 bg-primary/10 rounded-xl hover:bg-primary/20 transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            <span>Tambah Baris</span>
                        </button>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest leading-none mb-2">Akumulasi Bobot</p>
                            <span class="text-3xl font-black tabular-nums" :class="totalBobot == 100 ? 'text-primary' : 'text-red-500'" x-text="totalBobot + '%'"></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="totalBobot != 100" class="w-full py-6 bg-[#1a1a1a] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-3xl shadow-xl shadow-black/10 disabled:opacity-30 disabled:shadow-none hover:bg-primary hover:shadow-primary/20 transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="lockForm" action="{{ route('backend.dosen.nilai.lock', $jadwal) }}" method="POST" class="hidden">@csrf</form>

<script>
function gradingSystem() {
    return {
        showSettings: false,
        komponens: {!! json_encode($komponens->map(fn($c) => ['id' => $c->id, 'nama' => $c->nama_komponen, 'bobot' => $c->bobot])) !!},
        settings: [],
        rows: {},
        
        init() {
            if (this.komponens.length > 0) {
                this.settings = JSON.parse(JSON.stringify(this.komponens));
            } else {
                this.settings = [
                    { nama: 'Hadir', bobot: 10 },
                    { nama: 'Tugas', bobot: 20 },
                    { nama: 'UTS', bobot: 35 },
                    { nama: 'UAS', bobot: 35 }
                ];
            }
        },

        get totalBobot() {
            return this.settings.reduce((sum, item) => sum + (parseInt(item.bobot) || 0), 0);
        },

        addComponent() {
            this.settings.push({ nama: '', bobot: 0 });
        },

        removeComponent(index) {
            this.settings.splice(index, 1);
        },

        initRow(id, existingScores) {
            this.rows[id] = { scores: existingScores || {}, total: 0, huruf: '-' };
            this.calculateRow(id);
        },

        calculateRow(id) {
            const row = this.rows[id];
            let total = 0;
            this.komponens.forEach(comp => {
                const score = parseFloat(row.scores[comp.id]) || 0;
                total += (score * comp.bobot / 100);
            });
            row.total = total.toFixed(2);
            row.huruf = this.getLetterGrade(total);
        },

        getLetterGrade(score) {
            if (score >= 80) return 'A';
            if (score >= 75) return 'B+';
            if (score >= 70) return 'B';
            if (score >= 65) return 'C+';
            if (score >= 60) return 'C';
            if (score >= 50) return 'D';
            return 'E';
        },

        submitGrades() {
            document.getElementById('gradingForm').submit();
        },

        confirmLock() {
            if (confirm('Konfirmasi: Kunci nilai sekarang? Data tidak dapat diubah setelah dipublikasikan.')) {
                document.getElementById('lockForm').submit();
            }
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>
@endsection
