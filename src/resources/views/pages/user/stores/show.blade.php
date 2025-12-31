@extends('layouts.app')
@section('title', data_get($store,'name','店舗詳細'))


@section('content')
<div class="min-h-screen bg-base relative overflow-hidden">

  {{-- header --}}
  <header class="sticky top-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_auto] items-center px-4 h-16">
        <a class="p-2" href="{{ url()->previous() }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <div class="text-center text-text_color font-medium truncate">
        </div>

        <div class="flex items-center gap-2">
            <button type="button"
            class="h-10 w-10 grid place-items-center"
            aria-label="共有">
            <x-icons.share class="w-8 h-8" />
            </button>

            <button type="button"
            class="h-10 w-10 text-main grid place-items-center"
            aria-label="お気に入り">
            <x-icons.heart class="w-8 h-8" />
            </button>
        </div>
      </div>
    </div>
  </header>

  @php
    $name = data_get($store, 'name', 'No Name');
    $area = (string) data_get($store, 'area', '');
    $mood = (string) data_get($store, 'mood', '');
    $imageUrl = data_get($store, 'image_url');

    $meta = trim($area) !== '' && trim($mood) !== ''
      ? "{$area}・{$mood}"
      : (trim($area) !== '' ? $area : $mood);

    $rating = (float) data_get($store, 'rating', 0);
    $rating = max(0, min(5, $rating));
    $filled = (int) floor($rating + 0.00001);
  @endphp

  <div class="w-full max-w-md mx-auto pt-6 space-y-5">

    {{-- image --}}
    <section class="px-4">
      <div class="rounded-[8px] bg-form ring-1 ring-black/5 overflow-hidden">
        <div class="w-full aspect-[16/10] bg-base">
          <img
            src="{{ $imageUrl ?: asset('images/store/card.png') }}"
            alt="{{ $name }}"
            class="w-full h-full object-cover"
          >
        </div>
      </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="min-w-0 space-y-1">
          <div class="text-2xl text-text_color leading-tight">
            {{ $name }}
          </div>

          <div class="mt-1 flex items-center gap-2">
            <div class="flex items-center gap-1">
              @for ($i = 1; $i <= 5; $i++)
                <x-icons.star class="h-4 w-4 {{ $i <= $filled ? 'text-star' : 'text-placeholder' }}" />
              @endfor
            </div>
            <div class="text-sm text-text_color/70">
              {{ number_format($rating, 1) }}
            </div>
          </div>

          @if(trim($meta) !== '')
            <div class="mt-2 flex items-center text-base leading-base text-text_color">
              <x-icons.pin class="w-5 h-5 shrink-0 text-text_color relative top-[1px]" />
              <span class="min-w-0 line-clamp-1">
                {{ $meta }}
              </span>
            </div>
          @endif
        </div>

        @if(trim(data_get($store,'description','')) !== '')
            <div class="rounded-lg bg-base border border-main shadow-[0_2px_10px_rgba(0,0,0,0.15)] p-3 text-base text-text_color leading-relaxed">
            {{ data_get($store,'description') }}
            </div>
        @endif
      </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="text-lg text-text_color font-medium">ギャラリー</div>
        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" variant="secondary" class="w-full text-form">
                メニューを見る
            </x-ui.button>
        </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="text-lg text-text_color font-medium">みんなのレビュー(100件)</div>
        <div class="flex flex-nowrap gap-3 overflow-x-auto pb-6 px-2">
            @foreach($reviews as $review)
                <x-ui.review-card 
                    :review="$review" 
                    variant="mini" 
                    class="shrink-0" />
            @endforeach
        </div>
        <div class="text-text_color text-[14px]">みんなの写真から見る</div>
        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" variant="secondary" class="w-full text-form">
                レビューを投稿する
            </x-ui.button>
        </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="text-lg text-text_color font-medium">店舗情報</div>
        @if(trim(data_get($store,'description','')) !== '')
            <div class="rounded-lg bg-base border border-main shadow-[0_2px_10px_rgba(0,0,0,0.15)] p-3 text-base text-text_color leading-relaxed">
            {{ data_get($store,'description') }}
            </div>
        @endif
        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" variant="secondary" class="w-full text-form">
                このお店で予約する
            </x-ui.button>
        </div>
    </section>
  </div>
</div>
@endsection
