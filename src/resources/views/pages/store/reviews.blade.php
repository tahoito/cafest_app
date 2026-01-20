@extends('layouts.app')
@section('title','レビュー一覧')

@section('content')
@section('hideNavbar')
@endsection
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto overscroll-contain">
    {{-- header --}}
        <header class="sticky top-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.top') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    レビュー一覧
                </h1>
                <div></div>
                </div>
            </div>
        </header>

        <section class="px-4">
            <div class="rounded-2xl bg-form p-4 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
                <div class="space-y-4">
                    <div class="grid grid-cols-[96px_1fr] items-center gap-3">
                        <div class="text-text_color text-base font-medium">平均評価</div>

                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <x-icons.star class="h-6 w-6" />
                                <x-icons.star class="h-6 w-6" />
                                <x-icons.star class="h-6 w-6" />
                                <x-icons.star class="h-6 w-6" />
                                <x-icons.star class="h-6 w-6" />
                            </div>
                            <div class="text-text_color text-base font-medium">(3.0)</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-[96px_1fr] items-center gap-3">
                        <div class="text-text_color text-base font-medium">レビュー数</div>
                        <div class="text-text_color text-base">100件</div>
                    </div>

                    <div class="grid grid-cols-[96px_1fr] items-center gap-3">
                        <div class="text-text_color text-base font-medium">今週の新規</div>
                        <div class="text-text_color text-base">10件</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="space-y-4 items-center">
            @foreach($reviews as $review)
                <x-ui.card.store.review :review="$review" />
            @endforeach
        </section>


        <section class="px-4">

        </section>
    </div>
</div>
@endsection