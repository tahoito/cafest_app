<div
  x-show="activeModal === 'time'"
  x-transition.opacity
  class="fixed inset-0 z-[999] flex items-end justify-center"
  @keydown.escape.window="activeModal = null"
  style="display:none;"
>
  {{-- overlay --}}
  <div class="absolute inset-0 bg-black/40" @click="activeModal = null"></div>

  <div
    x-show="activeModal === 'time'"
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
          <x-icons.close class="w-7 h-7 text-text_color" />
        </button>

        <div class="text-base text-text_color">営業時間</div>
      </div>
    </div>

    {{-- content (beige) --}}
    <div class="bg-base_color px-5 pt-4 pb-6">
      <section class="space-y-2">
        <div class="relative">
          <select
            class="w-full appearance-none rounded-xl border border-line bg-form px-4 py-3 text-base text-text shadow-sm focus:outline-none focus:ring-2 focus:ring-main-color/30"
          >
            <option>今営業中</option>
            <option>朝から営業</option>
            <option>夜も営業</option>
            <option>指定なし</option>
          </select>
          <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-placeholder-color">▾</span>
        </div>
      </section>

      <div class="pt-5">
        <div class="flex justify-center">
          <x-ui.button as="button" type="button" class="w-[70%]" variants="secondary" @click="activeModal=null">
            決定
          </x-ui.button>
        </div>
      </div>
    </div>
  </div>
</div>
