@props([
  'size' => 24,      // 表示サイズ(px)
  'stroke' => 1.5,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 24 24"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>

<path d="M8.33366 6.66602L11.667 9.99935L8.33366 13.3327M18.3337 9.99935C18.3337 14.6017 14.6027 18.3327 10.0003 18.3327C5.39795 18.3327 1.66699 14.6017 1.66699 9.99935C1.66699 5.39698 5.39795 1.66602 10.0003 1.66602C14.6027 1.66602 18.3337 5.39698 18.3337 9.99935Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
