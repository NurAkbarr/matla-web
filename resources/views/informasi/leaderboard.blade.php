@extends('layouts.app')

@section('title', 'Leaderboard Duta Kampus - Matla Islamic University')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 lg:py-32 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/bg-web.png') }}" alt="Background" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-white via-white/80 to-white"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-[0.3em] rounded-none mb-4 inline-block">
                Program Kemitraan Dakwah
            </span>
            <h1 class="text-4xl lg:text-6xl font-black text-gray-900 leading-tight tracking-tight mb-6 uppercase">
                Duta Kampus <br> <span class="text-emerald-600">Leaderboard</span>
            </h1>
            <p class="text-lg text-gray-600 font-medium leading-relaxed">
                Apresiasi bagi para pejuang ilmu yang telah membantu menyebarkan informasi dan mengajak calon mahasiswa untuk bergabung di Matla Islamic University.
            </p>
        </div>

        <!-- Top 3 Podium -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end max-w-5xl mx-auto mb-20">
            @php
                $podium = $topDutas->take(3);
            @endphp
            
            @if($podium->count() >= 2)
            <!-- Rank 2 -->
            <div class="order-2 md:order-1 group">
                <div class="bg-white border-2 border-gray-100 p-8 pt-12 relative text-center shadow-sm hover:shadow-xl transition-all rounded-none overflow-hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gray-200"></div>
                    <div class="w-16 h-16 bg-gray-100 rounded-none flex items-center justify-center mx-auto mb-4 border-2 border-white shadow-sm font-black text-2xl text-gray-400">2</div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-wider mb-1">{{ $podium[1]->display_name }}</h3>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Duta Kampus</p>
                    <div class="text-3xl font-black text-emerald-600">{{ $podium[1]->registrations_count }} <span class="text-xs uppercase text-gray-400">Referral</span></div>
                </div>
            </div>
            @endif

            @if($podium->count() >= 1)
            <!-- Rank 1 (The King) -->
            <div class="order-1 md:order-2 group z-10">
                <div class="bg-emerald-600 p-10 pt-16 relative text-center shadow-2xl hover:scale-105 transition-all rounded-none overflow-hidden text-white -translate-y-4">
                    <div class="absolute top-4 left-1/2 -translate-x-1/2">
                        <svg class="w-10 h-10 text-yellow-400 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div class="w-20 h-20 bg-white/20 rounded-none flex items-center justify-center mx-auto mb-6 border-4 border-white/40 shadow-sm font-black text-4xl text-white">1</div>
                    <h3 class="text-2xl font-black uppercase tracking-widest mb-1">{{ $podium[0]->display_name }}</h3>
                    <p class="text-sm font-bold text-white/70 uppercase tracking-widest mb-6">Puncak Klasemen</p>
                    <div class="text-5xl font-black">{{ $podium[0]->registrations_count }} <span class="text-xs uppercase text-white/60">Referral</span></div>
                </div>
            </div>
            @endif

            @if($podium->count() >= 3)
            <!-- Rank 3 -->
            <div class="order-3 group">
                <div class="bg-white border-2 border-gray-100 p-8 pt-12 relative text-center shadow-sm hover:shadow-xl transition-all rounded-none overflow-hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-amber-600/30"></div>
                    <div class="w-16 h-16 bg-amber-50 rounded-none flex items-center justify-center mx-auto mb-4 border-2 border-white shadow-sm font-black text-2xl text-amber-600/60">3</div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-wider mb-1">{{ $podium[2]->display_name }}</h3>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Duta Kampus</p>
                    <div class="text-3xl font-black text-emerald-600">{{ $podium[2]->registrations_count }} <span class="text-xs uppercase text-gray-400">Referral</span></div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <!-- Full Leaderboard -->
            <div>
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-1 bg-emerald-600"></div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-widest">Top 10 Pejuang</h2>
                </div>
                
                <div class="bg-white border border-gray-100 rounded-none shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @foreach($topDutas->skip(3) as $index => $duta)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 w-16">
                                    <span class="text-sm font-black text-gray-400">#{{ $index + 4 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-gray-800 uppercase tracking-wider text-sm">{{ $duta->display_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-lg font-black text-emerald-600">{{ $duta->registrations_count }} <span class="text-[10px] text-gray-400">MHS</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div>
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-1 bg-emerald-600"></div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-widest">Aktivitas Terbaru</h2>
                </div>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-4 bg-white p-6 border border-gray-100 shadow-sm relative overflow-hidden rounded-none group hover:border-emerald-200 transition-all">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 -mr-8 -mt-8 rotate-45 transition-all group-hover:bg-emerald-100"></div>
                        
                        <div class="w-12 h-12 bg-emerald-600 flex items-center justify-center text-white shrink-0 rounded-none font-black text-xl">
                            {{ substr($activity->full_name, 0, 1) }}
                        </div>
                        
                        <div>
                            <p class="text-sm font-black text-gray-900 uppercase tracking-wider">
                                {{ substr($activity->full_name, 0, 3) }}*** 
                                <span class="text-[10px] font-bold text-gray-400 ml-1">BARU SAJA MENDAFTAR</span>
                            </p>
                            <p class="text-xs text-gray-500 font-bold mt-1 uppercase tracking-widest">
                                Melalui Duta: <span class="text-emerald-600 font-black">{{ $activity->affiliate->display_name }}</span>
                            </p>
                            <p class="text-[9px] text-gray-300 font-bold mt-2 uppercase tracking-[0.2em]">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-200">
                        <p class="text-gray-400 font-bold italic">Belum ada aktivitas pendaftaran terbaru.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-12 p-8 bg-gray-900 text-white rounded-none relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xl font-black uppercase tracking-widest mb-2">Ingin Jadi Duta Kampus?</h4>
                        <p class="text-sm text-gray-400 font-medium mb-6">Bantu sebarkan manfaat ilmu dan dapatkan apresiasi (bisyaroh) dari kampus.</p>
                        <a href="{{ route('kontak') }}" class="inline-block px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black uppercase tracking-widest text-xs transition-all">
                            Daftar Jadi Duta
                        </a>
                    </div>
                    <div class="absolute bottom-0 right-0 opacity-10 -mr-10 -mb-10 pointer-events-none">
                        <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
