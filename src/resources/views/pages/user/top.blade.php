@extends('layouts.app')
@section('title','トップ')

@section('content')
<div class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-5">
        <section class="px-4">
            <x-ui.search-bar :action="route('user.search')"/>
        </section>

        <section class="px-4 space-y-2">
            <div class="text-lg text-text_color font-medium">おすすめのタグ</div>
			<div class="-mx-4 px-4 flex gap-2 overflow-x-auto no-scrollbar">
				@foreach($recommendedTags as $tag)
                    <a href="{{ route('user.search', ['tag' => $tag->id]) }}" class="shrink-0">
                    <x-ui.tag>{{ $tag->name }}</x-ui.tag>
                    </a>
                @endforeach
			</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text_color font-medium">おすすめのカフェ</div>
            <div class="grid grid-cols-2 gap-3">
                @foreach($stores as $store)
                <x-ui.card.store
                    :store="$store"
                    :href="route('user.stores.show', ['store' => data_get($store,'id')])"
                    variant="list"
                />
                @endforeach
            </div>

            <div class="flex">
                <a href="{{ route('user.recommended') }}"
                    class="text-sm text-text_color ml-auto">
                    もっと見る
                </a>
            </div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text_color font-medium">みんなのレビュー</div>
            <div class="flex flex-nowrap gap-3 overflow-x-auto pb-6 px-2">
                @foreach($reviews as $review)
                    <x-ui.card.user.review
                        :review="$review" 
                        variant="mini" 
                        class="shrink-0" />
                @endforeach
            </div>
        </section>

        <section class="px-4 space-y-3 pb-24">
            <div class="text-lg text-text_color font-medium">カフェ一覧</div>
            <div class="grid grid-cols-2 gap-3">
                @foreach($stores as $store)
                <x-ui.card.store
                    :store="$store"
                    :href="route('user.stores.show', ['store' => data_get($store,'id')])"
                    variant="list"
                />
                @endforeach
            </div>
        </section>

    </div>
</div>
@endsection
