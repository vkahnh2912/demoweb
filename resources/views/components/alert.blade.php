@props([
    'type' => 'success',
    'message' => null,
])

@php
    $typeClasses = match($type) {
        'error', 'danger' => 'bg-rose-50 border-rose-200 text-rose-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'info' => 'bg-sky-50 border-sky-200 text-sky-800',
        default => 'bg-emerald-50 border-emerald-200 text-emerald-800',
    };
@endphp

@if($message || $slot->isNotEmpty())
    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="border px-4 py-3 rounded-none flex items-center justify-between text-sm {{ $typeClasses }} mb-4" role="alert">
        <div class="flex items-center gap-2">
            @if($type === 'success')
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @elseif($type === 'error' || $type === 'danger')
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @elseif($type === 'warning')
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @endif
            <span>{{ $message ?? $slot }}</span>
        </div>
        <button @click="show = false" type="button" class="text-current opacity-60 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif
