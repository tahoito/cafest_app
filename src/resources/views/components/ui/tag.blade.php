@props([
  'active' => false,
  'disabled' => false,
  'type' => 'button',
  'icon' => false,
])

@php
$common = 'inline-flex items-center justify-center whitespace-nowrap rounded-full border px-[16px] py-[3px] text-sm transition select-none';
$state = $disabled
  ? 'opacity-50 cursor-not-allowed'
  : 'cursor-pointer hover:opacity-90 active:scale-[0.98]';

$theme = $active
  ? 'bg-main border-main text-form'
  : 'bg-base border-main text-text';

@endphp

<button
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge(['class' => "$common $state $theme"]) }}
>
  {{ $slot }}
</button>
