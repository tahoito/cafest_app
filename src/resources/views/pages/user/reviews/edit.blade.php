@extends('layouts.app')
@section('title','レビュー編集')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color overflow-hidden">
  <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
        <a class="p-2" href="{{ route('user.mycafe') }}">
          <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
          wiik coffee
        </h1>
      </div>
    </div>
  </header>

  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
    <div class="w-full max-w-md mx-auto px-4 pt-4 space-y-6 pb-10">

      {{-- ユーザー --}}
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <img src="" class="w-10 h-10 rounded-full object-cover">
          <span class="text-base text-text_color font-medium">
            cafest_vantan
          </span>
        </div>
        <span class="text-sm text-placeholder">
          2025/12/13
        </span>
      </div>

      {{-- 星 --}}
      <div class="flex items-center gap-1">
        <x-icons.star class="w-5 h-5 text-yellow-400" />
        <x-icons.star class="w-5 h-5 text-yellow-400" />
        <x-icons.star class="w-5 h-5 text-gray-300" />
        <x-icons.star class="w-5 h-5 text-gray-300" />
        <x-icons.star class="w-5 h-5 text-gray-300" />
      </div>

      {{-- 本文 --}}
      <div class="space-y-2">
        <div class="text-lg text-text_color">本文</div>
        <div class="w-full rounded-lg p-3 bg-form text-text_color text-base shadow-[0_1px_4px_rgba(0,0,0,0.20)] focus:outline-none focus:ring-1  focus:border-main">
          可愛くておしゃれで映え写真をいっぱい撮ることができました。<br>
          店員さんも対応も良くて、また行きたいです。
        </div>
      </div>

      {{-- タグ --}}
      <div class="space-y-2">
        <div class="text-lg text-text_color">タグ</div>
        <div class="text-sm text-main">1つ以上選択してください</div>

        <div class="flex flex-wrap gap-2">
          <x-ui.tag>推し活</x-ui.tag>
        </div>
      </div>

      {{-- 写真 --}}
      <div class="space-y-2">
        <div class="text-lg text-text_color">写真</div>
        <div class="text-sm text-main">
          写真があれば追加してください（最大8枚）
        </div>

        <div class="rounded-lg overflow-hidden border-2 border-main h-48"></div>
      </div>

      <div class="space-y-6">
        <x-ui.button type="submit" class="w-full">
          保存する
        </x-ui.button>

        <button type="submit" class="mx-auto block h-12 w-full
            rounded-full border-2 border-main bg-base
            text-[18px] text-text_color
            shadow-[0_4px_10px_rgba(0,0,0,0.18)]">
          削除する
        </button>
      </div>
    </div>
  </div>

</div>
@endsection 