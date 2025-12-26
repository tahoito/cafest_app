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

  $base = "block rounded-lg bg-form shadow-sm overflow-hidden";
  $wrap = match ($variant) {
      'grid' => "p-3",
      'compact' => "p-2",
      default => "p-4",
  };

  $avatarSize = match ($variant) {
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

<a href="{{ $link }}" {{ $attributes->merge(['class' => $base]) }}>
  <div class="{{ $wrap }} space-y-2">

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

    {{-- 星 --}}
    <div class="flex items-center gap-1">
      @for($i=1; $i<=5; $i++)
        <x-icons.star class="w-4 h-4 {{ $i <= $stars ? 'text-star' : 'text-line' }}" />
      @endfor
    </div>

    {{-- 本文 --}}
    <div class="text-text text-[14px] leading-snug line-clamp-3">
      {{ $body }}
    </div>

  </div>
</a>
