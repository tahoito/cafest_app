@props([
  'item' => null, // RecommendedItem
])

@php
  $name = (string) data_get($item, 'name', '');
  $price = data_get($item, 'price');
  $description = (string) data_get($item, 'description', '');
  $imageUrl = (string) (
    data_get($item, 'image_url')
    ?? data_get($item, 'image')
    ?? ''
  );
@endphp

<div class="w-[353px] h-[175px] rounded-lg border-2 border-main bg-base p-5">
  <div class="flex gap-5">
    <div class="shrink-0">
      <div class="h-[130px] w-[130px] overflow-hidden rounded-xl bg-base ring-1 ring-black/5">
        <img
          src="{{ $imageUrl !== '' ? $imageUrl : asset('images/store/card.png') }}"
          alt=""
          class="h-full w-full object-cover"
          loading="lazy"
        />
      </div>
    </div>

    <div class="min-w-0 flex-1">
      <div class="flex items-center gap-2">
        <div class="truncate text-base text-text_color">
          {{ $name }}
        </div>

        @if(!is_null($price))
          <div class="truncate text-base text-text_color">
            ¥{{ number_format((int)$price) }}
          </div>
        @endif
      </div>

      @if($description !== '')
        <div class="mt-1 text-sm text-text_color">
          {{ $description }}
        </div>
      @endif
    </div>
  </div>
</div>
