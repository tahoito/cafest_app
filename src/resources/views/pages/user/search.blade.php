@extends('layouts.app')
@section('title','検索')

@section('hideNavbar')
@endsection
@section('content')
<div x-data="{ searchOpen: false }" class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-6 space-y-5">
        <section class="px-4">
            <x-ui.search-bar />
        </section>

        <section class="px-4 space-y-2">
            <div class="-mx-4 px-4 flex gap-2 overflow-x-auto no-scrollbar">
                <x-icons.condition/>
                <x-ui.tag>エリア</x-ui.tag>
                <x-ui.tag>予算</x-ui.tag>
                <x-ui.tag>営業時間</x-ui.tag>
                <x-ui.tag>レビュー</x-ui.tag>
                <x-ui.tag>タグ</x-ui.tag>
            </div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">カテゴリー</div>
        </section>

        <section class="px-4 space-y-3">
            <div class="text-lg text-text font-medium">おすすめのカフェ</div>
        </section>

    </div>
    {{-- モーダル --}}
    <x-ui.search-modal />
</div>
@endsection
