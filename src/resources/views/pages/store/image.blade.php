@extends('layouts.app')
@section('title','公式写真')

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
                    公式写真
                </h1>
                </div>
            </div>
        </header>


        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg text-text_color">スライド画像（5枚まで）</div>
                            <div class="text-sm text-text_color">店舗のトップに表示されます</div>
                        </div>
                        <a href="#"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>

                    <div class="rounded-2xl border border-favorite bg-base_color p-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="overflow-hidden rounded-xl border border-placeholder-color/40 bg-white">
                                <img src="/images/store/card.png" alt="" class="w-full aspect-[4/3] object-cover">
                            </div>

                            <div class="overflow-hidden rounded-xl border border-placeholder-color/40 bg-white">
                                <img src="/images/store/card.png" alt="" class="w-full aspect-[4/3] object-cover">
                            </div>

                            <div class="overflow-hidden rounded-xl border border-placeholder-color/40 bg-white">
                                <img src="/images/store/card.png" alt="" class="w-full aspect-[4/3] object-cover">
                            </div>

                            <a href="#" class="grid place-items-center rounded-xl border border-placeholder bg-notification2 aspect-[16/7]">
                                <x-icons.no_image class="w-6 h-6 text-text_color" />
                            </a>
                        </div>
                    </div>
                </section>

                <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg text-text_color">ギャラリー画像（6枚まで）</div>
                            <div class="text-sm text-text_color">店舗のトップに表示されます</div>
                        </div>
                        <a href="#"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>

                </section>
            </div>
        </div>            
    </div>
</div>
@endsection