@props([
  'review',
  'href' => null,
  'variant' => 'compact', // mini | compact
])

@php
  $userName = (string) data_get($review, 'user.name', data_get($review, 'username', ''));
  $userIcon = data_get($review, 'user.icon_path', data_get($review, 'icon_path', null));

  $shop     = data_get($review, 'shop', data_get($review, 'store', null));
  $shopId   = data_get($shop, 'id', data_get($review, 'shop_id', null));
  $shopName = (string) data_get($shop, 'name', data_get($review, 'shop_name', ''));

  $rating = (float) data_get($review, 'reviews_avg_rating', data_get($review, 'rating', 0));
  $body   = (string) data_get($review, 'body', data_get($review, 'comment', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date', null));
  $link = $href ?? ($shopId ? url("/stores/{$shopId}") : '#');

  // card
  $base = "rounded-xl bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)]";

  // サイズ：一覧(=compact)は高さ固定しない。miniだけ固定。
  $size = match ($variant) {
    'mini'  => "inline-block w-full h-[196px]",
    default => "block w-full",
  };

  // 余白：スクショ寄せで少し横を広め
  $wrap = match ($variant) {
    'mini'  => "px-4 py-3",
    default => "px-5 py-4",
  };

  $avatarSize = match ($variant) {
    'mini'  => "w-9 h-9",
    default => "w-11 h-11",
  };

  $dateText = '';
  if ($date) {
    try {
      $dateText = is_string($date) ? $date : $date->format('Y/m/d');
    } catch (\Throwable $e) {
      $dateText = (string) $date;
    }
  }

  // 星は「丸め」より「切り捨て」の方が見た目が安定する（好みでroundでもOK）
  $stars = max(0, min(5, (int) floor($rating + 0.00001)));
@endphp

<a
  href="{{ $link }}"
  {{ $attributes->merge([
    'class' =>
      'block w-[353px] h-[196px]
       rounded-xl bg-form
       ring-1 ring-black/5
       shadow-[0_2px_10px_rgba(0,0,0,0.12)]'
  ]) }}
>
  <div class="h-full px-5 py-4 flex flex-col">

    {{-- 1段目：ユーザー + 日付 --}}
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <div class="{{ $avatarSize }} rounded-full bg-base overflow-hidden shrink-0">
          @if($userIcon)
            <img src="{{ asset($userIcon) }}" class="w-full h-full object-cover" alt="">
          @endif
        </div>

        <div class="min-w-0">
          <div class="text-text text-base font-semibold truncate">
            {{ $userName }}
          </div>
        </div>
      </div>

      @if($dateText !== '')
        <div class="text-placeholder text-xs shrink-0 pt-1">
          {{ $dateText }}
        </div>
      @endif
    </div>

    {{-- 2段目：店舗名 + 星 --}}
    <div class="mt-3 flex items-center justify-between gap-3">
      <div class="text-text text-base font-medium truncate">
        {{ $shopName }}
      </div>

      <div class="flex items-center gap-[2px] shrink-0">
        @for ($i = 1; $i <= 5; $i++)
          <x-icons.star class="h-3 w-3 {{ $i <= $stars ? 'text-star' : 'text-placeholder' }}" />
        @endfor
      </div>
    </div>

    {{-- 本文：残り高さを使う --}}
    <div class="mt-2 text-text text-[15px] leading-snug line-clamp-2">
      {{ $body }}
    </div>

  </div>
</a>

