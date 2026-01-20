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

  @php
    use Illuminate\Support\Facades\Storage;

    // 置けるなら public/images/store/menu.png を用意（なくても下のプレースホルダが出る）
    $dummy = asset('images/store/menu.png');
    $hasDummy = file_exists(public_path('images/store/menu.png'));
  @endphp

  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
    <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-4 px-4">

      {{-- menu slider --}}
      <section
        x-data="{
          active: 0,
          open: false,
          modalUrl: '',
          openModal(url){
            if(!url) return;
            this.modalUrl = url;
            this.open = true;
          },
          closeModal(){
            this.open = false;
            this.modalUrl = '';
          },
          go(i){
            this.active = i;
            this.$refs.slider.scrollTo({
              left: i * this.$refs.slider.clientWidth,
              behavior: 'smooth'
            });
          }
        }"
        class="space-y-3"
      >
        <div
          x-ref="slider"
          class="flex gap-3 overflow-x-auto snap-x snap-mandatory scroll-smooth [-webkit-overflow-scrolling:touch]"
          @scroll="active = Math.round($el.scrollLeft / $el.clientWidth)"
        >
          @forelse($menuPhotos as $i => $photo)
            @php
              $url =
                data_get($photo, 'image_url')
                ?? (data_get($photo, 'photo_path') ? Storage::url(data_get($photo, 'photo_path')) : null);

              // image_urlもphoto_pathも無い場合
              if (!$url) {
                $url = $hasDummy ? $dummy : null;
              }
            @endphp

            <button
              type="button"
              class="min-w-full snap-center"
              @click="openModal('{{ $url ?? '' }}')"
              aria-label="メニュー画像を拡大"
            >
              @if($url)
                <img
                  src="{{ $url }}"
                  alt="menu photo"
                  class="w-full aspect-[3/4] object-cover rounded-lg"
                  loading="lazy"
                />
              @else
                <div class="w-full aspect-[3/4] rounded-lg bg-form grid place-items-center text-placeholder">
                  メニュー画像がありません
                </div>
              @endif
            </button>

          @empty
            <div class="min-w-full">
              <div class="w-full aspect-[3/4] rounded-lg bg-form grid place-items-center text-placeholder">
                メニュー画像がありません
              </div>
            </div>
          @endforelse
        </div>

        {{-- dots: 2枚以上のときだけ --}}
        @if($menuPhotos->count() > 1)
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
        @endif

        {{-- modal --}}
        <div
          x-show="open"
          x-cloak
          x-transition.opacity
          class="fixed inset-0 z-[999] flex items-center justify-center"
          @keydown.escape.window="closeModal()"
        >
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
            <div class="overflow-hidden bg-black/90 rounded-lg">
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

      {{-- recommended items --}}
      <section class="space-y-3">
        <div class="text-lg text-text_color font-medium">おすすめ商品</div>
        <div class="grid grid-cols-1 justify-items-center gap-5">
          @foreach($recommendedItems as $item)
            <x-ui.card.user.menu-item :item="$item" />
          @endforeach
        </div>
      </section>

      {{-- reserve --}}
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
