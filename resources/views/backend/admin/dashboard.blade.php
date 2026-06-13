@extends('layouts.backend')

@section('title', 'Admin Dashboard')
@section('breadcrumb', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
    <!-- Stat Card 1 -->
    <div class="bg-white p-5 md:p-6 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Pendaftar Baru</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $pendaftarBaru }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-primary flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white p-5 md:p-6 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalMahasiswa) }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white p-5 md:p-6 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Dosen</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalDosen) }}</h3>
        </div>
        <div class="w-12 h-12 bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 4 (Total Admin) -->
    <div class="bg-white p-5 md:p-6 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Admin</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalAdmin ?? 0) }}</h3>
        </div>
        <div class="w-12 h-12 bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
    </div>
</div>

<div class="mt-6 md:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
    <div class="bg-white p-6 md:p-8 shadow-sm border border-gray-100 flex flex-col h-full">
        <div class="flex items-center justify-between mb-6">
            <h4 class="text-lg font-extrabold text-gray-900">Aktivitas Terakhir</h4>
            <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="text-xs font-bold text-primary hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-100 flex-1">
            @forelse($recentActivities as $activity)
            <div class="py-4 flex items-start space-x-4">
                <div class="w-2.5 h-2.5 mt-1.5 rounded-full {{ $activity->status == 'pending' ? 'bg-amber-400' : ($activity->status == 'accepted' ? 'bg-emerald-500' : 'bg-rose-500') }} shadow-sm"></div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800">
                        {{ $activity->full_name }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1 leading-snug">
                        Pendaftar: <span class="font-medium text-gray-700">{{ $activity->registration_code }}</span> ({{ $activity->study_program }})
                    </p>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                        @if($activity->status == 'pending')
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider rounded border border-amber-100">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 flex flex-col items-center justify-center text-center h-full">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-500">Belum ada aktivitas pendaftar</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Clock and Calendar Column -->
    <div class="space-y-6 md:space-y-8 flex flex-col">
        <!-- Digital Clock -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 shadow-lg border border-slate-700 relative overflow-hidden group rounded-none">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-primary/20 blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center text-white space-y-2 py-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest" id="live-date">Memuat tanggal...</p>
                <div class="flex items-baseline space-x-2">
                    <h2 class="text-5xl font-black tabular-nums tracking-tight" id="live-time">00:00</h2>
                    <span class="text-xl font-bold text-emerald-400" id="live-seconds">00</span>
                </div>
                <p class="text-[11px] font-bold text-slate-400 flex items-center mt-2 uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-1.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Waktu Server Lokal
                </p>
            </div>
        </div>

        <!-- Stylish Calendar -->
        <div class="bg-white p-6 md:p-8 shadow-sm border border-gray-100 flex-1">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-base font-extrabold text-gray-900 uppercase tracking-widest" id="cal-month-year">...</h4>
                <div class="flex space-x-2">
                    <button id="cal-prev" class="p-1.5 hover:bg-gray-50 border border-transparent hover:border-gray-200 rounded text-gray-500 transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="cal-next" class="p-1.5 hover:bg-gray-50 border border-transparent hover:border-gray-200 rounded text-gray-500 transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center mb-4">
                <div class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">Min</div>
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sen</div>
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sel</div>
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Rab</div>
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kam</div>
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jum</div>
                <div class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Sab</div>
            </div>
            <div id="calendar-days" class="grid grid-cols-7 gap-2 text-center">
                <!-- Days will be generated by JS -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clock
    const timeEl = document.getElementById('live-time');
    const secEl = document.getElementById('live-seconds');
    const dateEl = document.getElementById('live-date');

    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        timeEl.innerText = `${hours}:${minutes}`;
        secEl.innerText = seconds;

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.innerText = now.toLocaleDateString('id-ID', options);
    }
    
    updateClock();
    setInterval(updateClock, 1000);

    // Calendar
    const calMonthYear = document.getElementById('cal-month-year');
    const calDays = document.getElementById('calendar-days');
    let currDate = new Date();
    let displayMonth = currDate.getMonth();
    let displayYear = currDate.getFullYear();

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function renderCalendar(month, year) {
        calDays.innerHTML = '';
        calMonthYear.innerText = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        // Empty slots for first week
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'py-2.5';
            calDays.appendChild(emptyDiv);
        }

        // Days
        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            const isToday = i === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            
            dayDiv.className = `py-2.5 text-sm font-bold flex items-center justify-center cursor-default transition-all ${
                isToday 
                ? 'bg-emerald-600 text-white shadow-md transform hover:scale-110' 
                : 'text-gray-700 hover:bg-gray-100'
            }`;
            dayDiv.innerText = i;
            calDays.appendChild(dayDiv);
        }
    }

    renderCalendar(displayMonth, displayYear);

    document.getElementById('cal-prev').addEventListener('click', () => {
        displayMonth--;
        if (displayMonth < 0) { displayMonth = 11; displayYear--; }
        renderCalendar(displayMonth, displayYear);
    });

    document.getElementById('cal-next').addEventListener('click', () => {
        displayMonth++;
        if (displayMonth > 11) { displayMonth = 0; displayYear++; }
        renderCalendar(displayMonth, displayYear);
    });
});
</script>
@endsection
