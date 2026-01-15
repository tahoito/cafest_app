@props(['store'])

@php
  $storeId  = (int) data_get($store, 'id');
  $name     = data_get($store, 'name', 'No Name');
  $imageUrl = data_get($store, 'image_url') ?: asset('images/store/card.png');
@endphp

<div x-data="{}" {{ $attributes }}>
    {{-- overlay --}}
    <div
        x-show="$store.favModal.openStoreId === {{ $storeId }}"
        x-transition.opacity
        class="fixed inset-0 z-[9000] bg-black/40"
        @click="$store.favModal.closeList()"
    ></div>

    {{-- bottom sheet --}}
    <div
        x-show="$store.favModal.openStoreId === {{ $storeId }}"
        x-transition
        class="fixed inset-x-0 bottom-0 z-[9001] rounded-t-3xl overflow-hidden bg-form shadow-[0_-10px_30px_rgba(0,0,0,0.18)]"
        @click.stop
    >
        {{-- header block --}}
        <div class="bg-form">
        <div class="pt-1">
            <div class="mx-auto h-1.5 w-12 rounded-full bg-line"></div>
        </div>

        <div class="relative px-5 pb-4">
            <div class="grid grid-cols-[40px_1fr_40px] items-center">
            <button
                type="button"
                class="mt-1 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5 z-10"
                @click="$store.favModal.closeList()"
                aria-label="閉じる"
            >
                <x-icons.close class="w-6 h-6 text-text_color" />
            </button>

            <div class="flex justify-center">
                <div class="flex items-center gap-12">
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $name }}"
                    class="h-[84px] w-[84px] rounded-lg object-cover shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
                    loading="lazy"
                >
                <div class="min-w-0 text-left">
                    <div class="text-base text-text_color truncate max-w-[180px]">
                    {{ $name }}
                    </div>
                    <div class="mt-2 text-sm text-text_color">
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
      class="px-5 pb-5 max-h-[60vh] overflow-y-auto bg-base_color"
      x-data="favoriteFoldersUI({{ $storeId }}, @js(asset('images/store/card.png')))"
      x-init="init()"
    >
        <div class="mt-3 flex items-center justify-between">
            <div class="text-text_color">コレクション</div>

            <button
            type="button"
            @mousedown.prevent.stop
            @touchstart.prevent.stop
            @click.prevent.stop="$store.favModal.openCreate({{ $storeId }})"
            class="relative z-10 inline-flex items-center gap-1 rounded-full border border-line bg-form px-3 py-2 text-sm text-text_color shadow-sm"
            @click.stop
            >
                <span class="text-base leading-none">＋</span>
                新規作成
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
              :src="folder.latest_store?.image_url ?? defaultThumb"
              class="w-12 h-12 rounded-lg object-cover"
              alt=""
            >
            <div class="text-text_color" x-text="folder.name"></div>
          </div>

          <button
            type="button"
            @click="toggleFolder(folder.id)"
            class="grid h-8 w-8 place-items-center rounded-full border border-line"
            :aria-label="selectedFolderIds.includes(folder.id) ? '解除' : '追加'"
          >
            <span x-text="selectedFolderIds.includes(folder.id) ? '✓' : '+'"></span>
          </button>
        </div>
      </template>
    </div>

    <div class="pb-[env(safe-area-inset-bottom)]"></div>
  </div>

  {{-- =========================
      CREATE: create folder modal (overlay + center modal)
  ========================== --}}

  {{-- overlay --}}
  <div
    x-show="$store.favModal.createStoreId === {{ $storeId }}"
    x-transition.opacity
    class="fixed inset-0 z-[9100] bg-black/40"
    @click="$store.favModal.closeCreate()"
  ></div>

  {{-- modal box --}}
  <div
    x-show="$store.favModal.createStoreId === {{ $storeId }}"
    x-transition
    class="fixed left-1/2 top-1/2 z-[9101] w-[353px] h-[550px] -translate-x-1/2 -translate-y-1/2 rounded-lg bg-base_color px-5 py-5 shadow-[0_20px_60px_rgba(0,0,0,0.25)]"
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
        <x-icons.close class="w-6 h-6 text-text_color" />
      </button>

      <div class="pt-1 text-text_color">新しいコレクション</div>

      <div class="w-9"></div>
    </div>

    <div class="mt-4 flex justify-center">
      <img
        src="{{ $imageUrl }}"
        class="h-[200px] w-[200px] rounded-lg object-cover shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
        alt=""
      >
    </div>

    <x-ui.input
      type="text"
      x-model="name"
      placeholder="放課後行きたいカフェ"
    />

    <x-ui.button
      class="w-full mt-4"
      x-bind:disabled="name.trim().length === 0 || saving"
      @click="save()"
    >
      <span x-show="!saving">保存</span>
      <span x-show="saving">保存中…</span>
    </x-ui.button>

    <div x-show="error" class="mt-3 text-sm text-notification" x-text="error"></div>
  </div>
</div>
