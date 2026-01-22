@extends('layouts.app')
@section('title','おすすめのカフェ')

@section('content')
<div class="relative px-4 pt-6 pb-6 bg-base_color">

  {{-- 編集ボタン --}}
  <div class="absolute top-4 right-4 text-xs text-text flex items-center gap-1">
    <x-icons.edit class="w-6 h-6" />編集
  </div>

  <div class="flex items-start gap-4 mt-6">

    {{-- ユーザーアイコン --}}
    <img
      src="/images/users/user1.jpg"
      class="w-[73px] h-[73px] rounded-full object-cover"
    >

    {{-- ユーザー情報（ベタ書き） --}}
  <div class="flex-1">
    <div class="rounded-xl ring-1 ring-main bg-base_color px-4 py-3 text-sm text-text leading-relaxed">
      <p class="mb-2">
        ユーザー名：cafest_vantan
      </p>
      <p>
        メールアドレス：cafest@gmail.com
      </p>
    </div>
  </div>


  </div>
</div>
@endsection