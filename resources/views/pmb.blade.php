@extends('layouts.app')

@section('title', 'PMB 2026/2027 - Matla Islamic Academy')

@section('content')
<div x-data="{ showModal: false }">
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
                <button @click="showModal = true" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-600/20 transition-all flex items-center justify-center space-x-2 mr-4 group">
                    <span>Daftar Sekarang</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
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

<!-- Selection Modal -->
<div x-show="showModal" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="showModal = false"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-200" @click.stop>
        
        <button @click="showModal = false" class="absolute top-6 right-6 p-4 text-gray-400 hover:text-gray-900 transition-colors z-[110] cursor-pointer" type="button">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="px-8 pt-12 pb-6 text-center border-b border-gray-50">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 font-sans tracking-tight">Pilih Program Studi</h2>
            <p class="text-gray-500 text-sm">Tentukan program pendidikan yang ingin Anda ikuti di MATLA</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <!-- PAI Option -->
            <a href="{{ $settings['pmb_registration_link'] }}?type=pai" class="group p-8 md:p-10 hover:bg-emerald-50/40 transition-all duration-300 flex flex-col h-full text-left">
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 group-hover:text-emerald-700 transition-colors">S1 PAI</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-8 flex-1">Program Sarjana Pendidikan Agama Islam dengan kurikulum lengkap dan terarah.</p>
                
                <div class="inline-flex items-center text-emerald-600 font-bold group-hover:translate-x-1 transition-transform">
                    <span>Mulai Pendaftaran</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>

            <!-- IDAD Option -->
            <a href="{{ $settings['pmb_registration_link'] }}?type=idad" class="group p-8 md:p-10 hover:bg-emerald-50/40 transition-all duration-300 flex flex-col h-full text-left">
                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 group-hover:text-emerald-700 transition-colors">I'dad Lughowi</h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-8 flex-1">Program intensif penguasaan Bahasa Arab bagi yang ingin memperdalam dasar-dasar bahasa Al-Qur'an.</p>
                
                <div class="inline-flex items-center text-emerald-600 font-bold group-hover:translate-x-1 transition-transform">
                    <span>Mulai Pendaftaran</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
        </div>

        <div class="bg-gray-50 px-8 py-4 text-center">
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">Matla Islamic Academy &bull; PMB 2026/2027</p>
        </div>
    </div>
