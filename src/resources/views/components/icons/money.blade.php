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

<path d="M10 7.91667V17.5M10 7.91667L5 2.5M10 7.91667L15 2.5M5 12.5H15M5 9.16667H15" />
</svg>
