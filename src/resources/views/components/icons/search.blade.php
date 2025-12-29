@props([
  'size' => 24,      // 表示サイズ(px)
  'stroke' => 2,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 40 40"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>
    <path d="M30.625 30.625L24.2958 24.2958M27.7083 16.0417C27.7083 22.485 22.485 27.7083 16.0417 27.7083C9.59834 27.7083 4.375 22.485 4.375 16.0417C4.375 9.59834 9.59834 4.375 16.0417 4.375C22.485 4.375 27.7083 9.59834 27.7083 16.0417Z" />
</svg>
