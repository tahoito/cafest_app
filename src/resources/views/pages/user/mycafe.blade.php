@extends('layouts.app')
@section('title','マイカフェページ')

@section('content')
<div class="relative px-4 pt-6 pb-6 bg-base_color">

  <div>
    @php
      use Illuminate\Support\Facades\Storage;

      $iconUrl = $user->icon_path
        ? Storage::url($user->icon_path)
        : Storage::url('users/user1.jpg');
    @endphp

    <a href="{{ route('user.mycafe.edit') }}">
      <div class="absolute top-6 right-4 text-xs text-text flex items-center gap-1">
        <x-icons.edit class="w-[15px] h-[15px]" />編集
      </div>
    </a>

    <div class="flex items-start gap-3 mt-6">
      <img
        src="{{ $iconUrl }}"
        class="w-[73px] h-[73px] rounded-full object-cover"
        alt="ユーザーアイコン"
      >

      <div class="flex-1">
        <div class="rounded-lg ring-1 ring-main bg-base_color px-2 py-3 text-sm text-text_color">
          <p class="mb-2">
            ユーザー名：{{ $user->name }}
          </p>
          <p class="break-all">
            メールアドレス：{{ $user->email }}
          </p>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection