@extends('layouts.backend')

@section('title', 'Detail Pendaftar: ' . $registration->registration_code)
@section('breadcrumb', 'PMB > Daftar Pendaftar > Detail')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-primary mb-3 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar
        </a>
             <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $registration->registration_code }}</h1>
            <div class="flex flex-wrap gap-2">
                @if($registration->registration_type == 'pai')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-indigo-100 text-indigo-700 border border-indigo-200">Jalur PAI (Lengkap)</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-amber-100 text-amber-700 border border-amber-200">Jalur I'DAD (Ringan)</span>
                @endif

                @if($registration->status == 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                @elseif($registration->status == 'verified')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 border border-blue-200">Terverifikasi</span>
                @elseif($registration->status == 'accepted')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">Diterima</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                @endif
            </div>
        </div>
        <p class="text-gray-500 font-medium mt-1">Didaftarkan pada: {{ $registration->created_at->format('d F Y H:i') }}</p>
    </div>
    
    <div class="flex space-x-3">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->whatsapp_number) }}" target="_blank" class="px-6 py-3 bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white rounded-xl font-bold flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            <span>Hubungi WhatsApp</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Data Review -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Section 1 & 2 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-4">
                <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3 text-sm">1</span>
                Data Pribadi & Pendidikan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Nama Lengkap</label>
                    <p class="font-bold text-gray-900 text-lg">{{ $registration->full_name }}</p>
                </div>
                <div></div>
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Tempat, Tgl Lahir</label>
                    <p class="font-medium text-gray-900">{{ $registration->birth_place }}, {{ \Carbon\Carbon::parse($registration->birth_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Jenis Kelamin</label>
                    <p class="font-medium text-gray-900">{{ $registration->gender }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Status Aktivitas</label>
                    <p class="font-medium text-gray-900">{{ $registration->activity_status }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Email</label>
                    <p class="font-medium text-gray-900">{{ $registration->email }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Alamat Domisili</label>
                    <p class="font-medium text-gray-900">{{ $registration->address }}</p>
                </div>
                <hr class="md:col-span-2 border-gray-100">
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Program Studi</label>
                    <p class="font-black text-primary text-lg">{{ $registration->study_program }}</p>
                </div>
                <div>
                    @if($registration->registration_type == 'pai')
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Pendidikan Terakhir</label>
                        <p class="font-medium text-gray-900">{{ $registration->last_education }} ({{ $registration->graduation_year }}) - {{ $registration->school_name }}</p>
                    @else
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Tipe Jalur</label>
                        <p class="text-amber-600 font-bold italic">I'DAD (Biodata Ringan)</p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Sumber Referensi / Info</label>
                    <p class="font-medium text-gray-900">{{ $registration->reference ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center border-b border-gray-100 pb-4">
                <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3 text-sm">2</span>
                Pandangan & Kesiapan Belajar
            </h2>

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Minat Utama</label>
                        <p class="font-bold text-gray-900">{{ $registration->main_interest }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Pengalaman IT</label>
                        <p class="font-bold text-gray-900">{{ $registration->tech_experience }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-1">Skill Ingin Belajar</label>
                        <p class="font-bold text-gray-900">{{ $registration->skill_to_learn }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-800">Komitmen Belajar Terverifikasi</p>
                        <p class="text-xs text-emerald-600 font-medium">Pendaftar menyatakan siap mengikuti program dengan sungguh-sungguh.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-2">Motivasi Kuliah</label>
                        <p class="text-sm font-medium text-gray-800 bg-gray-50 p-4 rounded-xl leading-relaxed border border-gray-100">{{ $registration->motivation }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-2">Pandangan Masa Depan</label>
                        <p class="text-sm font-medium text-gray-800 bg-gray-50 p-4 rounded-xl leading-relaxed border border-gray-100">{{ $registration->future_career }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-2">Pentingnya Gelar vs Skill</label>
                    <p class="text-sm font-medium text-gray-800 bg-gray-50 p-4 rounded-xl leading-relaxed border border-gray-100">{{ $registration->degree_importance }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Verification Action & Payment Proof -->
    <div class="space-y-8">
        
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6" x-data="{ zoom: false }">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center border-b border-gray-100 pb-3">
                <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-2 text-xs">3</span>
                Bukti Pembayaran
            </h2>
            
            <div class="relative rounded-2xl overflow-hidden bg-gray-100 cursor-pointer group" @click="zoom = true">
                <img src="{{ Storage::url($registration->payment_proof) }}" alt="Bukti Pembayaran" class="w-full object-cover aspect-[3/4] group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                    <span class="text-white opacity-0 group-hover:opacity-100 bg-black/50 px-4 py-2 rounded-full text-sm font-bold backdrop-blur-sm transition-opacity">Ketuk untuk Zoom</span>
                </div>
            </div>

            <!-- Modal Zoom -->
            <div x-show="zoom" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4" @click.self="zoom = false">
                <button @click="zoom = false" class="absolute top-6 right-6 text-white hover:text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img src="{{ Storage::url($registration->payment_proof) }}" class="max-w-full max-h-[90vh] object-contain rounded-lg">
            </div>
        </div>

        <div class="bg-gray-900 rounded-[2rem] shadow-xl p-6 text-white">
            <h2 class="text-lg font-bold mb-4 flex items-center border-b border-white/10 pb-3">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Aksi Verifikasi
            </h2>

            <form action="{{ route('backend.admin.pmb.registrations.updateStatus', $registration) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-2">Update Status Pendaftar</label>
                    <select name="status" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-primary text-white font-bold text-sm">
                        <option value="pending" class="text-gray-900" {{ $registration->status == 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                        <option value="verified" class="text-gray-900" {{ $registration->status == 'verified' ? 'selected' : '' }}>🔵 Terverifikasi (Lulus Admin)</option>
                        <option value="accepted" class="text-gray-900" {{ $registration->status == 'accepted' ? 'selected' : '' }}>🟢 Diterima (Lulus Final)</option>
                        <option value="rejected" class="text-gray-900" {{ $registration->status == 'rejected' ? 'selected' : '' }}>🔴 Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] uppercase font-bold tracking-widest text-gray-400 block mb-2">Catatan Internal (Opsional)</label>
                    <textarea name="admin_notes" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:border-primary text-white text-sm" placeholder="Catatan untuk arsip atau alasan penolakan...">{{ $registration->admin_notes }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all uppercase tracking-widest text-[10px]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
