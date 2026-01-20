@extends('layouts.app')
@section('title','おすすめのメニュー3つ')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full flex flex-col">

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


    <div class="flex-1 overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
        <div class="w-full max-w-md mx-auto px-5 pt-6 pb-28 space-y-6">


            <div class="w-full max-w-md mx-auto px-5 pt-6 pb-28 space-y-6">
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

            
                <div class="space-y-1">
                    <x-ui.label for="password">価格（税込）</x-ui.label>
                    <div class="relative">
                        <x-ui.input
                            id="price"
                            type="number"
                            name="price"
                            placeholder="650"
                            value="{{ old('price',$recommendedItem->price) }}"
                            min="0"
                        />
                    </div>


                    <div class="space-y-1">
                        <x-ui.label for="password">説明</x-ui.label>
                        <textarea id="description" name="description"
                            rows="4" placeholder="メニューの特徴やおすすめポイントを入力"
                            class="w-full rounded-lg border border-form bg-form px-3 py-2 text-text_color placeholder:text-placeholder focus:outline-none focus:ring-2 focus:ring-main"
                        >{{ old('description', $recommendedItem->description) }}</textarea>
                    </div>
                </div>

     
    
                <div class="fixed inset-x-0 bottom-0 bg-base_color">
                    <div class="pb-[env(safe-area-inset-bottom)]">
                        <div class="w-full max-w-md mx-auto px-4 py-4">
                        <x-ui.button
                            type="submit"
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
