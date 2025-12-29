@props([
  'size' => 24,
  'stroke' => 0,
])

<svg
  xmlns="http://www.w3.org/2000/svg"
  viewBox="0 0 9.12972 8.14058"
  width="{{ $size }}"
  height="{{ $size }}"
  fill="currentColor"
  {{ $attributes->merge(['class' => 'inline-block shrink-0']) }}
>
  <path d="M4.56465 0L5.64232 3.10942H9.12972L6.30835 5.03115L7.38602 8.14058L4.56465 6.21885L1.74328 8.14058L2.82095 5.03115L-0.000422955 3.10942H3.48698L4.56465 0Z"/>
</svg>
