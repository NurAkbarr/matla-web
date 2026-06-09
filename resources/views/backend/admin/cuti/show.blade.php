@extends('layouts.backend')
 
@section('title', 'Detail Pengajuan Cuti - Admin')
 
@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('backend.admin.cuti.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Pengajuan
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Pengajuan Cuti</h1>
                <p class="text-sm text-slate-500 mt-1">Diajukan pada {{ $cutiRequest->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div>
                @if($cutiRequest->status === 'approved')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Telah Disetujui
                    </span>
                @elseif($cutiRequest->status === 'rejected')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Telah Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Menunggu Persetujuan
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Mahasiswa</h3>
                
                <div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Nama Mahasiswa</p>
                    <p class="text-sm font-bold text-slate-800">{{ $cutiRequest->user->name ?? 'User Dihapus' }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">NIM</p>
                    <p class="text-sm font-bold text-slate-800">{{ $cutiRequest->user->nim ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Program Studi</p>
                    <p class="text-sm font-medium text-slate-700">{{ $cutiRequest->user->education['program_studi'] ?? '-' }}</p>
                </div>
                <div class="flex gap-4">
                    <div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Angkatan</p>
                        <p class="text-sm font-medium text-slate-700">{{ $cutiRequest->user->angkatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Semester Saat Ini</p>
                        <p class="text-sm font-medium text-slate-700">{{ $cutiRequest->user->semester ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">Detail Pengajuan</h3>
                
                <div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Semester Yang Diajukan Cuti</p>
                    <p class="text-lg font-black text-emerald-700">Semester {{ $cutiRequest->semester_diajukan }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1">Alasan Cuti</p>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $cutiRequest->alasan }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($cutiRequest->status === 'pending')
            <div class="border-t border-slate-100 pt-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4">Aksi Persetujuan</h3>
                
                <form action="{{ route('backend.admin.cuti.update', $cutiRequest->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="admin_notes" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Catatan Admin (Opsional, wajib jika ditolak)</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="w-full bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl px-4 py-3 text-sm transition-all resize-y" placeholder="Tambahkan pesan atau alasan penolakan..."></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" name="status" value="approved" class="flex-1 flex items-center justify-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-emerald-600/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Acc / Setujui Cuti
                        </button>
                        <button type="submit" name="status" value="rejected" class="flex-1 flex items-center justify-center px-6 py-3.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-bold rounded-xl text-sm transition-colors" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        @else
            @if($cutiRequest->admin_notes)
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-2">Catatan Admin</p>
                    <div class="p-4 {{ $cutiRequest->status === 'approved' ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-800' }} rounded-xl">
                        <p class="text-sm font-medium">{{ $cutiRequest->admin_notes }}</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
