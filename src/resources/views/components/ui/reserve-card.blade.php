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

