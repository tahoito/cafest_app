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

    <section class="px-4 pt-4">
      <div class="max-w-md mx-auto rounded-2xl bg-form p-4 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
        <div class="space-y-4">
          <div class="grid grid-cols-[96px_1fr] items-center gap-3">
            <div class="text-text_color text-base font-medium">平均評価</div>

            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1">
                @php $avgStars = floor($avgRating); @endphp 

                @for ($i = 1; $i <= 5; $i++)
                  <x-icons.star class="h-6 w-6 {{ $i <= $avgStars ? 'text-star' : 'text-placeholder' }}" />
                @endfor
              </div>
              <div class="text-text_color text-base">({{ number_format($avgRating, 1) }})</div>
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

@php 
  $filters = [
    'all' => 'すべて',
    '5' => '5.0',
    '4' => '4.0',
    '3' => '3.0以下',
    'with_photo' => '画像あり',
    'no_photo' => '画像なし',
  ];

  // 選択中を先頭へ（all以外）
  if ($filter !== 'all' && isset($filters[$filter])) {
    $filters = [$filter => $filters[$filter]] + $filters;
  }
@endphp

    <section class="px-4 pt-[27px]">
      <div class="max-w-md mx-auto">
        <div class="flex flex-wrap gap-2">
          @foreach($filters as $key => $label)
            <x-ui.tag tone="main2" :active="$filter == $key"
              :href="route('store.reviews', ['filter' => $key])"
            >
              @if(in_array($key, ['5','4','3']))
                <span class="inline-flex items-center gap-1">
                  <x-icons.star class="{{ $filter == $key ? 'h-5 w-5 text-star' : 'h-4 w-4 text-star' }}"></x-icons.star>
                  {{ $label }}
                </span>
              @else
                {{ $label }}
              @endif
            </x-ui.tag>
          @endforeach
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
