@extends('layouts.app')
@section('title','検索')

@section('hideNavbar')
@endsection
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
                    class="!px-0 !w-10 !h-7 !rounded-full flex items-center justify-center"
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

        <section class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">カテゴリー</div>
                <div class="-mx-4 px-4 flex gap-3 overflow-x-auto no-scrollbar">
                    <x-ui.category label="珈琲専門">
                    <x-icons.coffee class="w-7 h-7" />
                    </x-ui.category>

                    <x-ui.category label="紅茶">
                    <x-icons.tea class="w-7 h-7" />
                    </x-ui.category>

                    <x-ui.category label="スイーツ">
                    <x-icons.cake class="w-7 h-7" />
                    </x-ui.category>

                    <x-ui.category label="夜カフェ">
                    <x-icons.moon class="w-7 h-7" />
                    </x-ui.category>
                </div>
        </section>

        <section class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">おすすめのカフェ</div>
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
