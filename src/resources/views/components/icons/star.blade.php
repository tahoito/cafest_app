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
    <path d="M4.56465 0L5.64232 3.10942H9.12972L6.30835 5.03115L7.38602 8.14058L4.56465 6.21885L1.74328 8.14058L2.82095 5.03115L-0.000422955 3.10942H3.48698L4.56465 0Z" fill="#666666"/>
</svg>
