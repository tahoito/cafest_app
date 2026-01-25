@extends('layouts.app')
@section('title','検索')

@section('content')
<div x-data class="h-[100dvh] bg-base_color">
  <div class="fixed top-0 inset-x-0 z-50 bg-base_color border-b border-black/5 shadow-[0_6px_14px_-10px_rgba(0,0,0,0.35)]">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="w-full max-w-md mx-auto pt-6 space-y-5 pb-3">

        <section class="px-4">
          <x-ui.search-bar />
        </section>

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

      </div>
    </div>
  </div>
      
    <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+150px)]">
        <div class="w-full max-w-md mx-auto pt-2 space-y-5 pb-24">
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
                <img
                  x-show="!$store.search.hasMood('珈琲専門')"
                  x-cloak
                  src="{{ asset('images/moods/coffee.png') }}"
                >
                <img
                  x-show="$store.search.hasMood('珈琲専門')"
                  x-cloak
                  src="{{ asset('images/moods/coffee_white.png') }}"
                >
              </x-ui.category>

              <x-ui.category
                label="紅茶"
                type="button"
                @click="$store.search.toggleMood('紅茶')"
                x-bind:class="$store.search.hasMood('紅茶') ? '!bg-main !text-form' : ''"
              >
                <img
                  x-show="!$store.search.hasMood('紅茶')"
                  x-cloak
                  src="{{ asset('images/moods/tea.png') }}"
                >
                <img
                  x-show="$store.search.hasMood('紅茶')"
                  x-cloak
                  src="{{ asset('images/moods/tea_white.png') }}"
                >
              </x-ui.category>

              <x-ui.category
                label="スイーツ"
                type="button"
                @click="$store.search.toggleMood('スイーツ')"
                x-bind:class="$store.search.hasMood('スイーツ') ? '!bg-main !text-form' : ''"
              >
                <img
                  x-show="!$store.search.hasMood('スイーツ')"
                  x-cloak
                  src="{{ asset('images/moods/cake.png') }}"
                >
                <img
                  x-show="$store.search.hasMood('スイーツ')"
                  x-cloak
                  src="{{ asset('images/moods/cake_white.png') }}"
                >
              </x-ui.category>

              <x-ui.category
                label="夜カフェ"
                type="button"
                @click="$store.search.toggleMood('夜カフェ')"
                x-bind:class="$store.search.hasMood('夜カフェ') ? '!bg-main !text-form' : ''"
              >
                <img
                  x-show="!$store.search.hasMood('夜カフェ')"
                  x-cloak
                  src="{{ asset('images/moods/moon.png') }}"
                >
                <img
                  x-show="$store.search.hasMood('夜カフェ')"
                  x-cloak
                  src="{{ asset('images/moods/moon_white.png') }}"
                >
              </x-ui.category>

              <div x-show="showAll" x-transition class="contents">
                <x-ui.category
                  label="静か"
                  type="button"
                  @click="$store.search.toggleMood('静か')"
                  x-bind:class="$store.search.hasMood('静か') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('静か')"
                    x-cloak
                    src="{{ asset('images/moods/book.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('静か')"
                    x-cloak
                    src="{{ asset('images/moods/book_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="勉強・作業"
                  type="button"
                  @click="$store.search.toggleMood('勉強・作業')"
                  x-bind:class="$store.search.hasMood('勉強・作業') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('勉強・作業')"
                    x-cloak
                    src="{{ asset('images/moods/study.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('勉強・作業')"
                    x-cloak
                    src="{{ asset('images/moods/study_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="長居OK"
                  type="button"
                  @click="$store.search.toggleMood('長居OK')"
                  x-bind:class="$store.search.hasMood('長居OK') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('長居OK')"
                    x-cloak
                    src="{{ asset('images/moods/sofa.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('長居OK')"
                    x-cloak
                    src="{{ asset('images/moods/sofa_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="レトロ・喫茶"
                  type="button"
                  @click="$store.search.toggleMood('レトロ・喫茶')"
                  x-bind:class="$store.search.hasMood('レトロ・喫茶') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('レトロ・喫茶')"
                    x-cloak
                    src="{{ asset('images/moods/retro.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('レトロ・喫茶')"
                    x-cloak
                    src="{{ asset('images/moods/retro_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="デート"
                  type="button"
                  @click="$store.search.toggleMood('デート')"
                  x-bind:class="$store.search.hasMood('デート') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('デート')"
                    x-cloak
                    src="{{ asset('images/moods/love.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('デート')"
                    x-cloak
                    src="{{ asset('images/moods/love_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="女子会"
                  type="button"
                  @click="$store.search.toggleMood('女子会')"
                  x-bind:class="$store.search.hasMood('女子会') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('女子会')"
                    x-cloak
                    src="{{ asset('images/moods/girl.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('女子会')"
                    x-cloak
                    src="{{ asset('images/moods/girl_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="韓国風"
                  type="button"
                  @click="$store.search.toggleMood('韓国風')"
                  x-bind:class="$store.search.hasMood('韓国風') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('韓国風')"
                    x-cloak
                    src="{{ asset('images/moods/korean.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('韓国風')"
                    x-cloak
                    src="{{ asset('images/moods/korean_white.png') }}"
                  >
                </x-ui.category>

                <x-ui.category
                  label="ペットOK"
                  type="button"
                  @click="$store.search.toggleMood('ペットOK')"
                  x-bind:class="$store.search.hasMood('ペットOK') ? '!bg-main !text-form' : ''"
                >
                  <img
                    x-show="!$store.search.hasMood('ペットOK')"
                    x-cloak
                    src="{{ asset('images/moods/pets.png') }}"
                  >
                  <img
                    x-show="$store.search.hasMood('ペットOK')"
                    x-cloak
                    src="{{ asset('images/moods/pets_white.png') }}"
                  >
                </x-ui.category>
              </div>
            </div>

            <div class="flex">
              <button
                type="button"
                @click="showAll = !showAll"
                class="ml-auto text-sm text-text_color"
              >
                <span x-show="!showAll">もっと見る</span>
                <span x-show="showAll">閉じる</span>
              </button>
            </div>
          </section>

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
            <input type="hidden" name="rating_min" :value="$store.search.ratingMin ?? ''">
            <input type="hidden" name="keyword" :value="$store.search.keyword">

            <template x-for="m in $store.search.moods" :key="m">
              <input type="hidden" name="moods[]" :value="m">
            </template>

            <template x-for="t in $store.search.tags" :key="t">
              <input type="hidden" name="tags[]" :value="t">
            </template>
          </form>

          {{-- おすすめ --}}
          <section class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">
              {{ $isSearching ? '検索結果' : 'おすすめのカフェ' }}
            </div>
            @if($isSearching && $stores->isEmpty())
            <div class="text-placeholder text-sm">
              条件に合うカフェが見つかりません
            </div>
            @else
              <div class="grid grid-cols-2 gap-3">
                @foreach($stores as $store)
                  <x-ui.card.store
                    :store="$store"
                    :faved="in_array($store->id, $favIds)"
                    :href="route('user.stores.show', ['store' => data_get($store,'id')])"
                    variant="list"
                  />
                @endforeach
              </div>
            @endif
          </section>

        </div>
      </div>

      
      <x-ui.modal.search :tags="$tags"/>
      <x-ui.modal.area />
      <x-ui.modal.wallet />
      <x-ui.modal.time /> 
      <x-ui.modal.review />
      <x-ui.modal.tag :tags="$tags" />
</div>

@endsection
