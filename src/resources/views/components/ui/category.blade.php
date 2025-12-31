@props([
  'label' => '',
  'href' => null,
  'active' => false,
])

@php
  $tag = $href ? 'a' : 'button';

  $base = "w-[80px] h-[80px] rounded-xl flex flex-col items-center justify-center gap-1";

  $state = $active
    ? "bg-main text-form"
    : "bg-accent text-text_color";
@endphp

<{{ $tag }}
  @if($href) href="{{ $href }}" @else type="button" @endif
  {{ $attributes->merge(['class' => "{$base} {$state}"]) }}
>
  <div class="h-12 w1-12 grid place-items-center pt-0.5">
    {{ $slot }}
  </div>

  <div class="mt-0.5 text-[12px] leading-none text-center">
    {{ $label }}
  </div>
</{{ $tag }}>
