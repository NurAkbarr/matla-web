@extends('layouts.backend')

@section('title', 'Detail User: ' . $user->name)
@section('breadcrumb', 'Detail User')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Back Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('backend.admin.users.index') }}" class="flex items-center space-x-2 text-gray-400 hover:text-primary transition-colors group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-widest">Kembali ke Daftar</span>
        </a>
        <div class="flex space-x-3">
            <a href="{{ route('backend.admin.users.edit', $user->id) }}" class="px-6 py-2 bg-white border border-gray-100 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                Edit User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Profile -->
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-br from-emerald-500/10 to-blue-500/10"></div>
                
                <div class="relative z-10">
                    <div class="w-32 h-32 mx-auto rounded-3xl border-4 border-white shadow-xl overflow-hidden mb-4">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mt-1">{{ $user->role }}</p>
                    
                    <div class="mt-8 pt-8 border-t border-gray-50 space-y-4">
                        <div class="flex items-center justify-between text-left">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Email</span>
                            <span class="text-sm font-medium text-gray-700">{{ $user->email }}</span>
                        </div>
                        @if($user->nidn)
                        <div class="flex items-center justify-between text-left">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">NIDN</span>
                            <span class="text-sm font-medium text-gray-700">{{ $user->nidn }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between text-left">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Terdaftar</span>
                            <span class="text-sm font-medium text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            @if(isset($user->social_links))
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest px-2">Tautan Publik</h3>
                <div class="grid grid-cols-3 gap-2">
                    @if($user->social_links['linkedin'] ?? false)
                    <a href="{{ $user->social_links['linkedin'] }}" target="_blank" class="p-3 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    @endif
                    @if($user->social_links['github'] ?? false)
                    <a href="{{ $user->social_links['github'] }}" target="_blank" class="p-3 bg-gray-900 text-white rounded-2xl flex items-center justify-center hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    @endif
                    @if($user->social_links['scholar'] ?? false)
                    <a href="{{ $user->social_links['scholar'] }}" target="_blank" class="p-3 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Detail Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Bio & Info -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm space-y-10">
                <section>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Profil & Biografi</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Telepon</p>
                                <p class="text-sm font-bold text-gray-700">{{ $user->phone ?? 'Belum diisi' }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat</p>
                                <p class="text-sm font-bold text-gray-700 leading-relaxed">{{ $user->address ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 pt-4 border-t border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Biografi</p>
                            <p class="text-sm font-medium text-gray-600 leading-relaxed">{{ $user->bio ?? 'Belum ada biografi yang ditambahkan.' }}</p>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-50">

                <section>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Pendidikan & Keahlian</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Riwayat Pendidikan</p>
                            <div class="space-y-4">
                                @forelse($user->education ?? [] as $edu)
                                <div class="flex space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black">
                                        {{ $edu['level'] ?? '' }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $edu['institution'] ?? '' }}</p>
                                        <p class="text-xs font-medium text-gray-400">Lulus Tahun {{ $edu['year'] ?? '' }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-gray-400 italic">Data pendidikan belum tersedia.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bidang Keahlian</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->expertise ?? [] as $skill)
                                <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-purple-100">
                                    {{ $skill }}
                                </span>
                                @empty
                                <p class="text-xs text-gray-400 italic">Belum ada keahlian khusus.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
