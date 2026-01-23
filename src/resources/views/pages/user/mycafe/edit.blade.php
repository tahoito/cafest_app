@extends('layouts.app')
@section('title','プロフィール編集')

@section('content')

<!-- プロフィール編集（ベタ書き） -->
<div class="min-h-screen bg-base text-text">

  <!-- ヘッダー -->
        <a class="p-6" href="{{ url()->previous() }}">
          <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <div class="text-center text-text_color text-lg">
          プロフィール編集
        </div>


  <!-- コンテンツ -->
  <div class="px-5 pt-4 space-y-6">

    <!-- アイコン -->
    <div class="flex justify-center">
      <div class="relative w-[260px] rounded-2xl border border-line bg-form p-3">
        <div class="aspect-square rounded-xl overflow-hidden">
          <img
            src="/images/user1.jpg"
            alt="user icon"
            class="w-full h-full object-cover"
          >
        </div>

        <!-- ＋ボタン -->
        <a class="p-2" href="{{ url()->previous() }}">
          <x-icons.plus class="w-5 h-5 text-accent" />
        </a>
      </div>
    </div>


          <div class="space-y-10">
            {{-- username --}}
            <div class="space-y-1">
            <x-ui.label for="username">ユーザー名</x-ui.label>
            <x-ui.input
                id="username"
                type="text"
                name="username"
                placeholder="ユーザー名を入力"
                required
                autocomplete="off"
            />
            </div>

            {{-- email --}}
            <div class="space-y-1">
            <x-ui.label for="email">メールアドレス</x-ui.label>
            <div class="relative">
                <x-ui.input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="メールアドレスを入力"
                    class="pr-10"
                    autocomplete="new-password"
                />
            </div>

    <!-- 保存ボタン -->
    <div class="pt-10">
      <x-ui.button type="submit" class="w-full">
        保存
      </x-ui.button>
    </div>

  </div>
</div>
@endsection