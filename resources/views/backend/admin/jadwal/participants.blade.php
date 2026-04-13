@extends('layouts.backend')

@section('title', 'Peserta Kuliah')
@section('breadcrumb', 'Akademik / Jadwal / Peserta')

@section('content')
<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Peserta Mata Kuliah</h1>
            <p class="text-gray-500 font-medium">{{ $jadwal->mata_kuliah }} - {{ $jadwal->dosen->name }}</p>
        </div>
        <a href="{{ route('backend.admin.jadwal.index') }}" class="mt-4 md:mt-0 px-6 py-3 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Add Participant Form -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Tambah Peserta</h3>
                <form action="{{ route('backend.admin.jadwal.participants.add', $jadwal) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-3">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-700 appearance-none" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($availableMahasiswas as $mhs)
                                <option value="{{ $mhs->id }}">{{ $mhs->name }} (Semester {{ $mhs->semester ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                        Tambahkan ke Kelas
                    </button>
                </form>
            </div>
        </div>

        <!-- Participant List -->
        <div class="lg:col-span-2">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-50">
                            <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Mahasiswa</th>
                            <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Semester</th>
                            <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-bold tracking-tight">
                        @forelse($participants as $participant)
                        <tr class="group hover:bg-gray-50 transition-all">
                            <td class="py-6 px-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-900">{{ $participant->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $participant->email }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-4 text-center text-gray-900">{{ $participant->semester ?? '-' }}</td>
                            <td class="py-6 px-4">
                                <div class="flex items-center justify-end">
                                    <form action="{{ route('backend.admin.jadwal.participants.remove', [$jadwal, $participant]) }}" method="POST" onsubmit="return confirm('Keluarkan mahasiswa dari jadwal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-gray-400 italic font-medium">Belum ada peserta di mata kuliah ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
