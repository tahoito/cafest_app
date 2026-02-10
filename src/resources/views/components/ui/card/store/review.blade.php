@props(['review'])

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  $imgUrl = function ($raw) {
    if (!$raw) return null;

    if (is_string($raw) && Str::startsWith($raw, ['http://', 'https://'])) {
      return $raw;
    }

    if (is_string($raw) && Str::startsWith($raw, 'storage/')) {
      return asset($raw);
    }

    if (is_string($raw) && Str::startsWith($raw, '/')) {
      return asset(ltrim($raw, '/'));
    }

   $path = ltrim((string) $raw, '/');

   return Storage::url($path);
  };

  $userIconPath =
    data_get($review,'user.avatar_url')
    ?? data_get($review,'user.icon_path')
    ?? data_get($review,'icon_path')
    ?? null;

  $fallbackAvatar = Storage::url('images/users/user1.jpg');
  $userIconUrl = $imgUrl($userIconPath) ?? $fallbackAvatar;

  // review images
  $imagesRaw =
    data_get($review, 'images')
    ?? data_get($review, 'review_images')
    ?? data_get($review, 'photos')
    ?? [];

  $images = collect($imagesRaw)->map(function ($img) use ($imgUrl) {
    $path = data_get($img, 'url', data_get($img, 'path', $img));
    return $imgUrl($path);
  })->filter()->values();

  $userName = (string) data_get($review, 'user.name', data_get($review, 'username', ''));
  $rating   = (float) data_get($review, 'rating', 0);
  $body     = (string) data_get($review, 'body', data_get($review, 'body', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date'));
  $dateText = '';
  if ($date) {
    try { $dateText = is_string($date) ? $date : $date->format('Y/m/d'); }
    catch (\Throwable $e) { $dateText = (string) $date; }
  }

  $stars = max(0, min(5, (int) floor($rating + 0.00001)));

  $reviewId = data_get($review, 'id', data_get($review, 'review_id'));
  $storeId = data_get($review, 'store_id')
    ?? data_get($review, 'shop_id')
    ?? data_get($review, 'store.id')
    ?? data_get($review, 'shop.id');

  $moreUrl = ($storeId && $reviewId) 
      ? route('store.reviews.show', ['review' => $reviewId])
      : null;

  $endpoint = ($storeId && $reviewId)
      ? $moreUrl . '?format=json'
      :null;

  $tagsRaw = data_get($review, 'tags')
      ?? data_get($review, 'review_tags')
      ?? data_get($review, 'tag_names')
      ?? [];
      
  $tags = collect($tagsRaw)
      ->map(fn($t) => is_string($t) ? $t : (data_get($t, 'name') ?? data_get($t,'label')))
      ->filter()
      ->values();

@endphp

<div
  x-data="{ open: false }"
  {{ $attributes->merge([
    'class' => 'w-full max-w-[353px] rounded-xl bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)] text-left p-4 space-y-3'
  ]) }}
>
  <div class="flex items-start justify-between">
    <div class="flex items-center gap-3">
      <img src="{{ $userIconUrl }}" class="h-10 w-10 rounded-full object-cover" alt="">
      <div class="space-y-1">
        <div class="text-base font-medium text-text_color">{{ $userName }}</div>
        <div class="flex items-center gap-0.5">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star class="h-4 w-4 {{ $i <= $stars ? 'text-star' : 'text-placeholder' }}" />
          @endfor
        </div>
      </div>
    </div>

    @if($dateText !== '')
      <div class="text-sm text-placeholder">{{ $dateText }}</div>
    @endif
  </div>

  @if($tags->isNotEmpty())
    <div class="flex flex-wrap gap-2">
      @foreach($tags as $t)
        <x-ui.tag
          :active="true"
          type="button"
          class="pointer-events-none"
        >
          {{ $t }}
        </x-ui.tag>
      @endforeach
    </div>
  @endif


  <p class="text-base text-text_color leading-relaxed"
     :class="open ? '' : 'line-clamp-3'">
    {{ $body }}
  </p>

  {{-- 画像 --}}
  @if($images->count() > 0)
    <div class="flex gap-2" x-show="!open" x-cloak>
      @foreach($images->take(2) as $i => $url)
        <div class="relative w-[125px] h-[125px] overflow-hidden rounded-xl">
          <img src="{{ $url }}" class="h-full w-full object-cover" alt="">
          @if($i === 1 && $images->count() > 2)
            <div class="absolute inset-0 bg-black/40 grid place-items-center text-white text-2xl font-semibold">
              +{{ $images->count() - 2 }}
            </div>
          @endif
        </div>
      @endforeach
    </div>

    {{-- 開いた時：全部 --}}
    <div class="grid grid-cols-2 gap-2" x-show="open" x-transition x-cloak>
      @foreach($images as $url)
        <div class="relative aspect-square overflow-hidden rounded-xl">
          <img src="{{ $url }}" class="h-full w-full object-cover" alt="">
        </div>
      @endforeach
    </div>
  @endif

  {{-- もっと見る --}}
  <div class="pt-1 flex justify-end">
    <button type="button" @click="open = !open"
      class="inline-flex items-center gap-1 text-sm text-main2 hover:opacity-80">
      <span x-text="open ? '閉じる' : 'もっと見る'"></span>
    </button>
  </div>
</div>
