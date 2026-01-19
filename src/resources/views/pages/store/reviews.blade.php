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
            <div class="rounded-2xl bg-form p-3 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
                <div class="space-y-4">
                    <div class="grid grid-cols-[96px_auto] items-center gap-3">
                        <div class="text-base text-text_color font-medium">平均評価</div>
                        <div class="text-base text-text_color">レビューのほし</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4">

        </section>

        <section class="px-4">

        </section>
    </div>
</div>
@endsection