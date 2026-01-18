@extends('layouts.app')
@section('title','連絡情報')

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
                    連絡情報
                </h1>
                </div>
            </div>
        </header>

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                <section class="px-4">
                    <form method="POST" action="{{ route('store.profile.update.contact') }}">
                        @csrf 
                        @method('PATCH')

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <x-ui.label for="mail">メールアドレス</x-ui.label>
                                <x-ui.input
                                    id="name"
                                    type="mail"
                                    name="mail"
                                    placeholder="メールアドレスを入力"
                                    value="{{ old('mail', $store->mail) }}"
                                    required
                                />
                            </div>
                    
                            <div class="space-y-1">
                                <x-ui.label for="store">電話番号</x-ui.label>
                                <x-ui.input
                                    id="phone"
                                    type="number"
                                    name="phone"
                                    placeholder="電話番号を入力"
                                    value="{{ old('phone', $store->phone) }}"
                                    required
                                />
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
@endsection