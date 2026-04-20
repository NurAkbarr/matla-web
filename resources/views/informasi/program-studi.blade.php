@extends('layouts.app')

@section('title', 'Program Studi - Matla Islamic University')
@section('meta_description', 'Temukan program akademik unggulan Matla Islamic University. Pilihan program studi S1, S2 berbasis nilai-nilai Islami.')

@section('content')
<style>
    @keyframes blob-bounce {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob-bounce 7s infinite alternate;
    }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .inner-glow {
        box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.5);
    }
    
    .prodi-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .prodi-card:hover { transform: translateY(-12px) scale(1.02); }
</style>

{{-- ========== HERO SECTION ========== --}}
<div class="relative min-h-[650px] flex items-center justify-center overflow-hidden bg-[#0A2E2B]">
    <!-- Animated Blobs -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] animate-blob animation-delay-4000"></div>

    <!-- Mesh Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center text-white">
        <!-- Badge -->
        <div class="inline-flex items-center space-x-3 px-5 py-2.5 bg-white/5 backdrop-blur-xl border border-white/10 rounded-full mb-10 hover:bg-white/10 transition-all cursor-default group">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs font-bold tracking-[0.2em] uppercase text-emerald-100/80">Kecemerlangan Akademik</span>
        </div>

        <!-- Hero Text -->
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black mb-8 leading-[1.1] tracking-tighter">
            Eksplorasi<br>
            <span class="bg-gradient-to-r from-emerald-300 via-teal-200 to-emerald-100 bg-clip-text text-transparent">Potensi Tanpa Batas</span>
        </h1>
        
        <p class="text-lg md:text-2xl text-emerald-50/70 max-w-3xl mx-auto leading-relaxed mb-16 font-medium">
            Wujudkan masa depan gemilang melalui kurikulum berbasis nilai-nilai Islami yang relevan dengan tantangan global era modern.
        </p>

        @if($programStudis->count() > 0)
        <div class="flex justify-center items-center gap-12 mb-16">
            <div class="text-center group">
                <p class="text-5xl font-black text-white group-hover:scale-110 transition-transform">{{ $programStudis->count() }}</p>
                <p class="text-[10px] text-emerald-300 font-black uppercase tracking-[0.3em] mt-2">Program Studi</p>
            </div>
            <div class="h-12 w-px bg-white/10"></div>
            <div class="text-center group">
                <p class="text-5xl font-black text-white group-hover:scale-110 transition-transform">{{ $programStudis->unique('jenjang')->count() }}</p>
                <p class="text-[10px] text-emerald-300 font-black uppercase tracking-[0.3em] mt-2">Jenjang Pendidikan</p>
            </div>
        </div>
        @endif

        <a href="#prodi-grid" class="inline-flex flex-col items-center gap-3 group">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/40 group-hover:text-white transition-colors">Scroll Eksplorasi</span>
            <div class="w-10 h-16 border-2 border-white/10 rounded-full flex justify-center p-2 relative overflow-hidden group-hover:border-emerald-400 transition-colors">
                <div class="w-1.5 h-3 bg-white rounded-full animate-bounce"></div>
            </div>
        </a>
    </div>
</div>

