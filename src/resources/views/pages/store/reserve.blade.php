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

    <section class="px-4 pt-4">
      
    </section>

  </div>
</div>
@endsection
