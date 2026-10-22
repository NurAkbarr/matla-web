{{-- Custom Pagination for Keuangan matching MATLA Green Theme --}}
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1.5 sm:gap-2">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[var(--border)] text-xs font-medium text-[var(--text-muted)] opacity-50 cursor-not-allowed select-none bg-[var(--surface)]" aria-disabled="true">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Previous</span>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[var(--border)] text-xs font-medium text-[var(--text-body)] hover:text-emerald-700 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition shadow-xs bg-[var(--surface)]" aria-label="Halaman Sebelumnya">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Previous</span>
        </a>
    @endif

    {{-- Pagination Elements --}}
    @if ($paginator->hasPages() && isset($elements))
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="min-w-[28px] h-8 flex items-center justify-center text-xs text-[var(--text-muted)] select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="min-w-[32px] h-8 px-2.5 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-xs select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="min-w-[32px] h-8 px-2.5 rounded-lg border border-[var(--border)] text-[var(--text-body)] hover:text-emerald-700 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 flex items-center justify-center transition text-xs font-semibold bg-[var(--surface)]">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        {{-- Single page indicator --}}
        <span class="min-w-[32px] h-8 px-2.5 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-xs select-none">1</span>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[var(--border)] text-xs font-medium text-[var(--text-body)] hover:text-emerald-700 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition shadow-xs bg-[var(--surface)]" aria-label="Halaman Berikutnya">
            <span>Next</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[var(--border)] text-xs font-medium text-[var(--text-muted)] opacity-50 cursor-not-allowed select-none bg-[var(--surface)]" aria-disabled="true">
            <span>Next</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    @endif
</nav>
