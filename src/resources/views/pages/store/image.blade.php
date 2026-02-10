@extends('layouts.app')
@section('title','公式写真')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto">
    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.top') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            公式写真
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    @php
      $toPublicUrl = function ($path) {
        if (!$path) return null;

        // すでに public 側URLならそのまま
        if (str_starts_with($path, '/storage/')) return $path;

        // "storage/xxx" / "/storage/xxx" を正規化
        $path = preg_replace('#^storage/#', '', ltrim($path, '/'));

        return \Illuminate\Support\Facades\Storage::url($path);
      };
    @endphp


    <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
      <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">

        {{-- スライド --}}
        <section class="px-4 space-y-3">
          @php
            $slides = $store->slideImages->take(5)->values();
            $slideMax = 5;
          @endphp

          <div class="flex items-center justify-between">
            <div>
              <div class="text-lg text-text_color">スライド写真（5枚まで）</div>
              <div class="mt-1 text-sm text-text_color">店舗のトップに表示されます</div>
            </div>
            <a href="{{ route('store.image.edit.slide') }}"
              class="mt-10 flex items-center gap-1 text-sm text-text_color hover:opacity-80">
              <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集
            </a>
          </div>

          <div class="rounded-lg border border-favorite bg-base_color p-3">
            <div class="grid grid-cols-2 gap-3">
              @for ($i = 0; $i < $slideMax; $i++)
                @php $img = $slides[$i] ?? null; @endphp

                <div class="space-y-1">
                  <div class="text-main2 text-sm {{ ($img && $img->is_used_on_card) ? '' : 'invisible' }}">
                    店舗カードで使用中
                  </div>

                  @if($img)
                    <form method="POST" action="{{ route('store.slide.card', $img->id) }}">
                      @csrf
                      @method('PATCH')

                      <button type="submit" class="block w-full p-0 m-0 bg-transparent border-0 text-left focus:outline-none">
                        <div class="overflow-hidden rounded-xl border border-placeholder-color/40 bg-white">
                          <img
                            src="{{ $toPublicUrl($img->path) }}"
                            class="w-full aspect-[16/10] object-cover"
                            alt=""
                          >
                        </div>
                      </button>
                    </form>
                  @else
                    <a href="#"
                      class="grid place-items-center rounded-xl border border-placeholder bg-notification2 aspect-[16/10]">
                      <x-icons.no_image class="w-6 h-6 text-text_color" />
                    </a>
                  @endif
                </div>
              @endfor
            </div>
          </div>

          <div class="mt-2 text-sm text-placeholder text-right">
            最終更新{{ optional($store->slide_updated_at)->format('n/j') }}
          </div>
        </section>

        {{-- ギャラリー --}}
        <section class="px-4 space-y-3">
          @php
            $gallery = $store->galleryImages->take(6)->values();
            $galleryMax = 6;
          @endphp

          <div class="flex items-center justify-between">
            <div>
              <div class="text-lg text-text_color">ギャラリー画像（6枚まで）</div>
              <div class="mt-1 text-sm text-text_color">店舗の雰囲気が伝わる写真を登録してください</div>
            </div>
            <a href="{{ route('store.image.edit.gallery') }}"
              class="mt-10 flex items-center gap-1 text-sm text-text_color hover:opacity-80">
              <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集
            </a>
          </div>

          <div class="rounded-lg border border-favorite bg-base_color p-3">
            <div class="grid grid-cols-3 gap-3">
              @for ($i = 0; $i < $galleryMax; $i++)
                @php $img = $gallery[$i] ?? null; @endphp

                @if($img)
                  <div class="overflow-hidden rounded-lg border border-placeholder bg-form">
                    <img
                      src="{{ $toPublicUrl($img->path) }}"
                      class="w-full aspect-square object-cover"
                      alt=""
                    >
                  </div>
                @else
                  <a href="#"
                    class="grid place-items-center rounded-xl border border-placeholder bg-notification2 aspect-square hover:bg-white/70">
                    <x-icons.no_image class="w-6 h-6 text-placeholder" />
                  </a>
                @endif
              @endfor
            </div>
          </div>

          <div class="mt-2 text-sm text-placeholder text-right">
            最終更新{{ optional($store->gallery_updated_at)->format('n/j') }}
          </div>
        </section>

      </div>
    </div>
  </div>
</div>
@endsection
