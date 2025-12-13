@props([
    'variant' => 'action', // action | secondary | next
    'theme' => 'user',     // user | store
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded-full font-medium transition';

    $themes = [
        'user' => 'bg-main text-form',
        'store' => 'bg-main2 text-form',
    ];

    $variants = [
        'action' => 'py-4 text-lg hover:opacity-90',
        'secondary' => 'py-3 text-lg shadow-sm hover:opacity-90',
        'next' => 'px-4 py-2 shadow-sm hover:opacity-90',
    ];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([
        $base,
        $themes[$theme],
        $variants[$variant],
    ]) }}
>
    {{ $slot }}
</button>
