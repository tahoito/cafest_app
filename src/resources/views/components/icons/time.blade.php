@props([
  'size' => 24,
  'stroke' => 1.5,
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
  {{ $attributes->merge(['class' => 'inline-block align-middle']) }}
>

<g clip-path="url(#clip0_2059_3295)">
<path d="M9.99935 5.00033V10.0003L13.3327 8.33366M18.3327 10.0003C18.3327 14.6027 14.6017 18.3337 9.99935 18.3337C5.39698 18.3337 1.66602 14.6027 1.66602 10.0003C1.66602 5.39795 5.39698 1.66699 9.99935 1.66699C14.6017 1.66699 18.3327 5.39795 18.3327 10.0003Z" />
</g>
<defs>
<clipPath id="clip0_2059_3295">
<rect width="20" height="20" fill="white"/>
</clipPath>
</defs>
</svg>
