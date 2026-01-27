@extends('layouts.app')
@section('title', data_get($store,'name','店舗詳細'))

@section('content')
@section('hideNavbar')
@endsection
<div class="h-screen bg-base">

  <div class="h-full overflow-y-auto pt-16">
    {{-- header --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_auto] items-center px-4 h-16">
          <a
            href="{{ route('user.top') }}"
            class="p-2"
          >
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>
          <div class="text-center text-text_color font-medium truncate">
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

    @php
      $name = data_get($store, 'name', 'No Name');
      $areaKey = (string) data_get($store, 'area', '');
      $area = $areaKey !== '' ? (config('cafest.areas')[$areaKey] ?? $areaKey) : '';
      $phone = trim((string) data_get($store,'phone',''));
      $address = trim((string) data_get($store,'address',''));
      $mood = (string) data_get($store, 'mood', '');
      $meta = trim($area) !== '' && trim($mood) !== ''
        ? "{$area}・{$mood}"
        : (trim($area) !== '' ? $area : $mood);

      $rating = (float) data_get($store, 'rating', 0);
      $rating = max(0, min(5, $rating));
      $filled = (int) floor($rating + 0.00001);

      $dayNames = ['日','月','火','水','木','金','土'];
      $hours = collect(data_get($store,'hours',[]))->sortBy('day_of_week');
      $groups = $hours->map(function ($h) use ($dayNames){
        $dow = (int) data_get($h, 'day_of_week');
        $isClosed = (bool) data_get($h, 'is_closed', false);
        $open = data_get($h,'open_time');
        $close = data_get($h,'close_time');

        $key = $isClosed ? 'CLOSED' : "{$open}-{$close}";

        return [
          'dow' => $dow,
          'label' => $dayNames[$dow] ?? (string)$dow,
          'key' => $key,
          'isClosed' => $isClosed,
          'open' => $open,
          'close' => $close,
        ];
      })->groupBy('key')->values();
    @endphp

    <div class="w-full max-w-md mx-auto pt-6 space-y-5">
      {{-- image --}}
      <section class="px-4" x-data="{ active:0 }">
        <div class="overflow-hidden bg-base">
          {{-- image --}}
          <div class="relative w-full aspect-[16/10] overflow-hidden">
            <div
              class="flex h-full transition-transform duration-300 ease-out"
              :style="`transform: translateX(-${active * 100}%);`"
            >
              @foreach($store->slideImages as $img)
                <div class="w-full h-full flex-shrink-0">
                  <img src="{{ $img->url }}" class="w-full h-full object-cover rounded-[8px]" />
                </div>
              @endforeach
            </div>
          </div>

          {{-- dots --}}
          <div class="flex justify-center gap-2 py-3">
            @foreach($store->slideImages as $i => $img)
              <button @click="active={{ $i }}"
                class="w-[7px] h-[7px] rounded-full transition"
                :class="active === {{ $i }} ? 'bg-main' : 'bg-accent'"></button>
            @endforeach
          </div>
        </div>
      </section>

      <section class="px-4 space-y-2 pb-6">
          <div class="min-w-0 space-y-1">
            <div class="flex items-center gap-3">
              <div class="text-2xl text-text_color leading-tight">
                {{ $name }}
              </div> 
              <div class="h-[30px] w-[30px] flex items-center justify-center">
                <x-icons.instagram size="30" class="text-main block" />
              </div>
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
              <div class="mt-2 flex items-center text-base leading-base text-text_color pt-2">
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
              @foreach($store->galleryImages as $img)
              <div class="aspect-square overflow-hidden rounded-lg bg-base">
                  <img src="{{ $img->url }}" class="w-full h-full object-cover">
              </div>
              @endforeach
          </div>
          
          <div class="flex justify-center pt-4">
            <a href="{{ route('user.stores.menu', $store) }}">
              <x-ui.button variant="secondary" class="text-form">
                メニューを見る
              </x-ui.button>
            </a>
          </div>
      </section>

      <section class="px-4 space-y-2 pb-12">
          <div class="flex items-center justify-between">
              <div class="text-lg text-text_color font-medium">みんなのレビュー({{ $reviewCount }}件)
              </div>

              <a href="{{ route('user.stores.reviews', data_get($store,'id')) }}"
              class="text-sm text-main hover:text-text_color">
              一覧 →
              </a>
          </div>
          <div class="flex flex-nowrap gap-3 overflow-x-auto pb-4 px-2">
            @forelse($reviews as $review)
              @php
                $payload = [
                  'reviewId' => (int) $review->id,
                  'endpoint' => route('user.stores.reviews.show', [
                    'store' => $store->id,
                    'review' => $review->id,
                  ]) . '?format=json',
                ];
              @endphp

              <button type="button" class="shrink-0 cursor-pointer"
                @click.prevent.stop="window.dispatchEvent(new CustomEvent('review:open',{ detail: @js($payload) }))"
              >
                <x-ui.card.user.review :review="$review" variant="mini" />
              </button>
            @empty
              <div class="col-span-3 text-center text-placeholder py-10">
                まだレビューがありません
              </div>
            @endforelse
          </div>

          <div class="flex items-center justify-between pt-2">
              <div class="text-text_color text-sm">みんなの写真から見る</div>

              <a href="{{ route('user.stores.posts', data_get($store,'id')) }}"
              class="text-sm text-main hover:text-text_color">
              すべて →
              </a>

          </div>
          <div class="grid grid-cols-3 gap-3">
              @forelse($posts as $post)
                @php
                  $payload = [
                    'reviewId' => (int) $post->review_id,
                    'endpoint' => route('user.stores.reviews.show', [
                      'store' => $store->id,
                      'review' => $post->review_id,
                    ]) . '?format=json',
                  ];
                @endphp
                <button type="button" class="aspect-square overflow-hidden rounded-lg bg-base"
                  @click.prevent.stop="window.dispatchEvent(new CustomEvent('review:open',{ detail: @js($payload) }))"
                >
                  <img src="{{ $post->image }}" alt="review image" class="w-full h-full object-cover" loading="lazy">
                </button>
              @empty
                <div class="col-span-3 text-center text-placeholder py-10">
                  まだレビュー写真がありません
                </div>
              @endforelse
          </div>

          <div class="flex justify-center pt-4">
            <a href="{{ route('user.stores.reviews.create', ['store' => $store->id]) }}">
              <x-ui.button variant="secondary" class="text-form">
                  レビューを投稿する
              </x-ui.button>
            </a>
          </div>
      </section>

      <section class="px-4 space-y-2 pb-12">
          <div class="text-lg text-text_color font-medium">店舗情報</div>
              <div class="rounded-2xl bg-base border border-main shadow-[0_2px_10px_rgba(0,0,0,0.15)] p-5 text-text_color">
                  <div class="space-y-5">

                  <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                    <div class="text-lg font-medium text-text_color">営業時間</div>

                    <div class="text-base leading-[1.9] space-y-2">
                      @forelse($groups as $g)
                        @php
                          $days = $g->pluck('label')->all();
                          $daysText = implode('・', $days);

                          $first = $g->first();
                          $isClosed = (bool) data_get($first, 'isClosed', false);
                          $open = data_get($first, 'open');
                          $close = data_get($first, 'close');
                          $openText  = $open ? substr((string)$open, 0, 5) : null;   
                          $closeText = $close ? substr((string)$close, 0, 5) : null; 
                        @endphp

                        <div>
                          <p>{{ $daysText }}</p>
                          <p>
                            @if($isClosed)
                              定休日
                            @else
                              {{ $openText }}〜{{ $closeText }}
                            @endif
                          </p>
                        </div>
                      @empty
                        <div class="text-text_color">営業時間情報なし</div>
                      @endforelse
                    </div>
                  </div>

                  <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                      <div class="text-lg font-medium text-text_color">予算</div>
                      <div class="text-base leading-[1.9]">
                      @if($store->budget_min === 0 && $store->budget_max !== null)
                        <p>〜{{ number_format($store->budget_max) }}円</p>

                      @elseif($store->budget_min !== null && $store->budget_max !== null)
                        <p>{{ number_format($store->budget_min) }}円〜{{ number_format($store->budget_max) }}円</p>

                      @elseif($store->budget_min !== null && $store->budget_max === null)
                        <p>{{ number_format($store->budget_min) }}円〜</p>

                      @else
                        <p class="text-placeholder">未設定</p>
                      @endif
                      </div>
                  </div>

                  <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                      <div class="text-lg font-medium text-text_color">支払い方法</div>
                      <div class="text-base leading-[1.9] space-y-1">
                        @foreach($store->paymentMethods as $pm)
                          <p>{{ $pm->name }}</p>
                        @endforeach
                      </div>
                  </div>

                  <div class="grid grid-cols-[120px_1fr] gap-x-6 items-start">
                      <div class="text-lg font-medium text-text_color">電話番号</div>
                      <div class="text-base leading-[1.9]">
                      @if($phone !== '')
                        <a href="tel:{{ preg_replace('/\D+/', '', data_get($store,'phone')) }}" class="underline decoration-line/60">
                          {{ $phone }}
                        </a>
                      @endif
                      </div>
                  </div>

                  </div>
              </div>
          </section>

          <section class="px-4 space-y-2 flex pb-4 justify-center">
            <div class="text-center">
              @if($address != '')
                <div class="text-text_color text-base leading-relaxed">
                  {{ $address }}
                </div>
              @endif
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
</div>


@if (session('success'))
  <div
    x-data="{
      open: true,
      timer: null,
      start() { this.timer = setTimeout(() => { this.open = false }, 3000) },
      close() { this.open = false; if (this.timer) clearTimeout(this.timer) },
    }"
    x-init="start()"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-[200] flex items-center justify-center"
  >
    {{-- 背景 --}}
    <div class="absolute inset-0 bg-black/40" @click="close()"></div>

    {{-- カード --}}
    <div class="relative w-[353px] rounded-lg bg-base_color px-6 py-6">
      <button
        type="button"
        class="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-full hover:bg-black/5 active:scale-95"
        @click="close()"
        aria-label="閉じる"
      >
        <x-icons.close class="h-7 w-7 text-text_color" />
      </button>

      <div class="text-center pt-4">
        <div class="text-xl text-text_color">
          予約完了しました！！
        </div>

        <div class="mt-2 text-sm text-text_color">
          ご来店お待ちしています
        </div>

        <x-ui.button
          href="{{ route('user.reserve') }}"
          variant="secondary"
          class="mt-6 text-form"
          @click="close()"
        >
          予約を確認する
        </x-ui.button>
      </div>
    </div>
  </div>
@endif

@endsection