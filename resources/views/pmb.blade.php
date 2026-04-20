@extends('layouts.app')

@section('title', 'PMB 2026/2027 - Matla Islamic Academy')

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
                <span class="text-white text-sm font-semibold tracking-wide">{{ $settings['pmb_gelombang'] }}</span>
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
                Bekali Akhiratmu dengan <span class="text-emerald-400 underline decoration-emerald-400/30 underline-offset-8">Ilmu Syar'i</span>
            </h1>
            <p class="text-2xl md:text-3xl font-bold text-orange-400 mb-6 leading-snug">
                Bekali Duniamu dengan Skill Industri
            </p>
            
            <p class="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-2xl">
                Bergabunglah bersama MATLA untuk mempelajari ilmu syar'i secara mendalam sekaligus membekali diri dengan keterampilan yang siap digunakan di dunia kerja.
            </p>

            <!-- Countdown Timer -->
            @if($settings['pmb_is_open'] == '1')
            <div class="mb-12" id="pmb-countdown-container" data-end-date="{{ date('Y-m-d H:i:s', strtotime($settings['pmb_end_date'])) }}">
                <p class="text-emerald-400 font-bold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Batas Waktu Pendaftaran
                </p>
                <div class="flex space-x-3 md:space-x-5">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-days">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Hari</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-hours">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Jam</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-minutes">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Menit</span>
                    </div>
                </div>
            </div>
            @else
            {{-- Coming Soon Countdown --}}
            <div class="mb-12" id="pmb-coming-soon-container" data-open-date="{{ date('Y-m-d H:i:s', strtotime($settings['pmb_start_date'] ?? '2026-06-01 00:00:00')) }}">
                <p class="text-orange-400 font-bold mb-4 flex items-center text-sm uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm1 14.93V17a1 1 0 11-2 0v-.07A8.001 8.001 0 014.07 11H5a1 1 0 110 2h-.93A8.001 8.001 0 0111 19.93zm0-13.86V5a1 1 0 112 0v.07A8.001 8.001 0 0119.93 11H19a1 1 0 110-2h.93A8.001 8.001 0 0113 3.07zM12 8v4l2.5 2.5a1 1 0 01-1.414 1.414l-3-3A1 1 0 0110 12V8a1 1 0 012 0z"/>
                    </svg>
                    Pendaftaran Gelombang 1 Dibuka Dalam
                </p>
                <div class="flex space-x-3 md:space-x-5">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-days">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Hari</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-hours">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Jam</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-minutes">--</div>
                        <span class="text-gray-400 text-xs md:text-sm uppercase tracking-widest font-bold">Menit</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                @if($settings['pmb_is_open'] == '1')
                <a href="{{ $settings['pmb_registration_link'] }}" class="px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-lg shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center space-x-2">
                    <span>Daftar Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                @endif
                <a href="{{ $settings['pmb_status_link'] }}" class="px-8 py-4 bg-white/10 hover:bg-white border border-white/20 hover:text-primary-dark text-white rounded-xl font-bold text-lg transition-all flex items-center justify-center space-x-2 backdrop-blur-sm">
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
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Alur Pendaftaran</h2>
            <p class="text-lg text-gray-600">Proses pendaftaran mudah, cepat, dan bisa dilakukan dari mana saja.</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Step 1 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">1</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Isi Formulir Pendaftaran</h3>
                    <p class="text-gray-600 leading-relaxed">Calon mahasiswa mengisi formulir pendaftaran secara online melalui website PMB MATLA.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">2</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Upload Berkas</h3>
                    <p class="text-gray-600 leading-relaxed">Unggah dokumen yang dibutuhkan sesuai dengan ketentuan yang berlaku.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">3</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Pembayaran Biaya Pendaftaran</h3>
                    <p class="text-gray-600 leading-relaxed">Lakukan pembayaran biaya pendaftaran sesuai instruksi yang tersedia.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">4</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Verifikasi Data</h3>
                    <p class="text-gray-600 leading-relaxed">Tim PMB MATLA akan melakukan verifikasi data dan berkas yang telah dikirimkan.</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="flex items-start space-x-6 relative pb-8 border-l-2 border-emerald-100 ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">5</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Tes / Seleksi</h3>
                    <p class="text-gray-600 leading-relaxed">Mengikuti proses seleksi sesuai jadwal yang telah ditentukan.</p>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="flex items-start space-x-6 relative ml-4 lg:ml-8 pl-8">
                <div class="absolute -left-[17px] top-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-emerald-500/30">6</div>
                <div class="bg-gray-50 p-6 lg:p-8 rounded-3xl border border-gray-100 flex-1 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold text-primary mb-3">Pengumuman Hasil 🎉</h3>
                    <p class="text-gray-600 leading-relaxed">Hasil seleksi akan diumumkan melalui email dan WhatsApp yang terdaftar. Peserta yang dinyatakan lulus wajib bergabung ke grup kelas dan melunasi biaya administrasi sesuai ketentuan.</p>
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
                    <p>Beasiswa tersedia bagi <strong>yatim/piatu</strong> dan <strong>hafidz Al-Qur'an</strong>, berupa:</p>
                    <ul class="mt-2 space-y-1 list-disc list-inside">
                        <li>Pembebasan biaya pendaftaran</li>
                        <li>Potongan SPP sebesar 50%</li>
                    </ul>
                    <p class="mt-2 text-sm italic text-gray-500">* Beasiswa akan diberlakukan apabila jumlah pendaftar telah memenuhi kuota minimal 20 orang.</p>
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
<section class="py-24 bg-white overflow-hidden" id="brosur-pmb">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6">Brosur PMB 2026/2027</h2>
            <p class="text-lg text-gray-500 max-w-xl mx-auto italic">Temukan informasi lengkap mengenai program studi, beasiswa, dan kehidupan kampus kami.</p>
        </div>
        
        <div class="flex justify-center">
            @forelse($brosurs as $brosur)
                <div class="w-full max-w-2xl brochure-card-wrapper" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="brochure-card group relative bg-white rounded-[2.5rem] p-4 md:p-6 shadow-2xl border border-gray-100 transition-all duration-500 hover:shadow-primary/10 h-full flex flex-col cursor-pointer" 
                         data-file="{{ asset('pmb-brosur/' . $brosur->image) }}"
                         onclick="window.open(this.dataset.file, '_blank')">
                        
                        <!-- Frame - Natural Full Height, No Crop -->
                        <div class="relative bg-gray-50 rounded-[2rem] mb-8 border border-gray-100 overflow-hidden">
                            <img src="{{ asset('pmb-brosur/' . $brosur->image) }}" alt="{{ $brosur->title }}" class="w-full h-auto block transform group-hover:scale-[1.01] transition-transform duration-700">
                            
                            <!-- Overlay Glassmorphism -->
                            <div class="absolute inset-x-4 bottom-4 p-4 bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">
                                <p class="text-white text-xs font-bold uppercase tracking-widest text-center">{{ $brosur->title }}</p>
                            </div>

                            <!-- Decorative Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 flex flex-col px-2">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 truncate">{{ $brosur->title }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-6 flex-1">{{ $brosur->description ?? 'Informasi pendaftaran dan perkuliahan lengkap.' }}</p>
                            
                            <div class="mt-auto">
                                <a href="{{ asset('pmb-brosur/' . $brosur->image) }}" download 
                                   class="inline-flex items-center justify-center w-full px-6 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold transition-all shadow-lg shadow-primary/20 space-x-2 group/btn">
                                    <span>Download Gambar</span>
                                    <svg class="w-5 h-5 transform group-hover/btn:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Decorative glow -->
                        <div class="absolute -z-10 inset-0 bg-primary/5 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    </div>
                </div>
            @empty
                <div class="w-full max-w-2xl mx-auto aspect-[16/9] bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] flex flex-col items-center justify-center text-gray-400 p-8 space-y-4">
                    <div class="p-4 bg-white rounded-2xl shadow-sm">
                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="italic font-medium">Brosur pendaftaran belum tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
    .brochure-card {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @media (min-width: 1024px) {
        .brochure-card-wrapper {
            perspective: 1500px;
        }
        
        .brochure-card:hover {
            transform: translateY(-10px) rotateX(4deg) rotateY(4deg);
        }
    }

    [data-aos] {
        opacity: 0;
        transition-property: transform, opacity;
    }

    [data-aos="fade-up"] {
        transform: translateY(30px);
    }

    [data-aos].aos-animate {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                }
            });
        }, observerOptions);

        document.querySelectorAll('[data-aos]').forEach(el => {
            observer.observe(el);
        });
    });
