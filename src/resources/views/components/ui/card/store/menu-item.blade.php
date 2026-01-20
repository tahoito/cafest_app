@props([
  'item' => null,
  'name' => '',
  'price' => '',
  'description' => '',
  'imageUrl' => '',
  'editUrl' => null,
])

@php
  use Illuminate\Support\Facades\Storage;

  $defaultCard = Storage::url('images/store/card.png');

  $name = (string) data_get($item, 'name', '');
  $price = data_get($item, 'price');
  $description = (string) data_get($item, 'description', '');

  $id = data_get($item, 'id');

  $photoPath =
    data_get($item, 'photo_path')
    ?? data_get($item, 'image')
    ?? null;

    $photoPath =
    data_get($item, 'photo_path')
    ?? data_get($item, 'image')
    ?? null;

    $imageUrl = $photoPath ? Storage::url($photoPath) : '';

  $resolvedEditUrl = $editUrl ?? ($id ? route('store.menu.recommended.edit', $id) : null);
@endphp

<div class="relative w-[353px] rounded-lg border border-main2 bg-form p-6">
  {{-- 編集：右上 --}}
  @if($resolvedEditUrl)
    <a
      href="{{ $resolvedEditUrl }}"
      class="absolute top-4 right-4 inline-flex items-center gap-1 text-sm text-text_color hover:opacity-80"
    >
      <x-icons.edit class="w-[15px] h-[15px] text-text_color" />編集
    </a>
  @endif

  <div class="flex gap-5">
    {{-- 左：画像（サイズ固定） --}}
    <div class="shrink-0">
      <div class="h-[135px] w-[135px] overflow-hidden rounded-xl bg-form ring-1 ring-black/5 flex flex-col items-center justify-center gap-1">
        @if ($imageUrl !== '')
          <img src="{{ $imageUrl }}" class="h-full w-full object-cover" loading="lazy" alt="" />
        @else
          <x-icons.no_image class="w-8 h-8 text-placeholder" />
          <span class="text-xs text-placeholder">画像がありません</span>
        @endif
      </div>
    </div>

    <div class="min-w-0 flex-1 pr-12">
      <div class="flex flex-col gap-3">
        <div class="space-y-0.5">
          <div class="text-main2 text-[11px]">メニュー名</div>
          @if($name !== '')
            <div class="text-base text-text_color line-clamp-2 break-words">
              {{ $name }}
            </div>
          @else
            <div class="text-base text-placeholder">—</div>
          @endif
        </div>

        <div class="space-y-0.5">
          <div class="text-main2 text-[11px]">価格</div>
          @if(!is_null($price) && $price !== '')
            <div class="text-base text-text_color">{{ number_format((int)$price) }}円</div>
          @else
            <div class="text-base text-placeholder">—</div>
          @endif
        </div>

        <div class="space-y-0.5">
          <div class="text-main2 text-[11px]">説明文</div>
          @if($description !== '')
            <div class="text-sm text-text_color leading-relaxed break-words">
              {{ $description }}
            </div>
          @else
            <div class="text-sm text-placeholder">—</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
