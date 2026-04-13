@extends('layouts.dosen')

@section('title', 'Riwayat Perubahan Nilai')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Perubahan Nilai</h1>
            <p class="text-gray-500 font-bold italic">{{ $jadwal->mata_kuliah }}</p>
        </div>
        <a href="{{ route('backend.dosen.nilai.input', $jadwal) }}" class="text-sm font-black text-gray-400 hover:text-primary transition-colors flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Input Nilai</span>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mahasiswa</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="block text-sm font-black text-gray-900">{{ $log->created_at->format('d M Y') }}</span>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase">{{ $log->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="block text-sm font-black text-gray-900">{{ $log->nilai->mahasiswa->name }}</span>
                            <span class="block text-[10px] font-bold text-gray-400">{{ $log->nilai->mahasiswa->email }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 {{ $log->action == 'created' ? 'bg-emerald-50 text-emerald-600' : 'bg-indigo-50 text-indigo-600' }} text-[10px] font-black uppercase tracking-widest rounded-full">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="text-[10px] font-bold text-gray-400 line-through">
                                    @if($log->old_values)
                                        T:{{ $log->old_values['tugas'] }} UTS:{{ $log->old_values['uts'] }} UAS:{{ $log->old_values['uas'] }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                <div class="text-xs font-black text-primary">
                                    T:{{ $log->new_values['tugas'] }} UTS:{{ $log->new_values['uts'] }} UAS:{{ $log->new_values['uas'] }}
                                    <span class="ml-2 px-2 py-0.5 bg-primary/10 rounded font-black">{{ $log->new_values['total_angka'] }} ({{ $log->new_values['nilai_huruf'] }})</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold italic">Belum ada riwayat perubahan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
