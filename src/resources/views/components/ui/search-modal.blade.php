{{-- resources/views/components/ui/search-modal.blade.php --}}
<template x-teleport="body">
  <div
    x-data="searchFilter()"
    x-show="activeModal === 'search'"
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-end justify-center"
    style="display:none;"
    @keydown.escape.window="
      if (activeModal === 'searchTag') activeModal = 'search';
      else activeModal = null;
    "
  >
    <div
      class="absolute inset-0 bg-black/40"
      @click="
        if (activeModal === 'SearchTag') { activeModal = 'search' }
        else { activeModal = null }
      "
    ></div>

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
      {{-- header --}}
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

      <form 
        class="bg-base_color px-5 pt-4 pb-6"
        action="{{ route('user.search') }}"
        method="GET"
      >
        <div class="mx-auto w-full max-w-md space-y-4">
          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.area /></span><span>エリア</span>
            </div>
            <div class="relative">
              <select
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
                name="area"
              >
                <option value="">指定なし</option>
                <option value="栄">栄</option>
                <option value="名駅">名駅</option>
                <option value="矢場町">矢場町</option>
                <option value="矢場町">矢場町</option>
              </select>
              <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
          </section>

          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.wallet /></span><span>予算</span>
            </div>
            <div class="relative">
              <select
                name="budget"
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
              >
                <option>1,000円 - 2,000円</option>
                <option>2,000円 - 3,000円</option>
                <option>3,000円〜</option>
              </select>
              <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
          </section>

          <section class="space-y-2">
            <div class="flex items-center gap-1.5 text-lg text-text_color_color">
              <span><x-icons.time class="w-5 h-5 text-text_color_color" /></span><span>営業時間</span>
            </div>
            <div class="relative">
              <select
                name="time"
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

          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.review/></span><span>レビュー</span>
              <span class="text-xs text-main">※平均値です</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>3.0〜</x-ui.tag>
              <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>4.0〜</x-ui.tag>
              <x-ui.tag><x-icons.star class="text-star w-4 h-4"/>4.5〜</x-ui.tag>
            </div>
          </section>

          <template x-for="t in tags" :key="t">
            <input type="hidden" name="tags[]" :value="t">
          </template>

          <section class="space-y-2">
            <div class="flex items-center text-lg text-text_color_color">
              <span><x-icons.tag /></span><span>タグ</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <x-ui.tag 
                type="button"
                @click="toggleTag('映え')"
                x-bind:class="hasTag('映え')
                ? '!bg-main !border-main !text-form'
                : '!bg-base !border-main !text-text_color'">映え</x-ui.tag>
              <x-ui.tag 
                type="button"
                @click="toggleTag('映え')"
                x-bind:class="hasTag('映え')
                ? '!bg-main !border-main !text-form'
                : '!bg-base !border-main !text-text_color'">映え</x-ui.tag>
              <button
                type="button"
                class="rounded-full border-accent bg-accent px-[16px] py-[3px] text-sm text-text_color_color"
                @click="activeModal='searchTag'"
              >
                すべて
              </button>
            </div>
          </section>

          <div class="sticky bottom-0 pt-3">
            <div class="flex justify-center pb-1">
              <x-ui.button type="submit" class="w-[70%]" variants="secondary">
                検索
              </x-ui.button>
            </div>
          </div>
        </div>
      </form>

      <script>
        function searchFilter() {
          return {
            tags: [],
            hasTag(t) {
              return this.tags.includes(t);
            },

            toggleTag(t) {
              if (this.hasTag(t)) {
                this.tags = this.tags.filter(x => x !== t);
              } else {
                this.tags = [...this.tags, t];
              }
            },
          }
        }
      </script>

    </div>

    <div
      x-show="activeModal === 'searchTag'"
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
            @click="activeModal='search'"
            aria-label="戻る"
          >
            <x-icons.close class="w-8 h-8 text-text_color_color" />
          </button>

          <div class="text-lg text-text_color_color">全てのタグ</div>
        </div>
      </div>

      {{-- content --}}
      <div class="bg-base_color px-5 pt-4 pb-6">
        <div class="mx-auto w-full max-w-md space-y-4">

          {{-- タグ一覧（例） --}}
          <div class="flex flex-wrap gap-2">
            <x-ui.tag
              type="button"
              @click="toggleTag('推し活')"
              x-bind:class="hasTag('推し活')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              推し活
            </x-ui.tag>
            <x-ui.tag
              type="button"
              @click="toggleTag('作業')"
              x-bind:class="hasTag('作業')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              作業
            </x-ui.tag>
            <x-ui.tag
              type="button"
              @click="toggleTag('静か')"
              x-bind:class="hasTag('静か')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              静か
            </x-ui.tag>
            <x-ui.tag
              type="button"
              @click="toggleTag('スイーツ')"
              x-bind:class="hasTag('スイーツ')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              スイーツ
            </x-ui.tag>
            <x-ui.tag
              type="button"
              @click="toggleTag('コーヒー')"
              x-bind:class="hasTag('コーヒー')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              コーヒー
            </x-ui.tag>
            <x-ui.tag
              type="button"
              @click="toggleTag('モーニング')"
              x-bind:class="hasTag('モーニング')
              ? '!bg-main !border-main !text-form'
              : '!bg-base !border-main !text-text_color'"
            >
              モーニング
            </x-ui.tag>
          </div>

          {{-- 決定 --}}
          <div class="sticky bottom-0 pt-3">
            <div class="flex justify-center pb-1">
              <x-ui.button type="button" class="w-[70%]" variants="secondary" @click="activeModal='search'">
                決定
              </x-ui.button>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</template>
