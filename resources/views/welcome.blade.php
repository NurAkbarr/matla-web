@extends('layouts.app')

@section('content')
@php
    $currentQuickInfos = $quickInfos ?? collect();
    $infoCount = $currentQuickInfos->count();
    $translateX = "calc(-260px * $infoCount - 1rem * $infoCount)";
@endphp

<style>
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX({{ $translateX }}); }
    }

    .animate-scroll {
        animation: scroll 60s linear infinite;
        display: flex;
        width: max-content;
    }

    .info-ticker-container:hover .animate-scroll,
    .info-ticker-container.is-dragging .animate-scroll {
        animation-play-state: paused;
    }

    .info-ticker-container {
        mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        cursor: grab;
        user-select: none;
        touch-action: pan-y;
    }
    
    .info-ticker-container:active {
        cursor: grabbing;
    }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<!-- Hero Section (Beranda) -->
<section id="beranda" class="relative min-h-[600px] lg:min-h-screen flex items-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/bg-web.png') }}" alt="MATLA Background" class="w-full h-full object-cover">
        <!-- Subtle Gradient Overlay for readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-white/70 via-white/40 to-transparent"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10 pt-12 lg:pt-20 pb-20 lg:pb-32">
        <div class="max-w-2xl">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1F2937] leading-[1.1] tracking-tight">
                Jadi Tholibul Ilmi yang Menguasai 
                <span class="block text-primary mt-2">Bahasa Arab, Ilmu Syar’i & Technologi</span>
            </h2>
            
            <h3 class="mt-6 text-lg lg:text-xl font-bold text-[#374151] leading-relaxed">
                Program kuliah S1 resmi dengan pembelajaran online terstruktur: Bahasa Arab intensif, ilmu syar’i, dan skill digital
            </h3>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6">
                <div class="flex items-center space-x-3">
                    <span class="text-base">🔸</span>
                    <span class="text-gray-700 font-bold text-sm lg:text-base tracking-tight">S1 resmi</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-base">🔸</span>
                    <span class="text-gray-700 font-bold text-sm lg:text-base tracking-tight">Bahasa Arab dari nol sampai advance</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-base">🔸</span>
                    <span class="text-gray-700 font-bold text-sm lg:text-base tracking-tight">Kurikulum syar'i dan terarah</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-base">🔸</span>
                    <span class="text-gray-700 font-bold text-sm lg:text-base tracking-tight">Flexible & online</span>
                </div>
            </div>

            <div class="mt-8 lg:mt-10 flex flex-col gap-8">
                @if(\App\Models\Setting::get_value('pmb_is_open') == '1')
                <div>
                    <a href="{{ route('pmb') }}" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-lg font-bold shadow-lg shadow-emerald-600/20 transition-all group w-full sm:w-fit">
                        <span>Daftar Sekarang</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                @endif

                <!-- QUICK INFORMATION TICKER -->
                <div class="relative w-full max-w-4xl">
                    <div class="flex items-center space-x-2 mb-4 px-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Quick Information</span>
                    </div>
                    
                    <div class="info-ticker-container overflow-x-auto scrollbar-hide relative py-4">
                        <div class="flex animate-scroll w-max gap-4 px-4 hover:pause-scroll">
                            {{-- Loop twice for infinite effect --}}
                            @for ($i = 0; $i < 2; $i++)
                                @foreach($currentQuickInfos as $item)
                                <a href="{{ $item->link ?? '#' }}" class="flex items-center justify-center px-8 py-4 bg-emerald-600 text-white border border-emerald-700 rounded-none shadow-sm hover:shadow-xl hover:bg-emerald-700 hover:-translate-y-1 transition-all w-[220px] shrink-0 group">
                                    <span class="text-[11px] font-black uppercase tracking-[0.2em] leading-tight text-center">{{ $item->label }}</span>
                                </a>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Section -->
