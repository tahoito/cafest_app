@props([
  'size' => 20,      // 表示サイズ(px)
  'stroke' => 1.5,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 20 20"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'block shrink-0']) }}
>
  <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

