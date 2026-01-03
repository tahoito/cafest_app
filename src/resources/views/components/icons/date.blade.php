@props([
  'size' => 30,      // 表示サイズ(px)
  'stroke' => 1.5,     // 線の太さ
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
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>
<path d="M7.98881 2V6M16.0115 2V6M2.97461 10H21.0257M7.98881 14H7.99884M12.0002 14H12.0102M16.0115 14H16.0216M7.98881 18H7.99884M12.0002 18H12.0102M16.0115 18H16.0216M4.98029 4H19.0201C20.1278 4 21.0257 4.89543 21.0257 6V20C21.0257 21.1046 20.1278 22 19.0201 22H4.98029C3.87258 22 2.97461 21.1046 2.97461 20V6C2.97461 4.89543 3.87258 4 4.98029 4Z" stroke="#201200" stroke-linecap="round" stroke-linejoin="round"/>
</svg>


