@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color overflow-hidden">
    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_auto] items-center px-4 h-16">
            <a class="p-2" href="{{ url()->previous() }}">
                <x-icons.back class="w-5 h-5 text-text_color" />
            </a>

            <div class="text-center text-text_color text-2xl">
                メニュー
            </div>

            <div class="flex items-center gap-1.5">
              <button type="button" class="h-8 w-8 grid place-items-center text-text_color" aria-label="共有">
              <x-icons.share class="w-8 h-8" />
              </button>

              <button type="button" class="h-8 w-8 grid place-items-center text-main" aria-label="お気に入り">
              <x-icons.heart class="w-8 h-8" />
              </button>
          </div>
        </div>
      </div>
    </header>

  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
    <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-4 px-4">

        <section class="space-y-2 pb-3">
            @foreach($recommendedItems as $item)
                <x-ui.card.user.menu-item :item="$item" />
            @endforeach
        </section>
    </div>
  </div>
</div>

@endsection
