@props([
  'label' => '',
  'href' => null,
  'active' => false,
])

@php
  $tag = $href ? 'a' : 'button';

  $base = "w-[75px] h-[75px] rounded-xl flex flex-col items-center justify-center gap-1";

  $state = $active
    ? "bg-main text-form"
    : "bg-accent text-text_color";
@endphp

<{{ $tag }}
  @if($href) href="{{ $href }}" @else type="button" @endif
  {{ $attributes->merge(['class' => "{$base} {$state}"]) }}
>
  <div class="h-8 w-8 grid place-items-center">
    {{ $slot }}
  </div>

  <div class="text-[12px] leading-none text-center">
    {{ $label }}
  </div>
</{{ $tag }}>
