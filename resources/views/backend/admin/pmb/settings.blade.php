@extends('layouts.backend')

@section('title', 'Pengaturan PMB')
@section('breadcrumb', 'Manajemen Konten > Pengaturan PMB')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan PMB</h1>
        <p class="text-gray-500 font-medium italic">Atur konfigurasi penerimaan mahasiswa baru di sini</p>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
        <form action="{{ route('backend.admin.pmb.settings.update') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Status Pendaftaran -->
                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status Pendaftaran</h3>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="pmb_is_open" value="1" {{ $settings['pmb_is_open'] == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 focus:ring-primary">
                            <span class="text-gray-700 font-medium">Buka Pendaftaran</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="pmb_is_open" value="0" {{ $settings['pmb_is_open'] == '0' ? 'checked' : '' }} class="w-5 h-5 text-red-500 border-gray-300 focus:ring-red-500">
                            <span class="text-gray-700 font-medium">Tutup Pendaftaran</span>
                        </label>
                    </div>
                </div>

                <!-- Detail Pendaftaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Gelombang & Tahun Akademik</label>
                        <input type="text" name="pmb_gelombang" value="{{ old('pmb_gelombang', $settings['pmb_gelombang']) }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium"
                            placeholder="Contoh: 2024/2025 - Gelombang 1">
                        @error('pmb_gelombang') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Batas Waktu Countdown (Saat Buka)</label>
                        <input type="datetime-local" name="pmb_end_date" value="{{ old('pmb_end_date', $settings['pmb_end_date']) }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium">
                        @error('pmb_end_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-bold text-emerald-600">Digunakan saat pendaftaran aktif.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block text-orange-600">Tanggal Pembukaan (Coming Soon)</label>
                        <input type="datetime-local" name="pmb_start_date" value="{{ old('pmb_start_date', $settings['pmb_start_date']) }}" required
                            class="w-full px-5 py-4 bg-orange-50 border border-orange-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:bg-white text-gray-700 font-medium font-bold">
                        @error('pmb_start_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-bold text-orange-600">Digunakan sebagai target hitung mundur saat pendaftaran ditutup.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Link Formulir Pendaftaran</label>
                        <input type="url" name="pmb_registration_link" value="{{ old('pmb_registration_link', $settings['pmb_registration_link']) }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium">
                        @error('pmb_registration_link') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 block">Link Cek Status</label>
                        <input type="url" name="pmb_status_link" value="{{ old('pmb_status_link', $settings['pmb_status_link']) }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white text-gray-700 font-medium">
                        @error('pmb_status_link') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100">
                <button type="submit" class="w-full md:w-auto px-10 py-4 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold uppercase tracking-widest text-[10px] shadow-xl shadow-primary/20 transition-all">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
