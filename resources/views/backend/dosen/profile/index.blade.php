@extends('layouts.dosen')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Manajemen Profil')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Profil -->
    <div class="relative">
        <div class="h-48 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-3xl overflow-hidden shadow-2xl shadow-emerald-200">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
            <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
        
        <div class="px-8 -mt-20 relative z-10 flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-6">
            <div class="relative group">
                <div class="w-40 h-40 rounded-3xl border-8 border-white bg-white overflow-hidden shadow-xl shadow-gray-200/50 transition-transform duration-500 group-hover:scale-[1.02]">
                    <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
                <label for="avatar-input" class="absolute bottom-2 right-2 p-3 bg-white hover:bg-emerald-50 text-emerald-600 rounded-2xl shadow-lg border border-emerald-100 cursor-pointer transition-all hover:scale-110 active:scale-95 group/btn">
                    <svg class="w-5 h-5 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
            </div>
            
            <div class="pb-4 flex-1">
                <h1 class="text-3xl font-extrabold text-white drop-shadow-sm">{{ $user->name }}</h1>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-xs font-bold uppercase tracking-wider border border-white/30">
                        {{ $user->role }}
                    </span>
                    @if($user->nidn)
                    <span class="px-4 py-1.5 bg-emerald-500/80 backdrop-blur-md text-white rounded-full text-xs font-bold border border-emerald-400/30">
                        NIDN: {{ $user->nidn }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form Utama -->
    <form action="{{ route('backend.dosen.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        @csrf
        <input type="file" id="avatar-input" name="avatar" class="hidden" onchange="previewAvatar(this)">
        
        <!-- Kolom Kiri: Info Utama -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Data Personal -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Informasi Personal</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Detail identitas Anda</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="Masukkan nama lengkap...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="alamat@email.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">NIDN</label>
                        <input type="text" name="nidn" value="{{ old('nidn', $user->nidn) }}" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="Nomor Induk Dosen Nasional...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Nomor Telepon / WA</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="08XXXXXXXXX">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Biografi Singkat</label>
                        <textarea name="bio" rows="4" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="Ceritakan sedikit tentang Anda...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Alamat Tinggal</label>
                        <textarea name="address" rows="2" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="Alamat lengkap saat ini...">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pendidikan -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Riwayat Pendidikan</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Latar belakang akademik</p>
                        </div>
                    </div>
                    <button type="button" onclick="addEducation()" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all font-bold text-xs uppercase tracking-widest flex items-center space-x-2">
                        <span>Tambah</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>

                <div id="education-container" class="space-y-4">
                    @php 
                        $eduData = $user->education ?? [['level' => '', 'institution' => '', 'year' => '']];
                    @endphp
                    @foreach($eduData as $index => $edu)
                    <div class="education-item bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200 grid grid-cols-1 md:grid-cols-12 gap-4 relative group">
                        <div class="md:col-span-3 space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenjang</label>
                            <input type="text" name="education_level[]" value="{{ $edu['level'] ?? '' }}" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Misal: S2">
                        </div>
                        <div class="md:col-span-6 space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Institusi</label>
                            <input type="text" name="education_institution[]" value="{{ $edu['institution'] ?? '' }}" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Nama Kampus/Sekolah">
                        </div>
                        <div class="md:col-span-3 space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Lulus</label>
                            <input type="text" name="education_year[]" value="{{ $edu['year'] ?? '' }}" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Tahun">
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-8 h-8 bg-white text-red-500 rounded-full shadow-sm border border-red-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Skill & Keamanan -->
        <div class="space-y-8">
            <!-- Expertise -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Keahlian</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Bidang fokus Anda</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-tighter ml-1">Keahlian (Pisahkan dengan koma)</label>
                    <input type="text" name="expertise" value="{{ isset($user->expertise) ? implode(',', $user->expertise) : '' }}" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/5 transition-all font-medium text-gray-700 outline-none" placeholder="Contoh: Laravel, Data Science, AI">
                    <div class="flex flex-wrap gap-2 pt-2">
                        @if(isset($user->expertise))
                            @foreach($user->expertise as $skill)
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-purple-100">
                                {{ trim($skill) }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="p-3 bg-pink-50 text-pink-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Tautan Publik</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Media & Akademik</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">LinkedIn Profile</label>
                        <input type="text" name="linkedin" value="{{ $user->social_links['linkedin'] ?? '' }}" class="w-full px-5 py-3 bg-gray-50 border border-transparent rounded-xl focus:border-pink-500 outline-none text-sm font-medium" placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">GitHub / Portofolio</label>
                        <input type="text" name="github" value="{{ $user->social_links['github'] ?? '' }}" class="w-full px-5 py-3 bg-gray-50 border border-transparent rounded-xl focus:border-pink-500 outline-none text-sm font-medium" placeholder="https://github.com/username">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Google Scholar</label>
                        <input type="text" name="scholar" value="{{ $user->social_links['scholar'] ?? '' }}" class="w-full px-5 py-3 bg-gray-50 border border-transparent rounded-xl focus:border-pink-500 outline-none text-sm font-medium" placeholder="https://scholar.google.com/cit...">
                    </div>
                </div>
            </div>

            <!-- Ganti Password -->
            <div x-data="{ show: false }" class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md overflow-hidden">
                <button type="button" @click="show = !show" class="w-full flex items-center justify-between group">
                    <div class="flex items-center space-x-3 text-left">
                        <div class="p-3 bg-red-50 text-red-600 rounded-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900 tracking-tight">Ubah Password</h2>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Keamanan Akun</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="show ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="show" x-collapse x-cloak class="pt-8 space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru</label>
                        <input type="password" name="password" class="w-full px-5 py-3 bg-gray-50 border border-transparent rounded-xl focus:border-red-500 outline-none text-sm font-medium" placeholder="••••••••">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-gray-50 border border-transparent rounded-xl focus:border-red-500 outline-none text-sm font-medium" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full p-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[2rem] font-bold text-lg shadow-xl shadow-emerald-200 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center space-x-3 group">
                <span>Simpan Perubahan</span>
                <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3" />
                </svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addEducation() {
        const container = document.getElementById('education-container');
        const newItem = document.createElement('div');
        newItem.className = 'education-item bg-gray-50/50 p-6 rounded-3xl border border-dashed border-gray-200 grid grid-cols-1 md:grid-cols-12 gap-4 relative group animate-in zoom-in duration-300';
        newItem.innerHTML = `
            <div class="md:col-span-3 space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenjang</label>
                <input type="text" name="education_level[]" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Misal: S1">
            </div>
            <div class="md:col-span-6 space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Institusi</label>
                <input type="text" name="education_institution[]" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Nama Kampus/Sekolah">
            </div>
            <div class="md:col-span-3 space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Lulus</label>
                <input type="text" name="education_year[]" class="w-full px-4 py-3 bg-white border border-transparent rounded-xl focus:border-blue-500 outline-none text-sm font-bold" placeholder="Tahun">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-8 h-8 bg-white text-red-500 rounded-full shadow-sm border border-red-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        `;
        container.appendChild(newItem);
    }
</script>
@endpush
@endsection
