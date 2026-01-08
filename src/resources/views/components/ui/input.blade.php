@props([
  'type' => 'text',
])

<input
  type="{{ $type }}"
  {{ $attributes->class([
    'w-full rounded-xl bg-form px-4 py-4 text-text_color shadow-[0_1px_4px_rgba(0,0,0,0.20)] border border-transparent',
    'placeholder:text-placeholder focus:outline-none focus:ring-1  focus:border-main',
  ]) }}
/>