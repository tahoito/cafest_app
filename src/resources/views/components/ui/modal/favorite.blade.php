@props(['store'])

@php
  $name = data_get($store, 'name', 'No Name');
  $imageUrl = data_get($store, 'image_url') ?: asset('images/store/card.png');
@endphp

<div
  x-data="{ open: false, createOpen:false }"
  x-modelable="open"
  {{ $attributes->whereStartsWith('x-model') }}
  x-cloak
>
  <template x-teleport="body">

    {{-- overlay --}}
    <div
      x-show="open"
      x-transition.opacity
      class="fixed inset-0 z-[200] bg-black/40"
      @click.self="open = false"
    >
      {{-- sheet --}}
      <div
        x-show="open"
        x-transition
        class="fixed inset-x-0 bottom-0 z-[201] rounded-t-3xl overflow-hidden bg-form shadow-[0_-10px_30px_rgba(0,0,0,0.18)]"
      >
        <div class="bg-form">
          {{-- handle --}}
          <div class="pt-1">
            <div class="mx-auto h-1.5 w-12 rounded-full bg-line"></div>
          </div>

          <div class="relative px-5 pb-4">
            <div class="grid grid-cols-[40px_1fr_40px] items-center">
                <button
                    type="button"
                    class="mt-1 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5 z-10"
                    @click="open = false"
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

        {{-- body --}}
        <div
          class="px-5 pb-5 max-h-[60vh] overflow-y-auto bg-base_color"
          x-data="favoriteFoldersUI({{ (int) data_get($store,'id') }}, @js(asset('images/store/card.png')))"
          x-init="init()"
        >
          <div class="flex items-center justify-end pt-3">
            <button
                type="button"
                class="text-sm text-main underline-offset-4"
                @click.stop="createOpen = true; open = false"
                >
                新しいコレクション
            </button>
          </div>

          <div class="mt-3 text-text_color">コレクション</div>

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
                >
                <div class="text-text_color" x-text="folder.name"></div>
              </div>

              <button
                type="button"
                @click="toggleFolder(folder.id)"
                class="grid h-8 w-8 place-items-center rounded-full border border-line"
              >
                <span x-text="selectedFolderIds.includes(folder.id) ? '✓' : '+'"></span>
              </button>
            </div>
          </template>
        </div> 

        {{-- safe area --}}
        <div class="pb-[env(safe-area-inset-bottom)]"></div>
      </div>
    </div>

    {{-- create modal --}}
    <div
      x-show="createOpen"
      x-transition.opacity
      class="fixed inset-0 z-[220] bg-black/40"
      @click.self="createOpen = false; open=true"
    >
      <div
        x-show="createOpen"
        x-transition
        class="fixed left-1/2 top-1/2 z-[221] w-[353px] h-[550px] -translate-x-1/2 -translate-y-1/2
          bg-base px-5 py-5 shadow-[0_20px_60px_rgba(0,0,0,0.25)]"
        x-data="favoriteFolderCreateUI({{ (int) data_get($store,'id') }})"
      >
        <div class="flex items-start justify-between">
          <button
            type="button"
            class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
            @click="createOpen=false; open=true"
            aria-label="閉じる"
          >
            <x-icons.close class="w-6 h-6 text-text_color" /> 
          </button>

          <div class="text-text_color pt-1">新しいコレクション</div>
          <div class="w-9"></div>
        </div>

        <div class="mt-4 flex justify-center">
          <img
            src="{{ $imageUrl }}"
            class="h-[200px] w-[200px] rounded-lg object-cover shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
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

  </template>
</div>
