@props(['store'])

@php
  $name = data_get($store, 'name', 'No Name');
  $imageUrl = data_get($store, 'image_url') ?: asset('images/store/card.png');
@endphp

<div
  x-data="{ open: false }"
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
                <div class="grind grid-cols-[40px_1fr_40px] items-center">
                <button
                    type="button"
                    class="mt-1 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5 z-10"
                    @click="open = false"
                    aria-label="閉じる"
                >
                    <x-icons.close class="w-6 h-6 text-text_color" />
                </button>

                <div class="flex justify-center">
                    <div class="flex items-center gap-11">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $name }}"
                        class="h-[84px] w-[84px] rounded-lg object-cover ring-1 ring-black/5"
                        loading="lazy"
                    >
                    <div class="min-w-0 text-left">
                        <div class="text-base text-text_color truncate max-w-[180px]">
                        {{ $name }}
                        </div>
                        <div class="mt-2 text-sm text-placeholder">
                        お気に入り追加済み
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-5 pb-5 max-h-[60vh] overflow-y-auto bg-base_color">
            <button
                type="button"
                class="text-sm text-main items-right underline-offset-4"
                >
                新しいコレクション
            </button>

            {{-- folders list --}}
            <div class="mt-3 text-text_color bg-base">コレクション</div>
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <img 
                        :src="folder.latest_store?.image_url ?? '{{ asset('images/store/card.png') }}' "
                        class="w-12 h-12 rounded-lg object-cover"
                    >
                    <div class="text-text_color" x-text="folder.name"></div>
                </div>
                
                <button
                    @click="toggleFolder(folder.id)"
                    class="grid h-8 w-8 place-items-center rounded-full border border-line"
                    >
                    <span x-text="selectedFolderIds.includes(folder.id) ? '✓' : '+'"></span>
                </button>
            </div>
        </div>

        {{-- safe area --}}
        <div class="pb-[env(safe-area-inset-bottom)]"></div>
      </div>
    </div>
  </template>
</div>
