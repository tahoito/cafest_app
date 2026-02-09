@props([
  'store',
  'href' => '#',
  'variant' => 'list',
  'faved' => false,
])

@php
  use Illuminate\Support\Facades\Storage;

  $defaultCard = Storage::url('store/card.png');
  $url = $href ?? '#';

  $name = data_get($store, 'name', 'No Name');
  $areaKey = data_get($store, 'area', '');
  $area = $areaKey !== '' ? (config('cafest.areas')[$areaKey] ?? $areaKey) : '';
  $mood = data_get($store, 'mood', '');
  $imageUrl = optional(
    collect(data_get($store, 'slideImages', []))->firstWhere('is_used_on_card', true)
  )->url;


  $rating = (float) data_get($store, 'reviews_avg_rating', data_get($store, 'rating', 0));
  $rating = max(0, min(5, $rating));
  $filled = (int) round($rating);

  $meta = trim($area) !== '' && trim($mood) !== ''
    ? "{$area}・{$mood}"
    : (trim($area) !== '' ? $area : $mood);

  $imageSrc = $defaultCard;

  if ($imageUrl) {
    if (str_starts_with($imageUrl, 'http')) {
      $imageSrc = $imageUrl;
    } elseif (str_starts_with($imageUrl, '/storage/')) {
      $imageSrc = $imageUrl;
    } else {
      $path = preg_replace('#^storage/#', '', ltrim($imageUrl, '/'));
      $imageSrc = Storage::url($path);
    }
  }
@endphp

<a href="{{ $url }}"
  class="block w-[170px] h-[210px] rounded-lg bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)] overflow-hidden">

  {{-- image --}}
  <div class="relative px-4 pt-3 pb-2">
    <div class="w-full aspect-square max-w-[138px] mx-auto overflow-hidden rounded-lg bg-base">
      <img
        src="{{ $imageSrc }}"
        alt="{{ $name }}"
        loading="lazy"
        class="w-full h-full object-cover"
      >
    </div>

    <div x-data="favoriteFolderModal({{ (int) data_get($store,'id') }}, @js($faved))">
      <button
        type="button"
        class="absolute top-2 right-2 grid h-8 w-8 place-items-center rounded-full bg-accent"
        aria-label="お気に入り"
        @click.prevent.stop="toggleAndOpen()"
      >
        <x-icons.heart
          class="w-8 h-8 text-main transition duration-200"
          x-bind:class="on ? 'fill-main text-main scale-110' : 'fill-transparent text-main scale-100'"
        />
      </button>
      <x-ui.modal.favorite :store="$store" />
    </div>
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

    <div class="mt-1 flex items-center text-sm leading-sm text-text_color">
      <x-icons.pin class="w-4 h-4 shrink-0 text-text_color relative top-[1px]" />

      <span class="min-w-0 line-clamp-1">
        {{ $meta }}
      </span>
    </div>
  </div>
</a>
