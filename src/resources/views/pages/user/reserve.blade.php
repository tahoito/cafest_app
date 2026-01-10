@extends('layouts.app')
@section('title','予約')

@section('content')
<div class="min-h-screen bg-base_color">
    <x-ui.reserve-card
    :reservation="[
        'shop_name' => 'Cafe Latte',
        'image_url' => 'https://picsum.photos/400/300',
        'reserved_date' => '2026-01-05',
        'reserved_time' => '18:30',
        'guest_count' => 2,
        'id' => 1,
    ]"
    />
</div>
@endsection

@section('content')
<header class="sticky top-0 z-50 bg-base_color">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
            <a class="p-2" href="{{ route('user.top') }}">
                <x-icons.back class="w-5 h-5 text-text_color" />
            </a>

            <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                おすすめのカフェ
            </h1>
            </div>
        </div>
    </header>
<div class="min-h-screen bg-base_color">
    <div class="w-full max-w-md mx-auto pt-4 space-y-5">

        <section class="px-4 space-y-3">
            <div class="grid grid-cols-2 gap-3">
                @foreach($reservations as $reservation)
                <x-ui.reserve-card :reservation="$reservation" />
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection