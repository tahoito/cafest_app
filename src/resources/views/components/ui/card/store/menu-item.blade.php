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
  $imageUrl = (string) (
    data_get($item, 'image_url')
    ?? data_get($item, 'image')
    ?? ''
  );
@endphp

<div class="relative w-[353px] h-[179px] rounded-lg border border-main2 bg-form p-6">
    {{-- 編集：右上固定 --}}
    <a
      href="{{ $editUrl ?? '#' }}"
      class="absolute top-4 right-4 inline-flex items-center gap-1 text-sm text-text_color hover:opacity-80"
    >
      <x-icons.edit class="w-[15px] h-[15px] text-text_color" />編集
    </a>

    <div class="flex gap-5">
        <div class="shrink-0">
            <div class="h-[135px] w-[135px] overflow-hidden rounded-xl bg-form ring-1 ring-black/5 flex flex-col items-center justify-center gap-1">
                @if ($imageUrl !== '')
                    <img
                        src="{{ $imageUrl }}"
                        class="h-full w-full object-cover"
                        loading="lazy"
                        alt=""
                    />
                @else
                    <x-icons.no_image class="w-8 h-8 text-placeholder" />
                    <span class="text-xs text-placeholder">写真がありません</span>
                @endif
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <div class="space-y-3">
                <div class="space-y-1">
                    <div class="text-main2 text-sm">名前</div>
                    <div class="text-base text-text_color truncate">
                        {{ $name }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="text-main2 text-sm">価格</div>
                    @if(!is_null($price))
                        <div class="text-base text-text_color">
                            {{ number_format((int)$price) }}円
                        </div>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="text-main2 text-sm">説明文</div>
                    @if($description !== '')
                        <div class="text-base text-text_color leading-relaxed line-clamp-2">
                            {{ $description }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
