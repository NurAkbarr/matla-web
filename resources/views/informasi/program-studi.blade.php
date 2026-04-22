@extends('layouts.app')

@section('title', 'Program Studi - Matla Islamic University')
@section('meta_description', 'Temukan program akademik unggulan Matla Islamic University. Pilihan program studi S1, S2 berbasis nilai-nilai Islami.')

@section('content')
<style>
    .prodi-card { 
        transition: all 0.3s ease;
        border-top: 4px solid transparent;
    }
    .prodi-card:hover { 
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-top-color: #059669; /* primary green */
    }
    .jenjang-tag {
        position: absolute;
        top: 0;
        left: 0;
        padding: 4px 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #fbbf24; /* yellow like UNS */
        color: #000;
        border-bottom-right-radius: 8px;
        z-index: 20;
    }
</style>

{{-- ========== HERO SECTION ========== --}}
<div class="pt-32 pb-20 bg-[#0A2E2B] relative overflow-hidden">
    <!-- Subtle Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container mx-auto px-6 lg:px-16 relative z-10">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight uppercase">
                Program Studi
            </h1>
            <div class="w-20 h-1.5 bg-emerald-500 mb-8"></div>
            <p class="text-lg md:text-xl text-emerald-50/80 max-w-2xl leading-relaxed font-medium">
                Daftar program pendidikan unggulan di Matla Islamic University yang dirancang untuk mencetak lulusan kompeten dengan integritas moral yang tinggi.
            </p>
        </div>
    </div>
</div>

{{-- ========== PROGRAM STUDI SECTION ========== --}}
<section class="py-20 bg-white" id="prodi-grid">
    <div class="container mx-auto px-6 lg:px-16">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-16 gap-8 border-b border-gray-100 pb-8">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                Daftar Program Studi
            </h2>

            @php 
                $jenjangList = $programStudis->pluck('jenjang')->unique()->sort()->values(); 
            @endphp

            @if($jenjangList->count() > 1)
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterProdi('all')" id="filter-all"
                        class="prodi-filter px-6 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all bg-primary text-white shadow-lg shadow-primary/10">
                        Semua
                    </button>
                    @foreach($jenjangList as $jenjang)
                    <button onclick="filterProdi('{{ $jenjang }}')" id="filter-{{ $jenjang }}"
                        class="prodi-filter px-6 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all text-gray-400 border border-gray-100 hover:border-primary hover:text-primary">
                        {{ $jenjang }}
                    </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($programStudis->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($programStudis as $prodi)
            <div class="prodi-card group relative bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col h-full"
                 data-jenjang="{{ $prodi->jenjang }}">
                
                {{-- UNS style yellow tag --}}
                <div class="jenjang-tag">
                    {{ $prodi->jenjang }}
                </div>

                <div class="p-8 flex-1 flex flex-col">
                    {{-- Header: Name & Singkatan --}}
                    <div class="mb-6 mt-4">
                        <h3 class="text-xl font-black text-gray-900 leading-tight mb-2 group-hover:text-primary transition-colors min-h-[3rem] flex items-center">
                            {{ $prodi->nama }}
                        </h3>
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600">{{ $prodi->singkatan }}</p>
                    </div>

                    {{-- Description --}}
                    <p class="text-gray-500 leading-relaxed text-sm mb-8 line-clamp-3 font-medium flex-1">
                        {{ $prodi->deskripsi ?? 'Kurikulum komprehensif yang memadukan keunggulan akademik dengan pembentukan karakter Islami.' }}
                    </p>


                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="max-w-xl mx-auto text-center py-20 bg-gray-50 rounded-3xl border border-gray-100">
            <h2 class="text-2xl font-black text-gray-900 mb-4 uppercase">Data Belum Tersedia</h2>
            <p class="text-gray-400 font-medium px-8 leading-relaxed">Informasi program studi sedang diperbarui oleh bagian akademik.</p>
        </div>
        @endif
    </div>
</section>

{{-- ========== CTA SECTION ========== --}}
@if($programStudis->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-16">
        <div class="bg-[#0A2E2B] rounded-3xl p-12 md:p-16 text-center relative overflow-hidden">
            <!-- Subtle Decorative Element -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            
            <h2 class="text-3xl md:text-4xl font-black text-white mb-6 uppercase tracking-tight">
                Mulai Perjalanan Akademik Anda
            </h2>
            <p class="text-emerald-100/60 mb-10 text-lg max-w-2xl mx-auto font-medium leading-relaxed">
                Bergabunglah dengan komunitas akademik Matla Islamic University dan kembangkan potensi diri Anda dalam lingkungan yang Islami dan inovatif.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                <a href="{{ route('pmb') }}" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-emerald-900/20">
                    Daftar Sekarang
                </a>
                <a href="{{ route('kontak') }}" class="px-10 py-4 bg-transparent border border-white/20 hover:bg-white/5 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all">
                    Hubungi Kami
                </a>
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
