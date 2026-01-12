@props(['store'])

<div
  x-show="$root.__x.$data.favoriteOpen"
  x-transition.opacity
  class="fixed inset-0 z-[200] bg-black/40"
  @click.self="$root.__x.$data.favoriteOpen = false"
>
  <div
    x-transition
    class="fixed inset-x-0 bottom-0 z-[201] rounded-t-2xl bg-base_color p-4"
  >
    <div class="flex items-center justify-between">
      <p class="font-medium text-text_color">お気に入りに保存</p>
      <button type="button" class="p-2" @click="$root.__x.$data.favoriteOpen = false">×</button>
    </div>

    <div class="mt-3 space-y-2 max-h-[50vh] overflow-y-auto">
      {{-- ここにフォルダ一覧UI --}}
      <p class="text-text_color text-sm">フォルダ一覧ここに出す.</p>
    </div>

    <div class="mt-4 flex gap-2">
      <x-ui.button type="button" variant="secondary" class="flex-1" @click="/* 新規フォルダ */">
        ＋ 新しいフォルダ
      </x-ui.button>

      <x-ui.button type="button" class="flex-1" @click="/* 保存 */">
        保存
      </x-ui.button>
    </div>
  </div>
</div>
