@extends('layouts.app')
@section('title','トップ')

@section('content')
<div class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-10">

        <section class="px-4">
            <x-ui.search-bar />
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">おすすめのタグ</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">おすすめのカフェ</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">みんなのレビュー</div>
        </section>

        <section class="px-4 space-y-3 pb-24">
            <div class="text-lg text-text font-medium">カフェ一覧</div>
        </section>

    </div>
</div>
@endsection
