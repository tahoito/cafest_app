{{-- resources/views/components/ui/search-modal.blade.php --}}
<template x-teleport="body">
<div
  x-show="activeModal === 'search'"
  x-transition.opacity
  class="fixed inset-0 z-[999] flex items-end justify-center"
  @keydown.escape.window="activeModal = null"
  style="display:none;"
>
  <div class="absolute inset-0 bg-black/40" @click="activeModal = null"></div>
  <div
    x-show="activeModal === 'search'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="translate-y-6 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-6 opacity-0"
    class="relative w-full max-w-[400px] rounded-t-3xl overflow-hidden shadow-xl"
    @click.stop
  >

    <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl">
      <div class="mx-auto mb-2 h-1.5 w-12 rounded-full bg-line"></div>

      <div class="relative flex items-center justify-center">
        <button
          type="button"
          class="absolute left-0 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
          @click="activeModal = null"
          aria-label="閉じる"
        >
          <x-icons.close class="w-8 h-8 text-text_color_color" />
        </button>

        <div class="text-lg text-text_color_color">検索条件</div>
      </div>
    </div>

    <!-- content (beige) -->
    <div class="bg-base_color px-5 pt-4 pb-6">
      <div class="mx-auto w-full max-w-md space-y-4">

        <!-- エリア -->
        <section class="space-y-2">
          <div class="flex items-center text-lg text-text_color_color">
            <span><x-icons.area /></span><span>エリア</span>
          </div>
          <div class="relative">
            <select
              class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
            >
              <option>栄</option>
              <option>名駅</option>
              <option>大須</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
          </div>
        </section>

        <!-- 予算 -->
        <section class="space-y-2">
          <div class="flex items-center text-lg text-text_color_color">
            <span><x-icons.wallet /></span><span>予算</span>
          </div>
          <div class="relative">
            <select
              class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
            >
              <option>1,000円 - 2,000円</option>
              <option>2,000円 - 3,000円</option>
              <option>3,000円〜</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
          </div>
        </section>

        <!-- 営業時間 -->
        <section class="space-y-2">
          <div class="flex items-center gap-1.5 text-lg text-text_color_color">
            <span><x-icons.time class="w-5 h-5 text-text_color_color" /></span><span>営業時間</span>
          </div>
          <div class="relative">
            <select
              class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
            >
              <option>今営業中</option>
              <option>朝から営業</option>
              <option>夜も営業</option>
              <option>指定なし</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
          </div>
        </section>

        <!-- レビュー -->
        <section class="space-y-2">
          <div class="flex items-center text-lg text-text_color_color">
            <span><x-icons.review/></span><span>レビュー</span>
            <span class="text-xs text-main">※平均値です</span>
          </div>
          <div class="flex flex-wrap gap-2">
            <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>3.0以上</x-ui.tag>
            <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>4.0以上</x-ui.tag>
            <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>4.5以上</x-ui.tag>
          </div>
        </section>

        <!-- タグ -->
        <section class="space-y-2">
          <div class="flex items-center text-lg text-text_color_color">
            <span><x-icons.tag /></span><span>タグ</span>
          </div>
          <div class="flex flex-wrap gap-2">
            <x-ui.tag active>映え</x-ui.tag>
            <x-ui.tag>映え</x-ui.tag>
            <x-ui.tag>映え</x-ui.tag>
            <x-ui.tag>映え</x-ui.tag>
            <x-ui.tag>映え</x-ui.tag>

            <button
              type="button"
              class="rounded-full border-accent bg-accent px-[16px] py-[3px] text-sm text-text_color_color"
              @click="activeModal='tag'"
            >
              すべて
            </button>
          </div>
        </section>

        <!-- 検索ボタン -->
        <div class="sticky bottom-0 pt-3">
          <div class="flex justify-center pb-1">
            <x-ui.button type="submit" as="a" class="w-[70%]" variants="secondary">
              検索
            </x-ui.button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</template>
