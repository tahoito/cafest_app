@props([
  'active' => false,
  'disabled' => false,
  'type' => 'button', // button | submit
  'size' => 'md',     // sm | md
  'variant' => 'area', // area | mood
])

@php
$variants = [
  'area' => [
    'base' => 'h-10 px-8 text-base rounded-full',
  ],
  'mood' => [
    'base' => 'h-14 px-8 text-base rounded-xl',
  ],
];

$common = 'inline-flex items-center justify-center border transition select-none';

$state = $disabled
  ? 'opacity-50 cursor-not-allowed'
  : 'cursor-pointer hover:opacity-90 active:scale-[0.98]';

$bgColor = $active ? 'bg-main' : 'bg-accent';
$theme = "$bgColor border-main text-text-color main-form";
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
