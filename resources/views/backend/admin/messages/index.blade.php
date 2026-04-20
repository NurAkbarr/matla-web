@extends('layouts.backend')

@section('title', 'Kotak Pesan')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 relative">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">Kotak Pesan</h1>
        @if($messages->isEmpty())
            <p class="text-gray-600">Tidak ada pesan yang masuk.</p>
        @else
            <table class="w-full text-left text-sm border border-gray-200">
                <thead class="bg-gray-100 uppercase tracking-wider text-[10px] font-bold border-b border-gray-300">
                    <tr>
                        <th class="py-2 px-3 border-r border-gray-300 w-12 text-center">#</th>
                        <th class="py-2 px-3 border-r border-gray-300">Nama</th>
                        <th class="py-2 px-3 border-r border-gray-300">Email</th>
                        <th class="py-2 px-3 border-r border-gray-300">Subjek</th>
                        <th class="py-2 px-3 border-r border-gray-300">Pesan</th>
                        <th class="py-2 px-3 text-center w-24">Status</th>
                        <th class="py-2 px-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $i => $msg)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-2 px-3 border-r border-gray-200 text-center">{{ $i + 1 }}</td>
                            <td class="py-2 px-3 border-r border-gray-200 font-medium">{{ $msg->name }}</td>
                            <td class="py-2 px-3 border-r border-gray-200">{{ $msg->email }}</td>
                            <td class="py-2 px-3 border-r border-gray-200">{{ $msg->subject }}</td>
                            <td class="py-2 px-3 border-r border-gray-200 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap">{{ $msg->message }}</td>
                            <td class="py-2 px-3 text-center">
                                @if($msg->is_read)
                                    <span class="inline-block px-2 py-1 text-xs bg-emerald-100 text-emerald-800 rounded">Dibaca</span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">Baru</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center space-x-2">
                                @if(!$msg->is_read)
                                    <form action="{{ route('backend.admin.messages.read', $msg) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-primary hover:underline">Tandai Baca</button>
                                    </form>
                                @endif
                                <form action="{{ route('backend.admin.messages.destroy', $msg) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
