@props(['store'])

<div
  x-data="{ open: false }"
  x-modelable="open"
  {{ $attributes->whereStartsWith('x-model') }}
>
  <template x-teleport="body">
    <div
      x-show="open"
      x-transition.opacity
      class="fixed inset-0 bottom-0 z-[201] rounded-t-2xl bg-base_color p-4"
      @click.self="open = false"
    >
        <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl">
            <div class="mx-auto mb-2 h-1.5 w-12 rounded-full bg-line"></div>

            <div class="relative flex items-center justify-center">
                <button
                    type="button"
                    class="absolute left-0 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                    @click="$store.search.activeModal = null"
                    aria-label="閉じる"
                >
                    <x-icons.close class="w-8 h-8 text-text_color_color" />
                </button>
            </div>
         </div>

        <div class="mt-3 text-text_color">
          ここにフォルダ一覧UI.
        </div>

        <div class="mt-4">
          <button type="button" class="w-full" @click="open = false">
            閉じる
          </button>
        </div>
      </div>
    </div>
  </template>
</div>
