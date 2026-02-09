<div x-data="reviewModal" x-init="init()" x-effect="console.log('open=', open, 'loading=', loading, 'error=', error)" @keydown.escape.window="close()">
  <template x-teleport="body">
    <div
      x-show="open"
      x-transition.opacity
      class="fixed inset-0 z-[200] bg-black/40"
      @click.self="close()"
      x-cloak
    >
      <div
        class="fixed inset-x-0 bottom-0 z-[201] rounded-t-3xl bg-base_color shadow-[0_-10px_30px_rgba(0,0,0,0.18)] overflow-hidden"
        x-show="open"
        x-transition
      >
        <div class="pb-[env(safe-area-inset-bottom)]">

            <div class="sticky top-0 z-10 pt-4 bg-base_color">
                <div class="h-12 grid grid-cols-[48px_1fr_48px] items-center px-4">

                    <button
                    type="button"
                    class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                    @click="close()"
                    aria-label="閉じる"
                    >
                    <x-icons.close class="w-8 h-8 text-text_color" />
                    </button>

                    <div class="text-center text-text_color text-2xl truncate">
                    <span x-text="data?.store?.name ?? 'レビュー詳細'"></span>
                    </div>

                    <div class="h-9 w-9"></div>

                </div>
            </div>


          <div class="max-h-[75vh] overflow-y-auto overscroll-contain px-4 pb-6">
            <template x-if="loading">
              <div class="py-10 text-center text-placeholder">読み込み中...</div>
            </template>

            <template x-if="error && !loading">
              <div class="py-10 text-center text-red-500" x-text="error"></div>
            </template>

            <template x-if="data && !loading">
              <div>
                <div class="flex items-center gap-3 pt-2">
                  <div class="w-12 h-12 rounded-full overflow-hidden bg-base shrink-0">
                    <img
                      :src="data.user?.avatar_url ?? ''"
                      alt="avatar"
                      class="w-full h-full object-cover"
                      x-show="data.user?.avatar_url"
                    >
                  </div>

                  <div class="min-w-0">
                    <div class="text-text_color text-lg truncate" x-text="data.user?.name ?? 'Anonymous'"></div>
                    <div class="text-placeholder text-sm truncate" x-text="data.user?.handle ?? ''"></div>
                  </div>

                  <div class="ml-auto text-placeholder text-sm" x-text="data.created_at ?? ''"></div>
                </div>

                <div class="mt-3 flex items-center gap-1" aria-label="評価">
                  <x-icons.star class="h-4 w-4" x-bind:class="(data.rating ?? 0) >= 1 ? 'text-star' : 'text-placeholder'" />
                  <x-icons.star class="h-4 w-4" x-bind:class="(data.rating ?? 0) >= 2 ? 'text-star' : 'text-placeholder'" />
                  <x-icons.star class="h-4 w-4" x-bind:class="(data.rating ?? 0) >= 3 ? 'text-star' : 'text-placeholder'" />
                  <x-icons.star class="h-4 w-4" x-bind:class="(data.rating ?? 0) >= 4 ? 'text-star' : 'text-placeholder'" />
                  <x-icons.star class="h-4 w-4" x-bind:class="(data.rating ?? 0) >= 5 ? 'text-star' : 'text-placeholder'" />
                </div>


                <template x-if="data.body">
                  <div class="mt-3 rounded-lg border border-main bg-base px-4 py-3 shadow-sm shadow-[0_1px_4px_rgba(0,0,0,0.20)]">
                    <div class="text-text_color text-base leading-relaxed" x-text="data.body"></div>
                  </div>
                </template>

                <template x-if="data.tags?.length">
                  <div class="mt-3 flex flex-wrap gap-2">
                    <template x-for="t in data.tags" :key="t">
                        <span
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-full border border-main px-[16px] py-[2px] text-sm bg-main text-form"
                            x-text="t"
                        ></span>
                    </template>
                  </div>
                </template>

                <template x-if="data.images?.length">
                  <div class="mt-4 space-y-4">
                    <template x-for="(img, idx) in data.images" :key="idx">
                      <div class="rounded-2xl overflow-hidden bg-base">
                        <img :src="img" alt="review image" class="w-full h-auto object-cover">
                      </div>
                    </template>
                  </div>
                </template>
              </div>
            </template>
          </div>

        </div>
      </div>
    </div>
  </template>
<script>
(() => {
  const register = () => {
    Alpine.data('reviewModal', () => ({
      open: false,
      loading: false,
      error: null,
      data: null,
      controller: null,

      init() {
        window.addEventListener('review:open', (e) => {
          const { endpoint, fallback_url } = e.detail || {}
          if (!endpoint) {
            if (fallback_url) window.location.href = fallback_url
            return
          }
          this.openWithFetch(endpoint)
        })
      },

      async openWithFetch(url) {
        this.open = true
        this.loading = true
        this.error = null
        this.data = null

        document.body.classList.add('overflow-hidden')

        if (this.controller) this.controller.abort()
        this.controller = new AbortController()

        try {
          const res = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            signal: this.controller.signal,
          })
          if (!res.ok) throw new Error('読み込みに失敗したよ')
          this.data = await res.json()
        } catch (err) {
          if (err?.name !== 'AbortError') this.error = err?.message || 'エラーが起きたよ'
        } finally {
          this.loading = false
        }
      },

      close() {
        this.open = false
        this.loading = false
        this.error = null
        this.data = null

        if (this.controller) this.controller.abort()
        this.controller = null

        document.body.classList.remove('overflow-hidden')
      },
    }))
  }

  if (window.Alpine) {
    register()
  } else {
    document.addEventListener('alpine:init', register)
  }
})()
</script>
</div>
