@props([
  'review',
  'href' => null,
  'variant' => 'compact', // compact | default
])

@php
  $shop     = data_get($review, 'shop', data_get($review, 'store', null));
  $shopId   = data_get($shop, 'id', data_get($review, 'shop_id', null));
  $shopName = (string) data_get($shop, 'name', data_get($review, 'shop_name', ''));
  $shopName = $shopName !== '' ? $shopName : '店舗名未設定';

  $rating = (float) data_get($review, 'rating', 0);
  $stars  = (int) max(0, min(5, (int) round($rating))); // 0〜5に丸める

  $body = (string) data_get($review, 'body', data_get($review, 'comment', ''));
  $body = trim($body);

  $dateRaw = data_get($review, 'created_at', data_get($review, 'date', null));
  try {
    $dateText = $dateRaw ? \Carbon\Carbon::parse($dateRaw)->format('Y/m/d') : null;
  } catch (\Exception $e) {
    $dateText = null;
  }

  $link = $href ?? ($shopId ? url("/stores/{$shopId}") : '#');

  $base = "block rounded-xl bg-form ring-1 ring-black/5 shadow-[0_1px_4px_rgba(0,0,0,0.25)]";
  $wrap = $variant === 'compact'
    ? "p-4 space-y-2"
    : "p-5 space-y-3";

  $iconSize = $variant === 'compact' ? "w-6 h-6" : "w-7 h-7";
  $titleCls = $variant === 'compact'
    ? "text-text_color text-[14px] font-medium truncate"
    : "text-text_color text-[16px] font-semibold truncate";

  $bodyCls = $variant === 'compact'
    ? "text-placeholder text-[12px] leading-relaxed line-clamp-2"
    : "text-placeholder text-[13px] leading-relaxed line-clamp-3";

  $dateCls = "text-placeholder text-[11px]";
@endphp

<a href="{{ $link }}" class="{{ $base }}" {{ $attributes }}>
  <div class="{{ $wrap }}">

    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-2 min-w-0">
        <x-icons.store class="{{ $iconSize }} text-text_color" />
        <div class="{{ $titleCls }}">
          {{ $shopName }}
        </div>
      </div>

      @if($dateText)
        <div class="{{ $dateCls }} shrink-0">
          {{ $dateText }}
        </div>
      @endif
    </div>

    <div class="flex items-center gap-[2px]">
      @for($i=1; $i<=5; $i++)
        <x-icons.star
          :size="$variant === 'compact' ? 14 : 15"
          class="{{ $i <= $stars ? 'text-star' : 'text-line' }}"
        />
      @endfor

      <span class="ml-2 text-placeholder text-[12px]">
        {{ number_format($rating, 1) }}
      </span>
    </div>

    @if($body !== '')
      <div class="{{ $bodyCls }}">
        {{ $body }}
      </div>
    @endif

  </div>
</a>
