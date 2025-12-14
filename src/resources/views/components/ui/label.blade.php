@props([
    'for' => null,
])

<label
  @if($for) for="{{ $for }}" @endif
  {{ $attributes->merge([
    'class' => 'block text-lg font-medium text-text mb-0.5'
  ]) }}
>
  {{ $slot }}
</label>
