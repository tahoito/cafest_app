@props([
  'size' => 60,      // 表示サイズ(px)
  'stroke' => 2,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 60 60"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>

<path d="M7.5 7.5V47.5C7.5 48.8261 8.02678 50.0979 8.96447 51.0355C9.90215 51.9732 11.1739 52.5 12.5 52.5H52.5M47.5 22.5L35 35L25 25L17.5 32.5" />
</svg>
