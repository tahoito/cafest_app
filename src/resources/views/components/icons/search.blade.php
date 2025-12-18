@props([
  'size' => 24,      // 表示サイズ(px)
  'stroke' => 2,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 50 50"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>
    <path d="M41.125 41.125L32.6258 32.6258M37.2083 21.5417C37.2083 30.1941 30.1941 37.2083 21.5417 37.2083C12.8892 37.2083 5.875 30.1941 5.875 21.5417C5.875 12.8892 12.8892 5.875 21.5417 5.875C30.1941 5.875 37.2083 12.8892 37.2083 21.5417Z" stroke="#201200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
