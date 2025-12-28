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
            <div class="-mx-4 px-3 flex gap-1 overflow-x-auto no-scrollbar items-center">
            <button type="button" @click="activeModal='search'">
                <x-icons.condition/>
            </button>

            <button type="button" @click="activeModal='area'">
                <x-ui.tag>エリア</x-ui.tag>
            </button>

            <button type="button" @click="activeModal='wallet'">
                <x-ui.tag>予算</x-ui.tag>
            </button>

            <button type="button" @click="activeModal='time'">
                <x-ui.tag>営業時間</x-ui.tag>
            </button>

            <button type="button" @click="activeModal='review'">
                <x-ui.tag>レビュー</x-ui.tag>
            </button>

            <button type="button" @click="activeModal='tag'">
                <x-ui.tag>タグ</x-ui.tag>
            </button>
            </div>
        </section>

    {{-- モーダル（全部ここに置いてOK） --}}
        <x-ui.search-modal />
        <x-ui.area-modal />
        <x-ui.wallet-modal />
        <x-ui.time-modal />
        <x-ui.review-modal />
        <x-ui.tag-modal />
    </div>
</div>
@endsection
