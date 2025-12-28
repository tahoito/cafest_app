<div
  x-show="activeModal === 'wallet'"
  x-transition.opacity
  class="fixed inset-0 z-[999] flex items-end justify-center"
  @keydown.escape.window="activeModal = null"
  style="display:none;"
>
  {{-- overlay --}}
  <div class="absolute inset-0 bg-black/40" @click="activeModal = null"></div>

  <div
    x-show="activeModal === 'wallet'"
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
        
        <div class="text-base font-medium text-text_color">
          予算
        </div>

        <button
          type="button"
          class="h-9 px-3 rounded-lg text-main-color font-semibold hover:bg-black/5 active:scale-95 transition"
          @click="activeModal = null"
        >
          決定
        </button>
      </div>
    </div>

    <div class="bg-base_color px-5 pt-4 pb-6">
        <section class="space-y-2">
            <div class="relative">
            <select
                class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text_color shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
            >
                <option>1,000円 - 2,000円</option>
                <option>2,000円 - 3,000円</option>
                <option>3,000円〜</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>


