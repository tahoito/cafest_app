@extends('layouts.app')
@section('title','レビュー一覧')

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
            レビュー一覧
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    {{-- サマリー --}}
    <section class="px-4 pt-4">
      <div class="max-w-md mx-auto rounded-2xl bg-form p-4 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
        <div class="space-y-4">
          <div class="grid grid-cols-[96px_1fr] items-center gap-3">
            <div class="text-text_color text-base font-medium">平均評価</div>

            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1">
                <x-icons.star class="h-6 w-6" />
                <x-icons.star class="h-6 w-6" />
                <x-icons.star class="h-6 w-6" />
                <x-icons.star class="h-6 w-6" />
                <x-icons.star class="h-6 w-6" />
              </div>
              <div class="text-text_color text-base font-medium">({{ number_format($avgRating, 1) }})</div>
            </div>
          </div>

          <div class="grid grid-cols-[96px_1fr] items-center gap-3">
            <div class="text-text_color text-base font-medium">レビュー数</div>
            <div class="text-text_color text-base">{{ $reviewCount }}件</div>
          </div>

          <div class="grid grid-cols-[96px_1fr] items-center gap-3">
            <div class="text-text_color text-base font-medium">今週の新規</div>
            <div class="text-text_color text-base">{{ $thisWeekCount }}件</div>
          </div>
        </div>
      </div>
    </section>

    <section class="px-4 pt-3">
        <div class="max-w-md mx-auto">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('store.reviews', ['filter' => 'all']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='all'">すべて</x-ui.tag>
                </a>

                <a href="{{ route('store.reviews', ['filter' => '5']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='5'">
                    <span class="inline-flex items-center gap-1">
                        <x-icons.star class="h-4 w-4 text-star" />5.0
                    </span>
                    </x-ui.tag>
                </a>

                <a href="{{ route('store.reviews', ['filter' => '4']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='4'">
                    <span class="inline-flex items-center gap-1">
                        <x-icons.star class="h-4 w-4 text-star" />4.0
                    </span>
                    </x-ui.tag>
                </a>

                <a href="{{ route('store.reviews', ['filter' => '3']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='3'">
                    <span class="inline-flex items-center gap-1">
                        <x-icons.star class="h-4 w-4 text-star" />3.0以下
                    </span>
                    </x-ui.tag>
                </a>

                <a href="{{ route('store.reviews', ['filter' => 'with_photo']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='with_photo'">画像あり</x-ui.tag>
                </a>

                <a href="{{ route('store.reviews', ['filter' => 'no_photo']) }}">
                    <x-ui.tag tone="main2" :active="$filter==='no_photo'">画像なし</x-ui.tag>
                </a>
            </div>
        </div>
    </section>
    

    <section class="px-4 py-6">
      <div class="space-y-4 max-w-md mx-auto">
        @forelse($reviews as $review)
          <x-ui.card.store.review :review="$review" class="mx-auto" />
        @empty
          <div class="text-center text-placeholder py-10">
            まだレビューがありません
          </div>
        @endforelse
      </div>
    </section>

  </div>
</div>
@endsection
