@props([
  'review',
  'href' => null,
  'variant' => 'compact', // list | grid | compact | mini
])

@php
  use Illuminate\Support\Facades\Storage;
  $userName = data_get($review, 'user.name', data_get($review, 'username', ''));
  $userIcon = data_get($review, 'user.icon_path', data_get($review, 'icon_path', null));

  $shop     = data_get($review, 'shop', data_get($review, 'store', null));
  $shopId   = data_get($shop, 'id', data_get($review, 'shop_id', null));
  $shopName = data_get($shop, 'name', data_get($review, 'shop_name', ''));

  $rating = (float) data_get($review, 'reviews_avg_rating', data_get($review, 'rating', 0));
  $body   = (string) data_get($review, 'body', data_get($review, 'body', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date', null));

  // ここは「押した後に開ける詳細ページ」として残しておく（モーダル内ボタンで使ってもOK）
  $link = $href ?? ($shopId ? route('user.stores.show', $shopId) : '#');

  $base = "rounded-lg bg-form ring-1 ring-black/5 shadow-[0_1px_4px_rgba(0,0,0,0.20)]";

  $size = match ($variant) {
    'mini'    => "inline-block w-[167px]",
    'grid'    => "block w-full",
    'compact' => "block w-full",
    default   => "block w-full",
  };

  $wrap = match ($variant) {
    'mini'    => "p-2 space-y-1",
    'grid'    => "p-2 space-y-2",
    'compact' => "p-2 space-y-1.5",
    default   => "p-4 space-y-3",
  };

  $avatarSize = match ($variant) {
    'mini'    => "w-7 h-7",
    'compact' => "w-8 h-8",
    default   => "w-11 h-11",
  };

  $dateText = '';
  if ($date) {
      try {
          $dateText = is_string($date) ? $date : $date->format('Y/m/d');
      } catch (\Throwable $e) {
          $dateText = (string) $date;
      }
  }

  $stars = max(0, min(5, (int) round($rating)));

  // ✅ モーダル用：レビューID（review.id or id のどっちか）
  $reviewId = data_get($review, 'id', data_get($review, 'review_id'));
  $storeId  = data_get($review, 'store_id') ?? data_get($review, 'shop_id') ?? data_get($review, 'store.id') ?? data_get($review, 'shop.id');
  $endpoint = ($storeId && $reviewId)
  ? route('user.stores.reviews.show', ['store' => $storeId, 'review' => $reviewId]).'?format=json'
  : null;
@endphp

<div class="hidden">
  id: {{ data_get($review,'id') }} / review_id: {{ data_get($review,'review_id') }} / shop_id: {{ data_get($review,'shop_id') }}
</div>

<button
  type="button"
  x-data
  class="{{ $base }} {{ $size }} block text-left"
  {{ $attributes }}
  aria-label="レビュー詳細を開く"
  x-on:click='(() => {
    const endpoint = @json($endpoint);
    const fallback = @json($link);

    if (endpoint) {
      window.dispatchEvent(new CustomEvent("review:open", {
        detail: { endpoint, fallback_url: fallback }
      }));
    } else {
      window.location.href = fallback;
    }
  })()'
>

  <div class="{{ $wrap }}">

    {{-- 上段：ユーザー + 日付 --}}
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <div class="{{ $avatarSize }} rounded-full bg-base overflow-hidden shrink-0">
@php
  use Illuminate\Support\Facades\Storage;

  $userIconUrl = null;

  if ($userIcon) {
    $icon = (string) $userIcon;

    if (str_starts_with($icon, 'http')) {
      // 外部URL
      $userIconUrl = $icon;

    } elseif (str_starts_with($icon, '/images/')) {
      // ✅ public配下の固定画像
      $userIconUrl = asset(ltrim($icon, '/'));

    } elseif (str_starts_with($icon, '/storage/')) {
      // すでに公開URL
      $userIconUrl = $icon;

    } else {
      // user_icons/xxx.png みたいな storage 相対パス想定
      $path = preg_replace('#^storage/#', '', ltrim($icon, '/'));
      $userIconUrl = Storage::url($path); // => /storage/...
    }
  }
@endphp


        @if($userIconUrl)
          <img src="{{ $userIconUrl }}" class="w-full h-full object-cover" alt="">
        @endif
        </div>

        <div class="min-w-0">
          <div class="text-text_color text-sm font-semibold truncate">{{ $userName }}</div>
        </div>
      </div>

      @if($dateText !== '')
        <div class="text-placeholder text-xs shrink-0">{{ $dateText }}</div>
      @endif
    </div>

    {{-- 中段：店舗名 + 星 --}}
    <div class="flex items-center justify-between gap-3">
      <div class="text-text_color text-sm font-medium truncate">
        {{ $shopName }}
      </div>

      <div class="shrink-0">
        <div class="flex w-[48px] h-[9px] items-center justify-between">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star
              class="h-[9px] w-[9px] {{ $i <= $stars ? 'text-star' : 'text-placeholder' }}"
            />
          @endfor
        </div>
      </div>
    </div>

    {{-- 本文 --}}
    <div class="text-text_color text-[13px] leading-snug line-clamp-2">
      {{ $body }}
    </div>

  </div>
</button>
