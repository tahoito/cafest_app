@extends('layouts.app')
@section('title','おすすめのメニュー3つ')

@section('hideNavbar')
@endsection

@section('content')
@php
  $imgUrl = \App\Support\MediaUrl::from(data_get($recommendedItem, 'image'));
@endphp

<div class="h-screen bg-base_color">
  <div class="h-full flex flex-col">

    {{-- header --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.menu') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            おすすめのメニュー
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    {{-- body --}}
    <div class="flex-1 overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
      <div class="w-full max-w-md mx-auto px-5 pt-6 pb-28 space-y-6" x-data="{ open:false }">

        {{-- 画像（アップロード専用フォーム） --}}
        <div class="space-y-2">
          <x-ui.label>画像</x-ui.label>

          <div class="flex justify-center">
            <div class="relative w-[200px]">
              @if($imgUrl)
                <img
                  src="{{ $imgUrl }}"
                  class="w-full aspect-square object-cover rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.12)]"
                  alt=""
                >

                {{-- 削除（別フォーム submit） --}}
                <button
                  type="button"
                  @click="open=true"
                  class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-accent shadow grid place-items-center"
                >
                  <x-icons.close class="w-5 h-5 text-text_color translate-x-[1px]" />
                </button>
              @else
                <form
                  method="POST"
                  action="{{ route('store.menu.recommended.image.upload', $recommendedItem->id) }}"
                  enctype="multipart/form-data"
                >
                  @csrf
                  <input
                    x-ref="file"
                    type="file"
                    name="image"
                    accept="image/*"
                    class="hidden"
                    @change="$event.target.form.submit()"
                  >

                  <button
                    type="button"
                    class="w-full aspect-square flex flex-col items-center justify-center gap-2
                      rounded-lg border border-dashed border-main2 text-placeholder"
                    @click="$refs.file.click()"
                  >
                    <span class="text-sm">＋画像を追加</span>
                  </button>
                </form>
              @endif
            </div>
          </div>
        </div>

        {{-- テキスト保存フォーム（画像とは分離） --}}
        <form id="recommendedSaveForm" method="POST"
          action="{{ route('store.menu.recommended.update', $recommendedItem->id) }}"
        >
          @csrf
          @method('PATCH')

          <div class="space-y-6">
            {{-- メニュー名 --}}
            <div class="space-y-1">
              <x-ui.label for="name">メニュー名</x-ui.label>
              <x-ui.input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $recommendedItem->name) }}"
                placeholder="メニュー名を入力"
                required
              />
            </div>

            {{-- 価格 --}}
            <div class="space-y-1">
              <x-ui.label for="price">価格（税込）</x-ui.label>
              <x-ui.input
                id="price"
                type="number"
                name="price"
                placeholder="650"
                value="{{ old('price', $recommendedItem->price) }}"
                min="0"
              />
            </div>

            {{-- 説明 --}}
            <div class="space-y-1">
              <x-ui.label for="description">説明</x-ui.label>
              <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="メニューの特徴やおすすめポイントを入力"
                class="w-full rounded-xl bg-form px-3 py-2 text-text_color shadow-[0_1px_4px_rgba(0,0,0,0.20)]
                  focus:outline-none focus:ring-1 focus:ring-main"
              >{{ old('description', $recommendedItem->description) }}</textarea>
            </div>
          </div>
        </form>

        {{-- 画像削除モーダル --}}
        <form x-ref="deleteForm" method="POST" action="{{ route('store.menu.recommended.image.delete', $recommendedItem->id) }}" class="hidden">
          @csrf
          @method('DELETE')
        </form>

        <div
          x-show="open"
          x-cloak
          class="fixed inset-0 z-[999] flex items-center justify-center"
          @keydown.escape.window="open=false"
        >
          <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

          <div class="relative w-[calc(100%-48px)] max-w-sm rounded-lg bg-base_color shadow-lg p-6">
            <button type="button" class="absolute left-4 top-4" @click="open=false">
              <x-icons.close class="w-6 h-6 text-text_color translate-x-[1px]" />
            </button>

            <div class="text-center text-text_color text-lg font-medium pt-4">
              画像を削除しますか？
            </div>

            <div class="mt-6 flex flex-col items-center gap-4">
              <x-ui.button
                type="button"
                theme="store"
                class="w-full text-form"
                @click="$refs.deleteForm.submit()"
              >
                削除する
              </x-ui.button>

              <button type="button" class="text-text_color" @click="open=false">
                キャンセル
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- footer --}}
    <div class="fixed inset-x-0 bottom-0 bg-base_color">
      <div class="pb-5">
        <div class="w-full max-w-md mx-auto px-4 py-4">
          <x-ui.button
            type="submit"
            form="recommendedSaveForm"
            theme="store"
            class="w-full text-form"
          >
            保存
          </x-ui.button>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
