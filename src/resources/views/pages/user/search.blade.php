@extends('layouts.app')
@section('title','検索')

@section('content')

<div x-data="{ activeModal: null }" class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-5">
        <section class="px-4">
            <x-ui.search-bar />
        </section>

        <section class="px-4 space-y-2">
            <div class="-px-4 flex gap-2 overflow-x-auto no-scrollbar">
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
                <div class="flex justify-center gap-4 flex-wrap">
                    <x-ui.category label="珈琲専門"><x-icons.coffee class="w-7 h-7" /></x-ui.category>
                    <x-ui.category label="紅茶"><x-icons.tea class="w-7 h-7" /></x-ui.category>
                    <x-ui.category label="スイーツ"><x-icons.cake class="w-7 h-7" /></x-ui.category>
                    <x-ui.category label="夜カフェ"><x-icons.moon class="w-7 h-7" /></x-ui.category>

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
                        @click="showAll = !showAll"
                        class="ml-auto text-[14px] text-text_color"
                    >
                        <span x-show="!showAll">もっと見る</span>
                        <span x-show="showAll">閉じる</span>
                    </button>
                </div>
            </div>
        </section>

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