</script>

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

        @if($settings['pmb_is_open'] == '1')
        <a href="{{ $settings['pmb_registration_link'] }}" class="inline-block px-10 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xl shadow-xl shadow-orange-500/20 transition-all transform hover:scale-105">
            Isi Formulir Pendaftaran
        </a>
        @else
        <div class="inline-block px-10 py-4 bg-red-500/20 text-red-200 border border-red-500/30 rounded-xl font-bold text-xl">
            Pendaftaran Saat Ini Ditutup
        </div>
        @endif

        <p class="mt-8 text-emerald-100/60 font-medium">
            Butuh bantuan? <a href="https://wa.me/your-number" target="_blank" class="text-emerald-400 hover:underline">Hubungi kami via WhatsApp</a>
        </p>
    </div>
</section>

@if($settings['pmb_is_open'] == '1')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('pmb-countdown-container');
        if (!container) return;

        const dateStr = container.getAttribute('data-end-date');
        const endDate = new Date(dateStr.replace(/-/g, '/')).getTime();

        const elDays = document.getElementById('cd-days');
        const elHours = document.getElementById('cd-hours');
        const elMinutes = document.getElementById('cd-minutes');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                elDays.innerText = "00";
                elHours.innerText = "00";
                elMinutes.innerText = "00";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            elDays.innerText = String(days).padStart(2, '0');
            elHours.innerText = String(hours).padStart(2, '0');
            elMinutes.innerText = String(minutes).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
</script>
@endif

{{-- Coming Soon Countdown Script (saat PMB belum dibuka) --}}
@if($settings['pmb_is_open'] != '1')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('pmb-coming-soon-container');
        if (!container) return;

        const openDateStr = container.getAttribute('data-open-date');
        const openDate = new Date(openDateStr.replace(/-/g, '/')).getTime();

        const elDays    = document.getElementById('cs-days');
        const elHours   = document.getElementById('cs-hours');
        const elMinutes = document.getElementById('cs-minutes');

        function updateComingSoon() {
            const now = new Date().getTime();
            const distance = openDate - now;

            if (distance <= 0) {
                elDays.innerText = '00';
                elHours.innerText = '00';
                elMinutes.innerText = '00';
                return;
            }

            elDays.innerText    = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
            elHours.innerText   = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            elMinutes.innerText = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        }

        updateComingSoon();
        setInterval(updateComingSoon, 1000);
    });
</script>
@endif
@endsection
