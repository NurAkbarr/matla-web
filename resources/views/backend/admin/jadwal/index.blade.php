@extends('layouts.backend')

@section('title', 'Jadwal Perkuliahan')
@section('breadcrumb', 'Akademik / Jadwal')

@section('content')
<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Jadwal & Peserta Kuliah</h1>
            <p class="text-gray-500 font-medium">Atur jadwal kelas dan dosen pengampu secara dinamis</p>
        </div>
        <a href="{{ route('backend.admin.jadwal.create') }}" class="mt-4 md:mt-0 px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Jadwal</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-gray-50">
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-24">Hari</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu & Ruang</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mata Kuliah / Dosen</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Prodi & SMT</th>
                    <th class="pb-6 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 font-bold tracking-tight">
                @forelse($jadwals as $jadwal)
                <tr class="group hover:bg-gray-50 transition-all">
                    <td class="py-6 px-4 text-gray-900">{{ $jadwal->hari }}</td>
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="text-gray-900">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                            <span class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">{{ $jadwal->ruang }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="text-primary text-sm mb-0.5">{{ $jadwal->mata_kuliah }}</span>
                            <span class="text-[10px] text-gray-500 font-medium">Dosen: {{ $jadwal->dosen->name }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="text-gray-900 text-xs">{{ $jadwal->programStudi->nama }}</span>
                            <span class="text-[10px] text-gray-400 font-medium italic">Semester {{ $jadwal->semester }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-4">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('backend.admin.jadwal.edit', $jadwal) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('backend.admin.jadwal.destroy', $jadwal) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
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
                    <td colspan="5" class="py-12 text-center text-gray-400 italic font-medium">Belum ada data jadwal perkuliahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
