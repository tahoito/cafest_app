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
<path d="M18.3327 5.83301L10.8402 10.6055C10.5859 10.7532 10.2971 10.831 10.0031 10.831C9.70907 10.831 9.42027 10.7532 9.16602 10.6055L1.66602 5.83301M3.33268 3.33301H16.666C17.5865 3.33301 18.3327 4.0792 18.3327 4.99967V14.9997C18.3327 15.9201 17.5865 16.6663 16.666 16.6663H3.33268C2.41221 16.6663 1.66602 15.9201 1.66602 14.9997V4.99967C1.66602 4.0792 2.41221 3.33301 3.33268 3.33301Z" />
</svg>
