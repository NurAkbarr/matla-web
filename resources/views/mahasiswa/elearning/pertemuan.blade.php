@extends('layouts.app')

@section('title', 'Pertemuan ' . $pertemuan->pertemuan_ke . ' - ' . $jadwal->mata_kuliah)

@section('content')
<div class="bg-gray-50 min-h-screen pb-24 pt-8 lg:pt-12">
    <div class="container mx-auto px-4 lg:px-12 max-w-6xl">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pertemuan {{ $pertemuan->pertemuan_ke }}: {{ $pertemuan->judul_materi }}</h1>
        <p class="text-gray-500 mt-1 font-medium text-sm">{{ $jadwal->mata_kuliah }}</p>
    </div>
    <a href="{{ route('mahasiswa.elearning.jadwal', $jadwal->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-gray-500 text-xs font-bold uppercase tracking-widest rounded-xl hover:text-gray-900 border border-gray-100 transition-all shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content Area (Video / Zoom) -->
    <div class="lg:col-span-2 space-y-6">
        @if($pertemuan->tipe_pertemuan == 'video')
            <div class="bg-black rounded-3xl overflow-hidden shadow-xl aspect-video relative">
                @if($youtubeId)
                    <!-- YouTube Player Container -->
                    <div id="player" class="w-full h-full"></div>
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-white flex-col">
                        <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p class="font-bold tracking-widest uppercase">Video Tidak Valid</p>
                    </div>
                @endif
            </div>

            <!-- Progress Indicator -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div id="status-icon" class="w-10 h-10 rounded-full flex items-center justify-center {{ $log->is_lulus_nonton ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                        @if($log->is_lulus_nonton)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-end mb-1">
                        <h4 class="text-sm font-bold text-gray-900" id="status-text">
                            {{ $log->is_lulus_nonton ? 'Durasi Tonton Terpenuhi' : 'Menonton Video...' }}
                        </h4>
                        <span class="text-xs font-black text-primary" id="progress-text">0%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div id="progress-bar" class="h-2 rounded-full transition-all duration-1000 ease-linear {{ $log->is_lulus_nonton ? 'bg-emerald-500 w-full' : 'bg-amber-500 w-0' }}"></div>
                    </div>
                </div>
            </div>

        @else
            <!-- Zoom Interface -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
                <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center relative">
                    <svg class="w-24 h-24 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                </div>
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Live Session via Zoom</h3>
                    <p class="text-gray-500 font-medium mb-8">Sesi perkuliahan interaktif tatap muka secara virtual.</p>
                    @if($pertemuan->link_url)
                        <a href="{{ $pertemuan->link_url }}" target="_blank" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                            Bergabung ke Ruang Zoom
                        </a>
                    @else
                        <div class="inline-flex items-center px-8 py-4 bg-gray-100 text-gray-400 font-bold rounded-2xl cursor-not-allowed">
                            Link Zoom Belum Tersedia
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Instruksi -->
        @if($pertemuan->deskripsi)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Instruksi Perkuliahan</h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! nl2br(e($pertemuan->deskripsi)) !!}
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar / Evaluasi Form -->
    <div class="lg:col-span-1">
        @if($pertemuan->tipe_pertemuan == 'video' && $pertemuan->soal_evaluasi)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-gray-50">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Tugas Evaluasi</h3>
                        <p class="text-[10px] text-gray-400 font-bold">Wajib diisi sebagai absensi</p>
                    </div>
                </div>

                <div class="mb-6 text-sm font-medium text-gray-800 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                    {!! nl2br(e($pertemuan->soal_evaluasi)) !!}
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-xl border border-emerald-100 text-center">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-600 text-xs font-bold rounded-xl border border-red-100 text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('mahasiswa.elearning.evaluasi', $pertemuan->id) }}" method="POST" id="evaluasi-form">
                    @csrf
                    <div class="relative">
                        <textarea name="jawaban" rows="6" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none {{ !$log->is_lulus_nonton ? 'opacity-50 cursor-not-allowed bg-gray-50' : '' }}" placeholder="Ketikkan jawaban Anda di sini..." {{ !$log->is_lulus_nonton ? 'readonly' : 'required' }}>{{ old('jawaban', $evaluasi->jawaban ?? '') }}</textarea>
                        
                        <!-- Lock overlay if not watched enough -->
                        <div id="lock-overlay" class="absolute inset-0 flex items-center justify-center flex-col bg-white/60 backdrop-blur-[2px] rounded-xl transition-opacity duration-500 {{ $log->is_lulus_nonton ? 'opacity-0 pointer-events-none' : '' }}">
                            <div class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-amber-500 mb-2 border border-gray-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-800 bg-white px-3 py-1 rounded-full shadow-sm">Terkunci</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="mt-4 w-full py-3.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" {{ !$log->is_lulus_nonton ? 'disabled' : '' }}>
                        {{ $evaluasi ? 'Update Jawaban' : 'Kirim Jawaban' }}
                    </button>
                </form>
            </div>
        @endif
    </div>
        </div>
    </div>
