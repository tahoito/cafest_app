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
                                <x-ui.label for="email">メールアドレス</x-ui.label>
                                <x-ui.input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="メールアドレスを入力"
                                    value="{{ old('email', $store->email) }}"
                                    required
                                />
                            </div>
                    
                            <div class="space-y-1">
                                <x-ui.label for="store">電話番号</x-ui.label>
                                <x-ui.input
                                    id="phone"
                                    type="tel"
                                    name="phone"
                                    placeholder="電話番号を入力"
                                    value="{{ old('phone', $store->phone) }}"
                                    required
                                />
                            </div>

                            <div class="space-y-3">
                                <div class="text-lg text-text_color font-medium">SNSアカウント</div>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <x-icons.instagram class="h-7 w-7 text-text_color" />
                                        <input
                                            type="url"
                                            name="sns[instagram]"
                                            placeholder="https://www.instagram.com/your_cafe"
                                            value="{{ old('sns.instagram', $sns['instagram'] ?? '') }}"
                                            class="w-full bg-transparent border-b border-line px-0 py-2 text-sm text-text_color placeholder:text-placeholder focus:outline-none focus:border-main transition"
                                        />
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icons.website class="h-7 w-7 text-text_color" />
                                        <input
                                            type="url"
                                            name="sns[website]"
                                            placeholder="https://yourcafe.com"
                                            value="{{ old('sns.website', $sns['website'] ?? '') }}"
                                            class="w-full bg-transparent border-b border-line px-0 py-2 text-sm text-text_color placeholder:text-placeholder focus:outline-none focus:border-main transition"
                                        />
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icons.tiktok class="h-7 w-7 text-text_color" />
                                        <input
                                            type="url"
                                            name="sns[tiktok]"
                                            placeholder="https://www.tiktok.com/@your_cafe"
                                            value="{{ old('sns.tiktok', $sns['tiktok'] ?? '') }}"
                                            class="w-full bg-transparent border-b border-line px-0 py-2 text-sm text-text_color placeholder:text-placeholder focus:outline-none focus:border-main transition"
                                        />
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icons.x class="h-7 w-7 text-text_color" />
                                        <input
                                            type="url"
                                            name="sns[x]"
                                            placeholder="https://x.com/your_cafe"
                                            value="{{ old('sns.x', $sns['x'] ?? '') }}"
                                            class="w-full bg-transparent border-b border-line px-0 py-2 text-sm text-text_color placeholder:text-placeholder focus:outline-none focus:border-main transition"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="pt-8">
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