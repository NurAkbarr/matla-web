@extends('layouts.app')

@section('title', 'Kelola Profil & Portofolio')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 lg:px-12 max-w-6xl">
        <div class="mb-6">
            <a href="{{ route('backend.mahasiswa.dashboard') }}" class="text-gray-500 hover:text-primary font-bold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Lengkapi Profil & Portofolio Anda</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kiri: Profil & Foto -->
            <div class="col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                    <h2 class="font-bold text-lg mb-4 border-b pb-2">Identitas Diri</h2>
                    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="text-center mb-4">
                            <img src="{{ $user->foto_profil }}" class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-gray-50 mb-3">
                            <input type="file" name="foto" accept="image/*" class="text-xs w-full block border p-2 rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ $user->tanggal_lahir }}" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" {{ $user->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $user->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Domisili</label>
                            <textarea name="address" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3" placeholder="Alamat lengkap saat ini...">{{ $user->address }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tentang Saya</label>
                            <textarea name="tentang_saya" rows="4" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3" placeholder="Ceritakan sedikit tentang dirimu...">{{ $profil->tentang_saya }}</textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary-dark transition-colors">Simpan Profil</button>
                    </form>
                </div>
            </div>

            <!-- Kanan: Skill & Portofolio -->
            <div class="col-span-1 lg:col-span-2 space-y-8">
                
                <!-- Skill -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-lg mb-4 border-b pb-2">Keahlian (Skills)</h2>
                    
                    <form action="{{ route('mahasiswa.skill.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-6">
                        @csrf
                        <input type="text" name="nama_skill" placeholder="Contoh: Public Speaking" class="flex-1 rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3" required>
                        <select name="level" class="rounded-xl border-gray-200 text-sm focus:ring-primary focus:border-primary p-3" required>
                            <option value="dasar">Dasar</option>
                            <option value="menengah">Menengah</option>
                            <option value="mahir">Mahir</option>
                        </select>
                        <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition-colors">Tambah</button>
                    </form>

                    <div class="flex flex-wrap gap-3">
                        @forelse($user->skills as $skill)
                        <div class="inline-flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2">
                            <div class="mr-3">
                                <span class="block text-sm font-bold text-gray-800">{{ $skill->nama_skill }}</span>
                                <span class="block text-[10px] font-bold text-primary uppercase">{{ $skill->level }}</span>
                            </div>
                            <form action="{{ route('mahasiswa.skill.destroy', $skill->id) }}" method="POST" class="ml-2 border-l pl-3 border-gray-200">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 italic">Belum ada skill yang ditambahkan. Yuk, tambahkan skill pertamamu!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Portofolio -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-lg mb-4 border-b pb-2">Portofolio / Hasil Karya</h2>
                    
                    <form action="{{ route('mahasiswa.portofolio.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-5 rounded-2xl mb-6 border border-gray-100 space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="judul" placeholder="Judul Portofolio (Wajib)" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3" required>
                        </div>
                        <div>
                            <textarea name="deskripsi" placeholder="Deskripsi Singkat Karya Anda" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary text-sm p-3"></textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Link URL (Opsional)</label>
                                <input type="url" name="link" placeholder="https://..." class="w-full rounded-xl border-gray-200 text-sm p-3 focus:ring-primary">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Upload File (Opsional)</label>
                                <input type="file" name="files[]" multiple class="w-full text-sm bg-white border border-gray-200 rounded-xl p-2.5">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-gray-800 transition-colors">Simpan Portofolio Baru</button>
                    </form>

                    <div class="space-y-4">
                        @forelse($user->portofolio as $porto)
                        <div class="flex justify-between items-start p-5 border border-gray-100 rounded-2xl hover:shadow-md hover:border-gray-200 transition-all bg-white">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $porto->judul }}</h3>
                                <p class="text-sm text-gray-600 mt-1 mb-3">{{ $porto->deskripsi }}</p>
                                <div class="flex flex-wrap gap-3 text-sm">
                                    @if($porto->link)
                                    <a href="{{ $porto->link }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg hover:bg-emerald-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Kunjungi Link
                                    </a>
                                    @endif
                                    @if($porto->file)
                                    <a href="{{ asset('storage/' . $porto->file) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Lihat Dokumen
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('mahasiswa.portofolio.destroy', $porto->id) }}" method="POST" class="ml-4 flex-shrink-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 bg-red-50 hover:bg-red-500 hover:text-white p-2.5 rounded-xl transition-all" onclick="return confirm('Hapus portofolio ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center p-8 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                            <p class="text-sm text-gray-500 italic">Belum ada portofolio yang diupload.</p>
                            <p class="text-xs text-gray-400 mt-1">Tunjukkan karya terbaikmu di sini agar bisa dilihat oleh orang yang scan QR KTM kamu!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
