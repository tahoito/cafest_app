@extends('layouts.app')
@section('title','みんなの写真')

@section('content')
@section('hideNavbar')
@endsection

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
        <div class="w-full max-w-md mx-auto space-y-5">
            <section class="px-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="aspect-square overflow-hidden rounded-lg bg-base">
                        <img src="/images/store/image_example.png" class="w-full h-full object-cover">
                    </div>

                    <div class="aspect-square overflow-hidden rounded-lg bg-base">
                        <img src="/images/store/image_example.png" class="w-full h-full object-cover">
                    </div>

                    <div class="aspect-square overflow-hidden rounded-lg bg-base">
                        <img src="/images/store/image_example.png" class="w-full h-full object-cover">
                    </div>

                    <div class="aspect-square overflow-hidden rounded-lg bg-base">
                        <img src="/images/store/image_example.png" class="w-full h-full object-cover">
                    </div>

                    <div class="aspect-square overflow-hidden rounded-lg bg-base">
                        <img src="/images/store/image_example.png" class="w-full h-full object-cover">
                    </div>
                </div>

                
            </section>

            <div class="flex justify-center pt-4">
                <a href="{{ route('user.stores.reviews.create', ['store' => $store->id]) }}">
                    <x-ui.button variant="secondary" class="text-form">
                        レビューを投稿する
                    </x-ui.button>
                </a>
          </div>
        </div>
    </div>
</div>

@endsection