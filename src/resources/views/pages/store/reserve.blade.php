@extends('layouts.app')
@section('title','予約状況')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto overscroll-contain">
    <header class="sticky top-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.top') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            予約状況
          </h1>

          <div></div>
        </div>
      </div>
    </header>


    <div class="h-full overscroll-contain pt-[calc(env(safe-area-inset-top)+2rem)]">
        <div class="w-full max-w-md mx-auto space-y-5 pb-24">
            <div class="grid grid-cols-1 justify-items-center gap-5">
                @forelse($reservations as $reservation)
                    <x-ui.card.store.reserve
                        :reservation="$reservation" />
                @empty
                    <p class="text-center text-base text-placeholder py-10">
                    予約がまだありません
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
