@extends('layouts.app')
@section('title','ログイン')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-base_color relative overflow-hidden">
    <div class="w-full max-w-md px-4">
        <a href="{{ route('welcome') }}" class="fixed top-4 left-4 z-50 p-2">
            <x-icons.back class="w-5 h-5 text-text" />
        </a>
        <div class="mb-10 flex flex-col items-center">
            <x-icons.store size="80" stroke="1" class="text-text mb-2" />
            <h1 class="text-center text-text text-2xl">
                新規登録   
            </h1>
        </div>

        <form method="POST" action="{{ route('store.signup') }}" autocomplete="off">
        @csrf
        <!-- ダミー入力：ブラウザ自動補完を抑止 -->
        <input type="text" name="__fake_user" autocomplete="username" style="position:absolute;left:-9999px;top:-9999px;" tabindex="-1" />
        <input type="password" name="__fake_pass" autocomplete="new-password" style="position:absolute;left:-9999px;top:-9999px;" tabindex="-1" />

        <div class="space-y-4">
            {{-- email --}}
                <div class="space-y-1">
                <x-ui.label for="email">メールアドレス</x-ui.label>
                <x-ui.input id="email" type="email" name="email" placeholder="メールアドレスを入力" autocomplete="email" />
            </div>

            {{-- password --}}
            <div class="space-y-1">
                <x-ui.label for="password">パスワード</x-ui.label>
                <div class="relative">
                <x-ui.input
                    id="password"
                    type="password"
                    name="password"
                    class="pr-10"
                    placeholder="パスワードを入力"
                    autocomplete="new-password"
                />
                <x-icons.eye target="#password"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-placeholder" />
                </div>
            </div>

            {{-- password confirmation --}}
            <div class="space-y-1">
                <x-ui.label for="password_confirmation">パスワード（確認）</x-ui.label>
                <div class="relative">
                <x-ui.input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="pr-10"
                    placeholder="パスワードを入力"
                    autocomplete="new-password"
                />
                <x-icons.eye target="#password_confirmation"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-placeholder" />
                </div>
            </div>

            {{-- button --}}
            <div class="pt-8">
                <x-ui.button type="submit" theme="store" class="w-full text-form">
                    新規登録
                </x-ui.button>
            </div>
        </form>
        @if ($errors->any())
        <div class="text-notification mb-4">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="mt-4 text-center text-sm text-text">
            ログインの方は
            <a href="{{ route('store.login') }}"  class="text-main2 hover:underline">    
                こちら
            </a>
        </div>
    </div>
</div>