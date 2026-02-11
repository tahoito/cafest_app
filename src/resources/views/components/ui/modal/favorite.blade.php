@props(['store'])
@php
  $storeId = (int) data_get($store, 'id');
  $name    = data_get($store, 'name', 'No Name');

  $defaultCardPath = 'images/stores/card.png';
  $defaultCardUrl  = asset($defaultCardPath);

  $imageUrl =
    \App\Support\MediaUrl::from(
      optional(collect(data_get($store, 'slideImages', []))
        ->firstWhere('is_used_on_card', true))->url
      ?? optional(collect(data_get($store, 'slideImages', []))->first())->url
    ) ?? $defaultCardUrl;
@endphp



<div x-data x-cloak {{ $attributes }}>
  <template x-teleport="body">

    {{-- =========================
        WRAPPER (LIST / CREATE)
    ========================== --}}
    <div
      x-show="
        $store.favModal.openStoreId === {{ $storeId }} ||
        $store.favModal.createStoreId === {{ $storeId }}
      "
      class="fixed inset-0 z-[9000]"
      style="display:none;"
      @keydown.escape.window="
        if ($store.favModal.createStoreId === {{ $storeId }}) $store.favModal.closeCreate();
        else if ($store.favModal.openStoreId === {{ $storeId }}) $store.favModal.closeList();
      "
    >

      {{-- =========================================
          LIST backdrop（LISTの時だけ暗くする）
      ========================================== --}}
      <div
        x-show="$store.favModal.openStoreId === {{ $storeId }}"
        class="absolute inset-0 bg-black/50"
        @click.self="$store.favModal.closeList()"
      ></div>

      {{-- =========================================
          CREATE backdrop（CREATEの時だけ暗くする）
          ※ ここで「新しいコレクション」の背景を暗くできる
      ========================================== --}}
      <div
        x-show="$store.favModal.createStoreId === {{ $storeId }}"
        class="fixed inset-0 z-[9099] bg-black/40 backdrop-blur-[1px]"
        @click.self="$store.favModal.closeCreate()"
      ></div>

      {{-- =========================
          LIST: bottom sheet
      ========================== --}}
      <div
        x-show="$store.favModal.openStoreId === {{ $storeId }}"
        :class="$store.favModal.createStoreId === {{ $storeId }} ? 'pointer-events-none' : ''"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-6 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-6 opacity-0"
        class="fixed inset-x-0 bottom-0 z-[9001] w-full max-w-[400px] mx-auto rounded-t-3xl overflow-hidden shadow-xl"
        @click.stop
      >
        {{-- header --}}
        <div class="bg-form">
          <div class="pt-1">
            <div class="mx-auto h-1.5 w-12 rounded-full bg-line"></div>
          </div>

          <div class="relative px-5 pb-4 pt-3">
            <div class="grid grid-cols-[40px_1fr_40px] items-center">
              <button
                type="button"
                class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                @click="$store.favModal.closeList()"
                aria-label="閉じる"
              >
                <x-icons.close class="w-7 h-7 text-text_color" />
              </button>

              <div class="flex justify-center">
                <div class="flex items-center gap-4">
                  <img
                    src="{{ $store->card_image_url }}"
                    alt="{{ $name }}"
                    class="h-[64px] w-[64px] rounded-lg object-cover shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
                    loading="lazy"
                  >
                  <div class="min-w-0 text-left">
                    <div class="text-base text-text_color truncate max-w-[180px]">
                      {{ $name }}
                    </div>
                    <div class="mt-1 text-sm text-text_color">
                      お気に入り追加済み
                    </div>
                  </div>
                </div>
              </div>

              <div></div>
            </div>
          </div>
        </div>

        {{-- folders list --}}
        <div
          class="bg-base_color px-5 pb-5 max-h-[60vh] overflow-y-auto"
          x-data="favoriteFoldersUI({{ $storeId }}, @js($defaultCardUrl))"
          x-init="initWatch()">
          <div class="mt-3 flex items-center justify-between">
            <div class="text-text_color">コレクション</div>

            <button
              type="button"
              class="relative z-[9999] inline-flex items-center gap-1 rounded-full border border-line bg-form px-3 py-2 text-sm text-text_color shadow-sm"
              @click.stop.prevent="
                $store.favModal.openCreate({{ $storeId }});
              "
            >
              <span class="text-base leading-none">＋</span>
              追加
            </button>
          </div>

          <div
            x-show="folders && folders.length === 0"
            class="mt-3 rounded-2xl border border-line bg-form px-4 py-6 text-center text-sm text-placeholder"
          >
            コレクションがありません。
          </div>

          <template x-for="folder in folders" :key="folder.id">
            <div class="flex items-center justify-between py-3">
              <div class="flex items-center gap-3">
                <img
                  :src="toPublicUrl(folder.latest_store?.image_url) || defaultThumb"
                  class="w-[85px] h-[85px] rounded-lg object-cover"
                  alt=""
                >
                <div class="text-text_color" x-text="folder.name"></div>
              </div>

              <button
                type="button"
                @click="toggleFolder(folder.id)"
                class="grid place-items-center"
                :aria-label="selectedFolderIds.includes(folder.id) ? '解除' : '追加'"
              >
                <x-icons.add size="30" class="w-[30px] h-[30px] text-text_color"
                  x-show="!selectedFolderIds.includes(folder.id)" />

                <x-icons.check size="30" class="w-[30px] h-[30px] text-text_color"
                  x-show="selectedFolderIds.includes(folder.id)" />
              </button>
            </div>
          </template>

          <div class="pb-[calc(env(safe-area-inset-bottom)+12px)]"></div>
        </div>
      </div>

      {{-- =========================
          CREATE: center modal
      ========================== --}}
      <div
        x-show="$store.favModal.createStoreId === {{ $storeId }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-2 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-2 opacity-0 scale-95"
        class="fixed left-1/2 top-1/2 z-[9101] w-[353px] max-w-[92vw] -translate-x-1/2 -translate-y-1/2 rounded-lg bg-base_color px-5 py-5 shadow-[0_20px_60px_rgba(0,0,0,0.25)]"
        x-data="favoriteFolderCreateUI({{ $storeId }})"
        @click.stop
      >
        <div class="flex items-start justify-between">
          <button
            type="button"
            class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
            @click="$store.favModal.closeCreate()"
            aria-label="閉じる"
          >
            <x-icons.close class="w-7 h-7 text-text_color" />
          </button>

          <div class="pt-1 text-text_color text-lg">新しいコレクション</div>

          <div class="w-9"></div>
        </div>

        <div class="mt-4 flex justify-center">
          <img
            src="{{ $imageUrl }}"
            class="h-[200px] w-[200px] rounded-lg object-cover shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
            alt=""
          >
        </div>

        <div class="mt-[30px]">
          <x-ui.input
            type="text"
            x-model="name"
            placeholder="放課後行きたいカフェ"
          />
        </div>

        <x-ui.button
          type="button"
          class="w-full mt-[30px]"
          x-bind:disabled="name.trim().length === 0 || saving"
          @click.prevent.stop="save()"
        >
          <span x-show="!saving">保存</span>
          <span x-show="saving">保存中…</span>
        </x-ui.button>

        <div x-show="error" class="mt-3 text-sm text-notification" x-text="error"></div>
      </div>

    </div>
  </template>
</div>
