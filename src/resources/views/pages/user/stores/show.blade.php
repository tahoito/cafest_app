@extends('layouts.app')
@section('title', data_get($store,'name','店舗詳細'))


@section('content')
<div
    x-data="{ reserveOpen:false }"
    class="min-h-screen bg-base relative overflow-hidden">

  {{-- header --}}
  <header class="sticky top-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_auto] items-center px-4 h-16">
        <a class="p-2" href="{{ url()->previous() }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <div class="text-center text-text_color font-medium truncate">
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
        <div class="grid grid-cols-3 gap-3">
            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>
        </div>
        
        <div class="flex justify-center pt-4">
            <x-ui.button type="submit" variant="secondary" class="text-form">
                メニューを見る
            </x-ui.button>
        </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="flex items-center justify-between">
            <div class="text-lg text-text_color font-medium">みんなのレビュー(100件)</div>

            <a href="#"
            class="text-sm text-main hover:text-text_color">
            一覧 →
            </a>
        </div>
        <div class="flex flex-nowrap gap-3 overflow-x-auto pb-4 px-2">
            @foreach($reviews as $review)
                <x-ui.card.user.review
                    :review="$review" 
                    variant="mini" 
                    class="shrink-0" />
            @endforeach
        </div>

        <div class="flex items-center justify-between pt-2">
            <div class="text-text_color text-sm">みんなの写真から見る</div>

            <a href="#"
            class="text-sm text-main hover:text-text_color">
            すべて →
            </a>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>

            <div class="aspect-square overflow-hidden rounded-lg bg-base">
                <img src="/images/store/image_example.png" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="flex justify-center pt-4">
            <x-ui.button type="submit" variant="secondary" class="text-form">
                レビューを投稿する
            </x-ui.button>
        </div>
    </section>

    <section class="px-4 space-y-2 pb-12">
        <div class="text-lg text-text_color font-medium">店舗情報</div>

            <div class="rounded-2xl bg-base border border-main shadow-[0_2px_10px_rgba(0,0,0,0.15)] p-5 text-text_color">
                <div class="space-y-8">

                <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                    <div class="text-lg font-medium text-text_color">営業時間</div>
                    <div class="text-base leading-[1.9] space-y-2">
                    <div>
                        <p>月・水・木・金</p>
                        <p>10:00—18:00</p>
                    </div>
                    <div>
                        <p>土・日</p>
                        <p>9:00—19:00</p>
                    </div>
                    <div>
                        <p>火</p>
                        <p>定休日</p>
                    </div>
                    </div>
                </div>

                <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                    <div class="text-lg font-medium text-text_color">予算</div>
                    <div class="text-base leading-[1.9]">
                    <p>1,000円 — 2,000円</p>
                    </div>
                </div>

                <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                    <div class="text-lg font-medium text-text_color">支払い方法</div>
                    <div class="text-base leading-[1.9] space-y-1">
                    <p>現金・電子マネー可</p>
                    <p>クレジットカード不可</p>
                    </div>
                </div>

                <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                    <div class="text-lg font-medium text-text_color">電話番号</div>
                    <div class="text-base leading-[1.9]">
                    <a href="tel:09012345678" class="underline decoration-line/60">
                        090-1234-5678
                    </a>
                    </div>
                </div>

                </div>
            </div>

            <div class="flex justify-center pt-4">
                <x-ui.button :type="'button'" variant="secondary" class="text-form" @click="reserveOpen=true">
                このお店で予約する
                </x-ui.button>

                <x-ui.modal.reserve
                :store="$store"
                :action="route('user.stores.reserve.confirm.store', data_get($store, 'id'))"
                />
            </div>
        </section>

    </div>
</div>
@endsection