</div>

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
<section class="py-24 bg-gray-50" id="faq">
    <div class="container mx-auto px-4 lg:px-12" x-data="{ activeTab: 'umum' }">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-6">Pertanyaan Umum (FAQ)</h2>
            <p class="text-lg text-gray-600">Jawaban atas pertanyaan yang sering ditanyakan calon mahasiswa baru.</p>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button @click="activeTab = 'umum'" :class="{'bg-primary text-white shadow-md': activeTab === 'umum', 'bg-white text-gray-600 hover:bg-emerald-50': activeTab !== 'umum'}" class="px-6 py-3 rounded-full font-bold transition-all border border-gray-100 flex items-center gap-2">
                🏢 Informasi Umum
            </button>
            <button @click="activeTab = 'sistem'" :class="{'bg-primary text-white shadow-md': activeTab === 'sistem', 'bg-white text-gray-600 hover:bg-emerald-50': activeTab !== 'sistem'}" class="px-6 py-3 rounded-full font-bold transition-all border border-gray-100 flex items-center gap-2">
                📚 Sistem & Metode Kuliah
            </button>
            <button @click="activeTab = 'prodi'" :class="{'bg-primary text-white shadow-md': activeTab === 'prodi', 'bg-white text-gray-600 hover:bg-emerald-50': activeTab !== 'prodi'}" class="px-6 py-3 rounded-full font-bold transition-all border border-gray-100 flex items-center gap-2">
                🎓 Program Studi
            </button>
        </div>

        <div class="max-w-4xl mx-auto">
            @php
                $faqs = [
                    'umum' => [
                        [
                            'q' => "Apa itu Matla' Islamic Academy?",
                            'a' => "Kampus online sesuai Al Quran dan As-sunnah yang memberikan pembekalan ilmu syar’i agar kuat dalam agamanya terutama tauhidnya, aqidahnya, muamalah dan lainnya tapi juga memiliki skill industri sesuai bidangnya masing-masing."
                        ],
                        [
                            'q' => "Apakah ijazahnya diakui?",
                            'a' => "Iya, resmi diakui pemerintah dengan gelar S.Pd."
                        ],
                        [
                            'q' => "Apa pengajarnya ahlusunnah?",
                            'a' => "Betul."
                        ],
                        [
                            'q' => "Siapa pembinanya?",
                            'a' => "Ustaz Erfidel Fajri."
                        ],
                        [
                            'q' => "Apakah Matla' untuk akhwat?",
                            'a' => "Tidak. Kampus Matla' untuk ikhwan dan akhwat segala usia (sudah lulus SMA/sederajat)."
                        ],
                        [
                            'q' => "Lokasinya dimana?",
                            'a' => "Sekretariat kami berada di:<br>Masjid Al-Munawwaroh, Pondok Kacang Timur<br>Jl. Anggrek (bertepatan dengan PKBM SMP-SMA Abu Dzar), Pondok Aren, Tangerang Selatan – Banten<br>(hanya janji temu)"
                        ]
                    ],
                    'sistem' => [
                        [
                            'q' => "Bagaimana sistem pembelajarannya?",
                            'a' => "Semuanya pembelajarannya online via zoom/telegram."
                        ],
                        [
                            'q' => "Apakah buku-bukunya harus beli?",
                            'a' => "Tersedia ebook, namun jika mau beli buku fisik dipersilakan."
                        ],
                        [
                            'q' => "Apakah ada video rekaman pembelajaran?",
                            'a' => "Ada di YouTube."
                        ],
                        [
                            'q' => "Untuk KKN, PPL nya bagaimana?",
                            'a' => "Semua dilakukan online, KKN mengikuti instruksi dosen pembimbing. Biasanya melakukan kegiatan di daerah masing-masing. Misal seperti mengadakan kajian, riset dll."
                        ],
                        [
                            'q' => "Bagaimana dengan wisudanya?",
                            'a' => "Optional dan wisuda dilakukan secara offline, di hotel yang sudah disiapkan."
                        ],
                        [
                            'q' => "Apakah ada beasiswa?",
                            'a' => "Belum ada. Sedang kami usahakan untuk diadakan."
                        ]
                    ],
                    'prodi' => [
                        [
                            'q' => "Prodi yang tersedia apa saja?",
                            'a' => "• PAI (Pendidikan Agama Islam)<br>• I’dad Lughowi (Bahasa Arab Pemula)<br>• MPI RPL (Manajemen Pendidikan Islam)"
                        ],
                        [
                            'q' => "S1 PAI berapa tahun kuliahnya?",
                            'a' => "4 tahun 8 semester (bisa lebih cepat 3,5 tahun sesuai ketentuan berlaku)."
                        ],
                        [
                            'q' => "Apakah ada Skripsinya?",
                            'a' => "Untuk S1 ada atau bisa diganti dengan jurnal ilmiah sesuai regulasi pemerintah."
                        ],
                        [
                            'q' => "Bahasa pengantar kuliah S1 PAI nya apa?",
                            'a' => "Bahasa Indonesia."
                        ],
                        [
                            'q' => "Apakah I’dad ada ijazahnya?",
                            'a' => "Ada, dari kampus Matla non formal setara D2."
                        ],
                        [
                            'q' => "I’dad berapa tahun kuliahnya?",
                            'a' => "2 tahun 4 semester, disetiap semesternya akan ada project/tugas yang akan dibuat."
                        ],
                        [
                            'q' => "Kitab apa saja yang dipakai belajar?",
                            'a' => "Di Matla hanya memakai 2 kitab:<br>1. Durusul Lughoh<br>2. Baina Yadaik<br>dengan target kosa kata di setiap bulannya."
                        ],
                        [
                            'q' => "Bahasa Pengantar kuliah I’DAD nya apa?",
                            'a' => "Bahasa Indonesia bertahap."
                        ],
                        [
                            'q' => "Kalau sudah pernah ikut I'dad Lughowi?",
                            'a' => "Tidak perlu, bisa langsung lanjut ke S1 tanpa mengulang I'dad."
                        ],
                        [
                            'q' => "S2 belum buka ya?",
                            'a' => "Belum buka, mudah-mudahan segera buka."
                        ]
                    ]
                ];
            @endphp

            @foreach(['umum', 'sistem', 'prodi'] as $category)
            <div x-show="activeTab === '{{ $category }}'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                @foreach($faqs[$category] as $index => $faq)
                <div x-data="{ open: false }" class="faq-item border border-gray-100 bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:border-emerald-200 shadow-sm">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 lg:p-7 text-left focus:outline-none bg-white hover:bg-emerald-50/30 transition-colors group">
                        <span class="text-base lg:text-lg font-bold text-[#1F2937] pr-8 group-hover:text-primary transition-colors">
                            {{ $faq['q'] }}
                        </span>
                        <div class="shrink-0 w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center transition-all duration-300 group-hover:bg-primary group-hover:border-primary group-hover:text-white" :class="open ? 'bg-primary border-primary text-white' : ''">
                            <svg class="w-4 h-4 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <div class="px-6 lg:px-7 pb-7 text-gray-600 leading-relaxed text-sm lg:text-base border-t border-gray-50 pt-5">
                            {!! $faq['a'] !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
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
        <button @click="showModal = true" class="inline-block px-10 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xl shadow-xl shadow-orange-500/20 transition-all transform hover:scale-105">
            Daftar Sekarang
        </button>
        @else
        <div class="inline-block px-10 py-4 bg-red-500/20 text-red-200 border border-red-500/30 rounded-xl font-bold text-xl">
            Pendaftaran Saat Ini Ditutup
        </div>
        @endif

        <p class="mt-8 text-emerald-100/60 font-medium">
            Butuh bantuan? <a href="https://wa.me/6287784538820" target="_blank" class="text-emerald-400 hover:underline">Hubungi kami via WhatsApp</a>
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
</div>
@endsection
