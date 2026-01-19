@props([
  'store',
  'href' => '#',
  'variant' => 'list',
  'faved' => false,
])

@php
  $url = $href ?? '#';

  $name = data_get($store, 'name', 'No Name');
  $area = data_get($store, 'area', '');
  $mood = data_get($store, 'mood', '');
  $imageUrl = data_get($store, 'image_url');

  $rating = (float) data_get($store, 'rating', 0);
  $rating = max(0, min(5, $rating));
  $filled = (int) floor($rating + 0.00001);

  $meta = trim($area) !== '' && trim($mood) !== ''
    ? "{$area}・{$mood}"
    : (trim($area) !== '' ? $area : $mood);

@endphp

<a href="{{ $url }}"
   class="block w-[170px] h-[210px] rounded-lg bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)] overflow-hidden">

  {{-- image --}}
  <div class="relative px-4 pt-3 pb-2">
    <div class="w-full aspect-square max-w-[138px] mx-auto overflow-hidden bg-base">
      <img
        src="{{ $imageUrl ?: asset('images/store/card.png') }}"
        alt="{{ $name }}"
        loading="lazy"
        class="w-full h-full object-cover"
      >
    </div>

    <button type="button"
      @click.stop
      class="absolute top-2 right-2 grid h-8 w-8 place-items-center rounded-full bg-accent"
      aria-label="お気に入り"
    >
      <x-icons.heart class="w-8 h-8" />
    </button>
  </div>

  <div class="px-4 pt-1 pb-5">
    {{-- name + stars --}}
    <div class="flex items-end justify-between gap-2">
      <div class="min-w-0">
        <div class="text-base leading-none text-text_color truncate">
          {{ $name }}
        </div>
      </div>

      <div class="shrink-0">
        <div class="flex w-[48px] h-[9px] items-center justify-between">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star
              class="h-[9px] w-[9px] {{ $i <= $filled ? 'text-star' : 'text-placeholder' }}"
            />
          @endfor
        </div>
      </div>
    </div>

    <div class="mt-1 flex items-center text-[14px] leading-[14px] text-text_color">
      <x-icons.pin class="w-4 h-4 shrink-0 text-text_color relative top-[1px]" />

      <span class="min-w-0 line-clamp-1">
        {{ $meta }}
      </span>
    </div>
  </div>
</a>
