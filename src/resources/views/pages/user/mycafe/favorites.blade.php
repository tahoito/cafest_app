@php
  $folder = request('folder'); 
@endphp

@if ($folder)
  <div class="px-4">

    <div class="mb-3">
      <a href="{{ route('user.mycafe.favorites.folder', ['tab' => 'favorites']) }}"
         class="text-sm text-text_color underline">
        戻る
      </a>
    </div>

    @if (($favoriteStores ?? collect())->isEmpty())
      <div class="text-placeholder text-sm">
        このフォルダにはまだありません
      </div>
    @else
      <div class="grid grid-cols-2 gap-3">
        @foreach ($favoriteStores as $store)
          <x-ui.card.store
            :store="$store"
            :faved="in_array(data_get($store,'id'), $favIds)"
            :href="route('user.stores.show', ['store' => data_get($store,'id')])"
            variant="grid"
          />
        @endforeach
      </div>
    @endif
  </div>

@else
  <div class="px-4">
    @if (($favoritesAll ?? collect())->isEmpty() && ($foldersPayload ?? collect())->isEmpty())
      <div class="text-placeholder text-sm">
        お気に入りはまだありません
      </div>
    @else
      <div class="grid grid-cols-2 gap-3">

        {{-- すべて --}}
        <a href="{{ route('user.mycafe.favorites.folder', ['folder' => 'all']) }}"
           class="rounded-2xl">
          <div class="aspect-square rounded-xl overflow-hidden bg-placeholder">
            <div class="grid grid-cols-2 grid-rows-2 h-full w-full">
              @for ($i = 0; $i < 4; $i++)
                <div class="bg-placeholder shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
                  @if (isset($allThumbs[$i]))
                    <img src="{{ $allThumbs[$i] }}" class="h-full w-full object-cover" />
                  @endif
                </div>
              @endfor
            </div>
          </div>
          <div class="mt-2 text-base text-text_color">すべての投稿</div>
        </a>

        {{-- フォルダ --}}
        @foreach ($foldersPayload as $f)
          <a href="{{ route('user.mycafe.favorites.folder', ['folder' => $f['id']]) }}"
             class="rounded-2xl">
            <div class="aspect-square rounded-lg overflow-hidden bg-placeholder shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
              @if (!empty($f['thumb_url']))
                <img src="{{ $f['thumb_url'] }}" class="h-full w-full object-cover" />
              @endif
            </div>
            <div class="mt-2 text-base text-text_color">{{ $f['name'] }}</div>
          </a>
        @endforeach

      </div>
    @endif
  </div>
@endif
