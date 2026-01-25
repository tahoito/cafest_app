@php
  $folder = request()->route('folder');
  $storesCol = $stores ?? collect();
  $favoritesAllCol = $favoritesAll ?? collect();
  $foldersCol = $foldersPayload ?? collect();

  $firstFolder = $foldersCol->first();

  $hasOnlyDefaultFolder =
    $foldersCol->count() === 1 &&
    data_get($firstFolder, 'name') === 'お気に入り' &&
    empty(data_get($firstFolder, 'thumb_urls', []));
@endphp

<div class="px-4">

  {{-- フォルダ詳細 --}}
  @if ($folder && $folder !== 'all')
    <div class="mb-3">
      <a href="{{ route('user.mycafe', ['tab' => 'favorites']) }}" class="text-sm text-text_color underline">
        戻る
      </a>
    </div>

    @if ($storesCol->isEmpty())
      <div class="text-placeholder text-center text-sm">
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

  {{-- フォルダ一覧 --}}
  @else

    @if ($favoritesAllCol->isEmpty() && $foldersCol->isEmpty())
      <div class="flex justify-center">
        <span class="text-sm text-placeholder">お気に入りはまだありません</span>
      </div>

    @elseif ($hasOnlyDefaultFolder)
      <div class="flex justify-center">
        <span class="text-sm text-placeholder">お気に入りはまだありません</span>
      </div>

    @else
      <div class="grid grid-cols-2 gap-3">
        @foreach ($foldersCol as $f)
          @php
            $isDefault = (data_get($f, 'name') === 'お気に入り');

            $thumbs = collect(data_get($f, 'thumb_urls', []))
              ->filter()
              ->take(4)
              ->values();

            $latestThumb = $thumbs->first();
          @endphp

          <a href="{{ route('user.mycafe.favorites.folder', ['folder' => $f['id']]) }}" class="block">
            <div class="aspect-square rounded-lg overflow-hidden bg-placeholder shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
              @if ($isDefault)
                {{-- お気に入りだけ4分割 --}}
                @if ($thumbs->isNotEmpty())
                  <div class="grid h-full w-full grid-cols-2 grid-rows-2 gap-[2px] bg-placeholder">
                    @foreach ($thumbs as $src)
                      <img src="{{ $src }}" alt="" class="h-full w-full object-cover" loading="lazy" />
                    @endforeach

                    @for ($i = $thumbs->count(); $i < 4; $i++)
                      <div class="h-full w-full bg-placeholder"></div>
                    @endfor
                  </div>
                @endif
              @else
                {{-- 他は最新1枚 --}}
                @if ($latestThumb)
                  <img src="{{ $latestThumb }}" alt="" class="h-full w-full object-cover" loading="lazy" />
                @endif
              @endif
            </div>

            <div class="mt-2 text-base text-text_color line-clamp-1">
              {{ data_get($f, 'name') }}
            </div>
          </a>
        @endforeach
      </div>
    @endif

  @endif
</div>