<section id="tentang" class="relative min-h-[600px] flex items-center overflow-hidden bg-white py-20 lg:py-32">
    <!-- Background Image (Mirrored/Consistent) -->
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('assets/bg-web.png') }}" alt="MATLA Background" class="w-full h-full object-cover scale-x-[-1]">
        <div class="absolute inset-0 bg-white/60"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10">
        <div class="max-w-3xl">
            <h2 class="text-3xl lg:text-5xl font-extrabold text-[#1F2937] leading-tight tracking-tight">
                Tentang Matla Islamic University
            </h2>
            
            <p class="mt-6 lg:mt-8 text-lg lg:text-xl text-[#374151] leading-relaxed">
                Matla Islamic University adalah Kampus Islam Online terdepan yang menyediakan program kuliah Bahasa Arab dan S1 Pendidikan Agama Islam yang fleksibel dan mudah diakses.
            </p>

            <div class="mt-10 lg:mt-12 space-y-5 lg:space-y-6">
                <!-- Benefit Item 1 -->
                <div class="flex items-start space-x-4">
                    <div class="mt-1 bg-emerald-100 p-1.5 rounded-full shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-base lg:text-lg text-[#374151]">
                        Pembelajaran sistematis <span class="font-bold">sesuai dengan Kurikulum Islami</span> yang terintegrasi
                    </p>
                </div>

                <!-- Benefit Item 2 -->
                <div class="flex items-start space-x-4">
                    <div class="mt-1 bg-emerald-100 p-1.5 rounded-full shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-base lg:text-lg text-[#374151]">
                        Dosen-dosen <span class="font-bold">berpengalaman</span> lulusan Timur Tengah
                    </p>
                </div>

                <!-- Benefit Item 3 -->
                <div class="flex items-start space-x-4">
                    <div class="mt-1 bg-emerald-100 p-1.5 rounded-full shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-base lg:text-lg text-[#374151]">
                        Membangun <span class="font-bold">karakter Islami</span> dengan pendekatan menyeluruh
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan Section -->
<section id="keunggulan" class="bg-gray-50 py-20 lg:py-32">
    <div class="container mx-auto px-4 lg:px-12 relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-20">
            <h2 class="text-3xl lg:text-5xl font-extrabold text-[#1F2937] leading-tight tracking-tight px-4">
                Apa yang Membuat Matla Berbeda?
            </h2>
            <p class="mt-4 lg:mt-6 text-base lg:text-lg text-gray-600 px-4 whitespace-normal">
                Keunggulan program pendidikan kami yang dirancang untuk melahirkan generasi tangguh.
            </p>
        </div>

        <!-- Cards Grid / Slider -->
        <div class="relative group">
            <!-- Mobile Navigation Arrows -->
            <div class="flex lg:hidden items-center justify-between absolute top-1/2 -inset-x-2 sm:-inset-x-4 -translate-y-1/2 z-20 pointer-events-none">
                <button id="prev-btn" class="pointer-events-auto p-2 bg-white shadow-md rounded-full text-gray-700 hover:text-primary transition-all disabled:opacity-0 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="next-btn" class="pointer-events-auto p-2 bg-white shadow-md rounded-full text-gray-700 hover:text-primary transition-all disabled:opacity-0 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <div id="slider-container" class="flex flex-nowrap lg:grid lg:grid-cols-3 gap-6 lg:gap-8 overflow-x-auto lg:overflow-x-visible snap-x snap-mandatory scroll-smooth scrollbar-hide pb-4 lg:pb-0 w-full">
                <!-- Card 1 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Target Terukur & Sistematis</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Kurikulum disusun bertahap dengan target penguasaan 500 kosa kata per semester dan kemampuan membaca kitab dalam 2 tahun.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Gelar Resmi S1 PAI (S.Pd)</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Lulus dengan gelar akademik resmi S.Pd melalui program S1 Pendidikan Agama Islam yang terstruktur dan terarah.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Berbasis Al-Qur'an dan As-Sunnah</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Materi pembelajaran disusun berdasarkan dalil yang shahih dan pemahaman yang lurus sesuai tuntunan para urama.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Pembekalan Softskill & Hardskill</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Mahasiswa dibekali keahlian era digital: Public Speaking, Data Analis, Meta Ads, Programming, Desain, hingga Manajemen.
                    </p>
                </div>

                <!-- Card 5 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Sistem Online Fleksibel</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Bisa diikuti dari mana saja melalui genggaman dengan sistem pembelajaran e-learning yang terstruktur.
                    </p>
                </div>

                <!-- Card 6 -->
                <div class="flex-none w-full lg:w-auto snap-center bg-white p-7 lg:p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group min-w-0 overflow-hidden">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-4 break-words">Pembinaan Karakter & Mentoring</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-normal break-words">
                        Bukan sekadar kuliah, tetapi mahasiswa dibina secara personal dalam hal adab, kedisiplinan, dan kesiapan berkontribusi.
                    </p>
                </div>
            </div>
            
            <!-- Mobile Pagination Dots -->
            <div id="slider-dots" class="flex lg:hidden justify-center space-x-2 mt-6">
                <!-- Dots generated by JS -->
            </div>
        </div>
    </div>

    <!-- Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('slider-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const dotsContainer = document.getElementById('slider-dots');
            const cards = container.querySelectorAll('. snap-center');

            // Generate dots
            cards.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.className = `h-2 rounded-full transition-all duration-300 ${i === 0 ? 'w-8 bg-primary' : 'w-2 bg-gray-300'}`;
                dotsContainer.appendChild(dot);
            });

            const dots = dotsContainer.querySelectorAll('div');

            const updateControls = () => {
                const scrollLeft = container.scrollLeft;
                const width = container.offsetWidth;
                const activeIndex = Math.round(scrollLeft / width);

                prevBtn.disabled = scrollLeft <= 10;
                nextBtn.disabled = scrollLeft + width >= container.scrollWidth - 10;

                dots.forEach((dot, i) => {
                    if (i === activeIndex) {
                        dot.classList.add('w-8', 'bg-primary');
                        dot.classList.remove('w-2', 'bg-gray-300');
                    } else {
                        dot.classList.remove('w-8', 'bg-primary');
                        dot.classList.add('w-2', 'bg-gray-300');
                    }
                });
            };

            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -container.offsetWidth, behavior: 'smooth' });
            });

            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: container.offsetWidth, behavior: 'smooth' });
            });

            container.addEventListener('scroll', updateControls);
            window.addEventListener('resize', updateControls);
            updateControls();
        });
    </script>
