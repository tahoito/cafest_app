@extends('layouts.app')
@section('title','レビュー編集')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color overflow-hidden">
  <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
        <a class="p-2" href="{{ route('user.mycafe') }}">
          <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
          店舗名
        </h1>
      </div>
    </div>
  </header>

  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-4 px-4">
        
        </div>
    </div>
</div>

@endsection 