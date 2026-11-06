@extends('layouts.app')

@section('title', 'PMB 2026/2027 - Matla Islamic Academy')

@section('content')
<div x-data="{ showModal: false }">
<!-- Hero Section -->
<section class="relative min-h-[600px] lg:min-h-screen flex items-center overflow-hidden bg-[#006837]" style="background-image: url('{{ asset('assets/doodle-bg.svg') }}'); background-repeat: repeat;">
    <div class="container mx-auto px-4 lg:px-12 relative z-10 py-20 lg:py-32">
        <div class="max-w-3xl">
            <!-- Badge -->
            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-8">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="text-white text-sm font-semibold tracking-wide">{{ $settings['pmb_gelombang'] }}</span>
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
                Bekali Akhiratmu dengan <span class="text-emerald-300 underline decoration-emerald-300/40 underline-offset-8">Ilmu Syar'i</span>
            </h1>
            <p class="text-2xl md:text-3xl font-bold text-amber-400 mb-6 leading-snug">
                Bekali Duniamu dengan Skill Industri
            </p>
            
            <p class="text-lg md:text-xl text-emerald-50/90 mb-10 leading-relaxed max-w-2xl">
                Bergabunglah bersama MATLA untuk mempelajari ilmu syar'i secara mendalam sekaligus membekali diri dengan keterampilan yang siap digunakan di dunia kerja.
            </p>

            <!-- Countdown Timer -->
            @if($settings['pmb_is_open'] == '1')
            <div class="mb-12" id="pmb-countdown-container" data-end-date="{{ date('Y-m-d H:i:s', strtotime($settings['pmb_end_date'])) }}">
                <p class="text-emerald-300 font-bold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Batas Waktu Pendaftaran
                </p>
                <div class="flex space-x-3 md:space-x-5">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-days">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Hari</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-hours">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Jam</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-white mb-2" id="cd-minutes">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Menit</span>
                    </div>
                </div>
            </div>
            @else
            {{-- Coming Soon Countdown --}}
            <div class="mb-12" id="pmb-coming-soon-container" data-open-date="{{ date('Y-m-d H:i:s', strtotime($settings['pmb_start_date'] ?? '2026-06-01 00:00:00')) }}">
                <p class="text-amber-400 font-bold mb-4 flex items-center text-sm uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm1 14.93V17a1 1 0 11-2 0v-.07A8.001 8.001 0 014.07 11H5a1 1 0 110 2h-.93A8.001 8.001 0 0111 19.93zm0-13.86V5a1 1 0 112 0v.07A8.001 8.001 0 0119.93 11H19a1 1 0 110-2h.93A8.001 8.001 0 0113 3.07zM12 8v4l2.5 2.5a1 1 0 01-1.414 1.414l-3-3A1 1 0 0110 12V8a1 1 0 012 0z"/>
                    </svg>
                    Pendaftaran {{ $settings['pmb_gelombang'] }} Dibuka Dalam
                </p>
                <div class="flex space-x-3 md:space-x-5">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-days">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Hari</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-hours">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Jam</span>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-2xl flex items-center justify-center text-2xl md:text-3xl font-bold text-orange-300 mb-2" id="cs-minutes">--</div>
                        <span class="text-emerald-100/80 text-xs md:text-sm uppercase tracking-widest font-bold">Menit</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                @if($settings['pmb_is_open'] == '1')
                <button @click="showModal = true" class="px-8 py-4 bg-[#fca900] hover:bg-[#e09600] text-[#14231B] rounded-xl font-bold text-lg shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center space-x-2 mr-4 group">
                    <span>Daftar Sekarang</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
                @endif
                <a href="{{ $settings['pmb_status_link'] }}" class="px-8 py-4 bg-white/10 hover:bg-white border border-white/20 hover:text-[#006837] text-white rounded-xl font-bold text-lg transition-all flex items-center justify-center space-x-2 backdrop-blur-sm">
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
<section class="py-24 bg-white relative overflow-hidden" id="alur-pendaftaran"
    x-data="{
        activeStep: 1,
        hoveredStep: null,
        trackTop: 48,
        trackTotalHeight: 0,
        progressHeight: 0,
        get effectiveActive() {
            return this.hoveredStep ?? this.activeStep;
        },
        init() {
            this.$nextTick(() => {
                this.updateDimensions();
                this.handleScroll();
            });
            window.addEventListener('scroll', () => {
                requestAnimationFrame(() => this.handleScroll());
            }, { passive: true });
            window.addEventListener('resize', () => {
                this.updateDimensions();
            }, { passive: true });
            window.addEventListener('load', () => {
                this.updateDimensions();
            });
        },
        updateDimensions() {
            const firstNode = document.getElementById('alur-node-1');
            const lastNode = document.getElementById('alur-node-5');
            const container = document.getElementById('alur-timeline-container');
            if (firstNode && lastNode && container) {
                const firstRect = firstNode.getBoundingClientRect();
                const lastRect = lastNode.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();
                
                this.trackTop = (firstRect.top + firstRect.height / 2) - containerRect.top;
                this.trackTotalHeight = (lastRect.top + lastRect.height / 2) - (firstRect.top + firstRect.height / 2);
                this.updateProgress();
            }
        },
        updateProgress() {
            this.$nextTick(() => {
                const firstNode = document.getElementById('alur-node-1');
                const activeNode = document.getElementById('alur-node-' + this.effectiveActive);
                if (firstNode && activeNode) {
                    const firstRect = firstNode.getBoundingClientRect();
                    const activeRect = activeNode.getBoundingClientRect();
                    const diff = (activeRect.top + activeRect.height / 2) - (firstRect.top + firstRect.height / 2);
                    this.progressHeight = Math.max(0, diff);
                }
            });
        },
        handleScroll() {
            const steps = [1, 2, 3, 4, 5];
            const triggerY = window.innerHeight * 0.50;
            let current = 1;
            
            for (const s of steps) {
                const el = document.getElementById('alur-step-' + s);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    const mid = rect.top + rect.height / 2;
                    if (mid <= triggerY + 60) {
                        current = s;
                    }
                }
            }
            if (this.activeStep !== current) {
                this.activeStep = current;
                this.updateProgress();
            }
        },
        setHover(s) {
            this.hoveredStep = s;
            this.updateProgress();
        },
        clearHover() {
            this.hoveredStep = null;
            this.updateProgress();
        }
    }">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-16 md:mb-20">
            <div class="inline-flex items-center space-x-2 px-3 py-1 bg-amber-50 border border-amber-200/60 rounded-full text-amber-800 text-xs font-bold uppercase tracking-widest mb-3">
                <span>Panduan Calon Mahasiswa</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4 font-display">Alur Pendaftaran</h2>
            <p class="text-base md:text-lg text-gray-600 leading-relaxed">Proses pendaftaran mudah, cepat, dan bisa dilakukan dari mana saja.</p>
        </div>

        @php
        $alurSteps = [
            [
                'number' => 1,
                'title' => 'Isi Formulir Pendaftaran',
                'desc' => 'Calon mahasiswa mengisi formulir pendaftaran secara online melalui website PMB MATLA.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
            ],
            [
                'number' => 2,
                'title' => 'Pembayaran Biaya Pendaftaran',
                'desc' => 'Lakukan pembayaran biaya pendaftaran sesuai dengan instruksi dan nominal yang tertera pada sistem.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'
            ],
            [
                'number' => 3,
                'title' => 'Upload Bukti Pembayaran',
                'desc' => 'Unggah foto atau tangkapan layar bukti transfer pembayaran pendaftaran Anda ke dalam sistem PMB.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>'
            ],
            [
                'number' => 4,
                'title' => 'Verifikasi Pembayaran',
                'desc' => 'Tim Keuangan MATLA akan melakukan pengecekan dan memverifikasi bukti pembayaran yang telah Anda kirimkan.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'
            ],
            [
                'number' => 5,
                'title' => 'Bergabung Grup Kelas 🎉',
                'desc' => 'Setelah pembayaran terverifikasi, Anda resmi terdaftar! Silakan bergabung ke grup komunikasi kelas sesuai dengan program studi (Prodi) yang Anda pilih.',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'
            ],
        ];
        @endphp

        <!-- Timeline Container -->
        <div class="relative max-w-4xl mx-auto py-4" id="alur-timeline-container">
            <!-- Background Spine Track (Gray) -->
            <div class="absolute left-[28px] md:left-1/2 -translate-x-1/2 w-1.5 bg-gray-200 rounded-full z-0 pointer-events-none transition-all duration-300"
                 :style="'top: ' + trackTop + 'px; height: ' + (trackTotalHeight ? trackTotalHeight + 'px' : 'calc(100% - 96px)') + ';'"></div>

            <!-- Active Progress Spine Fill (Amber / Gold) -->
            <div class="absolute left-[28px] md:left-1/2 -translate-x-1/2 w-1.5 bg-[#fca900] rounded-full z-0 pointer-events-none transition-all duration-500 ease-out shadow-sm shadow-amber-500/30"
                 :style="'top: ' + trackTop + 'px; height: ' + progressHeight + 'px;'"></div>

            <!-- Steps -->
            @foreach($alurSteps as $step)
            @php
                $isOdd = ($step['number'] % 2 !== 0);
            @endphp
            <div id="alur-step-{{ $step['number'] }}" 
                 @mouseenter="setHover({{ $step['number'] }})" 
                 @mouseleave="clearHover()"
                 @click="activeStep = {{ $step['number'] }}; updateProgress(); $el.scrollIntoView({ behavior: 'smooth', block: 'center' })"
                 class="relative flex flex-col md:flex-row items-center justify-between py-6 md:py-8 cursor-pointer group">
                
                <!-- Left Column (Odd: Content Card | Even: Spacer on desktop) -->
                @if($isOdd)
                <div class="w-full md:w-[calc(50%-48px)] pl-16 md:pl-0 md:text-right">
                    <div class="p-6 md:p-7 rounded-2xl md:rounded-3xl transition-all duration-300"
                         :class="effectiveActive === {{ $step['number'] }} 
                             ? 'bg-[#FFF8E6] border-2 border-[#FCA900]/50 shadow-lg shadow-amber-500/10 scale-[1.02]' 
                             : 'bg-transparent border-2 border-transparent hover:bg-gray-50/70 hover:border-gray-100'">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2 transition-colors"
                              :class="effectiveActive === {{ $step['number'] }} ? 'bg-[#fca900] text-[#14231B]' : 'bg-gray-100 text-gray-500'">
                            Langkah 0{{ $step['number'] }}
                        </span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-2 font-display transition-colors"
                            :class="effectiveActive === {{ $step['number'] }} ? 'text-amber-950' : 'text-gray-900'">
                            {{ $step['title'] }}
                        </h3>
                        <p class="text-sm md:text-base leading-relaxed transition-colors"
                           :class="effectiveActive === {{ $step['number'] }} ? 'text-gray-700 font-medium' : 'text-gray-500'">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </div>
                @else
                <div class="hidden md:block md:w-[calc(50%-48px)]"></div>
                @endif

                <!-- Center Node (Circle with Icon / Number) -->
                <div id="alur-node-{{ $step['number'] }}"
                     class="absolute left-1 md:left-1/2 md:-translate-x-1/2 top-6 md:top-1/2 md:-translate-y-1/2 z-10 transition-transform duration-300">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center transition-all duration-300 font-bold"
                         :class="effectiveActive === {{ $step['number'] }} 
                             ? 'bg-[#fca900] text-[#14231B] ring-8 md:ring-[12px] ring-amber-100/90 scale-110 shadow-xl shadow-amber-500/30' 
                             : (effectiveActive > {{ $step['number'] }} 
                                 ? 'bg-[#fca900] text-[#14231B] shadow-md shadow-amber-500/20' 
                                 : 'bg-white border-2 border-gray-300 text-gray-400 shadow-sm group-hover:border-amber-300 group-hover:text-amber-500')">
                        <div x-show="effectiveActive >= {{ $step['number'] }}" class="flex items-center justify-center">
                            <svg class="w-5 h-5 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $step['icon'] !!}
                            </svg>
                        </div>
                        <div x-show="effectiveActive < {{ $step['number'] }}" class="flex items-center justify-center">
                            <span class="text-base md:text-lg font-bold">{{ $step['number'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Even: Content Card | Odd: Spacer on desktop) -->
                @if(!$isOdd)
                <div class="w-full md:w-[calc(50%-48px)] pl-16 md:pl-0 md:text-left">
                    <div class="p-6 md:p-7 rounded-2xl md:rounded-3xl transition-all duration-300"
                         :class="effectiveActive === {{ $step['number'] }} 
                             ? 'bg-[#FFF8E6] border-2 border-[#FCA900]/50 shadow-lg shadow-amber-500/10 scale-[1.02]' 
                             : 'bg-transparent border-2 border-transparent hover:bg-gray-50/70 hover:border-gray-100'">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2 transition-colors"
                              :class="effectiveActive === {{ $step['number'] }} ? 'bg-[#fca900] text-[#14231B]' : 'bg-gray-100 text-gray-500'">
                            Langkah 0{{ $step['number'] }}
                        </span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-2 font-display transition-colors"
                            :class="effectiveActive === {{ $step['number'] }} ? 'text-amber-950' : 'text-gray-900'">
                            {{ $step['title'] }}
                        </h3>
                        <p class="text-sm md:text-base leading-relaxed transition-colors"
                           :class="effectiveActive === {{ $step['number'] }} ? 'text-gray-700 font-medium' : 'text-gray-500'">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </div>
                @else
                <div class="hidden md:block md:w-[calc(50%-48px)]"></div>
                @endif

            </div>
            @endforeach
        </div>

        <!-- Optional Call to action button under timeline -->
        <div class="text-center mt-12 md:mt-16">
            @if($settings['pmb_is_open'] == '1')
            <button @click="showModal = true" class="inline-flex items-center space-x-3 px-8 py-4 bg-[#fca900] hover:bg-[#e09600] text-[#14231B] font-extrabold rounded-xl shadow-lg shadow-amber-500/20 hover:scale-105 transition-all cursor-pointer">
                <span>Daftar Sekarang Melalui Alur di Atas</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
            @endif
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
                            'q' => "Apakah Matla' untuk akhwat?",
                            'a' => "Tidak. Kampus Matla' untuk ikhwan dan akhwat segala usia (sudah lulus SMA/sederajat)."
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

@if($settings['pmb_is_open'] == '1')
<!-- Brosur Section -->
<section class="py-20 bg-white overflow-hidden" id="brosur-pmb">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">Brosur PMB 2026/2027</h2>
            <p class="text-base text-gray-500 max-w-xl mx-auto italic">Klik pada brosur untuk mengunduhnya.</p>
        </div>

        @php $brosurCount = count($brosurs); @endphp

        @if($brosurCount > 0)
        <!-- Carousel Wrapper -->
        <div class="relative max-w-sm mx-auto" id="brosur-carousel">
            <!-- Slides Container -->
            <div class="overflow-hidden rounded-3xl">
                <div class="flex transition-transform duration-500 ease-in-out" id="brosur-track">
                    @foreach($brosurs as $brosur)
                    <div class="w-full flex-shrink-0">
                        <!-- Card -->
                        <div class="relative group cursor-pointer select-none" onclick="downloadBrosur(this)" 
                             data-file="{{ asset('pmb-brosur/' . $brosur->image) }}"
                             data-title="{{ $brosur->title }}">

                            <!-- Image -->
                            <img src="{{ asset('pmb-brosur/' . $brosur->image) }}" 
                                 alt="{{ $brosur->title }}"
                                 class="w-full h-auto block rounded-3xl shadow-xl border border-gray-100">

                            <!-- Download Overlay (muncul saat hover/click) -->
                            <div class="brosur-overlay absolute inset-0 bg-black/50 rounded-3xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="bg-white rounded-full p-5 shadow-2xl mb-3 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </div>
                                <p class="text-white font-bold text-sm tracking-wide">Ketuk untuk Download</p>
                            </div>

                            <!-- Title Badge -->
                            <div class="absolute bottom-3 left-3 right-3">
                                <div class="bg-white/90 backdrop-blur-sm rounded-2xl px-4 py-2 text-center shadow">
                                    <p class="text-gray-900 font-bold text-sm truncate">{{ $brosur->title }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($brosurCount > 1)
            <!-- Prev/Next Buttons -->
            <button onclick="prevSlide()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white shadow-lg rounded-full w-10 h-10 flex items-center justify-center text-gray-700 hover:text-primary transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button onclick="nextSlide()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white shadow-lg rounded-full w-10 h-10 flex items-center justify-center text-gray-700 hover:text-primary transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Dots -->
            <div class="flex justify-center gap-2 mt-5" id="brosur-dots">
                @foreach($brosurs as $i => $b)
                <button onclick="goToSlide({{ $i }})" class="brosur-dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-primary w-6' : 'bg-gray-300' }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        @else
        <div class="w-full max-w-sm mx-auto bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center text-gray-400 p-12 space-y-4">
            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="italic font-medium text-center">Brosur pendaftaran belum tersedia saat ini.</p>
        </div>
        @endif
    </div>
</section>

<script>
    // ---- Brosur Carousel ----
    let currentSlide = 0;
    const totalSlides = {{ $brosurCount }};
    const track = document.getElementById('brosur-track');
    const dots = document.querySelectorAll('.brosur-dot');
    let autoSlideInterval;

    function goToSlide(index) {
        currentSlide = (index + totalSlides) % totalSlides;
        if (track) track.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((d, i) => {
            d.classList.toggle('bg-primary', i === currentSlide);
            d.classList.toggle('w-6', i === currentSlide);
            d.classList.toggle('bg-gray-300', i !== currentSlide);
            d.classList.toggle('w-2.5', i !== currentSlide);
        });
    }

    function nextSlide() { goToSlide(currentSlide + 1); resetAutoSlide(); }
    function prevSlide() { goToSlide(currentSlide - 1); resetAutoSlide(); }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        if (totalSlides > 1) {
            autoSlideInterval = setInterval(() => goToSlide(currentSlide + 1), 4000);
        }
    }

    function downloadBrosur(el) {
        const file = el.dataset.file;
        const a = document.createElement('a');
        a.href = file;
        a.download = el.dataset.title || 'brosur';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Start auto-slide
    if (totalSlides > 1) {
        autoSlideInterval = setInterval(() => goToSlide(currentSlide + 1), 4000);
    }
</script>
@endif

<style>
    .brosur-overlay {
        -webkit-tap-highlight-color: transparent;
    }
    /* On touch devices, show overlay on tap */
    @media (hover: none) {
        .brosur-overlay {
            opacity: 0;
        }
        .group:active .brosur-overlay {
            opacity: 1;
        }
    }
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
<section class="py-20 bg-[#006837] relative overflow-hidden" style="background-image: url('{{ asset('assets/doodle-bg.svg') }}'); background-repeat: repeat;">
    <!-- Decorative background circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/20 rounded-full -translate-x-1/4 translate-y-1/4 blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <div class="w-16 h-16 bg-white/15 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-xl">
             <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Siap Memulai Perjalanan Anda?</h2>
        <p class="text-emerald-50/90 mb-10 max-w-2xl mx-auto text-lg leading-relaxed">
            Bergabunglah dengan ribuan mahasiswa Matla Islamic University dan mulailah perjalanan ilmu Anda sekarang juga.
        </p>

        @if($settings['pmb_is_open'] == '1')
        <button @click="showModal = true" class="inline-block px-10 py-4 bg-[#fca900] hover:bg-[#e09600] text-[#14231B] rounded-xl font-bold text-xl shadow-xl shadow-amber-500/20 transition-all transform hover:scale-105 cursor-pointer">
            Daftar Sekarang
        </button>
        @else
        <div class="inline-block px-10 py-4 bg-red-500/20 text-red-100 border border-red-500/30 rounded-xl font-bold text-xl backdrop-blur-sm">
            Pendaftaran Saat Ini Ditutup
        </div>
        @endif

        <p class="mt-8 text-emerald-100/70 font-medium">
            Butuh bantuan? <a href="https://wa.me/6287784538820" target="_blank" class="text-amber-300 hover:text-amber-200 hover:underline font-semibold transition-colors">Hubungi kami via WhatsApp</a>
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
