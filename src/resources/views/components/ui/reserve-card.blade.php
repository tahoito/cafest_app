@props([
  'reservation',          
  'href' => null,         
  'image' => null,        
  'shopName' => null,     
  'date' => null,         
  'time' => null,         
  'people' => null,       
  'onCancel' => null,     
])

@php
  $shopName = $shopName ?? (string) data_get($reservation, 'shop_name', data_get($reservation, 'shop.name', 'cafest'));
  $imageUrl = $image ?? (string) data_get($reservation, 'image_url', data_get($reservation, 'shop.image_url', ''));

  $dateText = $date ?? (string) data_get($reservation, 'date', data_get($reservation, 'reserved_date', ''));
  $timeText = $time ?? (string) data_get($reservation, 'time', data_get($reservation, 'reserved_time', ''));
  $peopleText = $people ?? (string) data_get($reservation, 'people', data_get($reservation, 'guest_count', ''));
  if ($peopleText !== '' && !str_contains($peopleText, '名')) $peopleText .= '名';

  $link = $href ?? (data_get($reservation, 'id') ? url("/reservations/" . data_get($reservation, 'id')) : '#');

  $base = "rounded-2xl bg-form ring-1 ring-black/5 shadow-[0_1px_4px_rgba(0,0,0,0.25)]";
@endphp


<div class="{{ $base }} overflow-hidden">
  @if($href)
    <a href="{{ $link }}" class="block">
  @endif

  <div class="p-4">
    <div class="flex gap-4">
      <div class="shrink-0">
        <div class="w-[132px] h-[110px] rounded-2xl overflow-hidden bg-base_color ring-1 ring-black/5">
          @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="" class="w-full h-full object-cover" />
          @else
            <div class="w-full h-full grid place-items-center text-placeholder text-xs">
              no image
            </div>
          @endif
        </div>
      </div>

      <div class="min-w-0 flex-1">
        <div class="flex items-start gap-2">
            <x-icons.store size="80" stroke="1" class="text-text_color mb-2" />
            <div class="min-w-0">
                <div class="text-text_color text-xl font-semibold leading-tight truncate">
                    {{ $shopName }}
                </div>
                <div class="text-main text-base mt-1">
                    予約情報
                </div>
            </div>
        </div>

        <div class="mt-3 space-y-2 text-text_color">
            <div class="flex items-center gap-2">
                <x-icons.date class="w-5 h-5 text-text_color shrink-0" />
                <div class="text-base">{{ $dateText }}</div>
            </div>

            <div class="flex items-center gap-2">
                <x-icons.time class="w-5 h-5 text-text_color shrink-0" />
                <div class="text-base">{{ $timeText }}</div>
            </div>

            <div class="flex items-center gap-2">
                <x-icons.number class="w-5 h-5 text-text_color shrink-0" />
                <div class="text-base">{{ $peopleText }}</div>
            </div>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <x-ui.button
        as="button"
        type="button"
        variants="secondary"
        class="w-full !rounded-full !py-3"
        @if($onCancel) @click="{{ $onCancel }}" @endif
      >
        キャンセルする
      </x-ui.button>
    </div>
  </div>

  @if($href)
    </a>
  @endif
</div>