</section>

@if(count($brosurs) > 0 && \App\Models\Setting::get_value('pmb_is_open') == '1')
<!-- PMB Announcement Popup -->
<div id="pmb-popup" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-500">
    <div class="relative w-full max-w-lg transform scale-90 transition-all duration-500 shadow-2xl" id="pmb-popup-content">
        
        <!-- Popup Carousel Container -->
        <div class="bg-white rounded-xl p-1 relative overflow-hidden">
            <!-- Close Button (Di dalam kotak, kecil) -->
            <button onclick="closePmbPopup()" class="absolute top-2 right-2 z-[10001] bg-black/20 hover:bg-black/40 text-white transition-all p-1.5 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flex transition-transform duration-500 ease-in-out" id="popup-track">
                @foreach($brosurs as $brosur)
                <div class="w-full flex-shrink-0">
                    <div class="relative rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ asset('pmb-brosur/' . $brosur->image) }}" alt="Brosur" class="w-full h-auto max-h-[85vh] object-contain block mx-auto">
                    </div>
                </div>
                @endforeach
            </div>

            @if(count($brosurs) > 1)
            <!-- Dots Indicator Mini -->
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1 bg-black/10 backdrop-blur-sm px-2 py-1 rounded-full" id="popup-dots">
                @foreach($brosurs as $i => $b)
                <div class="popup-dot w-1 h-1 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white w-3' : 'bg-white/30' }}"></div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Hint Text -->
        <p class="text-white/40 text-center text-[10px] mt-3 italic">Klik area luar untuk menutup</p>
    </div>
</div>

<script>
    let popupCurrentSlide = 0;
    const popupTotalSlides = {{ count($brosurs) }};
    const popupTrack = document.getElementById('popup-track');
    const popupDots = document.querySelectorAll('.popup-dot');
    let popupInterval;

    function showPmbPopup() {
        const popup = document.getElementById('pmb-popup');
        const content = document.getElementById('pmb-popup-content');
        
        popup.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-90');
        content.classList.add('scale-100');

        if (popupTotalSlides > 1) {
            popupInterval = setInterval(nextPopupSlide, 4000);
        }
    }

    function nextPopupSlide() {
        popupCurrentSlide = (popupCurrentSlide + 1) % popupTotalSlides;
        if (popupTrack) popupTrack.style.transform = `translateX(-${popupCurrentSlide * 100}%)`;
        popupDots.forEach((d, i) => {
            d.classList.toggle('bg-white', i === popupCurrentSlide);
            d.classList.toggle('w-3', i === popupCurrentSlide);
            d.classList.toggle('bg-white/40', i !== popupCurrentSlide);
            d.classList.toggle('w-1', i !== popupCurrentSlide);
        });
    }

    function closePmbPopup() {
        const popup = document.getElementById('pmb-popup');
        const content = document.getElementById('pmb-popup-content');
        
        popup.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-90');
        
        clearInterval(popupInterval);
        sessionStorage.setItem('pmb_popup_shown', 'true');
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!sessionStorage.getItem('pmb_popup_shown')) {
            setTimeout(showPmbPopup, 800);
        }
    });

    document.getElementById('pmb-popup').addEventListener('click', function(e) {
        if (e.target === this) closePmbPopup();
    });
    // ===== TICKER DRAG-TO-SCROLL =====
    const slider = document.querySelector('.info-ticker-container');
    let isDown = false;
    let startX;
    let scrollLeft;

    if (slider) {
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('is-dragging');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('is-dragging');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('is-dragging');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; 
            slider.scrollLeft = scrollLeft - walk;
        });

        // Mobile touch support
        slider.addEventListener('touchstart', () => {
            slider.classList.add('is-dragging');
        }, {passive: true});

        slider.addEventListener('touchend', () => {
            slider.classList.remove('is-dragging');
        }, {passive: true});
    }
</script>
@endif
@endsection

