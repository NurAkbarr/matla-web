@extends('layouts.app')

@section('content')
@php
    $currentQuickInfos = $quickInfos ?? collect();
    $infoCount = $currentQuickInfos->count();
@endphp

<style>
    .info-ticker-container {
        mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Hero Button Animation */
    .btn-daftar-sekarang {
        position: relative;
        overflow: hidden;
        transition: all 0.5s ease-in-out;
    }
    
    .btn-daftar-sekarang span, .btn-daftar-sekarang svg {
        position: relative;
        z-index: 10;
    }

    .btn-type1::after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        transition: all 0.5s ease-in-out;
        background-color: #15502e; /* emerald-700 */
        border-radius: 30px;
        visibility: hidden;
        height: 10px;
        width: 10px;
        z-index: 1;
    }

    .btn-type1:hover::after {
        visibility: visible;
        transform: scale(100) translateX(2px);
    }

    /* Program Studi Card Hover Animation */
    .prodi-card {
        position: relative;
        z-index: 1;
        overflow: hidden;
        transition: all 0.7s ease;
    }
    .prodi-card:hover {
        transform: scale(1.03);
        z-index: 9;
    }
    .hover_color_bubble {
        position: absolute;
        background: linear-gradient(140deg, rgba(30, 112, 64, 0.8) 0%, rgba(21, 80, 46, 1) 100%);
        width: 100rem;
        height: 100rem;
        left: -10rem;
        z-index: -1;
        top: 100%;
        border-radius: 50%;
        transform: rotate(-36deg);
        transition: 0.7s ease;
    }
    .prodi-card:hover .hover_color_bubble {
        top: -10rem;
    }
    .prodi-card:before {
        content: "";
        position: absolute;
        background: rgba(30, 112, 64, 0.05); /* Faint green matching #1E7040 */
        width: 170px;
        height: 400px;
        z-index: 0;
        transform: rotate(42deg);
        right: -56px;
        top: -23px;
        border-radius: 35px;
        transition: 0.7s ease;
    }
    .prodi-card:hover:before {
        background: rgba(30, 112, 64, 0.1);
    }
