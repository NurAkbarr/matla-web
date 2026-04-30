@extends('layouts.app')

@section('content')
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
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1F2937] leading-[1.1] tracking-tight">
                Kuliah Bahasa Arab & S1 Resmi: Tholibul Ilmi yang Paham
                <span class="block text-primary mt-2">Bahasa Arab, Ilmu Syar'i, dan Teknologi</span>
            </h2>
            
            <h3 class="mt-6 text-xl lg:text-2xl font-bold text-[#374151]">
                Jadilah bagian dari Matla Islamic Academy. Tempat di mana adab, ilmu syar'i, dan kreativitas digital tumbuh bersama dalam harmoni.
            </h3>

            <p class="mt-6 text-base lg:text-lg text-gray-600 leading-relaxed max-w-xl font-medium">
                Dapatkan gelar S.Pd resmi dengan kurikulum terstruktur. Target fasih Bahasa Arab dalam 2 tahun melalui penguasaan 500 kosakata per semester, serta pembekalan wajib softskill dan hardskill seperti Public Speaking, Programming, Desain Grafis, dan lainnya.
            </p>

            <div class="mt-8 lg:mt-10">
                <a href="#pmb" class="inline-block px-8 py-3.5 lg:py-4 bg-primary hover:bg-primary-dark text-white rounded-lg text-lg font-bold shadow-lg shadow-primary/20 transition-all text-center w-full sm:w-fit">
                    Daftar Sekarang
                </a>
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
@endsection

