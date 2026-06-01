@extends('layouts.app')

@section('title', 'Leaderboard Duta Kampus - Matla Islamic University')

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden bg-white min-h-screen">
    <!-- Grid Background & Tech Lines -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="w-full h-full opacity-[0.05]" style="background-image: linear-gradient(#059669 1px, transparent 1px), linear-gradient(90deg, #059669 1px, transparent 1px); background-size: 80px 80px;"></div>
    </div>
    
    <!-- Top Fade -->
    <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-white via-white/80 to-transparent z-0 pointer-events-none"></div>

    <!-- Tech Circuit Lines (Left) -->
    <div class="absolute left-0 top-[40%] w-32 h-64 z-0 opacity-20 pointer-events-none hidden lg:block">
        <svg viewBox="0 0 100 200" fill="none" stroke="#059669" stroke-width="1.5">
            <path d="M0 20 L40 20 L50 30 L50 180" />
            <circle cx="50" cy="180" r="3" fill="#059669" />
            <path d="M0 60 L20 60 L30 70 L30 150 L40 160 L50 160" />
            <circle cx="50" cy="160" r="2" fill="#059669" />
            <path d="M0 100 L15 100 L25 110 L25 130" />
            <circle cx="25" cy="130" r="2" fill="#059669" />
        </svg>
    </div>

    <!-- Bold Green Accents -->
    <div class="absolute right-0 top-[25%] w-64 h-16 bg-emerald-600/90 z-0" style="clip-path: polygon(20px 0, 100% 0, 100% 100%, 0 100%);"></div>
    <div class="absolute right-32 top-[22%] w-16 h-4 bg-emerald-600/90 z-0" style="transform: skewX(-45deg);"></div>
    <div class="absolute right-52 top-[22%] w-8 h-4 bg-emerald-600/90 z-0" style="transform: skewX(-45deg);"></div>
    
    <div class="absolute left-10 bottom-[10%] w-64 h-8 bg-emerald-600/20 z-0" style="clip-path: polygon(0 0, 100% 0, calc(100% - 20px) 100%, 0 100%);"></div>
    <div class="absolute right-[20%] bottom-[5%] w-16 h-4 bg-emerald-600/90 z-0" style="transform: skewX(-45deg);"></div>
    <div class="absolute right-[25%] bottom-[5%] w-8 h-4 bg-emerald-600/90 z-0" style="transform: skewX(-45deg);"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 mt-8">
            <div class="inline-flex items-center justify-center bg-[#469e6b] text-white text-[9px] font-black uppercase px-6 py-2 tracking-[0.2em] mb-6 shadow-md shadow-emerald-600/20" style="clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0% 100%);">
                Program Kemitraan Dakwah
            </div>
            <h1 class="text-4xl lg:text-6xl font-black text-[#0f172a] leading-tight tracking-tight mb-4 uppercase">
                Duta Kampus <br> <span class="text-[#469e6b]">Leaderboard</span>
            </h1>
            <p class="text-sm text-gray-600 font-medium leading-relaxed max-w-2xl mx-auto">
                Apresiasi bagi para pejuang ilmu yang telah membantu menyebarkan informasi dan mengajak calon mahasiswa untuk bergabung di Matla Islamic University.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-5xl mx-auto items-start">
            
            <!-- Left Column: Top 1 & Top 10 -->
            <div class="lg:col-span-6 flex flex-col items-center lg:items-start w-full">
                
                @if($topDutas->count() > 0)
                <!-- Top 1 Card -->
                <div class="w-full max-w-xs mx-auto lg:mx-0 mb-12 relative pt-20">
                    <!-- Shield Graphic -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-36 h-44 z-20 drop-shadow-[0_15px_20px_rgba(5,150,105,0.3)] hover:-translate-y-2 transition-transform duration-500">
                        <svg viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                            <!-- Outer Metallic Rim -->
                            <path d="M50 0 L95 20 V50 C95 85 50 120 50 120 C50 120 5 85 5 50 V20 L50 0 Z" fill="url(#gradOuter)" stroke="#fff" stroke-width="1.5"/>
                            <!-- Inner Green Shield -->
                            <path d="M50 8 L85 24 V50 C85 80 50 110 50 110 C50 110 15 80 15 50 V24 L50 8 Z" fill="url(#gradInner)" stroke="#065f46" stroke-width="2"/>
                            <!-- Laurels (Left) -->
                            <path d="M25 75 Q15 55 25 35" stroke="#a7f3d0" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <path d="M20 65 L28 60" stroke="#a7f3d0" stroke-width="2" stroke-linecap="round"/>
                            <path d="M18 50 L26 48" stroke="#a7f3d0" stroke-width="2" stroke-linecap="round"/>
                            <!-- Laurels (Right) -->
                            <path d="M75 75 Q85 55 75 35" stroke="#a7f3d0" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <path d="M80 65 L72 60" stroke="#a7f3d0" stroke-width="2" stroke-linecap="round"/>
                            <path d="M82 50 L74 48" stroke="#a7f3d0" stroke-width="2" stroke-linecap="round"/>
                            <!-- The '1' -->
                            <text x="50" y="68" font-family="Arial, sans-serif" font-weight="900" font-size="44" fill="#ffffff" text-anchor="middle" style="filter: drop-shadow(0px 4px 4px rgba(0,0,0,0.3));">1</text>
                            
                            <defs>
                                <linearGradient id="gradOuter" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#d1fae5" />
                                    <stop offset="50%" stop-color="#469e6b" />
                                    <stop offset="100%" stop-color="#064e3b" />
                                </linearGradient>
                                <linearGradient id="gradInner" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#82c7a3" />
                                    <stop offset="100%" stop-color="#469e6b" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <!-- Card Body -->
                    <div class="bg-gradient-to-b from-[#e5f1ea] to-[#c7e3d3] p-8 pt-28 pb-12 text-center shadow-[0_15px_40px_-10px_rgba(70,158,107,0.3)] relative" style="clip-path: polygon(24px 0, 100% 0, 100% calc(100% - 24px), calc(100% - 24px) 100%, 0 100%, 0 24px);">
                        <!-- Inner borders for bevel -->
                        <div class="absolute inset-[3px] border-[3px] border-white/60 pointer-events-none" style="clip-path: polygon(21px 0, 100% 0, 100% calc(100% - 21px), calc(100% - 21px) 100%, 0 100%, 0 21px);"></div>
                        <div class="absolute inset-[6px] border border-[#a2d4b8] pointer-events-none" style="clip-path: polygon(18px 0, 100% 0, 100% calc(100% - 18px), calc(100% - 18px) 100%, 0 100%, 0 18px);"></div>
                        <div class="absolute bottom-0 left-0 w-full h-3 bg-[#469e6b]"></div>

                        <h3 class="text-2xl font-black text-[#0f172a] uppercase tracking-widest mb-1">{{ $topDutas[0]->display_name }}</h3>
                        <p class="text-[10px] font-black text-[#469e6b] uppercase tracking-[0.2em] mb-6">Puncak Klasemen</p>
                        
                        <div class="flex items-center justify-center space-x-2 text-[#0f172a]">
                            <span class="text-5xl font-black" style="text-shadow: 1px 1px 0px rgba(255,255,255,1);">{{ $topDutas[0]->registrations_count }}</span>
                            <span class="text-[9px] uppercase font-black tracking-[0.2em] mt-3">Referral</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Top 10 List -->
                <div class="w-full">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-8 h-[2px] bg-[#469e6b]"></div>
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">Top 10 Pejuang</h2>
                    </div>

                    <div class="space-y-0 relative">
                        <div class="absolute left-4 top-0 bottom-0 w-[1px] bg-gray-200 z-0"></div>
                        @foreach($topDutas->skip(1) as $index => $duta)
                        <div class="flex items-center justify-between py-4 border-b border-gray-200 relative group hover:bg-gray-50/50 transition-colors z-10">
                            <div class="flex items-center space-x-4 pl-1">
                                <div class="w-7 h-7 flex items-center justify-center font-black text-white text-xs {{ $index == 0 ? 'bg-[#469e6b]' : 'bg-[#469e6b]/80' }} shadow-sm" style="clip-path: polygon(6px 0, 100% 0, 100% calc(100% - 6px), calc(100% - 6px) 100%, 0 100%, 0 6px);">
                                    {{ $index + 2 }}
                                </div>
                                <div class="font-black text-[#1e293b] uppercase tracking-wider text-xs">{{ $duta->display_name }}</div>
                            </div>
                            <div class="flex items-center space-x-3 pr-2">
                                <div class="text-xs font-black text-[#0f172a]">{{ $duta->registrations_count }} <span class="text-[9px] text-gray-500 uppercase tracking-widest ml-1">Referral</span></div>
                                <!-- Tech corner decoration -->
                                <div class="w-2 h-3 bg-[#469e6b]" style="clip-path: polygon(100% 0, 100% 100%, 0 100%);"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Activities & Banner -->
            <div class="lg:col-span-6 flex flex-col lg:pl-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-8 h-[2px] bg-[#469e6b]"></div>
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">Aktivitas Terbaru</h2>
                </div>

                <!-- Scrollable Activity Feed -->
                <div class="bg-[#f4f7f6] border border-gray-200/60 p-6 shadow-inner relative mb-8 flex-1" style="clip-path: polygon(16px 0, 100% 0, 100% calc(100% - 16px), calc(100% - 16px) 100%, 0 100%, 0 16px);">
                    <div class="max-h-[350px] overflow-y-auto pr-2 custom-scrollbar relative">
                        <!-- Vertical Timeline Line -->
                        <div class="absolute left-[15px] top-4 bottom-4 w-[2px] bg-[#b1d4c0]"></div>
                        
                        <div class="space-y-6">
                            @forelse($recentActivities as $activity)
                            <div class="relative flex items-start space-x-4 group">
                                <!-- Hexagon Icon -->
                                <div class="relative z-10 w-8 h-8 mt-1 flex items-center justify-center bg-[#175e3c] text-[#8fd2ad] font-bold text-xs shrink-0 shadow-sm" style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);">
                                    {{ substr($activity->full_name, 0, 1) }}
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 pb-4 border-b border-gray-300 border-dashed group-hover:border-[#469e6b] transition-colors">
                                    <p class="text-[11px] font-black text-[#0f172a] uppercase tracking-wider leading-relaxed">
                                        {{ substr($activity->full_name, 0, 3) }}*** <span class="text-gray-500 font-bold mx-1">Melakukan Referral</span>
                                    </p>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">Duta: <span class="text-[#469e6b]">{{ $activity->affiliate->display_name }}</span></p>
                                    <p class="text-[9px] text-gray-400 font-bold mt-1.5 uppercase tracking-widest">{{ $activity->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-gray-400 font-bold text-sm italic">Belum ada aktivitas.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Banner CTA -->
                <div class="relative bg-[#d7ecd8] p-8 shadow-sm group" style="clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px);">
                    <!-- Abstract Tech Lines Background -->
                    <div class="absolute inset-0 z-0 opacity-40 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/60 to-transparent"></div>
                    <div class="absolute inset-0 z-0 opacity-10" style="background-image: linear-gradient(30deg, #10B981 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="absolute inset-0 z-0 opacity-10" style="background-image: linear-gradient(-30deg, #10B981 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="absolute inset-0 z-0 opacity-20" style="background-image: linear-gradient(90deg, #10B981 1px, transparent 1px), linear-gradient(0deg, #10B981 1px, transparent 1px); background-size: 40px 40px;"></div>
                    
                    <div class="relative z-10">
                        <h4 class="text-[#0f172a] font-black text-lg uppercase tracking-widest mb-2">Ingin Jadi Duta Kampus?</h4>
                        <p class="text-[#0f172a] text-xs font-medium leading-relaxed mb-6 max-w-[280px]">Bantu sebarkan manfaat ilmu dan dapatkan apresiasi (bisyaroh) dari kampus.</p>
                        
                        <a href="https://wa.me/6287784538820?text={{ rawurlencode('Assalamualaikum warahmatullahi wabarakatuh. Halo Admin, saya tertarik dan ingin mendaftar menjadi Duta Kampus Matla University. Mohon panduan dan informasinya lebih lanjut. Terima kasih.') }}" target="_blank" class="inline-block bg-[#469e6b] text-white font-black uppercase tracking-widest text-[10px] px-6 py-3 hover:bg-[#398458] transition-colors shadow-lg shadow-emerald-700/30" style="clip-path: polygon(8px 0, 100% 0, 100% calc(100% - 8px), calc(100% - 8px) 100%, 0 100%, 0 8px);">
                            Daftar Jadi Duta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Custom Scrollbar for activities */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #e2e8f0;
        border-radius: 0;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 0;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #469e6b;
    }
</style>
@endsection
