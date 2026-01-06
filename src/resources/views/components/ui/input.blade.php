@props([
  'type' => 'text',
])

<input
  type="{{ $type }}"
  {{ $attributes->class([
    'w-full rounded-xl bg-form px-4 py-4 text-text_color rounded-xl shadow-[0_1px_4px_rgba(0,0,0,0.20)]',
    'placeholder:text-placeholder focus:outline-none focus:ring-2 focus:ring-text/60',
  ]) }}
/>
