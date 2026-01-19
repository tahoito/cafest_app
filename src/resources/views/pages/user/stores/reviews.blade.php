@extends('layouts.app')
@section('title','レビュー一覧')

@section('content')
@section('hideNavbar')
@endsection


<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('user.top') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    レビュー一覧
                </h1>
                <button type="button" class="h-8 w-8 grid place-items-center text-main" aria-label="お気に入り">
                <x-icons.heart class="w-8 h-8" />
                </button>
                </div>
            </div>
        </header>

      <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
          <section class="px-4 flex flex-col space-y-[30px]">
          @foreach($reviews as $review)
            <x-ui.card.user.store-reviews :review="$review"/>
          @endforeach
          </section>
        </div>
      </div>


      <div class="flex justify-center pt-4">
        <a href="#">
            <x-ui.button variant="secondary" class="text-form">
                レビューを書く
            </x-ui.button>
        </a>
      </div>
    </div>
</div>
@endsection