</style>
<header id="beranda" class="matla-hero" style="background-image: url('{{ asset('assets/doodle-bg.svg') }}'); background-repeat: repeat;">
    <div class="matla-hero-wrap">
        <div>
            <h1 class="hero-h1">Kuliah online sesuai Alquran dan As Sunnah. <em>Flexible. Terarah.</em></h1>
            <p class="hero-lead">Pelajari Bahasa Arab dan Ilmu Agama Islam bersama pengajar kompeten — dari mana saja, kapan saja, tanpa batasan umur dengan kurikulum yang terstruktur dan terarah.</p>
            <div class="hero-cta">
                @if(\App\Models\Setting::get_value('pmb_is_open') == '1')
                <a class="btn y" href="{{ route('pmb') }}">Daftar Sekarang &rarr;</a>
                @endif
                <a class="btn o" href="#program-studi">Lihat Program</a>
            </div>
        </div>

        <div class="matla-art" aria-hidden="true">
            <svg viewBox="0 0 520 520" fill="none">
                <g class="matla-ring">
                    <circle cx="260" cy="260" r="246" stroke="#fff" stroke-opacity=".35" stroke-dasharray="3 9" stroke-linecap="round"/>
                    <circle cx="260" cy="14" r="6" fill="#fca900"/>
                    <circle cx="14" cy="260" r="4" fill="#fff" fill-opacity=".6"/>
                    <circle cx="470" cy="400" r="4" fill="#fff" fill-opacity=".6"/>
                </g>
                <circle cx="260" cy="260" r="190" fill="#fff" fill-opacity=".07"/>
                <!-- books -->
                <rect x="62" y="372" width="120" height="22" rx="3" fill="#fca900"/>
                <rect x="72" y="350" width="104" height="22" rx="3" fill="#fbf6ea"/>
                <rect x="56" y="328" width="112" height="22" rx="3" fill="#004d29" stroke="#fff" stroke-opacity=".3"/>
                <rect x="70" y="334" width="40" height="3" fill="#fca900"/>
                <!-- laptop -->
                <rect x="132" y="150" width="270" height="180" rx="12" fill="#1b2b22" stroke="#fff" stroke-width="3"/>
                <rect x="146" y="164" width="242" height="152" rx="5" fill="#fbf6ea"/>
                <!-- open mushaf -->
                <path d="M267 186c-22-12-52-12-104-4v110c52-8 82-8 104 4z" fill="#fff" stroke="#006837" stroke-width="3"/>
                <path d="M267 186c22-12 52-12 104-4v110c-52-8-82-8-104 4z" fill="#fff" stroke="#006837" stroke-width="3"/>
                <g stroke="#006837" stroke-opacity=".55" stroke-width="3" stroke-linecap="round">
                    <path d="M180 204h70M180 218h70M180 232h70M180 246h70M180 260h70M284 204h70M284 218h70M284 232h70M284 246h70M284 260h70"/>
                </g>
                <circle cx="267" cy="196" r="9" fill="#fca900"/>
                <path d="M104 330h326l-22 22H126z" fill="#d6dbd8"/><path d="M126 352h282l-4 8H130z" fill="#a9b0ac"/>
                <!-- pen -->
                <g transform="translate(420 388) rotate(-35)">
                    <rect width="84" height="10" rx="3" fill="#fca900"/>
                    <path d="M84 0l14 5-14 5z" fill="#fbf6ea"/>
                </g>
                <!-- cup -->
                <path d="M440 330h48v34a18 18 0 0 1-18 18h-12a18 18 0 0 1-18-18z" fill="#fbf6ea"/>
                <path d="M488 340h8a8 8 0 0 1 0 18h-8" stroke="#fbf6ea" stroke-width="5"/>
                <path d="M448 330h32v10h-32z" fill="#fca900"/>
            </svg>
            <div class="matla-fl matla-f1"><b>▶</b>Kelas Live</div>
            <div class="matla-fl matla-f2"><b>ع</b>Bahasa Arab</div>
            <div class="matla-fl matla-f3">
                <b><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></b>Ilmu Syar'i
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="hero-stats">
        <div><b>Full Online</b><span>Flexible</span></div>
        <div><b>3 Program</b><span>Studi tersedia</span></div>
        <div><b>Dosen</b><span>Berkompeten</span></div>
        <div><b>2024</b><span>Matla berdiri</span></div>
    </div>

    <!-- Quick Access Running Carousel -->
    <div class="features-sec">
        <div class="features-head">
            <small>Quick Access</small>
            <h2>Semua layanan kampus dalam satu tempat</h2>
        </div>
        <div class="f-slider" id="fSlider">
            <div class="f-track" id="fTrack">
                @php
                    $getQuickIcon = function($label) {
                        $lbl = strtolower($label ?? '');
                        if (str_contains($lbl, 'instagram') || str_contains($lbl, 'ig')) {
                            return '<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/></svg>';
                        }
                        if (str_contains($lbl, 'bayar') || str_contains($lbl, 'rek') || str_contains($lbl, 'uang') || str_contains($lbl, 'spp') || str_contains($lbl, 'biaya') || str_contains($lbl, 'keuangan')) {
                            return '<svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>';
                        }
                        if (str_contains($lbl, 'lms') || str_contains($lbl, 'tutor') || str_contains($lbl, 'login') || str_contains($lbl, 'siakad')) {
                            return '<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>';
                        }
                        if (str_contains($lbl, 'kaldik') || str_contains($lbl, 'jadwal') || str_contains($lbl, 'kalender') || str_contains($lbl, 'uts') || str_contains($lbl, 'uas')) {
                            return '<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
                        }
                        if (str_contains($lbl, 'kurikulum') || str_contains($lbl, 'prodi') || str_contains($lbl, 'buku') || str_contains($lbl, 'akademik')) {
                            return '<svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M8 7h8M8 11h6"/></svg>';
                        }
                        if (str_contains($lbl, 'tertib') || str_contains($lbl, 'aturan') || str_contains($lbl, 'panduan') || str_contains($lbl, 'faq') || str_contains($lbl, 'surat') || str_contains($lbl, 'pengajuan')) {
                            return '<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';
                        }
                        if (str_contains($lbl, 'wa') || str_contains($lbl, 'whatsapp') || str_contains($lbl, 'chat')) {
                            return '<svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>';
                        }
                        return '<svg viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
                    };

                    $displayItems = $currentQuickInfos ?? collect();
                    // Fallback default hanya jika database kosong
                    if ($displayItems->isEmpty()) {
                        $displayItems = collect([
                            (object)['label' => 'Kurikulum', 'link' => '#'],
                            (object)['label' => 'Kaldik', 'link' => '#'],
                            (object)['label' => 'Tata Tertib', 'link' => '#'],
                            (object)['label' => 'Panduan LMS', 'link' => '#'],
                            (object)['label' => 'Link Instagram', 'link' => 'https://instagram.com/matla.official'],
                        ]);
                    }
                    // Untuk efek marquee mulus (seamless), pastikan set minimal 6 item per half
                    if ($displayItems->count() < 6) {
                        $multiplier = (int) ceil(6 / $displayItems->count());
                        $repeated = collect();
                        for ($i = 0; $i < $multiplier; $i++) {
                            $repeated = $repeated->concat($displayItems);
                        }
                        $displayItems = $repeated;
                    }
                @endphp

                {{-- Set Utama (hanya mengambil data dari Quick Info Admin) --}}
                @foreach($displayItems as $item)
                <a class="f-card" href="{{ $item->link ?? '#' }}" {{ (!empty($item->link) && !str_starts_with($item->link, '#')) ? 'target="_blank"' : '' }}>
                    <div class="f-icon">
                        {!! $getQuickIcon($item->label) !!}
                    </div>
                    <span class="f-title">{{ $item->label }}</span>
                    <span class="f-link">Buka &rarr;</span>
                </a>
                @endforeach

                {{-- Set Duplikat untuk seamless infinite loop slider --}}
                @foreach($displayItems as $item)
                <a class="f-card" href="{{ $item->link ?? '#' }}" {{ (!empty($item->link) && !str_starts_with($item->link, '#')) ? 'target="_blank"' : '' }} aria-hidden="true" tabindex="-1">
                    <div class="f-icon">
                        {!! $getQuickIcon($item->label) !!}
                    </div>
                    <span class="f-title">{{ $item->label }}</span>
                    <span class="f-link">Buka &rarr;</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<!-- Tentang Kami / Mengapa Matla Berdiri -->
