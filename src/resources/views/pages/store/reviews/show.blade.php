@extends('layouts.app')
@section('title','レビュー詳細')

@section('hideNavbar')
@endsection

@section('content')
@endsection

<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.reviews') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                </div>
            </div>
        </header>

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">

        </div>
    </div>
</div>
