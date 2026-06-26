@extends('layouts.backend')

@section('title', 'Upload KHS - Pilih Program Studi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Pilih Program Studi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($programStudis as $prodi)
                <a href="{{ route('backend.admin.khs.prodi', $prodi->id) }}" class="p-5 rounded-xl border border-gray-100 hover:border-primary hover:shadow-md transition-all group bg-gray-50 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-gray-800 group-hover:text-primary transition-colors">{{ $prodi->nama }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $prodi->jenjang }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endforeach
        </div>
        @if($programStudis->isEmpty())
            <div class="text-center py-8 text-gray-500 text-sm">Belum ada data Program Studi.</div>
        @endif
    </div>
</div>
@endsection