<section class="matla-why-section" id="tentang">
    <div class="why">
        <div>
            <span class="k">Tentang kami</span>
            <h2>Mengapa Matla berdiri?</h2>
            <p class="s">Sebuah niat sederhana yang kami tulis sejak awal, dan masih kami pegang sampai sekarang.</p>
        </div>
        <div class="lap">
            <div class="screen">
                <div class="top">
                    <i></i><i></i><i></i>
                    <span>mengapa-matla-berdiri.txt</span>
                    <button id="skip" type="button">lewati ⏭</button>
                </div>
                <div class="code" id="code" aria-live="off"></div>
            </div>
            <div class="base"></div>
        </div>
    </div>
</section>

<!-- Program Studi Section -->
<section id="program-studi" class="py-14 sm:py-20 lg:py-28 bg-[#FDFDFD] relative overflow-hidden">
    <!-- Organic Green Smoke Background Effects -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Top left wisp -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-[#1E7040]/15 rounded-[100%] filter blur-[120px] transform -rotate-12 opacity-80"></div>
        <!-- Center flowing wisp -->
        <div class="absolute top-[20%] left-[20%] w-[60%] h-[30%] bg-emerald-400/10 rounded-[100%] filter blur-[100px] transform rotate-45 opacity-60"></div>
        <!-- Middle right wisp -->
        <div class="absolute top-[40%] -right-[10%] w-[40%] h-[50%] bg-[#1E7040]/10 rounded-[100%] filter blur-[130px] transform rotate-12 opacity-70"></div>
        <!-- Bottom left wisp -->
        <div class="absolute -bottom-[20%] left-[10%] w-[50%] h-[60%] bg-emerald-300/15 rounded-full filter blur-[140px] transform -rotate-12 opacity-80"></div>
    </div>
    
    <div class="container mx-auto px-5 sm:px-6 lg:px-12 relative z-10">
        <div class="text-center mb-10 sm:mb-16 max-w-2xl mx-auto">
            <span class="text-emerald-600 font-bold tracking-widest uppercase text-sm">Program Studi</span>
            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 mt-3">Program Unggulan Kami</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 max-w-5xl mx-auto">
            <!-- Card 1: Bahasa Arab -->
            <div class="prodi-card bg-[#FAF8F3] hover:bg-[#15502E] rounded-none p-6 sm:p-8 lg:p-10 shadow-sm hover:shadow-2xl border border-[#EBE5D9] hover:border-[#15502E] flex flex-col h-full group">
                <div class="hover_color_bubble"></div>
                
                <!-- Decorative Mandala Background -->
                <div class="absolute -right-20 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity duration-500 pointer-events-none">
                    <svg width="300" height="300" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="0.5" class="text-gray-900 group-hover:text-white">
                        <path d="M50 0 L55 40 L100 50 L55 60 L50 100 L45 60 L0 50 L45 40 Z"/>
                        <path d="M15 15 L40 40 M85 15 L60 40 M15 85 L40 60 M85 85 L60 60"/>
                        <circle cx="50" cy="50" r="30" />
                        <circle cx="50" cy="50" r="15" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Icon & Title Row -->
                    <div class="flex items-center space-x-4 sm:space-x-5 mb-3">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#E5F2EB] group-hover:bg-[#165541] rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 transition-colors duration-500">
                            <!-- Language Icon -->
                            <svg class="w-7 h-7 text-[#1E7040] group-hover:text-[#C49C54] transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 group-hover:text-white transition-colors duration-500">Bahasa Arab</h3>
                    </div>
                    
                    <p class="text-[#C49C54] font-bold text-[14px] sm:text-[15px] mb-5 ml-16 sm:ml-[4.5rem] lg:ml-[4.75rem]">Kuasai bahasa Al-Qur'an dari nol hingga mahir</p>
                    
                    <p class="text-gray-600 group-hover:text-gray-300 transition-colors duration-500 mb-6 sm:mb-8 leading-relaxed text-[13px] sm:text-[14px]">Program intensif yang dirancang untuk membangun kemampuan membaca, menulis, berbicara, dan memahami teks Arab klasik maupun kontemporer. Dibimbing langsung oleh dosen yang berkompeten di bidangnya.</p>
                    
                    <ul class="space-y-3 sm:space-y-4 mb-8 sm:mb-10 flex-1">
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Tersedia level pemula hingga lanjutan</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Kitab populer, baina yadaik, dan durusul lughoh</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Sertifikat dan ijazah dari kampus matla</span>
                        </li>
                    </ul>

                    <a href="{{ route('informasi.prodi') }}" class="inline-flex items-center justify-center w-max px-6 sm:px-8 py-2.5 sm:py-3 bg-transparent border border-[#1E7040] text-[#1E7040] group-hover:bg-[#C49C54] group-hover:border-[#C49C54] group-hover:text-white rounded-full font-bold text-sm sm:text-base transition-all duration-500 mt-auto">
                        Lihat Detail Program 
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: S1 PAI -->
            <div class="prodi-card bg-[#FAF8F3] hover:bg-[#15502E] rounded-none p-6 sm:p-8 lg:p-10 shadow-sm hover:shadow-2xl border border-[#EBE5D9] hover:border-[#15502E] flex flex-col h-full group">
                <div class="hover_color_bubble"></div>
                
                <!-- Decorative Badge -->
                <div class="absolute top-0 right-0 bg-[#C49C54] text-white text-[10px] font-black px-6 py-2 rounded-bl-[1.5rem] uppercase tracking-widest shadow-md z-20 flex items-center">
                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    Populer
                </div>
                
                <!-- Decorative Mandala Background -->
                <div class="absolute -right-20 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity duration-500 pointer-events-none">
                    <svg width="300" height="300" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="0.5" class="text-gray-900 group-hover:text-white">
                        <path d="M50 0 L55 40 L100 50 L55 60 L50 100 L45 60 L0 50 L45 40 Z"/>
                        <path d="M15 15 L40 40 M85 15 L60 40 M15 85 L40 60 M85 85 L60 60"/>
                        <circle cx="50" cy="50" r="30" />
                        <circle cx="50" cy="50" r="15" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Icon & Title Row -->
                    <div class="flex items-center space-x-4 sm:space-x-5 mb-3">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#E5F2EB] group-hover:bg-[#165541] rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 transition-colors duration-500">
                            <!-- Education Icon -->
                            <svg class="w-7 h-7 text-[#1E7040] group-hover:text-[#C49C54] transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6"></path></svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 group-hover:text-white transition-colors duration-500">S1 Pendidikan Agama Islam</h3>
                    </div>
                    
                    <p class="text-[#C49C54] font-bold text-[14px] sm:text-[15px] mb-5 ml-16 sm:ml-19 lg:ml-[4.75rem]">Raih gelar sarjana tanpa tinggalkan aktivitasmu</p>
                    
                    <p class="text-gray-600 group-hover:text-gray-300 transition-colors duration-500 mb-6 sm:mb-8 leading-relaxed text-[13px] sm:text-[14px]">Program S1 PAI yang terakreditasi, dirancang fleksibel untuk kamu yang ingin mendalami ilmu agama dari dasar sekaligus memiliki gelar pendidikan formal. Kurikulum terintegrasi antara Ilmu syar'i dan teknologi digital sebagai bekal setelah lulus kuliah</p>
                    
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Gelar S.Pd resmi & terakreditasi</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Kuliah 100% online</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 rounded-full bg-[#1E7040] flex items-center justify-center mr-4 shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 group-hover:text-gray-200 transition-colors duration-500 font-medium text-[14px]">Jadwal fleksibel</span>
                        </li>
                    </ul>

                    <a href="{{ route('informasi.prodi') }}" class="inline-flex items-center justify-center w-max px-6 sm:px-8 py-2.5 sm:py-3 bg-transparent border border-[#1E7040] text-[#1E7040] group-hover:bg-[#C49C54] group-hover:border-[#C49C54] group-hover:text-white rounded-full font-bold text-sm sm:text-base transition-all duration-500 mt-auto">
                        Lihat Detail Program 
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Tentang Section -->
<section id="tentang-detail" class="relative min-h-[400px] lg:min-h-[500px] flex items-center bg-white py-14 sm:py-20 lg:py-28 border-b border-gray-100">
    <div class="container mx-auto px-5 sm:px-6 lg:px-12 relative z-10">
        <div class="max-w-3xl">
            <h2 class="text-2xl sm:text-3xl lg:text-5xl font-extrabold text-[#1F2937] leading-tight tracking-tight">
                Tentang Matla Islamic University
            </h2>
            
            <p class="mt-5 sm:mt-6 lg:mt-8 text-base sm:text-lg lg:text-xl text-[#374151] leading-relaxed">
                Matla Islamic University adalah Kampus Islam Online terdepan yang menyediakan program kuliah Bahasa Arab dan S1 Pendidikan Agama Islam yang fleksibel dan mudah diakses.
            </p>

            <div class="mt-8 sm:mt-10 lg:mt-12 space-y-4 sm:space-y-5 lg:space-y-6">
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
<section id="keunggulan" class="bg-gray-50 py-14 sm:py-20 lg:py-32">
    <div class="container mx-auto px-5 sm:px-6 lg:px-12 relative z-10">
        <!-- Section Header & Navigation -->
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12 lg:mb-20 px-2 sm:px-4 md:px-0">
            <h2 class="text-2xl sm:text-3xl lg:text-5xl font-extrabold text-[#1F2937] leading-tight tracking-tight">
                Mengapa Memilih Matla?
            </h2>
            <p class="mt-3 sm:mt-4 lg:mt-6 text-sm sm:text-base lg:text-lg text-gray-600 whitespace-normal">
                Kami bukan sekadar kelas online. Matla adalah ekosistem belajar Islam yang menyeluruh.
            </p>
            
            <!-- Mobile Slider Controls -->
            <div class="md:hidden flex justify-center items-center space-x-3 mt-8">
                <button onclick="document.getElementById('keunggulan-slider').scrollBy({left: -280, behavior: 'smooth'})" class="w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-colors shadow-sm active:scale-95" aria-label="Slide Left">
                    <svg class="w-5 h-5 pr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button onclick="document.getElementById('keunggulan-slider').scrollBy({left: 280, behavior: 'smooth'})" class="w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-colors shadow-sm active:scale-95" aria-label="Slide Right">
                    <svg class="w-5 h-5 pl-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <!-- Text & Icons Grid / Slider -->
        <div id="keunggulan-slider" class="flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-[1px] bg-gray-200/70 max-w-7xl mx-auto overflow-x-auto snap-x snap-mandatory scrollbar-hide md:overflow-visible">
            <!-- Item 1 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">🕌</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">Kurikulum Islami Terintegrasi</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Materi disusun sistematis mengikuti tradisi keilmuan Islam, dari ilmu alat hingga ilmu inti — bukan kurikulum instan.</p>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">👨‍🏫</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">Dosen Bersanad & Berpengalaman</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Seluruh pengajar merupakan lulusan universitas Islam terkemuka di Timur Tengah dengan sanad keilmuan yang jelas.</p>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">💻</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">100% Online & Fleksibel</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Akses materi kapan saja dan di mana saja. Cocok untuk pelajar, pekerja, ibu rumah tangga, hingga santri.</p>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">💰</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">Biaya Terjangkau</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Pendidikan Islam berkualitas tidak harus mahal. Matla hadir dengan biaya yang bisa dijangkau semua kalangan.</p>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">🤝</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">Komunitas Belajar yang Aktif</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Bergabung dengan ribuan pelajar dari seluruh Indonesia. Belajar bersama, tumbuh bersama.</p>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="shrink-0 w-[85%] md:w-auto snap-center flex flex-col sm:flex-row items-start p-6 sm:p-8 lg:p-10 bg-gray-50 hover:bg-white transition-colors">
                <div class="text-3xl sm:text-4xl mb-3 sm:mb-0 sm:mr-5 shrink-0 filter drop-shadow-sm">📜</div>
                <div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937] mb-2 sm:mb-3 leading-snug">Ijazah & Sertifikat Resmi</h3>
                    <p class="text-gray-600 leading-relaxed text-[14px] sm:text-[15px]">Setiap program menghasilkan dokumen resmi yang diakui dan dapat digunakan untuk keperluan akademik maupun profesional.</p>
                </div>
            </div>
        </div>
</section>

<!-- Cara Belajar Section -->
<section id="cara-belajar" class="pt-14 pb-6 sm:pt-20 sm:pb-8 lg:pt-32 lg:pb-8 bg-white relative overflow-hidden border-t border-gray-100">
    <div class="container mx-auto px-5 sm:px-6 lg:px-12 relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 lg:mb-24">
            <span class="text-emerald-600 font-bold tracking-widest uppercase text-xs sm:text-sm mb-3 block">Cara Belajar di Matla</span>
            <h2 class="text-2xl sm:text-3xl lg:text-5xl font-extrabold text-[#1F2937] leading-tight tracking-tight">
                Mudah Dimulai, Nyata Hasilnya
            </h2>
            <p class="mt-3 sm:mt-4 lg:mt-6 text-sm sm:text-base lg:text-lg text-gray-600">
                Empat langkah sederhana menuju ilmu yang bermanfaat
            </p>
        </div>

        <!-- Steps Timeline -->
        <div class="relative max-w-6xl mx-auto">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden lg:block absolute top-8 left-[10%] right-[10%] h-[2px] bg-emerald-100 -z-10"></div>
            
            <!-- Connecting Line (Mobile - vertical) -->
            <div class="block sm:hidden absolute top-0 bottom-0 left-7 w-[2px] bg-emerald-100 -z-10"></div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-10 lg:gap-8">
                <!-- Step 1 -->
                <div class="relative flex flex-row sm:flex-col items-start sm:items-center text-left sm:text-center group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center p-1 mr-5 sm:mr-0 sm:mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <div class="w-full h-full bg-[#1E7040] text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">1</div>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-[#1F2937] mb-2 sm:mb-3">Daftar Online</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Isi formulir pendaftaran di website kami. Proses cepat, tidak ribet.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-row sm:flex-col items-start sm:items-center text-left sm:text-center group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center p-1 mr-5 sm:mr-0 sm:mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <div class="w-full h-full bg-[#1E7040] text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">2</div>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-[#1F2937] mb-2 sm:mb-3">Pilih Program</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Tentukan program yang sesuai dengan tujuan dan jadwalmu.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-row sm:flex-col items-start sm:items-center text-left sm:text-center group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center p-1 mr-5 sm:mr-0 sm:mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <div class="w-full h-full bg-[#1E7040] text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">3</div>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-[#1F2937] mb-2 sm:mb-3">Akses Materi Kuliah</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Login ke platform belajar Matla, ikuti kelas, diskusi, dan tugas kapan pun kamu siap.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-row sm:flex-col items-start sm:items-center text-left sm:text-center group">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center p-1 mr-5 sm:mr-0 sm:mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300 shrink-0">
                        <div class="w-full h-full bg-[#1E7040] text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">4</div>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-[#1F2937] mb-2 sm:mb-3">Lulus & Raih Sertifikat</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Selesaikan semua modul, ikuti ujian, dan terima ijazah resmi dari Matla Islamic University.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="mt-10 sm:mt-12 lg:mt-14 flex justify-center">
            <style>
                .animated-button {
                  position: relative;
                  display: flex;
                  align-items: center;
                  gap: 4px;
                  padding: 16px 36px;
                  border: 4px solid transparent;
                  font-size: 18px;
                  background-color: transparent;
                  border-radius: 100px;
                  font-weight: 800;
                  color: #1E7040;
                  box-shadow: 0 0 0 2px #1E7040;
                  cursor: pointer;
                  overflow: hidden;
                  transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
                  text-decoration: none;
                }

                .animated-button svg {
                  position: absolute;
                  width: 24px;
                  fill: #1E7040;
                  z-index: 9;
                  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
                }

                .animated-button .arr-1 {
                  right: 16px;
                }

                .animated-button .arr-2 {
                  left: -25%;
                }

                .animated-button .circle {
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  width: 20px;
                  height: 20px;
                  background-color: #1E7040;
                  border-radius: 50%;
                  opacity: 0;
                  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
                }

                .animated-button .text {
                  position: relative;
                  z-index: 1;
                  transform: translateX(-12px);
                  transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
                }

                .animated-button:hover {
                  box-shadow: 0 0 0 12px transparent;
                  color: #ffffff;
                  border-radius: 12px;
                }

                .animated-button:hover .arr-1 {
                  right: -25%;
                }

                .animated-button:hover .arr-2 {
                  left: 16px;
                }

                .animated-button:hover .text {
                  transform: translateX(12px);
                }

                .animated-button:hover svg {
                  fill: #ffffff;
                }

                .animated-button:active {
                  transform: scale(0.95);
                  box-shadow: 0 0 0 4px #1E7040;
                }

                .animated-button:hover .circle {
                  width: 220px;
                  height: 220px;
                  opacity: 1;
                }

                @media (max-width: 640px) {
                  .animated-button {
                    padding: 12px 26px;
                    font-size: 15px;
                  }
                  .animated-button svg {
                    width: 20px;
                  }
                  .animated-button .text {
                    transform: translateX(-8px);
                  }
                  .animated-button:hover .text {
                    transform: translateX(8px);
                  }
                }
            </style>
            
            <a href="/pmb" class="animated-button">
              <svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
              </svg>
              <span class="text">Mulai Sekarang</span>
              <span class="circle"></span>
              <svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
              </svg>
            </a>
        </div>
    </div>
</section>


<!-- Testimoni Section -->
<section class="pt-10 pb-10 lg:pt-16 lg:pb-12 bg-white relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
        <div class="text-center mb-16 lg:mb-20">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1F2937] uppercase tracking-widest mb-4">Testimoni Mahasiswa</h2>
            <div class="w-20 h-1 bg-[#1E7040] mx-auto mb-6"></div>
            <p class="text-[#1E7040] font-bold text-sm sm:text-lg">Kata Mereka yang Sudah Bergabung</p>
        </div>

        <style>
            /* Hide scrollbar for Chrome, Safari and Opera */
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
            /* Hide scrollbar for IE, Edge and Firefox */
            .hide-scrollbar {
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
            }
        </style>
        <div class="flex overflow-x-auto md:grid md:grid-cols-3 gap-6 md:gap-8 lg:gap-12 mt-0 pb-8 pt-16 px-4 sm:px-0 -mx-4 sm:mx-0 snap-x snap-mandatory hide-scrollbar scroll-smooth">
            <!-- Card 1 -->
            <div class="w-[85vw] sm:w-[320px] md:w-auto shrink-0 snap-center bg-gray-50 relative pt-24 pb-8 px-6 sm:px-8 text-center flex flex-col items-center shadow-md border border-gray-100 rounded-lg transform transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02] hover:shadow-xl hover:border-gray-200 group">
                <!-- Icon: Karyawan Swasta (Tie/Suit) -->
                <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 bg-white rounded-full p-2 shadow-sm border border-gray-200 transform transition-transform duration-300 group-hover:scale-110">
                    <div class="w-full h-full bg-[#1F2937] rounded-full flex items-center justify-center overflow-hidden">
                        <svg class="w-10 h-10 text-[#469e6b]" fill="currentColor" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm95.8 32.6L272 480l-32-136 32-56h-96l32 56-32 136-47.8-191.4C56.9 292 0 350.3 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-72.1-56.9-130.4-128.2-133.8z"/></svg>
                    </div>
                </div>
                
                <p class="text-[#1F2937] text-sm leading-relaxed mb-8 flex-1 mt-2 font-medium">
                    <span class="text-[#1F2937] text-2xl font-serif leading-none mr-1">“</span>Saya sudah lama ingin belajar bahasa Arab tapi tidak punya waktu karena kerja. Di Matla saya bisa belajar malam hari, materinya terstruktur, dan dosennya sabar banget menjelaskan.<span class="text-[#1F2937] text-2xl font-serif leading-none ml-1">”</span>
                </p>
                
                <div class="mt-auto w-full">
                    <span class="text-[#1E7040] font-black text-base block mb-1">Ahmad Fauzi</span>
                    <span class="text-gray-600 font-medium text-xs">32 tahun &middot; Karyawan Swasta &middot; Surabaya</span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="w-[85vw] sm:w-[320px] md:w-auto shrink-0 snap-center bg-gray-50 relative pt-24 pb-8 px-6 sm:px-8 text-center flex flex-col items-center shadow-md border border-gray-100 rounded-lg transform transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02] hover:shadow-xl hover:border-gray-200 group">
                <!-- Icon: Ibu Rumah Tangga (Female silhouette) -->
                <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 bg-white rounded-full p-2 shadow-sm border border-gray-200 transform transition-transform duration-300 group-hover:scale-110">
                    <div class="w-full h-full bg-[#1F2937] rounded-full flex items-center justify-center overflow-hidden">
                        <svg class="w-10 h-10 text-[#469e6b]" fill="currentColor" viewBox="0 0 320 512"><path d="M160 160c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zM245.9 176H74.1c-15.4 0-28.7 11.2-31.5 26.4L1.7 348.6c-4.4 23.4 14.8 44 38.8 42l16.1-1.3c15-1.2 27.5-12.2 30-27L96 321l-7.7 167.3c-1.1 23.5 17.6 43.1 41.2 43.7 21 .6 38.5-16.5 38.5-37.5v-79h-16v79c0 21 17.5 38.1 38.5 37.5 23.5-.6 42.2-20.2 41.2-43.7L224 321l9.4 41.2c2.5 14.8 15 25.8 30 27l16.1 1.3c23.9 2 43.1-18.6 38.8-42L277.4 202.4c-2.8-15.2-16.1-26.4-31.5-26.4z"/></svg>
                    </div>
                </div>
                
                <p class="text-[#1F2937] text-sm leading-relaxed mb-8 flex-1 mt-2 font-medium">
                    <span class="text-[#1F2937] text-2xl font-serif leading-none mr-1">“</span>Sebagai ibu rumah tangga, Matla benar-benar solusi. Saya bisa kuliah S1 sambil mengurus anak. Jadwalnya fleksibel dan teman-teman di kelas sangat supportif.<span class="text-[#1F2937] text-2xl font-serif leading-none ml-1">”</span>
                </p>
                
                <div class="mt-auto w-full">
                    <span class="text-[#1E7040] font-black text-base block mb-1">Ummu Khalid</span>
                    <span class="text-gray-600 font-medium text-xs">28 tahun &middot; Ibu Rumah Tangga &middot; Makassar</span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="w-[85vw] sm:w-[320px] md:w-auto shrink-0 snap-center bg-gray-50 relative pt-24 pb-8 px-6 sm:px-8 text-center flex flex-col items-center shadow-md border border-gray-100 rounded-lg transform transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02] hover:shadow-xl hover:border-gray-200 group">
                <!-- Icon: Mahasiswa (Graduation Cap) -->
                <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 bg-white rounded-full p-2 shadow-sm border border-gray-200 transform transition-transform duration-300 group-hover:scale-110">
                    <div class="w-full h-full bg-[#1F2937] rounded-full flex items-center justify-center overflow-hidden">
                        <svg class="w-12 h-12 text-[#469e6b]" fill="currentColor" viewBox="0 0 640 512"><path d="M320 32.1L64 153.2v200c0 10.9-6.3 20.8-16 25.6l-32 16v-400l304-142.3 304 142.3v400l-32-16c-9.7-4.8-16-14.7-16-25.6v-200L320 32.1zM112 189v158.4c0 36.1 40.8 69.9 104.9 88C248.8 444.6 283.4 448 320 448s71.2-3.4 103.1-12.6C487.2 417.3 528 383.5 528 347.4V189L320 286.7 112 189z"/></svg>
                    </div>
                </div>
                
                <p class="text-[#1F2937] text-sm leading-relaxed mb-8 flex-1 mt-2 font-medium">
                    <span class="text-[#1F2937] text-2xl font-serif leading-none mr-1">“</span>Kualitas pengajarnya luar biasa. Bukan hanya pintar, tapi juga sabar dan metodis. Saya merasa seperti belajar langsung di pesantren meski dari rumah.<span class="text-[#1F2937] text-2xl font-serif leading-none ml-1">”</span>
                </p>
                
                <div class="mt-auto w-full">
                    <span class="text-[#1E7040] font-black text-base block mb-1">Rizal Hakim</span>
                    <span class="text-gray-600 font-medium text-xs">24 tahun &middot; Mahasiswa &middot; Bandung</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Informasi / Instagram Section -->
<section id="informasi" class="pt-10 lg:pt-12 pb-10 bg-gray-50 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="text-center mb-12">
            <span class="text-emerald-600 font-bold tracking-widest uppercase text-xs sm:text-sm mb-3 block">Info & Update</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1F2937] tracking-tight mb-4">
                Informasi <span class="text-[#1E7040]">Terbaru</span>
            </h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                Ikuti terus update kegiatan, kajian, dan informasi penting lainnya langsung dari Instagram resmi kami.
            </p>
        </div>

        @if(isset($instagramPosts) && count($instagramPosts) > 0)
        {{-- Grid Thumbnail --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 grid-flow-row-dense">
            @foreach($instagramPosts as $index => $post)
            @php
                $isVideo = str_contains($post->instagram_link, '/reel/') || str_contains($post->instagram_link, '/tv/') || str_contains($post->instagram_link, '/video/');
                $isLarge = ($index === 4); // Postingan ke-5 (index 4) dibuat lebih besar
                
                // Styling dinamis berdasarkan ukuran layar
                $gridClass = $isLarge ? 'col-span-2 md:row-span-2 aspect-video md:aspect-square' : 'aspect-square';
                
                // Sembunyikan postingan ke 6-9 di mode HP (mobile)
                if ($index > 4) {
                    $gridClass .= ' hidden md:block';
                } else {
                    $gridClass .= ' block';
                }
            @endphp
            @if($isVideo)
            <a href="{{ $post->instagram_link }}" target="_blank" rel="noopener"
               class="group relative overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-100 w-full text-left {{ $gridClass }}">
            @else
            <button onclick="openIgModal('{{ asset('instagram-posts/' . $post->image) }}', '{{ $post->instagram_link }}')"
                    class="group relative overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 bg-gray-100 w-full text-left {{ $gridClass }}">
            @endif
                
                <img src="{{ asset('instagram-posts/' . $post->image) }}" 
                     alt="{{ $post->title ?? 'Instagram Post' }}" 
                     class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                {{-- Overlay Play Button (Center) for Videos --}}
                @if($isVideo)
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20 pointer-events-none">
                    <div class="bg-black/40 p-4 rounded-full backdrop-blur-sm transform scale-50 group-hover:scale-100 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 384 512"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg>
                    </div>
                </div>
                @endif

                {{-- Overlay on hover (Bottom Gradient) --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 z-10">
                    <div class="transform translate-y-3 group-hover:translate-y-0 transition-transform duration-300">
                        @if(!$isVideo)
                        <svg class="w-6 h-6 text-white mb-1" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                        @endif
                        <p class="text-white text-xs font-semibold">Lihat di Instagram →</p>
                    </div>
                </div>

                {{-- Instagram Icon Badge (Always Visible Top Right) --}}
                <div class="absolute top-2.5 right-2.5 bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 p-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-20">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                </div>
            @if($isVideo)
            </a>
            @else
            </button>
            @endif

            @endforeach
        </div>
        @else
        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-gray-100 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Postingan</h3>
            <p class="text-gray-500 text-sm">Postingan Instagram akan muncul di sini setelah ditambahkan di Dashboard Admin.</p>
        </div>
        @endif

        {{-- Modal Lightbox — Menampilkan foto full-size + tombol ke IG --}}
        <div id="ig-modal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
            <div id="ig-modal-box" class="relative w-full max-w-2xl transform scale-95 transition-transform duration-300">
                {{-- Close Button --}}
                <button onclick="closeIgModal()" class="absolute -top-12 right-0 z-20 text-white/70 hover:text-white flex items-center space-x-2 text-sm transition-colors">
                    <span>Tutup</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                {{-- Image Container --}}
                <div class="bg-black rounded-2xl overflow-hidden shadow-2xl">
                    <div class="relative">
                        <img id="ig-modal-img" src="" alt="" class="w-full max-h-[70vh] object-contain block">

                        {{-- Gradient overlay bottom --}}
                        <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Footer —  Buka di Instagram --}}
                    <div class="px-5 py-4 bg-black flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 p-[2px] flex-shrink-0">
                                <div class="bg-black rounded-full w-full h-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                                </div>
                            </div>
                            <span class="text-white/80 text-sm font-medium">kampusmatla</span>
                        </div>
                        <a id="ig-open-link" href="#" target="_blank" rel="noopener"
                           class="flex items-center space-x-2 bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 hover:opacity-90 text-white px-5 py-2 rounded-full font-bold transition-all text-sm shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                            <span>Lihat di Instagram</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openIgModal(imgSrc, igLink) {
                const modal    = document.getElementById('ig-modal');
                const box      = document.getElementById('ig-modal-box');
                const img      = document.getElementById('ig-modal-img');
                const openLink = document.getElementById('ig-open-link');

                img.src       = imgSrc;
                openLink.href = igLink;

                modal.classList.remove('opacity-0', 'pointer-events-none');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
                document.body.style.overflow = 'hidden';
            }

            function closeIgModal() {
                const modal = document.getElementById('ig-modal');
                const box   = document.getElementById('ig-modal-box');
                const img   = document.getElementById('ig-modal-img');

                modal.classList.add('opacity-0', 'pointer-events-none');
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
                document.body.style.overflow = '';
                setTimeout(() => { img.src = ''; }, 300);
            }

            // Close on backdrop click
            document.getElementById('ig-modal').addEventListener('click', function(e) {
                if (e.target === this) closeIgModal();
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeIgModal();
            });
        </script>
    </div>
</section>


@if(count($brosurs) > 0 && \App\Models\Setting::get_value('show_brosur_popup', '1') == '1')
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
    // ===== TICKER DRAG-TO-SCROLL & AUTO-SCROLL =====
    const ticker = document.getElementById('quick-info-ticker');
    const track = document.getElementById('quick-info-track');
    
    if (ticker && track) {
        let isDown = false;
        let startX;
        let scrollLeft;
        let isHovered = false;
        let animationId;
        
        // Auto scroll function
        const scrollTicker = () => {
            if (!isDown && !isHovered) {
                ticker.scrollLeft += 1; // Kecepatan scroll
                // Reset posisi ke awal jika sudah mencapai setengah jalan (karena diclone 4x, setengah jalan itu seamless)
                if (ticker.scrollLeft >= track.scrollWidth / 2) {
                    ticker.scrollLeft = 0;
                }
            }
            animationId = requestAnimationFrame(scrollTicker);
        };
        
        // Start auto scroll
        animationId = requestAnimationFrame(scrollTicker);

        // Desktop Drag Support
        ticker.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - ticker.offsetLeft;
            scrollLeft = ticker.scrollLeft;
        });
        
        ticker.addEventListener('mouseleave', () => {
            isDown = false;
            isHovered = false;
        });
        
        ticker.addEventListener('mouseup', () => {
            isDown = false;
        });
        
        ticker.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - ticker.offsetLeft;
            const walk = (x - startX) * 2;
            ticker.scrollLeft = scrollLeft - walk;
        });

        // Pause on interaction
        ticker.addEventListener('mouseenter', () => isHovered = true);
        
        // Mobile touch support
        ticker.addEventListener('touchstart', () => isHovered = true, {passive: true});
        ticker.addEventListener('touchend', () => {
            setTimeout(() => isHovered = false, 1000);
        }, {passive: true});
    }
</script>
@endif
@endsection

