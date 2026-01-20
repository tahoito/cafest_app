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

  $name = (string) data_get($item, 'name', '');
  $price = data_get($item, 'price');
  $description = (string) data_get($item, 'description', '');

  $id = data_get($item, 'id');

  $photoPath =
    data_get($item, 'photo_path')
    ?? data_get($item, 'image')
    ?? null;

  $imageUrl = $photoPath ? Storage::url($photoPath) : '';

  $resolvedEditUrl = $editUrl ?? ($id ? route('store.menu.recommended.edit', $id) : null);
@endphp

<div class="w-[353px] rounded-lg border border-main2 bg-form">
  {{-- 編集だけ：paddingなしで右端 --}}
  <div class="flex justify-end px-6 pt-4">
    @if($resolvedEditUrl)
      <a
        href="{{ $resolvedEditUrl }}"
        class="inline-flex items-center gap-1 text-sm text-text_color hover:opacity-80"
      >
        <x-icons.edit class="w-[15px] h-[15px] text-text_color" />編集
      </a>
    @endif
  </div>

  {{-- 中身：ここで padding --}}
  <div class="px-6 pb-6">
    <div class="grid grid-cols-[135px_1fr] gap-x-5">
      {{-- 左：画像 --}}
      <div>
        <div class="h-[135px] w-[135px] overflow-hidden rounded-xl bg-form ring-1 ring-black/5 flex items-center justify-center">
          @if ($imageUrl)
            <img src="{{ $imageUrl }}" class="h-full w-full object-cover" alt="" />
          @else
            <x-icons.no_image class="w-8 h-8 text-placeholder" />
          @endif
        </div>
      </div>

      {{-- 右：テキスト --}}
      <div class="min-w-0 space-y-3">
        <div>
          <div class="text-main2 text-[11px]">名前</div>
          <div class="text-base text-text_color break-words">{{ $name ?: '—' }}</div>
        </div>

        <div>
          <div class="text-main2 text-[11px]">価格</div>
          <div class="text-base text-text_color">
            {{ !is_null($price) ? number_format((int)$price).'円-' : '—' }}
          </div>
        </div>

        <div>
          <div class="text-main2 text-[11px]">説明文</div>
          <div class="text-sm text-text_color leading-relaxed break-words">
            {{ $description ?: '—' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
