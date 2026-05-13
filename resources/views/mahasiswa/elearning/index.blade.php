@extends('layouts.app')

@section('title', 'Ruang Kelas E-Learning')

@section('content')
<div class="bg-gray-50 min-h-screen pb-24 pt-8 lg:pt-12">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ruang Kelas E-Learning</h1>
    <p class="text-gray-500 mt-2 font-medium">Akses silabus, materi video, dan kuis perkuliahan Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jadwals as $jadwal)
        <a href="{{ route('mahasiswa.elearning.jadwal', $jadwal->id) }}" class="group block bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:border-primary/20 transition-all duration-300 transform hover:-translate-y-1">
            <div class="h-32 bg-gradient-to-br from-primary/10 to-indigo-50 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+CjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIyIiBmaWxsPSIjMDAwIi8+Cjwvc3ZnPg==')]"></div>
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-xl shadow-sm">
                    <span class="text-xs font-black text-primary uppercase tracking-widest">{{ $jadwal->sks }} SKS</span>
                </div>
            </div>
            <div class="p-6 relative">
                <div class="w-12 h-12 bg-white rounded-2xl shadow-md flex items-center justify-center text-primary -mt-12 mb-4 border border-gray-50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 mb-2">{{ $jadwal->mata_kuliah }}</h3>
                <p class="text-sm font-medium text-gray-500 line-clamp-1 mb-4">{{ $jadwal->dosen->name ?? 'Dosen Belum Ditentukan' }}</p>
                
                <div class="flex items-center justify-between text-xs font-bold text-gray-400">
                    <span class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">SMT {{ $jadwal->semester }}</span>
                    <span class="flex items-center text-primary group-hover:translate-x-1 transition-transform">
                        Masuk Kelas <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Kelas</h3>
            <p class="text-gray-500 font-medium">Anda belum terdaftar di mata kuliah apapun untuk semester ini.</p>
        </div>
    @endforelse
        </div>
    </div>
</div>
@endsection
