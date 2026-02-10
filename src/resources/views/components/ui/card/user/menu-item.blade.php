@props([
  'item' => null, // RecommendedItem
])

@php
  $photoPath = data_get($item,'image');
  $name = (string) data_get($item, 'name', '');
  $price = data_get($item, 'price');
  $description = (string) data_get($item, 'description', '');

  $imageUrl = $photoPath
    ? \App\Support\MediaUrl::from($photoPath)
    : null;
@endphp


<div class="w-[353px] h-[175px] rounded-lg border border-main bg-base p-5">
  <div class="flex gap-5">
    <div class="shrink-0">
      <div class="h-[135px] w-[135px] overflow-hidden rounded-xl bg-base_color ring-1 ring-black/5 flex items-center justify-center">
        @if($imageUrl)
          <img
            src="{{ $imageUrl }}"
            alt=""
            class="h-full w-full object-cover"
            loading="lazy"
          />
        @else
          <x-icons.no_image class="w-8 h-8 text-placeholder" />
        @endif
      </div>
    </div>

    <div class="min-w-0 flex-1">
      <div class="space-y-1">
        <div class="mt-2 text-lg text-text_color">
          {{ $name }}
        </div>

        @if(!is_null($price))
          <div class="text-base text-text_color">
            {{ number_format((int)$price) }}円
          </div>
        @endif
      </div>

      @if($description !== '')
        <div class="mt-4 text-sm text-text_color learning-relaxed">
          {{ $description }}
        </div>
      @endif
    </div>
  </div>
</div>
