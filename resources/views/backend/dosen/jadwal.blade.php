@extends('layouts.dosen')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Jadwal Mengajar</h1>
            <p class="text-gray-500 font-medium">Daftar mata kuliah yang Anda ampu semester ini</p>
        </div>
    </div>

    <!-- Calendar/Schedule View -->
    <div class="space-y-6">
        @forelse($jadwals as $jadwal)
        <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Info Utama -->
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">
                            {{ $jadwal->hari }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm font-bold text-gray-500 italic">Semester {{ $jadwal->semester }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm font-bold text-primary italic">{{ $jadwal->sks }} SKS</span>
                    </div>
                    
                    <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-2 leading-tight group-hover:text-primary transition-colors">
                        {{ $jadwal->mata_kuliah }}
                    </h3>
                    
                    <div class="flex flex-wrap gap-4 text-sm font-bold text-gray-400">
                        <div class="flex items-center space-x-2">
                             <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <span>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                             <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                             <span>Ruang: {{ $jadwal->ruang }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action / Stats -->
                <div class="flex items-center justify-between md:flex-col md:items-end gap-4 border-t border-gray-50 md:border-none pt-6 md:pt-0">
                    <div class="flex flex-col md:text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Peserta</span>
                        <span class="text-2xl font-black text-gray-900">{{ $jadwal->participants->count() }} Mahasiswa</span>
                    </div>
                    
                    <!-- Trigger Modal / Participant View -->
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all flex items-center space-x-2 shadow-lg shadow-primary/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span class="text-sm">Lihat Peserta</span>
                        </button>

                        <!-- Modal -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-4"
                             class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                            
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
                            
                            <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                    <div>
                                        <h4 class="text-xl font-black text-gray-900 leading-tight">Daftar Mahasiswa</h4>
                                        <p class="text-sm font-bold text-gray-500 italic">{{ $jadwal->mata_kuliah }}</p>
                                    </div>
                                    <button @click="open = false" class="p-2 bg-white text-gray-400 hover:text-red-500 rounded-xl shadow-sm transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                
                                <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-4">
                                    @forelse($jadwal->participants as $mhs)
                                    <div class="flex items-center space-x-4 p-4 rounded-3xl border border-gray-50 hover:bg-gray-50 transition-colors">
                                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-black">
                                            {{ substr($mhs->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-none mb-1">{{ $mhs->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $mhs->email }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="py-12 text-center">
                                        <p class="text-gray-400 italic font-bold">Belum ada mahasiswa terdaftar.</p>
                                    </div>
                                    @endforelse
                                </div>
                                
                                <div class="p-6 bg-gray-50 text-center">
                                    <button @click="open = false" class="text-sm font-black text-primary hover:underline uppercase tracking-widest">Tutup Jendela</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-gray-100">
            <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2">Tidak Ada Jadwal Mengajar</h3>
            <p class="text-gray-500 font-medium">Anda belum memiliki jadwal mengajar yang terdaftar untuk semester ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
