<template x-teleport="body">
  <div
    x-show="activeModal === 'review'"
    x-transition.opacity
    class="fixed inset-0 z-[99999] flex items-end justify-center"
    @keydown.escape.window="activeModal = null"
    style="display:none;"
  >
    <div class="absolute inset-0 bg-black/40" @click="activeModal = null"></div>

    <div
      x-data="{
        selected: [],
        toggle(n){
          const i = this.selected.indexOf(n);
          if (i === -1) this.selected.push(n);
          else this.selected.splice(i, 1);
          this.selected.sort((a,b)=>a-b);
        },
        isOn(n){ return this.selected.includes(n); },
        clear(){ this.selected = []; },
      }"
      x-show="activeModal === 'review'"
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

        <div class="relative flex items-center justify-between">
          <button
            type="button"
            class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
            @click="activeModal = null"
            aria-label="閉じる"
          >
            <x-icons.close class="w-7 h-7 text-text_color" />
          </button>

          <div class="text-base font-medium text-text_color">レビュー</div>

          <button
            type="button"
            class="h-9 px-3 rounded-lg text-main-color font-semibold hover:bg-black/5 active:scale-95 transition"
            @click="activeModal = null"
          >
            決定
          </button>
        </div>
      </div>

      <div class="bg-base_color px-5 pt-4 pb-6 space-y-3">
        <div class="flex items-center justify-between">
          <div class="text-sm text-text_color">星をタップして選択</div>

          <button
            type="button"
            class="text-sm text-main-color font-semibold hover:opacity-80"
            @click="clear()"
          >
            クリア
          </button>
        </div>

        <div class="flex flex-wrap gap-2">
          @for($n=1; $n<=5; $n++)
            <button
              type="button"
              class="inline-flex items-center gap-1 rounded-full border px-3 py-2 text-sm transition active:scale-95"
              :class="isOn({{ $n }})
                ? 'bg-main text-form border-main'
                : 'bg-form text-text_color border-line'"
              @click="toggle({{ $n }})"
            >
              <span :class="isOn({{ $n }}) ? 'text-form' : 'text-star'">
                <x-icons.star class="w-4 h-4" />
              </span>
              <span>{{ number_format($n, 1) }}</span>
            </button>
          @endfor
        </div>

        <input type="hidden" name="ratings" :value="selected.join(',')">
      </div>
    </div>
  </div>
</template>
