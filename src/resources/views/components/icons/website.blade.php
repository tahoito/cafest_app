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


<g clip-path="url(#clip0_2084_3716)">
<path d="M18.3327 10.0003C18.3327 14.6027 14.6017 18.3337 9.99935 18.3337M18.3327 10.0003C18.3327 5.39795 14.6017 1.66699 9.99935 1.66699M18.3327 10.0003H1.66602M9.99935 18.3337C5.39698 18.3337 1.66602 14.6027 1.66602 10.0003M9.99935 18.3337C7.85954 16.0869 6.66602 13.103 6.66602 10.0003C6.66602 6.89761 7.85954 3.91379 9.99935 1.66699M9.99935 18.3337C12.1392 16.0869 13.3327 13.103 13.3327 10.0003C13.3327 6.89761 12.1392 3.91379 9.99935 1.66699M1.66602 10.0003C1.66602 5.39795 5.39698 1.66699 9.99935 1.66699" />
</g>
<defs>
<clipPath id="clip0_2084_3716">
<rect width="20" height="20" fill="white"/>
</clipPath>
</defs>
</svg>
