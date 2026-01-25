@php
  $folder = request()->route('folder'); // 選択中フォルダID（なければ一覧）
  $storesCol = $stores ?? collect();
  $favoritesAllCol = $favoritesAll ?? collect();
  $foldersCol = $foldersPayload ?? collect();
@endphp

<div class="px-4">

  @if ($folder)

    <div class="mb-3">
      <a
        href="{{ route('user.mycafe', ['tab' => 'favorites']) }}"
        class="text-sm text-text_color underline"
      >
        戻る
      </a>
    </div>

    @if ($storesCol->isEmpty())
      <div class="text-placeholder text-sm">
        このフォルダにはまだありません
      </div>
    @else
      <div class="grid grid-cols-2 gap-3">
        @foreach ($storesCol as $store)
          <x-ui.card.store
            :store="$store"
            :faved="in_array((int) data_get($store,'id'), $favIds ?? [])"
            :href="route('user.stores.show', ['store' => data_get($store,'id')])"
            variant="grid"
          />
        @endforeach
      </div>
    @endif


  {{-- ===== フォルダ一覧（お気に入り一覧） ===== --}}
  @else

    @if ($favoritesAllCol->isEmpty() && $foldersCol->isEmpty())
      <div class="text-placeholder text-sm">
        お気に入りはまだありません
      </div>
    @else
      <div class="grid grid-cols-2 gap-3">
        @foreach ($foldersCol as $f)
          <a
            href="{{ route('user.mycafe.favorites.folder', ['folder' => $f['id']]) }}"
            class="block"
          >
            <div class="aspect-square rounded-lg overflow-hidden bg-placeholder shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
              @if (!empty($f['thumb_url']))
                <img
                  src="{{ $f['thumb_url'] }}"
                  alt=""
                  class="h-full w-full object-cover"
                  loading="lazy"
                />
              @endif
            </div>
            <div class="mt-2 text-base text-text_color line-clamp-1">
              {{ $f['name'] }}
            </div>
          </a>
        @endforeach
      </div>
    @endif

  @endif
</div>
