@props([
  'active' => false,
  'disabled' => false,
  'type' => 'button',
  'variant' => 'area', // area | mood
])

@php
$variants = [
  'area' => [
    'base' => 'h-8 w-full px-2 text-sm rounded-full',   // ← grid用
  ],
  'mood' => [
    'base' => 'h-10 w-full px-3 text-sm rounded-lg',   // ←少し大きめ
  ],
];

$common = 'inline-flex items-center justify-center border transition select-none';

$state = $disabled
  ? 'opacity-50 cursor-not-allowed'
  : 'cursor-pointer hover:opacity-90 active:scale-[0.98]';

$theme = $active
  ? 'bg-main border-main text-text-color'
  : 'bg-accent border-main text-text-color';
@endphp

<button
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge([
    'class' => "$common {$variants[$variant]['base']} $theme $state"
  ]) }}
>
  {{ $slot }}
</button>
