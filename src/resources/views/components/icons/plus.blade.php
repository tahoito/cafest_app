@props([
  'size' => 24,      // 表示サイズ(px)
  'stroke' => 2,     // 線の太さ
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 30 30"
  fill="none"
  stroke="currentColor"
  stroke-width="{{ $stroke }}"
  stroke-linecap="round"
  stroke-linejoin="round"
  width="{{ $size }}"
  height="{{ $size }}"
  {{ $attributes->merge(['class' => 'block shrink-0']) }}
>

<path d="M8 12H16M12 8V16M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" />
</svg>

