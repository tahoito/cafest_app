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
@endphp

<a href="{{ $url }}"
   class="block w-[170px] h-[206px] rounded-lg border border-line bg-form shadow-1 overflow-hidden">

  {{-- image --}}
  <div class="relative px-4 pt-4 pb-1">
    <div class="w-[140px] h-[140px] overflow-hidden bg-base mx-auto">
      <img
        src="{{ $imageUrl ?: asset('images/store/card.png') }}"
        alt="{{ $name }}"
        loading="lazy"
      >
    </div>

    {{-- heart --}}
    <button type="button"
      class="absolute top-3 right-3 grid h-9 w-9 place-items-center rounded-full
             bg-base/90 border border-line shadow-1"
      aria-label="お気に入り"
    >
      <x-icons.heart class="h-5 w-5" />
    </button>
  </div>

  {{-- body（写真と店名の間を狭く：pt-0.5相当で pt-1 を使う） --}}
  <div class="px-4 pt-1 pb-3">

    {{-- name + stars --}}
    <div class="flex items-end justify-between gap-2">
      <div class="min-w-0">
        <div class="text-base leading-none text-text truncate">
          {{ $name }}
        </div>
      </div>

      <div class="shrink-0">
        {{-- 星は横並び5つ、枠48×9 --}}
        <div class="flex w-[48px] h-[9px] items-center justify-between">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star
              class="h-[9px] w-[9px] {{ $i <= $filled ? 'text-star' : 'text-placeholder' }}"
            />
          @endfor
        </div>
      </div>
    </div>

    {{-- pin + area/mood --}}
<div class="mt-1 flex items-center gap-1 text-xs leading-none font-medium text-text">
  <x-icons.pin class="w-[15px] h-[15px] text-placeholder shrink-0" />

  <span class="truncate">
    {{ trim($area) !== '' ? $area : $mood }}
  </span>

  @if(trim($area) !== '' && trim($mood) !== '')
    <span class="text-placeholder mx-1">・</span>
    <span class="truncate">{{ $mood }}</span>
  @endif
</div>

  </div>
</a>
