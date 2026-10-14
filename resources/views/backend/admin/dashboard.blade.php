@extends('layouts.backend')

@section('title', 'Admin Dashboard')
@section('breadcrumb', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
    <!-- Stat Card 1 -->
    <div class="bg-[var(--surface)] p-5 md:p-6 rounded-2xl border border-[var(--border)] shadow-card shadow-card-hover flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Pendaftar Baru</p>
            <h3 class="text-[32px] leading-tight font-bold text-[var(--text-heading)]">{{ $pendaftarBaru }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[var(--secondary-100)] text-[var(--secondary-600)] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-[var(--surface)] p-5 md:p-6 rounded-2xl border border-[var(--border)] shadow-card shadow-card-hover flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Mahasiswa</p>
            <h3 class="text-[32px] leading-tight font-bold text-[var(--text-heading)]">{{ number_format($totalMahasiswa) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[var(--primary-100)] text-[var(--primary-500)] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-[var(--surface)] p-5 md:p-6 rounded-2xl border border-[var(--border)] shadow-card shadow-card-hover flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Dosen</p>
            <h3 class="text-[32px] leading-tight font-bold text-[var(--text-heading)]">{{ number_format($totalDosen) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[var(--primary-50)] text-[var(--primary-600)] dark:bg-[var(--primary-900)]/40 dark:text-[var(--primary-300)] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card 4 (Total Admin) -->
    <div class="bg-[var(--surface)] p-5 md:p-6 rounded-2xl border border-[var(--border)] shadow-card shadow-card-hover flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-1">Total Admin</p>
            <h3 class="text-[32px] leading-tight font-bold text-[var(--text-heading)]">{{ number_format($totalAdmin ?? 0) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:bg-[var(--secondary-900)]/30 dark:text-[var(--secondary-400)] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
    </div>
</div>

<div class="mt-6 md:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
    <div class="bg-[var(--surface)] p-6 md:p-8 rounded-2xl border border-[var(--border)] shadow-card flex flex-col h-full">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
            <h4 class="text-base md:text-lg font-bold text-[var(--text-heading)]">Aktivitas Terakhir</h4>
            <a href="{{ route('backend.admin.pmb.registrations.index') }}" class="text-xs font-semibold text-[var(--primary-500)] hover:text-[var(--primary-600)] transition-colors">Lihat Semua</a>
        </div>
        <div class="divide-y divide-[var(--border)] flex-1">
            @forelse($recentActivities as $activity)
            <div class="py-4 flex items-start space-x-4">
                <div class="w-2.5 h-2.5 mt-1.5 rounded-full {{ $activity->status == 'pending' ? 'bg-[var(--secondary-500)]' : ($activity->status == 'accepted' ? 'bg-[var(--primary-500)]' : 'bg-[var(--danger)]') }} shadow-xs"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[var(--text-heading)] truncate">
                        {{ $activity->full_name }}
                    </p>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5 leading-snug">
                        Pendaftar: <span class="font-medium text-[var(--text-body)]">{{ $activity->registration_code }}</span> ({{ $activity->study_program }})
                    </p>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="text-[11px] text-[var(--text-muted)] font-medium uppercase tracking-wider flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                        @if($activity->status == 'pending')
                            <span class="px-2.5 py-0.5 bg-[var(--secondary-50)] text-[var(--secondary-700)] dark:bg-[var(--secondary-900)]/40 dark:text-[var(--secondary-400)] text-[10px] font-semibold uppercase tracking-wider rounded border border-[var(--secondary-200)] dark:border-[var(--secondary-700)]/30">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 flex flex-col items-center justify-center text-center h-full">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[var(--primary-50)] dark:bg-[var(--primary-900)]/30 mb-3 text-[var(--primary-500)]">
                    <svg class="w-6 h-6 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-sm font-semibold text-[var(--text-muted)]">Belum ada aktivitas pendaftar</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Clock and Calendar Column -->
    <div class="space-y-6 md:space-y-8 flex flex-col">
        <!-- Digital Clock -->
        <div class="bg-gradient-to-br from-[var(--primary-900)] to-[var(--primary-800)] p-6 md:p-8 rounded-2xl border border-[var(--primary-700)]/40 relative overflow-hidden shadow-card">
            <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 rounded-full bg-[var(--primary-500)]/10 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center text-white space-y-2 py-2">
                <p class="text-xs font-semibold text-[var(--primary-200)] uppercase tracking-widest" id="live-date">Memuat tanggal...</p>
                <div class="flex items-baseline space-x-2 my-1">
                    <h2 class="text-5xl md:text-6xl font-black tabular-nums tracking-tight text-white" id="live-time">00:00</h2>
                    <span class="text-2xl font-bold text-[var(--secondary-500)]" id="live-seconds">00</span>
                </div>
                <p class="text-[11px] font-semibold text-[var(--primary-200)]/90 flex items-center mt-2 uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-1.5 text-[var(--secondary-500)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Waktu Server Lokal
                </p>
            </div>
        </div>

        <!-- Stylish Calendar -->
        <div class="bg-[var(--surface)] p-6 md:p-8 rounded-2xl border border-[var(--border)] shadow-card flex-1 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-sm md:text-base font-bold text-[var(--text-heading)] uppercase tracking-wider" id="cal-month-year">...</h4>
                <div class="flex space-x-2">
                    <button id="cal-prev" class="p-2 hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 border border-[var(--border)] rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] transition-colors cursor-pointer" aria-label="Bulan Sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="cal-next" class="p-2 hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 border border-[var(--border)] rounded-xl text-[var(--text-muted)] hover:text-[var(--text-heading)] transition-colors cursor-pointer" aria-label="Bulan Berikutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center mb-3">
                <div class="text-[11px] font-bold text-[var(--danger)] uppercase tracking-wider">Min</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Sen</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Sel</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Rab</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Kam</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Jum</div>
                <div class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Sab</div>
            </div>
            <div id="calendar-days" class="grid grid-cols-7 gap-1 text-center">
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
        
        if (timeEl) timeEl.innerText = `${hours}:${minutes}`;
        if (secEl) secEl.innerText = seconds;

        if (dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.innerText = now.toLocaleDateString('id-ID', options);
        }
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
        if (!calDays || !calMonthYear) return;
        calDays.innerHTML = '';
        calMonthYear.innerText = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        // Empty slots for first week
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'w-8 h-8 mx-auto';
            calDays.appendChild(emptyDiv);
        }

        // Days
        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            const dayOfWeek = new Date(year, month, i).getDay();
            const isToday = i === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const isSunday = dayOfWeek === 0;
            
            dayDiv.className = `w-8 h-8 mx-auto text-xs md:text-sm font-semibold flex items-center justify-center cursor-default transition-all duration-150 ${
                isToday 
                ? 'bg-[var(--primary-500)] text-white rounded-full shadow-sm font-bold' 
                : (isSunday 
                    ? 'text-[var(--danger)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--surface)] rounded-full' 
                    : 'text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 rounded-full')
            }`;
            dayDiv.innerText = i;
            calDays.appendChild(dayDiv);
        }
    }

    renderCalendar(displayMonth, displayYear);

    const prevBtn = document.getElementById('cal-prev');
    const nextBtn = document.getElementById('cal-next');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            displayMonth--;
            if (displayMonth < 0) { displayMonth = 11; displayYear--; }
            renderCalendar(displayMonth, displayYear);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            displayMonth++;
            if (displayMonth > 11) { displayMonth = 0; displayYear++; }
            renderCalendar(displayMonth, displayYear);
        });
    }
});
</script>
@endsection
