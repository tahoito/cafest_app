@extends('layouts.app')
@section('title','予約')

@section('content')
<div class="min-h-screen bg-base_color flex flex-col">

    <header class="sticky top-0 z-50 bg-base_color">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('user.signup') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>
                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    予約
                </h1>
                <div></div>
            </div>
        </div>
    </header>

    <section class="px-4 space-y-2">
        <div class="text-lg items-center text-placeholder font-medium">予約状況がまだあります</div>
    </section>
</div>
@endsection