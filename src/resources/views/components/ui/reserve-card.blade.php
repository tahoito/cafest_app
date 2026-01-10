@props([
 'reservation', 'href' => null, 'image' => null, 'shopName' => null, 'date' => null, 'time' => null, 'people' => null, 'onCancel' => null, ]) 

@php $shopName = $shopName ?? (string) data_get($reservation, 'shop_name', data_get($reservation, 'shop.name', 'cafest')); $imageUrl = $image ?? (string) data_get($reservation, 'image_url', data_get($reservation, 'shop.image_url', '')); $dateText = $date ?? (string) data_get($reservation, 'date', data_get($reservation, 'reserved_date', '')); $timeText = $time ?? (string) data_get($reservation, 'time', data_get($reservation, 'reserved_time', '')); $peopleText = $people ?? (string) data_get($reservation, 'people', data_get($reservation, 'guest_count', '')); if ($peopleText !== '' && !str_contains($peopleText, '名')) $peopleText .= '名'; $link = $href ?? (data_get($reservation, 'id') ? url("/reservations/" . data_get($reservation, 'id')) : '#'); $base = "rounded-2xl bg-form ring-1 ring-black/5 shadow-[0_1px_4px_rgba(0,0,0,0.25)]"; 
@endphp

<div class="w-[344px] rounded-xl border border-line bg-form p-4 shadow-sm">
<div class="flex gap-4">
{{-- left image --}}
<div class="shrink-0">
<div class="h-[130px] w-[130px] overflow-hidden rounded-xl bg-base ring-1 ring-black/5">
<img src="{{ $imageUrl ?: asset('images/store/card.png') }}" alt="" class="h-full w-full object-cover" loading="lazy" />
</div>
</div>

{{-- right content --}}
<div class="min-w-0 flex-1">
<div class="flex items-center gap-2">
<x-icons.store class="h-5 w-5 text-text_color" />
<div class="truncate text-base text-text">
{{ $shopName }}
</div>
</div>

<div class="mt-2 text-xs text-main/70">予約情報</div>

<div class="mt-2 grid grid-cols-[28px_1fr] gap-x-2 gap-y-2 text-xs text-text">
<x-icons.date class="h-6 w-6 text-text opacity-70" />
<span class="truncate">{{ $dateText }}</span>

<x-icons.time class="h-6 w-6 text-text opacity-70" />
<span class="truncate underline underline-offset-4">{{ $timeText }}</span>

<x-icons.number class="h-6 w-6 text-text opacity-70" />
<span class="truncate">{{ $peopleText }}</span>
</div>
</div>
</div>

<div class="mt-4 flex justify-center">
<form method="POST" action="{{ $onCancel }}">
@csrf
@method('DELETE')

<div class="flex justify-center">
            <x-ui.button type="submit" class="w-full bg-form text-text_color border border-main">
                キャンセルする
            </x-ui.button>
            </div>
</form>
</div>
</div>
