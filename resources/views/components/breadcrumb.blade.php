@props([
    'items' => [],
])

<nav aria-label="Breadcrumb" class="py-3 text-xs text-zinc-500">
    <ol class="flex items-center flex-wrap gap-2">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="hover:text-zinc-900 transition-colors">Trang chủ</a>
        </li>
        @foreach($items as $item)
            <li class="inline-flex items-center gap-2">
                <svg class="w-3 h-3 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="hover:text-zinc-900 transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-zinc-900 truncate max-w-[200px] sm:max-w-none">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
