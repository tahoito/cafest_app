@extends('layouts.app')
@section('title','トップ')

@section('content')
<div class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-5">

        <section class="px-4">
            <x-ui.search-bar />
        </section>

        <section class="px-4 space-y-2">
            <div class="text-lg text-text font-medium">おすすめのタグ</div>
			<div class="-mx-4 px-4 flex gap-2 overflow-x-auto no-scrollbar">
				<x-ui.tag active>映え</x-ui.tag>
				<x-ui.tag>映え</x-ui.tag>
				<x-ui.tag>映え</x-ui.tag>
				<x-ui.tag>映え</x-ui.tag>
				<x-ui.tag>映え</x-ui.tag>
			</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">おすすめのカフェ</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">みんなのレビュー</div>
            @foreach($reviews as $review)
                <x-ui.review-card :review="$review" />
            @endforeach
        </section>

        <section class="px-4 space-y-3 pb-24">
            <div class="text-lg text-text font-medium">カフェ一覧</div>
        </section>

    </div>
</div>
@endsection
