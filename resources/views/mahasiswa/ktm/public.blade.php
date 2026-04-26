<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profil Mahasiswa - {{ $user->name }}</title>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#10B981', dark: '#059669' }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased text-gray-800 pb-24">

    <!-- Navbar -->
    <nav class="bg-white px-4 py-4 flex items-center justify-between sticky top-0 z-50 border-b border-gray-100">
        <div class="flex items-center">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain">
        </div>
        <div class="flex items-center space-x-4">
            <!-- Moon Icon (Decorative) -->
            <button class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            <a href="{{ route('login') }}" class="text-primary hover:text-primary-dark">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            </a>
        </div>
    </nav>

    <div class="max-w-md mx-auto px-4 mt-6">
        <h1 class="text-xl font-bold text-gray-900 mb-4">Profil Mahasiswa</h1>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center space-x-4 mb-5">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-gray-50">
                    <img src="{{ $user->foto_profil }}" class="w-full h-full object-cover" alt="Foto">
                </div>
                <div>
                    <h2 class="font-extrabold text-gray-900 text-[15px] leading-tight uppercase">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-xs mt-1">NIM: {{ $user->nim ?? '-' }}</p>
                    <p class="text-gray-500 text-xs mt-0.5 uppercase">{{ $user->education['program_studi'] ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <div class="flex items-center space-x-2">
                    @if(strtoupper($user->status) == 'AKTIF')
                        <span class="px-3 py-1 bg-emerald-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase">AKTIF</span>
                    @else
                        <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black rounded-md tracking-wider uppercase">{{ $user->status }}</span>
                    @endif
                    
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md flex items-center">
                        <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Profil Resmi Matla
                    </span>
                </div>
                <button class="text-gray-400 hover:text-gray-600" onclick="navigator.share({title:'Profil KTM', url:window.location.href})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Tentang Saya -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-3 ml-1 text-[15px]">Tentang Saya</h3>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                @if($user->profil && $user->profil->tentang_saya)
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $user->profil->tentang_saya }}</p>
                @else
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                        <p class="text-gray-500 text-xs mb-3">Mahasiswa belum mengisi deskripsi diri.</p>
                        <a href="{{ route('login') }}" class="text-primary font-bold text-xs inline-flex items-center hover:underline">
                            Login untuk update profil <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Skill -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-3 ml-1 text-[15px]">Skill</h3>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                @if($user->skills->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->skills as $skill)
                        <div class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center">
                            <span class="text-sm font-bold text-gray-800">{{ $skill->nama_skill }}</span>
                            <span class="ml-2 text-[9px] font-black text-gray-400 uppercase tracking-wider">{{ $skill->level }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100 flex flex-col items-center">
                        <svg class="w-6 h-6 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <p class="text-gray-500 text-xs font-medium">Belum ada skill</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Karya & Portofolio -->
        <div class="mb-8">
            <h3 class="font-bold text-gray-900 mb-3 ml-1 text-[15px]">Karya & Portofolio</h3>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                @if($user->portofolio->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->portofolio as $porto)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-gray-900 text-sm">{{ $porto->judul }}</h4>
                            <p class="text-xs text-gray-500 mt-1 mb-3">{{ $porto->deskripsi }}</p>
                            <div class="flex flex-wrap gap-2">
                                @if($porto->link)
                                <a href="{{ $porto->link }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-[10px] font-bold rounded-lg shadow-sm">
                                    <svg class="w-3 h-3 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    Kunjungi Link
                                </a>
                                @endif
                                @if($porto->file)
                                <a href="{{ asset('storage/' . $porto->file) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-[10px] font-bold rounded-lg shadow-sm">
                                    <svg class="w-3 h-3 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat File
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100 flex flex-col items-center">
                        <svg class="w-6 h-6 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-gray-500 text-xs font-medium">Belum ada portofolio</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-6 text-center border-t border-gray-100 mt-6">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('assets/logo.png') }}" class="h-8 w-auto object-contain opacity-60 grayscale">
            </div>
            <p class="text-xs text-gray-400 font-medium leading-relaxed">
                &copy; {{ date('Y') }} Sistem Informasi Akademik Matla University.
                <br>Dokumen/Kartu ini valid dan terverifikasi secara elektronik.
            </p>
        </div>
    </div>

    <!-- Sticky Bottom Button -->
    <div class="fixed bottom-6 left-0 right-0 px-4 flex justify-center z-40">
        <button onclick="window.history.back()" class="bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-xl flex items-center space-x-2 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </button>
    </div>

</body>
</html>
