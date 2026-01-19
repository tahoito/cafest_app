@extends('layouts.app')
@section('title','スライド写真')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full flex flex-col">
    {{-- Header --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.image') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            スライド写真
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    @php
        $slides = $store->slideImages->take(5)->values();
    @endphp

    <div class="flex-1 overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
      <div class="w-full max-w-md mx-auto px-5 pt-6 pb-28 space-y-6">
        <div class="text-center space-y-2">
          <div class="text-sm text-text_color">
            店舗のトップに表示されます（5枚まで）
          </div>
        </div>


        <div class="space-y-6">
            @if ($slides->isEmpty())
                <div class="text-center text-sm text-placeholder">
                    まだ画像が登録されていません
                </div>
            @else
                @foreach ($slides as $img)
                <div class="relative">
                    <div class="overflow-hidden bg-base_color">
                    <img src="{{ asset('images/store/card.png') }}" class="w-full aspect-[16/10] object-cover" alt="">
                    </div>

                    <button
                        type="button"
                        class="absolute -top-3 -right-3 flex items-center justify-center
                                w-[30px] h-[30px] rounded-full bg-accent shadow-sm">
                        <x-icons.close
                            class="w-6 h-6 text-text_color translate-x-[2px] translate-y-[2px]" />
                    </button>
                </div>
                @endforeach
            @endif
            @if ($slides->count() < 5)
                <button type="button" class="w-full text-center text-text_color text-base py-2">
                    + 画像を追加する
                </button>
            @endif
      </div>
    </div>

    <div class="fixed inset-x-0 bottom-0 bg-base_color">
      <div class="pb-[env(safe-area-inset-bottom)]">
        <div class="w-full max-w-md mx-auto px-4 py-4">
          <x-ui.button type="submit" theme="store" class="w-full text-form">
            保存
          </x-ui.button>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
