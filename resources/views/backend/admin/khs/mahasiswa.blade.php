@extends('layouts.backend')

@section('title', 'Upload KHS - Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('backend.admin.khs.angkatan', [$prodi->id, $angkatan]) }}" class="text-gray-400 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-gray-800">{{ $prodi->nama }} - Angkatan {{ $angkatan }} - Semester {{ $semester }}</h3>
            <p class="text-sm text-gray-500">Daftar Mahasiswa & Upload KHS</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">NIM</th>
                        <th class="px-6 py-4">Nama Mahasiswa</th>
                        <th class="px-6 py-4">Status KHS</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mahasiswas as $mhs)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-600">{{ $mhs->nim ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $mhs->name }}</td>
                            <td class="px-6 py-4">
                                @if($mhs->khs->isNotEmpty())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Sudah Upload
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-3">
                                    @if($mhs->khs->isNotEmpty())
                                        <a href="{{ route('backend.admin.khs.download', $mhs->khs->first()->id) }}" target="_blank" class="text-primary hover:text-primary-dark font-semibold text-xs">Lihat KHS</a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('backend.admin.khs.destroy', $mhs->khs->first()->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus KHS ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-xs">Hapus</button>
                                        </form>
                                    @else
                                        <form action="{{ route('backend.admin.khs.store', $mhs->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                            @csrf
                                            <input type="hidden" name="semester" value="{{ $semester }}">
                                            <input type="file" name="file" accept="application/pdf" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                            <button type="submit" class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition-colors whitespace-nowrap">
                                                Upload
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">Tidak ada data mahasiswa di semester ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
