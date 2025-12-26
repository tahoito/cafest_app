@props([
  'review',
  'href' => null,
  'variant' => 'compact', // list | grid | compact
])

@php
  $userName = data_get($review, 'user.name', data_get($review, 'username', ''));
  $userIcon = data_get($review, 'user.icon_path', data_get($review, 'icon_path', null));

  $shop     = data_get($review, 'shop', data_get($review, 'store', null));
  $shopId   = data_get($shop, 'id', data_get($review, 'shop_id', null));
  $shopName = data_get($shop, 'name', data_get($review, 'shop_name', ''));

  $rating = (float) data_get($review, 'rating', 0);
  $body   = (string) data_get($review, 'body', data_get($review, 'comment', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date', null));
  $link = $href ?? ($shopId ? url("/stores/{$shopId}") : '#');

  $base = "rounded-xl bg-form ring-1 ring-black/5 shadow-[0_2px_6px_rgba(0,0,0,0.12)]";
  $size = match ($variant) {
    'mini'    => "inline-block w-[220px]",   // ←ここでカード幅を固定
    'grid'    => "block w-full",
    'compact' => "block w-full",
    default   => "block w-full",
  };

    $wrap = match ($variant) {
    'mini'    => "p-3 space-y-2",
    'grid'    => "p-3 space-y-2",
    'compact' => "p-2 space-y-1.5",
    default   => "p-4 space-y-3",
  };

  $avatarSize = match ($variant) {
    'mini'    => "w-9 h-9",
    'compact' => "w-9 h-9",
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
@endphp

<a href="{{ $link }}" class="{{ $base }} {{ $size }}" {{ $attributes }}>
  <div class="{{ $wrap }}">

    {{-- 上段：ユーザー + 日付 --}}
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <div class="{{ $avatarSize }} rounded-full bg-base overflow-hidden shrink-0">
          @if($userIcon)
            <img src="{{ asset($userIcon) }}" class="w-full h-full object-cover" alt="">
          @endif
        </div>

        <div class="min-w-0">
          <div class="text-text text-[14px] font-semibold truncate">{{ $userName }}</div>
        </div>
      </div>

      @if($dateText !== '')
        <div class="text-placeholder text-xs shrink-0">{{ $dateText }}</div>
      @endif
    </div>

    {{-- 中段：店舗名 + 星 --}}
    <div class="flex items-center justify-between gap-3">
      <div class="text-text text-[14px] font-medium truncate">
        {{ $shopName }}
      </div>

      <div class="flex items-center gap-[2px] shrink-0">
        @for($i=1; $i<=5; $i++)
          <x-icons.star
            :size="15"
            class="{{ $i <= $stars ? 'text-star' : 'text-line' }}"
          />
        @endfor
      </div>
    </div>

    {{-- 本文 --}}
    <div class="text-text text-[14px] leading-snug line-clamp-3">
      {{ $body }}
    </div>

  </div>
</a>
