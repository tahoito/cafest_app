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

              <div x-data="favoriteFolderModal({{ (int) data_get($store,'id') }}, @js($faved))">
                <button
                  type="button"
                  class="h-8 w-8 grid place-items-center text-main"
                  aria-label="お気に入り"
                  @click.prevent.stop="toggleAndOpen()"
                >
                  <x-icons.heart
                    class="w-8 h-8 text-main transition duration-200"
                    x-bind:class="on ? 'fill-main text-main scale-110' : 'fill-transparent text-main scale-100'"
                  />
                </button>
                <x-ui.modal.favorite :store="$store" />
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-4 px-4">
        <section
            x-data="{
                active: 0,
                open: false,
                modalUrl: '',
                openModal(url){ this.modalUrl = url; this.open = true },
                closeModal(){ this.open = false; this.modalUrl = '' },
                go(i){
                this.active = i;
                this.$refs.slider.scrollTo({ left: i * this.$refs.slider.clientWidth, behavior: 'smooth' });
                }
            }"
            class="space-y-3"
            >
            {{-- slider --}}
            <div
                x-ref="slider"
                class="flex gap-3 overflow-x-auto snap-x snap-mandatory scroll-smooth [-webkit-overflow-scrolling:touch]"
                @scroll="active = Math.round($el.scrollLeft / $el.clientWidth)">
                @foreach($menuPhotos as $i => $photo)
                @php
                    $dummy = asset('images/store/menu.png');
                    $url = data_get($photo, 'image_url')
                    ?? (data_get($photo, 'photo_path') ? asset(data_get($photo, 'photo_path')) : null)
                    ?? $dummy;
                @endphp

                <button
                    type="button"
                    class="min-w-full snap-center"
                    @click="openModal('{{ $url }}')"
                    aria-label="メニュー画像を拡大"
                >
                    <img
                    src="{{ $url }}"
                    alt="menu photo"
                    class="w-full aspect-[4/3] object-cover rounded-lg"
                    loading="lazy"
                    />
                </button>
                @endforeach
            </div>
            
            <div class="flex justify-center gap-2 pb-2">
                @foreach($menuPhotos as $i => $photo)
                <button
                    type="button"
                    class="w-[7px] h-[7px] rounded-full transition"
                    :class="active === {{ $i }} ? 'bg-main' : 'bg-accent'"
                    @click="go({{ $i }})"
                    aria-label="slide {{ $i + 1 }}"
                ></button>
                @endforeach
            </div>
       
            <div
            x-show="open"
            x-transition.opacity
            class="fixed inset-0 z-[999] flex items-center justify-center"
            @keydown.escape.window="closeModal()"
            >
            {{-- 背景 --}}
            <div class="absolute inset-0 bg-black/60" @click="closeModal()"></div>
                <button
                    type="button"
                    class="absolute left-3 top-3 z-10"
                    @click="closeModal()"
                    aria-label="閉じる"
                >
                    <x-icons.close class="h-7 w-7 text-text_color" />
                </button>
                <div class="relative w-[393px] rounded-lg p-3">
                    <div class="overflow-hidden bg-black/90">
                        <img
                            :src="modalUrl"
                            alt="menu photo"
                            class="max-h-[80vh] w-full object-contain"
                            loading="lazy"
                        />
                    </div>
                </div>
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

        <div x-data="{ reserveOpen:false }" class="flex justify-center pt-4 pb-4">
            <x-ui.button type="button" variant="secondary" class="text-form" @click="reserveOpen = true">
              このお店で予約する
            </x-ui.button>

            <x-ui.modal.reserve
              :store="$store"
              :action="route('user.stores.reserve.confirm.store', data_get($store, 'id'))"
              x-model="reserveOpen"
            />
        </div>
    </div>
  </div>
</div>

@endsection
