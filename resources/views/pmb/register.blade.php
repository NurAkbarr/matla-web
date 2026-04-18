@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Mahasiswa Baru')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto" x-data="pmbWizard()">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pendaftaran Mahasiswa Baru</h1>
            <p class="mt-2 text-gray-500">Mohon isi data dengan lengkap dan benar sesuai dokumen resmi.</p>
        </div>

        @if($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-2xl animate-in fade-in slide-in-from-top-4">
            <div class="flex items-center space-x-3 text-red-600 font-bold mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Mohon perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-500 space-y-1 ml-8">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Progress Bar -->
        <div class="mb-12 relative">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-200">
                <div :style="`width: ${((step - 1) / 3) * 100}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary transition-all duration-500"></div>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <span :class="step >= 1 ? 'text-primary' : ''">Data Pribadi</span>
                <span :class="step >= 2 ? 'text-primary' : ''" class="text-center">Pendidikan</span>
                <span :class="step >= 3 ? 'text-primary' : ''" class="text-center">Syar'i & Tech</span>
                <span :class="step >= 4 ? 'text-primary' : ''" class="text-right">Administrasi</span>
            </div>
        </div>

        <form action="{{ route('pmb.register.store') }}" method="POST" enctype="multipart/form-data" id="pmbForm" class="bg-white rounded-[2.5rem] shadow-xl p-8 md:p-12 border border-gray-100 relative overflow-hidden">
            @csrf

            <!-- SECTION 1: DATA PRIBADI -->
            <div x-show="step === 1" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b border-gray-100 pb-4">Data Pribadi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Depan *</label>
                        <input type="text" name="first_name" x-model="formData.first_name" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('first_name') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Belakang</label>
                        <input type="text" name="last_name" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('last_name') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Induk Kependudukan (NIK) *</label>
                        <input type="number" name="nik" x-model="formData.nik" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('nik') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select name="gender" x-model="formData.gender" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir *</label>
                        <input type="text" name="birth_place" x-model="formData.birth_place" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('birth_place') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" name="birth_date" x-model="formData.birth_date" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('birth_date') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp *</label>
                        <input type="text" name="whatsapp_number" x-model="formData.whatsapp_number" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" placeholder="08123xxxx" value="{{ old('whatsapp_number') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email *</label>
                        <input type="email" name="email" x-model="formData.email" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap Domisili *</label>
                    <textarea name="address" x-model="formData.address" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Aktivitas Saat Ini *</label>
                        <input type="text" name="activity_status" x-model="formData.activity_status" placeholder="Cth: Pegawai Swasta / Freelancer" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('activity_status') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sumber Informasi MATLA</label>
                        <input type="text" name="reference" placeholder="Cth: Instagram, Rekan" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('reference') }}">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA PENDIDIKAN -->
            <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b border-gray-100 pb-4">Data Pendidikan</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Program Studi Tujuan *</label>
                    <select name="study_program" x-model="formData.study_program" class="w-full px-5 py-4 bg-primary/5 text-primary border border-primary/20 font-bold rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                        <option value="">-- Pilih Program Studi --</option>
                        <option value="Pendidikan Agama Islam (PAI)" {{ old('study_program') == 'Pendidikan Agama Islam (PAI)' ? 'selected' : '' }}>S1 Pendidikan Agama Islam (PAI)</option>
                        <option value="I'dad Lughowi (Bahasa Arab Pemula)" {{ old('study_program') == "I'dad Lughowi (Bahasa Arab Pemula)" ? 'selected' : '' }}>I'dad Lughowi (Program Persiapan Bahasa Arab)</option>
                        <option value="I'dad + S1" {{ old('study_program') == "I'dad + S1" ? 'selected' : '' }}>Paket: I'dad Lughowi + S1 PAI</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pendidikan Terakhir *</label>
                        <select name="last_education" x-model="formData.last_education" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="SMA/SMK Sederajat" {{ old('last_education') == 'SMA/SMK Sederajat' ? 'selected' : '' }}>SMA/SMK/MA/Sederajat</option>
                            <option value="D3" {{ old('last_education') == 'D3' ? 'selected' : '' }}>Diploma (D3)</option>
                            <option value="S1/D4" {{ old('last_education') == 'S1/D4' ? 'selected' : '' }}>S1/D4</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Lulus *</label>
                        <input type="number" name="graduation_year" x-model="formData.graduation_year" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" placeholder="Cth: 2023" value="{{ old('graduation_year') }}">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Sekolah / Institusi Terakhir *</label>
                    <input type="text" name="school_name" x-model="formData.school_name" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" value="{{ old('school_name') }}">
                </div>
            </div>

            <!-- SECTION 3: ILMU SYAR'I & TECHNOLOGY -->
            <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 border-b border-gray-100 pb-4">Pandangan Ilmu Syar'i & Teknologi</h2>
                <p class="text-xs text-gray-400 mb-8 font-bold italic">Kami ingin mengenal Anda lebih jauh dalam menghadapi digitalisasi dakwah dan ilmu.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Interest & Skill -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bidang Minat Utama *</label>
                            <select name="main_interest" x-model="formData.main_interest" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                <option value="">-- Pilih Minat --</option>
                                <option value="Bahasa Arab & Ilmu Syar'i" {{ old('main_interest') == 'Bahasa Arab & Ilmu Syar\'i' ? 'selected' : '' }}>Bahasa Arab & Ilmu Syar'i</option>
                                <option value="Teknologi & Digital" {{ old('main_interest') == 'Teknologi & Digital' ? 'selected' : '' }}>Teknologi & Digital</option>
                                <option value="Pendidikan / Dakwah" {{ old('main_interest') == 'Pendidikan / Dakwah' ? 'selected' : '' }}>Pendidikan / Dakwah</option>
                                <option value="Manajemen & Bisnis" {{ old('main_interest') == 'Manajemen & Bisnis' ? 'selected' : '' }}>Manajemen & Bisnis</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Target Keahlian Baru yang Ingin Dipelajari *</label>
                            <input type="text" name="target_skill" x-model="formData.target_skill" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" placeholder="Cth: Mengajar PAI berbasis multimedia" value="{{ old('target_skill') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Rating Skill Teknologi yang Dikuasai Saat Ini (1-100) *</label>
                            <div class="flex items-center space-x-4">
                                <input type="range" name="skill_level" x-model="formData.skill_level" min="1" max="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary">
                                <span class="text-xl font-black text-primary w-12 text-center" x-text="formData.skill_level"></span>
                            </div>
                        </div>

                        <div x-show="formData.skill_level == 100" x-collapse>
                            <label class="block text-sm font-bold text-gray-700 mb-2 text-primary">Wah, Skill Anda 100%! Keahlian apa itu?</label>
                            <textarea name="skill_100_desc" rows="2" class="w-full px-5 py-4 bg-primary/5 border border-primary/20 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('skill_100_desc') }}</textarea>
                        </div>
                    </div>

                    <!-- Essays -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Urgensi Ilmu Syar'i & Teknologi di Era Kini *</label>
                            <textarea name="urgency_opinion" x-model="formData.urgency" rows="2" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all" placeholder="Pendapat Anda tentang menggabungkan kedua ilmu ini...">{{ old('urgency_opinion') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Apakah Anda berniat fokus HANYA pada Syar'i dan mengabaikan Teknologi? Mengapa? *</label>
                            <textarea name="focus_opinion" x-model="formData.focus" rows="2" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('focus_opinion') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Komparasi: Orang yang paham Syar'i sekaligus ahli Teknologi VS Orang yang hanya paham Syar'i zaman ini. Berikan tanggapan. *</label>
                            <textarea name="comparison_opinion" x-model="formData.comparison" rows="2" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('comparison_opinion') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Motivasi Utama Kuliah di Matla Islamic University *</label>
                    <textarea name="motivation" x-model="formData.motivation" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">{{ old('motivation') }}</textarea>
                </div>
            </div>

            <!-- SECTION 4: ADMINISTRASI -->
            <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b border-gray-100 pb-4">Administrasi & Pembayaran</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 mb-6">
                            <h3 class="text-blue-900 font-bold mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Informasi Rekening Pendaftaran
                            </h3>
                            <p class="text-sm text-blue-800 mb-4 leading-relaxed tracking-wide">
                                Biaya Administrasi/Pendaftaran: <strong class="text-lg">Rp 250.000,-</strong>
                                <br><span class="text-xs italic">*Biaya sudah termasuk uang pangkal.</span>
                            </p>
                            
                            <div class="space-y-3">
                                <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm">
                                    <div class="text-xs text-gray-500 font-bold mb-1">Bank Syariah Indonesia (BSI)</div>
                                    <div class="font-black text-lg text-gray-900 tracking-wider">4195187780</div>
                                    <div class="text-sm text-gray-600">a.n. Heru Fantono</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm">
                                    <div class="text-xs text-gray-500 font-bold mb-1">BCA Syariah</div>
                                    <div class="font-black text-lg text-gray-900 tracking-wider">0280041278</div>
                                    <div class="text-sm text-gray-600">a.n. Heru Fantono</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-4">Upload Bukti Pembayaran *</label>
                        <div class="relative group">
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="hidden" x-ref="fileInput" @change="previewImage">
                            <div @click="$refs.fileInput.click()" class="aspect-[4/3] bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center p-6 cursor-pointer hover:bg-gray-100 hover:border-primary transition-all relative overflow-hidden group">
                                
                                <template x-if="!imagePreview">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm group-hover:scale-110 transition-transform">
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-700">Klik untuk unggah Bukti Transfer</p>
                                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG (Max 5MB)</p>
                                    </div>
                                </template>

                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                
                                <!-- Overlay change when image exists -->
                                <template x-if="imagePreview">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <p class="text-white font-bold text-sm bg-black/50 px-4 py-2 rounded-full">Ganti File</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-6 flex items-start space-x-3 text-sm text-gray-600 bg-amber-50 p-4 rounded-xl border border-amber-100">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="leading-relaxed">Pastikan menulis keterangan pada catatan transfer bank Anda (Contoh: "Pendaftaran An. Abdullah PAI") agar mempermudah verifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Navigation -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <button type="button" x-show="step > 1" @click="prev()" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </button>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" x-show="step < 4" @click="next()" class="px-10 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center">
                        Selanjutnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    
                    <button type="button" x-show="step === 4" @click="submitForm()" class="px-10 py-3 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-black/20 transition-all flex items-center" :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">Selesaikan Pendaftaran</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function pmbWizard() {
        return {
            step: 1,
            isSubmitting: false,
            imagePreview: null,
            formData: {
                first_name: '{{ old("first_name") }}',
                nik: '{{ old("nik") }}',
                birth_place: '{{ old("birth_place") }}',
                birth_date: '{{ old("birth_date") }}',
                gender: '{{ old("gender") }}',
                whatsapp_number: '{{ old("whatsapp_number") }}',
                email: '{{ old("email") }}',
                address: '{{ old("address") }}',
                activity_status: '{{ old("activity_status") }}',
                
                study_program: '{{ old("study_program") }}',
                last_education: '{{ old("last_education") }}',
                graduation_year: '{{ old("graduation_year") }}',
                school_name: '{{ old("school_name") }}',
                
                main_interest: '{{ old("main_interest") }}',
                target_skill: '{{ old("target_skill") }}',
                skill_level: '{{ old("skill_level", 50) }}',
                urgency: '{{ old("urgency_opinion") }}',
                focus: '{{ old("focus_opinion") }}',
                comparison: '{{ old("comparison_opinion") }}',
                motivation: '{{ old("motivation") }}',
            },

            validateStep(currentStep) {
                // Sangat basic client-side validation logic untuk flow.
                // Bisa dibuat sangat ketat, atau simpel dan dibiarkan divalidasi ke backend.
                // Untuk kemudahan UX kita percayakan ke backend validation jika submit,
                // Namun kita check minimal required field supaya tidak kosong.
                let requiredKeys = [];
                if(currentStep === 1) {
                    requiredKeys = ['first_name', 'nik', 'birth_place', 'birth_date', 'gender', 'whatsapp_number', 'email', 'address', 'activity_status'];
                } else if(currentStep === 2) {
                    requiredKeys = ['study_program', 'last_education', 'graduation_year', 'school_name'];
                } else if(currentStep === 3) {
                    requiredKeys = ['main_interest', 'target_skill', 'urgency', 'focus', 'comparison', 'motivation'];
                }
                
                for(let key of requiredKeys) {
                    if(!this.formData[key] || this.formData[key].trim() === '') {
                        alert('Mohon isi semua field yang diwajibkan (*) di halaman ini.');
                        return false;
                    }
                }
                return true;
            },

            next() {
                if(this.validateStep(this.step)) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            prev() {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                } else {
                    this.imagePreview = null;
                }
            },

            submitForm() {
                if(!this.$refs.fileInput.files.length) {
                    alert('Mohon unggah file bukti pembayaran terlebih dahulu.');
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
</style>
@endsection
