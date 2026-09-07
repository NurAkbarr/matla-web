@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Mahasiswa Baru')

@section('content')
<div class="min-h-screen bg-gray-50 pt-32 pb-20" x-data="pmbForm()" style="font-family: 'Outfit', sans-serif;">
    <!-- Custom Alert Modal -->
    <div x-show="alert.show" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-md" @click="alert.show = false" x-show="alert.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl max-w-sm w-full p-10 text-center border border-gray-100" x-show="alert.show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 ring-8 ring-red-50/50">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2 tracking-tight">Periksa Kembali</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium" x-text="alert.message"></p>
            <button @click="alert.show = false" class="w-full py-4 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 active:scale-95">
                Tutup
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-5xl">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-1.5 bg-primary/10 text-primary text-[10px] uppercase font-black tracking-widest rounded-full mb-6">Pendaftaran Online</div>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">Formulir <span class="text-primary">Pendaftaran</span></h1>
            <p class="text-gray-500 font-medium text-lg max-w-xl mx-auto leading-relaxed">
                Bergabunglah bersama keluarga besar <span class="text-primary font-bold">Matla Islamic Academy</span>.
            </p>
        </div>

        @if($errors->any())
        <div class="mb-10 p-6 bg-red-50 border border-red-100 rounded-2xl shadow-sm">
            <ul class="text-sm text-red-600 font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pmb.register.store') }}" method="POST" enctype="multipart/form-data" id="pmbForm" class="space-y-16 md:space-y-24">
            @csrf
            <!-- Hidden Registration Type Capturing URL param -->
            <input type="hidden" name="registration_type" x-model="formData.registration_type">

            <!-- SECTION 1: DATA PRIBADI -->
            <div class="bg-white rounded-3xl md:rounded-[2.5rem] shadow-xl shadow-gray-200/40 p-8 md:p-12 border border-white">
                <div class="flex items-center space-x-5 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-primary flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Data Pribadi</h2>
                        <p class="text-gray-400 text-xs font-bold mt-1">Identitas diri & Riwayat pendidikan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Lengkap *</label>
                        <input type="text" name="full_name" x-model="formData.full_name" placeholder="Masukkan nama lengkap" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Referensi</label>
                        <input type="text" name="reference" x-model="formData.reference" placeholder="Sumber informasi mengenai MATLA" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Tempat Lahir *</label>
                        <input type="text" name="birth_place" x-model="formData.birth_place" placeholder="Kota kelahiran" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Tanggal Lahir *</label>
                        <input type="date" name="birth_date" x-model="formData.birth_date" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Jenis Kelamin *</label>
                        <select name="gender" x-model="formData.gender" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900 appearance-none">
                            <option value="">Pilih Gender</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nomor WhatsApp *</label>
                        <input type="tel" name="whatsapp_number" x-model="formData.whatsapp_number" placeholder="Kontak seluler aktif" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Alamat Email *</label>
                        <input type="email" name="email" x-model="formData.email" placeholder="Alamat email resmi" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                </div>

                <div class="space-y-2 mb-8">
                    <label class="text-sm font-bold text-gray-700 ml-1">Alamat Domisili Saat Ini *</label>
                    <textarea name="address" x-model="formData.address" rows="2" placeholder="Alamat lengkap tempat tinggal sekarang" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Status Aktivitas *</label>
                        <input type="text" name="activity_status" x-model="formData.activity_status" placeholder="Informasi apakah sedang bekerja/lainnya" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Program Studi *</label>
                        <div class="w-full px-6 py-4 bg-primary/5 border border-primary/20 rounded-2xl flex items-center justify-between group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <span class="font-black text-gray-900 tracking-tight">{{ $selectedProgram->nama ?? 'Program Studi' }}</span>
                            </div>
                            <span class="text-[10px] font-black uppercase text-primary tracking-widest bg-white px-3 py-1 rounded-full shadow-sm">Terpilih</span>
                        </div>
                        <input type="hidden" name="study_program" x-model="formData.study_program">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Sekolah *</label>
                        <input type="text" name="school_name" x-model="formData.school_name" placeholder="Nama instansi pendidikan" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Tahun Lulus *</label>
                        <input type="text" name="graduation_year" x-model="formData.graduation_year" maxlength="4" placeholder="Tahun kelulusan" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1 italic">Kode Referral (Opsional)</label>
                        <input type="text" name="affiliate_code" x-model="formData.affiliate_code" placeholder="E.g. MTL..." class="w-full px-6 py-4 bg-emerald-50 border border-emerald-100 rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-black text-emerald-700 uppercase placeholder:text-emerald-200">
                    </div>
                </div>

                <!-- SECTION 2: ILMU SYAR'I & TECHNOLOGY -->
                <div class="flex items-center space-x-5 mt-16 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Kesiapan Belajar</h2>
                        <p class="text-gray-400 text-xs font-bold mt-1">Visi, minat & Pandangan kritis</p>
                    </div>
                </div>

                <div class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-sm font-bold text-gray-700 ml-1 italic">Bidang Minat Utama *</label>
                            <input type="hidden" name="main_interest" :value="formData.main_interest === 'Lainnya' ? formData.main_interest_lainnya : formData.main_interest">
                            <select x-model="formData.main_interest" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900 appearance-none">
                                <option value="" disabled selected hidden>Pilih Minat Utama</option>
                                <option value="Dakwah">Dakwah</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Teknologi & Digital">Teknologi & Digital</option>
                                <option value="Bisnis">Bisnis</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            
                            <div x-show="formData.main_interest === 'Lainnya'" x-transition class="pt-2">
                                <input type="text" x-model="formData.main_interest_lainnya" placeholder="Sebutkan minat utama Anda..." class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-sm font-bold text-gray-700 ml-1 italic">Pernah belajar teknologi? *</label>
                            <select name="tech_experience" x-model="formData.tech_experience" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900 appearance-none">
                                <option value="" disabled selected hidden>Pilih Tingkat Pengalaman</option>
                                <option value="Belum Pernah">Belum Pernah</option>
                                <option value="Dasar">Dasar</option>
                                <option value="Menengah">Menengah</option>
                                <option value="Mahir">Mahir</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-bold text-gray-700 ml-1 italic">Skill yang ingin dipelajari *</label>
                        <input type="text" name="skill_to_learn" x-model="formData.skill_to_learn" placeholder="Contoh: desain, video editing, coding, dakwah digital, tekhnisi, petani, dll" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900">
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-bold text-gray-700 ml-1 italic">Motivasi Kuliah *</label>
                        <textarea name="motivation" x-model="formData.motivation" rows="3" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900" placeholder="Alasan atau tujuan utama ingin menempuh pendidikan di perguruan tinggi"></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-10">
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-700 ml-1 italic block leading-relaxed">"Menurut Anda, seberapa penting ilmu syar'i dan keterampilan industri di zaman sekarang? Jelaskan singkat." *</label>
                            <textarea name="urgency_opinion" x-model="formData.urgency_opinion" rows="4" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900" placeholder="Berikan pandangan & kesiapan Anda..."></textarea>
                        </div>
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-700 ml-1 italic block leading-relaxed">"Setelah lulus kuliah, Anda ingin menjadi apa atau berkarier di bidang apa?" *</label>
                            <textarea name="future_career" x-model="formData.future_career" rows="4" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900" placeholder="Jelaskan arah masa depan Anda..."></textarea>
                        </div>
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-700 ml-1 italic block leading-relaxed">"Menurut Anda, apakah hanya mengandalkan ijazah saja cukup untuk menghadapi dunia kerja saat ini? Jelaskan singkat." *</label>
                            <textarea name="degree_importance" x-model="formData.degree_importance" rows="4" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-semibold text-gray-900" placeholder="Berikan pendapat Anda mengenai realita dunia kerja..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: ADMINISTRASI PEMBAYARAN -->
                <div class="flex items-center space-x-5 mt-16 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Administrasi Pembayaran</h2>
                        <p class="text-gray-400 text-xs font-bold mt-1">Konfirmasi & Bukti Pendaftaran</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100">
                            <p class="text-xs text-gray-400 font-black mb-2">Pilihan Rekening Pembayaran</p>
                            <div class="space-y-4">
                                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                                    <p class="text-[10px] text-gray-400 font-black mb-1 italic">BCA SYARIAH</p>
                                    <p class="text-xl font-black text-gray-900 tracking-wider">0280050345</p>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">a.n. Kampus Matla Islamic Academy</p>
                                </div>
                                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                                    <p class="text-[10px] text-gray-400 font-black mb-1 italic">BCA SYARIAH</p>
                                    <p class="text-xl font-black text-gray-900 tracking-wider">0280050337</p>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">a.n. Yayasan Nusaiba Alihdan Madani</p>
                                </div>
                            </div>
                            <div class="mt-6 p-4 bg-primary/5 rounded-xl border border-primary/20">
                                <p class="text-[11px] text-primary font-bold italic leading-relaxed">
                                    Pastikan Anda melakukan transfer dengan nominal yang sesuai ke salah satu rekening resmi kampus di atas. Setelah berhasil, silakan unggah foto atau screenshot bukti transfer yang valid pada kolom di sebelah kanan.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="space-y-6">
                        <label class="text-sm font-bold text-gray-700 ml-1">Upload Bukti Transfer *</label>
                        <div class="relative group">
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="hidden" x-ref="fileInput" @change="previewImage">
                            <div @click="$refs.fileInput.click()" 
                                class="aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center p-8 cursor-pointer hover:bg-gray-100/50 hover:border-primary transition-all relative overflow-hidden ring-4 ring-transparent hover:ring-primary/5">
                                
                                <template x-if="!imagePreview">
                                    <div class="text-center group">
                                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl group-hover:scale-110 transition-transform">
                                            <svg class="w-8 h-8 text-gray-300 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                        <p class="text-sm font-black text-gray-900 mb-1">Pilih File Gambar</p>
                                        <p class="text-[10px] text-gray-400 font-bold italic">Upload bukti biaya pendaftaran</p>
                                    </div>
                                </template>

                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                
                                <template x-if="imagePreview">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                        <div class="bg-white/10 px-6 py-3 rounded-xl border border-white/20 text-white font-bold text-xs uppercase tracking-widest">Ganti Bukti</div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 bg-primary/5 p-8 md:p-10 rounded-[2rem] border border-primary/10">
                    <div class="flex items-start space-x-4 md:space-x-6">
                        <div class="mt-1 flex-shrink-0">
                            <input type="checkbox" id="commitment" name="commitment_check" x-model="formData.commitment_check" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label for="commitment" class="cursor-pointer group block">
                                <h4 class="text-gray-900 font-black tracking-tight text-xl mb-2 group-hover:text-primary transition-colors">Komitmen Belajar</h4>
                                <p class="text-gray-600 text-sm font-bold leading-relaxed mb-3 italic">
                                    Program ini diperuntukkan bagi peserta yang memiliki komitmen belajar yang serius. Jika Anda belum siap, disarankan untuk tidak melanjutkan pendaftaran.
                                </p>
                                <span class="text-primary font-black text-sm group-hover:underline">
                                    Saya siap mengikuti program dengan sungguh-sungguh dan disiplin
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT ACTION -->
                <div class="mt-12 pt-10 border-t border-gray-100 flex flex-col items-center">
                    <button type="button" @click="submitNow()" 
                        class="w-full md:w-auto px-16 py-6 bg-primary hover:bg-primary-dark text-white rounded-[2rem] font-black tracking-widest text-lg shadow-2xl shadow-primary/30 transition-all active:scale-95 disabled:opacity-50">
                        <span x-show="!isSubmitting">Daftar Sekarang</span>
                        <span x-show="isSubmitting" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- HELP BANNER -->
        <div class="mt-12 bg-gradient-to-r from-emerald-800 to-primary rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-primary/20 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden border border-emerald-700/50 group">
            <!-- Glowing Accents -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-150 group-hover:bg-white/20"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-950/40 rounded-full blur-3xl pointer-events-none"></div>
            
            <!-- Abstract Waves Pattern -->
            <svg class="absolute inset-0 w-full h-full object-cover opacity-[0.05] pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,50 Q25,25 50,50 T100,50 L100,100 L0,100 Z" fill="currentColor"/>
                <path d="M0,70 Q25,45 50,70 T100,70 L100,100 L0,100 Z" fill="currentColor" opacity="0.5"/>
            </svg>

            <!-- Floating Dots -->
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white/40 rounded-full animate-ping pointer-events-none" style="animation-duration: 3s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-3 h-3 bg-white/30 rounded-full pointer-events-none"></div>
            <div class="absolute top-1/2 left-[40%] w-1.5 h-1.5 bg-white/20 rounded-full pointer-events-none"></div>
            <div class="absolute bottom-1/3 right-1/4 w-2 h-2 bg-white/20 rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 flex-1 text-center md:text-left pl-0 md:pl-4">
                <h3 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-3">Kami Siap Membantu Anda</h3>
                <p class="text-emerald-50 text-sm md:text-base font-medium leading-relaxed max-w-2xl">
                    Apabila ada kendala terkait Pendaftaran silahkan hubungi melalui WhatsApp.
                </p>
            </div>
            
            <div class="relative z-10">
                <a href="https://wa.me/6287764785588" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white text-white hover:text-primary backdrop-blur-sm border border-white/30 rounded-2xl font-black tracking-wide transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 group">
                    <svg class="w-6 h-6 mr-3 group-hover:text-green-500 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function pmbForm() {
        return {
            formData: {
                full_name: @json(old('full_name', '')),
                reference: @json(old('reference', '')),
                birth_place: @json(old('birth_place', '')),
                birth_date: @json(old('birth_date', '')),
                gender: @json(old('gender', '')),
                whatsapp_number: @json(old('whatsapp_number', '')),
                email: @json(old('email', '')),
                address: @json(old('address', '')),
                activity_status: @json(old('activity_status', '')),
                study_program: @json(old('study_program', $selectedProgram->nama ?? '')),
                registration_type: @json(old('registration_type', request('type', 'pai'))),
                school_name: @json(old('school_name', '')),
                graduation_year: @json(old('graduation_year', '')),
                affiliate_code: @json(old('affiliate_code', session('pmb_referrer', ''))),
                main_interest: @json(old('main_interest', '')),
                main_interest_lainnya: @json(old('main_interest_lainnya', '')),
                tech_experience: @json(old('tech_experience', '')),
                skill_to_learn: @json(old('skill_to_learn', '')),
                motivation: @json(old('motivation', '')),
                urgency_opinion: @json(old('urgency_opinion', '')),
                future_career: @json(old('future_career', '')),
                degree_importance: @json(old('degree_importance', '')),
                commitment_check: false
            },
            imagePreview: null,
            isSubmitting: false,
            alert: {
                show: false,
                message: ''
            },
            
            showAlert(msg) {
                this.alert.message = msg;
                this.alert.show = true;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            submitNow() {
                // CLIENT VALIDATION
                const required = [
                    { key: 'full_name', label: 'Nama Lengkap' },
                    { key: 'birth_place', label: 'Tempat Lahir' },
                    { key: 'birth_date', label: 'Tanggal Lahir' },
                    { key: 'gender', label: 'Jenis Kelamin' },
                    { key: 'whatsapp_number', label: 'Nomor WhatsApp' },
                    { key: 'email', label: 'Alamat Email' },
                    { key: 'address', label: 'Alamat Domisili' },
                    { key: 'activity_status', label: 'Status Aktivitas' },
                    { key: 'study_program', label: 'Program Studi' },
                    { key: 'school_name', label: 'Nama Sekolah' },
                    { key: 'graduation_year', label: 'Tahun Lulus' },
                    { key: 'main_interest', label: 'Bidang Minat Utama' },
                    { key: 'tech_experience', label: 'Pengalaman Teknologi' },
                    { key: 'skill_to_learn', label: 'Skill yang ingin dipelajari' },
                    { key: 'motivation', label: 'Motivasi Kuliah' },
                    { key: 'urgency_opinion', label: 'Pandangan Ilmu Syar\'i' },
                    { key: 'future_career', label: 'Arah Masa Depan' },
                    { key: 'degree_importance', label: 'Realita Dunia Kerja' }
                ];

                for (let field of required) {
                    // Cek dari state formData, jika kosong cek langsung dari elemen DOM (mengatasi issue autofill di HP)
                    let domElement = document.querySelector('[name="' + field.key + '"]');
                    let actualValue = this.formData[field.key] || (domElement ? domElement.value : '');
                    
                    if (field.key === 'main_interest' && this.formData.main_interest === 'Lainnya') {
                        actualValue = this.formData.main_interest_lainnya;
                    }
                    
                    if (!actualValue) {
                        this.showAlert(`Mohon lengkapi bagian: ${field.label}`);
                        return;
                    }
                    
                    // Update back to state just in case
                    if (field.key !== 'main_interest') {
                        this.formData[field.key] = actualValue;
                    }
                }

                if (!this.formData.commitment_check) {
                    this.showAlert('Anda harus menyetujui Komitmen Belajar.');
                    return;
                }

                if(!this.$refs.fileInput.files.length) {
                    this.showAlert('Mohon unggah bukti transfer pendaftaran Anda.');
                    return;
                }

                this.isSubmitting = true;
                document.getElementById('pmbForm').submit();
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
</style>
@endsection
