@props(['store'])

<div
  x-data="{ open: false }"
  x-modelable="open"
  x-model="open"
>
  <template x-teleport="body">
    <div
      x-show="open"
      x-transition.opacity
      class="fixed inset-0 z-[200] bg-black/40"
      @click.self="open = false"
    >
      <div
        x-show="open"
        x-transition
        class="fixed inset-x-0 bottom-0 z-[201] rounded-t-2xl bg-base_color p-4"
      >
        <div class="flex items-center justify-between">
          <p class="font-medium text-text_color">お気に入りに保存</p>
          <button type="button" class="p-2" @click="open = false">×</button>
        </div>

        <div class="mt-3 text-text_color">
          フォルダ一覧ここ.
        </div>

        <div class="mt-4">
          <x-ui.button type="button" class="w-full" @click="open = false">
            保存
          </x-ui.button>
        </div>
      </div>
    </div>
  </template>
</div>
