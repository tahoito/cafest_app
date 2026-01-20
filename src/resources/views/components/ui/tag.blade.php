@props([
  'active' => false,
  'disabled' => false,
  'type' => 'button',
  'tone' => 'main',
  'href' => null,
])

@php
$common = 'inline-flex items-center justify-center whitespace-nowrap rounded-full border px-[16px] py-[2px] text-sm transition select-none';
$state = $disabled
  ? 'opacity-50 cursor-not-allowed'
  : 'cursor-pointer hover:opacity-90 active:scale-[0.98]';

if ($tone === 'main2') {
  $theme = $active
    ? 'bg-main2 border-main2 text-form'
    : 'bg-base_color border-main2 text-text_color';
} else {
  $theme = $active
    ? 'bg-main border-main text-form'
    : 'bg-base_color border-main text-text_color';
}

$class = "$common $state $theme";
@endphp

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
  </a>
@else
  <button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $class]) }}
  >
    {{ $slot }}
  </button>
@endif