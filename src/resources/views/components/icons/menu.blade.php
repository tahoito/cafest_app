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
<path d="M17.5 20H42.5M17.5 30H42.5M17.5 40H42.5M12.5 7.5H47.5C50.2614 7.5 52.5 9.73858 52.5 12.5V47.5C52.5 50.2614 50.2614 52.5 47.5 52.5H12.5C9.73858 52.5 7.5 50.2614 7.5 47.5V12.5C7.5 9.73858 9.73858 7.5 12.5 7.5Z" />
</svg>
