@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-4 space-y-4">
  <div class="text-xl font-semibold">予約確認</div>

  <div class="rounded-lg bg-white p-4">
    <div class="font-medium">{{ $store->name }}</div>
    <div>日付：{{ data_get($data, 'date') }}</div>
    <div>時間：{{ data_get($data, 'time') }}</div>
    <div>人数：{{ data_get($data, 'people') }}</div>
    <div>要望：{{ data_get($data, 'note') }}</div>
  </div>

  <form method="POST" action="{{ route('user.stores.reserve.store', $store) }}">
    @csrf
    @foreach($data as $k => $v)
      <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach

    <button class="w-full py-3 rounded bg-black text-white">この内容で予約する</button>
  </form>
</div>
@endsection
