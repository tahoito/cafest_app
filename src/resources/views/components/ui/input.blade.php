@props([
  'type' => 'text',
])

<input
  type="{{ $type }}"
  {{ $attributes->class([
    'w-full rounded-xl bg-form px-4 py-4 text-text_color_color rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.12)]',
    'placeholder:text-placeholder focus:outline-none focus:ring-2 focus:ring-text/60',
  ]) }}
/>
