@extends('layouts.app')

@section('content')
@php
$stores = [
  [
    'id' => 1,
    'name' => 'wiik coffee',
    'area' => '栄',
    'rating' => 4.2,
    'mood' => '韓国風',
    'image_url' => null,
  ],
  [
    'id' => 2,
    'name' => 'Cafe 두유',
    'area' => '大須',
    'rating' => 3.8,
    'mood' => '静か',
    'image_url' => null,
  ],
];
@endphp

<div class="px-4">
  <div class="flex gap-3 overflow-x-auto no-scrollbar">
    @foreach($stores as $store)
      <x-ui.store-card
        :store="$store"
        :href="url('/stores/' . data_get($store,'id'))"
        variant="list"
      />
    @endforeach
  </div>
</div>
@endsection
