@extends('layouts.app')
@section('title','店舗紹介')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.profile') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    店舗紹介
                </h1>
                </div>
            </div>
        </header>

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                <section class="px-4">
                    <form method="POST" action="{{ route('store.profile.edit.description') }}"
                        x-data="{ text: @js(old('description', $store->description ?? ''">
                        @csrf 
                        @method('PATCH')

                        <div class="text-text_color text-lg">
                            カフェの雰囲気が伝わる文章を書いてください
                        </div>

                        <div class="pt-4 space-y-2">
                            <textarea id="description" name="description" x-model="text" maxlength="200"
                                rows="6" class="w-full rounded-xl bg-form px-4 py-4 text-text_color shadow-[0_1px_4px_rgba(0,0,0,0.20)] border border-transparent"
                                placeholder="静かな店内で、電源・wifi完備。自家焙煎コーヒーと季節のスイーツが人気です。"></textarea>

                            <div class="flex items-center justify-between text-xs">
                                <p class="text-placeholder">改行OK・絵文字OK</p>
                                <p :class="text.length > max ? 'text-notification' : 'text-placeholder'">
                                    <span x-text="text.length"></span>/<span x-text="max"></span>
                                </p>
                            </div>
                        </div>


                        <div class="pt-4">
                            <x-ui.button type="submit" theme="store" class="w-full text-form">
                                保存
                            </x-ui.button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>