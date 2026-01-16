@extends('layouts.app')
@section('title','みんなの写真')

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
                    みんなの写真
                </h1> 
                <div>
                    <button 
                        type="button"
                        class="h-8 w-8 grid place-items-center text-main" 
                        aria-label="お気に入り">
                        <x-icons.heart class="w-8 h-8 text-main transition duration-200" />
                    </button> 
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full max-w-md mx-auto px-4 space-y-5">
            <section>
                <div class="grid grid-cols-3 gap-3">
                    @forelse($posts as $post)
                        <button type="button" class="aspect-square overflow-hidden rounded-lg bg-base"
                            @click='window.dispatchEvent(new CustomEvent("review:open",{
                            detail: {
                                reviewId: {{ $post->review_id }},
                                endpoint: "{{ route('user.stores.reviews.show', ['store' => $store->id, 'review' => $post->review_id]) }}?format=json"
                            }
                            }))'
                        >
                            <img src="{{ $post->image }}"
                                alt="review image"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                        </button>
                    @empty
                        <div class="col-span-3 text-center text-placeholder py-10">
                            まだレビュー写真がありません
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <div class="sticky bottom-0 z-50 pb-3 bg-base_color/90 backdrop-blur px-4 pt-3 pb-[calc(env(safe-area-inset-bottom)+16px)]">
        <div class="w-full max-w-md mx-auto flex justify-center">
            <a href="{{ route('user.stores.reviews.create', ['store' => data_get($store,'id')]) }}">
                <x-ui.button variant="secondary" class="text-form">
                    レビューを投稿する
                </x-ui.button>
            </a>
        </div>
    </div>
</div>
@endsection