</div>

@if($pertemuan->tipe_pertemuan == 'video' && $youtubeId)
@push('scripts')
<script>
    let player;
    let watchTimer;
    let totalDuration = 0;
    
    // PHP variables
    let watchedSeconds = {{ $log->detik_ditonton }};
    let isPassed = {{ $log->is_lulus_nonton ? 'true' : 'false' }};
    let updateUrl = "{{ route('mahasiswa.elearning.log', $pertemuan->id) }}";
    let csrfToken = "{{ csrf_token() }}";

    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('player', {
            videoId: '{{ $youtubeId }}',
            playerVars: {
                'playsinline': 1,
                'rel': 0,
                'modestbranding': 1,
                'origin': window.location.origin
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        totalDuration = player.getDuration();
        updateUIProgress();
        if(isPassed) unlockForm();
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            // Start checking every second
            watchTimer = setInterval(trackProgress, 1000);
        } else {
            // Stopped or paused
            clearInterval(watchTimer);
            saveProgressToServer(); // save immediately on pause
        }
    }

    let lastTime = -1;
    function trackProgress() {
        if(isPassed) return; // already unlocked, no strict need to track intensely, but we can keep updating bar

        let currentTime = player.getCurrentTime();
        // Simple trick to prevent seeking/skipping: only count if time diff is ~1 second
        if (lastTime !== -1 && (currentTime - lastTime) < 2 && (currentTime - lastTime) > 0) {
            watchedSeconds += (currentTime - lastTime);
        }
        lastTime = currentTime;

        updateUIProgress();

        let threshold = totalDuration * 0.5; // 50%
        if (watchedSeconds >= threshold && !isPassed) {
            isPassed = true;
            unlockForm();
            saveProgressToServer(); // save that they passed
        }

        // Auto save every 10 seconds
        if (Math.floor(watchedSeconds) % 10 === 0) {
            saveProgressToServer();
        }
    }

    function updateUIProgress() {
        if (!totalDuration) return;
        // Hitung berdasarkan total durasi, bukan setengah durasi
        let percentage = Math.min(100, (watchedSeconds / totalDuration) * 100);

        document.getElementById('progress-bar').style.width = percentage + '%';
        document.getElementById('progress-text').innerText = Math.round(percentage) + '% Ditonton';
    }

    function unlockForm() {
        document.getElementById('lock-overlay').classList.add('opacity-0', 'pointer-events-none');
        document.querySelector('textarea[name="jawaban"]').removeAttribute('readonly');
        document.querySelector('textarea[name="jawaban"]').classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-50');
        document.getElementById('submit-btn').removeAttribute('disabled');
        
        document.getElementById('status-text').innerText = 'Durasi Tonton Terpenuhi';
        document.getElementById('status-icon').classList.remove('bg-amber-100', 'text-amber-600');
        document.getElementById('status-icon').classList.add('bg-emerald-100', 'text-emerald-600');
        document.getElementById('status-icon').innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
        document.getElementById('progress-bar').classList.remove('bg-amber-500');
        document.getElementById('progress-bar').classList.add('bg-emerald-500');
    }

    function saveProgressToServer() {
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                detik_ditonton: Math.floor(watchedSeconds),
                is_lulus_nonton: isPassed
            })
        })
        .then(response => response.json())
        .then(data => console.log('Progress saved', data))
        .catch(error => console.error('Error saving progress:', error));
    }
</script>
<script src="https://www.youtube.com/iframe_api"></script>
@endpush
@endif

@endsection
