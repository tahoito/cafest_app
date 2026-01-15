@extends('layouts.app')
@section('title','予約一覧')

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="h-16 flex items-center pt-6 justify-center px-4">
                <h1 class="text-center text-2xl text-text_color tracking-wide">
                    予約一覧
                </h1>
                </div>
            </div>
        </header>


        <div class="h-full overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-24">
            <section class="px-4 space-y-3">
                <div class="grid grid-cols-1 justify-items-center gap-5">
                @forelse($reservations as $reservation)
                    <x-ui.card.user.reserve-card 
                        :reservation="$reservation"
                        :onCancel="route('user.reserve.destroy', $reservation->id)" />
                @empty
                    <p class="text-center text-base text-placeholder py-10">
                    予約状況がまだありません
                    </p>
                @endforelse
                </div>
            </section>
            </div>
        </div>
    </div>
</div>
@endsection

