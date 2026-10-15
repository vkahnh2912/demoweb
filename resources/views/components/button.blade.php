@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClass = "inline-flex items-center justify-center font-medium rounded-none transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider text-xs";

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-[11px]',
        'lg' => 'px-8 py-3.5 text-sm',
        default => 'px-6 py-2.5 text-xs',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200 border border-transparent',
        'outline' => 'bg-transparent text-zinc-900 border border-zinc-900 hover:bg-zinc-900 hover:text-white',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 border border-transparent',
        'ghost' => 'bg-transparent text-zinc-700 hover:text-zinc-900 hover:bg-zinc-100 border border-transparent',
        default => 'bg-zinc-950 text-white hover:bg-zinc-800 border border-zinc-950',
    };

    $classes = "{$baseClass} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
