@extends('layouts.app')

@section('title', 'PMB 2024/2025 - Matla Islamic University')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[600px] lg:min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/mosque-pmb.png') }}" alt="PMB Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10 py-20 lg:py-32">
        <div class="max-w-3xl">
            <!-- Badge -->
            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-8">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-white text-sm font-semibold tracking-wide">PMB 2024/2025 - Gelombang 1</span>
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                Mulai Perjalanan Ilmu Anda <span class="text-emerald-400 underline decoration-emerald-400/30 underline-offset-8">Bersama Kami</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-2xl">
                Daftarkan diri Anda sekarang dan raih gelar S1 PAI resmi dengan kurikulum berbasis Al-Qur'an dan As-Sunnah. Pendaftaran terbatas!
            </p>

            <!-- Countdown Timer -->
            <div class="mb-12">
                <p class="text-emerald-400 font-bold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Batas Waktu Pendaftaran
                </p>
                <div class="flex space-x-3 md:space-x-5">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2">30</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Hari</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2">12</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Jam</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2">45</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Menit</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2">10</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Detik</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="#" class="px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-lg shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center space-x-2">
                    <span>Daftar Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="#" class="px-8 py-4 bg-white/10 hover:bg-white border border-white/20 hover:text-primary-dark text-white rounded-xl font-bold text-lg transition-all flex items-center justify-center space-x-2 backdrop-blur-sm">
                    <span>Cek Status</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Alur Pendaftaran Section -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-6">Alur Pendaftaran</h2>
            <p class="text-lg text-gray-600">Proses pendaftaran mudah, cepat, dan bisa dilakukan dari mana saja.</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Step 1 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">1</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Isi Formulir Online</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Lengkapi formulir pendaftaran secara online dengan data diri yang benar dan valid sesuai dokumen identitas resmi Anda.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">2</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Pembayaran Administrasi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Lakukan pembayaran biaya administrasi pendaftaran ke rekening yang tertera melalui transfer bank atau dompet digital.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">3</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Seleksi & Verifikasi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tim kami akan memverifikasi data dan form Anda dalam waktu 3x24 jam kerja. Status akan dikirimkan melalui email/WA.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-start space-x-6 relative ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">4</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Pengumuman & Diterima</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Hasil seleksi akan diumumkan melalui email dan WhatsApp yang Anda daftarkan. Selamat bergabung! 🎉
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-6">Pertanyaan Umum (FAQ)</h2>
            <p class="text-lg text-gray-600">Jawaban atas pertanyaan yang sering ditanyakan calon mahasiswa baru.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            <!-- FAQ item 1 -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden faq-item group">
                <button class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-700 hover:text-primary transition-colors faq-btn outline-none">
                    <span>Berapa biaya pendaftaran di Matla Islamic University?</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed hidden faq-content">
                    Biaya pendaftaran untuk mahasiswa baru adalah Rp 250.000, yang meliputi biaya pengisian formulir dan verifikasi dokumen administrasi.
                </div>
            </div>
            <!-- FAQ item 2 -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden faq-item group">
                <button class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-700 hover:text-primary transition-colors faq-btn outline-none">
                    <span>Apakah kuliah bisa dilakukan sambil bekerja?</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed hidden faq-content">
                    Tentu saja! Keunggulan Matla Islamic University adalah sistem pembelajaran online yang fleksibel, memungkinkan Anda untuk mengatur jadwal kuliah di sela-sela kesibukan bekerja.
                </div>
            </div>
            <!-- FAQ item 3 -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden faq-item group">
                <button class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-700 hover:text-primary transition-colors faq-btn outline-none">
                    <span>Berapa lama durasi studi program S1 PAI?</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed hidden faq-content">
                    Program S1 Pendidikan Agama Islam (PAI) dirancang untuk ditempuh dalam 8 semester atau sekitar 4 tahun, dengan kurikulum yang padat namun terarah.
                </div>
            </div>
            <!-- FAQ item 4 -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden faq-item group">
                <button class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-700 hover:text-primary transition-colors faq-btn outline-none">
                    <span>Gelar apa yang akan saya dapatkan setelah lulus?</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed hidden faq-content">
                    Setelah lulus, Anda akan menyandang gelar Sarjana Pendidikan (S.Pd) yang resmi dan diakui negara sesuai dengan akreditasi program studi kami.
                </div>
            </div>
            <!-- FAQ item 5 -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden faq-item group">
                <button class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-700 hover:text-primary transition-colors faq-btn outline-none">
                    <span>Apakah ada program beasiswa?</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-6 text-gray-600 leading-relaxed hidden faq-content">
                    Kami menawarkan berbagai jalur beasiswa, termasuk Beasiswa Prestasi, Beasiswa Tahfidz Al-Qur'an, dan Beasiswa Bantuan Pendidikan bagi yang membutuhkan.
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.faq-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const content = btn.nextElementSibling;
                    const icon = btn.querySelector('.faq-icon');
                    
                    // Toggle visibility
                    content.classList.toggle('hidden');
                    
                    // Toggle rotation
                    icon.classList.toggle('rotate-180');
                    
                    // Optional: Close other items
                    document.querySelectorAll('.faq-content').forEach(other => {
                        if (other !== content) {
                            other.classList.add('hidden');
                            other.previousElementSibling.querySelector('.faq-icon').classList.remove('rotate-180');
                        }
                    });
                });
            });
        </script>

    </div>
</section>

<!-- Brosur Section -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-12 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Brosur PMB 2024/2025</h2>
        <p class="text-gray-500 mb-12 max-w-2xl mx-auto">Temukan semua informasi lengkap seputar program pendidikan dan fasilitas kami.</p>
        
        <div class="max-w-2xl mx-auto aspect-[16/9] bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex items-center justify-center text-gray-400 italic">
            Brosur belum tersedia.
        </div>
    </div>
</section>

<!-- CTA Footer Section -->
<section class="py-20 bg-primary-dark relative overflow-hidden">
    <!-- Decorative background circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/20 rounded-full -translate-x-1/4 translate-y-1/4 blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-xl">
             <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Siap Memulai Perjalanan Anda?</h2>
        <p class="text-emerald-50/80 mb-10 max-w-2xl mx-auto text-lg leading-relaxed">
            Bergabunglah dengan ribuan mahasiswa Matla Islamic University dan mulailah perjalanan ilmu Anda sekarang juga.
        </p>

        <a href="#" class="inline-block px-10 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xl shadow-xl shadow-orange-500/20 transition-all transform hover:scale-105">
            Isi Formulir Pendaftaran
        </a>

        <p class="mt-8 text-emerald-100/60 font-medium">
            Butuh bantuan? <a href="https://wa.me/your-number" target="_blank" class="text-emerald-400 hover:underline">Hubungi kami via WhatsApp</a>
        </p>
    </div>
</section>
@endsection
