@extends('layouts.app')
@section('title','メニュー管理')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.top') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    メニュー管理
                </h1>
                </div>
            </div>
        </header>


        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
               <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="text-lg text-text_color">メニュー表画像（最大3枚まで）</div>
                        <a href="{{ route('store.menu.edit.image') }}"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>
                    <div class="rounded-lg border border-favorite bg-base_color p-3">
                        @if ($menuPhotos->isEmpty())
                            <p class="text-sm text-placeholder text-center">
                                メニュー画像はまだありません
                            </p>
                        @else
                            <div class="space-y-3">
                                @foreach ($menuPhotos as $photo)
                                    <div class="aspect-[3/4] overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                            alt="メニュー画像"
                                            class="w-full h-full object-cover">
                                    </div>
                                @endforeach    
                            </div>
                        @endif
                    </div>
                </section>

                <section class="px-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="text-lg text-text_color">おすすめのメニュー3つ</div>
                    </div>
                    <div class="space-y-3">
                        @forelse($recommendedItems as $item)
                            <x-ui.card.store.menu-item 
                                :name="$item->name" 
                                :price="$item->price" 
                                :description="$item->description" 
                                :imageUrl="asset('storage/'. $item->photo_path)"
                                />
                        @empty
                            <div class="rounded-lg border border-favorite bg-base_color p-3">
                                <p class="text-sm text-placeholder text-center">
                                    おすすめメニューはまだありません
                                </p>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>
        </div>            
    </div>
</div>
@endsection