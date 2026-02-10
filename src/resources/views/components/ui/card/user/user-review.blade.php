@props([
  'review',
  'href' => null,
  'variant' => 'compact', // mini | compact
])

@php
  $userName = (string) data_get($review, 'user.name', data_get($review, 'username', ''));
  $userHandle = (string) data_get($review, 'user.handle', '');

  // user icon (storage / absolute url 対応)
  $userIconPath = data_get($review,'user.icon_path', data_get($review,'icon_path', null));
  $userIconUrl = null;

  if ($userIconPath) {
    if (is_string($userIconPath) && str_starts_with($userIconPath, ['http://', 'https://'])) {
      $userIconUrl = $userIconPath;
    } elseif (is_string($userIconPath) && str_starts_with($userIconPath, ['/storage/', 'storage/'])) {
      $userIconUrl = asset(ltrim($userIconPath, '/'));
    } else {
      $path = preg_replace('#^storage/#', '', ltrim((string) $userIconPath, '/'));
      $userIconUrl = \Illuminate\Support\Facades\Storage::url($path);
    }
  }
  
  // store
  $store     = data_get($review, 'store', data_get($review, 'shop', null));
  $storeId   = data_get($store, 'id', data_get($review, 'store_id', data_get($review, 'shop_id', null)));
  $storeName = (string) data_get($store, 'name', data_get($review, 'store_name', data_get($review, 'shop_name', '')));

  // rating/body/date
  $rating = (float) data_get($review, 'rating', 0);
  $body   = (string) data_get($review, 'body', data_get($review, 'body', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date', null));
  $dateText = '';
  if ($date) {
    try {
      $dateText = is_string($date) ? $date : $date->format('Y/m/d');
    } catch (\Throwable $e) {
      $dateText = (string) $date;
    }
  }

  $stars = max(0, min(5, (int) floor($rating + 0.00001)));

  $link = $href;
  $avatarSize = match ($variant) {
    'mini'  => "w-9 h-9",
    default => "w-11 h-11",
  };

  // カード全体クラス
  $cardClass = 'block w-full rounded-xl bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)] text-left';
@endphp

@if($link)
  <a href="{{ $link }}" {{ $attributes->merge(['class' => $cardClass]) }}>
@else
  <div {{ $attributes->merge(['class' => $cardClass]) }}>
@endif

  <div class="h-full px-5 py-4 flex flex-col">
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <div class="{{ $avatarSize }} rounded-full bg-base overflow-hidden shrink-0">
          @if($userIconUrl)
            <img src="{{ $userIconUrl }}" class="w-full h-full object-cover" alt="">
          @endif
        </div>

        <div class="min-w-0">
          <div class="text-text_color text-base font-semibold truncate">
            {{ $userName }}
          </div>
          @if($userHandle)
            <div class="text-placeholder text-xs truncate">
              {{ '@' . $userHandle }}
            </div>
          @endif
        </div>
      </div>

      @if($dateText !== '')
        <div class="text-placeholder text-xs shrink-0 pt-1">
          {{ $dateText }}
        </div>
      @endif
    </div>

    <div class="mt-3 flex items-center justify-between gap-3">
      <div class="text-text_color text-base font-medium truncate">
        {{ $storeName }}
      </div>

      <div class="flex items-center gap-[2px] shrink-0">
        @for ($i = 1; $i <= 5; $i++)
          <x-icons.star class="h-3 w-3 {{ $i <= $stars ? 'text-star' : 'text-placeholder' }}" />
        @endfor
      </div>
    </div>

    <div class="mt-2 text-text_color text-[15px] leading-snug line-clamp-3">
      {{ $body }}
    </div>
  </div>

@if($link)
  </a>
@else
  </div>
@endif
