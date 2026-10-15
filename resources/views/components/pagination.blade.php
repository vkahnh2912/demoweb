@props([
    'paginator',
])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between border-t border-zinc-200 pt-6 mt-8">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-zinc-400 bg-white border border-zinc-200 cursor-default">
                    Trước
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-zinc-700 bg-white border border-zinc-300 hover:bg-zinc-50">
                    Trước
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-zinc-700 bg-white border border-zinc-300 hover:bg-zinc-50">
                    Sau
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-medium text-zinc-400 bg-white border border-zinc-200 cursor-default">
                    Sau
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-zinc-500">
                    Hiển thị từ <span class="font-medium text-zinc-900">{{ $paginator->firstItem() ?? 0 }}</span> đến <span class="font-medium text-zinc-900">{{ $paginator->lastItem() ?? 0 }}</span> trong <span class="font-medium text-zinc-900">{{ $paginator->total() }}</span> kết quả
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-none gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-zinc-300 bg-white border border-zinc-200 cursor-default">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-900 hover:text-white transition-colors" aria-label="Previous">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-3.5 py-2 text-xs font-bold text-white bg-zinc-900 border border-zinc-900 cursor-default">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-900 hover:text-white transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-900 hover:text-white transition-colors" aria-label="Next">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-zinc-300 bg-white border border-zinc-200 cursor-default">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
