@extends('layouts.app')
@section('title','スライド写真')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full flex flex-col">

    <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.image') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            ギャラリー
          </h1>

          <div></div>
        </div>
      </div>
    </header>

    @php
      $images = $store->galleryImages->take(6)->values();
    @endphp

    <div class="flex-1 overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
      <div class="w-full max-w-md mx-auto px-5 pt-6 pb-28 space-y-6">

        <div class="text-center">
          <div class="text-sm text-text_color">
            店舗の雰囲気が伝わる写真を登録できます（6枚まで）
          </div>
        </div>

        <div x-data="{ open:false, targetId:null }">

          <div class="space-y-6">
            @if ($images->isEmpty())
                <div class="text-center text-sm text-placeholder">
                    まだ画像が登録されていません
                </div>
            @else
              @foreach ($images as $img)
                <div class="relative">
                    <div class="overflow-hidden bg-base_color">
                        <img
                        src="{{ asset(ltrim($img->path,'/')) }}"
                        class="w-full aspect-square object-cover"
                        alt=""
                        >
                    </div>

                    <button
                        type="button"
                        @click="open=true; targetId={{ $img->id }}"
                        class="absolute -top-3 -right-3 flex items-center justify-center
                            w-[30px] h-[30px] rounded-full bg-accent shadow-sm"
                    >
                    <x-icons.close class="w-6 h-6 text-text_color translate-x-[2px] translate-y-[2px]" />
                  </button>
                </div>
              @endforeach
            @endif
          </div>

          <form method="POST" action="{{ route('store.image.gallery.upload') }}" enctype="multipart/form-data">
            @csrf
            <input x-ref="file" type="file" name="image" accept="image/*" class="hidden"
              @change="$event.target.form.submit()"
            >
            @if ($images->count() < 6)
              <button type="button"
                class="w-full text-center text-text_color text-base mt-6"
                @click="$refs.file.click()"
              >
                + 画像を追加する
              </button>
            @endif
          </form>

          <form id="gallerySaveForm" method="POST" action="{{ route('store.image.update.gallery') }}" class="hidden">
            @csrf
            @method('PATCH')

            @foreach ($images as $img)
              <input type="hidden" name="image_ids[]" value="{{ $img->id }}">
            @endforeach
          </form>

          <form x-ref="deleteForm" method="POST" action="{{ route('store.image.gallery.delete') }}" class="hidden">
            @csrf
            @method('DELETE')
            <input type="hidden" name="image_id" :value="targetId">
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
    </div>

    <div class="fixed inset-x-0 bottom-0 bg-base_color">
      <div class="pb-[env(safe-area-inset-bottom)]">
        <div class="w-full max-w-md mx-auto px-4 py-4">
          <x-ui.button
            type="submit"
            form="gallerySaveForm"
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
