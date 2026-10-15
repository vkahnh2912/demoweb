@props([
    'price' => 0,
    'salePrice' => null,
    'size' => 'base',
])

@php
    $hasSale = $salePrice !== null && (float)$salePrice > 0 && (float)$salePrice < (float)$price;
    $textClass = match($size) {
        'sm' => 'text-sm',
        'lg' => 'text-xl font-bold',
        'xl' => 'text-2xl font-bold',
        default => 'text-base font-semibold',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    @if($hasSale)
        <span class="{{ $textClass }} text-rose-600 font-bold">
            {{ number_format($salePrice, 0, ',', '.') }}₫
        </span>
        <span class="text-xs text-zinc-400 line-through">
            {{ number_format($price, 0, ',', '.') }}₫
        </span>
    @else
        <span class="{{ $textClass }} text-zinc-900">
            {{ number_format($price, 0, ',', '.') }}₫
        </span>
    @endif
</div>
