@extends('layouts.app')
@section('title','レビュー一覧')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color flex flex-col">


    <header class="sticky top-0 z-50 bg-base_color">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('user.stores.show', $store->id) }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    レビュー一覧
                </h1>

                <div x-data="favoriteFolderModal({{ (int) data_get($store,'id') }}, @js($faved))">
                    <button
                        type="button"
                        class="h-8 w-8 grid place-items-center text-main"
                        aria-label="お気に入り"
                        @click.prevent.stop="toggleAndOpen()"
                    >
                    <x-icons.heart
                            class="w-8 h-8 text-main transition duration-200"
                            x-bind:class="on ? 'fill-main text-main scale-110' : 'fill-transparent text-main scale-100'"
                    />
                    </button>
                    <x-ui.modal.favorite :store="$store" />
                </div>
            </div>
        </div>
    </header>


    <main class="flex-1 overflow-y-auto overscroll-contain">
        <section class="px-4 flex flex-col items-center space-y-[30px] pt-5
            pb-[calc(env(safe-area-inset-bottom)+96px)]">
            @forelse($reviews as $review)
                <x-ui.card.user.store-reviews :review="$review"/>
            @empty
                <div class="text-center text-placeholder py-10">
                    まだレビューがありません
                </div>
            @endforelse
        </section>

        <div class="sticky bottom-0 z-50 px-4
                pt-3 pb-[calc(env(safe-area-inset-bottom)+16px)]">
            <div class="w-full max-w-md mx-auto flex justify-center">
                <a href="{{ route('user.stores.reviews.create', ['store' => data_get($store,'id')]) }}">
                <x-ui.button variant="secondary" class="text-form">
                    レビューを投稿する
                </x-ui.button>
                </a>
            </div>
        </div>
    </main>
</div>
@endsection
