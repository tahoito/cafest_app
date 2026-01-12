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
        <section x-data="{ active: 0 }" class="space-y-3">
        {{-- slider --}}
        <div
            x-ref="slider"
            class="flex gap-3 overflow-x-auto snap-x snap-mandatory scroll-smooth [-webkit-overflow-scrolling:touch]"
            @scroll="active = Math.round($el.scrollLeft / $el.clientWidth)"
        >
            @foreach($menuPhotos as $i => $photo)
            @php
                $dummy = asset('images/store/menu.png');

                $url = data_get($photo, 'image_url')
                ?? (data_get($photo, 'photo_path') ? asset(data_get($photo, 'photo_path')) : null)
                ?? $dummy;

                $id = 'menu-photo-'.$photo->id;
            @endphp

            <a href="#{{ $id }}" class="min-w-full snap-center">
                <img
                src="{{ $url }}"
                alt="menu photo"
                class="w-full aspect-[4/3] object-cover rounded-xl"
                loading="lazy"
                />
            </a>

            {{-- modal (target方式) --}}
            <div id="{{ $id }}" class="fixed inset-0 z-[999] hidden">
                <a href="#" class="absolute inset-0 bg-black/60"></a>

                <div class="relative mx-auto mt-[env(safe-area-inset-top)] h-full max-w-md px-4 pb-6 pt-6">
                <div class="flex justify-end">
                    <a href="#" class="h-10 w-10 grid place-items-center rounded-full bg-white/90">
                    <x-icons.close class="w-6 h-6 text-text_color" />
                    </a>
                </div>

                <div class="mt-3 h-[calc(100vh-140px)] flex items-center justify-center bg-black/90 rounded-xl p-4">
                    <img
                    src="{{ $url }}"
                    alt="menu photo"
                    class="max-h-full max-w-full object-contain rounded-xl"
                    loading="lazy"
                    />
                </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- dots --}}
        <div class="flex justify-center gap-2 py-3">
            @foreach($menuPhotos as $i => $photo)
            <button
                type="button"
                @click="active = {{ $i }}; $refs.slider.scrollTo({ left: {{ $i }} * $refs.slider.clientWidth, behavior: 'smooth' })"
                class="w-[7px] h-[7px] rounded-full transition"
                :class="active === {{ $i }} ? 'bg-main' : 'bg-accent'"
                aria-label="slide {{ $i + 1 }}"
            ></button>
            @endforeach
        </div>
        </section>

        <section class="space-y-3">
            <div class="text-lg text-text_color font-medium">おすすめ商品</div>
            <div class="grid grid-cols-1 justify-items-center gap-5"> 
                @foreach($recommendedItems as $item)
                    <x-ui.card.user.menu-item :item="$item" />
                @endforeach
            </div>
        </section>
    </div>
  </div>
</div>

@endsection
