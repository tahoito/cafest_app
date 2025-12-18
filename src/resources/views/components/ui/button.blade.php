@props([
    'variant' => 'action', // action | secondary | next
    'theme' => 'user',     // user | store
    'type' => 'button',
    'as' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded-full font-medium transition';

    $themes = [
        'user' => 'bg-main text-form',
        'store' => 'bg-main2 text-form',
    ];

    $variants = [
        'action' => 'px-6 py-4 text-lg hover:opacity-90 shadow-[0_6px_16px_rgba(0,0,0,0.16)]',
        'secondary' => 'px-6 py-3 text-lg hover:opacity-90 shadow-[0_6px_16px_rgba(0,0,0,0.16)]',
        'next' => 'px-4 py-2 hover:opacity-90 shadow-[0_6px_16px_rgba(0,0,0,0.16)]',
    ];

    $classes = [
        $base,
        $themes[$theme] ?? $themes['user'],
        $variants[$variant] ?? $variants['action'],
    ];
@endphp

@if ($as === 'a')
    <a {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
