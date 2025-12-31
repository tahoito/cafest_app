@extends('layouts.app')
@section('title','検索')

@section('content')

<div x-data class="min-h-screen bg-base_color">
  <div class="w-full max-w-md mx-auto pt-6 space-y-5">

    <section class="px-4">
      <x-ui.search-bar />
    </section>

    {{-- 上の検索チップ --}}
    <section class="px-4 space-y-2">
      <div class="flex gap-2 overflow-x-auto no-scrollbar">
        <x-ui.search-tag
            @click="$store.search.activeModal = 'search'"
            class="!px-0 !w-10 !h-7 !rounded-full flex items-center justify-center
                    !border-0 !ring-0 !shadow-none before:!hidden after:!hidden"
            >
            <span class="pointer-events-none">
                <x-icons.condition class="w-6 h-3"/>
            </span>
        </x-ui.search-tag>
        <x-ui.search-tag @click="$store.search.activeModal = 'area'">エリア</x-ui.search-tag>
        <x-ui.search-tag @click="$store.search.activeModal = 'budget'">予算</x-ui.search-tag>
        <x-ui.search-tag @click="$store.search.activeModal = 'time'">営業時間</x-ui.search-tag>
        <x-ui.search-tag @click="$store.search.activeModal = 'review'">レビュー</x-ui.search-tag>
        <x-ui.search-tag @click="$store.search.activeModal = 'tag'">タグ</x-ui.search-tag>
      </div>
    </section>

    {{-- カテゴリー（= mood） --}}
    <section x-data="{ showAll: false }" class="px-4 space-y-2">
      <div class="text-lg text-text_color font-medium">カテゴリー</div>

      <div class="flex justify-center gap-3 flex-wrap">
        <x-ui.category
          label="珈琲専門"
          type="button"
          @click="$store.search.toggleMood('珈琲専門')"
          x-bind:class="$store.search.hasMood('珈琲専門') ? '!bg-main !text-form' : ''"
        >
          <x-icons.coffee />
        </x-ui.category>

        <x-ui.category
          label="紅茶"
          type="button"
          @click="$store.search.toggleMood('紅茶')"
          x-bind:class="$store.search.hasMood('紅茶') ? '!bg-main !text-form' : ''"
        >
          <x-icons.tea />
        </x-ui.category>

        <x-ui.category
          label="スイーツ"
          type="button"
          @click="$store.search.toggleMood('スイーツ')"
          x-bind:class="$store.search.hasMood('スイーツ') ? '!bg-main !text-form' : ''"
        >
          <x-icons.cake />
        </x-ui.category>

        <x-ui.category
          label="夜カフェ"
          type="button"
          @click="$store.search.toggleMood('夜カフェ')"
          x-bind:class="$store.search.hasMood('夜カフェ') ? '!bg-main !text-form' : ''"
        >
          <x-icons.moon />
        </x-ui.category>

        <div x-show="showAll" x-transition class="contents">
          <x-ui.category
            label="静か"
            type="button"
            @click="$store.search.toggleMood('静か')"
            x-bind:class="$store.search.hasMood('静か') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="勉強・作業"
            type="button"
            @click="$store.search.toggleMood('勉強・作業')"
            x-bind:class="$store.search.hasMood('勉強・作業') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="長居OK"
            type="button"
            @click="$store.search.toggleMood('長居OK')"
            x-bind:class="$store.search.hasMood('長居OK') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="レトロ・喫茶"
            type="button"
            @click="$store.search.toggleMood('レトロ・喫茶')"
            x-bind:class="$store.search.hasMood('レトロ・喫茶') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="デート"
            type="button"
            @click="$store.search.toggleMood('デート')"
            x-bind:class="$store.search.hasMood('デート') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="女子会"
            type="button"
            @click="$store.search.toggleMood('女子会')"
            x-bind:class="$store.search.hasMood('女子会') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="韓国風"
            type="button"
            @click="$store.search.toggleMood('韓国風')"
            x-bind:class="$store.search.hasMood('韓国風') ? '!bg-main !text-form' : ''"
          />
          <x-ui.category
            label="ペットOK"
            type="button"
            @click="$store.search.toggleMood('ペットOK')"
            x-bind:class="$store.search.hasMood('ペットOK') ? '!bg-main !text-form' : ''"
          />
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

    {{-- ✅ 検索フォーム（hidden） --}}
    <form
      id="searchForm"
      method="GET"
      action="{{ route('user.search') }}"
      class="hidden"
      x-ref="searchForm"
    >
      <input type="hidden" name="area" :value="$store.search.area">
      <input type="hidden" name="budget" :value="$store.search.budget">
      <input type="hidden" name="time" :value="$store.search.time">
      <input type="hidden" name="ratings" :value="($store.search.selectedRatings || []).join(',')">

      <template x-for="m in $store.search.moods" :key="m">
        <input type="hidden" name="moods[]" :value="m">
      </template>

      <template x-for="t in $store.search.tags" :key="t">
        <input type="hidden" name="tags[]" :value="t">
      </template>
    </form>

    {{-- おすすめ --}}
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

  {{-- ✅ モーダル --}}
  <x-ui.search-modal />
  <x-ui.area-modal />
  <x-ui.wallet-modal />
  <x-ui.time-modal />
  <x-ui.review-modal />
  <x-ui.tag-modal />
</div>

@endsection
