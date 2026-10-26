{{-- Custom Pagination for Keuangan matching design.md --}}
<nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center gap-1.5">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="inline-flex items-center gap-1.5 h-8 px-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] text-xs font-medium text-[var(--text-muted)] opacity-50 cursor-not-allowed select-none bg-[var(--surface)]" aria-disabled="true">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="hidden sm:inline">Sebelumnya</span>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 h-8 px-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] text-xs font-medium text-[var(--text-body)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] transition bg-[var(--surface)] cursor-pointer" aria-label="Halaman sebelumnya">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="hidden sm:inline">Sebelumnya</span>
        </a>
    @endif

    {{-- Pagination Elements --}}
    @if ($paginator->hasPages() && isset($elements))
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center text-xs text-[var(--text-muted)] select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 rounded-[var(--radius-xs)] bg-[var(--primary-500)] text-white flex items-center justify-center text-xs font-bold tabular-nums select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-[var(--radius-xs)] border border-[var(--border)] text-[var(--text-body)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] flex items-center justify-center transition text-xs font-medium tabular-nums bg-[var(--surface)] cursor-pointer">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        <span class="w-8 h-8 rounded-[var(--radius-xs)] bg-[var(--primary-500)] text-white flex items-center justify-center text-xs font-bold tabular-nums select-none">1</span>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 h-8 px-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] text-xs font-medium text-[var(--text-body)] hover:text-[var(--primary-600)] hover:bg-[var(--primary-50)] transition bg-[var(--surface)] cursor-pointer" aria-label="Halaman berikutnya">
            <span class="hidden sm:inline">Berikutnya</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @else
        <span class="inline-flex items-center gap-1.5 h-8 px-2.5 rounded-[var(--radius-xs)] border border-[var(--border)] text-xs font-medium text-[var(--text-muted)] opacity-50 cursor-not-allowed select-none bg-[var(--surface)]" aria-disabled="true">
            <span class="hidden sm:inline">Berikutnya</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    @endif
</nav>
