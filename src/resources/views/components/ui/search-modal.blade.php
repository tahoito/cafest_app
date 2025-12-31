{{-- resources/views/components/ui/search-modal.blade.php --}}
<template x-teleport="body">
  <div
    x-show="$store.search.activeModal === 'search' || $store.search.activeModal === 'searchTag'"
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-end justify-center"
    style="display:none;"
    @keydown.escape.window="
      if ($store.search.activeModal === 'searchTag') $store.search.activeModal = 'search';
      else $store.search.activeModal = null;
    "
  >
    {{-- backdrop --}}
    <div
      class="absolute inset-0 bg-black/40"
      @click="
        if ($store.search.activeModal === 'searchTag') $store.search.activeModal = 'search';
        else $store.search.activeModal = null;
      "
    ></div>

    {{-- ===== 検索条件（メイン） ===== --}}
    <div
      x-show="$store.search.activeModal === 'search'"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="translate-y-6 opacity-0"
      x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="translate-y-0 opacity-100"
      x-transition:leave-end="translate-y-6 opacity-0"
      class="relative w-full max-w-[400px] rounded-t-3xl overflow-hidden shadow-xl"
      @click.stop
    >
      {{-- header --}}
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
          <div class="text-lg text-text_color_color">検索条件</div>
        </div>
      </div>

      <form
        class="bg-base_color px-5 pt-4 pb-0"
        action="{{ route('user.search') }}"
        method="GET"
      >
        <div class="mx-auto w-full max-w-md space-y-4">

          {{-- エリア --}}
          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.area /></span><span>エリア</span>
            </div>
            <div class="relative">
              <select
                name="area"
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
                x-model="$store.search.area"
              >
                <option value="">指定なし</option>
                <option value="栄">栄</option>
                <option value="名駅">名駅</option>
                <option value="大須">大須</option>
                <option value="矢場町">矢場町</option>
              </select>
              <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
          </section>

          {{-- 予算 --}}
          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.wallet /></span><span>予算</span>
            </div>
            <div class="relative">
              <select
                name="budget"
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
                x-model="$store.search.budget"
              >
                <option value="">指定なし</option>
                <option value="1000-2000">1,000円〜2,000円</option>
                <option value="2000-3000">2,000円〜3,000円</option>
                <option value="3000-">3,000円〜</option>
              </select>
              <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
          </section>

          {{-- 営業時間 --}}
          <section class="space-y-2">
            <div class="flex items-center gap-1.5 text-lg text-text_color_color">
              <span><x-icons.time class="w-5 h-5 text-text_color_color" /></span><span>営業時間</span>
            </div>
            <div class="relative">
              <select
                name="time"
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
                x-model="$store.search.time"
              >
                <option value="">指定なし</option>
                <option value="open_now">今営業中</option>
                <option value="morning">朝から営業</option>
                <option value="night">夜も営業</option>
              </select>
              <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
          </section>

          {{-- レビュー --}}
          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.review/></span><span>レビュー</span>
              <span class="text-xs text-main">※平均値です</span>
            </div>

            <div class="flex flex-wrap gap-2">
              <x-ui.tag
                type="button"
                @click="$store.search.toggleRating(3.0)"
                x-bind:class="$store.search.isRatingOn(3.0)
                  ? '!bg-main !border-main !text-form'
                  : '!bg-base !border-main !text-text_color'"
              >
                <x-icons.star class="text-star w-4 h-4"/>3.0〜
              </x-ui.tag>

              <x-ui.tag
                type="button"
                @click="$store.search.toggleRating(4.0)"
                x-bind:class="$store.search.isRatingOn(4.0)
                  ? '!bg-main !border-main !text-form'
                  : '!bg-base !border-main !text-text_color'"
              >
                <x-icons.star class="text-star w-4 h-4"/>4.0〜
              </x-ui.tag>

              <x-ui.tag
                type="button"
                @click="$store.search.toggleRating(4.5)"
                x-bind:class="$store.search.isRatingOn(4.5)
                  ? '!bg-main !border-main !text-form'
                  : '!bg-base !border-main !text-text_color'"
              >
                <x-icons.star class="text-star w-4 h-4"/>4.5〜
              </x-ui.tag>
            </div>
          </section>

          {{-- tags hidden --}}
          <template x-for="t in $store.search.tags" :key="t">
            <input type="hidden" name="tags[]" :value="t">
          </template>

          <input type="hidden" name="rating_min" :value="$store.search.ratingMin ?? ''">

          {{-- タグ --}}
          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.tag /></span><span>タグ</span>
            </div>

            <div class="flex flex-wrap gap-2">
              <x-ui.tag
                type="button"
                @click="$store.search.toggleTag('映え')"
                x-bind:class="$store.search.hasTag('映え')
                  ? '!bg-main !border-main !text-form'
                  : '!bg-base !border-main !text-text_color'"
              >
                映え
              </x-ui.tag>

              <button
                type="button"
                class="rounded-full border-accent bg-accent px-[16px] py-[3px] text-sm text-text_color_color"
                @click="$store.search.activeModal='searchTag'"
              >
                すべて
              </button>
            </div>
          </section>

          {{-- ボタン --}}
          <div class="sticky bottom-0 bg-base_color pt-3 pb-6">
            <div class="flex justify-center">
              <x-ui.button type="submit" class="w-[70%]" variants="secondary">
                検索
              </x-ui.button>
            </div>
          </div>

        </div>
      </form>
    </div>

    {{-- ===== 全タグ ===== --}}
    <div
      x-show="$store.search.activeModal === 'searchTag'"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="translate-y-6 opacity-0"
      x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="translate-y-0 opacity-100"
      x-transition:leave-end="translate-y-6 opacity-0"
      class="relative w-full max-w-[400px] rounded-t-3xl overflow-hidden shadow-xl"
      @click.stop
    >
      {{-- header --}}
      <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl">
        <div class="mx-auto mb-2 h-1.5 w-12 rounded-full bg-line"></div>

        <div class="relative flex items-center justify-center">
          <button
            type="button"
            class="absolute left-0 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
            @click="$store.search.activeModal='search'"
            aria-label="戻る"
          >
            <x-icons.close class="w-8 h-8 text-text_color_color" />
          </button>
          <div class="text-lg text-text_color_color">全てのタグ</div>
        </div>
      </div>

      <div class="bg-base_color px-5 pt-4 pb-6">
        <div class="flex flex-wrap gap-2">
          <x-ui.tag
            type="button"
            @click="$store.search.toggleTag('推し活')"
            x-bind:class="$store.search.hasTag('推し活')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
          >推し活</x-ui.tag>

          <x-ui.tag
            type="button"
            @click="$store.search.toggleTag('作業')"
            x-bind:class="$store.search.hasTag('作業')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
          >作業</x-ui.tag>

          <x-ui.tag
            type="button"
            @click="$store.search.toggleTag('静か')"
            x-bind:class="$store.search.hasTag('静か')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
          >静か</x-ui.tag>
        </div>

        <div class="sticky bottom-0 pt-3">
          <div class="flex justify-center">
            <x-ui.button
              type="button"
              class="w-[70%]"
              variants="secondary"
              @click="$store.search.activeModal='search'"
            >
              決定
            </x-ui.button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
