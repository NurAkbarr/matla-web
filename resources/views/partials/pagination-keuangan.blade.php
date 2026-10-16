{{-- Custom Pagination for Keuangan matching design.md --}}
<div class="flex items-center gap-1.5 sm:gap-2">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-muted)] opacity-40 flex items-center justify-center text-xs cursor-not-allowed select-none" aria-disabled="true">&lt;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 hover:text-[var(--primary-500)] flex items-center justify-center transition text-xs font-semibold" aria-label="Halaman Sebelumnya">&lt;</a>
    @endif

    {{-- Pagination Elements --}}
    @if (isset($elements))
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center text-xs text-[var(--text-muted)] select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 rounded-lg bg-[var(--primary-500)] text-white flex items-center justify-center text-xs font-bold shadow-xs select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 hover:text-[var(--primary-500)] flex items-center justify-center transition text-xs font-semibold">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        {{-- Fallback if only 1 page or elements not provided --}}
        <span class="w-8 h-8 rounded-lg bg-[var(--primary-500)] text-white flex items-center justify-center text-xs font-bold shadow-xs select-none">1</span>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-body)] hover:bg-[var(--primary-50)] dark:hover:bg-[var(--primary-900)]/40 hover:text-[var(--primary-500)] flex items-center justify-center transition text-xs font-semibold" aria-label="Halaman Berikutnya">&gt;</a>
    @else
        <span class="w-8 h-8 rounded-lg border border-[var(--border)] text-[var(--text-muted)] opacity-40 flex items-center justify-center text-xs cursor-not-allowed select-none" aria-disabled="true">&gt;</span>
    @endif
</div>