{{-- ========== PROGRAM STUDI SECTION ========== --}}
<section class="py-32 bg-[#F8FAFC] relative" id="prodi-grid">
    <div class="container mx-auto px-6 lg:px-16">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
            <div class="max-w-2xl">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                    Program Akademik<br><span class="text-primary italic">Unggulan Kami</span>
                </h2>
                <p class="text-gray-500 font-medium text-lg">Pilih jalur pendidikan yang dirancang khusus untuk membangun kompetensi teknis sekaligus integritas moral.</p>
            </div>

            @php 
                $jenjangList = $programStudis->pluck('jenjang')->unique()->sort()->values(); 
            @endphp

            @if($jenjangList->count() > 1)
                <div class="flex p-2 bg-white rounded-2xl border border-gray-100 shadow-sm glass-card">
                    <button onclick="filterProdi('all')" id="filter-all"
                        class="prodi-filter px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-primary text-white shadow-xl shadow-primary/20">
                        Semua
                    </button>
                    @foreach($jenjangList as $jenjang)
                    <button onclick="filterProdi('{{ $jenjang }}')" id="filter-{{ $jenjang }}"
                        class="prodi-filter px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-gray-400 hover:text-primary">
                        {{ $jenjang }}
                    </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($programStudis->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($programStudis as $prodi)
            <div class="prodi-card group relative bg-white rounded-[3rem] border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] hover:shadow-[0_40px_80px_rgba(5,150,105,0.12)] overflow-hidden"
                 data-jenjang="{{ $prodi->jenjang }}">
                
                <div class="p-10 relative z-10">
                    {{-- Category & Badge --}}
                    <div class="flex justify-between items-center mb-10">
                        <div class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $prodi->jenjang }} Program
                        </div>
                        @if($prodi->akreditasi)
                        <div class="flex items-center space-x-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span>{{ $prodi->akreditasi }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Icon Box --}}
                    <div class="relative w-20 h-20 mb-10 group-hover:rotate-[15deg] transition-transform duration-500">
                        <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full scale-150 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="w-full h-full bg-gradient-to-br from-primary/10 to-emerald-50 rounded-3xl flex items-center justify-center text-4xl shadow-inner relative z-10 transition-all border border-primary/5">
                            {{ $prodi->icon }}
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-gray-900 leading-tight mb-2 group-hover:text-primary transition-colors">
                            {{ $prodi->nama }}
                        </h3>
                        <div class="flex items-center space-x-3 text-gray-400">
                            <span class="w-8 h-px bg-gray-200"></span>
                            <span class="text-xs font-black uppercase tracking-[0.2em]">{{ $prodi->singkatan }}</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <p class="text-gray-500 leading-relaxed text-sm mb-12 line-clamp-3 font-medium">
                        {{ $prodi->deskripsi ?? 'Kurikulum komprehensif yang memadukan keunggulan akademik dengan pembentukan karakter Islami yang kokoh.' }}
                    </p>

                    {{-- Action --}}
                    <div class="flex items-center justify-between pt-8 border-t border-gray-50">
                        <span class="text-[10px] font-black uppercase tracking-widest text-primary/40 group-hover:text-primary transition-colors italic">Exploration Ready</span>
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center group-hover:bg-primary transition-all duration-300">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Decoration --}}
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            @endforeach
        </div>
        @else
        <div class="max-w-xl mx-auto text-center py-32 bg-white rounded-[4rem] border border-gray-100 shadow-sm">
            <div class="w-24 h-24 bg-emerald-50 rounded-[2.5rem] flex items-center justify-center text-5xl mx-auto mb-10 shadow-inner">🎓</div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Gerbang Akademik</h2>
            <p class="text-gray-400 font-medium px-8 leading-relaxed">Informasi kurikulum dan spesialisasi sedang kami persiapkan untuk memastikan akurasi data masa depan Anda.</p>
        </div>
        @endif
    </div>
</section>

{{-- ========== CTA SECTION ========== --}}
@if($programStudis->count() > 0)
<section class="py-32 bg-[#0A2E2B] relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
    <div class="container mx-auto px-6 lg:px-16 relative z-10">
        <div class="max-w-5xl mx-auto bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[4rem] p-12 md:p-20 text-center relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-primary rounded-3xl flex items-center justify-center text-3xl shadow-2xl shadow-primary/40 text-white font-bold">🎯</div>
            
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Tentukan Langkah Strategis<br>Karir Anda Hari Ini</h2>
            <p class="text-emerald-100/60 mb-12 text-lg max-w-2xl mx-auto font-medium leading-relaxed">Jangan tunda kesempatan untuk belajar di lingkungan yang mendukung pertumbuhan intelektual dan spiritual Anda.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="{{ route('pmb') }}" class="group relative px-12 py-5 bg-primary overflow-hidden rounded-2xl font-black text-xs uppercase tracking-[0.2em] text-white transition-all shadow-2xl shadow-primary/20 hover:scale-[1.05] active:scale-[0.98]">
                    <span class="relative z-10">Daftar Sekarang</span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </a>
                <a href="{{ route('kontak') }}" class="px-12 py-5 bg-white/5 border border-white/10 hover:bg-white/10 rounded-2xl font-black text-xs uppercase tracking-[0.2em] text-white transition-all">Konsultasi Studi</a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function filterProdi(jenjang) {
    const cards = document.querySelectorAll('.prodi-card');
    const filters = document.querySelectorAll('.prodi-filter');
    
    // Update active filter button
    filters.forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'shadow-xl', 'shadow-primary/20');
        btn.classList.add('text-gray-400');
    });
    const activeBtn = document.getElementById('filter-' + jenjang);
    if (activeBtn) {
        activeBtn.classList.add('bg-primary', 'text-white', 'shadow-xl', 'shadow-primary/20');
        activeBtn.classList.remove('text-gray-400');
    }

    // Filter cards with simple animation
    cards.forEach(card => {
        if (jenjang === 'all' || card.dataset.jenjang === jenjang) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, 50);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}
</script>
@endpush
