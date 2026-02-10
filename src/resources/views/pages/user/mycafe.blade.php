@extends('layouts.app')
@section('title','マイカフェページ')

@section('content')
<div class="relative pt-6 pb-6 bg-base_color">

  <div class="px-4">
    @php
      use Illuminate\Support\Facades\Storage;

      $iconPath = $user->icon_path;

      if ($iconPath && str_starts_with($iconPath, '/images/')) {
          $iconUrl = asset(ltrim($iconPath, '/'));
      }
      
      elseif ($iconPath && str_starts_with($iconPath, '/storage/')) {
          $iconUrl = $iconPath;
      }
      
      elseif ($iconPath) {
          $iconUrl = Storage::url($iconPath); // => /storage/...
      }
     
      else {
          $iconUrl = asset('images/users/user01.png');
      }
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

  <div x-data="{ tab: 'favorite' }" class="mt-8">
    <div class="flex items-end border-b border-line">
      <button
        type="button"
        @click="tab='favorite'"
        class="rounded-t-2xl transition-all duration-100"
        :class="
          tab === 'favorite'
            ? 'bg-main text-form w-[154px] text-lg py-3'
            : 'bg-accent text-main w-[120px] text-base py-2'
        "
      >
        お気に入り
      </button>

      <button
        type="button"
        @click="tab='review'"
        class="rounded-t-2xl transition-all duration-100"
        :class="
          tab === 'review'
            ? 'bg-main text-form w-[154px] text-lg py-3'
            : 'bg-accent text-main w-[120px] text-base py-2'
        "
      >
        レビュー
      </button>

      <button
        type="button"
        @click="tab='history'"
        class="rounded-t-2xl transition-all duration-100"
        :class="
          tab === 'history'
            ? 'bg-main text-form w-[154px] text-lg py-3'
            : 'bg-accent text-main w-[120px] text-base py-2'
        "
      >
        閲覧履歴
      </button>
    </div>

    <div class="pt-4">
      <div x-show="tab==='favorite'" x-cloak>
        @include('pages.user.mycafe.favorites')
      </div>
      <div x-show="tab==='review'" x-cloak>
        @include('pages.user.mycafe.reviews')
      </div>
      <div x-show="tab==='history'" x-cloak>
        @include('pages.user.mycafe.histories')
      </div>
    </div>
  </div>
</div>
@endsection