<div x-data="reviewModal()" x-init="init()" 
    @keydown.escape.window="close()">
    <template x-teleport="body">
        <div x-show="open" x-transition.opacity
            class="fixed inset-0 z-[200] bg-black/40"
            @click.self="close()" x-cloak
        >
            <div class="fixed inset-x-0 bottom-0 z-[201] rounded-t-3xl overflow-hidden bg-base_color shadow-[0_-10px_30px_rgba(0,0,0,0.18)]"
                x-show="open" x-transition
            >
                <div class="pb-[env(safe-area-inset-bottom)]">
                    <div class="sticky top-0 z-10 bg-base_color">
                        <div class="h-12 flex items-center px-4">
                           <button
                                type="button"
                                class="absolute left-0 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                                @click="close()"
                                aria-label="閉じる"
                            >
                            <div class="flex-1 text-center text-text_color font-semibold truncate">
                                <span x-text="post?.store_name ?? 'Review'"></span>
                            </div>
                            <div class="w-10"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-base_color shrink-0">
                            <img :src="post?.user_avatar ?? ''" alt="avatar" class="w-full h-full object-cover"
                                x-show="post?.user_avatar"
                            >
                        </div>

                        <div class="min-w-0">
                            <div class="text-text_color font-medium truncate" x-text="post?.user_name ?? 'Anonymous'"></div>
                            <div class="text-placeholder text-sm truncate" x-text="post?.user_handle ?? ''"></div>
                        </div>

                        <div class="ml-auto text-placeholder text-sm" x-text="post?.created_at ?? ''"></div>
                    </div>

                    <div class="mt-3 flex items-center gap-1" aria-label="評価">
                        <template x-for="i in 5" :key="i">
                            <span class="text-xl" :class="(post?.rating ?? 0) >= i ? 'text-star' : 'text-placeholder'">
                                <x-ui.star />
                            </span>
                        </template>
                    </div>

                    <template x-if="post?.body">
                        <div class="mt-3 rounded-2xl border border-main bg-base px-4 py-3 shadow-sm">
                            <p class="text-text_color leading-relaxed" x-text="post.body"></p>
                        </div>
                    </template>

                    <template x-if="post?.tags?.length">
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="t in post.tags" key="t">
                                <span class="px-4 py-4 rounded-full bg-main text-text_color text-sm">
                                    <span x-text="t"></span>
                                </span>
                            </template>
                        </div>
                    </template>

                    <template x-if="post?.images?.length">
                        <div class="mt-4 space-y-4">
                            <template x-for="(img, idx) in post.images" :key="idx">
                            <div class="rounded-2xl overflow-hidden bg-base">
                                <img :src="img" alt="review image" class="w-full h-auto object-cover">
                            </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="(!post?.images?.length) && post?.image">
                        <div class="mt-4 rounded-2xl overflow-hidden bg-base">
                            <img :src="post.image" alt="review image" class="w-full h-auto object-cover">
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    function reviewModal() {
      return {
        open: false,
        post: null,
        init() {
          window.addEventListener('review:open', (e) => {
            this.post = e.detail;
            this.open = true;
            document.body.classList.add('overflow-hidden');
          });
        },
        close() {
          this.open = false;
          this.post = null;
          document.body.classList.remove('overflow-hidden');
        }
      }
    }
</script>
</div>
