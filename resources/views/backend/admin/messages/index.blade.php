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
                            <td class="py-2 px-3 border-r border-gray-200 whitespace-pre-wrap min-w-[300px]">{{ $msg->message }}</td>
                            <td class="py-2 px-3 text-center">
                                @if($msg->is_replied)
                                    <span class="inline-block px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded font-bold">Dibalas</span>
                                @elseif($msg->is_read)
                                    <span class="inline-block px-2 py-1 text-xs bg-emerald-100 text-emerald-800 rounded">Dibaca</span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">Baru</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center space-x-2" x-data="{ showReply: false }">
                                <button type="button" @click="showReply = true" class="text-xs text-blue-600 font-bold hover:underline">Balas</button>
                                @if(!$msg->is_read)
                                    <form action="{{ route('backend.admin.messages.read', $msg) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-primary font-bold hover:underline">Baca</button>
                                    </form>
                                @endif
                                <form action="{{ route('backend.admin.messages.destroy', $msg) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 font-bold hover:underline">Hapus</button>
                                </form>

                                <!-- Modal Balas Pesan -->
                                <template x-teleport="body">
                                    <div x-show="showReply" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto text-left" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div x-show="showReply" 
                                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                                 @click="showReply = false" class="absolute inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>
                                            
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            
                                            <div x-show="showReply" 
                                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                 class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                                                
                                                <form action="{{ route('backend.admin.messages.reply', $msg) }}" method="POST">
                                                    @csrf
                                                    <div class="bg-white px-6 pt-6 pb-6 sm:p-8">
                                                        <div class="flex items-start justify-between mb-5">
                                                            <div>
                                                                <h3 class="text-xl font-black text-gray-900" id="modal-title">
                                                                    Kirim Balasan
                                                                </h3>
                                                                <p class="text-sm text-gray-500 font-medium mt-1">Ke: {{ $msg->name }} ({{ $msg->email }})</p>
                                                            </div>
                                                            <button type="button" @click="showReply = false" class="text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        <div class="mb-4 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Subjek</h4>
                                                            <p class="text-sm font-semibold text-gray-800">Balasan: {{ $msg->subject }}</p>
                                                        </div>

                                                        <div class="mt-4">
                                                            <label for="reply_message" class="block text-sm font-medium text-gray-700 mb-2">Pesan Balasan</label>
                                                            <textarea id="reply_message" name="reply_message" rows="6" class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-xl p-3" placeholder="Ketik balasan Anda di sini..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-primary text-base font-bold text-white hover:bg-primary-dark focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                            Kirim Email
                                                        </button>
                                                        <button type="button" @click="showReply = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
