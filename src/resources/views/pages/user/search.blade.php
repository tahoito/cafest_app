@extends('layouts.app')
@section('title','検索')

@section('content')

<div x-data="searchFilter()" class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-5">
        <section class="px-4">
            <x-ui.search-bar />
        </section>

        <section class="px-4 space-y-2">
            <div class="flex gap-2 overflow-x-auto no-scrollbar">
                <x-ui.search-tag
                    @click="activeModal='search'"
                    class="!px-0 !w-10 !h-7 !rounded-full flex items-center justify-center
                            !border-0 !ring-0 !shadow-none before:!hidden after:!hidden"
                    >
                <x-icons.condition class="w-6 h-3"/>
                </x-ui.search-tag>
                <x-ui.search-tag @click="activeModal='area'">エリア</x-ui.search-tag>
                <x-ui.search-tag @click="activeModal='wallet'">予算</x-ui.search-tag>
                <x-ui.search-tag @click="activeModal='time'">営業時間</x-ui.search-tag>
                <x-ui.search-tag @click="activeModal='review'">レビュー</x-ui.search-tag>
                <x-ui.search-tag @click="activeModal='tag'">タグ</x-ui.search-tag>
            </div>
        </section>

        <section x-data="{ showAll: false }" class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">カテゴリー</div>
            <div class="flex justify-center gap-3 flex-wrap">
                <x-ui.category 
                    label="珈琲専門"
                    type="button"
                    @click="toggleMood('珈琲専門')"
                    x-bind:class="hasMood('珈琲専門') ? '!bg-main !text-form' : ''"
                >
                    <x-icons.coffee />
                </x-ui.category> 
                <x-ui.category 
                    label="紅茶"
                    type="button"
                    @click="toggleMood('紅茶')"
                    x-bind:class="hasMood('紅茶') ? '!bg-main !text-form' : ''"
                >
                    <x-icons.tea />
                </x-ui.category> 
                <x-ui.category 
                    label="スイーツ"
                    type="button"
                    @click="toggleMood('スイーツ')"
                    x-bind:class="hasMood('スイーツ') ? '!bg-main !text-form' : ''"
                >
                    <x-icons.cake />
                </x-ui.category> 
                <x-ui.category 
                    label="夜カフェ"
                    type="button"
                    @click="toggleMood('夜カフェ')"
                    x-bind:class="hasMood('夜カフェ') ? '!bg-main !text-form' : ''"
                >
                    <x-icons.moon />
                </x-ui.category> 

                <div x-show="showAll" x-transition class="contents">
                <x-ui.category label="静か" />
                <x-ui.category label="勉強・作業" />
                <x-ui.category label="長居OK" />
                <x-ui.category label="レトロ・喫茶" />
                <x-ui.category label="デート" />
                <x-ui.category label="女子会" />
                <x-ui.category label="韓国風" />
                <x-ui.category label="ペットOK" />
                </div>
            </div>
            <div class="flex">
                <button
                type="button"
                @click="showAll = !showAll"
                class="ml-auto text-[14px] text-text_color"
                >
                <span x-show="!showAll">もっと見る</span>
                <span x-show="showAll">閉じる</span>
                </button>
            </div>
        </section>

        <form id="searchForm" method="GET" action="{{ route('user.search') }}" class="hidden"
            x-ref="searchForm"
            >
            <input type="hidden" name="area" :value="area">
            <input type="hidden" name="budget" :value="budget">
            <input type="hidden" name="open" :value="open">
            <input type="hidden" name="rating_min" :value="ratingMin ?? ''">
        
            <template x-for="m in moods" :key="m">
                <input type="hidden" name="moods[]" :value="m">
            </template>
            <template x-for="t in tags" :key="t">
                <input type="hidden" name="tags[]" :value="t">
            </template>
        </form>

        <section class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">おすすめのカフェ</div>
            <div class="grid grid-cols-2 gap-3">
                @foreach($stores as $store)
                <x-ui.store-card
                    :store="$store"
                    :href="url('/stores/' . data_get($store,'id'))"
                    variant="list"
                />
                @endforeach
            </div>
        </section>

    </div>

    {{-- モーダル（全部ここに置いてOK） --}}
        <x-ui.search-modal />
        <x-ui.area-modal />
        <x-ui.wallet-modal />
        <x-ui.time-modal />
        <x-ui.review-modal />
        <x-ui.tag-modal />   
</div>
@endsection

<script>
function searchFilter() {
  return {
    area: '',
    budget: '',
    open: '',
    ratingMin: null,

    moods: [],
    tags: [],

    hasMood(m){ return this.moods.includes(m); },
    toggleMood(m){
      this.moods = this.hasMood(m)
        ? this.moods.filter(x => x !== m)
        : [...this.moods, m];
    },


    hasTag(t){ return this.tags.includes(t); },
    toggleTag(t){
      this.tags = this.hasTag(t)
        ? this.tags.filter(x => x !== t)
        : [...this.tags, t];
    },

    submitSearch(){
      this.$refs.searchForm.submit();
    }
  }
}
</script>
