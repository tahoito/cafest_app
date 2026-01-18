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


<path d="M10.0007 15.0003C10.0007 16.8413 8.50827 18.3337 6.66732 18.3337C4.82637 18.3337 3.33398 16.8413 3.33398 15.0003C3.33398 13.1594 4.82637 11.667 6.66732 11.667C8.50827 11.667 10.0007 13.1594 10.0007 15.0003ZM10.0007 15.0003V1.66699L15.834 5.00033" />
</svg>
