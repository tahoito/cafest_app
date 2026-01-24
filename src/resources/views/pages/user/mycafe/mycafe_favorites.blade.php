@extends('layouts.app')

@section('content')
  <div class="px-4">

    <div class="mb-3">
      <a href="{{ route('user.mycafe', ['tab' => 'favorites']) }}"
         class="text-sm text-text_color underline">
        戻る
      </a>
    </div>

    <div class="mb-3 text-text_color text-lg">
      {{ $title ?? '' }}
    </div>

    @if (($stores ?? collect())->isEmpty())
      <div class="text-placeholder text-sm">
        このフォルダにはまだありません
      </div>
    @else
      <div class="grid grid-cols-2 gap-3">
        @foreach ($stores as $store)
          <x-ui.card.store
            :store="$store"
            :faved="in_array(data_get($store,'id'), $favIds)"
            :href="route('user.stores.show', ['store' => data_get($store,'id')])"
            variant="grid"
          />
        @endforeach
      </div>
    @endif

  </div>
@endsection
